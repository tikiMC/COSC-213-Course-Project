<?php
include 'includes/header.php';
include 'includes/admin_check.php';
include 'includes/db.php';
?>

<h1>Add Product</h1>

<form method="post" enctype="multipart/form-data">
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" required></textarea><br><br>

    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" required><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock" required><br><br>

    <label>Image:</label><br>
    <input type="file" name="image"><br><br>

    <button type="submit">Add</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // handle image upload
    $image_path = '';
    if (!empty($_FILES['image']['name'])) {
        $image_path = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "images/$image_path");
    }

    $stmt = $conn->prepare("
        INSERT INTO products (name, description, price, stock, image_path)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$name, $desc, $price, $stock, $image_path]);

    header("Location: admin.php");
    exit;
}
?>
