<?php
$page_title = 'My Complaints';
require_once 'includes/functions.php';
requireLogin();

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : '';

$where_conditions = ["c.user_id = ?"];
$params = [$_SESSION['user_id']];

if (!empty($status_filter)) {
    $where_conditions[] = "c.status = ?";
    $params[] = $status_filter;
}

if (!empty($search)) {
    $where_conditions[] = "(c.complaint_number LIKE ? OR c.subject LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
}

$where_clause = implode(' AND ', $where_conditions);

$query = "SELECT c.*, o.name as organization_name 
          FROM complaints c 
          LEFT JOIN organizations o ON c.organization_id = o.id 
          WHERE $where_clause 
          ORDER BY c.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute($params);
$complaints = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<h2>My Complaints</h2>

<form method="GET" style="background: #f9f9f9; padding: 15px; margin-bottom: 20px; border: 1px solid #ddd;">
    <div style="display: inline-block; margin-right: 15px;">
        <label for="search">Search:</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" 
               placeholder="Search complaints..." style="width: 200px;">
    </div>
    
    <div style="display: inline-block; margin-right: 15px;">
        <label for="status">Status:</label>
        <select id="status" name="status" style="width: 120px;">
            <option value="">All Status</option>
            <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="in_progress" <?php echo $status_filter == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
            <option value="resolved" <?php echo $status_filter == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
            <option value="closed" <?php echo $status_filter == 'closed' ? 'selected' : ''; ?>>Closed</option>
            <option value="rejected" <?php echo $status_filter == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
        </select>
    </div>
    
    <button type="submit" class="btn">Search</button>
    <a href="my_complaints.php" class="btn btn-secondary">Clear</a>
</form>

<p><strong>Total Complaints:</strong> <?php echo count($complaints); ?></p>

<?php if (empty($complaints)): ?>
    <p>No complaints found. <a href="submit_complaint.php">Submit your first complaint</a>.</p>
<?php else: ?>
    <table class="table">
        <tr>
            <th>Complaint #</th>
            <th>Subject</th>
            <th>Organization</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php foreach ($complaints as $complaint): ?>
            <tr>
                <td><?php echo htmlspecialchars($complaint['complaint_number']); ?></td>
                <td>
                    <?php echo htmlspecialchars(substr($complaint['subject'], 0, 40)); ?>
                    <?php if (strlen($complaint['subject']) > 40) echo '...'; ?>
                </td>
                <td><?php echo htmlspecialchars($complaint['organization_name']); ?></td>
                <td class="status-<?php echo $complaint['status']; ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                </td>
                <td class="priority-<?php echo $complaint['priority']; ?>">
                    <?php echo ucfirst($complaint['priority']); ?>
                </td>
                <td><?php echo date('M d, Y', strtotime($complaint['created_at'])); ?></td>
                <td>
                    <a href="view_complaint.php?id=<?php echo $complaint['id']; ?>">View</a>
                    <?php if ($complaint['attachment']): ?>
                        | <a href="uploads/<?php echo htmlspecialchars($complaint['attachment']); ?>" target="_blank">File</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>