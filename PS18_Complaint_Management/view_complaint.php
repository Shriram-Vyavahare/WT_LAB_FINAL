<?php
$page_title = 'View Complaint';
require_once 'includes/functions.php';
requireLogin();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: my_complaints.php');
    exit();
}

$complaint_id = (int)$_GET['id'];

// Get complaint details
$query = "SELECT c.*, o.name as organization_name, o.contact_email, o.contact_phone, 
                 cat.name as category_name, u.full_name as user_name,
                 assigned.full_name as assigned_to_name
          FROM complaints c 
          LEFT JOIN organizations o ON c.organization_id = o.id 
          LEFT JOIN categories cat ON c.category_id = cat.id 
          LEFT JOIN users u ON c.user_id = u.id
          LEFT JOIN users assigned ON c.assigned_to = assigned.id
          WHERE c.id = ?";

// Check if user can view this complaint
if (!isAdmin()) {
    $query .= " AND c.user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$complaint_id, $_SESSION['user_id']]);
} else {
    $stmt = $db->prepare($query);
    $stmt->execute([$complaint_id]);
}

$complaint = $stmt->fetch();

if (!$complaint) {
    header('Location: my_complaints.php');
    exit();
}

// Get complaint updates
$query = "SELECT cu.*, u.full_name as user_name 
          FROM complaint_updates cu 
          LEFT JOIN users u ON cu.user_id = u.id 
          WHERE cu.complaint_id = ? 
          ORDER BY cu.created_at ASC";
$stmt = $db->prepare($query);
$stmt->execute([$complaint_id]);
$updates = $stmt->fetchAll();

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_comment'])) {
    $comment = sanitize($_POST['comment']);
    
    if (!empty($comment)) {
        $query = "INSERT INTO complaint_updates (complaint_id, user_id, update_type, message) VALUES (?, ?, 'comment', ?)";
        $stmt = $db->prepare($query);
        if ($stmt->execute([$complaint_id, $_SESSION['user_id'], $comment])) {
            header("Location: view_complaint.php?id=$complaint_id");
            exit();
        }
    }
}

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-eye me-2"></i>
        Complaint Details
    </h2>
    <div class="btn-group">
        <a href="<?php echo isAdmin() ? 'admin/complaints.php' : 'my_complaints.php'; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to List
        </a>
        <button type="button" class="btn btn-outline-primary" onclick="window.print()">
            <i class="fas fa-print me-2"></i>
            Print
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Complaint Details -->
        <div class="card complaint-card priority-<?php echo $complaint['priority']; ?>">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <?php echo htmlspecialchars($complaint['complaint_number']); ?>
                    </h5>
                    <div>
                        <span class="badge <?php echo getStatusBadgeClass($complaint['status']); ?> me-2">
                            <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                        </span>
                        <span class="badge <?php echo getPriorityBadgeClass($complaint['priority']); ?>">
                            <?php echo ucfirst($complaint['priority']); ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <h6 class="card-title"><?php echo htmlspecialchars($complaint['subject']); ?></h6>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($complaint['description'])); ?></p>
                
                <?php if ($complaint['attachment']): ?>
                    <div class="mt-3">
                        <h6>Attachment:</h6>
                        <a href="uploads/<?php echo htmlspecialchars($complaint['attachment']); ?>" 
                           class="attachment-link" target="_blank">
                            <i class="fas fa-paperclip me-2"></i>
                            <?php echo htmlspecialchars($complaint['attachment']); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if ($complaint['resolution'] && $complaint['status'] == 'resolved'): ?>
                    <div class="mt-3">
                        <h6>Resolution:</h6>
                        <div class="alert alert-success">
                            <?php echo nl2br(htmlspecialchars($complaint['resolution'])); ?>
                        </div>
                        <small class="text-muted">
                            Resolved on: <?php echo formatDate($complaint['resolved_at']); ?>
                        </small>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <small class="text-muted">
                    Submitted on <?php echo formatDate($complaint['created_at']); ?>
                    <?php if ($complaint['updated_at'] != $complaint['created_at']): ?>
                        • Last updated on <?php echo formatDate($complaint['updated_at']); ?>
                    <?php endif; ?>
                </small>
            </div>
        </div>
        
        <!-- Updates Timeline -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Updates & Comments
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($updates)): ?>
                    <p class="text-muted">No updates yet.</p>
                <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($updates as $update): ?>
                            <div class="timeline-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($update['user_name']); ?></h6>
                                        <p class="mb-1"><?php echo nl2br(htmlspecialchars($update['message'])); ?></p>
                                        <small class="text-muted">
                                            <?php echo formatDate($update['created_at']); ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-secondary">
                                        <?php echo ucfirst(str_replace('_', ' ', $update['update_type'])); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Add Comment Form -->
        <?php if (in_array($complaint['status'], ['pending', 'in_progress'])): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-comment me-2"></i>
                        Add Comment
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <textarea class="form-control" name="comment" rows="3" 
                                      placeholder="Add your comment or additional information..." required></textarea>
                        </div>
                        <button type="submit" name="add_comment" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>
                            Add Comment
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-lg-4">
        <!-- Complaint Info -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Complaint Information
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><strong>Complaint #:</strong></td>
                        <td><?php echo htmlspecialchars($complaint['complaint_number']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge <?php echo getStatusBadgeClass($complaint['status']); ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Priority:</strong></td>
                        <td>
                            <span class="badge <?php echo getPriorityBadgeClass($complaint['priority']); ?>">
                                <?php echo ucfirst($complaint['priority']); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Organization:</strong></td>
                        <td><?php echo htmlspecialchars($complaint['organization_name']); ?></td>
                    </tr>
                    <?php if ($complaint['category_name']): ?>
                        <tr>
                            <td><strong>Category:</strong></td>
                            <td><?php echo htmlspecialchars($complaint['category_name']); ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>Submitted by:</strong></td>
                        <td><?php echo htmlspecialchars($complaint['user_name']); ?></td>
                    </tr>
                    <?php if ($complaint['assigned_to_name']): ?>
                        <tr>
                            <td><strong>Assigned to:</strong></td>
                            <td><?php echo htmlspecialchars($complaint['assigned_to_name']); ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td><?php echo formatDate($complaint['created_at']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Last Updated:</strong></td>
                        <td><?php echo formatDate($complaint['updated_at']); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Organization Contact -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-building me-2"></i>
                    Organization Contact
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong><?php echo htmlspecialchars($complaint['organization_name']); ?></strong>
                </p>
                <?php if ($complaint['contact_email']): ?>
                    <p class="mb-1">
                        <i class="fas fa-envelope me-2"></i>
                        <a href="mailto:<?php echo htmlspecialchars($complaint['contact_email']); ?>">
                            <?php echo htmlspecialchars($complaint['contact_email']); ?>
                        </a>
                    </p>
                <?php endif; ?>
                <?php if ($complaint['contact_phone']): ?>
                    <p class="mb-0">
                        <i class="fas fa-phone me-2"></i>
                        <a href="tel:<?php echo htmlspecialchars($complaint['contact_phone']); ?>">
                            <?php echo htmlspecialchars($complaint['contact_phone']); ?>
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Status Guide -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-question-circle me-2"></i>
                    Status Guide
                </h6>
            </div>
            <div class="card-body">
                <small>
                    <div class="mb-2">
                        <span class="badge badge-warning">Pending</span>
                        Complaint received and under review
                    </div>
                    <div class="mb-2">
                        <span class="badge badge-info">In Progress</span>
                        Complaint is being actively worked on
                    </div>
                    <div class="mb-2">
                        <span class="badge badge-success">Resolved</span>
                        Issue has been resolved
                    </div>
                    <div class="mb-2">
                        <span class="badge badge-secondary">Closed</span>
                        Complaint has been closed
                    </div>
                    <div class="mb-0">
                        <span class="badge badge-danger">Rejected</span>
                        Complaint was rejected
                    </div>
                </small>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>