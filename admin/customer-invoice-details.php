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

		<?php include 'layouts/menu.php'; ?>

		<!-- ========================
			Start Page Content
		========================= -->

		<div class="page-wrapper">	

			<!-- Start Content -->
			<div class="content">

				<!-- start row -->
				<div class="row">
					<div class="col-md-10 mx-auto">
						<div>
							<div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
								<h6><a href="customer-invoices.php"><i class="isax isax-arrow-left me-2"></i>Invoice (Customer)</a></h6>
								<div class="d-flex align-items-center flex-wrap row-gap-3">
									<a href="#" class="btn btn-outline-white d-inline-flex align-items-center me-3"><i class="isax isax-document-like me-1"></i>Download PDF</a>
									<a href="#" class="btn btn-outline-white d-inline-flex align-items-center me-3"><i class="isax isax-message-notif me-1"></i>Send Email</a>
									<a href="#" class="btn btn-outline-white d-inline-flex align-items-center me-3"><i class="isax isax-printer me-1"></i>Print</a>
									<a href="#" class="btn btn-primary d-inline-flex align-items-center" data-bs-toggle="offcanvas" data-bs-target="#customcanvas">
										<i class="isax isax-money-send5 me-1"></i>Pay Invoice
									</a>
								</div>
							</div>
							<div class="card">
								<div class="card-body">
									<div class="bg-light p-4 rounded position-relative mb-3">
										<div class="position-absolute top-0 end-0">
											<img src="assets/img/bg/card-bg.png" alt="User Img">
										</div>
										<div class="d-flex align-items-center justify-content-between border-bottom flex-wrap mb-3 pb-2">
											<div class="mb-3">
												<h4 class="mb-1">Invoice</h4>
												<div class="d-flex align-items-center flex-wrap row-gap-3">
													<div class="me-4">
														<h6 class="fs-14 fw-semibold mb-1">Dreams Technologies Pvt Ltd.,</h6>
														<p>15 Hodges Mews, High Wycombe HP12 3JL, United Kingdom</p>
													</div>
													<span><img src="assets/img/icons/not-paid.png" alt="User Img" width="48" height="48"></span>
												</div>
											</div>
											<div class="mb-3">
												<img src="assets/img/invoice-logo.svg" class="invoice-logo-dark" alt="img">
												<img src="assets/img/invoice-logo-white-2.svg" class="invoice-logo-white" alt="img">
											</div>
										</div>

										<!-- start row -->
										<div class="row gy-3">
											<div class="col-lg-4">
												<div>
													<h6 class="mb-2 fs-16 fw-semibold">Invoice Details</h6>
													<div>
														<p class="mb-1">Invoice Number : <span class="text-dark">INV215654</span></p>
														<p class="mb-1">Issued On : <span class="text-dark">25 Jan 2025</span></p>
														<p class="mb-1">Due Date :  <span class="text-dark">31 Jan 2025</span></p>
														<p class="mb-1">Recurring Invoice  :  <span class="text-dark">Monthly</span></p>
														<span class="badge bg-danger">Due in 8 days</span>
													</div>
												</div>
											</div><!-- end col -->
											<div class="col-lg-4">
												<div>
													<h6 class="mb-2 fs-16 fw-semibold">Billing From</h6>
													<div>
														<h6 class="fs-14 fw-semibold mb-1">Kanakku Invoice Management</h6>
														<p class="mb-1">15 Hodges Mews, HP12 3JL, United Kingdom</p>
														<p class="mb-1">Phone : +1 54664 75945</p>
														<p class="mb-1">Email : info@example.com</p>
														<p class="mb-1">GST : 243E45767889</p>
													</div>
												</div>
											</div><!-- end col -->
											<div class="col-lg-4">
												<div>
													<h6 class="mb-2 fs-16 fw-semibold">Billing To</h6>
                                                    
													<div class="bg-white rounded p-3">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <span class="avatar avatar-md border border-gray-100 border-2 me-2">
                                                                <img src="assets/img/invoice/timesquare.png" alt="User Img">
                                                            </span>
                                                            <p class="text-dark fw-semibold">Timesquare Tech</p>
                                                        </div>
														<p class="mb-1">299 Star Trek Drive, Florida, 3240, USA</p>
														<p class="mb-1">Phone : +1 54664 75945</p>
														<p class="mb-1">Email : info@example.com</p>
														<p class="mb-1">GST : 243E45767889</p>
													</div>
												</div>
											</div><!-- end col -->
										</div>
										<!-- end row -->

									</div>
									<div class="mb-3">
										<h6 class="mb-3">Product / Service Items</h6>
										<div class="table-responsive rounded border-bottom-0 border">
											<table class="table table-nowrap add-table">
												<thead class="thead-dark">
													<tr>
														<th>#</th>
														<th>Product/Service</th>
														<th>Quantity</th>
														<th>Unit</th>
														<th>Rate</th>
														<th>Discount</th>
														<th>Tax (%)</th>
														<th>Amount</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>1</td>
														<td class="text-dark">T-Shirt</td>
														<td>2</td>
														<td>Pcs</td>
														<td>$200.00</td>
														<td>10%</td>
														<td>$36.00</td>
														<td>$396.00</td>
													</tr>
													<tr>
														<td>2</td>
														<td class="text-dark">Office Chair</td>
														<td>1</td>
														<td>Pcs</td>
														<td>$350.00</td>
														<td>5%</td>
														<td>$33.25</td>
														<td>$365.75</td>
													</tr>
													<tr>
														<td>3</td>
														<td class="text-dark">LED Monitor</td>
														<td>1</td>
														<td>Pcs</td>
														<td>$399.00</td>
														<td>2%</td>
														<td>$39.10</td>
														<td>$398.90</td>
													</tr>
													<tr>
														<td>4</td>
														<td class="text-dark">Smartphone</td>
														<td>4</td>
														<td>Pcs</td>
														<td>$100.00</td>
														<td>10%</td>
														<td>$36.00</td>
														<td>$396.00</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>

									<div class="border-bottom mb-3">

										<!-- start row -->
										<div class="row">
											<div class="col-lg-6">
												<div class="d-flex align-items-center flex-wrap row-gap-3 mb-3">
													<div class="me-3">
														<p class="mb-2">Scan to the pay</p>
														<span><img src="assets/img/icons/qr.png" alt="User Img"></span>
													</div>
													<div>
														<h6 class="mb-2">Bank Details</h6>
														<div>
															<p class="mb-1">Bank Name :  <span class="text-dark">ABC Bank</span></p>
															<p class="mb-1">Account Number :  <span class="text-dark">782459739212</span></p>
															<p class="mb-1">IFSC Code :  <span class="text-dark">ABC0001345</span></p>
															<p class="mb-0">Payment Reference :  <span class="text-dark">INV-20250220-001</span></p>
														</div>
													</div>
												</div>
											</div><!-- end col -->
											<div class="col-lg-6">
												<div class="mb-3">
													<div class="d-flex align-items-center justify-content-between mb-3">
														<h6 class="fs-14 fw-semibold">Amount</h6>
														<h6 class="fs-14 fw-semibold">$1,793.12</h6>
													</div>
													<div class="d-flex align-items-center justify-content-between mb-3">
														<h6 class="fs-14 fw-semibold">CGST (9%)</h6>
														<h6 class="fs-14 fw-semibold">$18</h6>
													</div>
													<div class="d-flex align-items-center justify-content-between mb-3">
														<h6 class="fs-14 fw-semibold">SGST (9%)</h6>
														<h6 class="fs-14 fw-semibold">$18</h6>
													</div>
													<div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
														<h6 class="fs-14 fw-semibold">Discount</h6>
														<h6 class="fs-14 fw-semibold text-danger">$18</h6>
													</div>
													<div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
														<h6>Total (USD)</h6>
														<h6>$1811.12</h6>
													</div>
													<div>
														<h6 class="fs-14 fw-semibold mb-1">Total In Words</h6>
														<p>One thousand eight hundred &amp; eleven dollars &amp; twelve cents.</p>
													</div>
												</div>
											</div><!-- end col -->
										</div>
										<!-- end row -->

									</div>

									<!-- start row -->
									<div class="row">
										<div class="col-lg-7">
											<div class="mb-3">
												<div class="mb-3">
													<h6 class="fs-14 fw-semibold mb-1">Terms and Conditions</h6>
													<p>The Payment must be returned in the same condition.</p>
												</div>
												<div>
													<h6 class="fs-14 fw-semibold mb-1">Notes</h6>
													<p>All charges are final and include applicable taxes, fees, and additional costs</p>
												</div>
											</div>
										</div><!-- end col -->
										<div class="col-lg-5">
											<div class="text-lg-end mb-3">
												<span><img src="assets/img/icons/sign.png" class="sign-dark" alt="img"></span>
												<h6 class="fs-14 fw-semibold mb-1">Ted M. Davis</h6>
												<p>Manager</p>
											</div>
										</div><!-- end col -->
									</div>
									<!-- end row -->

									<div class="bg-light d-flex align-items-center justify-content-between p-4 rounded card-bg">
										<div>
											<h6 class="fs-14 fw-semibold mb-1">Dreams Technologies Pvt Ltd.,</h6>
											<p>15 Hodges Mews, High Wycombe HP12 3JL, United Kingdom</p>
										</div>
										<div>
											<img src="assets/img/invoice-logo.svg" class="invoice-logo-dark" alt="img">
											<img src="assets/img/invoice-logo-white-2.svg" class="invoice-logo-white" alt="img">
										</div>
									</div>
								</div><!-- end card body -->
							</div><!-- end card -->
						</div>
					</div><!-- end col -->
				</div>
				<!-- end row -->

			</div>
			<!-- End Content -->
			
			<!-- Start Footer-->
			<div class="footer d-sm-flex align-items-center justify-content-between bg-white py-2 px-4 border-top">
				<p class="text-dark mb-0">&copy; 2025 <a href="javascript:void(0);" class="link-primary">Kanakku</a>, All Rights Reserved</p>
				<p class="text-dark">Version : 1.3.8</p>
			</div>
			<!-- End Footer-->

		</div>

		 <!-- ========================
			End Page Content
		========================= -->

		<!-- Start Filter -->
		<div class="offcanvas offcanvas-offset offcanvas-end" tabindex="-1" id="customcanvas">                                      
			<div class="offcanvas-header d-block pb-0">
				<div class="border-bottom d-flex align-items-center justify-content-between pb-3">
					<h6 class="offcanvas-title">Pay Invoice</h6>
					<button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa-solid fa-x"></i></button>
				</div>
			</div>			
			<div class="offcanvas-body pt-3">  
				<form action="#">
                    <div class="activity-feed bg-light rounded d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <p class="text-primary fw-semibold mb-1">#INV00001</p>
                            <p class="fs-13">Due Date : <span class="text-dark">03 Jun 2025</span></p>
                        </div>
                        <div>
                            <p class="text-dark fw-semibold mb-1">Invoice Total</p>
                            <p class="fs-13">$2560.25</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount to be Paid <span class="text-danger">*</span></label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fs-16">Select a Payment Method</h6>
                            <span class="d-flex align-items-center text-dark"   data-bs-dismiss="offcanvas" data-bs-toggle="modal" data-bs-target="#add_card"><i class="isax isax-add-circle5 text-primary me-1"></i>Add</span>
                        </div>
                        <div class="border rounded px-3 py-2 mb-2">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input mt-0" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                <div class="ms-2">
                                    <label class="form-check-label fw-semibold text-dark" for="flexRadioDefault1">
                                        Visa *******5658
                                    </label>
                                    <p class="fs-13 text-gray-5 fw-normal mb-0">Expires on: 12/26</p>
                                </div>
                            </div>
                        </div>
                        <div class="border rounded px-3 py-2 mb-2">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input mt-0" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                <div class="ms-2">
                                    <label class="form-check-label fw-semibold text-dark" for="flexRadioDefault2">
                                        Visa *******5258
                                    </label>
                                    <p class="fs-13 text-gray-5 fw-normal mb-0">Expires on: 10/26</p>
                                </div>
                            </div>
                        </div>
                        <div class="border rounded px-3 py-2 mb-2 d-flex align-items-center h-60">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input mt-0" type="radio" name="flexRadioDefault" id="flexRadioDefault3">
                                <div class="ms-2">
                                    <label class="form-check-label fw-semibold text-dark" for="flexRadioDefault3">
                                        Stripe
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="border rounded px-3 py-2 mb-2 d-flex align-items-center h-60 mb-3">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input mt-0" type="radio" name="flexRadioDefault" id="flexRadioDefault4">
                                <div class="ms-2">
                                    <label class="form-check-label fw-semibold text-dark" for="flexRadioDefault4">
                                        Paypal
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="border-bottom mb-3">
                            <h6 class="fs-16 mb-2">Summary</h6>
                            <div class=" mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <p class="mb-0">Payment</p>
                                    <p>$565</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="mb-0">Platform Fees</p>
                                    <p>$18</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <h6>Total (USD)</h6>
                            <h6>$596</h6>
                        </div>
                    </div>
                    <div class="bg-success-100 p-2 d-flex align-items-center justify-content-center mb-3">
                        <i class="isax isax-security-safe5 text-success fs-40 me-2"></i>
                        <div>
                            <p class="text-dark fw-semibold mb-0">100% Cashback Guarantee</p>
                            <p class="fs-13">We Protect Your Money</p>
                        </div>
                    </div>
                    <div class="mb-2">
                        <a href="#"  class="btn btn-primary w-100 " data-bs-toggle="modal" data-bs-target="#success_modal">Pay Now $596</a>
                    </div>
					<div class="offcanvas-footer">
                        <button data-bs-dismiss="offcanvas" class="btn btn-outline-white w-100">Cancel</button>
                    </div>
				</form>
			</div>
		</div>
		<!-- End Filter -->

   		<!-- Start Add New Card -->
        <div class="modal fade" id="add_card">
			<div class="modal-dialog modal-dialog-centered modal-sm">
				<div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Card</h5>
                        <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-x"></i></button>
                      </div>
					<div class="modal-body">
                        <form action="customer-invoice-details.php">
                            <div class="mb-3">
                                <label class="form-label">Card Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name on Card <span class="text-danger">*</span></label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Expiry Date <span class="text-danger">*</span></label>
                                    <div class="input-group position-relative mb-3">
                                        <input type="text" class="form-control datetimepicker rounded-end">
                                        <span class="input-icon-addon fs-16 text-gray-9">
                                            <i class="isax isax-calendar-2"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Security Number <span class="text-danger">*</span></label>
                                    <div class="input-group position-relative mb-3">
                                        <input type="text" class="form-control rounded-end">
                                        <span class="input-icon-addon fs-16 text-gray-9">
                                            <i class="isax isax-lock-1"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="javascript:void(0);" class="btn btn-outline-white me-3" data-bs-dismiss="modal">Cancel</a>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
					</div>
				</div>
			</div>
		</div>
 		<!-- End Add New Card -->

        <!-- Start Success -->
        <div class="modal fade custom-modal" id="success_modal">
			<div class="modal-dialog modal-dialog-centered modal-sm">
				<div class="modal-content">
					<div class="modal-body text-center">
						<div class="mb-3">
							<i class="isax isax-tick-circle5 fs-48 text-success"></i>
						</div>
						<h6 class="mb-1">Payment Successful</h6>
						<p class="mb-3 text-center">Your invoice payment has been successfully completed! Reference Number: #INV54896</p>
						<div class="d-flex justify-content-center">
							<a href="customer-invoices.php" class="btn btn-outline-white me-3">Back to Invoices</a>
							<a href="javascript:void(0);" class="btn btn-primary close-modal" data-bs-toggle="offcanvas" data-bs-target="#customcanvas3" onclick="closeModal()">View  Details</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Success -->

        <!-- Start Filter -->
		<div class="offcanvas offcanvas-offset offcanvas-end" tabindex="-1" id="customcanvas3">                                      
			<div class="offcanvas-header d-block pb-0">
				<div class="border-bottom d-flex align-items-center justify-content-between pb-3">
					<h6 class="offcanvas-title">Details</h6>
					<button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa-solid fa-x"></i></button>
				</div>
			</div>			
			<div class="offcanvas-body pt-3">  
				<form action="#">
					<div class="mb-3">
						<label class="form-label">Status</label>
						<div class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle btn btn-lg bg-light  d-flex align-items-center justify-content-start fs-13 fw-normal border" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
								Select
							</a>
							<div class="dropdown-menu shadow-lg w-100 dropdown-info">	
								<ul class="mb-3">
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											<i class="fa-solid fa-circle fs-6 text-success me-1"></i>Paid
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											<i class="fa-solid fa-circle fs-6 text-warning me-1"></i>Unpaid
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											<i class="fa-solid fa-circle fs-6 text-danger me-1"></i>Cancelled
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											<i class="fa-solid fa-circle fs-6 text-purple me-1"></i>Partially Paid
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											<i class="fa-solid fa-circle fs-6 text-orange me-1"></i>Uncollectable
										</label>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div>
						<h6 class="fs-16 fw-semibold mb-2">Payment Details</h6>
						<div class="border-bottom mb-3 pb-3">
							<div class="row">
								<div class="col-6">
									<div class="mb-3">
										<h6 class="fs-14 fw-semibold mb-1">PayPal</h6>
										<p>examplepaypal.com</p>
									</div>
								</div>
								<div class="col-6">
									<div class="mb-3">
										<h6 class="fs-14 fw-semibold mb-1">Account </h6>
										<p>examplepaypal.com</p>
									</div>
								</div>
								<div class="col-6">
									<div class="mb-3">
										<h6 class="fs-14 fw-semibold mb-1">Payment Term</h6>
										<p class="d-flex align-items-center">15 Days <span class="badge bg-danger ms-2">Due in 8 days</span></p>
									</div>
								</div>
							</div>
						</div>
					</div>		
					<div>
						<h6 class="fs-16 mb-2">Invoice History</h6>
						<ul class="activity-feed bg-light rounded">
							<li class="feed-item timeline-item">
								<p class="mb-1">Status Changed to <span class="text-dark fw-semibold">Partially Paid</span></p>
								<div class="invoice-date"><span><i class="isax isax-calendar me-1"></i>17 Jan 2025</span></div>
							</li>
							<li class="feed-item timeline-item">
								<p class="mb-1"><span class="text-dark fw-semibold">$300 </span> Partial Amount Paid on <span class="text-dark fw-semibold">Paypal</span></p>
								<div class="invoice-date"><span><i class="isax isax-calendar me-1"></i>16 Jan 2025</span></div>
							</li>
							<li class="feed-item timeline-item">
								<p class="mb-1"><span class="text-dark fw-semibold">John Smith </span> Created <span class="text-dark fw-semibold">Invoice</span><a href="#" class="text-primary">#INV1254</a></p>
								<div class="invoice-date"><span><i class="isax isax-calendar me-1"></i>16 Jan 2025</span></div>
							</li>
						</ul>
					</div>
				</form>
			</div>
		</div>
		<!-- End Filter -->

    </div>
    <!-- End Main Wrapper -->

	<?php include 'layouts/vendor-scripts.php'; ?>

</body>

</html>        