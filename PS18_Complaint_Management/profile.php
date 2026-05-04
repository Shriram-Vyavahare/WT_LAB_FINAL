<?php
$page_title = 'Profile';
require_once 'includes/functions.php';
requireLogin();

$error = '';
$success = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($full_name) || empty($email)) {
        $error = 'Full name and email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        // Check if email is already taken by another user
        $query = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email, $_SESSION['user_id']]);
        
        if ($stmt->fetch()) {
            $error = 'Email is already taken by another user.';
        } else {
            // Update profile
            $query = "UPDATE users SET full_name = ?, email = ?, phone = ? WHERE id = ?";
            $stmt = $db->prepare($query);
            
            if ($stmt->execute([$full_name, $email, $phone, $_SESSION['user_id']])) {
                $_SESSION['full_name'] = $full_name;
                $_SESSION['email'] = $email;
                
                // Handle password change
                if (!empty($new_password)) {
                    if ($new_password !== $confirm_password) {
                        $error = 'New passwords do not match.';
                    } elseif (strlen($new_password) < 3) {
                        $error = 'New password must be at least 3 characters long.';
                    } else {
                        // Update password (plain text)
                        $query = "UPDATE users SET password = ? WHERE id = ?";
                        $stmt = $db->prepare($query);
                        
                        if ($stmt->execute([$new_password, $_SESSION['user_id']])) {
                            $success = 'Profile and password updated successfully!';
                        } else {
                            $error = 'Failed to update password.';
                        }
                    }
                } else {
                    $success = 'Profile updated successfully!';
                }
            } else {
                $error = 'Failed to update profile.';
            }
        }
    }
}

// Get current user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

require_once 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    My Profile
                </h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            <div class="invalid-feedback">
                                Please provide your full name.
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" 
                                   value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                            <div class="form-text">Username cannot be changed.</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            <div class="invalid-feedback">
                                Please provide a valid email address.
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" class="form-control" id="role" 
                                   value="<?php echo ucfirst($user['role']); ?>" readonly>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="created_at" class="form-label">Member Since</label>
                            <input type="text" class="form-control" id="created_at" 
                                   value="<?php echo formatDate($user['created_at']); ?>" readonly>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h5 class="mb-3">Change Password</h5>
                    <p class="text-muted small">Leave password fields empty if you don't want to change your password.</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" minlength="3">
                            <div class="invalid-feedback">
                                Password must be at least 3 characters long.
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="3">
                            <div class="invalid-feedback">
                                Please confirm your new password.
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Account Statistics -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Account Statistics
                </h5>
            </div>
            <div class="card-body">
                <?php
                // Get user statistics
                $user_id = $_SESSION['user_id'];
                
                $query = "SELECT 
                            COUNT(*) as total_complaints,
                            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_complaints,
                            SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved_complaints,
                            SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_complaints
                          FROM complaints WHERE user_id = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([$user_id]);
                $stats = $stmt->fetch();
                ?>
                
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h4><?php echo $stats['total_complaints']; ?></h4>
                                <p class="mb-0">Total Complaints</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h4><?php echo $stats['pending_complaints']; ?></h4>
                                <p class="mb-0">Pending</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h4><?php echo $stats['in_progress_complaints']; ?></h4>
                                <p class="mb-0">In Progress</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h4><?php echo $stats['resolved_complaints']; ?></h4>
                                <p class="mb-0">Resolved</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>