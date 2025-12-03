<?php
include 'includes/db.php';
include 'includes/header.php';
echo "<link rel='stylesheet' type='text/css' href='styles.css' />";
?>

<h1 style="text-align: center;">Product Catalog</h1>

<div class="product-container">
    <?php
    $stmt = $conn->query("SELECT * FROM products");
    $products = $stmt->fetchAll();

    foreach ($products as $product) {
        echo "<div class='product'>";
        echo "<p class='product-name'>" . htmlspecialchars($product['name']) . "</p>";
        echo "<p class='product-price'>Price: $" . number_format($product['price'], 2) . "</p>";

        //Adding stock info
        if($product['stock'] > 0){
            echo "<p class='product-stock'>In Stock: " . $product['stock'] . "</p>";
        }else{
            echo "<p style='color:red; font-weight:bold;'>Out of Stock</p>";
        }

        // Show image if it exists
        if (!empty($product['image_path'])) {
            echo "<img src='images/" . htmlspecialchars($product['image_path']) . "' width='150'>";
        }

        // Link to product detail page
        echo "<button><a href='product.php?id=" . $product['product_id'] . "'>View Details</a></button>";
        echo "</div>";
    }
    ?>
</div>
