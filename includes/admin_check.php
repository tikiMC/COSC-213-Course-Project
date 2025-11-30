<?php
//Checks if the user is an admin or not
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    die("Access denied. Admins only.");
}
?>
