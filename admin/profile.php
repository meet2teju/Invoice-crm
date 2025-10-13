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

            <!-- Start Container  -->
            <div class="content content-two">

                <!-- start row  -->
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="mb-3 border-bottom pb-3">
                            <h6 class="mb-0">Profile</h6>
                        </div>
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="bg-dark avatar avatar-sm me-2 flex-shrink-0"><i class="isax isax-info-circle fs-14"></i></span>
                                    <h6 class="fs-16 fw-semibold mb-0">General Information</h6>
                                </div>
                                <form action="account-settings.php">
                                    <div class="mb-3">
                                        <span class="text-gray-9 fw-bold mb-2 d-flex">Profile Image</span>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xxl border border-dashed bg-light me-3 flex-shrink-0">
                                                <div class="position-relative d-flex align-items-center">
                                                    <img src="assets/img/users/user-01.jpg" class="avatar avatar-xl " alt="User Img">
                                                    <a href="javascript:void(0);" class="rounded-trash trash-top d-flex align-items-center justify-content-center"><i class="isax isax-trash"></i></a>
                                                </div>
                                            </div>
                                            <div class="d-inline-flex flex-column align-items-start">
                                                <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                                    <i class="isax isax-image me-1"></i>Upload Image
                                                    <input type="file" class="form-control image-sign" multiple="">
                                                </div>
                                                <span class="text-gray-9 fs-12">JPG or PNG format, not exceeding 5MB.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-bottom mb-3 pb-2">

										<!-- start row -->
                                        <div class="row gx-3">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Gender</label>
                                                    <select class="select">
                                                        <option>Select</option>
                                                        <option>Male</option>
                                                        <option>Female</option>
                                                    </select>
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">DOB</label>
                                                    <div class="input-group position-relative mb-3">
                                                        <input type="text" class="form-control datetimepicker rounded-end" placeholder="25 Mar 2025">
                                                        <span class="input-icon-addon fs-16 text-gray-9">
                                                            <i class="isax isax-calendar-2"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div><!-- end col -->
                                        </div>
										<!-- end row -->

                                    </div>
                                    <div class="border-bottom mb-3">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="bg-dark avatar avatar-sm me-2 flex-shrink-0"><i class="isax isax-info-circle fs-14"></i></span>
                                            <h6 class="fs-16 fw-semibold mb-0">Address Information</h6>
                                        </div>

										<!-- start row -->
                                        <div class="row gx-3">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Address</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Country</label>
                                                    <select class="select">
                                                        <option>Select</option>
                                                        <option>United States</option>
                                                        <option>Canada</option>
                                                        <option>UK</option>
                                                        <option>Germany</option>
                                                        <option>France</option>
                                                    </select>
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">State</label>
                                                    <select class="select">
                                                        <option>Select</option>
                                                        <option>California</option>
                                                        <option>Ontario</option>
                                                        <option>Bavaria</option>
                                                        <option>Wellington</option>
                                                        <option>Le-de-France</option>
                                                    </select>
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">City<span class="text-danger ms-1">*</span></label>
                                                    <select class="select">
                                                        <option>Select</option>
                                                        <option>Los Angeles</option>
                                                        <option>Toronto</option>
                                                        <option>London</option>
                                                        <option>Munich</option>
                                                        <option>Sydney</option>
                                                    </select>
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Postal Code<span class="text-danger ms-1">*</span></label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div><!-- end col -->
                                        </div>
										<!-- end row -->

                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <button type="button" class="btn btn-outline-white">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div><!-- end card body  -->
                        </div><!-- end card  -->
                    </div><!-- end col  -->
                </div>
                <!-- end row  -->

            </div>
            <!-- container  -->

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