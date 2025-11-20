<?php
include '../../config/config.php';
session_start();

// ---------------- Helper Functions ----------------
function dbString($conn, $value) {
    return mysqli_real_escape_string($conn, trim($value ?? ''));
}
function unformat($value) {
    return (float)str_replace(['$', ',',' '], '', $value);
}
function dbInt($value) {
    return (is_numeric($value) && $value !== '') ? (int)$value : 0;
}

function dbFloat($value) {
    return (is_numeric($value) && $value !== '') ? (float)$value : 0;
}

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

// ---------------- Main Logic ----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $currentUserId = $_SESSION['crm_user_id'] ?? 1;
    $orgId = $_SESSION['org_id'] ?? 1;
    $quotation_id = dbInt($_POST['id'] ?? 0);

    mysqli_begin_transaction($conn);

    try {
        // === Sanitize Inputs ===
        $client_id       = dbInt($_POST['client_id']);
        $reference_name  = dbString($conn, $_POST['reference_name'] ?? '');
        $quotation_date  = dbString($conn, $_POST['quotation_date']);
        $expiry_date     = dbString($conn, $_POST['expiry_date']);
        $item_type       = dbInt($_POST['item_type'] ?? 1);
        $user_id         = dbInt($_POST['user_id'] ?? 0);
        $project_id      = dbInt($_POST['project_id'] ?? 0);
        $client_note     = dbString($conn, $_POST['client_note'] ?? '');
        $description     = dbString($conn, $_POST['description'] ?? '');
        $amount          = dbFloat($_POST['sub_amount'] ?? 0);
        $tax_amount      = dbFloat($_POST['tax_amount'] ?? 0);
        $shipping_charge = unformat($_POST['shipping_charge'] ?? 0);
        $total_amount    = dbFloat($_POST['total_amount'] ?? 0);
        
        // === GST Type field ===
        $gst_type        = dbString($conn, $_POST['gst_type'] ?? 'gst');

        // === 1. Update quotation main record ===
        $update_quotation = "UPDATE quotation SET
            client_id = '$client_id',
            reference_name = '$reference_name',
            quotation_date = '$quotation_date',
            expiry_date = '$expiry_date',
            item_type = '$item_type',
            user_id = '$user_id',
            project_id = '$project_id',
            client_note = '$client_note',
            description = '$description',
            amount = '$amount',
            tax_amount = '$tax_amount',
            shipping_charge = '$shipping_charge',
            total_amount = '$total_amount',
            gst_type = '$gst_type',
            updated_by = '$currentUserId'
            WHERE id = '$quotation_id'";

        if (!mysqli_query($conn, $update_quotation)) {
            throw new Exception("Failed to update quotation: " . mysqli_error($conn));
        }

        // === 2. Mark old items deleted ===
        $mark_deleted = "UPDATE quotation_item SET is_deleted = 1 WHERE quotation_id = '$quotation_id'";
        if (!mysqli_query($conn, $mark_deleted)) {
            throw new Exception("Failed to mark old items as deleted: " . mysqli_error($conn));
        }

        // === 3. Insert/update quotation items - UPDATED FOR PRODUCTS & SERVICES SEPARATION ===
        if (isset($_POST['item_id']) && is_array($_POST['item_id'])) {
            foreach ($_POST['item_id'] as $index => $item_id) {
                $item_id       = dbInt($item_id);
                $service_name  = dbString($conn, $_POST['service_name'][$index] ?? '');
                $quantity      = dbFloat($_POST['quantity'][$index] ?? 0);
                $selling_price = unformat($_POST['selling_price'][$index] ?? 0);
                $tax_id        = dbInt($_POST['tax_id'][$index] ?? 0);
                $rate          = dbFloat($_POST['rate'][$index] ?? 0);
                $item_amount   = unformat($_POST['amount'][$index] ?? 0);
                $code          = dbString($conn, $_POST['code'][$index] ?? '');
                $item_type_row = $_POST['item_type_row'][$index] ?? 'product';

                // Initialize product_id and service_id based on your new logic
                $product_id_sql = 'NULL';
                $service_id_sql = 'NULL';
                $service_name_sql = 'NULL';

                // Determine whether it's a product or service and set appropriate values
                if ($item_type_row === 'product' && !empty($item_id)) {
                    // This is a product - store in product_id
                    $product_id_sql = $item_id;
                } else if ($item_type_row === 'service') {
                    if (!empty($item_id)) {
                        // This is a service selected from dropdown - store in service_id
                        $service_id_sql = $item_id;
                        // Also store the service name for reference
                        $service_name_sql = "'" . dbString($conn, $_POST['service_name'][$index] ?? '') . "'";
                    } else if (!empty($service_name)) {
                        // This is a custom service (no dropdown selection) - store in service_name
                        $service_name_sql = "'$service_name'";
                    }
                }

                // Skip if both product and service are empty
                if ($product_id_sql === 'NULL' && $service_id_sql === 'NULL' && $service_name_sql === 'NULL') {
                    continue;
                }

                $tax_id_sql = ($tax_id === 0) ? 'NULL' : $tax_id;

                // Check if item already exists (using your exact logic)
                if ($item_type_row === 'product' && !empty($item_id)) {
                    $check_item = "SELECT id FROM quotation_item 
                                   WHERE quotation_id = '$quotation_id' 
                                   AND product_id = '$product_id_sql'
                                   LIMIT 1";
                } else if ($item_type_row === 'service') {
                    if (!empty($item_id)) {
                        // Service from dropdown - check by service_id
                        $check_item = "SELECT id FROM quotation_item 
                                       WHERE quotation_id = '$quotation_id' 
                                       AND service_id = '$service_id_sql'
                                       LIMIT 1";
                    } else if (!empty($service_name)) {
                        // Custom service - check by service_name
                        $check_item = "SELECT id FROM quotation_item 
                                       WHERE quotation_id = '$quotation_id' 
                                       AND service_name = '$service_name'
                                       LIMIT 1";
                    } else {
                        continue; // Skip if no service identifier
                    }
                } else {
                    continue; // Skip invalid items
                }
                
                $item_exists = mysqli_query($conn, $check_item);

                if ($item_exists && mysqli_num_rows($item_exists) > 0) {
                    // UPDATE existing item
                    if ($item_type_row === 'product' && !empty($item_id)) {
                        $update_item = "UPDATE quotation_item SET
                            quantity = '$quantity',
                            selling_price = '$selling_price',
                            tax_id = $tax_id_sql,
                            rate = '$rate',
                            amount = '$item_amount',
                            is_deleted = 0
                            WHERE quotation_id = '$quotation_id' 
                            AND product_id = '$product_id_sql'";
                    } else if ($item_type_row === 'service') {
                        if (!empty($item_id)) {
                            // Service from dropdown - update by service_id
                            $update_item = "UPDATE quotation_item SET
                                quantity = '$quantity',
                                selling_price = '$selling_price',
                                tax_id = $tax_id_sql,
                                rate = '$rate',
                                amount = '$item_amount',
                                service_name = $service_name_sql,
                                is_deleted = 0
                                WHERE quotation_id = '$quotation_id' 
                                AND service_id = '$service_id_sql'";
                        } else if (!empty($service_name)) {
                            // Custom service - update by service_name
                            $update_item = "UPDATE quotation_item SET
                                quantity = '$quantity',
                                selling_price = '$selling_price',
                                tax_id = $tax_id_sql,
                                rate = '$rate',
                                amount = '$item_amount',
                                is_deleted = 0
                                WHERE quotation_id = '$quotation_id' 
                                AND service_name = '$service_name'";
                        }
                    }
                    
                    if (!mysqli_query($conn, $update_item)) {
                        throw new Exception("Failed to update item: " . mysqli_error($conn));
                    }
                } else {
                    // INSERT new item
                    $insert_item = "INSERT INTO quotation_item (
                        quotation_id, product_id, service_id, service_name, quantity, 
                        selling_price, tax_id, rate, amount, org_id, 
                        created_by, updated_by
                    ) VALUES (
                        '$quotation_id', 
                        $product_id_sql, 
                        $service_id_sql, 
                        $service_name_sql, 
                        '$quantity',
                        '$selling_price', 
                        $tax_id_sql, 
                        '$rate', 
                        '$item_amount', 
                        '$orgId',
                        '$currentUserId', 
                        '$currentUserId'
                    )";
                    
                    if (!mysqli_query($conn, $insert_item)) {
                        throw new Exception("Failed to insert item: " . mysqli_error($conn));
                    }
                }
            }
        }

        // === 4. Handle document uploads ===
        if (!empty($_FILES['document']['name'][0])) {
            foreach ($_FILES['document']['tmp_name'] as $key => $tmpName) {
                if (!empty($_FILES['document']['name'][$key])) {
                    $document = [
                        'name' => $_FILES['document']['name'][$key],
                        'type' => $_FILES['document']['type'][$key],
                        'tmp_name' => $tmpName,
                        'error' => $_FILES['document']['error'][$key],
                        'size' => $_FILES['document']['size'][$key]
                    ];

                    $docFileName = uploadFile($document, '../../uploads/');
                    if ($docFileName) {
                        $docQuery = "INSERT INTO quotation_document (
                            quotation_id, document, created_by, updated_by
                        ) VALUES (
                            '$quotation_id', '$docFileName', '$currentUserId', '$currentUserId'
                        )";
                        if (!mysqli_query($conn, $docQuery)) {
                            throw new Exception("Document insert failed: " . mysqli_error($conn));
                        }
                    }
                }
            }
        }

        // === 5. Commit transaction ===
        mysqli_commit($conn);
        $_SESSION['message'] = "Quotation updated successfully!";
        $_SESSION['message_type'] = "success";
        header("Location: ../edit-quotation.php?id=" . $quotation_id);
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: ../edit-quotation.php?id=" . $quotation_id);
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request";
    header("Location: ../quotations.php");
    exit();
}
?>