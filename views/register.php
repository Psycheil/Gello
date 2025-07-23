<?php
session_start();
if (isset($_SESSION['registered'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Register - MANFAS</title>
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <form class="p-4 rounded shadow bg-white" action="../handlers/register_handler.php" method="POST">
        <h4>Register</h4>
        <input type="text" name="username" class="form-control my-2" placeholder="Username" required>
        <input type="password" name="password" class="form-control my-2" placeholder="Password" required>
        <button class="btn btn-primary w-100" type="submit">Register</button>
    </form>
</body>
</html>
