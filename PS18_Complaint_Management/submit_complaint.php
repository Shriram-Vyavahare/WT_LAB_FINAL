<?php
$page_title = 'Submit Complaint';
require_once 'includes/functions.php';
requireLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $organization_id = (int)$_POST['organization_id'];
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $subject = sanitize($_POST['subject']);
    $description = sanitize($_POST['description']);
    $priority = sanitize($_POST['priority']);
    
    if (empty($organization_id) || empty($subject) || empty($description)) {
        $error = 'Please fill in all required fields.';
    } else {
        $attachment = null;
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
            $upload_result = uploadFile($_FILES['attachment']);
            if ($upload_result['success']) {
                $attachment = $upload_result['filename'];
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (empty($error)) {
            $complaint_number = generateComplaintNumber();
            
            $query = "INSERT INTO complaints (complaint_number, user_id, organization_id, category_id, subject, description, priority, attachment) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            
            if ($stmt->execute([$complaint_number, $_SESSION['user_id'], $organization_id, $category_id, $subject, $description, $priority, $attachment])) {
                $success = "Complaint submitted successfully! Your complaint number is: <strong>$complaint_number</strong>";
                $_POST = [];
            } else {
                $error = 'Failed to submit complaint. Please try again.';
            }
        }
    }
}

$organizations = getOrganizations($db);
require_once 'includes/header.php';
?>

<h2>Submit New Complaint</h2>

<?php if ($error): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success">
        <?php echo $success; ?>
        <br><br>
        <a href="my_complaints.php">View My Complaints</a> | 
        <a href="dashboard.php">Go to Dashboard</a>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="organization_id">Organization *:</label>
        <select id="organization_id" name="organization_id" required>
            <option value="">Select Organization</option>
            <?php foreach ($organizations as $org): ?>
                <option value="<?php echo $org['id']; ?>" 
                        <?php echo (isset($_POST['organization_id']) && $_POST['organization_id'] == $org['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($org['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id">
            <option value="">Select Category (Optional)</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="subject">Subject *:</label>
        <input type="text" id="subject" name="subject" 
               value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>" 
               placeholder="Brief description of your complaint" required>
    </div>
    
    <div class="form-group">
        <label for="description">Description *:</label>
        <textarea id="description" name="description" rows="5" 
                  placeholder="Provide detailed information about your complaint..." required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="priority">Priority:</label>
        <select id="priority" name="priority">
            <option value="low" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'low') ? 'selected' : ''; ?>>Low</option>
            <option value="medium" <?php echo (!isset($_POST['priority']) || $_POST['priority'] == 'medium') ? 'selected' : ''; ?>>Medium</option>
            <option value="high" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'high') ? 'selected' : ''; ?>>High</option>
            <option value="urgent" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'urgent') ? 'selected' : ''; ?>>Urgent</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="attachment">Attachment (Optional):</label>
        <input type="file" id="attachment" name="attachment" 
               accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
        <small>Supported: JPG, PNG, PDF, DOC, DOCX (Max: 5MB)</small>
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn">Submit Complaint</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<script>
document.getElementById('organization_id').addEventListener('change', function() {
    const orgId = this.value;
    const categorySelect = document.getElementById('category_id');
    
    categorySelect.innerHTML = '<option value="">Select Category (Optional)</option>';
    
    if (orgId) {
        fetch(`ajax/get_categories.php?org_id=${orgId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    categorySelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>