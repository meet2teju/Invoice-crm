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
						<h6 class="mb-0">Sales Orders Report</h6>
					</div>
					<div class="my-xl-auto">
						<div class="dropdown">
							<a href="javascript:void(0);" class="btn btn-outline-white d-inline-flex align-items-center"  data-bs-toggle="dropdown">
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
						<div class="col-xl-3 col-lg-4 col-md-6">
							<div class="card highlights-primary shadow-lg">
								<div class="card-body">
									<div class="d-flex justify-content-between">
										<div>
											<p class="mb-1">Total Sales Orders</p>
											<h6 class="fs-16 fw-semibold mb-1">520</h6>
											<p class="text-truncate">
												<span class="text-success"><i class="isax isax-send fs-10 me-1"></i>5.62%</span> from last month
											</p>
										</div>
										<div>
											<span class="badge badge-soft-primary border border-primary rounded-circle p-2 d-inline-flex align-items-center justify-content-center">
												<i class="isax isax-bag-2 fs-16"></i>
											</span>
										</div>
									</div>
									
								</div><!-- end card body -->
							</div><!-- end card -->
						</div><!-- end col -->
						<div class="col-xl-3 col-lg-4 col-md-6">
							<div class="card highlights-success shadow-lg">
								<div class="card-body">
									<div class="d-flex justify-content-between">
										<div>
											<p class="mb-1">Completed Orders</p>
											<h6 class="fs-16 fw-semibold mb-1">450</h6>
											<p class="text-truncate">
												<span class="text-success"><i class="isax isax-send fs-10 me-1"></i>11.4%</span> from last month
											</p>
										</div>
										<div>
											<span class="badge badge-soft-success border border-success rounded-circle p-2 d-inline-flex align-items-center justify-content-center">
												<i class="isax isax-tick-circle fs-16"></i>
											</span>
										</div>
									</div>
								  
								</div><!-- end card body -->
							</div><!-- end card -->
						</div><!-- end col -->
						<div class="col-xl-3 col-lg-4 col-md-6">
							<div class="card highlights-warning shadow-lg">
								<div class="card-body">
									<div class="d-flex justify-content-between">
										<div>
											<p class="mb-1">Pending Orders</p>
											<h6 class="fs-16 fw-semibold mb-1">45</h6>
											<p class="text-truncate">
												<span class="text-success"><i class="isax isax-send fs-10 me-1"></i>8.52%</span> from last month
											</p>
										</div>
										<div>
											<span class="badge badge-soft-warning border border-warning rounded-circle p-2 d-inline-flex align-items-center justify-content-center">
												<i class="isax isax-timer fs-16"></i>
											</span>
										</div>
									</div>
								   
								</div><!-- end card body -->
							</div><!-- end card -->
						</div><!-- end col -->
						<div class="col-xl-3 col-lg-4 col-md-6">
							<div class="card highlights-danger shadow-lg">
								<div class="card-body">
									<div class="d-flex justify-content-between">
										<div>
											<p class="mb-1">Cancelled Orders</p>
											<h6 class="fs-16 fw-semibold mb-1">25</h6>
											<p class="text-truncate">
												<span class="text-success"><i class="isax isax-send fs-10 me-1"></i>7.45%</span> from last month
											</p>
										</div>
										<div>
											<span class="badge badge-soft-danger border border-danger rounded-circle p-2 d-inline-flex align-items-center justify-content-center">
												<i class="isax isax-close-circle fs-16"></i>
											</span>
										</div>
									</div>
									
								</div><!-- end card body -->
							</div><!-- end card -->
						</div><!-- end col -->
					</div>
					<!-- end row -->
				</div>
				
				<!-- Table Search -->
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
											<span>SKU</span>
										</label>
									</li>
									<li>
										<label class="dropdown-item d-flex align-items-center form-switch">
											<i class="fa-solid fa-grip-vertical me-3 text-default"></i>
											<input class="form-check-input m-0 me-2" type="checkbox" checked>
											<span>Product</span>
										</label>
									</li>
									<li>
										<label class="dropdown-item d-flex align-items-center form-switch">
											<i class="fa-solid fa-grip-vertical me-3 text-default"></i>
											<input class="form-check-input m-0 me-2" type="checkbox" checked>
											<span>Category</span>
										</label>
									</li>
									<li>
										<label class="dropdown-item d-flex align-items-center form-switch">
											<i class="fa-solid fa-grip-vertical me-3 text-default"></i>
											<input class="form-check-input m-0 me-2" type="checkbox" checked>
											<span>Sold Amount</span>
										</label>
									</li>
									<li>
										<label class="dropdown-item d-flex align-items-center form-switch">
											<i class="fa-solid fa-grip-vertical me-3 text-default"></i>
											<input class="form-check-input m-0 me-2" type="checkbox" checked>
											<span>Sold Quantity</span>
										</label>
									</li>
									<li>
										<label class="dropdown-item d-flex align-items-center form-switch">
											<i class="fa-solid fa-grip-vertical me-3 text-default"></i>
											<input class="form-check-input m-0 me-2" type="checkbox" checked>
											<span>Payment Mode</span>
										</label>
									</li>
									<li>
										<label class="dropdown-item d-flex align-items-center form-switch">
											<i class="fa-solid fa-grip-vertical me-3 text-default"></i>
											<input class="form-check-input m-0 me-2" type="checkbox" checked>
											<span>Due Date</span>
										</label>
									</li>
								</ul>
							</div>
						</div>
					</div>				

					<!-- Filter Info -->
					<div class="align-items-center gap-2 flex-wrap filter-info mt-3">
						<h6 class="fs-13 fw-semibold">Filters</h6>
						<span class="tag bg-light border rounded-1 fs-12 text-dark badge"><span class="num-count d-inline-flex align-items-center justify-content-center bg-success fs-10 me-1">5</span>Product Selected<span class="ms-1 tag-close"><i class="fa-solid fa-x fs-10"></i></span></span>	
						<a href="#" class="link-danger fw-medium text-decoration-underline ms-md-1">Clear All</a>
					</div>
					<!-- /Filter Info -->

				</div>
				<!-- /Table Search -->
				
				<!-- Table List -->
				<div class="table-responsive">
					<table class="table table-nowrap datatable">
						<thead>
							<tr>
								<th class="no-sort">
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox" id="select-all">
                                    </div>
                                </th>
								<th>SKU</th>
								<th>Product</th>
								<th>Category</th>
								<th class="no-sort">Sold Amount</th>
								<th class="no-sort">Sold Quantity</th>
								<th class="no-sort">Payment Mode</th>
								<th class="no-sort">Due Date</th>
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
                                    <a href="javascript:void(0);" class="text-default">PR00025</a>
                                </td>
								<td>
                                    <div class="d-flex align-items-center">
										<a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
											<img src="assets/img/products/product-01.jpg" class="rounded-circle" alt="img">
										</a>
										<div>
											<h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Apple iPhone 15</a></h6>
										</div>
									</div>
                                </td>
                                <td>
                                    Smartphones
                                    
                                </td>
								<td class="text-dark">$100</td>
								<td>2</td>
								<td class="text-dark">Cash</td>
								<td>22 Feb 2025</td>
							</tr>
							<tr>
								<td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
									<a href="javascript:void(0);" class="text-default">PR00014</a>
                                </td>
								<td>
                                    <div class="d-flex align-items-center">
										<a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
											<img src="assets/img/products/product-02.jpg" class="rounded-circle" alt="img">
										</a>
										<div>
											<h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Dell XPS 13 9310</a></h6>
										</div>
									</div>
                                </td>
                                <td>
                                    Laptops
                                </td>
								
								<td class="text-dark">$25</td>
								<td>12</td>
								<td class="text-dark">Cheque</td>
								<td>07 Feb 2025</td>
							</tr>
							<tr>
								<td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
									<a href="javascript:void(0);" class="text-default">PR00012</a>
                                </td>
								<td>
                                    <div class="d-flex align-items-center">
										<a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
											<img src="assets/img/products/product-03.jpg" class="rounded-circle" alt="img">
										</a>
										<div>
											<h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Bose QuietComfort 45</a></h6>
										</div>
									</div>
                                </td>
                                <td>
                                    Headphones
                                </td>
								
								<td class="text-dark">$34</td>
								<td>2</td>
								<td class="text-dark">Cash</td>
								<td>30 Jan 2025</td>
							</tr>
							<tr>
								<td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
									<a href="javascript:void(0);" class="text-default">PR00016</a>
                                </td>
								<td>
                                    <div class="d-flex align-items-center">
										<a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
											<img src="assets/img/products/product-04.jpg" class="rounded-circle" alt="img">
										</a>
										<div>
											<h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Nike Dri-FIT T-shirt</a></h6>
										</div>
									</div>
                                </td>
                                <td>
                                    Computer Service
                                </td>
								<td class="text-dark">$75</td>
								<td>24</td>
								
								<td class="text-dark">Cheque</td>
								<td>04 Jan 2025</td>
							</tr>
							<tr>
								<td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
									<a href="javascript:void(0);" class="text-default">PR00022</a>
                                </td>
								<td>
                                    <div class="d-flex align-items-center">
										<a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
											<img src="assets/img/products/product-05.jpg" class="rounded-circle" alt="img">
										</a>
										<div>
											<h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Adidas Ultraboost 22 Running Shoe</a></h6>
										</div>
									</div>
                                </td>
                                <td>
                                    Footwear
                                </td>
								
								<td class="text-dark">$9</td>
								<td>13</td>
								<td class="text-dark">Cash</td>
								<td>09 Dec 2024</td>
							</tr>
							<tr>
								<td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
									<a href="javascript:void(0);" class="text-default">PR00047</a>
                                </td>
								<td>
                                    <div class="d-flex align-items-center">
										<a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
											<img src="assets/img/products/product-06.jpg" class="rounded-circle" alt="img">
										</a>
										<div>
											<h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Samsung French Door Refrigerator</a></h6>
										</div>
									</div>
                                </td>
                                <td>
                                    Kitchen
                                </td>
								
								<td class="text-dark">$120</td>
								<td>67</td>
								<td class="text-dark">Cheque</td>
								<td>02 Dec 2024</td>
							</tr>
							<tr>
								<td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
									<a href="javascript:void(0);" class="text-default">PR00014</a>
                                </td>
								<td>
                                    <div class="d-flex align-items-center">
										<a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
											<img src="assets/img/products/product-07.jpg" class="rounded-circle" alt="img">
										</a>
										<div>
											<h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Dyson V15 Detect  Vacuum Cleaner</a></h6>
										</div>
									</div>
                                </td>
                                <td>
                                    Cleaning
                                </td>
								
								<td class="text-dark">$250</td>
								<td>13</td>
								<td class="text-dark">Cheque</td>
								<td>02 Dec 2024</td>
							</tr>
							<tr>
								<td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
									<a href="javascript:void(0);" class="text-default">PR00031</a>
                                </td>
								<td>
                                    <div class="d-flex align-items-center">
										<a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
											<img src="assets/img/products/product-08.jpg" class="rounded-circle" alt="img">
										</a>
										<div>
											<h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">HP Spectre x360 14</a></h6>
										</div>
									</div>
                                </td>
                                <td>
                                    Laptops
                                </td>
								
								<td class="text-dark">$541</td>
								<td>25</td>
								<td class="text-dark">Cash</td>
								<td>15 Nov 2024</td>
							</tr>
							<tr>
								<td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
									<a href="javascript:void(0);" class="text-default">PR00077</a>
                                </td>
								<td>
                                    <div class="d-flex align-items-center">
										<a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
											<img src="assets/img/products/product-09.jpg" class="rounded-circle" alt="img">
										</a>
										<div>
											<h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Dyson Supersonic Hair Dryer</a></h6>
										</div>
									</div>
                                </td>
                                <td>
                                    Haircare
                                </td>
								
								<td class="text-dark">$741</td>
								<td>24</td>
								<td class="text-dark">Cash</td>
								<td>30 Nov 2024</td>
							</tr>
							<tr>
								<td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
									<a href="javascript:void(0);" class="text-default">PR00045</a>
                                </td>
								<td>
                                    <div class="d-flex align-items-center">
										<a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
											<img src="assets/img/products/product-10.jpg" class="rounded-circle" alt="img">
										</a>
										<div>
											<h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Apple AirPods Pro</a></h6>
										</div>
									</div>
                                </td>
                                <td>
                                    Headphones
                                </td>
								
								<td class="text-dark">$89</td>
								<td>65</td>
								<td class="text-dark">Cash</td>
								<td>12 Oct 2024</td>
							</tr>
							<tr>
								<td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
									<a href="javascript:void(0);" class="text-default">PR00021</a>
                                </td>
								<td>
                                    <div class="d-flex align-items-center">
										<a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
											<img src="assets/img/products/product-11.jpg" class="rounded-circle" alt="img">
										</a>
										<div>
											<h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Levi’s 501 Original Fit Jeans</a></h6>
										</div>
									</div>
                                </td>
                                <td>
                                    Men’s Apparel
                                </td>
								
								<td class="text-dark">$34</td>
								<td>23</td>
								<td class="text-dark">Cheque</td>
								<td>05 Oct 2024</td>
							</tr>
							<tr>
								<td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
									<a href="javascript:void(0);" class="text-default">PR00074</a>
                                </td>
								<td>
                                    <div class="d-flex align-items-center">
										<a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
											<img src="assets/img/products/product-12.jpg" class="rounded-circle" alt="img">
										</a>
										<div>
											<h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">CeraVe Hydrating Facial Cleanser</a></h6>
										</div>
									</div>
                                </td>
                                <td>
                                    Skincare
                                </td>
								
								<td class="text-dark">$45</td>
								<td>12</td>
								<td class="text-dark">Cheque</td>
								<td>09 Sep 2024</td>
							</tr>
							<tr>
								<td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
									<a href="javascript:void(0);" class="text-default">PR00048</a>
                                </td>
								<td>
                                    <div class="d-flex align-items-center">
										<a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
											<img src="assets/img/products/product-13.jpg" class="rounded-circle" alt="img">
										</a>
										<div>
											<h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">Giro Synthe MIPS Helmet</a></h6>
										</div>
									</div>
                                </td>
                                <td>
                                    Bikes & Accessories
                                </td>
								
								<td class="text-dark">$74</td>
								<td>43</td>
								<td class="text-dark">Cheque</td>
								<td class="text-dark">02 Sep 2024</td>
							</tr>
							<tr>
								<td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <td>
									<a href="javascript:void(0);" class="text-default">PR00022</a>
                                </td>
								<td>
                                    <div class="d-flex align-items-center">
										<a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
											<img src="assets/img/products/product-14.jpg" class="rounded-circle" alt="img">
										</a>
										<div>
											<h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);">OnePlus 11 5G</a></h6>
										</div>
									</div>
                                </td>
                                <td>
                                    Smartphones
                                </td>
								
								<td class="text-dark">$80</td>
								<td>20</td>
								<td class="text-dark">Cheque</td>
								<td>07 Aug 2024</td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- /Table List -->

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
						<label class="form-label">Product</label>
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
											<input class="form-check-input select-all m-0 me-2" type="checkbox">
											Select All
										</label>
										<a href="javascript:void(0);" class="link-danger fw-medium text-decoration-underline">Reset</a>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											<span class="avatar avatar-sm rounded-circle me-2"><img src="assets/img/products/product-01.jpg" class="flex-shrink-0 rounded-circle" alt="img"></span>Apple iPhone 15
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											<span class="avatar avatar-sm rounded-circle me-2"><img src="assets/img/products/product-02.jpg" class="flex-shrink-0 rounded-circle" alt="img"></span>Dell XPS 13 9310
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											<span class="avatar avatar-sm rounded-circle me-2"><img src="assets/img/products/product-03.jpg" class="flex-shrink-0 rounded-circle" alt="img"></span>Bose QuietComfort 45
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											<span class="avatar avatar-sm rounded-circle me-2"><img src="assets/img/products/product-04.jpg" class="flex-shrink-0 rounded-circle" alt="img"></span>Nike Dri-FIT T-shirt
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											<span class="avatar avatar-sm rounded-circle me-2"><img src="assets/img/products/product-05.jpg" class="flex-shrink-0 rounded-circle" alt="img"></span>Adidas Ultraboost 22 Running Shoe
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											<span class="avatar avatar-sm rounded-circle me-2"><img src="assets/img/products/product-06.jpg" class="flex-shrink-0 rounded-circle" alt="img"></span>Samsung French Door Refrigerator
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
						<label class="form-label">Category</label>
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
											<input class="form-check-input select-all m-0 me-2" type="checkbox">
											Select All
										</label>
										<a href="javascript:void(0);" class="link-danger fw-medium text-decoration-underline">Reset</a>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											Smartphones
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											Headphones
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											Men's Apparel
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											Footwear
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											Kitchen
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											Cleaning
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											Skincare
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											Bike & Accessories
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
						<label class="form-label">Price</label>
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
						<label class="form-label">Payment Mode</label>
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
											<input class="form-check-input select-all m-0 me-2" type="checkbox">
											Select All
										</label>
										<a href="javascript:void(0);" class="link-danger fw-medium text-decoration-underline">Reset</a>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											Cash
										</label>
									</li>
									<li>
										<label class="dropdown-item px-2 d-flex align-items-center text-dark">
											<input class="form-check-input m-0 me-2" type="checkbox">
											Cheque
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
								<a href="#"  class="btn btn-outline-white w-100">Reset</a>
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