<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

// Radio precheck
$is_product = ($row['item_type'] == 1) ? 'checked' : '';
$is_service = ($row['item_type'] == 0) ? 'checked' : '';
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
    </style>
</head>

<body>
    <div class="main-wrapper">
        <?php include 'layouts/menu.php'; ?>

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-md-12 mx-auto">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6>Edit Quotations</h6>
                                <a href="javascript:void(0);" class="btn btn-outline-white d-inline-flex align-items-center"><i class="isax isax-eye me-1"></i>Preview</a>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form action="process/action_edit_quotation.php" method="POST" enctype="multipart/form-data" id="form">
                                    <input type="hidden" name="id" value="<?= $quotation_id ?>">   
                                    <div class="border-bottom mb-3 pb-1">
                                        <div class="row gx-3">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                  <label class="form-label">Client Name <span class="text-danger">*</span></label>
                                                  <select class="form-select select2" name="client_id" id="client_id" >
                                                      <option value="">Select Client</option>
                                                    <?php while ($client = mysqli_fetch_assoc($clients)) {
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
                                                    <?php while ($user = mysqli_fetch_assoc($users)) {
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
                                                    <?php while ($project = mysqli_fetch_assoc($projects)) {
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
                                                <table class="table mb-0 add-table">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>Product/Service</th>
                                                            <th>Quantity</th>
                                                            <th>Unit</th>
                                                            <th>Selling Price</th>
                                                            <th>Tax</th>
                                                            <th>Amount</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                              <tbody class="add-tbody" id="product">
                                                            <span class="text-danger error-text" id="product_error"></span>

                                                            <?php
                                                            $quotation_id = $_GET['id'];
                                                            $item_query = "SELECT 
                                                                            qi.*, 
                                                                            p.name AS product_name, 
                                                                            u.name AS unit_name,
                                                                            t.rate AS tax_rate,
                                                                            t.name AS tax_name,
                                                                            t.id AS tax_id,
                                                                            p.selling_price,
                                                                            p.unit_id
                                                                        FROM quotation_item qi
                                                                        LEFT JOIN product p ON qi.product_id = p.id
                                                                        LEFT JOIN units u ON p.unit_id = u.id
                                                                        LEFT JOIN tax t ON p.tax_id = t.id
                                                                        WHERE qi.quotation_id = $quotation_id AND qi.is_deleted = 0";

                                                            $item_result = mysqli_query($conn, $item_query);
                                                            while ($item = mysqli_fetch_assoc($item_result)) {
                                                                $qty = (float)($item['quantity'] ?? 0);
                                                                $price = (float)($item['selling_price'] ?? 0);
                                                                $taxRate = (float)($item['tax_rate'] ?? 0);
                                                                $taxName = $item['tax_name'] ?? '';
                                                                $productName = $item['product_name'] ?? '';
                                                                $unitName = $item['unit_name'] ?? '';
                                                                $unitId = $item['unit_id'] ?? '';
                                                                $taxId = $item['tax_id'] ?? '';

                                                                $lineSubtotal = $qty * $price;
                                                                $lineTax = $lineSubtotal * $taxRate / 100;
                                                                $amount = $lineSubtotal + $lineTax;
                                                                
                                                                // Determine row class based on item type
                                                                $rowClass = $row['item_type'] == 1 ? 'product-row' : 'service-row';
                                                            ?>
                                                                <tr class="<?= $rowClass ?>">
                                                                    <td>
                                                                        <div class="product-fields">
                                                                            <select class="form-select item-select" name="item_id[]">
                                                                                <option value="">Select Product</option>
                                                                                <?php
                                                                                $product_query = "SELECT 
                                                                                                    p.id, p.name, p.selling_price, p.unit_id, 
                                                                                                    u.name AS unit_name, 
                                                                                                    t.id AS tax_id, t.rate AS tax_rate, t.name AS tax_name
                                                                                                FROM product p
                                                                                                LEFT JOIN units u ON p.unit_id = u.id
                                                                                                LEFT JOIN tax t ON p.tax_id = t.id
                                                                                                WHERE p.is_deleted = 0";
                                                                                $product_result = mysqli_query($conn, $product_query);
                                                                                while ($product = mysqli_fetch_assoc($product_result)) {
                                                                                    $selected = ($product['id'] == $item['product_id']) ? 'selected' : '';
                                                                                    echo '<option value="' . $product['id'] . '" 
                                                                                        data-unit="' . htmlspecialchars($product['unit_name']) . '"
                                                                                        data-unit-id="' . $product['unit_id'] . '"
                                                                                        data-price="' . $product['selling_price'] . '"
                                                                                        data-tax="' . $product['tax_rate'] . '"
                                                                                        data-tax-id="' . $product['tax_id'] . '"
                                                                                        data-tax-name="' . htmlspecialchars($product['tax_name']) . '" ' . $selected . '>' . 
                                                                                        htmlspecialchars($product['name']) . '</option>';
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                            <input type="hidden" class="unit-id" name="unit_id[]" value="<?= $unitId ?>">
                                                                            <input type="hidden" class="tax-id" name="tax_id[]" value="<?= $taxId ?>">
                                                                            <input type="hidden" class="tax-name" name="name[]" value="<?= htmlspecialchars($taxName) ?>">
                                                                        </div>
                                                                        <div class="service-fields">
                                                                            <input type="text" class="form-control service-name-input" name="service_name[]" placeholder="Enter service name" value="<?= htmlspecialchars($productName) ?>">
                                                                            <input type="hidden" class="unit-id" name="unit_id[]" value="0">
                                                                            <input type="hidden" name="item_id[]" value="0">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" class="form-control quantity" name="quantity[]" value="<?= $qty ?>" min="1">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control unit-name" value="<?= htmlspecialchars($unitName) ?>" readonly>
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
                                                                    <td>
                                                                        <div class="product-fields">
                                                                            <input type="text" class="form-control tax-rate" name="rate[]"
                                                                                value="<?= number_format($taxRate, 2) . '%' ?>" 
                                                                                data-value="<?= $taxRate ?>" style="display: none;">
                                                                            <div class="tax-display-container">
                                                                                <div class="tax-amount-line"><?= '$ ' . number_format($lineTax, 2) ?></div>
                                                                                <div class="tax-rate-line"><?= number_format($taxRate, 2) . '%' ?></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="service-fields">
                                                                            <select class="form-select service-tax-select" name="tax_id[]">
                                                                                <option value="">Select Tax</option>
                                                                                <?php foreach ($taxRates as $tax): ?>
                                                                                <option value="<?= $tax['id'] ?>" 
                                                                                    data-rate="<?= $tax['rate'] ?>"
                                                                                    <?= ($tax['id'] == $taxId) ? 'selected' : '' ?>>
                                                                                    <?= $tax['name'] ?> (<?= $tax['rate'] ?>%)
                                                                                </option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                            <input type="hidden" class="tax-rate" name="rate[]" data-value="<?= $taxRate ?>">
                                                                            <input type="hidden" class="tax-name" name="name[]" value="<?= htmlspecialchars($taxName) ?>">
                                                                            <div class="tax-display-container mt-2">
                                                                                <div class="tax-amount-line"><?= '$ ' . number_format($lineTax, 2) ?></div>
                                                                                <div class="tax-rate-line"><?= number_format($taxRate, 2) . '%' ?></div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control amount" name="amount[]" 
                                                                            value="<?= '$ ' . number_format($amount, 2) ?>" 
                                                                            data-value="<?= $amount ?>" readonly>
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
                                                <input type="hidden" name="sub_amount" id="subtotal-amount-field" value="<?= $row['amount'] ?>">
                                                <input type="hidden" name="tax_amount" id="tax-amount-field" value="<?= $row['tax_amount'] ?>">
                                                <input type="hidden" name="total_amount" id="total-amount-field" value="<?= $row['total_amount'] ?>">

                                                <div class="mb-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <h6 class="fs-14 fw-semibold">Amount</h6>
                                                        <h6 class="fs-14 fw-semibold" id="subtotal-amount"><?= '$ ' . number_format($row['amount'], 2) ?></h6>
                                                    </div>
                                                     <div class="tax-details">
                                                            <!-- JS will populate tax per rate here -->
                                                        </div>
                                                    <div id="shipping-charge-group" class="d-flex align-items-center justify-content-between mb-3">
                                                        <h6 class="fs-14 fw-semibold mb-0">Shipping Charge</h6>
                                                        <input type="text" class="form-control" id="shipping-charge" name="shipping_charge" value="<?= '$ ' . number_format($row['shipping_charge'], 2) ?>">
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                                                        <h6>Total</h6>
                                                        <h6 id="total-amount"><?= '$ ' . number_format($row['total_amount'], 2) ?></h6>
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

        // Fetch client billing & shipping info
        $('#client_id').on('change', function() {
            const clientId = $(this).val();
            if (clientId) {
                $.ajax({
                    url: 'process/fetch_client_full_info.php',
                    type: 'POST',
                    data: { client_id: clientId },
                    dataType: 'json',
                    success: response => {
                        $('#client_info_block').html(response.billing_html);
                        $('#shipping_info_block').html(response.shipping_html);
                    }
                });
            } else {
                $('#client_info_block, #shipping_info_block').empty();
            }
        });

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

        function loadItems(type, target) {
            $.post('process/get_productcategories_by_type.php', { item_type: type }, data => {
                if (target) target.html(data);
                updateItemDropdowns();
            });
        }

        function updateItemDropdowns() {
            let selectedItems = [];
            $('.item-select').each(function() {
                let val = $(this).val();
                if (val) selectedItems.push(val);
            });

            $('.item-select').each(function() {
                let currentVal = $(this).val();
                $(this).find('option').each(function() {
                    if ($(this).val() && selectedItems.includes($(this).val()) && $(this).val() !== currentVal) {
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

        /* =========================
           Item events
        ========================== */
        $(document).on('change', '.item-select', function() {
            const $row = $(this).closest('tr');
            const option = $(this).find('option:selected');

            if (option.val()) {
                const price = parseFloat(option.data('price')) || 0;
                const unit = option.data('unit') || '';
                const unitId = option.data('unit-id') || '';
                const taxRate = parseFloat(option.data('tax')) || 0;
                const taxId = option.data('tax-id') || '';
                const taxName = option.data('tax-name') || '';

                $row.find('.unit-name').val(unit);
                $row.find('.unit-id').val(unitId);
                $row.find('.tax-id').val(taxId);
                $row.find('.tax-name').val(taxName);
                $row.find('.selling-price').data('value', price).val(formatCurrency(price));
                $row.find('.tax-rate').data('value', taxRate).val(formatPercent(taxRate));

                calculateRow($row);
            } else {
                resetRow($row);
            }

            updateItemDropdowns();
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

            $row.find('.tax-rate').data('value', taxRate).val(formatPercent(taxRate));
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
            updateItemDropdowns();
        });

        /* =========================
           Calculations
        ========================== */
        function calculateRow($row) {
            const qty  = unformat($row.find('.quantity').val());
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
            const stored = $ship.data('value');
            if (stored !== undefined) return parseFloat(stored) || 0;
            return unformat($ship.val());
        }

        function calculateSummary() {
            let sub = 0, taxGroups = {}, grandTotal = 0;

            $('.add-tbody tr').each(function() {
                const p = $(this).find('.selling-price').data('value') || 0;
                const q = unformat($(this).find('.quantity').val());
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

            $('.add-tbody tr').each(function(index) {
                const p = $(this).find('.selling-price').data('value') || 0;
                const q = unformat($(this).find('.quantity').val());
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

            $('.tax-details').html(taxHtml);

            const totalAll = grandTotal + shippingCharge;

            $('#subtotal-amount').text(formatCurrency(sub));
            $('#total-amount').text(formatCurrency(totalAll));

            $('#subtotal-amount-field').val(sub.toFixed(2));
            $('#tax-amount-field').val(Object.values(taxGroups).reduce((a,b)=>a+b,0).toFixed(2));
            $('#total-amount-field').val(totalAll.toFixed(2));
        }

        function resetRow($row) {
            $row.find('.quantity').val(1);
            $row.find('.unit-name, .selling-price, .tax-rate, .amount, .service-name-input, .service-price-input').val('').removeData('value');
            $row.find('.unit-id, .tax-id').val('');
            $row.find('.tax-amount-line').text('');
            $row.find('.tax-rate-line').text('');
            calculateSummary();
        }

        // Initialize shipping field formatting on load
        (function initShipping(){
            const $ship = $('#shipping-charge');
            if ($ship.length) {
                const initVal = unformat($ship.val());
                $ship.data('value', initVal);
                if ($ship.attr('type') !== 'number') {
                    $ship.val(formatCurrency(initVal));
                } else {
                    $ship.val(initVal.toFixed(2));
                }
            }
        })();

        // Item type change handler - CONVERTS EXISTING ROWS
        $('input[name="item_type"]').on('change', function() {
            const itemType = $(this).val();
            console.log('Item type changed to:', itemType);
            
            // Convert all existing rows to the new type
            convertExistingRows(itemType);
        });

        function convertExistingRows(itemType) {
            $('.add-tbody tr').each(function() {
                const $row = $(this);
                const rowClass = itemType == 1 ? 'product-row' : 'service-row';
                
                // Remove existing classes
                $row.removeClass('product-row service-row').addClass(rowClass);
                
                // Generate tax options HTML
                let taxOptions = '<option value="">Select Tax</option>';
                <?php foreach ($taxRates as $tax): ?>
                taxOptions += `<option value="<?= $tax['id'] ?>" data-rate="<?= $tax['rate'] ?>"><?= $tax['name'] ?> (<?= $tax['rate'] ?>%)</option>`;
                <?php endforeach; ?>

                if (itemType == 1) {
                    // Convert to PRODUCT
                    const currentServiceName = $row.find('.service-name-input').val() || '';
                    
                    $row.find('td:eq(0)').html(`
                        <div class="product-fields">
                            <select class="form-select item-select" name="item_id[]">
                                <option value="">Select Product</option>
                            </select>
                            <input type="hidden" class="unit-id" name="unit_id[]">
                            <input type="hidden" class="tax-id" name="tax_id[]">
                            <input type="hidden" class="tax-name" name="name[]">
                        </div>
                        <div class="service-fields" style="display: none;">
                            <input type="text" class="form-control service-name-input" name="service_name[]" placeholder="Enter service name" value="${currentServiceName}">
                            <input type="hidden" class="unit-id" name="unit_id[]" value="0">
                            <input type="hidden" name="item_id[]" value="0">
                        </div>
                    `);
                    
                    $row.find('td:eq(3)').html(`
                        <div class="product-fields">
                            <input type="text" class="form-control selling-price" name="selling_price[]" data-value="0">
                        </div>
                        <div class="service-fields" style="display: none;">
                            <input type="text" class="form-control service-price-input" name="selling_price[]" data-value="0" placeholder="0.00">
                        </div>
                    `);
                    
                    $row.find('td:eq(4)').html(`
                        <div class="product-fields">
                            <input type="text" class="form-control tax-rate" name="rate[]" data-value="0" style="display: none;">
                            <div class="tax-display-container">
                                <div class="tax-amount-line"></div>
                                <div class="tax-rate-line"></div>
                            </div>
                        </div>
                        <div class="service-fields" style="display: none;">
                            <select class="form-select service-tax-select" name="tax_id[]">
                                ${taxOptions}
                            </select>
                            <input type="hidden" class="tax-rate" name="rate[]" data-value="0">
                            <input type="hidden" class="tax-name" name="name[]" value="">
                            <div class="tax-display-container mt-2">
                                <div class="tax-amount-line"></div>
                                <div class="tax-rate-line"></div>
                            </div>
                        </div>
                    `);
                    
                    // Load product dropdown for this row
                    const $newSelect = $row.find('.item-select');
                    loadItems(itemType, $newSelect);
                    
                } else {
                    // Convert to SERVICE
                    const currentItemId = $row.find('.item-select').val() || '';
                    const currentPrice = $row.find('.selling-price').data('value') || 0;
                    const currentTaxRate = $row.find('.tax-rate').data('value') || 0;
                    const currentTaxId = $row.find('.tax-id').val() || '';
                    const currentTaxName = $row.find('.tax-name').val() || '';
                    
                    $row.find('td:eq(0)').html(`
                        <div class="product-fields" style="display: none;">
                            <select class="form-select item-select" name="item_id[]">
                                <option value="">Select Product</option>
                            </select>
                            <input type="hidden" class="unit-id" name="unit_id[]">
                            <input type="hidden" class="tax-id" name="tax_id[]">
                            <input type="hidden" class="tax-name" name="name[]">
                        </div>
                        <div class="service-fields">
                            <input type="text" class="form-control service-name-input" name="service_name[]" placeholder="Enter service name">
                            <input type="hidden" class="unit-id" name="unit_id[]" value="0">
                            <input type="hidden" name="item_id[]" value="0">
                        </div>
                    `);
                    
                    $row.find('td:eq(3)').html(`
                        <div class="product-fields" style="display: none;">
                            <input type="text" class="form-control selling-price" name="selling_price[]" data-value="0">
                        </div>
                        <div class="service-fields">
                            <input type="text" class="form-control service-price-input" name="selling_price[]" data-value="${currentPrice}" value="${formatCurrency(currentPrice)}" placeholder="0.00">
                        </div>
                    `);
                    
                    $row.find('td:eq(4)').html(`
                        <div class="product-fields" style="display: none;">
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
                            <input type="hidden" class="tax-rate" name="rate[]" data-value="${currentTaxRate}">
                            <input type="hidden" class="tax-name" name="name[]" value="${currentTaxName}">
                            <div class="tax-display-container mt-2">
                                <div class="tax-amount-line"></div>
                                <div class="tax-rate-line"></div>
                            </div>
                        </div>
                    `);
                    
                    // Set default unit as "Service"
                    $row.find('.unit-name').val('Service');
                    
                    // Preserve selected tax if any
                    if (currentTaxId) {
                        $row.find('.service-tax-select').val(currentTaxId);
                    }
                    
                    // Attach currency behavior to service price input
                    const $servicePrice = $row.find('.service-price-input');
                    $servicePrice.on('focus', function(){
                        const raw = $(this).data('value');
                        $(this).val(raw !== undefined ? raw : unformat($(this).val()));
                    });
                    $servicePrice.on('blur', function(){
                        const num = unformat($(this).val());
                        $(this).data('value', num).val(formatCurrency(num));
                        calculateRow($(this).closest('tr'));
                    });
                    $servicePrice.on('input', function(){
                        calculateRow($(this).closest('tr'));
                    });
                }
                
                // Recalculate the row
                calculateRow($row);
            });
            
            updateItemDropdowns();
            calculateSummary();
        }

        // "Add New" event handler with dynamic item type
        $('.add-invoice-data').on('click', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            
            console.log('Add New clicked - adding ONE row only');
            
            const itemType = $('input[name="item_type"]:checked').val();
            const rowClass = itemType == 1 ? 'product-row' : 'service-row';
            
            // Generate tax options HTML from PHP data
            let taxOptions = '<option value="">Select Tax</option>';
            <?php foreach ($taxRates as $tax): ?>
            taxOptions += `<option value="<?= $tax['id'] ?>" data-rate="<?= $tax['rate'] ?>"><?= $tax['name'] ?> (<?= $tax['rate'] ?>%)</option>`;
            <?php endforeach; ?>

            const newRow = `
                <tr class="${rowClass}">
                    <td>
                        <div class="product-fields">
                            <select class="form-select item-select" name="item_id[]">
                                <option value="">Select Product</option>
                            </select>
                            <input type="hidden" class="unit-id" name="unit_id[]">
                            <input type="hidden" class="tax-id" name="tax_id[]">
                            <input type="hidden" class="tax-name" name="name[]">
                        </div>
                        <div class="service-fields">
                            <input type="text" class="form-control service-name-input" name="service_name[]" placeholder="Enter service name">
                            <input type="hidden" class="unit-id" name="unit_id[]" value="0">
                            <input type="hidden" name="item_id[]" value="0">
                        </div>
                    </td>
                    <td>
                        <input type="number" class="form-control quantity" name="quantity[]" value="1" min="1">
                    </td>
                    <td>
                        <input type="text" class="form-control unit-name" name="unit_name[]" readonly>
                    </td>
                    <td>
                        <div class="product-fields">
                            <input type="text" class="form-control selling-price" name="selling_price[]" data-value="0">
                        </div>
                        <div class="service-fields">
                            <input type="text" class="form-control service-price-input" name="selling_price[]" data-value="0" placeholder="0.00">
                        </div>
                    </td>
                    <td>
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
                            <input type="hidden" class="tax-rate" name="rate[]" data-value="0">
                            <input type="hidden" class="tax-name" name="name[]" value="">
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
            
            // If it's a product, load the dropdown items
            if (itemType == 1) {
                const $newSelect = $('.add-tbody tr:last .item-select');
                loadItems(itemType, $newSelect);
            } else {
                // For service, set default unit as "Service"
                $('.add-tbody tr:last .unit-name').val('Service');
                
                // Attach currency behavior to service price input
                const $servicePrice = $('.add-tbody tr:last .service-price-input');
                $servicePrice.on('focus', function(){
                    const raw = $(this).data('value');
                    $(this).val(raw !== undefined ? raw : unformat($(this).val()));
                });
                $servicePrice.on('blur', function(){
                    const num = unformat($(this).val());
                    $(this).data('value', num).val(formatCurrency(num));
                    calculateRow($(this).closest('tr'));
                });
                $servicePrice.on('input', function(){
                    calculateRow($(this).closest('tr'));
                });
            }
            
            updateItemDropdowns();
            return false;
        });

        // Initial pass
        updateItemDropdowns();
        calculateSummary();
        
        console.log('Initialization complete - ONE handler attached');
    });
    </script>
</body>
</html>