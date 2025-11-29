<?php
session_start();
include 'includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all cart items for this user, joining with products for info
$stmt = $conn->prepare("
    SELECT c.cart_item_id, c.quantity, p.name, p.price, p.image_path
    FROM cart_items c
    JOIN products p ON c.product_id = p.product_id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();
?>

<h1>Your Shopping Cart</h1>

<?php
if (empty($items)) {
    echo "<p>Your cart is empty.</p>";
    echo '<p><a href="index.php">Back to Product Catalog</a></p>';
    exit;
}

$total = 0;

foreach ($items as $item) {
    $line_total = $item['price'] * $item['quantity'];
    $total += $line_total;
    ?>
    <div style="border:1px solid #ccc; padding:10px; margin:10px;">
        <h2><?php echo htmlspecialchars($item['name']); ?></h2>
        <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
        <p>Quantity: <?php echo $item['quantity']; ?></p>
        <p>Line Total: $<?php echo number_format($line_total, 2); ?></p>
        <?php if (!empty($item['image_path'])): ?>
            <img src="images/<?php echo htmlspecialchars($item['image_path']); ?>" width="100">
        <?php endif; ?>
        <form method="post" action="remove_from_cart.php" style="margin-top:5px;">
            <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
            <button type="submit">Remove</button>
        </form>
    </div>
    <?php
}
?>

<h3>Total: $<?php echo number_format($total, 2); ?></h3>
<p><a href="index.php">Back to Product Catalog</a></p>
<a href="checkout.php">Proceed to Checkout</a>