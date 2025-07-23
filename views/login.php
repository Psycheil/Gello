<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MANFAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-form {
            max-width: 320px;
            width: 100%;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

    <form class="login-form bg-white p-4 rounded-3 shadow-sm" action="../handlers/login_handler.php" method="POST">
        <h5 class="mb-3 text-center">MANFAS Login</h5>
        
        <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="text-danger small mb-2 text-center"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <button class="btn btn-success w-100" type="submit">Login</button>
    </form>

</body>
</html>
