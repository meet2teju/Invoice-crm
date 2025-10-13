<?php
include 'layouts/session.php';
include '../config/config.php';

$task_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch task details with project and client information
$task_query = "
    SELECT pt.*, 
           p.project_name, 
           p.project_code,
           p.client_id,
           c.first_name as client_name,
           c.customer_image as client_image
    FROM project_task pt
    LEFT JOIN project p ON pt.project_id = p.id
    LEFT JOIN client c ON p.client_id = c.id
    WHERE pt.id = $task_id AND pt.is_deleted = 0
";
$task_result = mysqli_query($conn, $task_query);
$task = mysqli_fetch_assoc($task_result);

if (!$task) {
    $_SESSION['message'] = 'Task not found';
    $_SESSION['message_type'] = 'error';
    header('Location: project-tasks.php');
    exit();
}

// Fetch task attachments
$attachments_query = "SELECT * FROM project_task_doc WHERE task_id = $task_id ORDER BY created_at DESC";
$attachments_result = mysqli_query($conn, $attachments_query);
$attachments = [];
while ($row = mysqli_fetch_assoc($attachments_result)) {
    $attachments[] = $row;
}

// Fetch assigned users
$assigned_users_query = "
    SELECT u.id, u.name, u.email, u.profile_img 
    FROM project_users pu 
    JOIN login u ON pu.user_id = u.id 
    WHERE pu.project_id = $task_id AND pu.is_deleted = 0
    ORDER BY u.name ASC
";
$assigned_users_result = mysqli_query($conn, $assigned_users_query);
$assigned_users = [];
while ($row = mysqli_fetch_assoc($assigned_users_result)) {
    $assigned_users[] = $row;
}

// Define status options (same as in your add/edit forms)
$statusOptions = [
    1 => ['name' => 'Pending', 'color' => '#ffc107'],
    2 => ['name' => 'In Progress', 'color' => '#17a2b8'],
    3 => ['name' => 'Completed', 'color' => '#28a745'],
    4 => ['name' => 'On Hold', 'color' => '#6c757d'],
    5 => ['name' => 'Cancelled', 'color' => '#dc3545']
];

// Get status info
$status_id = $task['status_id'] ?? 1;
$status_name = $statusOptions[$status_id]['name'] ?? 'Pending';
$status_color = $statusOptions[$status_id]['color'] ?? '#6c757d';

// Format dates
$start_date = !empty($task['start_date']) ? date('d-m-Y', strtotime($task['start_date'])) : 'Not set';
$end_date = !empty($task['end_date']) ? date('d-m-Y', strtotime($task['end_date'])) : 'Not set';
$created_date = !empty($task['created_at']) ? date('d-m-Y', strtotime($task['created_at'])) : 'Not set';
?>

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
            
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Task Information</h5>
                <div>
                    <a href="edit-task.php?id=<?= $task_id ?>" class="btn btn-sm btn-primary">
                        <i class="isax isax-edit me-1"></i>Edit Task
                    </a>
                    <a href="project-details.php?id=<?= $task['project_id'] ?>" class="btn btn-outline-info">
                        <i class="isax isax-building-3 me-1"></i>View Project
                    </a>
                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                        <i class="isax isax-refresh me-1"></i>Change Status
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteTaskModal">
                        <i class="isax isax-trash me-1"></i>Delete Task
                    </button>
                    <a href="project-tasks.php" class="btn btn-sm btn-outline-white">
                        <i class="isax isax-arrow-left me-1"></i>Back to Tasks
                    </a>
                </div>
            </div>

            <!-- Task Info Card -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 mb-3">
                            <h4 class="view-task-title">Task Name:</h4>
                            <span class="d-flex align-items-center mt-1 view-task-detais-text"><?= htmlspecialchars($task['task_name']) ?></span>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <h4 class="view-task-title">Status:</h4>
                            <span class="badge fs-12" style="background-color: <?= $status_color ?>; color: white;">
                                <?= htmlspecialchars($status_name) ?>
                            </span>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-3">
                            <h4 class="view-task-title">Project:</h4>
                            <div class="d-flex align-items-center mt-1 view-task-detais-text">
                                <i class="isax isax-building-3 me-2 text-primary"></i>
                                <span><?= htmlspecialchars($task['project_name']) ?></span>
                            </div>
                            <small class="text-muted">Code: <?= htmlspecialchars($task['project_code']) ?></small>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-3">
                            <h4 class="view-task-title">Client:</h4>
                            <div class="d-flex align-items-center mt-1">
                                <?php if (!empty($task['client_image'])): ?>
                                    <img src="../uploads/<?= htmlspecialchars($task['client_image']) ?>" 
                                         class="rounded-circle me-2" 
                                         style="width:24px; height:24px; object-fit:cover;"
                                         onerror="this.src='assets/img/users/user-16.jpg'">
                                <?php else: ?>
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width:24px; height:24px; font-size:10px;">
                                        <?= strtoupper(substr($task['client_name'] ?? 'C', 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <span class="view-task-detais-text"><?= htmlspecialchars($task['client_name'] ?? 'Not assigned') ?></span>
                            </div>
                        </div>

                        <!-- Assigned Users Section -->
                        <div class="col-lg-3 col-md-6 mb-3">
                            <h4 class="view-task-title">Assigned Users:</h4>
                            <?php if (!empty($assigned_users)): ?>
                                <div class="mt-1">
                                    <?php foreach ($assigned_users as $user): ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <?php if (!empty($user['profile_img'])): ?>
                                                <img src="../uploads/<?= htmlspecialchars($user['profile_img']) ?>" 
                                                     class="rounded-circle me-2" 
                                                     style="width:24px; height:24px; object-fit:cover;"
                                                     onerror="this.src='assets/img/users/user-16.jpg'">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" 
                                                     style="width:24px; height:24px; font-size:10px;">
                                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-medium"><?= htmlspecialchars($user['name']) ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-muted mt-1 view-task-detais-text">No users assigned</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-3">
                            <h4 class="view-task-title">Start Date:</h4>
                            <div class="d-flex align-items-center mt-1">
                                <i class="isax isax-calendar-1 me-2 text-info"></i>
                                <span class="view-task-detais-text"><?= $start_date ?></span>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-3">
                            <h4 class="view-task-title">End Date:</h4>
                            <div class="d-flex align-items-center mt-1">
                                <i class="isax isax-calendar-1 me-2 text-info"></i>
                                <span class="view-task-detais-text"><?= $end_date ?></span>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-3">
                            <h4 class="view-task-title">Estimated Hours:</h4>
                            <div class="d-flex align-items-center mt-1">
                                <i class="isax isax-clock me-2 text-warning"></i>
                                <span class="view-task-detais-text"><?= !empty($task['hour']) ? htmlspecialchars($task['hour']) . ' hours' : 'Not set' ?></span>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 mb-3">
                            <h4 class="view-task-title">Created Date:</h4>
                            <div class="d-flex align-items-center mt-1">
                                <i class="isax isax-calendar-tick me-2 text-success"></i>
                                <span class="view-task-detais-text"><?= $created_date ?></span>
                            </div>
                        </div>
                        
                        <?php if (!empty($task['task_description'])): ?>
                        <div class="col-12 mb-3">
                            <h4 class="view-task-title">Description:</h4>
                            <div class="mt-2 p-3 view-task-detais-text bg-light rounded rich-text-content">
                                <?= $task['task_description'] ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Task Attachments Card -->
            <?php if (!empty($attachments)): ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3">Task Attachments</h6>
                    <div class="row">
                        <?php foreach ($attachments as $attachment): 
                            $file_path = '../uploads/task_images/' . $attachment['image'];
                            $file_extension = pathinfo($attachment['image'], PATHINFO_EXTENSION);
                            $is_image = in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            $file_icon = $is_image ? 'isax-gallery' : 'isax-document';
                            $file_type = $is_image ? 'IMAGE' : strtoupper($file_extension);
                        ?>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="attachment-item border rounded p-3">
                                <div class="d-flex align-items-center">
                                    <div class="attachment-icon me-3">
                                        <i class="isax <?= $file_icon ?> text-primary fs-4"></i>
                                    </div>
                                    <div class="attachment-details flex-grow-1">
                                        <div class="attachment-name fw-medium text-truncate">
                                            <?= htmlspecialchars($attachment['image']) ?>
                                        </div>
                                        <div class="attachment-meta text-muted small">
                                            <div>Type: <?= $file_type ?></div>
                                            <div>Uploaded: <?= date('d-m-Y', strtotime($attachment['created_at'])) ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="attachment-actions mt-3 d-flex gap-2">
                                    <?php if ($is_image): ?>
                                        <a href="<?= $file_path ?>" target="_blank" class="btn btn-sm btn-outline-primary flex-fill">
                                            <i class="isax isax-eye me-1"></i>View
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= $file_path ?>" download class="btn btn-sm btn-outline-primary flex-fill">
                                             <i class="fa-solid fa-download me-1"></i> Download
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= $file_path ?>" download class="btn btn-sm btn-outline-info">
                                        <i class="fa-solid fa-download me-1"></i> 
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Task Timeline Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3">Task Timeline</h6>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Task Created</h6>
                                <p class="text-muted mb-1"><?= $created_date ?></p>
                                <small class="text-muted">Task was created in the system</small>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Work Started</h6>
                                <p class="text-muted mb-1"><?= $start_date ?></p>
                                <small class="text-muted">Scheduled start date for the task</small>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Expected Completion</h6>
                                <p class="text-muted mb-1"><?= $end_date ?></p>
                                <small class="text-muted">Scheduled completion date</small>
                            </div>
                        </div>
                        
                        <?php if ($status_id == 3): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Task Completed</h6>
                                <p class="text-muted mb-1"><?= date('d-m-Y') ?></p>
                                <small class="text-muted">Task marked as completed</small>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Progress and Statistics Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3">Progress & Statistics</h6>
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="p-3 border rounded">
                                <i class="isax isax-clock text-primary fs-2"></i>
                                <h5 class="mt-2 mb-1"><?= !empty($task['hour']) ? htmlspecialchars($task['hour']) : '0' ?>h</h5>
                                <small class="text-muted">Estimated Hours</small>
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-center mb-3">
                            <div class="p-3 border rounded">
                                <i class="isax isax-calendar text-info fs-2"></i>
                                <h5 class="mt-2 mb-1">
                                    <?php 
                                    if (!empty($task['start_date']) && !empty($task['end_date'])) {
                                        $start = new DateTime($task['start_date']);
                                        $end = new DateTime($task['end_date']);
                                        $interval = $start->diff($end);
                                        echo $interval->days . ' days';
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </h5>
                                <small class="text-muted">Duration</small>
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-center mb-3">
                            <div class="p-3 border rounded">
                                <i class="isax isax-tick-circle text-success fs-2"></i>
                                <h5 class="mt-2 mb-1">
                                    <?php
                                    $progress = 0;
                                    switch($status_id) {
                                        case 1: $progress = 10; break; // Pending
                                        case 2: $progress = 50; break; // In Progress
                                        case 3: $progress = 100; break; // Completed
                                        case 4: $progress = 25; break; // On Hold
                                        case 5: $progress = 0; break; // Cancelled
                                    }
                                    echo $progress . '%';
                                    ?>
                                </h5>
                                <small class="text-muted">Progress</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Task Progress</span>
                            <span><?= $progress ?>%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%; background-color: <?= $status_color ?>;" 
                                 aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3">Quick Actions</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="edit-task.php?id=<?= $task_id ?>" class="btn btn-outline-primary">
                            <i class="isax isax-edit me-1"></i>Edit Task
                        </a>
                        <a href="project-details.php?id=<?= $task['project_id'] ?>" class="btn btn-outline-info">
                            <i class="isax isax-building-3 me-1"></i>View Project
                        </a>
                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                            <i class="isax isax-refresh me-1"></i>Change Status
                        </button>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteTaskModal">
                            <i class="isax isax-trash me-1"></i>Delete Task
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php include 'layouts/footer.php'; ?>
</div>

<!-- Change Status Modal -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="process/action_update_task_status.php" method="POST">
                <input type="hidden" name="task_id" value="<?= $task_id ?>">
                <div class="modal-header">
                    <h6 class="modal-title">Change Task Status</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select New Status</label>
                        <select class="form-select" name="status_id" required>
                            <?php foreach ($statusOptions as $id => $status): ?>
                                <option value="<?= $id ?>" <?= $id == $status_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($status['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Task Modal -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-m">
        <div class="modal-content">
            <form method="POST" action="process/action_delete_task.php">
                <input type="hidden" name="id" value="<?= $task_id ?>">
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <img src="assets/img/icons/delete.svg" alt="img">
                    </div>
                    <h6 class="mb-1">Delete Task</h6>
                    <p class="mb-3">Are you sure you want to delete "<?= htmlspecialchars($task['task_name']) ?>"?</p>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-white me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Yes, Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Zoom Modal for Description Images -->
<div class="image-zoom-modal" id="descImageZoomModal">
    <div class="image-zoom-content">
        <img id="descZoomedImage" src="" alt="Zoomed Image">
        <button class="image-zoom-close" id="descImageZoomClose">&times;</button>
        <div class="image-zoom-nav">
            <button class="image-zoom-prev" id="descImageZoomPrev">&#10094;</button>
            <button class="image-zoom-next" id="descImageZoomNext">&#10095;</button>
        </div>
        <div class="image-counter" id="descImageCounter"></div>
    </div>
</div>

<?php include 'layouts/vendor-scripts.php'; ?>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
.timeline-content {
    padding-bottom: 10px;
}
.timeline-item:not(:last-child) .timeline-content {
    border-left: 2px solid #e9ecef;
    padding-left: 20px;
    margin-left: -20px;
}

/* Attachment Styles */
.attachment-item {
    transition: all 0.3s ease;
    height: 100%;
}
.attachment-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.attachment-icon {
    flex-shrink: 0;
}
.attachment-details {
    min-width: 0;
}
.attachment-name {
    font-size: 14px;
    margin-bottom: 4px;
}
.attachment-meta {
    font-size: 12px;
}
.attachment-actions .btn {
    font-size: 12px;
    padding: 4px 8px;
}

/* Rich Text Content Styles - Preserving existing design */
.rich-text-content {
    font-family: inherit;
    line-height: 1.6;
    color: #495057;
}

.rich-text-content p {
    margin-bottom: 1rem;
}

.rich-text-content h1,
.rich-text-content h2,
.rich-text-content h3,
.rich-text-content h4,
.rich-text-content h5,
.rich-text-content h6 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
    color: #343a40;
}

.rich-text-content h1 { font-size: 2rem; }
.rich-text-content h2 { font-size: 1.75rem; }
.rich-text-content h3 { font-size: 1.5rem; }
.rich-text-content h4 { font-size: 1.25rem; }
.rich-text-content h5 { font-size: 1.1rem; }
.rich-text-content h6 { font-size: 1rem; }

.rich-text-content ul,
.rich-text-content ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.rich-text-content li {
    margin-bottom: 0.5rem;
}

.rich-text-content blockquote {
    border-left: 4px solid #0d6efd;
    padding-left: 1rem;
    margin-left: 0;
    margin-right: 0;
    margin-bottom: 1rem;
    font-style: italic;
    color: #6c757d;
}

.rich-text-content code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
    color: #e83e8c;
}

.rich-text-content pre {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
    overflow-x: auto;
    margin-bottom: 1rem;
}

.rich-text-content pre code {
    background: none;
    padding: 0;
    color: inherit;
}

.rich-text-content table {
    width: 100%;
    margin-bottom: 1rem;
    border-collapse: collapse;
}

.rich-text-content table th,
.rich-text-content table td {
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    text-align: left;
}

.rich-text-content table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.rich-text-content img {
    max-width: 400px !important;
    height: auto !important;
    display: block;
    margin: 10px 0;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    cursor: zoom-in;
    transition: transform 0.2s ease;
}

.rich-text-content img:hover {
    transform: scale(1.02);
}

.rich-text-content a {
    color: #0d6efd;
    text-decoration: none;
}

.rich-text-content a:hover {
    text-decoration: underline;
}

.rich-text-content strong {
    font-weight: 600;
}

.rich-text-content em {
    font-style: italic;
}

.rich-text-content u {
    text-decoration: underline;
}

.rich-text-content s {
    text-decoration: line-through;
}

/* Ensure the content fits within your existing design */
.rich-text-content {
    max-height: 500px;
    overflow-y: auto;
}

/* Scrollbar styling for the description box */
.rich-text-content::-webkit-scrollbar {
    width: 6px;
}

.rich-text-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.rich-text-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.rich-text-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Image Zoom Modal Styles */
.image-zoom-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.image-zoom-content {
    max-width: 90%;
    max-height: 90%;
    position: relative;
}

.image-zoom-content img {
    max-width: 100%;
    max-height: 100%;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.image-zoom-close {
    position: absolute;
    top: -40px;
    right: 0;
    background: none;
    border: none;
    color: white;
    font-size: 30px;
    cursor: pointer;
    padding: 5px;
}

.image-zoom-close:hover {
    color: #ff6b6b;
}

.image-zoom-nav {
    position: absolute;
    top: 50%;
    width: 100%;
    display: flex;
    justify-content: space-between;
    transform: translateY(-50%);
}

.image-zoom-prev,
.image-zoom-next {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 24px;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 50%;
    margin: 0 20px;
    transition: background 0.3s ease;
}

.image-zoom-prev:hover,
.image-zoom-next:hover {
    background: rgba(255, 255, 255, 0.4);
}

.image-counter {
    position: absolute;
    bottom: -40px;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    font-size: 16px;
    background: rgba(0, 0, 0, 0.7);
    padding: 5px 15px;
    border-radius: 20px;
}
</style>

<script>
// Image zoom functionality for description images
let descImages = [];
let currentDescImageIndex = 0;

function initDescImageZoom() {
    // Get all images from rich text content
    descImages = Array.from(document.querySelectorAll('.rich-text-content img')).map(img => img.src);
    
    // Add click event listeners to description images
    document.querySelectorAll('.rich-text-content img').forEach((img, index) => {
        img.addEventListener('click', function() {
            openDescImageZoom(this.src);
        });
    });
    
    // Initialize modal events
    const modal = document.getElementById('descImageZoomModal');
    const closeBtn = document.getElementById('descImageZoomClose');
    const prevBtn = document.getElementById('descImageZoomPrev');
    const nextBtn = document.getElementById('descImageZoomNext');

    closeBtn.addEventListener('click', closeDescImageZoom);
    prevBtn.addEventListener('click', () => navigateDescImage(-1));
    nextBtn.addEventListener('click', () => navigateDescImage(1));

    // Close modal when clicking outside the image
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeDescImageZoom();
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (modal.style.display === 'flex') {
            if (e.key === 'Escape') closeDescImageZoom();
            if (e.key === 'ArrowLeft') navigateDescImage(-1);
            if (e.key === 'ArrowRight') navigateDescImage(1);
        }
    });
}

function openDescImageZoom(src) {
    currentDescImageIndex = descImages.indexOf(src);
    if (currentDescImageIndex === -1) return;
    
    const modal = document.getElementById('descImageZoomModal');
    const zoomedImage = document.getElementById('descZoomedImage');
    const imageCounter = document.getElementById('descImageCounter');
    
    zoomedImage.src = src;
    updateDescImageCounter();
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeDescImageZoom() {
    const modal = document.getElementById('descImageZoomModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function navigateDescImage(direction) {
    currentDescImageIndex += direction;
    
    if (currentDescImageIndex < 0) {
        currentDescImageIndex = descImages.length - 1;
    } else if (currentDescImageIndex >= descImages.length) {
        currentDescImageIndex = 0;
    }
    
    const zoomedImage = document.getElementById('descZoomedImage');
    zoomedImage.src = descImages[currentDescImageIndex];
    updateDescImageCounter();
}

function updateDescImageCounter() {
    const imageCounter = document.getElementById('descImageCounter');
    imageCounter.textContent = `${currentDescImageIndex + 1} / ${descImages.length}`;
}

// Initialize description image zoom when page loads
document.addEventListener('DOMContentLoaded', function() {
    initDescImageZoom();
});
</script>

</body>
</html>