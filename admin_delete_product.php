<?php
include 'includes/header.php';
include 'includes/admin_check.php';
include 'includes/db.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
$stmt->execute([$id]);

header("Location: admin.php");
exit;
?>
