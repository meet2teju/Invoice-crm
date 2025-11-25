<?php
include '../../config/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation - check if required fields are not empty
    if (!empty(trim($_POST['bank_name'])) && !empty(trim($_POST['account_holder'])) && !empty(trim($_POST['account_number']))) {
        
        // Handle all fields with proper NULL conversion
        $bank_name = !empty(trim($_POST['bank_name'])) ? mysqli_real_escape_string($conn, $_POST['bank_name']) : NULL;
        $account_holder = !empty(trim($_POST['account_holder'])) ? mysqli_real_escape_string($conn, $_POST['account_holder']) : NULL;
        $account_number = !empty(trim($_POST['account_number'])) ? mysqli_real_escape_string($conn, $_POST['account_number']) : NULL;
        $routing_number = !empty(trim($_POST['routing_number'])) ? mysqli_real_escape_string($conn, $_POST['routing_number']) : NULL;
        $ifsc_code = !empty(trim($_POST['ifsc_code'])) ? mysqli_real_escape_string($conn, $_POST['ifsc_code']) : NULL;
        $swift_code = !empty(trim($_POST['swift_code'])) ? mysqli_real_escape_string($conn, $_POST['swift_code']) : NULL;
        
        // Handle opening_balance - convert empty to NULL or 0.00
        $opening_balance = !empty(trim($_POST['opening_balance'])) ? floatval($_POST['opening_balance']) : NULL;

        // Use prepared statement to handle NULL values properly
        $sql = "INSERT INTO bank (bank_name, account_holder, account_number, routing_number, ifsc_code, swift_code, opening_balance, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            // Bind parameters - use appropriate types for each field
            // s = string, d = double/float, i = integer
            mysqli_stmt_bind_param($stmt, "ssssssd", 
                $bank_name,
                $account_holder, 
                $account_number,
                $routing_number,
                $ifsc_code,
                $swift_code,
                $opening_balance
            );
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = 'Bank added successfully.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Error: ' . mysqli_stmt_error($stmt);
                $_SESSION['message_type'] = 'danger';
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['message'] = 'Error preparing statement: ' . mysqli_error($conn);
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = 'Please fill in all required fields.';
        $_SESSION['message_type'] = 'danger';
    }

    header("Location: ../bank.php");
    exit;
}