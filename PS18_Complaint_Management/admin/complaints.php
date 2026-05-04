<?php
$page_title = 'Admin - All Complaints';
require_once '../includes/functions.php';
requireAdmin();

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $complaint_id = (int)$_POST['complaint_id'];
    $new_status = sanitize($_POST['status']);
    $resolution = sanitize($_POST['resolution'] ?? '');
    
    $query = "UPDATE complaints SET status = ?, resolution = ?, resolved_at = ? WHERE id = ?";
    $resolved_at = ($new_status == 'resolved') ? date('Y-m-d H:i:s') : null;
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([$new_status, $resolution, $resolved_at, $complaint_id])) {
        // Add update record
        $query = "INSERT INTO complaint_updates (complaint_id, user_id, update_type, message, old_value, new_value) 
                  VALUES (?, ?, 'status_change', ?, ?, ?)";
        $stmt = $db->prepare($query);
        $message = "Status changed to " . ucfirst(str_replace('_', ' ', $new_status));
        if (!empty($resolution)) {
            $message .= ". Resolution: " . $resolution;
        }
        $stmt->execute([$complaint_id, $_SESSION['user_id'], $message, '', $new_status]);
        
        header("Location: complaints.php?updated=1");
        exit();
    }
}

// Pagination and filters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 15;
$offset = ($page - 1) * $per_page;

$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$org_filter = isset($_GET['organization']) ? (int)$_GET['organization'] : 0;
$priority_filter = isset($_GET['priority']) ? sanitize($_GET['priority']) : '';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

// Build query
$where_conditions = ["1=1"];
$params = [];

if (!empty($status_filter)) {
    $where_conditions[] = "c.status = ?";
    $params[] = $status_filter;
}

if (!empty($org_filter)) {
    $where_conditions[] = "c.organization_id = ?";
    $params[] = $org_filter;
}

if (!empty($priority_filter)) {
    $where_conditions[] = "c.priority = ?";
    $params[] = $priority_filter;
}

if (!empty($search)) {
    $where_conditions[] = "(c.complaint_number LIKE ? OR c.subject LIKE ? OR u.full_name LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

$where_clause = implode(' AND ', $where_conditions);

// Get total count
$count_query = "SELECT COUNT(*) as total FROM complaints c 
                LEFT JOIN users u ON c.user_id = u.id 
                WHERE $where_clause";
$stmt = $db->prepare($count_query);
$stmt->execute($params);
$total_complaints = $stmt->fetch()['total'];
$total_pages = ceil($total_complaints / $per_page);

// Get complaints
$query = "SELECT c.*, o.name as organization_name, cat.name as category_name, u.full_name as user_name
          FROM complaints c 
          LEFT JOIN organizations o ON c.organization_id = o.id 
          LEFT JOIN categories cat ON c.category_id = cat.id 
          LEFT JOIN users u ON c.user_id = u.id
          WHERE $where_clause 
          ORDER BY c.created_at DESC 
          LIMIT $per_page OFFSET $offset";
$stmt = $db->prepare($query);
$stmt->execute($params);
$complaints = $stmt->fetchAll();

// Get organizations for filter
$organizations = getOrganizations($db);

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-clipboard-list me-2"></i>
        All Complaints
    </h2>
    <div class="btn-group">
        <a href="../dashboard.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Dashboard
        </a>
        <button type="button" class="btn btn-outline-primary" onclick="window.print()">
            <i class="fas fa-print me-2"></i>
            Print
        </button>
    </div>
</div>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>
        Complaint updated successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Search and Filter -->
<div class="search-box">
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <label for="search" class="form-label">Search</label>
            <input type="text" class="form-control" id="search" name="search" 
                   value="<?php echo htmlspecialchars($search); ?>" 
                   placeholder="Search complaints...">
        </div>
        <div class="col-md-2">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="">All Status</option>
                <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="in_progress" <?php echo $status_filter == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                <option value="resolved" <?php echo $status_filter == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                <option value="closed" <?php echo $status_filter == 'closed' ? 'selected' : ''; ?>>Closed</option>
                <option value="rejected" <?php echo $status_filter == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="organization" class="form-label">Organization</label>
            <select class="form-select" id="organization" name="organization">
                <option value="">All Organizations</option>
                <?php foreach ($organizations as $org): ?>
                    <option value="<?php echo $org['id']; ?>" <?php echo $org_filter == $org['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($org['code']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label for="priority" class="form-label">Priority</label>
            <select class="form-select" id="priority" name="priority">
                <option value="">All Priorities</option>
                <option value="low" <?php echo $priority_filter == 'low' ? 'selected' : ''; ?>>Low</option>
                <option value="medium" <?php echo $priority_filter == 'medium' ? 'selected' : ''; ?>>Medium</option>
                <option value="high" <?php echo $priority_filter == 'high' ? 'selected' : ''; ?>>High</option>
                <option value="urgent" <?php echo $priority_filter == 'urgent' ? 'selected' : ''; ?>>Urgent</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">
                <i class="fas fa-search me-1"></i>
                Search
            </button>
            <a href="complaints.php" class="btn btn-outline-secondary">
                <i class="fas fa-times me-1"></i>
                Clear
            </a>
        </div>
    </form>
</div>

<!-- Results -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            Complaints (<?php echo $total_complaints; ?> total)
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($complaints)): ?>
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No complaints found</h5>
                <p class="text-muted">Try adjusting your search criteria.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Complaint #</th>
                            <th>User</th>
                            <th>Subject</th>
                            <th>Organization</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($complaints as $complaint): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($complaint['complaint_number']); ?></strong>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars($complaint['user_name']); ?></small>
                                </td>
                                <td>
                                    <div class="fw-bold">
                                        <?php echo htmlspecialchars(substr($complaint['subject'], 0, 30)); ?>
                                        <?php if (strlen($complaint['subject']) > 30) echo '...'; ?>
                                    </div>
                                    <small class="text-muted">
                                        <?php echo htmlspecialchars(substr($complaint['description'], 0, 50)); ?>
                                        <?php if (strlen($complaint['description']) > 50) echo '...'; ?>
                                    </small>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars($complaint['organization_name']); ?></small>
                                </td>
                                <td>
                                    <span class="badge <?php echo getStatusBadgeClass($complaint['status']); ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo getPriorityBadgeClass($complaint['priority']); ?>">
                                        <?php echo ucfirst($complaint['priority']); ?>
                                    </span>
                                </td>
                                <td>
                                    <small><?php echo formatDate($complaint['created_at']); ?></small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="../view_complaint.php?id=<?php echo $complaint['id']; ?>" 
                                           class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#updateModal<?php echo $complaint['id']; ?>"
                                                title="Update Status">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Update Status Modal -->
                            <div class="modal fade" id="updateModal<?php echo $complaint['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Complaint Status</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Complaint #</label>
                                                    <input type="text" class="form-control" 
                                                           value="<?php echo htmlspecialchars($complaint['complaint_number']); ?>" readonly>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="status<?php echo $complaint['id']; ?>" class="form-label">Status</label>
                                                    <select class="form-select" name="status" id="status<?php echo $complaint['id']; ?>" required>
                                                        <option value="pending" <?php echo $complaint['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="in_progress" <?php echo $complaint['status'] == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                                        <option value="resolved" <?php echo $complaint['status'] == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                                        <option value="closed" <?php echo $complaint['status'] == 'closed' ? 'selected' : ''; ?>>Closed</option>
                                                        <option value="rejected" <?php echo $complaint['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="resolution<?php echo $complaint['id']; ?>" class="form-label">Resolution/Comments</label>
                                                    <textarea class="form-control" name="resolution" 
                                                              id="resolution<?php echo $complaint['id']; ?>" rows="3"
                                                              placeholder="Add resolution details or comments..."><?php echo htmlspecialchars($complaint['resolution'] ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Complaints pagination">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&organization=<?php echo $org_filter; ?>&priority=<?php echo urlencode($priority_filter); ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page-2); $i <= min($total_pages, $page+2); $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&organization=<?php echo $org_filter; ?>&priority=<?php echo urlencode($priority_filter); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&organization=<?php echo $org_filter; ?>&priority=<?php echo urlencode($priority_filter); ?>">
                                    Next
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>