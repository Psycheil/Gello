<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: views/dashboard.php");  // Updated path to include the views directory
} elseif (!isset($_SESSION['registered'])) {
    header("Location: views/login.php");
} else {
    header("Location: views/login.php");
}
exit();
