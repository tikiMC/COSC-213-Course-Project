<?php
include 'includes/db.php';
include 'includes/header.php';
echo "<link rel='stylesheet' type='text/css' href='styles.css' />";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Validate input
if (!isset($_POST['cart_item_id'])) {
    die("Invalid request.");
}

$cart_item_id = (int)$_POST['cart_item_id'];
$user_id = $_SESSION['user_id'];

// Delete the cart item only if it belongs to this user
$stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_item_id = ? AND user_id = ?");
$stmt->execute([$cart_item_id, $user_id]);

// Redirect back to cart page
header("Location: cart.php");
exit;
