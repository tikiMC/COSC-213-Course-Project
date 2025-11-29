<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);

    if ($stmt->rowCount() > 0) {
        $error = "Email or username is already taken.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (email, username, password, is_admin) VALUES (?, ?, ?, 0)");
        $stmt->execute([$email, $username, $hashed]);

        $_SESSION['user_id'] = $conn->lastInsertId();
        $_SESSION['username'] = $username;
        $_SESSION['is_admin'] = 0;

        header("Location: index.php");
        exit;
    }
}
?>

<h2>Register</h2>
<form method="post">
    Email: <input type="email" name="email" required><br>
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Register</button>
</form>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
