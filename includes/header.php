<?php
session_start();
?>
<nav>
    <a href="index.php">Home</a> |
    <?php
    if (isset($_SESSION['user_id'])) {
        echo '<a href="cart.php">Cart</a> | <a href="logout.php">Logout</a> | ';
        if($_SESSION['is_admin'] == 1){
            echo '<a href="admin.php">Admin Panel</a>';
        }
    } else {
        echo '<a href="login.php">Login</a> | <a href="register.php">Register</a>';
    }
    ?>
</nav>
<hr>