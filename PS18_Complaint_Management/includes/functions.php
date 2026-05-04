<?php
session_start();
require_once 'config/database.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Authentication functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: dashboard.php');
        exit();
    }
}

// Utility functions
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateComplaintNumber() {
    return 'CMP' . date('Y') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
}

function getStatusBadgeClass($status) {
    switch($status) {
        case 'pending': return 'badge-warning';
        case 'in_progress': return 'badge-info';
        case 'resolved': return 'badge-success';
        case 'closed': return 'badge-secondary';
        case 'rejected': return 'badge-danger';
        default: return 'badge-secondary';
    }
}

function getPriorityBadgeClass($priority) {
    switch($priority) {
        case 'low': return 'badge-success';
        case 'medium': return 'badge-warning';
        case 'high': return 'badge-danger';
        case 'urgent': return 'badge-dark';
        default: return 'badge-secondary';
    }
}

function formatDate($date) {
    return date('M d, Y h:i A', strtotime($date));
}

// Get organizations
function getOrganizations($db) {
    $query = "SELECT * FROM organizations WHERE status = 'active' ORDER BY name";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get categories by organization
function getCategoriesByOrg($db, $org_id) {
    $query = "SELECT * FROM categories WHERE organization_id = ? ORDER BY name";
    $stmt = $db->prepare($query);
    $stmt->execute([$org_id]);
    return $stmt->fetchAll();
}

// File upload function
function uploadFile($file, $upload_dir = 'uploads/') {
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    if ($file['size'] > 5 * 1024 * 1024) { // 5MB limit
        return ['success' => false, 'message' => 'File too large (max 5MB)'];
    }
    
    $filename = uniqid() . '.' . $file_extension;
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    }
    
    return ['success' => false, 'message' => 'Upload failed'];
}
?>