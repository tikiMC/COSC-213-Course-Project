<?php
include 'includes/db.php';
include 'includes/header.php';

//Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

//Validate input
if (!isset($_POST['product_id'], $_POST['quantity'])) {
    die("Invalid request.");
}

$product_id = (int)$_POST['product_id'];
$quantity = (int)$_POST['quantity'];
if ($quantity < 1) {
    $quantity = 1;
}
$user_id = $_SESSION['user_id'];

//Gets stock from the database
$stmt = $conn->prepare("SELECT stock FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    die("Product does not exist.");
}

$stock = $product['stock'];
if ($stock <= 0) {
    die("This product is out of stock.");
}

//Checks if the item is already in the cart
$stmt = $conn->prepare("SELECT cart_item_id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);
$existing = $stmt->fetch();

if ($existing) {
    $new_quantity = $existing['quantity'] + $quantity;

    //Prevents exceeding the stock
    if ($new_quantity > $stock) {
        die("You cannot add more than the available stock. Available: $stock");
    }

    //Updates the quantity
    $update = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
    $update->execute([$new_quantity, $existing['cart_item_id']]);

} else {
    //Prevent adding more than stock
    if ($quantity > $stock) {
        die("Only $stock items are available.");
    }

    //Inserts new item into cart
    $insert = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $insert->execute([$user_id, $product_id, $quantity]);
}

header("Location: cart.php");
exit;
