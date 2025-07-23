<?php
session_start();
require '../includes/db.php';

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Check if already registered
$check = $conn->query("SELECT * FROM users");
if ($check->num_rows > 0) {
    $_SESSION['error'] = "Registration is closed.";
    header("Location: ../index.php");
    exit();
}

$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();

$_SESSION['registered'] = true;
header("Location: ../index.php");
?>
