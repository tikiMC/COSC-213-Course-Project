<?php
include 'includes/db.php';
include 'includes/header.php';

// Validate product ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}
$product_id = (int)$_GET['id'];

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    die("Product not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($product['name']); ?></h1>

    <?php 
    if (!empty($product['image_path'])) {
        echo '<img src="images/' . htmlspecialchars($product['image_path']) . '" width="300">';
    }
    ?>

    <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

    <?php
    if (isset($_SESSION['user_id'])) {
        echo '<form method="post" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="' . $product['product_id'] . '">
                Quantity: <input type="number" name="quantity" value="1" min="1">
                <button type="submit">Add to Cart</button>
              </form>';
    } else {
        echo '<p><a href="login.php">Login</a> to add this product to your cart.</p>';
    }
    ?>
</body>
</html>
