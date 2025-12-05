<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'] ?? 'success';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>
<?php include 'layouts/session.php'; ?>
<?php
include '../config/config.php';

// Validate and sanitize the ID
$invoice_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($invoice_id <= 0) {
    $_SESSION['error'] = "Invalid invoice ID.";
    header("Location: invoices.php");
    exit();
}

// Fetch invoice data
$query = "SELECT * FROM invoice WHERE id = $invoice_id";
$result = mysqli_query($conn, $query);

// Check if invoice exists
if (!$result || mysqli_num_rows($result) === 0) {
    $_SESSION['error'] = "Invoice not found.";
    header("Location: invoices.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

// Fetch tax rates from database
$taxRates = [];
$taxQuery = "SELECT id, name, rate FROM tax WHERE status = 1";
$taxResult = mysqli_query($conn, $taxQuery);
while ($taxRow = mysqli_fetch_assoc($taxResult)) {
    $taxRates[] = $taxRow;
}

// Fetch products and services from product table based on item_type
$products = [];
$services = [];
$itemQuery = "SELECT p.id, p.name, p.selling_price, p.code, p.item_type, 
                     t.id AS tax_id, t.rate AS tax_rate, t.name AS tax_name
              FROM product p
              LEFT JOIN tax t ON p.tax_id = t.id
              WHERE p.is_deleted = 0 AND p.status = 1";
$itemResult = mysqli_query($conn, $itemQuery);
while ($item = mysqli_fetch_assoc($itemResult)) {
    if ($item['item_type'] == 1) {
        $products[] = $item;
    } else {
        $services[] = $item;
    }
}

// Fetch related data
$clients = mysqli_query($conn, "SELECT id, first_name,last_name,salutation,company_name FROM client WHERE is_deleted = 0");
$users = mysqli_query($conn,  "SELECT login.id, login.name FROM login
    JOIN user_role ON login.role_id = user_role.id
    WHERE   login.is_deleted = 0
    ORDER BY login.name ASC");
$documents = mysqli_query($conn, "SELECT id, document FROM invoice_document WHERE invoice_id = $invoice_id AND is_deleted = 0");

// Radio precheck
$is_product = ($row['item_type'] == 1) ? 'checked' : '';
$is_service = ($row['item_type'] == 0) ? 'checked' : '';

// GST Type precheck
$gst_type = $row['gst_type'] ?? 'gst';
$gst_checked = ($gst_type == 'gst') ? 'checked' : '';
$non_gst_checked = ($gst_type == 'non_gst') ? 'checked' : '';

// Get project and task data for pre-selection
$project_id = $row['project_id'] ?? 0;
$task_ids = [];
if ($project_id > 0) {
    // First check if invoice_tasks table exists, if not create it
    $check_table = mysqli_query($conn, "SHOW TABLES LIKE 'invoice_tasks'");
    if (mysqli_num_rows($check_table) == 0) {
        $create_table = "CREATE TABLE invoice_tasks (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            invoice_id INT(11) NOT NULL,
            task_id INT(11) NOT NULL,
            org_id BIGINT(20) NULL,
            is_deleted TINYINT(1) DEFAULT 0,
            created_by BIGINT(20) NULL,
            updated_by BIGINT(20) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        mysqli_query($conn, $create_table);
    }
    
    $task_query = "SELECT task_id FROM invoice_tasks WHERE invoice_id = $invoice_id AND is_deleted = 0";
    $task_result = mysqli_query($conn, $task_query);
    if ($task_result) {
        while ($task = mysqli_fetch_assoc($task_result)) {
            $task_ids[] = $task['task_id'];
        }
    }
    
    // FIX: Update radio button selection based on tasks
    if (!empty($task_ids)) {
        $is_service = 'checked';
        $is_product = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php include 'layouts/title-meta.php'; ?> 
	<?php include 'layouts/head-css.php'; ?>
   <!-- Additional CSS for datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .tax-display-container {
            min-height: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            background-color: #f8f9fa;
        }
        .tax-amount-line {
            font-weight: 600;
            font-size: 14px;
            line-height: 1.2;
        }
        .tax-rate-line {
            font-size: 12px;
            color: #6c757d;
            line-height: 1.2;
        }
        .table td {
            vertical-align: middle;
        }
        .service-fields {
            display: none;
        }
        .service-row .service-fields {
            display: block;
        }
        .service-row .product-fields {
            display: none;
        }
        .product-row .product-fields {
            display: block;
        }
        .product-row .service-fields {
            display: none;
        }
        .service-quantity {
            background-color: #f8f9fa;
        }
        .service-custom-input {
            margin-top: 5px;
        }
        .gst-toggle-group {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .gst-toggle-label {
            font-weight: 600;
            color: #495057;
        }
        .non-gst-mode .tax-column {
            display: none;
        }
        .non-gst-mode .tax-details {
            display: none !important;
        }
        .product-tax-select, .service-tax-select {
            margin-bottom: 8px;
        }
        .hidden-tax-id {
            display: none;
        }
    </style>
</head>

<body>

    <!-- Start Main Wrapper -->
    <div class="main-wrapper">

		<?php include 'layouts/menu.php'; ?>

        <!-- ========================
			Start Page Content
		========================= -->

        <div class="page-wrapper">
  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
<?php endif; ?>
            <!-- Start Content -->
            <div class="content">

                <!-- Start row  -->
                <div class="row">
                    <div class="col-md-12 mx-auto">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6>Edit Invoice</h6>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="gst-toggle-group">
                                        <span class="gst-toggle-label">GST Type:</span>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gst_type" id="gst-enabled" value="gst" <?= $gst_checked ?>>
                                            <label class="form-check-label" for="gst-enabled">GST</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gst_type" id="gst-disabled" value="non_gst" <?= $non_gst_checked ?>>
                                            <label class="form-check-label" for="gst-disabled">Non-GST</label>
                                        </div>
                                    </div>
                                    <!-- <a href="invoice-details.php?id=<?= $invoice_id ?>" class="btn btn-outline-white d-inline-flex align-items-center">
                                        <i class="isax isax-eye me-1"></i>Preview
                                    </a> -->
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form action="process/action_edit_invoice.php" method="POST" enctype="multipart/form-data" id="form">
                                      <input type="hidden" name="id" value="<?= $invoice_id ?>">
                                      <input type="hidden" name="user_id" value="<?php echo $_SESSION['crm_user_id'] ?? ''; ?>">
                                      <input type="hidden" name="gst_type" id="gst_type_field" value="<?= $gst_type ?>">

                                        <div class="border-bottom mb-3 pb-1">
                                          <div class="row gx-3">
                                            <div class="col-lg-4 col-md-6">
                                              <div class="mb-3">
                                                  <label class="form-label">Client Name<span class="text-danger">*</span></label>
                                                  <select class="form-select select2" name="client_id" id="client_id">
                                                        <option value="">Select Client</option>
                                                        <?php 
                                                        while ($client = mysqli_fetch_assoc($clients)) {

                                                            $selected = ($client['id'] == $row['client_id']) ? 'selected' : '';

                                                            $displayName = trim($client['salutation'] . ' ' . $client['first_name'] . ' ' . $client['last_name']);

                                                            if (!empty($client['company_name'])) {
                                                                $displayName .= ' - ' . $client['company_name'];
                                                            }

                                                            echo "<option value='{$client['id']}' $selected>" . htmlspecialchars($displayName) . "</option>";
                                                        } 
                                                        ?>
                                                    </select>

                                              <span class="text-danger error-text" id="clientname_error"></span>
                                              </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                              <div class="mb-3">
                                                <label class="form-label">Project</label>
                                                <select class="form-select select2" name="project_id" id="project_id" <?= $project_id ? '' : 'disabled' ?>>
                                                  <option value="">Select Project</option>
                                                  <?php
                                                  if ($row['client_id']) {
                                                      $client_projects = mysqli_query($conn, "SELECT * FROM project WHERE client_id = {$row['client_id']} AND is_deleted = 0");
                                                      while ($project = mysqli_fetch_assoc($client_projects)) {
                                                          $selected = ($project['id'] == $project_id) ? 'selected' : '';
                                                          echo "<option value='{$project['id']}' $selected>{$project['project_name']}</option>";
                                                      }
                                                  }
                                                  ?>
                                                </select>
                                                <!-- <span class="text-danger error-text" id="project_error"></span> -->
                                              </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                              <div class="mb-3">
                                                <label class="form-label">Tasks</label>
                                                <select class="form-select select2" name="task_id[]" id="task_id" multiple="multiple" <?= $project_id ? '' : 'disabled' ?>>
                                                  <option value="">Select Tasks</option>
                                                  <?php
                                                  if ($project_id) {
                                                      // FIX: Removed status filter to show all tasks
                                                      $project_tasks = mysqli_query($conn, "SELECT * FROM project_task WHERE project_id = $project_id AND is_deleted = 0");
                                                      while ($task = mysqli_fetch_assoc($project_tasks)) {
                                                          $selected = in_array($task['id'], $task_ids) ? 'selected' : '';
                                                          echo "<option value='{$task['id']}' $selected>{$task['task_name']} ({$task['hour']} hours)</option>";
                                                      }
                                                  }
                                                  ?>
                                                </select>
                                                <!-- <span class="text-danger error-text" id="task_error"></span> -->
                                              </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                              <div class="mb-3">
                                                  <label class="form-label">Reference Name</label>
                                                  <input type="text" class="form-control" name="reference_name" id="reference_name" value="<?= htmlspecialchars($row['reference_name'] ?? '') ?>">
                                              </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                              <div class="mb-3">
                                                <label class="form-label">Order Number</label>
                                                <input type="number" class="form-control" name="order_number" value="<?= htmlspecialchars($row['order_number'] ?? '') ?>">
                                              </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                              <div class="mb-3">
                                                  <label class="form-label">Invoice Number</label>
                                                  <input type="text" class="form-control" name="invoice_id" value="<?= htmlspecialchars($row['invoice_id']) ?>" readonly >
                                              </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                              <div class="mb-3">
                                                  <label class="form-label">Salesperson</label>
                                                    <select class="form-select select2" name="user_id" id="user_id">
                                                  <option value="">Select Salesperson</option>
                                                  <?php 
                                                  // Reset users pointer and loop through
                                                  mysqli_data_seek($users, 0);
                                                  while ($user = mysqli_fetch_assoc($users)) {
                                                      $selected = ($user['id'] == $row['user_id']) ? 'selected' : '';
                                                      echo "<option value='{$user['id']}' $selected>{$user['name']}</option>";
                                                  } ?>
                                                </select>
                                                <span class="text-danger error-text" id="username_error"></span>

                                              </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <label class="form-label">Invoice Date<span class="text-danger">*</span></label>
                                                <div class="input-group position-relative mb-3">
                                                    <input type="text" class="form-control datepicker"id="invoice_date" name="invoice_date" value="<?= htmlspecialchars($row['invoice_date']) ?>">
                                                    <span class="input-icon-addon fs-16 text-gray-9">
                                                      <i class="isax isax-calendar-2"></i>
                                                    </span>
                                                </div>
                                                <span class="text-danger error-text" id="invoice_date_error"></span>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                              <label class="form-label">Invoice Due Date<span class="text-danger">*</span></label>
                                              <div class="input-group position-relative mb-3">
                                                  <input type="text" class="form-control datepicker" id="due_date" name="due_date" value="<?= htmlspecialchars($row['due_date']) ?>">
                                                  <span class="input-icon-addon fs-16 text-gray-9">
                                                    <i class="isax isax-calendar-2"></i>
                                                  </span>
                                              </div>
                                              <span class="text-danger error-text" id="invoice_due_error"></span> 
                                            </div>
                                          </div>
                                        </div>
                                        <div class="border-bottom mb-3">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="card shadow-none">
                                                        <div class="card-body">
                                                            <h6 class="mb-3">Bill To</h6>
                                                            <div class="bg-light border rounded p-3 d-flex align-items-start">
                                                                <div id="client_info_block"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="card shadow-none">
                                                        <div class="card-body">
                                                            <h6 class="mb-3">Bill From</h6>
                                                            <div class="bg-light border rounded p-3 d-flex align-items-start">
                                                                <div id="shipping_info_block"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                   <div class="border-bottom mb-3 pb-3">
                                            <div class="row">
                                                <div class="col-xl-4 col-md-6">
                                                    <h6 class="mb-3">Items & Details</h6>
                                                    <div>
                                                        <label class="form-label">Item Type<span class="text-danger">*</span></label>
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="item_type" id="Radio-product" value="1" <?= $is_product ?>>
                                                                <label class="form-check-label" for="Radio-product">Product</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="item_type" id="Radio-service" value="0" <?= $is_service ?>>
                                                                <label class="form-check-label" for="Radio-service">Service</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

<div class="table-responsive rounded table-nowrap border-bottom-0 border mb-3">
    <table class="table mb-0 add-table <?= $gst_type == 'non_gst' ? 'non-gst-mode' : '' ?>">
        <thead class="table-dark" id="table-heading">
            <tr>
                <th>Product/Service</th>
                <th>Quantity</th>
                <th>HSN Code</th>
                <th>Selling Price</th>
                <th class="tax-column">Tax</th>
                <th>Amount</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="add-tbody" id="product">
            <span class="text-danger error-text" id="product_error"></span>

           <?php
$invoice_id = $_GET['id'];
// FIXED QUERY: Join product table for both product_id AND service_id - EXACTLY LIKE QUOTATION
$item_query = "SELECT 
                qi.*, 
                p.name AS product_name, 
                p.code AS product_hsn_code,
                p.selling_price AS product_price,
                p.item_type AS product_item_type,
                s.name AS service_name_from_product,
                s.code AS service_hsn_code,
                s.selling_price AS service_price,
                t.rate AS tax_rate,
                t.name AS tax_name,
                t.id AS tax_id
            FROM invoice_item qi
            LEFT JOIN product p ON qi.product_id = p.id
            LEFT JOIN product s ON qi.service_id = s.id  -- Join for services too
            LEFT JOIN tax t ON qi.tax_id = t.id
            WHERE qi.invoice_id = $invoice_id AND qi.is_deleted = 0";

$item_result = mysqli_query($conn, $item_query);
while ($item = mysqli_fetch_assoc($item_result)) {
    $qty = (float)($item['quantity'] ?? 0);
    $price = (float)($item['selling_price'] ?? 0);
    $taxRate = (float)($item['tax_rate'] ?? 0);
    $taxName = $item['tax_name'] ?? '';
    $taxId = $item['tax_id'] ?? '';
    
    // FIXED: Get HSN code from appropriate source
    $hsnCode = '';
    if (!empty($item['product_id'])) {
        // This is a product - get HSN from product join
        $hsnCode = $item['product_hsn_code'] ?? '';
    } else if (!empty($item['service_id'])) {
        // This is a service from dropdown - get HSN from service join
        $hsnCode = $item['service_hsn_code'] ?? '';
    }
    // For custom services (service_name without service_id), HSN code remains empty
    
    // DETERMINE IF IT'S A PRODUCT OR SERVICE
    $isProduct = (!empty($item['product_id']) && $item['product_id'] != 0);
    $isService = (!empty($item['service_id']) && $item['service_id'] != 0) || !empty($item['service_name']);
    
    // Get display name
    if ($isProduct) {
        $displayName = $item['product_name'] ?? '';
        $itemId = $item['product_id'];
    } else {
        $displayName = $item['service_name'] ?? '';
        $itemId = $item['service_id'] ?? '';
    }
    
    // FIXED: Calculate amounts properly
    $lineSubtotal = $qty * $price;
    $lineTax = $lineSubtotal * $taxRate / 100;
    $amount = $lineSubtotal + $lineTax;
    
    // Adjust for Non-GST mode
    $displayTaxRate = $gst_type == 'non_gst' ? 0 : $taxRate;
    $displayLineTax = $gst_type == 'non_gst' ? 0 : $lineTax;
    $displayAmount = $gst_type == 'non_gst' ? $lineSubtotal : $amount;
    
    // Determine row class and values
    $rowClass = $isProduct ? 'product-row' : 'service-row';
    $quantityClass = $isProduct ? '' : 'service-quantity';
    $quantityValue = $isProduct ? $qty : ($qty > 0 ? $qty : '');
    $quantityPlaceholder = $isProduct ? '' : 'Optional';
?>
                <tr class="<?= $rowClass ?>">
                    <!-- ADDED: Hidden field for invoice item ID -->
                    <td style="display:none;">
                        <input type="hidden" name="invoice_item_id[]" value="<?= $item['id'] ?>">
                    </td>
                    <td>
                        <div class="product-fields">
                            <select class="form-select product-select" name="item_id[]">
                                <option value="">Select Product</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?= $product['id'] ?>" 
                                        data-price="<?= $product['selling_price'] ?>" 
                                        data-hsn="<?= $product['code'] ?>"
                                        data-tax="<?= $product['tax_rate'] ?>"
                                        data-tax-id="<?= $product['tax_id'] ?>"
                                        data-tax-name="<?= $product['tax_name'] ?>"
                                        <?= ($isProduct && $product['id'] == $itemId) ? 'selected' : '' ?>>
                                        <?= $product['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <!-- REMOVED: Hidden tax-id field - using dropdown instead -->
                            <input type="hidden" class="tax-name" name="tax_name[]" value="<?= htmlspecialchars($taxName) ?>">
                            <!-- Hidden field to track item type -->
                            <input type="hidden" name="item_type_row[]" value="product">
                            <!-- FIX: Add empty service_name field for product rows -->
                            <input type="hidden" name="service_name[]" value="">
                        </div>
                        <div class="service-fields">
                            <select class="form-select service-select" name="item_id[]">
                                <option value="">Select Service</option>
                                <?php foreach ($services as $service): ?>
                                    <option value="<?= $service['id'] ?>" 
                                        data-price="<?= $service['selling_price'] ?>" 
                                        data-hsn="<?= $service['code'] ?>"
                                        data-tax="<?= $service['tax_rate'] ?>"
                                        data-tax-id="<?= $service['tax_id'] ?>"
                                        data-tax-name="<?= $service['tax_name'] ?>"
                                        <?= ($isService && $service['id'] == $itemId) ? 'selected' : '' ?>>
                                        <?= $service['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" class="form-control service-name-input service-custom-input" name="service_name[]" placeholder="Or enter custom service name" value="<?= $isService ? htmlspecialchars($displayName) : '' ?>">
                            <!-- REMOVED: Hidden tax-id field - using dropdown instead -->
                            <input type="hidden" class="tax-name" name="tax_name[]" value="<?= htmlspecialchars($taxName) ?>">
                            <!-- Hidden field to track item type -->
                            <input type="hidden" name="item_type_row[]" value="service">
                        </div>
                    </td>
                    <td>
                        <input type="number" class="form-control quantity <?= $quantityClass ?>" name="quantity[]" value="<?= $quantityValue ?>" <?= $isProduct ? 'min="1"' : '' ?> placeholder="<?= $quantityPlaceholder ?>">
                    </td>
                    <td>
                        <input type="text" class="form-control hsn-code" name="code[]" value="<?= htmlspecialchars($hsnCode) ?>" readonly>
                    </td>
                    <td>
                        <div class="product-fields">
                            <input type="text" class="form-control selling-price" name="selling_price[]" 
                                value="<?= '$ ' . number_format($price, 2) ?>" 
                                data-value="<?= $price ?>">
                        </div>
                        <div class="service-fields">
                            <input type="text" class="form-control service-price-input" name="selling_price[]" 
                                value="<?= '$ ' . number_format($price, 2) ?>" 
                                data-value="<?= $price ?>" placeholder="0.00">
                        </div>
                    </td>
                    <td class="tax-column">
                        <div class="product-fields">
                            <!-- Tax dropdown for products - FIXED: Remove hidden tax-id, use dropdown directly -->
                            <select class="form-select product-tax-select" name="tax_id[]" <?= $gst_type == 'non_gst' ? 'disabled' : '' ?>>
                                <option value="">Select Tax</option>
                                <?php foreach ($taxRates as $tax): ?>
                                <option value="<?= $tax['id'] ?>" 
                                    data-rate="<?= $tax['rate'] ?>"
                                    <?= ($gst_type != 'non_gst' && $tax['id'] == $taxId) ? 'selected' : '' ?>>
                                    <?= $tax['name'] ?> (<?= $tax['rate'] ?>%)
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" class="tax-rate" name="rate[]" data-value="<?= $displayTaxRate ?>" value="<?= number_format($displayTaxRate, 2) . '%' ?>">
                            <div class="tax-display-container mt-2">
                                <div class="tax-amount-line"><?= '$ ' . number_format($displayLineTax, 2) ?></div>
                                <div class="tax-rate-line"><?= number_format($displayTaxRate, 2) . '%' ?></div>
                            </div>
                        </div>
                        <div class="service-fields">
                            <select class="form-select service-tax-select" name="tax_id[]" <?= $gst_type == 'non_gst' ? 'disabled' : '' ?>>
                                <option value="">Select Tax</option>
                                <?php foreach ($taxRates as $tax): ?>
                                <option value="<?= $tax['id'] ?>" 
                                    data-rate="<?= $tax['rate'] ?>"
                                    <?= ($gst_type != 'non_gst' && $tax['id'] == $taxId) ? 'selected' : '' ?>>
                                    <?= $tax['name'] ?> (<?= $tax['rate'] ?>%)
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" class="tax-rate" name="rate[]" data-value="<?= $displayTaxRate ?>" value="<?= number_format($displayTaxRate, 2) . '%' ?>">
                            <div class="tax-display-container mt-2">
                                <div class="tax-amount-line"><?= '$ ' . number_format($displayLineTax, 2) ?></div>
                                <div class="tax-rate-line"><?= number_format($displayTaxRate, 2) . '%' ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control amount" name="amount[]" 
                            value="<?= '$ ' . number_format($displayAmount, 2) ?>" 
                            data-value="<?= $displayAmount ?>" readonly>
                    </td>
                    <td>
                        <a href="javascript:void(0);" class="remove-table"><i class="isax isax-trash"></i></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

                                            <div>
                                                <a href="javascript:void(0);" class="d-inline-flex align-items-center add-invoice-data"><i class="isax isax-add-circle5 text-primary me-1"></i>Add New</a>
                                            </div>
                                        </div>

                                        <div class="border-bottom mb-3">
                                            <!-- start row -->
                                            <div class="row">
                                                <div class="col-lg-7">
                                                    <div class="mb-3">
                                                        <h6 class="mb-3">Extra Information</h6>
                                                        <div>
                                                            <ul class="nav nav-tabs nav-solid-primary tab-style-1 border-0 p-0 d-flex flex-wrap gap-3 mb-3" role="tablist">
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link active d-inline-flex align-items-center border fs-12 fw-semibold rounded-2" data-bs-toggle="tab" data-bs-target="#notes" aria-current="page" href="javascript:void(0);"><i class="isax isax-document-text me-1"></i>Add Notes</a>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link d-inline-flex align-items-center border fs-12 fw-semibold rounded-2" data-bs-toggle="tab" data-bs-target="#terms" href="javascript:void(0);"><i class="isax isax-document me-1"></i>Add Terms & Conditions</a>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link d-inline-flex align-items-center border fs-12 fw-semibold rounded-2" data-bs-toggle="tab" data-bs-target="#bank" href="javascript:void(0);"><i class="isax isax-bank me-1"></i>Bank Details</a>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link d-inline-flex align-items-center border fs-12 fw-semibold rounded-2" data-bs-toggle="tab" data-bs-target="#documents" href="javascript:void(0);"><i class="isax isax-bank me-1"></i>Upload Documents</a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content">
                                                                <div class="tab-pane active show" id="notes" role="tabpanel">
                                                                    <label class="form-label">Additional Notes</label>
                                                                    <textarea class="form-control" name="invoice_note"><?= htmlspecialchars($row['invoice_note'] ?? '') ?></textarea>
                                                                </div>
                                                                <div class="tab-pane fade" id="terms" role="tabpanel">
                                                                    <label class="form-label">Terms & Conditions</label>
                                                                    <textarea class="form-control" name="description"><?= htmlspecialchars($row['description'] ?? '') ?></textarea>
                                                                </div>
                                                                <div class="tab-pane fade" id="bank" role="tabpanel">
                                                                    <label class="form-label">Account<span class="text-danger">*</span></label>
                                                                   <select class="select2" name="bank_id" id="bank_id">
                                                                        <option value="">Select Account</option>
                                                                        <?php
                                                                        $invoiceBankId = $row['bank_id'] ?? 0;
                                                                            $bankResult = mysqli_query($conn, "SELECT * FROM bank WHERE status = 1");
                                                                                while ($bank = mysqli_fetch_assoc($bankResult)) {
                                                                                    $selected = ($bank['id'] == $invoiceBankId) ? 'selected' : '';
                                                                                    echo '<option value="' . $bank['id'] . '" ' . $selected . '>'
                                                                                        . htmlspecialchars($bank['account_holder']) . ' - '
                                                                                        . htmlspecialchars($bank['account_number']) . ' ('
                                                                                        . htmlspecialchars($bank['bank_name']) . ')</option>';
                                                                                }
                                                                        ?>
                                                                    </select>
                                                                                <span class="text-danger error-text" id="invoice_account_error"></span> 
                                                                </div>
                                                                <div class="tab-pane fade" id="documents" role="tabpanel">
                                                                    <div class="file-upload drag-file w-100 h-auto py-3 d-flex align-items-center justify-content-center flex-column">
                                                                        <span class="upload-img d-block"><i class="isax isax-image text-primary me-1"></i>Upload Documents</span>
                                                                        <input type="file" class="form-control" name="document[]" id="document-upload" multiple>
                                                                        <span id="file-count-label" class="mt-2 text-muted"></span>
                                                                    </div>
                                                                    <span id="document_error" class="text-danger error-text"></span>
                                                                    
                                                                    <?php 
                                                                    // Reset and check if we have documents
                                                                    if ($documents && mysqli_num_rows($documents) > 0): ?>
                                                                        <div class="mt-3 w-100">
                                                                            <label class="form-label">Uploaded Documents:</label>
                                                                            <ul class="list-group">
                                                                                <?php 
                                                                                // Reset pointer and loop through documents
                                                                                mysqli_data_seek($documents, 0);
                                                                                while ($doc = mysqli_fetch_assoc($documents)): 
                                                                                ?>
                                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                        <a href="../uploads/<?= htmlspecialchars($doc['document']) ?>" target="_blank" class="text-truncate" style="max-width: 70%;">
                                                                                            <i class="isax isax-document me-2"></i>
                                                                                            <?= htmlspecialchars($doc['document']) ?>
                                                                                        </a>
                                                                                        <a href="process/delete_document.php?id=<?= $doc['id'] ?>&invoice_id=<?= $invoice_id ?>" 
                                                                                           class="text-danger delete-document" 
                                                                                           title="Delete document">
                                                                                            <i class="isax isax-trash"></i>
                                                                                        </a>
                                                                                    </li>
                                                                                <?php endwhile; ?>
                                                                            </ul>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="mt-3">
                                                                            <p class="text-muted">No documents uploaded yet.</p>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-lg-5">
                                                <?php
                                                // Calculate display amounts for Non-GST mode
                                                $displaySubAmount = $gst_type == 'non_gst' ? ($row['amount'] - $row['tax_amount']) : $row['amount'];
                                                $displayTotalAmount = $gst_type == 'non_gst' ? ($row['total_amount'] - $row['tax_amount']) : $row['total_amount'];
                                                ?>
                                                <input type="hidden" name="sub_amount" id="subtotal-amount-field" value="<?= $displaySubAmount ?>">
                                                <input type="hidden" name="tax_amount" id="tax-amount-field" value="<?= $gst_type == 'non_gst' ? 0 : $row['tax_amount'] ?>">
                                                <input type="hidden" name="total_amount" id="total-amount-field" value="<?= $displayTotalAmount ?>">

                                                    <div class="mb-3">
                                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                                            <h6 class="fs-14 fw-semibold">Amount</h6>
                                                            <h6 class="fs-14 fw-semibold" id="subtotal-amount"><?= '$ ' . number_format($displaySubAmount, 2) ?></h6>
                                                        </div>
                                                       <div class="tax-details" style="<?= $gst_type == 'non_gst' ? 'display: none !important;' : '' ?>">
                                                            <!-- JS will populate tax per rate here -->
                                                        </div>
                                                        <div id="shipping-charge-group" class="d-flex align-items-center justify-content-between mb-3">
                                                            <h6 class="fs-14 fw-semibold mb-0">Shipping Charge</h6>
                                                            <input type="text" class="form-control" id="shipping-charge" name="shipping_charge" value="<?= '$ ' . number_format($row['shipping_charge'], 2) ?>">
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                                                            <h6>Total</h6>
                                                            <h6 id="total-amount"><?= '$ ' . number_format($displayTotalAmount, 2) ?></h6>
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                            </div>
											                <!-- end row -->

                                        </div>

                                        <div class="d-flex align-items-center justify-content-between">
                                        <button type="button" class="btn btn-outline-white" onclick="window.location.href='invoices.php'">Cancel</button>
                                            <button type="submit" name="submit" value="1" class="btn btn-primary">Save</button>
                                        </div>
										
                                    </form>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->

            </div>
            <!-- End Content -->

            <?php include 'layouts/footer.php'; ?>

        </div>

        <!-- ========================
			End Page Content
		========================= -->

    </div>
    <!-- End Main Wrapper -->

	<?php include 'layouts/vendor-scripts.php'; ?>
<!-- Additional JS for datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
   
<script>
// =============================================
// FIXED: Single "Add New" event handler - EXACTLY LIKE QUOTATION
// =============================================
$(document).ready(function() {
    console.log('Document ready - initializing invoice edit...');

    // Remove ALL existing click handlers from add button FIRST
    $(document).off('click', '.add-invoice-data');
    $('.add-invoice-data').off('click');

    // Initialize datepicker
    $('.datepicker').flatpickr({
        dateFormat: "Y-m-d",
        allowInput: true,
        defaultDate: new Date(),
        clickOpens: true
    });
    
    // Initialize select2
    $('.select2').select2({
        theme: 'bootstrap-5'
    });

    // === Allow only text (no digits) ===
    $('#reference_name').on('input', function () {
        this.value = this.value.replace(/[0-9]/g, '');
    });

    $('#shipping-charge').on('input', function () {
        let val = this.value.replace(/[^0-9.]/g, ''); 
        let parts = val.split('.');
        if (parts.length > 2) {
            val = parts[0] + '.' + parts[1];
        }
        this.value = val;
    });

    // Document upload functionality
    $('#document-upload').on('change', function () {
        let files = $(this)[0].files;
        if (files.length === 0) {
            $('#file-count-label').text('');
        } else if (files.length === 1) {
            $('#file-count-label').text(files[0].name);
        } else {
            $('#file-count-label').text(`${files.length} files selected`);
        }
    });

    // =============================================
    // Project and Task Selection
    // =============================================
    // When client changes
    $('#client_id').on('change', function() {
        const clientId = $(this).val();
        
        if (clientId) {
            // Enable and load projects
            $('#project_id').prop('disabled', false).html('<option value="">Loading projects...</option>');
            $('#task_id').prop('disabled', true).html('<option value="">Select Tasks</option>');
            
            $.ajax({
                url: 'process/get_projects_by_client.php',
                type: 'POST',
                data: { client_id: clientId },
                success: function(data) {
                    $('#project_id').html(data);
                },
                error: function() {
                    $('#project_id').html('<option value="">Error loading projects</option>');
                }
            });
        } else {
            $('#project_id, #task_id').prop('disabled', true);
            $('#project_id').html('<option value="">Select Project</option>');
            $('#task_id').html('<option value="">Select Tasks</option>');
        }
    });

    // When project changes
    $('#project_id').on('change', function() {
        const projectId = $(this).val();
        
        if (projectId) {
            // Enable and load tasks
            $('#task_id').prop('disabled', false).html('<option value="">Loading tasks...</option>');
            
            $.ajax({
                url: 'process/get_tasks_by_project.php',
                type: 'POST',
                data: { project_id: projectId },
                success: function(data) {
                    $('#task_id').html(data);
                    // Initialize select2 for multi-select
                    $('#task_id').select2({
                        placeholder: "Select Tasks",
                        allowClear: true
                    });
                },
                error: function() {
                    $('#task_id').html('<option value="">Error loading tasks</option>');
                }
            });
        } else {
            $('#task_id').prop('disabled', true).html('<option value="">Select Tasks</option>');
        }
    });

    // When tasks are selected
    $('#task_id').on('change', function() {
        const selectedTasks = $(this).val();
        
        if (selectedTasks && selectedTasks.length > 0) {
            // Clear existing rows first
            $('.add-tbody').empty();
            
            // Set item type to Service when tasks are selected
            $('#Radio-service').prop('checked', true).trigger('change');
            
            // Load details for each selected task
            selectedTasks.forEach(function(taskId) {
                if (taskId) {
                    $.ajax({
                        url: 'process/get_task_details.php',
                        type: 'POST',
                        data: { task_id: taskId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                // Add task as an invoice item using your existing row structure
                                const newRow = `
                                <tr class="service-row">
                                    <td style="display:none;">
                                        <input type="hidden" name="invoice_item_id[]" value="">
                                    </td>
                                    <td>
                                        <div class="service-fields">
                                            <input type="text" class="form-control service-name-input" value="${response.task_name}" readonly>
                                            <input type="hidden" name="item_id[]" value="${taskId}">
                                            <input type="hidden" class="tax-name" name="tax_name[]" value="">
                                            <!-- Hidden field to track item type for this row -->
                                            <input type="hidden" name="item_type_row[]" value="service">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control quantity service-quantity" name="quantity[]" value="${response.hours}" min="1" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control hsn-code" name="code[]" value="" readonly>
                                    </td>
                                    <td>
                                        <div class="service-fields">
                                            <input type="text" class="form-control service-price-input" name="selling_price[]" value="${response.rate_per_hour}" data-value="${response.rate_per_hour}" readonly>
                                        </div>
                                    </td>
                                    <td class="tax-column">
                                        <div class="service-fields">
                                            <select class="form-select service-tax-select" name="tax_id[]">
                                                <option value="">Select Tax</option>
                                            </select>
                                            <input type="hidden" class="tax-rate" name="rate[]" data-value="0" value="0%">
                                            <div class="tax-display-container mt-2">
                                                <div class="tax-amount-line">$ 0.00</div>
                                                <div class="tax-rate-line">0%</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control amount" name="amount[]" value="${response.total_amount}" data-value="${response.total_amount}" readonly>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" class="remove-table"><i class="isax isax-trash text-danger"></i></a>
                                    </td>
                                </tr>`;
                                
                                $('.add-tbody').append(newRow);
                                
                                // Update summary
                                calculateSummary();
                            }
                        },
                        error: function() {
                            console.log('Error loading task details for task ID: ' + taskId);
                        }
                    });
                }
            });
        } else {
            // If no tasks selected, clear the items table
            $('.add-tbody').empty();
            calculateSummary();
        }
    });

    // Client info fetch
    function fetchClientInfo(clientId) {
        if (clientId) {
            console.log('Fetching client info for ID:', clientId);
            $.ajax({
                url: 'process/fetch_client_full_info.php',
                type: 'POST',
                data: { client_id: clientId },
                dataType: 'json',
                success: function(response) {
                    console.log('Client info response:', response);
                    if (response.billing_html) {
                        $('#client_info_block').html(response.billing_html);
                    } else {
                        $('#client_info_block').html('<p class="text-muted">No billing information found.</p>');
                    }
                    
                    if (response.shipping_html) {
                        $('#shipping_info_block').html(response.shipping_html);
                    } else {
                        $('#shipping_info_block').html('<p class="text-muted">No shipping information found.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching client info:', error);
                    console.log('XHR response:', xhr.responseText);
                    $('#client_info_block').html('<p class="text-danger">Error loading client information.</p>');
                    $('#shipping_info_block').html('<p class="text-danger">Error loading shipping information.</p>');
                }
            });
        } else {
            $('#client_info_block').html('<p class="text-muted">Please select a client.</p>');
            $('#shipping_info_block').html('<p class="text-muted">Please select a client.</p>');
        }
    }

    // Fetch client info on page load if client is selected
    const preselectedClient = $('#client_id').val();
    console.log('Page loaded - Client ID:', preselectedClient);
    if (preselectedClient) {
        // Small delay to ensure DOM is fully ready
        setTimeout(function() {
            fetchClientInfo(preselectedClient);
        }, 100);
    }

    // Also fetch when client changes
    $('#client_id').on('change', function() {
        const clientId = $(this).val();
        console.log('Client changed to:', clientId);
        fetchClientInfo(clientId);
    });

    // ================ FIXED: GST/Non-GST Toggle Functionality ================
    $('input[name="gst_type"]').on('change', function() {
        const gstType = $(this).val();
        $('#gst_type_field').val(gstType);
        
        if (gstType === 'non_gst') {
            // Non-GST mode - hide tax column and tax details
            $('.add-table').addClass('non-gst-mode');
            $('.tax-details').hide();
            
            // Set all tax rates to 0 and recalculate
            $('.tax-rate').data('value', 0).val('0%');
            $('.service-tax-select, .product-tax-select').val('').prop('disabled', true);
            
            // Update tax display containers
            $('.tax-amount-line').text('$ 0.00');
            $('.tax-rate-line').text('0%');
            
            // Update hidden tax fields
            $('.tax-name').val('');
            
        } else {
            // GST mode - show tax column and tax details
            $('.add-table').removeClass('non-gst-mode');
            $('.tax-details').show();
            
            // Enable both tax dropdowns
            $('.service-tax-select, .product-tax-select').prop('disabled', false);
            
            // Restore original tax values for products and services
            $('.add-tbody tr').each(function() {
                const $row = $(this);
                const isService = $row.hasClass('service-row');
                const isProduct = $row.hasClass('product-row');
                
                if (isProduct) {
                    // For products: restore from dropdown data
                    const $productSelect = $row.find('.product-select');
                    const $productTaxSelect = $row.find('.product-tax-select');
                    const option = $productSelect.find('option:selected');
                    
                    if (option.val()) {
                        const tax = parseFloat(option.data('tax')) || 0;
                        const taxId = option.data('tax-id') || '';
                        const taxName = option.data('tax-name') || '';
                        
                        $row.find('.tax-rate').data('value', tax);
                        $row.find('.tax-name').val(taxName);
                        $row.find('.tax-rate').val(formatPercent(tax));
                        
                        // Update product tax dropdown selection
                        if (taxId) {
                            $productTaxSelect.val(taxId);
                        }
                    }
                    
                    // If no tax selected yet, set to first available tax
                    if (!$productTaxSelect.val() || $productTaxSelect.val() === '') {
                        const firstOption = $productTaxSelect.find('option:not(:first)').first();
                        if (firstOption.length) {
                            const tax = parseFloat(firstOption.data('rate')) || 0;
                            const taxId = firstOption.val();
                            const taxName = firstOption.text().split(' (')[0];
                            
                            $productTaxSelect.val(taxId);
                            $row.find('.tax-rate').data('value', tax);
                            $row.find('.tax-name').val(taxName);
                            $row.find('.tax-rate').val(formatPercent(tax));
                        }
                    }
                } else if (isService) {
                    // For services: restore from dropdown data or set default
                    const $serviceSelect = $row.find('.service-select');
                    const $serviceTaxSelect = $row.find('.service-tax-select');
                    
                    // If service dropdown has a selection, use its tax data
                    const serviceOption = $serviceSelect.find('option:selected');
                    if (serviceOption.val()) {
                        const tax = parseFloat(serviceOption.data('tax')) || 0;
                        const taxId = serviceOption.data('tax-id') || '';
                        const taxName = serviceOption.data('tax-name') || '';
                        
                        $row.find('.tax-rate').data('value', tax);
                        $row.find('.tax-name').val(taxName);
                        $row.find('.tax-rate').val(formatPercent(tax));
                        
                        // Also update the tax dropdown selection
                        if (taxId) {
                            $serviceTaxSelect.val(taxId);
                        }
                    }
                    
                    // If no tax selected yet, set to first available tax
                    if (!$serviceTaxSelect.val() || $serviceTaxSelect.val() === '') {
                        const firstOption = $serviceTaxSelect.find('option:not(:first)').first();
                        if (firstOption.length) {
                            const tax = parseFloat(firstOption.data('rate')) || 0;
                            const taxId = firstOption.val();
                            const taxName = firstOption.text().split(' (')[0];
                            
                            $serviceTaxSelect.val(taxId);
                            $row.find('.tax-rate').data('value', tax);
                            $row.find('.tax-name').val(taxName);
                            $row.find('.tax-rate').val(formatPercent(tax));
                        }
                    }
                }
            });
        }
        
        // Recalculate all rows
        $('.add-tbody tr').each(function() {
            calculateRow($(this));
        });
        
        calculateSummary();
    });

    // ================ UPDATED FUNCTIONS ================
    function formatCurrency(value) {
        if (value === '' || value === null || value === undefined) return '$ 0.00';
        const n = parseFloat(value);
        if (isNaN(n)) return '$ 0.00';
        return `$ ${n.toFixed(2)}`;
    }

    function formatPercent(value) {
        if (value === '' || value === null || value === undefined) return '0%';
        const n = parseFloat(value);
        if (isNaN(n)) return '0%';
        return `${n.toFixed(2)}%`;
    }

    function unformat(value) {
        if (value === '' || value === null || value === undefined) return 0;
        // Remove currency symbols, commas, percent signs, and spaces
        const n = parseFloat(String(value).replace(/[^0-9.-]/g, ''));
        return isNaN(n) ? 0 : n;
    }

    function loadProducts(target) {
        let productOptions = '<option value="">Select Product</option>';
        <?php foreach ($products as $product): ?>
        productOptions += `<option value="<?= $product['id'] ?>" 
                      data-price="<?= $product['selling_price'] ?>" 
                      data-hsn="<?= $product['code'] ?>"
                      data-tax="<?= $product['tax_rate'] ?>"
                      data-tax-id="<?= $product['tax_id'] ?>"
                      data-tax-name="<?= $product['tax_name'] ?>">
                      <?= $product['name'] ?>
                      </option>`;
        <?php endforeach; ?>
        
        if (target) {
            target.html(productOptions);
        }
        updateProductDropdowns();
    }

    function loadServices(target) {
        let serviceOptions = '<option value="">Select Service</option>';
        <?php foreach ($services as $service): ?>
        serviceOptions += `<option value="<?= $service['id'] ?>" 
                      data-price="<?= $service['selling_price'] ?>" 
                      data-hsn="<?= $service['code'] ?>"
                      data-tax="<?= $service['tax_rate'] ?>"
                      data-tax-id="<?= $service['tax_id'] ?>"
                      data-tax-name="<?= $service['tax_name'] ?>">
                      <?= $service['name'] ?>
                      </option>`;
        <?php endforeach; ?>
        
        if (target) {
            target.html(serviceOptions);
        }
        updateServiceDropdowns();
    }

    function updateProductDropdowns() {
        let selectedProducts = [];
        $('.product-select').each(function() {
            let val = $(this).val();
            if (val) selectedProducts.push(val);
        });

        $('.product-select').each(function() {
            let currentVal = $(this).val();
            $(this).find('option').each(function() {
                if ($(this).val() && selectedProducts.includes($(this).val()) && $(this).val() !== currentVal) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });
    }

    function updateServiceDropdowns() {
        let selectedServices = [];
        $('.service-select').each(function() {
            let val = $(this).val();
            if (val) selectedServices.push(val);
        });

        $('.service-select').each(function() {
            let currentVal = $(this).val();
            $(this).find('option').each(function() {
                if ($(this).val() && selectedServices.includes($(this).val()) && $(this).val() !== currentVal) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });
    }

    // =============================================
    // FIXED: SINGLE "Add New" event handler 
    // =============================================
    $(document).on('click', '.add-invoice-data', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        console.log('Add New clicked - adding ONE row only');
        
        const itemType = $('input[name="item_type"]:checked').val();
        const rowClass = itemType == 1 ? 'product-row' : 'service-row';
        const isNonGST = $('input[name="gst_type"]:checked').val() === 'non_gst';
        
        // Generate tax options HTML from PHP data
        let taxOptions = '<option value="">Select Tax</option>';
        <?php foreach ($taxRates as $tax): ?>
        taxOptions += `<option value="<?= $tax['id'] ?>" data-rate="<?= $tax['rate'] ?>"><?= $tax['name'] ?> (<?= $tax['rate'] ?>%)</option>`;
        <?php endforeach; ?>

        const newRow = `
            <tr class="${rowClass}">
                <!-- ADDED: Empty hidden field for new rows -->
                <td style="display:none;">
                    <input type="hidden" name="invoice_item_id[]" value="">
                </td>
                
                <td>
                    <div class="product-fields">
                        <select class="form-select product-select" name="item_id[]">
                            <option value="">Select Product</option>
                        </select>
                        <input type="hidden" class="tax-name" name="tax_name[]" value="">
                        <input type="hidden" name="item_type_row[]" value="product">
                        <input type="hidden" name="service_name[]" value="">
                    </div>
                    <div class="service-fields">
                        <select class="form-select service-select" name="item_id[]">
                            <option value="">Select Service</option>
                        </select>
                        <input type="text" class="form-control service-name-input service-custom-input" name="service_name[]" placeholder="Or enter custom service name">
                        <input type="hidden" class="tax-name" name="tax_name[]" value="">
                        <input type="hidden" name="item_type_row[]" value="service">
                    </div>
                </td>
                <td>
                    <input type="number" class="form-control quantity ${itemType == 0 ? 'service-quantity' : ''}" name="quantity[]" value="${itemType == 1 ? '1' : ''}" ${itemType == 1 ? 'min="1"' : 'placeholder="Optional"'}">
                </td>
                <td>
                    <input type="text" class="form-control hsn-code" name="code[]" readonly>
                </td>
                <td>
                    <div class="product-fields">
                        <input type="text" class="form-control selling-price" name="selling_price[]" data-value="0" value="$ 0.00">
                    </div>
                    <div class="service-fields">
                        <input type="text" class="form-control service-price-input" name="selling_price[]" data-value="0" value="$ 0.00" placeholder="0.00">
                    </div>
                </td>
                <td class="tax-column">
                    <div class="product-fields">
                        <!-- Product tax dropdown -->
                        <select class="form-select product-tax-select" name="tax_id[]" ${isNonGST ? 'disabled' : ''}>
                            ${taxOptions}
                        </select>
                        <input type="hidden" class="tax-rate" name="rate[]" data-value="${isNonGST ? '0' : '0'}" value="${isNonGST ? '0%' : '0%'}">
                        <div class="tax-display-container mt-2">
                            <div class="tax-amount-line">$ 0.00</div>
                            <div class="tax-rate-line">${isNonGST ? '0%' : '0%'}</div>
                        </div>
                    </div>
                    <div class="service-fields">
                        <select class="form-select service-tax-select" name="tax_id[]" ${isNonGST ? 'disabled' : ''}>
                            ${taxOptions}
                        </select>
                        <input type="hidden" class="tax-rate" name="rate[]" data-value="${isNonGST ? '0' : '0'}" value="${isNonGST ? '0%' : '0%'}">
                        <div class="tax-display-container mt-2">
                            <div class="tax-amount-line">$ 0.00</div>
                            <div class="tax-rate-line">${isNonGST ? '0%' : '0%'}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control amount" name="amount[]" data-value="0" value="$ 0.00" readonly>
                </td>
                <td>
                    <a href="javascript:void(0);" class="remove-table"><i class="isax isax-trash"></i></a>
                </td>
            </tr>
        `;
        
        $('.add-tbody').append(newRow);
        
        // Load appropriate dropdown
        if (itemType == 1) {
            const $productSelect = $('.add-tbody tr:last .product-select');
            loadProducts($productSelect);
        } else {
            const $serviceSelect = $('.add-tbody tr:last .service-select');
            loadServices($serviceSelect);
        }
        
        updateProductDropdowns();
        updateServiceDropdowns();
        
        // Initialize calculations for the new row
        setTimeout(function() {
            const $newRow = $('.add-tbody tr:last');
            calculateRow($newRow);
        }, 100);
        
        return false;
    });

    // Item events - FIXED for product and service handling
    $(document).on('change', '.product-select', function() {
        const $row = $(this).closest('tr');
        const option = $(this).find('option:selected');

        if (option.val()) {
            const price = parseFloat(option.data('price')) || 0;
            const hsnCode = option.data('hsn') || '';
            const tax = parseFloat(option.data('tax')) || 0;
            const taxId = option.data('tax-id') || '';
            const taxName = option.data('tax-name') || '';

            $row.find('.hsn-code').val(hsnCode);
            $row.find('.tax-name').val(taxName);
            
            // Check if we're in Non-GST mode
            const isNonGST = $('input[name="gst_type"]:checked').val() === 'non_gst';
            const effectiveTax = isNonGST ? 0 : tax;
            
            $row.find('.selling-price').data('value', price).val(formatCurrency(price));
            $row.find('.tax-rate').data('value', effectiveTax).val(formatPercent(effectiveTax));
            
            // Set product tax select if available and not in Non-GST mode
            if (taxId && !isNonGST) {
                $row.find('.product-tax-select').val(taxId);
            } else {
                $row.find('.product-tax-select').val('');
            }

            // Set default quantity to 1 for new product rows if empty
            const $quantity = $row.find('.quantity');
            if (!$quantity.val() || $quantity.val() === '' || $quantity.val() === '0') {
                $quantity.val('1');
            }

            calculateRow($row);
        } else {
            resetRow($row);
        }

        updateProductDropdowns();
    });

    // Handle product tax selection
    $(document).on('change', '.product-tax-select', function() {
        const $row = $(this).closest('tr');
        const selectedOption = $(this).find('option:selected');
        
        // Check if we're in Non-GST mode
        const isNonGST = $('input[name="gst_type"]:checked').val() === 'non_gst';
        
        if (!isNonGST && selectedOption.val()) {
            const taxRate = parseFloat(selectedOption.data('rate')) || 0;
            const taxId = selectedOption.val();
            const taxName = selectedOption.text().split(' (')[0];
            
            $row.find('.tax-rate').data('value', taxRate).val(formatPercent(taxRate));
            $row.find('.tax-name').val(taxName);
        } else {
            // Non-GST mode or no selection
            $row.find('.tax-rate').data('value', 0).val('0%');
            $row.find('.tax-name').val('');
        }
        
        calculateRow($row);
    });

    // Handle service selection - DON'T auto-fill service name
    $(document).on('change', '.service-select', function() {
        const $row = $(this).closest('tr');
        const option = $(this).find('option:selected');

        if (option.val()) {
            const price = parseFloat(option.data('price')) || 0;
            const hsnCode = option.data('hsn') || '';
            const tax = parseFloat(option.data('tax')) || 0;
            const taxId = option.data('tax-id') || '';
            const taxName = option.data('tax-name') || '';

            // Check if we're in Non-GST mode
            const isNonGST = $('input[name="gst_type"]:checked').val() === 'non_gst';
            const effectiveTax = isNonGST ? 0 : tax;
            
            // Update HSN code and tax fields
            $row.find('.hsn-code').val(hsnCode);
            $row.find('.tax-name').val(taxName);
            
            $row.find('.service-price-input').data('value', price).val(formatCurrency(price));
            $row.find('.tax-rate').data('value', effectiveTax).val(formatPercent(effectiveTax));
            
            // DON'T auto-fill service name input
            // Leave service name input as is (could be empty or custom text)
            
            // Set service tax select if available and not in Non-GST mode
            if (taxId && !isNonGST) {
                $row.find('.service-tax-select').val(taxId);
            } else {
                $row.find('.service-tax-select').val('');
            }

            calculateRow($row);
        } else {
            // If no service selected from dropdown, clear related fields
            // BUT don't clear the manually typed service name
            $row.find('.hsn-code').val('');
            $row.find('.tax-name').val('');
            $row.find('.tax-rate').data('value', 0).val('0%');
            $row.find('.service-tax-select').val('');
            
            calculateRow($row);
        }

        updateServiceDropdowns();
    });

    // Handle service tax selection
    $(document).on('change', '.service-tax-select', function() {
        const $row = $(this).closest('tr');
        const selectedOption = $(this).find('option:selected');
        
        // Check if we're in Non-GST mode
        const isNonGST = $('input[name="gst_type"]:checked').val() === 'non_gst';
        
        if (!isNonGST && selectedOption.val()) {
            const taxRate = parseFloat(selectedOption.data('rate')) || 0;
            const taxId = selectedOption.val();
            const taxName = selectedOption.text().split(' (')[0];
            
            $row.find('.tax-rate').data('value', taxRate).val(formatPercent(taxRate));
            $row.find('.tax-name').val(taxName);
        } else {
            // Non-GST mode or no selection
            $row.find('.tax-rate').data('value', 0).val('0%');
            $row.find('.tax-name').val('');
        }
        
        calculateRow($row);
    });

    // Handle service name input - DON'T clear dropdown when typing
    $(document).on('input', '.service-name-input', function() {
        const $row = $(this).closest('tr');
        calculateRow($row);
    });

    // Handle service price input
    $(document).on('input', '.service-price-input', function() {
        const $row = $(this).closest('tr');
        const price = unformat($(this).val());
        $row.find('.service-price-input').data('value', price).val(formatCurrency(price));
        calculateRow($row);
    });

    $(document).on('input', '.quantity', function() {
        calculateRow($(this).closest('tr'));
    });

    $(document).on('click', '.remove-table', function(e) {
        e.preventDefault();
        $(this).closest('tr').remove();
        calculateSummary();
        updateProductDropdowns();
        updateServiceDropdowns();
    });

    /* =========================
       Calculations
    ========================== */
    function calculateRow($row) {
        const qtyInput = $row.find('.quantity');
        let qty = unformat(qtyInput.val());
        
        // For services, if quantity is empty or 0, treat as 1 for calculation display
        // but keep the field empty for user input
        const isService = $row.hasClass('service-row');
        const isProduct = $row.hasClass('product-row');
        
        // For display calculation, use 1 if service quantity is empty
        const calcQty = (isService && (qty === 0 || qtyInput.val() === '')) ? 1 : qty;
        
        const price = isService ? 
            ($row.find('.service-price-input').data('value') || 0) : 
            ($row.find('.selling-price').data('value') || 0);
        const taxRate = $row.find('.tax-rate').data('value') || 0;

        const lineSubtotal = calcQty * price;
        const lineTaxAmount = lineSubtotal * (taxRate / 100);
        const lineTotal = lineSubtotal + lineTaxAmount;

        // Update the tax display container
        const taxAmountFormatted = formatCurrency(lineTaxAmount);
        const taxRateFormatted = formatPercent(taxRate);
        
        $row.find('.tax-amount-line').text(taxAmountFormatted);
        $row.find('.tax-rate-line').text(taxRateFormatted);
        
        // Ensure data-value is set properly
        $row.find('.amount').data('value', lineTotal).val(formatCurrency(lineTotal));
        
        calculateSummary();
    }

    function calculateTaxAmount($row) {
        const isService = $row.hasClass('service-row');
        const qtyInput = $row.find('.quantity');
        let qty = unformat(qtyInput.val());
        
        // For services, if quantity is empty or 0, treat as 1 for calculation
        if (isService && (qty === 0 || qtyInput.val() === '')) {
            qty = 1;
        }
        
        // For products with empty quantity, use 0 for calculation
        if (!isService && qtyInput.val() === '') {
            qty = 0;
        }
        
        const price = isService ? 
            ($row.find('.service-price-input').data('value') || 0) : 
            ($row.find('.selling-price').data('value') || 0);
        const taxRate = $row.find('.tax-rate').data('value') || 0;
        
        const lineSubtotal = qty * price;
        const lineTaxAmount = lineSubtotal * (taxRate / 100);
        
        return lineTaxAmount;
    }

    function getShippingCharge() {
        const $ship = $('#shipping-charge');
        if (!$ship.length) return 0;
        // Prefer stored numeric if available
        const stored = $ship.data('value');
        if (stored !== undefined) return parseFloat(stored) || 0;
        // Fallback: parse current field
        return unformat($ship.val());
    }

    function calculateSummary() {
        let sub = 0, taxGroups = {}, grandTotal = 0;

        $('.add-tbody tr').each(function() {
            const isService = $(this).hasClass('service-row');
            const p = isService ? 
                ($(this).find('.service-price-input').data('value') || 0) : 
                ($(this).find('.selling-price').data('value') || 0);
            const qtyInput = $(this).find('.quantity');
            let q = unformat(qtyInput.val());
            
            // For services with empty quantity, use 1 for calculation
            if (isService && (q === 0 || qtyInput.val() === '')) {
                q = 1;
            }
            
            // For products with empty quantity, use 0 for now (will be set to 1 before submit)
            if (!isService && qtyInput.val() === '') {
                q = 0;
            }
            
            const t = $(this).find('.tax-rate').data('value') || 0;
            const taxName = $(this).find('.tax-name').val() || 'Tax';

            const lineSubtotal = p * q;
            const lineTaxAmount = (lineSubtotal * t / 100);
            const lineTotal = lineSubtotal + lineTaxAmount;

            sub += lineSubtotal;
            grandTotal += lineTotal;

            if (t > 0) {
                const taxKey = `${taxName} (${t}%)`;
                if (!taxGroups[taxKey]) taxGroups[taxKey] = 0;
                taxGroups[taxKey] += lineTaxAmount;
            }
        });

        const shippingCharge = getShippingCharge();
        let taxHtml = "";

        // Only show tax details in GST mode
        const isNonGST = $('input[name="gst_type"]:checked').val() === 'non_gst';
        if (!isNonGST) {
            $('.add-tbody tr').each(function(index) {
                const isService = $(this).hasClass('service-row');
                const p = isService ? 
                    ($(this).find('.service-price-input').data('value') || 0) : 
                    ($(this).find('.selling-price').data('value') || 0);
                const qtyInput = $(this).find('.quantity');
                let q = unformat(qtyInput.val());
                
                if (isService && (q === 0 || qtyInput.val() === '')) {
                    q = 1;
                }
                
                if (!isService && qtyInput.val() === '') {
                    q = 0;
                }
                
                const t = $(this).find('.tax-rate').data('value') || 0;
                const taxName = $(this).find('.tax-name').val() || 'Tax';

                const lineSubtotal = p * q;
                const lineTaxAmount = (lineSubtotal * t / 100);

                if (t > 0 && lineTaxAmount > 0) {
                    const taxLabel = `${taxName} (${t}%)`;
                    taxHtml += `
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fs-14 fw-semibold">${taxLabel}</h6>
                            <h6 class="fs-14 fw-semibold">${formatCurrency(lineTaxAmount)}</h6>
                        </div>`;
                }
            });
        }

        $('.tax-details').html(taxHtml);

        const totalAll = grandTotal + shippingCharge;

        $('#subtotal-amount').text(formatCurrency(sub));
        $('#total-amount').text(formatCurrency(totalAll));

        // Hidden numeric fields for backend
        $('#subtotal-amount-field').val(sub.toFixed(2));
        $('#tax-amount-field').val(Object.values(taxGroups).reduce((a,b)=>a+b,0).toFixed(2));
        $('#total-amount-field').val(totalAll.toFixed(2));
    }

    function resetRow($row) {
        const isService = $row.hasClass('service-row');
        $row.find('.quantity').val(isService ? '' : '1').removeClass('service-quantity');
        
        // For services, don't clear the service name input
        if (isService) {
            $row.find('.hsn-code, .selling-price, .tax-rate, .amount, .service-price-input').val('').removeData('value');
            // Keep service-name-input as is
        } else {
            $row.find('.hsn-code, .selling-price, .tax-rate, .amount, .service-name-input, .service-price-input').val('').removeData('value');
        }
        
        $row.find('.tax-name').val('');
        $row.find('.tax-amount-line').text('');
        $row.find('.tax-rate-line').text('');
        $row.find('.product-tax-select, .service-tax-select').val('');
        calculateSummary();
    }

    // Item type change handler
    $('input[name="item_type"]').on('change', function() {
        const itemType = $(this).val();
        
        // Update all existing rows
        $('.add-tbody tr').each(function() {
            const $row = $(this);
            
            if (itemType == 1) {
                // Switch to product mode
                $row.removeClass('service-row').addClass('product-row');
                $row.find('.quantity').val(1).removeClass('service-quantity');
            } else {
                // Switch to service mode
                $row.removeClass('product-row').addClass('service-row');
                $row.find('.quantity').val('').addClass('service-quantity');
            }
            
            // Reset values when switching types
            $row.find('.hsn-code').val('');
            $row.find('.selling-price, .service-price-input').val('').removeData('value');
            $row.find('.tax-rate').val('').removeData('value');
            $row.find('.amount').val('').removeData('value');
            $row.find('.tax-name').val('');
            $row.find('.tax-amount-line').text('');
            $row.find('.tax-rate-line').text('');
            $row.find('.product-tax-select, .service-tax-select').val('');
            
            // Clear service name input when switching from product to service
            if (itemType == 0) {
                $row.find('.service-name-input').val('');
            }
        });
        
        calculateSummary();
    });

    // Initialize all rows on page load
    function initializeRowCalculations() {
        $('.add-tbody tr').each(function() {
            const $row = $(this);
            const isService = $row.hasClass('service-row');
            const isProduct = $row.hasClass('product-row');
            
            // Check GST mode
            const isNonGST = $('input[name="gst_type"]:checked').val() === 'non_gst';
            
            // Ensure data-values are set for existing rows
            if (isService) {
                const priceInput = $row.find('.service-price-input');
                let priceValue = priceInput.data('value');
                if (!priceValue || priceValue === 0) {
                    priceValue = unformat(priceInput.val());
                    priceInput.data('value', priceValue);
                    priceInput.val(formatCurrency(priceValue));
                }
                
                // Disable service tax dropdown in Non-GST mode
                if (isNonGST) {
                    $row.find('.service-tax-select').prop('disabled', true).val('');
                }
            } else if (isProduct) {
                const priceInput = $row.find('.selling-price');
                let priceValue = priceInput.data('value');
                if (!priceValue || priceValue === 0) {
                    priceValue = unformat(priceInput.val());
                    priceInput.data('value', priceValue);
                    priceInput.val(formatCurrency(priceValue));
                }
                
                // Disable product tax dropdown in Non-GST mode
                if (isNonGST) {
                    $row.find('.product-tax-select').prop('disabled', true).val('');
                }
            }
            
            const taxRateInput = $row.find('.tax-rate');
            let taxRateValue = taxRateInput.data('value');
            if (!taxRateValue && taxRateValue !== 0) {
                taxRateValue = unformat(taxRateInput.val());
                taxRateInput.data('value', taxRateValue);
                taxRateInput.val(formatPercent(taxRateValue));
            }
            
            const amountInput = $row.find('.amount');
            let amountValue = amountInput.data('value');
            if (!amountValue || amountValue === 0) {
                amountValue = unformat(amountInput.val());
                amountInput.data('value', amountValue);
                amountInput.val(formatCurrency(amountValue));
            }
            
            // Ensure product rows have quantity set
            if (isProduct) {
                const $quantity = $row.find('.quantity');
                if (!$quantity.val() || $quantity.val() === '' || $quantity.val() === '0') {
                    $quantity.val('1');
                }
            }
            
            // Update tax display
            const taxAmount = calculateTaxAmount($row);
            $row.find('.tax-amount-line').text(formatCurrency(taxAmount));
            $row.find('.tax-rate-line').text(formatPercent(taxRateValue));
            
            // Recalculate the row
            calculateRow($row);
        });
        
        calculateSummary();
    }

    // Trigger GST toggle on page load to apply initial state
    setTimeout(function() {
        const initialGstType = $('input[name="gst_type"]:checked').val();
        $('#gst_type_field').val(initialGstType);
        
        if (initialGstType === 'non_gst') {
            // Apply Non-GST mode on page load
            $('.add-table').addClass('non-gst-mode');
            $('.tax-details').hide();
            
            // Set all tax rates to 0 and recalculate
            $('.tax-rate').data('value', 0).val('0%');
            $('.service-tax-select, .product-tax-select').prop('disabled', true).val('');
            
            // Update tax display containers
            $('.tax-amount-line').text('$ 0.00');
            $('.tax-rate-line').text('0%');
            
            // Update hidden tax fields
            $('.tax-name').val('');
            
            // Recalculate all rows
            $('.add-tbody tr').each(function() {
                calculateRow($(this));
            });
            
            calculateSummary();
        } else {
            // For GST mode, ensure calculations run on page load
            initializeRowCalculations();
            
            // Enable both tax dropdowns
            $('.service-tax-select, .product-tax-select').prop('disabled', false);
            
            // Ensure product rows have tax dropdown selected
            $('.product-row').each(function() {
                const $row = $(this);
                const $productTaxSelect = $row.find('.product-tax-select');
                const $taxName = $row.find('.tax-name');
                
                if (!$productTaxSelect.val() && $taxName.val() && $taxName.val() !== '') {
                    // Find tax option by name
                    const taxName = $taxName.val();
                    const $option = $productTaxSelect.find('option').filter(function() {
                        return $(this).text().includes(taxName);
                    });
                    if ($option.length) {
                        $productTaxSelect.val($option.val());
                    }
                } else if (!$productTaxSelect.val()) {
                    // Set to first available tax
                    const firstOption = $productTaxSelect.find('option:not(:first)').first();
                    if (firstOption.length) {
                        $productTaxSelect.val(firstOption.val());
                    }
                }
            });
            
            // Ensure service rows have tax dropdown selected
            $('.service-row').each(function() {
                const $row = $(this);
                const $serviceTaxSelect = $row.find('.service-tax-select');
                const $taxName = $row.find('.tax-name');
                
                if (!$serviceTaxSelect.val() && $taxName.val() && $taxName.val() !== '') {
                    // Find tax option by name
                    const taxName = $taxName.val();
                    const $option = $serviceTaxSelect.find('option').filter(function() {
                        return $(this).text().includes(taxName);
                    });
                    if ($option.length) {
                        $serviceTaxSelect.val($option.val());
                    }
                } else if (!$serviceTaxSelect.val()) {
                    // Set to first available tax
                    const firstOption = $serviceTaxSelect.find('option:not(:first)').first();
                    if (firstOption.length) {
                        $serviceTaxSelect.val(firstOption.val());
                    }
                }
            });
        }
    }, 100);

    // Initial pass - Run calculations on page load
    setTimeout(function() {
        initializeRowCalculations();
    }, 500);

    updateProductDropdowns();
    updateServiceDropdowns();
    
    console.log('Invoice edit initialization complete - SINGLE ADD NEW FIXED');
});
</script>

<script>
    $(document).ready(function () {
        $('#document-upload').on('change', function () {
            let files = $(this)[0].files;
            if (files.length === 0) {
                $('#file-count-label').text('');
            } else if (files.length === 1) {
                $('#file-count-label').text(files[0].name);
            } else {
                $('#file-count-label').text(`${files.length} files selected`);
            }
        });
    });
</script>

</body>
</html>