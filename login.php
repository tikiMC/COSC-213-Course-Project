<?php
session_start();
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username_or_email'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$user, $user]);
    $row = $stmt->fetch();

    if ($row && password_verify($pass, $row['password'])) {
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['is_admin'] = $row['is_admin'];

        header("Location: index.php"); //sent back to homepage
        exit;
    } else {
        $error = "Invalid login.";
    }
}
?>
<form method="post">
    Username or Email: <input type="text" name="username_or_email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>
<?php if (!empty($error)) echo $error; ?>
