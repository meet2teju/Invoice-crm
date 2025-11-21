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
$quotation_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($quotation_id <= 0) {
    $_SESSION['error'] = "Invalid quotation ID.";
    header("Location: quotations.php");
    exit();
}

// Fetch quotation data
$query = "SELECT * FROM quotation WHERE id = $quotation_id";
$result = mysqli_query($conn, $query);

// Check if quotation exists
if (!$result || mysqli_num_rows($result) === 0) {
    $_SESSION['error'] = "Quotation not found.";
    header("Location: quotations.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

// Fetch dropdown data
$clients = mysqli_query($conn, "SELECT id, first_name,company_name  FROM client WHERE is_deleted = 0");
$users = mysqli_query($conn,  "SELECT login.id, login.name FROM login
        JOIN user_role ON login.role_id = user_role.id
        WHERE login.is_deleted = 0
        ORDER BY login.name ASC");
$projects = mysqli_query($conn, "SELECT id, project_name FROM project WHERE is_deleted = 0");
$documents = mysqli_query($conn, "SELECT id, document FROM quotation_document WHERE quotation_id = $quotation_id");

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

// Radio precheck
$is_product = ($row['item_type'] == 1) ? 'checked' : '';
$is_service = ($row['item_type'] == 0) ? 'checked' : '';

// Check if we're in Non-GST mode for initial display
$isNonGST = ($row['gst_type'] ?? 'gst') === 'non_gst';
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
    </style>
</head>

<body>
    <div class="main-wrapper">
        <?php include 'layouts/menu.php'; ?>

        <div class="page-wrapper">
            <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
<?php endif; ?>
            <div class="content">
                <div class="row">
                    <div class="col-md-12 mx-auto">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6>Edit Quotations</h6>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="gst-toggle-group">
                                        <span class="gst-toggle-label">GST Type:</span>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gst_type" id="gst-enabled" value="gst" <?= ($row['gst_type'] ?? 'gst') === 'gst' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="gst-enabled">GST</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gst_type" id="gst-disabled" value="non_gst" <?= ($row['gst_type'] ?? 'gst') === 'non_gst' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="gst-disabled">Non-GST</label>
                                        </div>
                                    </div>
                                    <!-- <a href="javascript:void(0);" class="btn btn-outline-white d-inline-flex align-items-center"><i class="isax isax-eye me-1"></i>Preview</a> -->
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form action="process/action_edit_quotation.php" method="POST" enctype="multipart/form-data" id="form">
                                        <input type="hidden" name="id" value="<?= $quotation_id ?>">
                                        <input type="hidden" name="gst_type" id="gst_type_field" value="<?= $row['gst_type'] ?? 'gst' ?>">
                                        
                                        <div class="border-bottom mb-3 pb-1">
                                            <div class="row gx-3">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="mb-3">
                                                      <label class="form-label">Client Name <span class="text-danger">*</span></label>
                                                      <select class="form-select select2" name="client_id" id="client_id" >
                                                          <option value="">Select Client</option>
                                                        <?php 
                                                        $clients = mysqli_query($conn, "SELECT id, first_name,company_name  FROM client WHERE is_deleted = 0");
                                                        while ($client = mysqli_fetch_assoc($clients)) {
                                                            $displayName = $client['first_name'];
                                                            if (!empty($client['company_name'])) {
                                                                $displayName .= ' - ' . $client['company_name'];
                                                            }
                                                            $selected = ($client['id'] == $row['client_id']) ? 'selected' : '';
                                                            echo "<option value='{$client['id']}' $selected>{$displayName}</option>";
                                                        } ?>
                                                      </select>
                                                      <span class="text-danger error-text" id="clientname_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Quotation ID</label>
                                                        <input type="text" class="form-control" name="quotation_id" value="<?= htmlspecialchars($row['quotation_id']) ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Reference Name</label>
                                                        <input type="text" class="form-control" name="reference_name" id="reference_name" value="<?= htmlspecialchars($row['reference_name']) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6">
                                                  <div class="mb-3">
                                                    <label class="form-label">Quotation Date<span class="text-danger">*</span></label>
                                                    <div class="input-group position-relative mb-3">
                                                        <input type="text" class="form-control datepicker" id="quotation_date" placeholder="dd/mm/yyyy" name="quotation_date" value="<?= htmlspecialchars($row['quotation_date']) ?>">
                                                        <span class="input-icon-addon fs-16 text-gray-9">
                                                            <i class="isax isax-calendar-2"></i>
                                                        </span>
                                                    </div>
                                                    <span class="text-danger error-text" id="quotation_date_error"></span>
                                                  </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6">
                                                  <div class="mb-3">
                                                    <label class="form-label">Expire Date<span class="text-danger">*</span></label>
                                                    <div class="input-group position-relative mb-3">
                                                        <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="expiry_date" value="<?= htmlspecialchars($row['expiry_date']) ?>">
                                                        <span class="input-icon-addon fs-16 text-gray-9">
                                                            <i class="isax isax-calendar-2"></i>
                                                        </span>
                                                    </div>
                                                    <span class="text-danger error-text" id="expiry_date_error"></span>
                                                  </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6">
                                                  <div class="mb-3">
                                                    <label class="form-label">Salesperson </label>
                                                    <select class="form-select select2" name="user_id" id="user_id">
                                                        <option value="">Select Salesperson</option>
                                                        <?php 
                                                        $users = mysqli_query($conn,  "SELECT login.id, login.name FROM login
                                                                JOIN user_role ON login.role_id = user_role.id
                                                                WHERE login.is_deleted = 0
                                                                ORDER BY login.name ASC");
                                                        while ($user = mysqli_fetch_assoc($users)) {
                                                            $selected = ($user['id'] == $row['user_id']) ? 'selected' : '';
                                                            echo "<option value='{$user['id']}' $selected>{$user['name']}</option>";
                                                        } ?>
                                                    </select>
                                                    <span class="text-danger error-text" id="username_error"></span>
                                                  </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6">
                                                  <div class="mb-3">
                                                    <label class="form-label">Project Name </label>
                                                    <select class="form-select select2" name="project_id" id="project_id">
                                                        <option value="">Select Project</option>
                                                        <?php 
                                                        $projects = mysqli_query($conn, "SELECT id, project_name FROM project WHERE is_deleted = 0");
                                                        while ($project = mysqli_fetch_assoc($projects)) {
                                                            $selected = ($project['id'] == $row['project_id']) ? 'selected' : '';
                                                            echo "<option value='{$project['id']}' $selected>{$project['project_name']}</option>";
                                                        } ?> 
                                                    </select>
                                                    <span class="text-danger error-text" id="projectname_error"></span>
                                                  </div>
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
                                                                <div id="client_info_block">
                                                                    <p class="text-muted mb-0">Client information will appear here after selection</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="card shadow-none">
                                                        <div class="card-body">
                                                            <h6 class="mb-3">Bill From</h6>
                                                            <div class="bg-light border rounded p-3 d-flex align-items-start">
                                                                <div id="shipping_info_block">
                                                                    <p class="text-muted mb-0">Shipping information will appear here after selection</p>
                                                                </div>
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
                                                <table class="table mb-0 add-table <?= $isNonGST ? 'non-gst-mode' : '' ?>">
                                                    <thead class="table-dark">
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
                                                        $quotation_id = $_GET['id'];
                                                        // FIXED QUERY: Join product table for both product_id AND service_id
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
                                                                    FROM quotation_item qi
                                                                    LEFT JOIN product p ON qi.product_id = p.id
                                                                    LEFT JOIN product s ON qi.service_id = s.id  -- Join for services too
                                                                    LEFT JOIN tax t ON qi.tax_id = t.id
                                                                    WHERE qi.quotation_id = $quotation_id AND qi.is_deleted = 0";

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
                                                            $displayTaxRate = $isNonGST ? 0 : $taxRate;
                                                            $displayLineTax = $isNonGST ? 0 : $lineTax;
                                                            $displayAmount = $isNonGST ? $lineSubtotal : $amount;
                                                            
                                                            // Determine row class and values
                                                            $rowClass = $isProduct ? 'product-row' : 'service-row';
                                                            $quantityClass = $isProduct ? '' : 'service-quantity';
                                                            $quantityValue = $isProduct ? $qty : ($qty > 0 ? $qty : '');
                                                            $quantityPlaceholder = $isProduct ? '' : 'Optional';
                                                        ?>
                                                            <tr class="<?= $rowClass ?>">
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
                                                                        <input type="hidden" class="tax-id" name="tax_id[]" value="<?= $isNonGST ? 0 : $taxId ?>">
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
                                                                        <input type="hidden" class="tax-id" name="tax_id[]" value="<?= $isNonGST ? 0 : $taxId ?>">
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
                                                                        <input type="text" class="form-control tax-rate" name="rate[]"
                                                                            value="<?= number_format($displayTaxRate, 2) . '%' ?>" 
                                                                            data-value="<?= $displayTaxRate ?>" style="display: none;">
                                                                        <div class="tax-display-container">
                                                                            <div class="tax-amount-line"><?= '$ ' . number_format($displayLineTax, 2) ?></div>
                                                                            <div class="tax-rate-line"><?= number_format($displayTaxRate, 2) . '%' ?></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="service-fields">
                                                                        <select class="form-select service-tax-select" name="tax_id[]">
                                                                            <option value="">Select Tax</option>
                                                                            <?php foreach ($taxRates as $tax): ?>
                                                                            <option value="<?= $tax['id'] ?>" 
                                                                                data-rate="<?= $tax['rate'] ?>"
                                                                                <?= (!$isNonGST && $tax['id'] == $taxId) ? 'selected' : '' ?>>
                                                                                <?= $tax['name'] ?> (<?= $tax['rate'] ?>%)
                                                                            </option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                        <input type="hidden" class="tax-rate" name="rate[]" data-value="<?= $displayTaxRate ?>">
                                                                        <input type="hidden" class="tax-name" name="tax_name[]" value="<?= htmlspecialchars($taxName) ?>">
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
                                                                    <a class="nav-link d-inline-flex align-items-center border fs-12 fw-semibold rounded-2" data-bs-toggle="tab" data-bs-target="#documents" href="javascript:void(0);"><i class="isax isax-bank me-1"></i>Upload Documents</a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content">
                                                                <div class="tab-pane active show" id="notes" role="tabpanel">
                                                                    <label class="form-label">Client Notes</label>
                                                                    <textarea class="form-control" name="client_note"><?= htmlspecialchars($row['client_note']) ?></textarea>
                                                                </div>
                                                                <div class="tab-pane fade" id="terms" role="tabpanel">
                                                                    <label class="form-label">Terms & Conditions</label>
                                                                    <textarea class="form-control" name="description"><?= htmlspecialchars($row['description']) ?></textarea>
                                                                </div>
                                                                <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                                                    <div class="file-upload drag-file w-100 h-auto py-3 d-flex align-items-center justify-content-center flex-column">
                                                                        <span class="upload-img d-block"><i class="isax isax-image text-primary me-1"></i>Upload Documents</span>
                                                                        <input type="file" class="form-control" name="document[]" id="document-upload" multiple>
                                                                        <span id="file-count-label" class="mt-2 text-muted"></span>
                                                                    </div>
                                                                    <span id="document_error" class="text-danger error-text"></span>
                                                                      <?php if (mysqli_num_rows($documents) > 0): ?>
                                                                   <div class="mt-3 w-100">
                                                                    <label class="form-label">Uploaded Documents:</label>
                                                                    <ul class="list-group">
                                                                        <?php while ($doc = mysqli_fetch_assoc($documents)): ?>
                                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                <a href="../uploads/<?= htmlspecialchars($doc['document']) ?>" target="_blank">
                                                                                    <?= htmlspecialchars($doc['document']) ?>
                                                                                </a>
                                                                            </li>
                                                                        <?php endwhile; ?>
                                                                    </ul>
                                                                </div>
                                                        <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <div class="col-lg-5">
                                                <?php
                                                // Calculate display amounts for Non-GST mode
                                                $displaySubAmount = $isNonGST ? ($row['amount'] - $row['tax_amount']) : $row['amount'];
                                                $displayTotalAmount = $isNonGST ? ($row['total_amount'] - $row['tax_amount']) : $row['total_amount'];
                                                ?>
                                                <input type="hidden" name="sub_amount" id="subtotal-amount-field" value="<?= $displaySubAmount ?>">
                                                <input type="hidden" name="tax_amount" id="tax-amount-field" value="<?= $isNonGST ? 0 : $row['tax_amount'] ?>">
                                                <input type="hidden" name="total_amount" id="total-amount-field" value="<?= $displayTotalAmount ?>">

                                                <div class="mb-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <h6 class="fs-14 fw-semibold">Amount</h6>
                                                        <h6 class="fs-14 fw-semibold" id="subtotal-amount"><?= '$ ' . number_format($displaySubAmount, 2) ?></h6>
                                                    </div>
                                                     <div class="tax-details" style="<?= $isNonGST ? 'display: none !important;' : '' ?>">
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
                                            </div>

                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                             <a href="quotations.php" class="btn btn-outline-white">Cancel</a>
                                            <button type="submit" name="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include 'layouts/footer.php'; ?>
        </div>
    </div>

    <?php include 'layouts/vendor-scripts.php'; ?>
<!-- Additional JS for datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <script>
    // Remove ALL existing click handlers from add button
    $(document).off('click', '.add-invoice-data');
    $('.add-invoice-data').off('click');
    
    $(document).ready(function() {
        console.log('Document ready - initializing...');

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

        // Fetch client billing & shipping info on page load
        function fetchClientInfo(clientId) {
            if (clientId) {
                $.ajax({
                    url: 'process/fetch_client_full_info.php',
                    type: 'POST',
                    data: { client_id: clientId },
                    dataType: 'json',
                    success: response => {
                        $('#client_info_block').html(response.billing_html);
                        $('#shipping_info_block').html(response.shipping_html);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching client info:', error);
                        $('#client_info_block').html('<p class="text-muted">Client information not available</p>');
                        $('#shipping_info_block').html('<p class="text-muted">Shipping information not available</p>');
                    }
                });
            } else {
                $('#client_info_block').html('<p class="text-muted">Please select a client</p>');
                $('#shipping_info_block').html('<p class="text-muted">Please select a client</p>');
            }
        }

        // Fetch client info when page loads
        const initialClientId = $('#client_id').val();
        fetchClientInfo(initialClientId);

        // Fetch client info when client selection changes
        $('#client_id').on('change', function() {
            const clientId = $(this).val();
            fetchClientInfo(clientId);
        });

        // GST/Non-GST toggle functionality
        $('input[name="gst_type"]').on('change', function() {
            const gstType = $(this).val();
            $('#gst_type_field').val(gstType);
            
            if (gstType === 'non_gst') {
                // Non-GST mode - hide tax column and tax details
                $('.add-table').addClass('non-gst-mode');
                $('.tax-details').hide();
                
                // Set all tax rates to 0 and recalculate
                $('.tax-rate').data('value', 0).val('0%');
                $('.service-tax-select').val('');
                
                // Update tax display containers
                $('.tax-amount-line').text('$ 0.00');
                $('.tax-rate-line').text('0%');
                
            } else {
                // GST mode - show tax column and tax details
                $('.add-table').removeClass('non-gst-mode');
                $('.tax-details').show();
                
                // Restore original tax values for products
                $('.product-select').each(function() {
                    const $row = $(this).closest('tr');
                    const option = $(this).find('option:selected');
                    if (option.val()) {
                        const tax = parseFloat(option.data('tax')) || 0;
                        $row.find('.tax-rate').data('value', tax).val(formatPercent(tax));
                    }
                });
                
                // Restore original tax values for services
                $('.service-select').each(function() {
                    const $row = $(this).closest('tr');
                    const option = $(this).find('option:selected');
                    if (option.val()) {
                        const tax = parseFloat(option.data('tax')) || 0;
                        $row.find('.tax-rate').data('value', tax).val(formatPercent(tax));
                    }
                });
            }
            
            // Recalculate all rows
            $('.add-tbody tr').each(function() {
                calculateRow($(this));
            });
            
            calculateSummary();
        });

        function formatCurrency(value) {
            const n = parseFloat(value);
            if (isNaN(n)) return '';
            return `$ ${n.toFixed(2)}`;
        }

        function formatPercent(value) {
            const n = parseFloat(value);
            if (isNaN(n)) return '';
            return `${n.toFixed(2)}%`;
        }

        function unformat(value) {
            const n = parseFloat(String(value).replace(/[^0-9.-]/g, ''));
            return isNaN(n) ? 0 : n;
        }

        // Form validation
        $('#form').on('submit', function(e) {
            let isValid = true;
            $('.error-text').text('');

            if (!$('#client_id').val()) {
                $('#clientname_error').text('Client is required.');
                isValid = false;
            }
            if (!$('#quotation_date').val()) {
                $('#quotation_date_error').text('Quotation Date is required.');
                isValid = false;
            }

            if (!$('input[name="expiry_date"]').val()) {
                $('#expiry_date_error').text('Expire Date is required.');
                isValid = false;
            }

            if (!$('.add-tbody tr').length) {
                $('#product_error').text('Please add at least one product or service');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                $('html, body').animate({ scrollTop: $('.error-text:visible').first().offset().top - 100 }, 500);
            }
        });

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

        /* =========================
           Format behaviors for currency/percent inputs
        ========================== */
        function attachCurrencyBehavior(selector, onChangeCallback) {
            $(document).on('focus', selector, function(){
                const raw = $(this).data('value');
                $(this).val(raw !== undefined ? raw : unformat($(this).val()));
            });
            $(document).on('blur', selector, function(){
                const num = unformat($(this).val());
                $(this).data('value', num).val(formatCurrency(num));
                if (onChangeCallback) onChangeCallback($(this));
            });
            $(document).on('input', selector, function(){
                if (onChangeCallback) onChangeCallback($(this));
            });
        }

        function attachPercentBehavior(selector, onChangeCallback) {
            $(document).on('focus', selector, function(){
                const raw = $(this).data('value');
                $(this).val(raw !== undefined ? raw : unformat($(this).val()));
            });
            $(document).on('blur', selector, function(){
                const num = unformat($(this).val());
                $(this).data('value', num).val(formatPercent(num));
                if (onChangeCallback) onChangeCallback($(this));
            });
            $(document).on('input', selector, function(){
                if (onChangeCallback) onChangeCallback($(this));
            });
        }

        // Apply to line-item fields
        attachCurrencyBehavior('.selling-price', function($el){
            calculateRow($el.closest('tr'));
        });
        attachPercentBehavior('.tax-rate', function($el){
            calculateRow($el.closest('tr'));
        });

        // Apply to shipping charge
        attachCurrencyBehavior('#shipping-charge', function(){
            calculateSummary();
        });

        // Initialize shipping field formatting on load
        (function initShipping(){
            const $ship = $('#shipping-charge');
            if ($ship.length) {
                const initVal = unformat($ship.val());
                $ship.data('value', initVal);
                // Only show $ if input type allows text
                if ($ship.attr('type') !== 'number') {
                    $ship.val(formatCurrency(initVal));
                } else {
                    $ship.val(initVal.toFixed(2)); // keep numeric visible
                }
            }
        })();

        /* =========================
           Item events
        ========================== */
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
                $row.find('.tax-id').val(taxId);
                $row.find('.tax-name').val(taxName);
                
                // Check if we're in Non-GST mode
                const isNonGST = $('input[name="gst_type"]:checked').val() === 'non_gst';
                const effectiveTax = isNonGST ? 0 : tax;
                
                $row.find('.selling-price').data('value', price).val(formatCurrency(price));
                $row.find('.tax-rate').data('value', effectiveTax).val(formatPercent(effectiveTax));

                calculateRow($row);
            } else {
                resetRow($row);
            }

            updateProductDropdowns();
        });

        // FIXED: Handle service selection from dropdown - DON'T auto-fill service name
        $(document).on('change', '.service-select', function() {
            const $row = $(this).closest('tr');
            const option = $(this).find('option:selected');

            if (option.val()) {
                const price = parseFloat(option.data('price')) || 0;
                const hsnCode = option.data('hsn') || '';
                const tax = parseFloat(option.data('tax')) || 0;
                const taxId = option.data('tax-id') || '';
                const taxName = option.data('tax-name') || '';

                // REMOVED: Auto-fill the service name input
                // $row.find('.service-name-input').val(option.text());
                
                $row.find('.hsn-code').val(hsnCode);
                $row.find('.tax-id').val(taxId);
                $row.find('.tax-name').val(taxName);
                
                // Check if we're in Non-GST mode
                const isNonGST = $('input[name="gst_type"]:checked').val() === 'non_gst';
                const effectiveTax = isNonGST ? 0 : tax;
                
                $row.find('.selling-price').data('value', price).val(formatCurrency(price));
                $row.find('.tax-rate').data('value', effectiveTax).val(formatPercent(effectiveTax));
                
                // Set service tax select if available and not in Non-GST mode
                if (taxId && !isNonGST) {
                    $row.find('.service-tax-select').val(taxId).trigger('change');
                }

                calculateRow($row);
            }

            updateServiceDropdowns();
        });

        // FIXED: Handle service name input - DON'T clear service dropdown selection
        $(document).on('input', '.service-name-input', function() {
            const $row = $(this).closest('tr');
            // Clear HSN code when manually entering service name
            $row.find('.hsn-code').val('');
            // REMOVED: Don't clear service dropdown selection when typing manually
            // $row.find('.service-select').val('');
            
            calculateRow($row);
        });

        // Handle service price input
        $(document).on('input', '.service-price-input', function() {
            const $row = $(this).closest('tr');
            const price = unformat($(this).val());
            $row.find('.selling-price').data('value', price).val(formatCurrency(price));
            calculateRow($row);
        });

        // Handle service tax selection
        $(document).on('change', '.service-tax-select', function() {
            const $row = $(this).closest('tr');
            const selectedOption = $(this).find('option:selected');
            const taxRate = parseFloat(selectedOption.data('rate')) || 0;
            const taxId = selectedOption.val();
            const taxName = selectedOption.text().split(' (')[0]; // Get tax name without percentage

            // Check if we're in Non-GST mode
            const isNonGST = $('input[name="gst_type"]:checked').val() === 'non_gst';
            const effectiveTax = isNonGST ? 0 : taxRate;
            
            $row.find('.tax-rate').data('value', effectiveTax).val(formatPercent(effectiveTax));
            $row.find('.tax-id').val(taxId);
            $row.find('.tax-name').val(taxName);
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
            
            // For services, if quantity is empty or 0, treat as 1 for calculation
            const isService = $row.hasClass('service-row');
            if (isService && (qty === 0 || qtyInput.val() === '')) {
                qty = 1; // Use 1 for calculation but keep field empty for display
            }
            
            const price = $row.find('.selling-price').data('value') || 0;
            const taxRate = $row.find('.tax-rate').data('value') || 0;

            const lineSubtotal = qty * price;
            const lineTaxAmount = lineSubtotal * (taxRate / 100);
            const lineTotal = lineSubtotal + lineTaxAmount;

            // Update the tax display container
            const taxAmountFormatted = formatCurrency(lineTaxAmount);
            const taxRateFormatted = `${taxRate}%`;
            
            $row.find('.tax-amount-line').text(taxAmountFormatted);
            $row.find('.tax-rate-line').text(taxRateFormatted);
            
            $row.find('.amount').data('value', lineTotal).val(formatCurrency(lineTotal));
            
            calculateSummary();
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
                const p = $(this).find('.selling-price').data('value') || 0;
                const qtyInput = $(this).find('.quantity');
                let q = unformat(qtyInput.val());
                
                // For services with empty quantity, use 1 for calculation
                const isService = $(this).hasClass('service-row');
                if (isService && (q === 0 || qtyInput.val() === '')) {
                    q = 1;
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
                    const p = $(this).find('.selling-price').data('value') || 0;
                    const qtyInput = $(this).find('.quantity');
                    let q = unformat(qtyInput.val());
                    
                    const isService = $(this).hasClass('service-row');
                    if (isService && (q === 0 || qtyInput.val() === '')) {
                        q = 1;
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
            $row.find('.quantity').val(1).removeClass('service-quantity');
            $row.find('.hsn-code, .selling-price, .tax-rate, .amount, .service-name-input, .service-price-input').val('').removeData('value');
            $row.find('.tax-id').val('');
            $row.find('.tax-amount-line').text('');
            $row.find('.tax-rate-line').text('');
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
                $row.find('.selling-price').val('').removeData('value');
                $row.find('.tax-rate').val('').removeData('value');
                $row.find('.amount').val('').removeData('value');
                $row.find('.tax-id').val('');
                $row.find('.tax-name').val('');
                $row.find('.tax-amount-line').text('');
                $row.find('.tax-rate-line').text('');
            });
            
            calculateSummary();
        });

        // SINGLE "Add New" event handler - FIXED
        $('.add-invoice-data').on('click', function(e) {
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
                    <td>
                        <div class="product-fields">
                            <select class="form-select product-select" name="item_id[]">
                                <option value="">Select Product</option>
                            </select>
                            <input type="hidden" class="tax-id" name="tax_id[]">
                            <input type="hidden" class="tax-name" name="tax_name[]">
                            <!-- Hidden field to track item type -->
                            <input type="hidden" name="item_type_row[]" value="product">
                            <!-- FIX: Add empty service_name field for product rows -->
                            <input type="hidden" name="service_name[]" value="">
                        </div>
                        <div class="service-fields">
                            <select class="form-select service-select" name="item_id[]">
                                <option value="">Select Service</option>
                            </select>
                            <!-- FIX: Always include service_name input -->
                            <input type="text" class="form-control service-name-input service-custom-input" name="service_name[]" placeholder="Or enter custom service name">
                            <input type="hidden" class="tax-id" name="tax_id[]">
                            <input type="hidden" class="tax-name" name="tax_name[]">
                            <!-- Hidden field to track item type -->
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
                            <input type="text" class="form-control selling-price" name="selling_price[]" data-value="0">
                        </div>
                        <div class="service-fields">
                            <input type="text" class="form-control service-price-input" name="selling_price[]" data-value="0" placeholder="0.00">
                        </div>
                    </td>
                    <td class="tax-column">
                        <div class="product-fields">
                            <input type="text" class="form-control tax-rate" name="rate[]" data-value="0" style="display: none;">
                            <div class="tax-display-container">
                                <div class="tax-amount-line"></div>
                                <div class="tax-rate-line"></div>
                            </div>
                        </div>
                        <div class="service-fields">
                            <select class="form-select service-tax-select" name="tax_id[]">
                                ${taxOptions}
                            </select>
                            <input type="hidden" class="tax-rate" name="rate[]" data-value="${isNonGST ? '0' : '0'}">
                            <input type="hidden" class="tax-name" name="tax_name[]" value="">
                            <div class="tax-display-container mt-2">
                                <div class="tax-amount-line"></div>
                                <div class="tax-rate-line"></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control amount" name="amount[]" data-value="0" readonly>
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
                // For service, set default tax display
                $('.add-tbody tr:last .tax-amount-line').text('$ 0.00');
                $('.add-tbody tr:last .tax-rate-line').text('0%');
            }
            
            updateProductDropdowns();
            updateServiceDropdowns();
            return false;
        });

        // FIXED: Calculate all rows on page load to ensure tax amounts and totals display properly
        function initializeRowCalculations() {
            $('.add-tbody tr').each(function() {
                const $row = $(this);
                
                // Ensure data-values are set for existing rows
                const price = unformat($row.find('.selling-price').val());
                const taxRate = unformat($row.find('.tax-rate').val());
                const amount = unformat($row.find('.amount').val());
                
                $row.find('.selling-price').data('value', price);
                $row.find('.tax-rate').data('value', taxRate);
                $row.find('.amount').data('value', amount);
                
                // Recalculate the row to update tax display
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
                $('.service-tax-select').val('');
                
                // Update tax display containers
                $('.tax-amount-line').text('$ 0.00');
                $('.tax-rate-line').text('0%');
                
                // Recalculate all rows with zero tax
                $('.add-tbody tr').each(function() {
                    const $row = $(this);
                    const qtyInput = $row.find('.quantity');
                    let qty = unformat(qtyInput.val());
                    
                    // For services, if quantity is empty or 0, treat as 1 for calculation
                    const isService = $row.hasClass('service-row');
                    if (isService && (qty === 0 || qtyInput.val() === '')) {
                        qty = 1;
                    }
                    
                    const price = $row.find('.selling-price').data('value') || 0;
                    const lineSubtotal = qty * price;
                    const lineTotal = lineSubtotal; // No tax in Non-GST mode
                    
                    $row.find('.amount').data('value', lineTotal).val(formatCurrency(lineTotal));
                });
                
                calculateSummary();
            } else {
                // For GST mode, ensure calculations run on page load
                initializeRowCalculations();
            }
        }, 100);

        // Initial pass - FIXED: Run calculations on page load
        setTimeout(function() {
            initializeRowCalculations();
        }, 500);

        updateProductDropdowns();
        updateServiceDropdowns();
        
        console.log('Initialization complete - ONE handler attached');
    });
    </script>
</body>
</html>