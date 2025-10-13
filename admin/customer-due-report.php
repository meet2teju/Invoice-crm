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
            <div class="content content-two">

                <!-- Page Header -->
                <div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
                    <div>
                        <h6 class="mb-0">Customer Due Report</h6>
                    </div>
                    <div class="my-xl-auto">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-outline-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                                <i class="isax isax-export-1 me-1"></i>Export
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);">Download as PDF</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);">Download as Excel</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
				<!-- End Page Header -->

                <div class="border-bottom mb-3">
					<!-- start row -->
					<div class="row">
						<div class="col-xl-3 col-lg-6 col-md-6">
							<div class="card shadow-lg position-relative border-0 border-bottom border-3 border-primary custom-border">
								<div class="card-body">
									<div class="d-flex align-items-center mb-2">
										<div class="me-2">
											<span class="avatar bg-primary rounded-circle border border-primary text-white">
												<i class="isax isax-profile-2user fs-16"></i>
											</span>
										</div>
										<div>
											<p class="mb-1 text-truncate">Total Customers</p>
											<h6 class="fs-16 fw-semibold mb-0">320</h6>
										</div>
									</div>
									<p class="fs-13 mb-0">
										<span class="text-success"><i class="isax isax-send text-success me-1"></i>5.62%</span> from last month
									</p>
								</div><!-- end card body -->
							</div><!-- end card -->
						</div><!-- end col -->
						<div class="col-xl-3 col-lg-6 col-md-6">
							<div class="card shadow-lg position-relative border-0 border-bottom border-3 border-success custom-border">
								<div class="card-body">
									<div class="d-flex align-items-center mb-2">
										<div class="me-2">
											<span class="avatar bg-success rounded-circle border border-success text-white">
												<i class="isax isax-profile-2user fs-16"></i>
											</span>
										</div>
										<div>
											<p class="mb-1 text-truncate">Outstanding Amount</p>
											<h6 class="fs-16 fw-semibold mb-0">$3,500,000</h6>
										</div>
									</div>
									<p class="fs-13 mb-0">
										<span class="text-success"><i class="isax isax-send text-success me-1"></i>11.4%</span> from last month
									</p>
								</div><!-- end card body -->
							</div><!-- end card -->
						</div><!-- end col -->
						<div class="col-xl-3 col-lg-6 col-md-6">
							<div class="card shadow-lg position-relative border-0 border-bottom border-3 border-warning custom-border">
								<div class="card-body">
									<div class="d-flex align-items-center mb-2">
										<div class="me-2">
											<span class="avatar bg-warning rounded-circle border border-warning text-white">
												<i class="isax isax-dollar-circle fs-16"></i>
											</span>
										</div>
										<div>
											<p class="mb-1 text-truncate">Overdue Payments</p>
											<h6 class="fs-16 fw-semibold mb-0">$1,200,000</h6>
										</div>
									</div>
									<p class="fs-13 mb-0">
										<span class="text-success"><i class="isax isax-send text-success me-1"></i>8.52%</span> from last month
									</p>
								</div><!-- end card body -->
							</div><!-- end card -->
						</div><!-- end col -->
						<div class="col-xl-3 col-lg-6 col-md-6">
							<div class="card shadow-lg position-relative border-0 border-bottom border-3 border-danger custom-border">
								<div class="card-body">
									<div class="d-flex align-items-center mb-2">
										<div class="me-2">
											<span class="avatar bg-danger rounded-circle border border-danger text-white">
												<i class="isax isax-dollar-circle fs-16"></i>
											</span>
										</div>
										<div>
											<p class="mb-1 text-truncate">Margin</p>
											<h6 class="fs-16 fw-semibold mb-0">65%</h6>
										</div>
									</div>
									<p class="fs-13 mb-0">
										<span class="text-success"><i class="isax isax-send text-success me-1"></i>7.45%</span> from last month
									</p>
								</div><!-- end card body -->
							</div><!-- end card -->
						</div><!-- end col -->
					</div>
					<!-- end row -->
				</div>

                <!-- Start Table Search -->
				<div class="mb-3">
					<div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
						<div class="d-flex align-items-center gap-2 flex-wrap">
							<div class="table-search d-flex align-items-center mb-0">
								<div class="search-input">
									<a href="javascript:void(0);" class="btn-searchset"><i class="isax isax-search-normal fs-12"></i></a>
								</div>
							</div>
							<div id="reportrange" class="reportrange-picker d-flex align-items-center">
								<i class="isax isax-calendar text-gray-5 fs-14 me-1"></i><span class="reportrange-picker-field">16 Apr 25 - 16 Apr 25</span>
							</div>
							<a class="btn btn-outline-white fw-normal d-inline-flex align-items-center" href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#customcanvas">
								<i class="isax isax-filter me-1"></i>Filter
							</a>
						</div>
						<div class="d-flex align-items-center flex-wrap gap-2">
							<div class="dropdown">
								<a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside">
									<i class="isax isax-grid-3 me-1"></i>Column
								</a>
								<ul class="dropdown-menu  dropdown-menu">
									<li>
										<label class="dropdown-item d-flex align-items-center form-switch">
											<i class="fa-solid fa-grip-vertical me-3 text-default"></i>
											<input class="form-check-input m-0 me-2" type="checkbox" checked>
											<span>ID</span>
										</label>
									</li>
									<li>
										<label class="dropdown-item d-flex align-items-center form-switch">
											<i class="fa-solid fa-grip-vertical me-3 text-default"></i>
											<input class="form-check-input m-0 me-2" type="checkbox" checked>
											<span>Customer</span>
										</label>
									</li>
									<li>
										<label class="dropdown-item d-flex align-items-center form-switch">
											<i class="fa-solid fa-grip-vertical me-3 text-default"></i>
											<input class="form-check-input m-0 me-2" type="checkbox" checked>
											<span>Total Amount</span>
										</label>
									</li>
									<li>
										<label class="dropdown-item d-flex align-items-center form-switch">
											<i class="fa-solid fa-grip-vertical me-3 text-default"></i>
											<input class="form-check-input m-0 me-2" type="checkbox" checked>
											<span>Paid</span>
										</label>
									</li>
									<li>
										<label class="dropdown-item d-flex align-items-center form-switch">
											<i class="fa-solid fa-grip-vertical me-3 text-default"></i>
											<input class="form-check-input m-0 me-2" type="checkbox">
											<span>Due</span>
										</label>
									</li>
									<li>
										<label class="dropdown-item d-flex align-items-center form-switch">
											<i class="fa-solid fa-grip-vertical me-3 text-default"></i>
											<input class="form-check-input m-0 me-2" type="checkbox">
											<span>Status</span>
										</label>
									</li>
								</ul>
							</div>
						</div>
					</div>
                    <!-- Filter Info -->
                    <div class="align-items-center gap-2 flex-wrap filter-info mt-3">
                        <h6 class="fs-13 fw-semibold">Filters</h6>
                        <span class="tag bg-light border rounded-1 fs-12 text-dark badge"><span class="num-count d-inline-flex align-items-center justify-content-center bg-success fs-10 me-1">5</span>Customers Selected<span class="ms-1 tag-close"><i class="fa-solid fa-x fs-10"></i></span></span>                   
                        <span class="tag bg-light border rounded-1 fs-12 text-dark badge"><span class="num-count d-inline-flex align-items-center justify-content-center bg-success fs-10 me-1">1</span>Status Selected<span class="ms-1 tag-close"><i class="fa-solid fa-x fs-10"></i></span></span>                                           
                        <a href="#" class="link-danger fw-medium text-decoration-underline ms-md-1">Clear All</a>
                    </div>
                    <!-- /Filter Info -->		
				</div>
				<!-- End Table Search -->

                <!-- Start Table List -->
                <div class="table-responsive">
                    <table class="table table-nowrap datatable">
                        <thead class="thead-light">
                            <tr>
                                <th class="no-sort">
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox" id="select-all">
                                    </div>
                                </th>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Total Amount</th>
                                <th>Paid</th>
                                <th>Status</th>
                                <th>Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td><a href="javascript:void(0);">INV00025</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="customer-details.php" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-28.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="customer-details.php">Emily Clark</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>$10,000</td>
                                <td>$5,000</td>
                                <td><span class="badge badge-soft-success d-inline-flex align-items-center">Paid <i class="isax isax-tick-circle ms-1"></i></span></td>
                                <td>$50</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td><a href="javascript:void(0);">INV00024</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="customer-details.php" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-29.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="customer-details.php">John Carter</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>$15,000</td>
                                <td>$10,750</td>
                                <td><span class="badge badge-soft-warning d-inline-flex align-items-center">Pending <i class="isax isax-timer ms-1"></i></span></td>
                                <td>$100</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td><a href="javascript:void(0);">INV00023</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="customer-details.php" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-12.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="customer-details.php">Sophia White</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>$20,000</td>
                                <td>$20,000</td>
                                <td><span class="badge badge-soft-danger d-inline-flex align-items-center">Cancelled <i class="isax isax-close-circle ms-1"></i></span></td>
                                <td>$200</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td><a href="javascript:void(0);">INV00022</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="customer-details.php" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-06.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="customer-details.php">Michael Johnson</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>$9,000</td>
                                <td>$50,000</td>
                                <td><span class="badge badge-soft-success d-inline-flex align-items-center">Paid <i class="isax isax-tick-circle ms-1"></i></span></td>
                                <td>$500</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td><a href="javascript:void(0);">INV00021</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="customer-details.php" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-30.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="customer-details.php">Olivia Harris</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>$12,000</td>
                                <td>$80,000</td>
                                <td><span class="badge badge-soft-warning d-inline-flex align-items-center">Pending <i class="isax isax-timer ms-1"></i></span></td>
                                <td>$800</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td><a href="javascript:void(0);">INV00020</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="customer-details.php" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-16.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="customer-details.php">David Anderson</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>$17,000</td>
                                <td>$60,000</td>
                                <td><span class="badge badge-soft-danger d-inline-flex align-items-center">Cancelled <i class="isax isax-close-circle ms-1"></i></span></td>
                                <td>$600</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td><a href="javascript:void(0);">INV00019</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="customer-details.php" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-17.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="customer-details.php">Emma Lewis</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>$23,000</td>
                                <td>$1,25,000</td>
                                <td><span class="badge badge-soft-success d-inline-flex align-items-center">Paid <i class="isax isax-tick-circle ms-1"></i></span></td>
                                <td>$125</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td><a href="javascript:void(0);">INV00018</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="customer-details.php" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-23.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="customer-details.php">Robert Thomas</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>$25,000</td>
                                <td>$5,00,000</td>
                                <td><span class="badge badge-soft-warning d-inline-flex align-items-center">Pending <i class="isax isax-timer ms-1"></i></span></td>
                                <td>$50000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td><a href="javascript:void(0);">INV00017</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="customer-details.php" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-23.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="customer-details.php">Isabella Scott</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>$18,000</td>
                                <td>$2,50,500</td>
                                <td><span class="badge badge-soft-danger d-inline-flex align-items-center">Cancelled <i class="isax isax-close-circle ms-1"></i></span></td>
                                <td>$25000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td><a href="javascript:void(0);">INV00016</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="customer-details.php" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-07.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="customer-details.php">Daniel Martinez</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>$13,000</td>
                                <td>$4,00,000</td>
                                <td><span class="badge badge-soft-success d-inline-flex align-items-center">Paid <i class="isax isax-tick-circle ms-1"></i></span></td>
                                <td>$40000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td><a href="javascript:void(0);">INV00015</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="customer-details.php" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-41.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="customer-details.php">Charlotte Brown</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>$27,000</td>
                                <td>$40,000</td>
                                <td><span class="badge badge-soft-warning d-inline-flex align-items-center">Pending <i class="isax isax-timer ms-1"></i></span></td>
                                <td>$40,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td> <a href="javascript:void(0);">INV00014</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="customer-details.php" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-42.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="customer-details.php">William Parker</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>$23,500</td>
                                <td>$30,000</td>
                                <td><span class="badge badge-soft-danger d-inline-flex align-items-center">Cancelled <i class="isax isax-close-circle ms-1"></i></span></td>
                                <td>$3355</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td><a href="javascript:void(0);">INV00013</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="customer-details.php" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-43.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="customer-details.php">Mia Thompson</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>$24,000</td>
                                <td>$15,000</td>
                                <td><span class="badge badge-soft-success d-inline-flex align-items-center">Paid <i class="isax isax-tick-circle ms-1"></i></span></td>
                                <td>$1500</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td><a href="javascript:void(0);">INV00012</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="customer-details.php" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-44.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="customer-details.php">Amelia Robinson</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>$35,000</td>
                                <td>$1,50,000</td>
                                <td><span class="badge badge-soft-warning d-inline-flex align-items-center">Pending <i class="isax isax-timer ms-1"></i></span></td>
                                <td>$1450</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- End Table List -->

            </div>
			<!-- End Content -->

            <?php include 'layouts/footer.php'; ?>

        </div>

        <!-- ========================
			End Page Content
		========================= -->

        <!-- Start Filter -->
        <div class="offcanvas offcanvas-offset offcanvas-end" tabindex="-1" id="customcanvas">
            <div class="offcanvas-header d-block pb-0">
                <div class="border-bottom d-flex align-items-center justify-content-between pb-3">
                    <h6 class="offcanvas-title">Filter</h6>
                    <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa-solid fa-x"></i></button>
                </div>
            </div>
            <div class="offcanvas-body pt-3">
                <form action="#">
                    <div class="mb-3">
                        <label class="form-label">Customers</label>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-lg bg-light  d-flex align-items-center justify-content-start fs-13 fw-normal border" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
								Select
							</a>
                            <div class="dropdown-menu shadow-lg w-100 dropdown-info">
                                <div class="mb-3">
                                    <div class="input-icon-start position-relative">
                                        <span class="input-icon-addon fs-12">
											<i class="isax isax-search-normal"></i>
										</span>
                                        <input type="text" class="form-control form-control-sm" placeholder="Search">
                                    </div>
                                </div>
                                <ul class="mb-3">
                                    <li class="d-flex align-items-center justify-content-between mb-3">
                                        <label class="d-inline-flex align-items-center text-gray-9">
                                            <input class="form-check-input select-all m-0 me-2" type="checkbox"> Select All
                                        </label>
                                        <a href="javascript:void(0);" class="link-danger fw-medium text-decoration-underline">Reset</a>
                                    </li>
                                    <li>
                                        <label class="dropdown-item px-2 d-flex align-items-center text-dark">
                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                            <span class="avatar avatar-sm rounded-circle me-2">
                                                <img src="assets/img/profiles/avatar-28.jpg" class="flex-shrink-0 rounded-circle" alt="img">
                                            </span>Emily Clark
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item px-2 d-flex align-items-center text-dark">
                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                            <span class="avatar avatar-sm rounded-circle me-2">
                                                <img src="assets/img/profiles/avatar-12.jpg" class="flex-shrink-0 rounded-circle" alt="img">
                                            </span>Sophia White
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item px-2 d-flex align-items-center text-dark">
                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                            <span class="avatar avatar-sm rounded-circle me-2">
                                                <img src="assets/img/profiles/avatar-06.jpg" class="flex-shrink-0 rounded-circle" alt="img">
                                            </span>Michael Johnson
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item px-2 d-flex align-items-center text-dark">
                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                            <span class="avatar avatar-sm rounded-circle me-2">
                                                <img src="assets/img/profiles/avatar-30.jpg" class="flex-shrink-0 rounded-circle" alt="img">
                                            </span>Olivia Harris
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item px-2 d-flex align-items-center text-dark">
                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                            <span class="avatar avatar-sm rounded-circle me-2">
                                                <img src="assets/img/profiles/avatar-16.jpg" class="flex-shrink-0 rounded-circle" alt="img">
                                            </span>David Anderson
                                        </label>
                                    </li>
                                </ul>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <a href="#" class="btn btn-outline-white w-100 close-filter">Cancel</a>
                                    </div>
                                    <div class="col-6">
                                        <a href="#" class="btn btn-primary w-100">Select</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-lg bg-light  d-flex align-items-center justify-content-start fs-13 fw-normal border" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
								Select
							</a>
                            <div class="dropdown-menu shadow-lg w-100 dropdown-info">
                                <div class="filter-range">
                                    <input type="text" id="range_03">
                                    <p>Range : <span class="text-gray-9">Range : $200 - $5695</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-lg bg-light  d-flex align-items-center justify-content-start fs-13 fw-normal border" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
								Select
							</a>
                            <div class="dropdown-menu shadow-lg w-100 dropdown-info">
                                <div class="mb-3">
                                    <div class="input-icon-start position-relative">
                                        <span class="input-icon-addon fs-12">
											<i class="isax isax-search-normal"></i>
										</span>
                                        <input type="text" class="form-control form-control-sm" placeholder="Search">
                                    </div>
                                </div>
                                <ul class="mb-3">
                                    <li class="d-flex align-items-center justify-content-between mb-3">
                                        <label class="d-inline-flex align-items-center text-gray-9">
                                            <input class="form-check-input select-all m-0 me-2" type="checkbox"> Select All
                                        </label>
                                        <a href="javascript:void(0);" class="link-danger fw-medium text-decoration-underline">Reset</a>
                                    </li>
                                    <li>
                                        <label class="dropdown-item px-2 d-flex align-items-center text-dark">
                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                            <span class="badge-dot bg-success me-1"></span>Paid
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item px-2 d-flex align-items-center text-dark">
                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                            <span class="badge-dot bg-warning me-1"></span>Pending
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item px-2 d-flex align-items-center text-dark">
                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                            <span class="badge-dot bg-danger me-1"></span>Cancelled
                                        </label>
                                    </li>
                                </ul>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <a href="#" class="btn btn-outline-white w-100 close-filter">Cancel</a>
                                    </div>
                                    <div class="col-6">
                                        <a href="#" class="btn btn-primary w-100">Select</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="offcanvas-footer">
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="#" class="btn btn-outline-white w-100">Reset</a>
                            </div>
                            <div class="col-6">
                                <button data-bs-dismiss="offcanvas" class="btn btn-primary w-100" id="filter-submit">Submit</button>
                            </div>
                        </div>
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