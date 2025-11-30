<?php
include 'includes/header.php';
include 'includes/admin_check.php';
include 'includes/db.php';
?>

<h1>Admin Panel - Products</h1>

<p><a href="admin_create_product.php">Add New Product</a></p>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Price</th>
    <th>Stock</th>
    <th>Image</th>
    <th>Actions</th>
</tr>

<?php
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll();

foreach ($products as $p) {
    echo "<tr>";
    echo "<td>{$p['product_id']}</td>";
    echo "<td>{$p['name']}</td>";
    echo "<td>\${$p['price']}</td>";
    echo "<td>{$p['stock']}</td>";
    echo "<td>";

    if (!empty($p['image_path'])) {
        echo "<img src='images/{$p['image_path']}' width='60'>";
    }

    echo "</td>";

    echo "<td>
        <a href='admin_update_product.php?id={$p['product_id']}'>Edit</a> |
        <a href='admin_delete_product.php?id={$p['product_id']}'
            onclick=\"return confirm('Delete this product?');\">
            Delete
        </a>
    </td>";

    echo "</tr>";
}
?>
</table>
