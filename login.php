<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['email_or_username']);
    $pass = trim($_POST['password']);

    // Fetch user by username or email
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$user, $user]);
    $row = $stmt->fetch();

    if ($row) {
        if (password_verify($pass, $row['password'])) {
            // Password correct, set session
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['is_admin'] = $row['is_admin'];

            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that email/username.";
    }
}
?>

<h2>Login</h2>
<form method="post">
    Email or Username: <input type="text" name="email_or_username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
