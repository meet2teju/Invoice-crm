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
				<div class="row justify-content-center">
					<div class="col-xl-12">

						<!-- start row -->
						<div class=" row settings-wrapper d-flex">

							<?php include 'layouts/settings-sidebar.php'; ?>

							<div class="col-xl-9 col-lg-8">
								<div class="mb-3">
									<div class="pb-3 border-bottom mb-3">
										<h6 class="mb-0">Invoice Settings</h6>
									</div>
									<form action="invoice-setting.php">

										<!-- start row -->
										<div class="row">
											<div class="col-md-12">
												<div class="mb-3">
													<label class="form-label">Invoice Image<span class="text-danger ms-1">*</span></label>
													<div class="d-flex align-items-center flex-wrap row-gap-3  mb-3">                                                
														<div class="d-flex align-items-center bg-light justify-content-center avatar avatar-xxl border me-3 flex-shrink-0 text-dark frames">
															<i class="isax isax-image text-primary fs-24"></i>
														</div>                                              
														<div class="profile-upload">
															<div class="profile-uploader d-flex align-items-center">
																<div class="drag-upload-btn btn btn-md btn-primary">
																	<i class="isax isax-image text-white fs-16 me-1"></i>
																	Upload Image
																	<input type="file" class="form-control image-sign" multiple="">
																</div>
															</div>
															<div class="mt-2">
																<p class="fs-12">JPG or PNG format, not exceeding 5MB.</p>
															</div>
														</div>
													</div>
												</div>
											</div><!-- end col -->
										</div>
										<!-- end row -->

										<!-- start row -->
										<div class="row align-items-center">
											<div class="col-md-8 col-sm-12">
												<label class="form-label fw-medium">Invoice Prefix </label>
											</div><!-- end col -->
											<div class="col-md-4 col-sm-12">
												<div class="mb-3">
													<input type="text" class="form-control">
												</div>
											</div><!-- end col -->
										</div>
										<!-- end row -->

										<!-- start row -->
										<div class="row align-items-center">
											<div class="col-md-8 col-sm-12">
												<label class="form-label fw-medium">Invoice Round Off </label>
											</div><!-- end col -->
											<div class="col-md-3 col-sm-12">
												<div class="mb-3 d-flex align-items-center">
													<select class="select">
														<option>Select</option>
														<option>5</option>
														<option>10</option>
													</select>
												</div>
											</div><!-- end col -->
											<div class="col-md-1 col-sm-12">
												<div class="ms-1 d-flex align-items-center mb-3">
													<div class="form-check form-check-sm form-switch">
														<label class="form-check-label form-label m-0">
														<input class="form-check-input form-label" type="checkbox" role="switch" checked>
														</label>
													</div>
												</div>
											</div><!-- end col -->
										</div>
										<!-- end row -->

										<!-- start row -->
										<div class="row align-items-center">
											<div class="col-md-8 col-sm-12">
												<label class="form-label fw-medium">Show Company Details </label>
											</div><!-- end col -->
											<div class="col-md-4 col-sm-12">
												<div class="form-check form-check-sm form-switch text-end">
													<label class="form-check-label form-label m-0">
													<input class="form-check-input form-label" type="checkbox" role="switch" checked>
													</label>
												</div>
											</div><!-- end col -->
										</div>	
										<!-- end row -->

										<!-- start row -->
										<div class="row align-items-center">
											<div class="col-md-4 col-sm-12">
												<label class="form-label fw-medium">Invoice Terms </label>
											</div><!-- end col -->
											<div class="col-md-8 col-sm-12">
												<div class="editor mb-1"></div>
											</div><!-- end col -->
										</div>
										<!-- end row -->

									</form>
								</div>
							</div><!-- end col -->
						</div>
						<!-- end row -->

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

</body>

</html>        