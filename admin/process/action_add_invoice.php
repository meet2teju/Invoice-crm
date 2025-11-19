<?php
session_start();
include '../../config/config.php';

function uploadFile($file, $uploadDir) {
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $fileName;
    }
    return null;
}

if (isset($_POST['submit'])) {
    $currentUserId = $_SESSION['user_id'] ?? 1;
    $orgId = $_SESSION['org_id'] ?? 1;

    mysqli_begin_transaction($conn);

    try {
        // Sanitize inputs
        $client_id     = (int)($_POST['client_id'] ?? 0);
        
        // Make project_id optional - set to NULL if empty/0
        $project_id_raw = $_POST['project_id'] ?? '';
        $project_id_sql = ($project_id_raw !== '' && is_numeric($project_id_raw) && $project_id_raw > 0) ? (int)$project_id_raw : 'NULL';
        
        $invoice_id    = mysqli_real_escape_string($conn, $_POST['invoice_id'] ?? '');
        $reference_name= mysqli_real_escape_string($conn, $_POST['reference_name'] ?? '');
        $invoice_date  = mysqli_real_escape_string($conn, $_POST['invoice_date'] ?? '');
        $expiry_date   = mysqli_real_escape_string($conn, $_POST['due_date'] ?? '');
        $order_number  = (int)($_POST['order_number'] ?? 0);
        $item_type     = (int)($_POST['item_type'] ?? 0);
        $user_id       = (int)($_POST['user_id'] ?? 0);
        $gst_type      = mysqli_real_escape_string($conn, $_POST['gst_type'] ?? 'gst');

        $bank_id_raw   = $_POST['bank_id'] ?? '';
        $bank_id_sql   = ($bank_id_raw !== '' && is_numeric($bank_id_raw)) ? (int)$bank_id_raw : 'NULL';

        $invoice_note  = mysqli_real_escape_string($conn, $_POST['invoice_note'] ?? '');
        $description   = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
        $amount        = (float)($_POST['sub_amount'] ?? 0);
        $tax_amount    = (float)($_POST['tax_amount'] ?? 0);
        $shipping_charge= (float)($_POST['shipping_charge'] ?? 0);
        $total_amount  = (float)($_POST['total_amount'] ?? 0);

        // Insert invoice (with optional project_id and gst_type)
        $query = "INSERT INTO invoice (
            client_id, project_id, invoice_id, reference_name, invoice_date, due_date,
            order_number, item_type, user_id, bank_id, gst_type,
            invoice_note, description, amount, tax_amount, shipping_charge, total_amount,
            org_id, is_deleted, created_by, updated_by
        ) VALUES (
            '$client_id', $project_id_sql, '$invoice_id', '$reference_name', '$invoice_date', '$expiry_date',
            '$order_number', '$item_type', '$user_id', $bank_id_sql, '$gst_type',
            '$invoice_note', '$description', '$amount', '$tax_amount', '$shipping_charge', '$total_amount',
            '$orgId', 0, '$currentUserId', '$currentUserId'
        )";

        if (!mysqli_query($conn, $query)) {
            throw new Exception("Invoice insert failed: " . mysqli_error($conn));
        }

        $invoiceId = mysqli_insert_id($conn);

        // Insert selected tasks into invoice_tasks table (optional)
        if (isset($_POST['task_id']) && is_array($_POST['task_id']) && !empty($_POST['task_id'][0])) {
            foreach ($_POST['task_id'] as $taskId) {
                $taskId = (int)$taskId;
                if ($taskId > 0) {
                    $taskQuery = "INSERT INTO invoice_tasks (invoice_id, task_id, created_by, updated_by) 
                                  VALUES ('$invoiceId', '$taskId', '$currentUserId', '$currentUserId')";
                    if (!mysqli_query($conn, $taskQuery)) {
                        throw new Exception("Task insert failed: " . mysqli_error($conn));
                    }
                }
            }
        }

        // Multiple document uploads
        if (!empty($_FILES['document']['name'][0])) {
            foreach ($_FILES['document']['tmp_name'] as $key => $tmpName) {
                if (!empty($_FILES['document']['name'][$key])) {
                    $document = [
                        'name'     => $_FILES['document']['name'][$key],
                        'type'     => $_FILES['document']['type'][$key],
                        'tmp_name' => $tmpName,
                        'error'    => $_FILES['document']['error'][$key],
                        'size'     => $_FILES['document']['size'][$key]
                    ];

                    $docFileName = uploadFile($document, '../../uploads/');
                    if ($docFileName) {
                        $docQuery = "INSERT INTO invoice_document (invoice_id, document, created_by, updated_by)
                                     VALUES ('$invoiceId', '$docFileName', '$currentUserId', '$currentUserId')";
                        if (!mysqli_query($conn, $docQuery)) {
                            throw new Exception("Document insert failed: " . mysqli_error($conn));
                        }
                    }
                }
            }
        }

        // Insert invoice items
        if (isset($_POST['item_id'])) {
            foreach ($_POST['item_id'] as $index => $item_id) {
                $item_id      = $_POST['item_id'][$index] ?? '';
                $quantity     = (float)($_POST['quantity'][$index] ?? 0);
                // $code         = mysqli_real_escape_string($conn, $_POST['code'][$index] ?? '');
                $selling_price= (float)($_POST['selling_price'][$index] ?? 0);
                $tax_id       = $_POST['tax_id'][$index] ?? '';
                // $tax_name     = mysqli_real_escape_string($conn, $_POST['tax_name'][$index] ?? '');
                $rate         = (float)($_POST['rate'][$index] ?? 0);
                $item_amount  = (float)($_POST['amount'][$index] ?? 0);
                $service_name = mysqli_real_escape_string($conn, $_POST['service_name'][$index] ?? '');

                $item_id_sql = ($item_id === '' ? 'NULL' : (int)$item_id);
                $tax_id_sql  = ($tax_id === '' ? 'NULL' : (int)$tax_id);

                // Handle both products and services
                if (!empty($item_id) || !empty($service_name)) {
                    $itemInsertQuery = "INSERT INTO invoice_item (
                        invoice_id, quantity, product_id, selling_price,
                        tax_id,rate, amount, service_name, org_id, is_deleted, created_by, updated_by
                    ) VALUES (
                        '$invoiceId', '$quantity', $item_id_sql, '$selling_price',
                        $tax_id_sql, '$rate', '$item_amount', '$service_name', '$orgId', 0, '$currentUserId', '$currentUserId'
                    )";

                    if (!mysqli_query($conn, $itemInsertQuery)) {
                        throw new Exception("Item insert failed: " . mysqli_error($conn));
                    }
                }
            }
        }

        // Commit everything
        mysqli_commit($conn);
        $_SESSION['message'] = "Invoice added successfully!";
        $_SESSION['message_type'] = "success";
        header("Location: ../invoices.php");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
}
?>