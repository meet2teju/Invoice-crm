<?php
session_start();
include '../../config/config.php';

// ---------------- Helper Functions ----------------
function dbString($conn, $value) {
    return mysqli_real_escape_string($conn, trim($value ?? ''));
}

function unformat($value) {
    if ($value === null || $value === '') {
        return 0;
    }
    
    // Remove all non-numeric characters except decimal point and minus sign
    $cleaned = preg_replace('/[^0-9.-]/', '', $value);
    
    // Handle empty result
    if ($cleaned === '' || $cleaned === null) {
        return 0;
    }
    
    $result = (float)$cleaned;
    return is_nan($result) ? 0 : $result;
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

        // === 3. Insert/update quotation items ===
        $item_count = 0;
        
        // Get all item data
        $item_id_array = $_POST['item_id'] ?? [];
        $item_type_row_array = $_POST['item_type_row'] ?? [];
        $service_name_array = $_POST['service_name'] ?? [];
        $quantity_array = $_POST['quantity'] ?? [];
        $selling_price_array = $_POST['selling_price'] ?? [];
        $tax_id_array = $_POST['tax_id'] ?? [];
        $rate_array = $_POST['rate'] ?? [];
        $amount_array = $_POST['amount'] ?? [];
        
        // Process each row
        $max_rows = count($item_type_row_array);
        
        for ($index = 0; $index < $max_rows; $index++) {
            // Get row type
            $item_type_row = $item_type_row_array[$index] ?? '';
            
            // Get values with proper array indexing
            $item_id = isset($item_id_array[$index]) ? dbInt($item_id_array[$index]) : 0;
            $service_name = isset($service_name_array[$index]) ? dbString($conn, $service_name_array[$index]) : '';
            $quantity = isset($quantity_array[$index]) ? dbFloat($quantity_array[$index]) : 0;
            $selling_price = isset($selling_price_array[$index]) ? unformat($selling_price_array[$index]) : 0;
            $tax_id = isset($tax_id_array[$index]) ? dbInt($tax_id_array[$index]) : 0;
            $rate = isset($rate_array[$index]) ? unformat($rate_array[$index]) : 0;
            $amount = isset($amount_array[$index]) ? unformat($amount_array[$index]) : 0;
            
            // Check if this is an empty/blank row that should be skipped
            $is_empty_row = false;
            
            // Determine if row should be skipped based on type
            if ($item_type_row === 'product') {
                // For products: must have item_id and valid selling price
                if ($item_id <= 0 || $selling_price <= 0) {
                    $is_empty_row = true;
                }
            } else if ($item_type_row === 'service') {
                // For services: must have either service_id OR service_name AND valid selling price
                $has_service_id = ($item_id > 0);
                $has_service_name = (!empty($service_name) && trim($service_name) !== '');
                
                if ((!$has_service_id && !$has_service_name) || $selling_price <= 0) {
                    $is_empty_row = true;
                }
            } else {
                $is_empty_row = true;
            }
            
            if ($is_empty_row) {
                continue;
            }

            // Initialize product_id and service_id
            $product_id_sql = 'NULL';
            $service_id_sql = 'NULL';
            $service_name_sql = 'NULL';

            // Determine whether it's a product or service
            if ($item_type_row === 'product') {
                // This is a product
                $product_id_sql = $item_id;
                
                // Ensure product quantity is at least 1
                if ($quantity <= 0) {
                    $quantity = 1;
                }
            } else if ($item_type_row === 'service') {
                if ($item_id > 0) {
                    // This is a service from dropdown
                    $service_id_sql = $item_id;
                    // Also store the service name
                    $service_name_sql = "'" . dbString($conn, $service_name) . "'";
                } else if (!empty($service_name)) {
                    // This is a custom service
                    $service_name_sql = "'" . dbString($conn, $service_name) . "'";
                }
                
                // For services, quantity can be 0 or empty - no default value
                // Keep the original quantity value (could be 0)
            }

            // Calculate amount if not provided but we have price and quantity
            if ($amount <= 0 && $selling_price > 0) {
                // For services, if quantity is 0 or empty, use 1 for calculation
                $calc_quantity = $quantity;
                if ($item_type_row === 'service' && $quantity <= 0) {
                    $calc_quantity = 1;
                }
                
                $line_subtotal = $selling_price * $calc_quantity;
                $line_tax = $line_subtotal * ($rate / 100);
                $amount = $line_subtotal + $line_tax;
            }
            
            // Final validation
            if ($selling_price <= 0 || $amount <= 0) {
                continue;
            }

            // Set tax_id - if in Non-GST mode, tax_id should be NULL
            $tax_id_sql = 'NULL';
            if ($gst_type !== 'non_gst' && $tax_id > 0) {
                $tax_id_sql = $tax_id;
            } else if ($gst_type === 'non_gst') {
                $rate = 0; // Force 0% tax in Non-GST mode
            }

            // INSERT new item (without code column)
            $insert_item = "INSERT INTO quotation_item (
                quotation_id, 
                product_id, 
                service_id, 
                service_name, 
                quantity, 
                selling_price, 
                tax_id, 
                rate, 
                amount, 
                org_id, 
                created_by, 
                updated_by
            ) VALUES (
                '$quotation_id', 
                $product_id_sql, 
                $service_id_sql, 
                $service_name_sql, 
                '$quantity',
                '$selling_price', 
                $tax_id_sql, 
                '$rate', 
                '$amount', 
                '$orgId',
                '$currentUserId', 
                '$currentUserId'
            )";
            
            if (!mysqli_query($conn, $insert_item)) {
                throw new Exception("Failed to insert item at index $index: " . mysqli_error($conn));
            }
            $item_count++;
        }

        // === 4. Handle document uploads ===
        if (!empty($_FILES['document']['name'][0])) {
            $uploadDir = '../../uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            foreach ($_FILES['document']['tmp_name'] as $key => $tmpName) {
                if (!empty($_FILES['document']['name'][$key]) && $_FILES['document']['error'][$key] === 0) {
                    $document = [
                        'name' => $_FILES['document']['name'][$key],
                        'type' => $_FILES['document']['type'][$key],
                        'tmp_name' => $tmpName,
                        'error' => $_FILES['document']['error'][$key],
                        'size' => $_FILES['document']['size'][$key]
                    ];

                    $docFileName = uploadFile($document, $uploadDir);
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
        
        $_SESSION['message'] = "Quotation updated successfully with $item_count items!";
        $_SESSION['message_type'] = "success";
        
        header("Location: ../edit-quotation.php?id=" . $quotation_id);
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error_message = "Error: " . $e->getMessage();
        
        $_SESSION['error'] = $error_message;
        header("Location: ../edit-quotation.php?id=" . $quotation_id);
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request";
    header("Location: ../quotations.php");
    exit();
}

?>