<?php include 'layouts/session.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php include 'layouts/title-meta.php'; ?> 

	<?php include 'layouts/head-css.php'; ?>
</head>

<body>

    <!-- Start Main Wrapper -->
    <div class="main-wrapper">

        <!-- Start Invoice -->
		<div class="invoice-wrapper receipt-page">
            <div class="mb-3">
                <h6><a href="invoice-templates.php"><i class="isax isax-arrow-left me-1"></i>Back</a></h6>
            </div>
            <div class="card m-auto shadow-none">
                <div class="card-body">
                    <div class="bg-light p-2 text-center mb-2">
                        <img src="assets/img/receipt-logo.svg" alt="User Img">
                    </div>
                    <div class="p-2 text-center mb-2">
                        <h6 class="fs-16">Cash Receipt</h6>
                    </div>
                    <h6 class="fs-13 fw-semibold text-center text-gray-5 mb-2">Dreams Technologies Pvt Ltd.,</h6>
                    <p class=" text-center pb-2 border-dashed mb-2">15 Hodges Mews, High Wycombe HP12 3JL, United Kingdom.
                        Email: demo@gmail.com</p>
                    <div class="mb-2">

                        <!-- start row -->
                        <div class="row mb-2 row-gap-3">
                            <div class="col-sm-6 col-md-6">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class=" mb-0">Name:</p>
                                    <p class=" text-dark">John Doe</p>
                                </div>
                            </div><!-- end col -->
                            <div class="col-sm-6 col-md-6">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class=" mb-0">Invoice No:</p>
                                    <p class=" text-dark">CS132453</p>
                                </div>
                            </div><!-- end col -->
                        </div>
                        <!-- end row -->

                        <!-- start row -->
                        <div class="row row-gap-3">
                            <div class="col-sm-6 col-md-6">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class=" mb-0">Customer Id:</p>
                                    <p class=" text-dark">#LL93784</p>
                                </div>
                            </div><!-- end col -->
                            <div class="col-sm-6 col-md-6">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class=" mb-0">Date :</p>
                                    <p class=" text-dark">01.07.2024</p>
                                </div>
                            </div><!-- end col -->
                        </div>
                        <!-- end row -->

                    </div>
                    <div class="receipt-header">
                        <table class="table table-nowrap border-dashed mb-2">
                            <thead>
                                <tr class="mb-2">
                                    <th class="fs-10 border-0 pe-0">SL</t>
                                    <th class="fs-10 border-0 ps-0">Item</th>
                                    <th class="fs-10 border-0 pe-0 text-end">Price</th>
                                    <th class="fs-10 border-0 pe-0 text-end">Qty</th>
                                    <th class="fs-10 border-0 pe-0 text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fs-10 border-0 p-1 pe-0">1.</td>
                                    <td class="fs-10 border-0 p-1 ps-0">Sugarfree</td>
                                    <td class="fs-10 border-0 p-1 text-end">$50</td>
                                    <td class="fs-10 border-0 p-1 text-end">3</td>
                                    <td class="fs-10 border-0 p-1 text-end">$150</td>
                                </tr>
                                <tr>
                                    <td class="fs-10 border-0 p-1 pe-0">2.</td>
                                    <td class="fs-10 border-0 p-1 ps-0">Onion (Loose) (5kg)</td>
                                    <td class="fs-10 border-0 p-1 text-end">$50</td>
                                    <td class="fs-10 border-0 p-1 text-end">2</td>
                                    <td class="fs-10 border-0 p-1 text-end">$100</td>
                                </tr>
                                <tr>
                                    <td class="fs-10 border-0 p-1 pe-0">3.</td>
                                    <td class="fs-10 border-0 p-1 ps-0">Mushrooms - Button 1 pack</td>
                                    <td class="fs-10 border-0 p-1 text-end">$50</td>
                                    <td class="fs-10 border-0 p-1 text-end">3</td>
                                    <td class="fs-10 border-0 p-1 text-end">$150</td>
                                </tr>
                                <tr>
                                    <td class="fs-10 border-0 p-1 pe-0">4.</td>
                                    <td class="fs-10 border-0 p-1 ps-0">Tea 1kg</td>
                                    <td class="fs-10 border-0 p-1 text-end">$50</td>
                                    <td class="fs-10 border-0 p-1 text-end">3</td>
                                    <td class="fs-10 border-0 p-1 text-end">$150</td>
                                </tr>
                                <tr class="border-dashed">
                                    <td class="fs-10 border-0 p-1 pe-0">5.</td>
                                    <td class="fs-10 border-0 p-1 ps-0">Diet Coke Soft Drink 300ml</td>
                                    <td class="fs-10 border-0 p-1 text-end">$50</td>
                                    <td class="fs-10 border-0 p-1 text-end">3</td>
                                    <td class="fs-10 border-0 p-1 text-end">$150</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="fs-10 border-0 p-1">Sub Total :</td>
                                    <td class="border-0"></td>
                                    <td colspan="2" class="fs-10 border-0 p-1 text-end">$700.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="fs-10 border-dashed p-1">Discount :</td>
                                    <td class="border-0"></td>
                                    <td colspan="2" class="fs-10 border-dashed p-1 text-end">-$50.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="fs-10 border-0 p-1">Service Charge :</td>
                                    <td class="border-0"></td>
                                    <td colspan="2" class="fs-10 border-0 p-1 text-end">0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="fs-10 border-dashed p-1">Tax(5%) :</td>
                                    <td class="border-0"></td>
                                    <td colspan="2" class="fs-10 border-dashed p-1 text-end">$5.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="fs-10 border-0 p-1">Total Bill :</td>
                                    <td class="border-0"></td>
                                    <td colspan="2" class="fs-10 border-0 p-1 text-end">$655.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="fs-10 border-0 p-1">Due :</td>
                                    <td class="border-0"></td>
                                    <td colspan="2" class="fs-10 border-0 p-1 text-end">$0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="fs-10 border-dashed p-1 text-dark fw-semibold">Total Payable :</td>
                                    <td class="border-0"></td>
                                    <td colspan="2" class="fs-10 border-dashed p-1 text-dark text-end fw-semibold">$655.00</td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="text-center mb-2">12332345698234592384</p>
                        <img src="assets/img/barcode.svg" class="img-fluid mb-2 pb-2 border-dashed" alt="img">
                        <p class="text-center pb-2 border-dashed">Thank You</p>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
        <!-- End Invoice -->

    </div>
    <!-- End Main Wrapper -->

	<?php include 'layouts/vendor-scripts.php'; ?>

</body>

</html>         