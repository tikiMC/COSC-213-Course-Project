<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// 1. Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 2. Validate input
if (!isset($_POST['product_id'], $_POST['quantity'])) {
    die("Invalid request.");
}

$product_id = (int)$_POST['product_id'];
$quantity = (int)$_POST['quantity'];
$user_id = $_SESSION['user_id'];

// 3. Check if the product is already in the cart
$stmt = $conn->prepare("SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);
$existing = $stmt->fetch();

if ($existing) {
    //If the item already exists, update the quantity
    $new_quantity = $existing['quantity'] + $quantity;
    $update = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
    $update->execute([$new_quantity, $existing['cart_item_id']]);
} else {
    //If the item doesn't exist insert a row into cart items
    $insert = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $insert->execute([$user_id, $product_id, $quantity]);
}

// 5. Redirect back to cart or product page
header("Location: cart.php");
exit;
