<?php
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_GET['org_id']) || empty($_GET['org_id'])) {
    echo json_encode([]);
    exit();
}

$org_id = (int)$_GET['org_id'];

try {
    $categories = getCategoriesByOrg($db, $org_id);
    echo json_encode($categories);
} catch (Exception $e) {
    echo json_encode([]);
}
?>