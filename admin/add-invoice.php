<?php include 'layouts/session.php'; ?>
<?php
include '../config/config.php';

// Get next AUTO_INCREMENT value
$query = "SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES 
          WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'invoice'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row && isset($row['AUTO_INCREMENT'])) {
    $nextId = $row['AUTO_INCREMENT'];
    $newinvoiceID = 'INV-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
} else {
    // Fallback in case of error
    $newinvoiceID = 'INV-0001';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include 'layouts/title-meta.php'; ?> 

	<?php include 'layouts/head-css.php'; ?>
   <!-- Additional CSS for datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>

    <!-- Start Main Wrapper -->
    <div class="main-wrapper">

		<?php include 'layouts/menu.php'; ?>

        <!-- ========================
			Start Page Content
		========================= -->

        <div class="page-wrapper">

            <!-- Start Content -->
            <div class="content">

                <!-- Start row  -->
                <div class="row">
                    <div class="col-md-11 mx-auto">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6><a href="invoices.php"><i class="isax isax-arrow-left me-2"></i>Invoice</a></h6>
                                <a href="invoice-details.php" class="btn btn-outline-white d-inline-flex align-items-center"><i class="isax isax-eye me-1"></i>Preview</a>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="mb-3">Invoice Details</h6>
                                    <form action="process/action_add_invoice.php" method="POST" enctype="multipart/form-data" id="form">
                                        <div class="border-bottom mb-3 pb-1">
                                            <!-- start row -->
                                            <div class="row justify-content-between">
                                                <div class="col-xl-5 col-lg-7">
                                                    <div class="row gx-3">
                                                        <div class="col-md-6">
               <div class="mb-3">
        <label class="form-label">Client Name<span class="text-danger">*</span></label>
        <select class="form-select select2" name="client_id" id="client_id">
            <option value="">Select Client</option>
            <?php
            // Get selected client ID from URL
            $selectedClient = isset($_GET['client_id']) ? intval($_GET['client_id']) : 0;

            $result = mysqli_query($conn, "SELECT * FROM client");
            while ($row = mysqli_fetch_assoc($result)) {
                $isSelected = ($row['id'] == $selectedClient) ? 'selected' : '';
                echo '<option value="' . $row['id'] . '" ' . $isSelected . '>' . htmlspecialchars($row['first_name']) . '</option>';
            }
            ?>
        </select>
        <span class="text-danger error-text" id="clientname_error"></span>
    </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Reference Name</label>
                                                                <input type="text" class="form-control" name="reference_name" id="reference_name">
                                                            </div>
                                                              <div class="mb-3">
                                                                <label class="form-label">Order Number</label>
                                                                <input type="number" class="form-control" name="order_number">
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Invoice Number</label>
                                                                <input type="text" class="form-control" name="invoice_id" value="<?= $newinvoiceID ?>" readonly >
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Salesperson<span class="text-danger">*</span></label>
                                                                 <select class="form-select select2" name="user_id" id="user_id">
                                                                <option value="">Select Salesperson</option>
                                                                <?php
                                                                $query = "SELECT login.id, login.name FROM login
                                                                          JOIN user_role ON login.role_id = user_role.id
                                                                          WHERE  login.is_deleted = 0
                                                                          ORDER BY login.name ASC";
                                                                $result = mysqli_query($conn, $query);
                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                                <span class="text-danger error-text" id="username_error"></span>

                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <label class="form-label">Invoice Date<span class="text-danger">*</span></label>
                                                            <div class="input-group position-relative mb-3">
                                                                <input type="text" class="form-control datepicker"id="invoice_date" name="invoice_date">
                                                                <span class="input-icon-addon fs-16 text-gray-9">
																	<i class="isax isax-calendar-2"></i>
																</span>
                                                            </div>
                                                            <span class="text-danger error-text" id="invoice_date_error"></span>
                                                        </div>
                                                     <div class="col-lg-12">
                                                            <label class="form-label">Invoice Due Date<span class="text-danger">*</span></label>
                                                            <div class="input-group position-relative mb-3">
                                                                <input type="text" class="form-control datepicker" id="due_date" name="due_date">
                                                                <span class="input-icon-addon fs-16 text-gray-9">
																	<i class="isax isax-calendar-2"></i>
																</span>
                                                            </div>
                                                           <span class="text-danger error-text" id="invoice_due_error"></span> 
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                           
                                            </div>
                                            <!-- end row -->

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
                                                                <input class="form-check-input" type="radio" name="item_type" id="Radio-product" value="1" checked>
                                                                <label class="form-check-label" for="Radio-product">Product</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="item_type" id="Radio-service" value="0">
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
                                                            <th>Tax (%)</th>
                                                            <th>Amount</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="add-tbody" id="product">
                                                        <span class="text-danger error-text" id="product_error"></span>
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
                                                                    <a class="nav-link d-inline-flex align-items-center border fs-12 fw-semibold rounded-2" data-bs-toggle="tab" data-bs-target="#documents" href="javascript:void(0);"><i class="isax isax-bank me-1"></i>Upload Documnets</a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content">
                                                                <div class="tab-pane active show" id="notes" role="tabpanel">
                                                                    <label class="form-label">Additional Notes</label>
                                                                    <textarea class="form-control" name="invoice_note"></textarea>
                                                                </div>
                                                                <div class="tab-pane fade" id="terms" role="tabpanel">
                                                                    <label class="form-label">Terms & Conditions</label>
                                                                    <textarea class="form-control" name="description"></textarea>
                                                                </div>
                                                                <div class="tab-pane fade" id="bank" role="tabpanel">
                                                                    <label class="form-label">Account<span class="text-danger">*</span></label>
                                                                    <select class="select2" name="bank_id" id="bank_id">
                                                                         <option value="">Select Account</option>
                                                                        <?php                                                         
                                                                        $result = mysqli_query($conn, "SELECT * FROM bank where status=1");
                                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                                        echo '<option value="' . $row['id'] . '">' . $row['account_holder'] . ' - ' . $row['account_number'] . ' (' . $row['bank_name'] . ')</option>';
                                                                        }
                                                                        ?>  
                                                                    </select>
                                                                    <span class="text-danger error-text" id="invoice_account_error"></span> 
                                                                </div>
                                                                <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                                                    <div class="file-upload drag-file w-100 h-auto py-3 d-flex align-items-center justify-content-center flex-column">
                                                                        <span class="upload-img d-block"><i class="isax isax-image text-primary me-1"></i>Upload Documents</span>
                                                                        <input type="file" class="form-control" name="document[]" id="document-upload" multiple>
                                                                        <span id="file-count-label" class="mt-2 text-muted"></span>
                                                                    </div>
                                                                    <span id="document_error" class="text-danger error-text"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-lg-5">
                                                    <input type="hidden" name="sub_amount" id="subtotal-amount-field" value="0">
                                                    <input type="hidden" name="tax_amount" id="tax-amount-field" value="0">
                                                    <input type="hidden" name="total_amount" id="total-amount-field" value="0">

                                                    <div class="mb-3">
                                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                                            <h6 class="fs-14 fw-semibold">Amount</h6>
                                                            <h6 class="fs-14 fw-semibold" id="subtotal-amount"></h6>
                                                        </div>
                                                        <div class="tax-details">
                                                           
                                                        </div>
                                                        <div id="shipping-charge-group" class="d-flex align-items-center justify-content-between mb-3">
                                                            <h6 class="fs-14 fw-semibold mb-0">Shipping Charge</h6>
                                                            <input type="text" class="form-control" id="shipping-charge" name="shipping_charge" value="0.00">
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                                                            <h6>Total</h6>
                                                            <h6 id="total-amount"></h6>
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                            </div>
											<!-- end row -->

                                        </div>

                                        <div class="d-flex align-items-center justify-content-between">
                                        <button type="button" class="btn btn-outline-white" onclick="window.location.href='invoices.php'">Cancel</button>
                                            <button type="submit" name="submit" class="btn btn-primary">Save</button>
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
      <!-- <script>
        // Initialize datepicker
        $(document).ready(function() {
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
          });
            </script>


<script>
    $(document).ready(function () {
   

    // === Allow only text (no digits) ===
    $('#reference_name').on('input', function () {
        this.value = this.value.replace(/[0-9]/g, '');
    });

  
});

</script>

<script>
$(document).ready(function() {

  /* =========================
     Helpers: format / unformat
  ========================== */
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

  /* =========================
     Allow only text (no digits)
  ========================== */
  $('#reference_name').on('input', function () {
    this.value = this.value.replace(/[0-9]/g, '');
  });

 $('#shipping-charge').on('input', function () {
    let val = this.value.replace(/[^0-9.]/g, ''); 
    let parts = val.split('.');
    if (parts.length > 2) {
        val = parts[0] + '.' + parts[1]; // keep only first decimal point
    }
    this.value = val;
});
  /* =========================
     Fetch client billing & shipping info
  ========================== */
function fetchClientInfo(clientId) {
    if (clientId) {
        $.ajax({
            url: 'process/fetch_client_full_info.php',
            type: 'POST',
            data: { client_id: clientId },
            dataType: 'json',
            success: function(response) {
                $('#client_info_block').html(response.billing_html);
                $('#shipping_info_block').html(response.shipping_html);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    } else {
        $('#client_info_block, #shipping_info_block').empty();
    }
}

// Trigger on change
$('#client_id').on('select2:select', function(e) {
    fetchClientInfo($(this).val());
});

// Trigger on page load if client already selected (from URL)
const preselectedClient = $('#client_id').val();
if (preselectedClient) {
    fetchClientInfo(preselectedClient);
}


  /* =========================
     FORM VALIDATION + Clean values on submit
  ========================== */
  $('#form').on('submit', function(e) {
    let isValid = true;
    $('.error-text').text('');
    let firstErrorTab = null;

    if (!$('#client_id').val()) {
      $('#clientname_error').text('Client is required.');
      isValid = false;
    }
    if (!$('#invoice_date').val()) {
      $('#invoice_date_error').text('Invoice Date is required.');
      isValid = false;
    }
    if (!$('#user_id').val()) {
      $('#username_error').text('Salesperson is required.');
      isValid = false;
    }
    if (!$('#due_date').val()) {
      $('#invoice_due_error').text('Due Date is required.');
      isValid = false;
    }
    if (!$('#bank_id').val()) {
      $('#invoice_account_error').text('Account is required.');
      isValid = false;
      firstErrorTab = firstErrorTab || '#bank';
    }
    if (!$('.add-tbody tr').length) {
      $('#product_error').text('Please add at least one product or service');
      isValid = false;
    }

    if (!isValid) {
      e.preventDefault();
      if (firstErrorTab) {
        $('a[data-bs-toggle="tab"][data-bs-target="' + firstErrorTab + '"]').tab('show');
      }
      $('html, body').animate({ scrollTop: $('.error-text:visible').first().offset().top - 100 }, 500);
      return;
    }

    // ✅ Before submit: strip formatting so PHP gets clean numbers
    $('.selling-price').each(function(){
      const num = $(this).data('value') ?? unformat($(this).val());
      $(this).val(parseFloat(num).toFixed(2));
    });
    $('.tax-rate').each(function(){
      const num = $(this).data('value') ?? unformat($(this).val());
      $(this).val(num); // percent as plain number (no %)
    });
    $('.amount').each(function(){
      const num = $(this).data('value') ?? unformat($(this).val());
      $(this).val(parseFloat(num).toFixed(2));
    });

    const shipNum = $('#shipping-charge').data('value') ?? unformat($('#shipping-charge').val());
    $('#shipping-charge').val(parseFloat(shipNum).toFixed(2));
  });

  /* =========================
     Upload label
  ========================== */
  $('#document-upload').on('change', function() {
    const files = this.files;
    const label = files.length === 1 ? files[0].name : (files.length > 1 ? `${files.length} files selected` : '');
    $('#file-count-label').text(label);
  });

  /* =========================
     Items dropdown utilities
  ========================== */
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
    // Focus -> show raw number
    $(document).on('focus', selector, function(){
      const raw = $(this).data('value');
      $(this).val(raw !== undefined ? raw : unformat($(this).val()));
    });
    // Blur -> store & show $xx.xx
    $(document).on('blur', selector, function(){
      const num = unformat($(this).val());
      $(this).data('value', num).val(formatCurrency(num));
      if (onChangeCallback) onChangeCallback($(this));
    });
    // Input -> recalc live
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
  // ⚠️ If your input is type="number", it will still COUNT but won't show "$".
  // To display "$", make it type="text".
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
   
 $(document).on('change', '.item-select', function() {
  const $row = $(this).closest('tr');
  const option = $(this).find('option:selected');

  if (option.val()) {
    const price = parseFloat(option.data('price')) || 0;
    const unit = option.data('unit') || '';
    const unitId = option.data('unit-id') || '';
    const tax = parseFloat(option.data('tax')) || 0;
    const taxId = option.data('tax-id') || '';
    const taxName = option.data('tax-name') || ''; // Get tax name

    $row.find('.unit-name').val(unit);
    $row.find('.unit-id').val(unitId);
    $row.find('.tax-id').val(taxId);
    $row.find('.tax-name').val(taxName); // Store tax name

    $row.find('.selling-price').data('value', price).val(formatCurrency(price));
    $row.find('.tax-rate').data('value', tax).val(formatPercent(tax));

    calculateRow($row);
  } else {
    resetRow($row);
  }

  updateItemDropdowns();
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
    const tax   = $row.find('.tax-rate').data('value') || 0;

    const lineSubtotal = qty * price;
    const lineTax = lineSubtotal * (tax / 100);
    const lineTotal = lineSubtotal + lineTax;

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
    const q = unformat($(this).find('.quantity').val());
    const t = $(this).find('.tax-rate').data('value') || 0;
    const taxName = $(this).find('.tax-name').val() || ''; // Get tax name

    const lineSubtotal = p * q;
    const lineTax = (lineSubtotal * t / 100);
    const lineTotal = lineSubtotal + lineTax;

    sub += lineSubtotal;
    grandTotal += lineTotal;

    if (t > 0) {
      const taxKey = `${taxName} (${t}%)`; // Create key with name and rate
      if (!taxGroups[taxKey]) taxGroups[taxKey] = 0;
      taxGroups[taxKey] += lineTax;
    }
  });

  // Shipping
  const shippingCharge = getShippingCharge();
  // let taxHtml = "";

  // $.each(taxGroups, function(taxLabel, amount) {
  //   taxHtml += `
  //     <div class="d-flex align-items-center justify-content-between mb-2">
  //       <h6 class="fs-14 fw-semibold">${taxLabel}</h6>
  //       <h6 class="fs-14 fw-semibold">${formatCurrency(amount)}</h6>
  //     </div>`;
  // });

  // $('.tax-details').html(taxHtml);
let taxHtml = "";

$('.add-tbody tr').each(function(index) {
  const p = $(this).find('.selling-price').data('value') || 0;
  const q = unformat($(this).find('.quantity').val());
  const t = $(this).find('.tax-rate').data('value') || 0;
  const taxName = $(this).find('.tax-name').val() || '';

  const lineSubtotal = p * q;
  const lineTax = (lineSubtotal * t / 100);

  if (t > 0 && lineTax > 0) {
    const taxLabel = `${taxName} (${t}%)`;
    taxHtml += `
      <div class="d-flex align-items-center justify-content-between mb-2">
        <h6 class="fs-14 fw-semibold">${taxLabel}</h6>
        <h6 class="fs-14 fw-semibold">${formatCurrency(lineTax)}</h6>
      </div>`;
  }
});

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
    $row.find('.quantity').val(1);
    $row.find('.unit-name, .selling-price, .tax-rate, .amount').val('').removeData('value');
    $row.find('.unit-id, .tax-id').val('');
    calculateSummary();
  }

  // Initial pass
  updateItemDropdowns();
  calculateSummary();
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
    </script> -->
 <script>
  $(document).ready(function() {

  /* =========================
     Helpers: format / unformat
  ========================== */
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

  /* =========================
     Radio button change handler
  ========================== */
  $(document).on('change', 'input[name="item_type"]', function() {
    const selectedType = $(this).val(); // 1 for Product, 0 for Service
    
    // Update all existing dropdowns to match the selected type
    updateAllItemDropdowns(selectedType);
  });

  function updateAllItemDropdowns(itemType) {
    $('.item-select').each(function() {
      const currentVal = $(this).val();
      loadItems(itemType, $(this));
      
      // Try to restore the previous selection if it exists in the new list
      if (currentVal) {
        setTimeout(() => {
          if ($(this).find('option[value="' + currentVal + '"]').length > 0) {
            $(this).val(currentVal).trigger('change');
          } else {
            // If previous selection doesn't exist in new type, clear the row
            resetRow($(this).closest('tr'));
          }
        }, 100);
      }
    });
  }

  /* =========================
     Items dropdown utilities
  ========================== */
  function loadItems(type, target) {
    $.post('process/get_productcategories_by_type.php', { item_type: type }, function(data) {
      if (target) {
        target.html(data);
        updateItemDropdowns();
      }
    }).fail(function() {
      if (target) {
        target.html('<option value="">Error loading items</option>');
      }
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
     Add new row functionality
  ========================== */
  function addNewRow() {
    const itemType = $('input[name="item_type"]:checked').val();
    const rowId = 'row_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    
    const newRow = `
    <tr id="${rowId}">
        <td>
            <select class="form-select item-select" name="item_id[]" required>
                <option value="">Loading...</option>
            </select>
            <input type="hidden" class="unit-id" name="unit_id[]">
            <input type="hidden" class="tax-id" name="tax_id[]">
            <input type="hidden" class="tax-name" name="tax_name[]">
        </td>
        <td>
            <input type="number" class="form-control quantity" name="quantity[]" value="1" min="1" required>
        </td>
        <td>
            <input type="text" class="form-control unit-name" name="unit_name[]" readonly>
        </td>
        <td>
            <input type="text" class="form-control selling-price" name="selling_price[]" readonly>
        </td>
        <td>
            <input type="text" class="form-control tax-rate" name="tax_rate[]" readonly>
        </td>
        <td>
            <input type="text" class="form-control amount" name="amount[]" readonly>
        </td>
        <td>
            <a href="javascript:void(0);" class="remove-table"><i class="isax isax-trash text-danger"></i></a>
        </td>
    </tr>`;
    
    $('.add-tbody').append(newRow);
    
    // Load appropriate items for this new row
    const $select = $('#' + rowId + ' .item-select');
    loadItems(itemType, $select);
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
      if ($ship.attr('type') !== 'number') {
        $ship.val(formatCurrency(initVal));
      } else {
        $ship.val(initVal.toFixed(2));
      }
    }
  })();

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
      const tax = parseFloat(option.data('tax')) || 0;
      const taxId = option.data('tax-id') || '';
      const taxName = option.data('tax-name') || '';

      $row.find('.unit-name').val(unit);
      $row.find('.unit-id').val(unitId);
      $row.find('.tax-id').val(taxId);
      $row.find('.tax-name').val(taxName);

      $row.find('.selling-price').data('value', price).val(formatCurrency(price));
      $row.find('.tax-rate').data('value', tax).val(formatPercent(tax));

      calculateRow($row);
    } else {
      resetRow($row);
    }

    updateItemDropdowns();
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
     Add new row click handler
  ========================== */
  $(document).on('click', '.add-invoice-data', function() {
    addNewRow();
  });

  /* =========================
     Calculations
  ========================== */
  function calculateRow($row) {
    const qty  = unformat($row.find('.quantity').val());
    const price = $row.find('.selling-price').data('value') || 0;
    const tax   = $row.find('.tax-rate').data('value') || 0;

    const lineSubtotal = qty * price;
    const lineTax = lineSubtotal * (tax / 100);
    const lineTotal = lineSubtotal + lineTax;

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
      const taxName = $(this).find('.tax-name').val() || '';

      const lineSubtotal = p * q;
      const lineTax = (lineSubtotal * t / 100);
      const lineTotal = lineSubtotal + lineTax;

      sub += lineSubtotal;
      grandTotal += lineTotal;

      if (t > 0) {
        const taxKey = `${taxName} (${t}%)`;
        if (!taxGroups[taxKey]) taxGroups[taxKey] = 0;
        taxGroups[taxKey] += lineTax;
      }
    });

    const shippingCharge = getShippingCharge();
    
    let taxHtml = "";
    $.each(taxGroups, function(taxLabel, amount) {
      taxHtml += `
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h6 class="fs-14 fw-semibold">${taxLabel}</h6>
          <h6 class="fs-14 fw-semibold">${formatCurrency(amount)}</h6>
        </div>`;
    });

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
    $row.find('.quantity').val(1);
    $row.find('.unit-name, .selling-price, .tax-rate, .amount').val('').removeData('value');
    $row.find('.unit-id, .tax-id').val('');
    calculateSummary();
  }

  /* =========================
     FORM VALIDATION + Clean values on submit
  ========================== */
  $('#form').on('submit', function(e) {
    let isValid = true;
    $('.error-text').text('');
    let firstErrorTab = null;

    if (!$('#client_id').val()) {
      $('#clientname_error').text('Client is required.');
      isValid = false;
    }
    if (!$('#invoice_date').val()) {
      $('#invoice_date_error').text('Invoice Date is required.');
      isValid = false;
    }
    if (!$('#user_id').val()) {
      $('#username_error').text('Salesperson is required.');
      isValid = false;
    }
    if (!$('#due_date').val()) {
      $('#invoice_due_error').text('Due Date is required.');
      isValid = false;
    }
    if (!$('#bank_id').val()) {
      $('#invoice_account_error').text('Account is required.');
      isValid = false;
      firstErrorTab = firstErrorTab || '#bank';
    }
    if (!$('.add-tbody tr').length) {
      $('#product_error').text('Please add at least one product or service');
      isValid = false;
    }

    if (!isValid) {
      e.preventDefault();
      if (firstErrorTab) {
        $('a[data-bs-toggle="tab"][data-bs-target="' + firstErrorTab + '"]').tab('show');
      }
      $('html, body').animate({ scrollTop: $('.error-text:visible').first().offset().top - 100 }, 500);
      return;
    }

    // Clean formatting before submit
    $('.selling-price').each(function(){
      const num = $(this).data('value') ?? unformat($(this).val());
      $(this).val(parseFloat(num).toFixed(2));
    });
    $('.tax-rate').each(function(){
      const num = $(this).data('value') ?? unformat($(this).val());
      $(this).val(num);
    });
    $('.amount').each(function(){
      const num = $(this).data('value') ?? unformat($(this).val());
      $(this).val(parseFloat(num).toFixed(2));
    });

    const shipNum = $('#shipping-charge').data('value') ?? unformat($('#shipping-charge').val());
    $('#shipping-charge').val(parseFloat(shipNum).toFixed(2));
  });

  /* =========================
     Client info fetch (existing)
  ========================== */
  function fetchClientInfo(clientId) {
    if (clientId) {
      $.ajax({
        url: 'process/fetch_client_full_info.php',
        type: 'POST',
        data: { client_id: clientId },
        dataType: 'json',
        success: function(response) {
          $('#client_info_block').html(response.billing_html);
          $('#shipping_info_block').html(response.shipping_html);
        },
        error: function(xhr, status, error) {
          console.error(error);
        }
      });
    } else {
      $('#client_info_block, #shipping_info_block').empty();
    }
  }

  $('#client_id').on('select2:select', function(e) {
    fetchClientInfo($(this).val());
  });

  const preselectedClient = $('#client_id').val();
  if (preselectedClient) {
    fetchClientInfo(preselectedClient);
  }

  /* =========================
     Upload label
  ========================== */
  $('#document-upload').on('change', function() {
    const files = this.files;
    const label = files.length === 1 ? files[0].name : (files.length > 1 ? `${files.length} files selected` : '');
    $('#file-count-label').text(label);
  });

  /* =========================
     Initialize first row
  ========================== */
  // Add initial row based on default selected radio (Product)
  addNewRow();

  // Initial calculations
  calculateSummary();
});
 </script>
</body>

</html>        