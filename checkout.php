<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT c.cart_item_id, c.quantity, p.product_id, p.name, p.price
    FROM cart_items c
    JOIN products p ON c.product_id = p.product_id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
    echo '<p><a href="index.php">Back to Product Catalog</a></p>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Gets shipping info
    $name=trim($_POST['shipping_name']);
    $address=trim($_POST['shipping_address']);
    $city=trim($_POST['shipping_city']);
    $postal_code=trim($_POST['shipping_postal']);

    //Checks stock
    $stmt=$conn->prepare("
        SELECT c.quantity AS cart_qty, p.product_id, p.stock, p.name
        FROM cart_items c
        JOIN products p ON c.product_id=p.product_id
        WHERE c.user_id=?
    ");
    $stmt->execute([$user_id]);
    $items=$stmt->fetchAll();

    foreach ($items as $item) {
        if ($item['cart_qty']>$item['stock']) {
            die("Not enough stock for {$item['name']}. Available: {$item['stock']}");
        }
    }

    //Creates the order
    $total=0;
    foreach ($cart_items as $item) {
        $total+=$item['price']*$item['quantity'];
    }

    $stmt=$conn->prepare("
        INSERT INTO orders (user_id, shipping_name, shipping_address, shipping_city, shipping_postal, total)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$user_id,$name,$address,$city,$postal_code,$total]);
    $order_id=$conn->lastInsertId();

    //Adds the order items
    $stmt=$conn->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price_each)
        VALUES (?, ?, ?, ?)
    ");
    foreach ($cart_items as $item) {
        $stmt->execute([$order_id,$item['product_id'],$item['quantity'],$item['price']]);
    }

    //Reduces the stock
    $stmt=$conn->prepare("UPDATE products SET stock=stock-? WHERE product_id=?");
    foreach ($items as $item) {
        $stmt->execute([$item['cart_qty'],$item['product_id']]);
    }

    //Clears the cart
    $stmt=$conn->prepare("DELETE FROM cart_items WHERE user_id=?");
    $stmt->execute([$user_id]);

    echo "<h1>Thank you for your order!</h1>";
    echo "<p>Your order has been placed successfully.</p>";
    echo '<p><a href="index.php">Back to Product Catalog</a></p>';
    exit;
}
?>

<h1>Checkout</h1>

<form method="post" action="checkout.php">
    <label for="shipping_name">Full Name:</label><br>
    <input type="text" name="shipping_name" id="shipping_name" required><br><br>

    <label for="shipping_address">Address:</label><br>
    <input type="text" name="shipping_address" id="shipping_address" required><br><br>

    <label for="shipping_city">City:</label><br>
    <input type="text" name="shipping_city" id="shipping_city" required><br><br>

    <label for="shipping_postal">Postal Code:</label><br>
    <input type="text" name="shipping_postal" id="shipping_postal" required><br><br>

    <h2>Order Summary</h2>
    <?php
    $total = 0;
    foreach ($cart_items as $item) {
        $line_total = $item['price'] * $item['quantity'];
        $total += $line_total;
        echo "<p>" . htmlspecialchars($item['name']) . " x {$item['quantity']} = $" . number_format($line_total,2) . "</p>";
    }
    ?>
    <p><strong>Total: $<?php echo number_format($total,2); ?></strong></p>

    <button type="submit">Place Order</button>
</form>