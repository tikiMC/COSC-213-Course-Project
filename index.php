<?php
session_start();
include 'includes/db.php';
?>

<h1>Product Catalog</h1>

<?php
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll();

foreach ($products as $product) {
    echo "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";
    echo "<h2>" . htmlspecialchars($product['name']) . "</h2>";
    echo "<p>Price: $" . number_format($product['price'], 2) . "</p>";

    // Show image if it exists
    if (!empty($product['image_path'])) {
        echo "<img src='images/" . htmlspecialchars($product['image_path']) . "' width='150'>";
    }

    // Link to product detail page
    echo "<p><a href='product.php?id=" . $product['product_id'] . "'>View Details</a></p>";
    echo "</div>";
}
?>
