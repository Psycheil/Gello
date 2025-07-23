<?php
ob_start();
include_once("../includes/sidebar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { z-index: 1040; }
        .container { padding-left: 0; padding-right: 0; }
        .report-card {
            transition: transform 0.2s;
            cursor: pointer;
        }
        .report-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4" style="margin-left:270px; width: calc(100% - 280px);">
        <h4 class="mb-4">Reports</h4>
        
        <div class="row g-4">
            <!-- Members Report Card -->
            <div class="col-md-4">
                <a href="members.php" class="text-decoration-none">
                    <div class="card report-card h-100 bg-primary text-white">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-people display-4 mb-3"></i>
                            <h5 class="card-title">Members</h5>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Finance Report Card -->
            <div class="col-md-4">
                <a href="finance.php" class="text-decoration-none">
                    <div class="card report-card h-100 bg-success text-white">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-cash-coin display-4 mb-3"></i>
                            <h5 class="card-title">Finance</h5>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Services Report Card -->
            <div class="col-md-4">
                <a href="services.php" class="text-decoration-none">
                    <div class="card report-card h-100 bg-info text-white">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-gear display-4 mb-3"></i>
                            <h5 class="card-title">Services</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>