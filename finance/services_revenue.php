<?php
include_once("../includes/sidebar.php");

// Database connection
$conn = new mysqli("localhost", "root", "", "manfas_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Services Revenue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { z-index: 1040; }
        .service-btn {
            width: 200px;
            height: 100px;
            font-size: 1.2rem;
            margin: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
            text-decoration: none;
            color: white !important;
        }
        .service-btn:hover {
            transform: translateY(-3px);
            opacity: 0.9;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4" style="margin-left:270px; width: calc(100% - 280px);">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Services Revenue</h4>
        </div>
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <a href="fertilizer.php" class="btn btn-primary service-btn">
                <div class="text-center">
                    <i class="fas fa-leaf mb-2" style="font-size: 2rem;"></i>
                    <div>Fertilizer</div>
                </div>
            </a>
            <a href="machineries.php" class="btn btn-success service-btn">
                <div class="text-center">
                    <i class="fas fa-tractor mb-2" style="font-size: 2rem;"></i>
                    <div>Machineries</div>
                </div>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>