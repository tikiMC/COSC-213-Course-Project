<?php
include 'includes/header.php';
include 'includes/admin_check.php';
include 'includes/db.php';
echo "<link rel='stylesheet' type='text/css' href='styles.css' />";

// Get product ID
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Product not found.");
}
?>

<h1>Edit Product</h1>

<form method="post" enctype="multipart/form-data">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?= $product['name'] ?>" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" required><?= $product['description'] ?></textarea><br><br>

    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock" value="<?= $product['stock'] ?>" required><br><br>

    <label>Replace Image (optional):</label><br>
    <input type="file" name="image"><br>
    <small>Current: <?= $product['image_path'] ?></small><br><br>

    <button type="submit">Save Changes</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $image_path = $product['image_path'];
    if (!empty($_FILES['image']['name'])) {
        $image_path = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "images/$image_path");
    }

    $stmt = $conn->prepare("
        UPDATE products 
        SET name = ?, description = ?, price = ?, stock = ?, image_path = ?
        WHERE product_id = ?
    ");
    $stmt->execute([$name, $desc, $price, $stock, $image_path, $id]);

    header("Location: admin.php");
    exit;
}
?>
