<?php
session_start();
session_destroy();
header("Location: login.php"); //sent back to homepage
exit;
?>