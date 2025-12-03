<?php
session_start();
echo "<link rel='stylesheet' type='text/css' href='styles.css' />";
?>
<header>
    <div class='navbar'>
        <a class="nav-links" href="index.php">Home</a> |
        <?php
        if (isset($_SESSION['user_id'])) {
            echo '<a class="nav-links" href="cart.php">Cart</a> | <a class="nav-links" href="logout.php">Logout</a> | ';
            if($_SESSION['is_admin'] == 1){
                echo '<a class="nav-links" href="admin.php">Admin Panel</a>';
            }
        } else {
            echo '<a class="nav-links" href="login.php">Login</a> | <a class="nav-links" href="register.php">Register</a>';
        }
        ?>
    </div>
</header>
<hr>
