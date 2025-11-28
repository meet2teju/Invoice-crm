<?php
include '../config/config.php';

$invoice_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$download = isset($_GET['download']) ? true : false;

if ($invoice_id <= 0) {
    die('Invalid Invoice ID!');
}

// Fetch invoice
$invoice_result = mysqli_query($conn, "
    SELECT i.*, l.name AS salesperson_name
    FROM invoice i
    LEFT JOIN login l ON i.user_id = l.id
    WHERE i.id = $invoice_id AND i.is_deleted = 0
");

$invoice = mysqli_fetch_assoc($invoice_result);

if (!$invoice) {
    die('Invoice not found!');
}

$invoiceId = $invoice['id'];
$client_id = $invoice['client_id'];
$bank_id = $invoice['bank_id'];
$item_type = $invoice['item_type'];

// Fetch client only if client_id is valid
$client = null;
if (!empty($client_id)) {
    $client = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM client WHERE id = $client_id"));
}

// Fetch bank only if bank_id is valid
$bank = null;
if (!empty($bank_id)) {
    $bank = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bank WHERE id = $bank_id"));
}

// Fetch items
$items_result = mysqli_query($conn, "
    SELECT ii.*, 
           p.name AS product_name,
           p.code AS product_code,
           s.name AS service_product_name,
           s.code AS service_code,
           COALESCE(p.code, s.code) AS code,
           t.name AS tax_name, 
           u.name AS unit_name
    FROM invoice_item ii
    LEFT JOIN product p ON p.id = ii.product_id
    LEFT JOIN product s ON s.id = ii.service_id
    LEFT JOIN units u ON u.id = ii.unit_id
    LEFT JOIN tax t ON t.id = ii.tax_id
    WHERE ii.invoice_id = $invoice_id AND ii.is_deleted = 0
");

// Check if any item has quantity value
$showQuantityColumn = false;
mysqli_data_seek($items_result, 0);
while ($item = mysqli_fetch_assoc($items_result)) {
    if (!is_null($item['quantity']) && $item['quantity'] > 0) {
        $showQuantityColumn = true;
        break;
    }
}
mysqli_data_seek($items_result, 0);

// Fetch client address only if client_id is valid
$client_address = null;
if (!empty($client_id)) {
    $client_address_query = "
        SELECT ca.*, 
               co.name AS country_name, 
               s.name AS state_name, 
               ci.name AS city_name
        FROM client_address ca
        LEFT JOIN countries co ON co.id = ca.billing_country
        LEFT JOIN states s ON s.id = ca.billing_state
        LEFT JOIN cities ci ON ci.id = ca.billing_city
        WHERE ca.client_id = $client_id
        LIMIT 1
    ";
    $client_address = mysqli_fetch_assoc(mysqli_query($conn, $client_address_query));
}

// Fetch company info with invoice_logo
$company = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT ci.*, 
           co.name AS country_name,
           s.name AS state_name,
           c.name AS city_name
    FROM company_info ci
    LEFT JOIN countries co ON co.id = ci.country_id
    LEFT JOIN states s ON s.id = ci.state_id
    LEFT JOIN cities c ON c.id = ci.city_id
    LIMIT 1
"));

// Check if notes are available
$showNotes = !empty($invoice['invoice_note']);

// Check if terms & conditions are available
$showTerms = !empty($invoice['description']);

// Check if bank details are available
$showBankDetails = $bank && (!empty($bank['bank_name']) || !empty($bank['account_number']) || !empty($bank['ifsc_code']));

// Set headers for PDF download
if ($download) {
    // Force download as PDF with invoice number as filename
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="Invoice_' . htmlspecialchars($invoice['invoice_id']) . '.pdf"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
}

// Function to convert number to words
function numberToWords($number) {
    $ones = array(
        0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
        5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
        14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
        18 => 'Eighteen', 19 => 'Nineteen'
    );
    
    $tens = array(
        2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty',
        6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
    );
    
    if ($number < 20) {
        return $ones[$number];
    }
    
    if ($number < 100) {
        return $tens[(int)($number / 10)] . ($number % 10 != 0 ? ' ' . $ones[$number % 10] : '');
    }
    
    if ($number < 1000) {
        return $ones[(int)($number / 100)] . ' Hundred' . ($number % 100 != 0 ? ' ' . numberToWords($number % 100) : '');
    }
    
    if ($number < 100000) {
        return numberToWords((int)($number / 1000)) . ' Thousand' . ($number % 1000 != 0 ? ' ' . numberToWords($number % 1000) : '');
    }
    
    if ($number < 10000000) {
        return numberToWords((int)($number / 100000)) . ' Lakh' . ($number % 100000 != 0 ? ' ' . numberToWords($number % 100000) : '');
    }
    
    return numberToWords((int)($number / 10000000)) . ' Crore' . ($number % 10000000 != 0 ? ' ' . numberToWords($number % 10000000) : '');
}

// Function to get base URL for images
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    return $protocol . "://" . $host;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice <?= htmlspecialchars($invoice['invoice_id']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        ol, ul, dl {
            margin-bottom: 0px;
        }

        body {
            background: #fff;
            font-family: "Instrument Sans", sans-serif;
            color: #2c3e50;
            font-size: 16px;
        }

        html {
            font-size: 16px;
        }

        .main-body {
            position: relative;
            background-color: #fff;
            border: 1px solid #ccc;
            max-width: 850px;
            margin: 15px auto;
            padding: 30px;
            border-radius: 5px;
        }

        .logo {
            position: relative;
            max-width: 180px;
            max-height: 80px;
        }

        .invoice-title {
            position: relative;
            color: #2c3e50;
            font-weight: 500;
            font-size: 26px;
            margin: 0px
        }

        .invoice-top {
            position: relative;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 10px;
            margin: 20px 0px;
        }

        .tittle-text {
            position: relative;
            color: #2c3e50;
            font-weight: 700;
            font-size: 18px;
        }

        .to-title {
            position: relative;
            color: #000;
            font-weight: 600;
            font-size: 16px;
        }

        .bold-text{
            color: #000;
            font-weight: 600;
        }

        .address-deatils-box {
            position: relative;
            color: #5d6772;
            font-weight: 500;
            font-size: 16px;
        }

        .bank-deatils-title {
            position: relative;
            color: #2c3e50;
            font-weight: 500;
            font-size: 20px;
        }

        .table {
            position: relative;
            width: 100%;
        }

        .bank-details-ul {
            position: relative;
            list-style: none;
            padding-left: 0px;
        }

        .bank-details-ul li {
            position: relative;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 16px;
            line-height: normal;
        }

        .subtotal-box {
            position: relative;
            display: flex;
            gap: 20px;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .subtotal-box h4 {
            position: relative;
            color: #2c3e50;
            font-weight: 500;
            font-size: 16px;
            line-height: normal;
            margin-bottom: 0;
            white-space: nowrap;
        }

        .subtotal-box p {
            position: relative;
            color: #5d6772;
            font-weight: 500;
            font-size: 16px;
            line-height: normal;
            margin-bottom: 0;
        }

        .terms-conditions-title {
            position: relative;
            color: #000;
            font-weight: 500;
            font-size: 18px;
            line-height: normal;
            margin-bottom: 10px;
        }

        .terms-conditions li {
            position: relative;
            color: #5d6772;
            font-weight: 500;
            font-size: 16px;
            line-height: normal;
            margin-bottom: 5px;
        }

        .table-main-body {
            overflow: hidden;
            margin-bottom: 20px;
        }

        .table tbody tr:last-child td {
            border-bottom: 0;
        }

        .table .bg-light td {
            background: #051321;
            color: #fff;
        }

        .terms-conditions {
            background-color: #ddeeff;
            border-radius: 5px;
            padding: 15px;
        }
        
        .gst-badge {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
        }
        .gst-badge.gst {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }
        .gst-badge.non-gst {
            background-color: #fff3cd;
            color: #664d03;
            border: 1px solid #ffecb5;
        }

        /* Print styles for direct PDF generation */
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }
            
            body {
                margin: 0;
                padding: 0;
            }
            
            .main-body {
                margin: 0;
                padding: 0;
                border: none;
                max-width: 100%;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main-body">
            <div class="header">
                <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                        <?php 
                        // Use invoice_logo if available, otherwise fall back to company_logo
                        $logoPath = '';
                        if (!empty($company['invoice_logo'])) {
                            $logoPath = '../uploads/' . htmlspecialchars($company['invoice_logo']);
                        } elseif (!empty($company['company_logo'])) {
                            $logoPath = '../uploads/' . htmlspecialchars($company['company_logo']);
                        }
                        
                        if (!empty($logoPath) && file_exists($logoPath)): 
                        ?>
                            <img src="<?= $logoPath ?>" alt="logo" class="logo">
                        <?php else: ?>
                            <h4><?= htmlspecialchars($company['name'] ?? 'Company Name') ?></h4>
                        <?php endif; ?>
                    </div>
                    <div class="col-auto">
                        <h3 class="invoice-title">Invoice</h3>
                    </div>
                </div>
            </div>
            <div class="main">
                <div class="invoice-top">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="tittle-text">Date:</div>
                            <div class="address-deatils-box"><?= htmlspecialchars($invoice['invoice_date']) ?></div>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <div class="tittle-text">Invoice No:</div>
                            <div class="address-deatils-box"><?= htmlspecialchars($invoice['invoice_id']) ?></div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <div class="tittle-text">Billing From:</div>
                        <div class="to-title"><?= htmlspecialchars($company['name'] ?? '') ?></div>
                        <?php if (!empty($company['address'])): ?>
                            <address class="address-deatils-box mb-0"><?= htmlspecialchars($company['address'] ?? '') ?></address>
                        <?php endif; ?>
                        <?php if (!empty($company['city_name']) || !empty($company['state_name']) || !empty($company['country_name']) || !empty($company['zipcode'])): ?>
                            <div class="address-deatils-box">
                                <?= htmlspecialchars($company['city_name'] ?? '') ?>, 
                                <?= htmlspecialchars($company['state_name'] ?? '') ?>, 
                                <?= htmlspecialchars($company['country_name'] ?? '') ?>, 
                                <?= htmlspecialchars($company['zipcode'] ?? '') ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($company['mobile_number'])): ?>
                            <div class="address-deatils-box"><span class="bold-text">Phone:</span> <?= htmlspecialchars($company['mobile_number'] ?? '') ?></div>
                        <?php endif; ?>
                        <?php if (!empty($company['email'])): ?>
                            <div class="address-deatils-box"><span class="bold-text">Email:</span> <?= htmlspecialchars($company['email'] ?? '') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <div class="tittle-text">Billing To:</div>
                        <div class="to-title"><?= htmlspecialchars($client['first_name'] ?? '') ?></div>
                        <?php if (!empty($client_address['billing_address1'])): ?>
                            <address class="address-deatils-box mb-0"><?= htmlspecialchars($client_address['billing_address1'] ?? '') ?></address>
                        <?php endif; ?>
                        <?php if (!empty($client_address['city_name']) || !empty($client_address['state_name']) || !empty($client_address['country_name']) || !empty($client_address['billing_pincode'])): ?>
                            <div class="address-deatils-box">
                                <?= htmlspecialchars($client_address['city_name'] ?? '') ?>, 
                                <?= htmlspecialchars($client_address['state_name'] ?? '') ?>, 
                                <?= htmlspecialchars($client_address['country_name'] ?? '') ?>, 
                                <?= htmlspecialchars($client_address['billing_pincode'] ?? '') ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($client['phone_number'])): ?>
                            <div class="address-deatils-box"><span class="bold-text">Phone:</span> <?= htmlspecialchars($client['phone_number'] ?? '') ?></div>
                        <?php endif; ?>
                        <?php if (!empty($client['email'])): ?>
                            <div class="address-deatils-box"><span class="bold-text">Email:</span> <?= htmlspecialchars($client['email'] ?? '') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="table-main-body">
                    <div class="table-responsive">
                        <table class="table mb-0 table-bordered">
                            <thead>
                                <tr class="bg-light">
                                    <?php if ($item_type == 1): ?>
                                        <!-- Product Headings -->
                                        <td class="col-4">Product/Service</td>
                                        <td class="col-2">HSN Code</td>
                                        <?php if ($showQuantityColumn): ?>
                                            <td class="col-1 text-center">QTY</td>
                                        <?php endif; ?>
                                        <td class="col-2">Selling Price</td>
                                        <td class="col-1">Tax</td>
                                        <td class="col-2">Amount</td>
                                    <?php else: ?>
                                        <!-- Service Headings -->
                                        <td class="col-4">Service</td>
                                        <td class="col-2">HSN Code</td>
                                        <?php if ($showQuantityColumn): ?>
                                            <td class="col-1 text-center">Hours</td>
                                        <?php endif; ?>
                                        <td class="col-2">Hourly Price</td>
                                        <td class="col-1">Tax</td>
                                        <td class="col-2">Amount</td>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1; 
                                mysqli_data_seek($items_result, 0);
                                while($item = mysqli_fetch_assoc($items_result)) { 
                                    // Combine product name (from product table) and service name (from invoice_item)
                                    if (!empty($item['service_id'])) {
                                        // If it's a service, combine both names with hyphen
                                        $productName = !empty($item['service_product_name']) ? $item['service_product_name'] : '';
                                        $serviceName = !empty($item['service_name']) ? $item['service_name'] : '';
                                        $itemName = $productName .' '. '-' . ' '. $serviceName;
                                    } else {
                                        // If it's a product, use only product name
                                        $itemName = !empty($item['product_name']) ? $item['product_name'] : 'Product';
                                    }
                                ?>
                                <tr>
                                    <td class="col-4"><?= htmlspecialchars($itemName) ?></td>
                                    <td class="col-1"><?= htmlspecialchars($item['code'] ?? 'N/A') ?></td>
                                    <?php if ($showQuantityColumn): ?>
                                        <td class="col-1 text-center"><?= $item['quantity'] ?></td>
                                    <?php endif; ?>
                                    <td class="col-2">$<?= $item['selling_price'] ?></td>
                                    <td class="col-2">
                                        <?php if (($invoice['gst_type'] ?? 'gst') === 'non_gst'): ?>
                                            Non-GST
                                        <?php else: ?>
                                            <?= $item['tax_name'] ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="col-2">$<?= $item['amount'] ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mb-3">
                    <?php if ($showBankDetails): ?>
                    <div class="col-sm-6">
                        <h5 class="terms-conditions-title">Bank Details</h5>
                        <ul class="bank-details-ul">
                            <?php if (!empty($bank['bank_name'])): ?>
                                <li>Bank Name :<span class="address-deatils-box"> <?= htmlspecialchars($bank['bank_name']) ?></span></li>
                            <?php endif; ?>
                            <?php if (!empty($bank['account_number'])): ?>
                                <li>A/C No :<span class="address-deatils-box"> <?= htmlspecialchars($bank['account_number']) ?></span></li>
                            <?php endif; ?>
                            <?php if (!empty($bank['ifsc_code'])): ?>
                                <li>IFSC Code :<span class="address-deatils-box"> <?= htmlspecialchars($bank['ifsc_code']) ?></span></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <div class="<?= $showBankDetails ? 'col-sm-6' : 'col-sm-12' ?> text-sm-end">
                        <div class="subtotal-box">
                            <h4>Sub Amount:</h4>
                            <p>$<?= $invoice['amount'] ?></p>
                        </div>
                        <?php if (($invoice['gst_type'] ?? 'gst') === 'non_gst'): ?>
                            <div class="subtotal-box">
                                <h4>Tax (Non-GST):</h4>
                                <p>$0.00</p>
                            </div>
                        <?php else: ?>
                            <div class="subtotal-box">
                                <h4>Tax Amount:</h4>
                                <p>$<?= $invoice['tax_amount'] ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($invoice['shipping_charge']) && $invoice['shipping_charge'] > 0): ?>
                            <div class="subtotal-box">
                                <h4>Shipping Charge:</h4>
                                <p>$<?= $invoice['shipping_charge'] ?></p>
                            </div>
                        <?php endif; ?>
                        <hr>
                        <div class="subtotal-box mt-3">
                            <h4>Total</h4>
                            <h4>$<?= $invoice['total_amount'] ?></h4>
                        </div>
                        <div class="subtotal-box">
                            <h4>Total In Words:</h4>
                            <p><?= numberToWords($invoice['total_amount']) ?> Dollars</p>
                        </div>
                    </div>
                </div>
                <?php if ($showNotes): ?>
                <div class="terms-conditions">
                    <p class="terms-conditions-title">Notes:</p>
                    <p><?= htmlspecialchars($invoice['invoice_note']) ?></p>
                </div>
                <?php endif; ?>
                <?php if ($showTerms): ?>
                <div class="terms-conditions mt-3">
                    <p class="terms-conditions-title">Terms & Conditions:</p>
                    <p><?= htmlspecialchars($invoice['description']) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>