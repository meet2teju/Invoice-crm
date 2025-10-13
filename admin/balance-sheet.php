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

                <!-- Start Breadcrumb -->
                <div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
                    <div>
                        <h6 class="mb-0">Balance Sheet Report</h6>
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
                <!-- Start Breadcrumb -->

                <div class="border-bottom mb-3">

                    <!-- start row -->
                    <div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="card position-relative shadow-lg">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                                        <div>
                                            <h6 class="fs-16 fw-semibold mb-1">$30,000,000</h6>
                                            <p class="mb-1">Total Assets</p>
                                        </div>
                                        <div>
                                            <span class="badge badge-soft-primary p-2 rounded-circle">
												<i class="isax isax-dollar-circle fs-16"></i>
											</span>
                                        </div>
                                    </div>
                                    <p class="fs-13 mb-0">
                                        <span class="text-success"><i class="isax isax-send text-success me-1"></i>5.62%</span> from last month
                                    </p>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div><!-- end col -->
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="card position-relative shadow-lg">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                                        <div>
                                            <h6 class="fs-16 fw-semibold mb-1">$100,000</h6>
                                            <p class="mb-1">Total Liabilities</p>
                                        </div>
                                        <div>
                                            <span class="badge badge-soft-success p-2 rounded-circle">
												<i class="isax isax-tick-circle fs-16"></i>
											</span>
                                        </div>
                                    </div>
                                    <p class="fs-13 mb-0">
                                        <span class="text-success"><i class="isax isax-send text-success me-1"></i>11.4%</span> from last month
                                    </p>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div><!-- end col -->
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="card position-relative shadow-lg">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                                        <div>
                                            <h6 class="fs-16 fw-semibold mb-1">$400,000</h6>
                                            <p class="mb-1">Net Worth (Equity)</p>
                                        </div>
                                        <div>
                                            <span class="badge badge-soft-warning p-2 rounded-circle">
												<i class="isax isax-wallet-3 fs-16"></i>
											</span>
                                        </div>
                                    </div>
                                    <p class="fs-13 mb-0">
                                        <span class="text-success"><i class="isax isax-send text-success me-1"></i>8.52%</span> from last month
                                    </p>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div><!-- end col -->
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="card position-relative shadow-lg">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                                        <div>
                                            <h6 class="fs-16 fw-semibold mb-1">80%</h6>
                                            <p class="mb-1">Equity Ratio</p>
                                        </div>
                                        <div>
                                            <span class="badge badge-soft-danger p-2 rounded-circle">
												<i class="isax isax-wallet-money fs-16"></i>
											</span>
                                        </div>
                                    </div>
                                    <p class="fs-13 mb-0">
                                        <span class="text-success"><i class="isax isax-received text-success me-1"></i>7.45%</span> from last month
                                    </p>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
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
                                <ul class="dropdown-menu  dropdown-menu-lg">
                                    <li>
                                        <label class="dropdown-item d-flex align-items-center form-switch">
                                            <i class="fa-solid fa-grip-vertical me-3 text-default"></i>
                                            <input class="form-check-input m-0 me-2" type="checkbox" checked>
                                            <span>Account Holder Name</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item d-flex align-items-center form-switch">
                                            <i class="fa-solid fa-grip-vertical me-3 text-default"></i>
                                            <input class="form-check-input m-0 me-2" type="checkbox" checked>
                                            <span>Bank & Account No</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item d-flex align-items-center form-switch">
                                            <i class="fa-solid fa-grip-vertical me-3 text-default"></i>
                                            <input class="form-check-input m-0 me-2" type="checkbox" checked>
                                            <span>Credit</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item d-flex align-items-center form-switch">
                                            <i class="fa-solid fa-grip-vertical me-3 text-default"></i>
                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                            <span>Debit</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item d-flex align-items-center form-switch">
                                            <i class="fa-solid fa-grip-vertical me-3 text-default"></i>
                                            <input class="form-check-input m-0 me-2" type="checkbox" checked>
                                            <span>Balance</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Info -->
                    <div class="align-items-center gap-2 flex-wrap filter-info mt-3">
                        <h6 class="fs-13 fw-semibold">Filters</h6>
                        <span class="tag bg-light border rounded-1 fs-12 text-dark badge"><span class="num-count d-inline-flex align-items-center justify-content-center bg-success fs-10 me-1">6</span>Account Holders Selected<span class="ms-1 tag-close"><i class="fa-solid fa-x fs-10"></i></span></span>
                        <a href="#" class="link-danger fw-medium text-decoration-underline ms-md-1">Clear All</a>
                    </div>
                    <!-- /Filter Info -->
                </div>
                <!-- End Table Search -->

                <!-- Table List Start -->
                <div class="table-responsive">
                    <table class="table table-nowrap datatable">
                        <thead class="thead-light">
                            <tr>
                                <th class="no-sort">
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox" id="select-all">
                                    </div>
                                </th>
                                <th>Account Holder Name</th>
                                <th>Bank & Account No</th>
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-28.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Emily Clark</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>GTB - 3298784309485</td>
                                <td>$15,000</td>
                                <td>$5,000</td>
                                <td>$10,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-29.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">John Carter</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>PNB - 5475878970090</td>
                                <td>$22,500</td>
                                <td>$7,500</td>
                                <td>$15,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-12.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Sophia White</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>SFB - 3255465758698</td>
                                <td>$30,000</td>
                                <td>$10,000</td>
                                <td>$20,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-06.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Michael Johnson</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>HSB - 4353689870544</td>
                                <td>$12,000</td>
                                <td>$3,000</td>
                                <td>$9,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-30.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Olivia Harris</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>ETB - 4324356677889</td>
                                <td>$18,000</td>
                                <td>$6,000</td>
                                <td>$12,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-16.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">David Anderson</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>NPB - 2343547586900</td>
                                <td>$25,000</td>
                                <td>$8,000</td>
                                <td>$17,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-17.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Emma Lewis</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>SDB - 3354456565687</td>
                                <td>$35,000</td>
                                <td>$12,000</td>
                                <td>$23,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-23.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Robert Thomas</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>FEB - 3453647664889</td>
                                <td>$40,000</td>
                                <td>$15,000</td>
                                <td>$25,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-23.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Isabella Scott</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>CCB - 9876543210123</td>
                                <td>$27,500</td>
                                <td>$9,500</td>
                                <td>$18,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-07.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Daniel Martinez</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>CCB - 9876543210123</td>
                                <td>$20,000</td>
                                <td>$7,000</td>
                                <td>$13,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-41.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Charlotte Brown</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>ICB - 6543217896543</td>
                                <td>$45,000</td>
                                <td>$18,000</td>
                                <td>$27,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-42.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">William Parker</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>ECB - 3692581473692</td>
                                <td>$37,500</td>
                                <td>$14,000</td>
                                <td>$23,500</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-43.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Mia Thompson</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>RFB - 3214569873214</td>
                                <td>$38,000</td>
                                <td>$14,000</td>
                                <td>$24,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="assets/img/profiles/avatar-44.jpg" class="rounded-circle" alt="img">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Amelia Robinson</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>MFB - 1597534682597</td>
                                <td>$60,000</td>
                                <td>$25,000</td>
                                <td>$35,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Table List End -->

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
                        <label class="form-label">Account Holder</label>
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