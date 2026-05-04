<?php
$page_title = 'Dashboard';
require_once 'includes/functions.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Get user statistics
$query = "SELECT COUNT(*) as total FROM complaints WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$user_id]);
$total_complaints = $stmt->fetch()['total'];

$query = "SELECT COUNT(*) as pending FROM complaints WHERE user_id = ? AND status = 'pending'";
$stmt = $db->prepare($query);
$stmt->execute([$user_id]);
$pending_complaints = $stmt->fetch()['pending'];

// Get recent complaints
$query = "SELECT c.*, o.name as organization_name 
          FROM complaints c 
          LEFT JOIN organizations o ON c.organization_id = o.id 
          WHERE c.user_id = ? 
          ORDER BY c.created_at DESC 
          LIMIT 5";
$stmt = $db->prepare($query);
$stmt->execute([$user_id]);
$recent_complaints = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<h2>Dashboard</h2>

<p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>!</p>

<div style="background: #f0f8ff; padding: 15px; margin: 20px 0; border: 1px solid #b3d9ff;">
    <h3>Your Statistics</h3>
    <p><strong>Total Complaints:</strong> <?php echo $total_complaints; ?></p>
    <p><strong>Pending Complaints:</strong> <?php echo $pending_complaints; ?></p>
</div>

<div style="background: #f0fff0; padding: 15px; margin: 20px 0; border: 1px solid #90ee90;">
    <h3>Quick Actions</h3>
    <p>
        <a href="submit_complaint.php">Submit New Complaint</a> | 
        <a href="my_complaints.php">View All My Complaints</a>
    </p>
</div>

<h3>Recent Complaints</h3>

<?php if (empty($recent_complaints)): ?>
    <p>You haven't submitted any complaints yet. <a href="submit_complaint.php">Submit your first complaint</a>.</p>
<?php else: ?>
    <table class="table">
        <tr>
            <th>Complaint #</th>
            <th>Subject</th>
            <th>Organization</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php foreach ($recent_complaints as $complaint): ?>
            <tr>
                <td><?php echo htmlspecialchars($complaint['complaint_number']); ?></td>
                <td><?php echo htmlspecialchars(substr($complaint['subject'], 0, 40)); ?><?php if (strlen($complaint['subject']) > 40) echo '...'; ?></td>
                <td><?php echo htmlspecialchars($complaint['organization_name']); ?></td>
                <td class="status-<?php echo $complaint['status']; ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                </td>
                <td><?php echo date('M d, Y', strtotime($complaint['created_at'])); ?></td>
                <td><a href="view_complaint.php?id=<?php echo $complaint['id']; ?>">View</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>