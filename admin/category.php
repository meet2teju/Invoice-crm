<?php include 'layouts/session.php'; ?>
<?php include '../config/config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>
</head>
<body>
    <div class="main-wrapper">
        <?php include 'layouts/menu.php'; ?>

        <div class="page-wrapper">
            <div class="content content-two">
           <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
<?php endif; ?>

                <!-- Page Header -->
                <div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h6>Category</h6>
                    </div>

                    <div class="d-flex my-xl-auto right-content align-items-center flex-wrap gap-2">
                        <div class="table-search d-flex align-items-center mb-0">
                            <div class="search-input position-relative">
                                <a href="javascript:void(0);" class="btn-searchset">
                                    <i class="isax isax-search-normal fs-12"></i>
                                </a>
                            </div>
                        </div>
                        <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#add_modal">
                            <i class="isax isax-add-circle5 me-1"></i>New Category
                        </a>
                    </div>
                </div>

                <!-- Display messages -->
             

                <!-- Table Search -->
                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <!-- Search Input -->
                            <!-- <div class="table-search d-flex align-items-center mb-0">
                                <div class="search-input position-relative">
                                    <a href="javascript:void(0);" class="btn-searchset">
                                        <i class="isax isax-search-normal fs-12"></i>
                                    </a>
                                </div>
                            </div> -->

                            <!-- Delete Button -->
                           <a href="#" class="btn btn-outline-danger delete-multiple d-none">
                        <i class="fa-regular fa-trash-can me-1"></i>Delete
                        </a>
                        </div>
                    </div>
                </div>

                <!-- Table List -->
                <div class="table-responsive">
                    <table class="table table-nowrap datatable">
                        <thead class="thead-light">
                            <tr>
                                <th class="no-sort">
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox" id="select-all">
                                    </div>
                                </th>
                                <th>Category Type</th>
                                <th>Category</th>
                                <th>No of Products</th>
                                <th class="no-sort">Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // $result = mysqli_query($conn, "SELECT * FROM category");
                            $result = mysqli_query($conn, "
                            SELECT c.*, COUNT(p.id) AS product_count
                            FROM category c
                            LEFT JOIN product p ON p.category_id = c.id
                            GROUP BY c.id
                        ");

                            while ($row = mysqli_fetch_assoc($result)) {
                                 $status = $row['status'] ? 'checked' : '';
                            $categoryImg = !empty($row['image']) ? '../uploads/' . htmlspecialchars($row['image']) : 'assets/img/users/user-16.jpg';

                            ?>
                            <tr>
                               
                                <td>
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input category-checkbox" type="checkbox" value="<?= $row['id'] ?>">
                                    </div>
                                </td>
                                <td><?= ($row['category_type'] == 1) ? 'Product' : 'Service' ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar avatar-sm rounded-circle me-2 flex-shrink-0">
                                            <img src="<?= $categoryImg ?>" onerror="this.src='assets/img/users/user-16.jpg';">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-0"><a href="javascript:void(0);"><?= htmlspecialchars($row['name']) ?></a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($row['product_count'] ?? 0) ?></td>
                                <td>
                                      <div class="form-check form-switch">
                                    <input class="form-check-input status-toggle" type="checkbox" role="switch" <?= $status ? 'checked' : '' ?> data-id="<?= $row['id'] ?>">
                                </div>
                                </td>
                                <td class="action-item">
                                    <a href="javascript:void(0);" data-bs-toggle="dropdown">
                                        <i class="isax isax-more"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="#" class="dropdown-item edit-btn" 
                                               data-id="<?= $row['id'] ?>"
                                               data-name="<?= htmlspecialchars($row['name']) ?>"
                                               data-image="<?= htmlspecialchars($row['image'] ?? '') ?>"
                                               data-slug="<?= htmlspecialchars($row['slug'] ?? '') ?>"
                                                data-category_type="<?= htmlspecialchars($row['category_type'] ?? '1') ?>">
                                               <i class="isax isax-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="dropdown-item delete-btn" 
                                               data-id="<?= $row['id'] ?>">
                                               <i class="isax isax-trash me-2"></i>Delete
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php include 'layouts/footer.php'; ?>
        </div>

        <!-- Add Modal -->
        <div id="add_modal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Category</h4>
                        <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="addForm" method="POST" action="process/action_addcategory.php" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <span class="text-gray-9 fw-bold mb-2 d-flex">Image</span>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xxl border border-dashed bg-light me-3 flex-shrink-0" id="add_image_preview">
                                        <i class="isax isax-image text-primary fs-24"></i>
                                    </div>
                                    <div class="d-inline-flex flex-column align-items-start">
                                        <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                            <i class="isax isax-image me-1"></i>Upload Image
                                            <input type="file" name="image" id="add_image" class="form-control image-sign" accept="image/*">
                                        </div>
                                        <span class="text-gray-9 fs-12">JPG or PNG format, not exceeding 5MB.</span>
                                        <span class="text-danger error-msg" id="add_image_error"></span>
                                    </div>
                                </div>
                            </div>
                            <label class="form-label">Item Type</label>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" value="1" type="radio" name="item_type" checked>
                                            <label class="form-check-label">Product</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" value="0" type="radio" name="item_type">
                                            <label class="form-check-label">Service</label>
                                        </div>
                                    </div>
                            <div class="mb-3">
                                <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                                <input type="text" name="name" id="add_name" class="form-control" >
                                <span class="text-danger error-msg" id="add_name_error"></span>
                            </div>
                            <div>
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" id="add_slug" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center justify-content-between gap-1">
                            <button type="button" class="btn btn-outline-white" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="add_category" class="btn btn-primary">Add New</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <!-- Edit Modal -->
<!-- <div id="edit_modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Category</h4>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST" action="process/action_editcategory.php" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="current_image" id="edit_current_image">
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="text-gray-9 fw-bold mb-2 d-flex">Image</span>
                        <div class="d-flex align-items-center">
                              <?php
                                $default_image = 'assets/img/category/default.png';
                                $image_path = '../uploads/' . $row['image'];
                                $category_img = (!empty($row['image']) && file_exists($image_path)) ? $image_path : $default_image;
                                ?>
                            <div class="avatar avatar-xxl border border-dashed bg-light me-3 flex-shrink-0" id="edit_image_preview">
                                <img src="<?= $category_img ?>" class="avatar avatar-xl" id="edit_display_image" alt="Category Image" style="display: none;">
                                <i class="isax isax-image text-primary fs-24"></i>
                            </div>
                            <div class="d-inline-flex flex-column align-items-start">
                                <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                    <i class="isax isax-image me-1"></i>Upload Image
                                    <input type="file" name="image" id="edit_image" class="form-control image-sign" accept="image/*">
                                </div>
                                <span class="text-gray-9 fs-12">JPG or PNG format, not exceeding 5MB.</span>
                                <span class="text-danger error-msg" id="edit_image_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category Type <span class="required">*</span></label>
                        <div class="d-flex align-items-center">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="radio" name="category_type" id="Radio-sm-1" value="1">
                                <label class="form-check-label" for="Radio-sm-1">Product</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category_type" id="Radio-sm-2" value="0">
                                <label class="form-check-label" for="Radio-sm-2">Service</label>
                            </div>
                        </div>
                        <span id="item_type_error" class="error-message text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control">
                        <span class="text-danger error-msg" id="edit_name_error"></span>
                    </div>
                    <div>
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="edit_slug" class="form-control">
                    </div>
                </div>
                <div class="modal-footer d-flex align-items-center justify-content-between gap-1">
                    <button type="button" class="btn btn-outline-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit_category" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div> -->
<!-- Edit Modal -->
<div id="edit_modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Category</h4>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST" action="process/action_editcategory.php" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="current_image" id="edit_current_image">
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="text-gray-9 fw-bold mb-2 d-flex">Image</span>
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xxl border border-dashed bg-light me-3 flex-shrink-0" id="edit_image_preview">
                                <i class="isax isax-image text-primary fs-24"></i>
                            </div>
                            <div class="d-inline-flex flex-column align-items-start">
                                <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                    <i class="isax isax-image me-1"></i>Upload Image
                                    <input type="file" name="image" id="edit_image" class="form-control image-sign" accept="image/*">
                                </div>
                                <span class="text-gray-9 fs-12">JPG or PNG format, not exceeding 5MB.</span>
                                <span class="text-danger error-msg" id="edit_image_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category Type <span class="required">*</span></label>
                        <div class="d-flex align-items-center">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="radio" name="category_type" id="Radio-sm-1" value="1">
                                <label class="form-check-label" for="Radio-sm-1">Product</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category_type" id="Radio-sm-2" value="0">
                                <label class="form-check-label" for="Radio-sm-2">Service</label>
                            </div>
                        </div>
                        <span id="item_type_error" class="error-message text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control">
                        <span class="text-danger error-msg" id="edit_name_error"></span>
                    </div>
                    <div>
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="edit_slug" class="form-control">
                    </div>
                </div>
                <div class="modal-footer d-flex align-items-center justify-content-between gap-1">
                    <button type="button" class="btn btn-outline-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit_category" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
        <!-- Delete Modal -->
        <div id="delete_modal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered modal-m">
                <div class="modal-content">
                    <form id="deleteForm" method="GET" action="process/action_deletecategory.php">
                        <input type="hidden" name="id" id="delete_id">
                        <div class="modal-body text-center">
                            <div class="mb-3">
                                <img src="assets/img/icons/delete.svg" alt="img">
                            </div>
                            <h6 class="mb-1">Delete Category</h6>
                            <p class="mb-3">Are you sure you want to delete this category?</p>
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-outline-white me-3" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Yes, Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Multi Delete Modal -->
        <div class="modal fade" id="multideleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-m">
                <div class="modal-content">
                    <form method="POST" id="multiDeleteForm" action="process/action_multi_delete_category.php">
                        <div class="modal-body text-center">
                            <div class="mb-3">
                                <img src="assets/img/icons/delete.svg" alt="img">
                            </div>
                            <h6 class="mb-1">Delete Categories</h6>
                            <p class="mb-3">Are you sure you want to delete the selected categories?</p>
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-outline-white me-3" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Yes, Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'layouts/vendor-scripts.php'; ?>

    <script>
    $(document).ready(function() {
        // Image preview for add modal
        $('#add_image').change(function() {
            const file = this.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    $('#add_image_error').text('File size must be less than 5MB');
                    $(this).val('');
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#add_image_preview').html(`<img src="${e.target.result}" class="avatar avatar-xl" alt="Preview">`);
                    $('#add_image_error').text('');
                }
                reader.readAsDataURL(file);
            }
        });

        // Image preview for edit modal
        $('#edit_image').change(function() {
            const file = this.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    $('#edit_image_error').text('File size must be less than 5MB');
                    $(this).val('');
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#edit_image_preview').html(`<img src="${e.target.result}" class="avatar avatar-xl" alt="Preview">`);
                    $('#edit_image_error').text('');
                }
                reader.readAsDataURL(file);
            }
        });
        $('#add_name').on('input', function () {
                this.value = this.value.replace(/[0-9]/g, '');
            });
        // Add form validation
        $('#addForm').on('submit', function(e) {
            let valid = true;
            
            // Reset errors
            $('#add_name_error, #add_image_error').text('');
            
            // Validate name
            if ($('#add_name').val().trim() === '') {
                $('#add_name_error').text('Name is required');
                valid = false;
            }
            
            // Only prevent default if validation fails
            if (!valid) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            $(this).find('[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Adding...');
            
            // Form will submit normally if validation passes
            return true;
        });

        // Edit form validation
        $('#editForm').on('submit', function(e) {
            let valid = true;
            
            // Reset errors
            $('#edit_name_error, #edit_image_error').text('');
            
            // Validate name
            if ($('#edit_name').val().trim() === '') {
                $('#edit_name_error').text('Name is required');
                valid = false;
            }
            
            if (!valid) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            $(this).find('[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Saving...');
            
            return true;
        });

        // Handle edit button click to populate modal
     $(document).on('click', '.edit-btn', function(e) {
    e.preventDefault();

    const id = $(this).data('id');
    const name = $(this).data('name');
    const image = $(this).data('image');
    const slug = $(this).data('slug');
    const categoryType = $(this).data('category_type');

    // set values
    $('#edit_id').val(id);
    $('#edit_name').val(name);
    $('#edit_slug').val(slug);
    $('#edit_current_image').val(image);

    // set category type radio
    if (categoryType == '1') {
        $('#Radio-sm-1').prop('checked', true);
    } else {
        $('#Radio-sm-2').prop('checked', true);
    }

    // set image preview
    const preview = $('#edit_image_preview');
    if (image) {
        let imagePath = '../uploads/' + image;
        preview.html(`<img src="${imagePath}" class="avatar avatar-xl" alt="Category Image">`);
    } else {
        preview.html('<i class="isax isax-image text-primary fs-24"></i>');
    }

    // clear previous error messages
    $('#edit_name_error, #edit_image_error').text('');

    // open modal
    $('#edit_modal').modal('show');
});

        // Handle delete button click
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            $('#delete_id').val(id);
            $('#delete_modal').modal('show');
        });

        // Reset forms when modals are closed
        $('#add_modal').on('hidden.bs.modal', function() {
            $('#addForm')[0].reset();
            $('#add_image_preview').html('<i class="isax isax-image text-primary fs-24"></i>');
            $('#add_name_error, #add_image_error').text('');
            $('#addForm').find('[type="submit"]').prop('disabled', false).text('Add New');
        });

        $('#edit_modal').on('hidden.bs.modal', function() {
            $('#edit_name_error, #edit_image_error').text('');
            $('#editForm').find('[type="submit"]').prop('disabled', false).text('Save Changes');
        });

        // Multi-delete functionality
       const multiDeleteModal = new bootstrap.Modal(document.getElementById('multideleteModal'));

// Toggle delete button visibility
function toggleDeleteBtn() {
    if ($('.category-checkbox:checked').length > 0) {
        $('.delete-multiple').removeClass('d-none'); // show
    } else {
        $('.delete-multiple').addClass('d-none'); // hide
    }
}

// Delete button click
$('.delete-multiple').on('click', function(e) {
    e.preventDefault();
    const checkboxes = $('.category-checkbox:checked');
    const form = $('#multiDeleteForm');

    // Clear old hidden inputs
    form.find('input[name="category_ids[]"]').remove();

    // Add selected ids
    checkboxes.each(function() {
        form.append(`<input type="hidden" name="category_ids[]" value="${$(this).val()}">`);
    });

    // Update modal text
    const modalTitle = $('#multideleteModal h6');
    const modalMessage = $('#multideleteModal p');

    if (checkboxes.length === 1) {
        modalTitle.text('Delete Category');
        modalMessage.text('Are you sure you want to delete the selected category?');
    } else {
        modalTitle.text('Delete Categories');
        modalMessage.text(`Are you sure you want to delete the ${checkboxes.length} selected categories?`);
    }

    multiDeleteModal.show();
});

// Select All functionality
$('#select-all').on('change', function() {
    $('.category-checkbox').prop('checked', $(this).prop('checked'));
    toggleDeleteBtn();
});

// Individual checkbox change
$(document).on('change', '.category-checkbox', function() {
    toggleDeleteBtn();
});

// Run once on page load (in case some boxes are pre-checked)
toggleDeleteBtn();

    });
    </script>
           <script>
$(document).ready(function() {
    $('.status-toggle').on('change', function() {
        var id = $(this).data('id');
        var status = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: 'process/action_toggle_category_status.php',
            type: 'POST',
            data: {
                id: id,
                status: status
            },
            success: function(response) {
                console.log('Status updated');
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
});
</script>
<script>
    // Function to validate file type
function validateImage(file, errorElementId, previewElementId) {
    const allowedTypes = ['image/jpeg', 'image/png'];
    const errorElement = $(errorElementId);

    // File type validation
    if (!allowedTypes.includes(file.type)) {
        errorElement.text('Only JPG and PNG files are allowed');
        return false;
    }

    // File size validation (5MB limit)
    if (file.size > 5 * 1024 * 1024) {
        errorElement.text('File size must be less than 5MB');
        return false;
    }

    // If valid, show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        $(previewElementId).html(`<img src="${e.target.result}" class="avatar avatar-xl" alt="Preview">`);
        errorElement.text('');
    }
    reader.readAsDataURL(file);

    return true;
}

// Add Modal
$('#add_image').change(function() {
    const file = this.files[0];
    if (file) {
        if (!validateImage(file, '#add_image_error', '#add_image_preview')) {
            $(this).val('');
        }
    }
});

// Edit Modal
$('#edit_image').change(function() {
    const file = this.files[0];
    if (file) {
        if (!validateImage(file, '#edit_image_error', '#edit_image_preview')) {
            $(this).val('');
        }
    }
});

</script>
</body>
</html>