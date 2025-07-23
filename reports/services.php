<?php
ob_start();
include_once("../includes/sidebar.php");
require '../includes/db.php';

// Fetch service statistics
$total_fertilizer = $conn->query("SELECT COUNT(*) as total FROM fertilizer")->fetch_assoc()['total'];
$total_livestock = $conn->query("SELECT COUNT(*) as total FROM livestock")->fetch_assoc()['total'];
$total_machinery = $conn->query("SELECT COUNT(*) as total FROM machineries")->fetch_assoc()['total'];

// Fetch recent transactions
$recent_fertilizer = $conn->query("SELECT * FROM fertilizer ORDER BY date_added DESC LIMIT 5");
$recent_livestock = $conn->query("SELECT * FROM livestock ORDER BY date_added DESC LIMIT 5");
$recent_machinery = $conn->query("SELECT * FROM machineries ORDER BY date_added DESC LIMIT 5");

// Calculate livestock statistics
$male_livestock = $conn->query("SELECT COUNT(*) as total FROM livestock WHERE gender = 'Male'")->fetch_assoc()['total'];
$female_livestock = $conn->query("SELECT COUNT(*) as total FROM livestock WHERE gender = 'Female'")->fetch_assoc()['total'];

// Calculate machinery usage
$tractor_usage = $conn->query("SELECT SUM(area) as total FROM machineries WHERE machine = 'Tractor'")->fetch_assoc()['total'] ?? 0;
$rotavator_usage = $conn->query("SELECT SUM(area) as total FROM machineries WHERE machine = 'Rotavator'")->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Services Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar { z-index: 1040; }
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-number {
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0;
        }
        .stat-label {
            font-size: 0.875rem;
            color: #6c757d;
            margin: 0;
        }
        .stat-icon {
            font-size: 1.5rem;
            opacity: 0.7;
        }
        .peso:before { content: "₱"; margin-right: 2px; }
        @media print {
            .no-print { display: none; }
            .container { margin: 0; width: 100%; }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4" style="margin-left:270px; width: calc(100% - 280px);">
        <!-- Header Section -->
        <div class="text-center mb-4">
            <h3 class="mb-1">MANFAS Services Report</h3>
            <p class="text-muted">Generated on <?= date('F d, Y') ?></p>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="../reports/index.php" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back to Reports
            </a>
            <div>
                <button onclick="exportToExcel()" class="btn btn-success btn-sm">
                    <i class="bi bi-file-earmark-excel"></i> Export to Excel
                </button>
            </div>
        </div>

        <!-- Service Statistics -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card stat-card h-100" style="border-left-color: #28a745;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div>
                            <p class="stat-number"><?= $total_fertilizer ?></p>
                            <p class="stat-label">Fertilizer Transactions</p>
                        </div>
                        <i class="bi bi-box stat-icon ms-auto text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card h-100" style="border-left-color: #007bff;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div>
                            <p class="stat-number"><?= $total_livestock ?></p>
                            <p class="stat-label">Livestock Records</p>
                        </div>
                        <i class="bi bi-egg stat-icon ms-auto text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card h-100" style="border-left-color: #dc3545;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div>
                            <p class="stat-number"><?= $total_machinery ?></p>
                            <p class="stat-label">Machinery Rentals</p>
                        </div>
                        <i class="bi bi-gear stat-icon ms-auto text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Details -->
        <div class="row mb-4">
            <!-- Livestock Distribution -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Livestock Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="livestockChart"></canvas>
                    </div>
                </div>
            </div>
            <!-- Machinery Usage -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Machinery Usage (Hectares)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="machineryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Transactions</h5>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="serviceTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#fertilizer">Fertilizer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#livestock">Livestock</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#machinery">Machinery</a>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <!-- Fertilizer Tab -->
                    <div class="tab-pane fade show active" id="fertilizer">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Classification</th>
                                        <th>Quantity</th>
                                        <th>Amount/Sack</th>
                                        <th>Date Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $recent_fertilizer->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['classification']) ?></td>
                                        <td><?= $row['quantity'] ?></td>
                                        <td class="peso"><?= number_format($row['amount'], 2) ?></td>
                                        <td><?= date('M d, Y', strtotime($row['date_added'])) ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Livestock Tab -->
                    <div class="tab-pane fade" id="livestock">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Gender</th>
                                        <th>Age</th>
                                        <th>Date Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $recent_livestock->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['livestock_type']) ?></td>
                                        <td><?= htmlspecialchars($row['gender']) ?></td>
                                        <td><?= htmlspecialchars($row['age']) ?></td>
                                        <td><?= date('M d, Y', strtotime($row['date_added'])) ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Machinery Tab -->
                    <div class="tab-pane fade" id="machinery">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Machine</th>
                                        <th>Amount/Hectare</th>
                                        <th>Area</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $recent_machinery->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['machine']) ?></td>
                                        <td class="peso"><?= number_format($row['amount'], 2) ?></td>
                                        <td><?= htmlspecialchars($row['area']) ?> hectares</td>
                                        <td><?= date('M d, Y', strtotime($row['date_added'])) ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <script>
        // Livestock Distribution Chart
        new Chart(document.getElementById('livestockChart'), {
            type: 'pie',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [<?= $male_livestock ?>, <?= $female_livestock ?>],
                    backgroundColor: ['#007bff', '#dc3545']
                }]
            }
        });

        // Machinery Usage Chart
        new Chart(document.getElementById('machineryChart'), {
            type: 'bar',
            data: {
                labels: ['Tractor', 'Rotavator'],
                datasets: [{
                    label: 'Total Area (Hectares)',
                    data: [<?= $tractor_usage ?>, <?= $rotavator_usage ?>],
                    backgroundColor: ['#28a745', '#17a2b8'],
                    borderColor: ['#28a745', '#17a2b8'],
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false  // This removes the colored label
                    },
                    title: {
                        display: true,
                        text: 'Total Area (Hectares)',
                        position: 'top'
                    }
                }
            }
        });

        // Export to Excel functionality
        function exportToExcel() {
            const tables = document.querySelectorAll('.table');
            const wb = XLSX.utils.book_new();
            
            tables.forEach((table, index) => {
                const ws = XLSX.utils.table_to_sheet(table);
                XLSX.utils.book_append_sheet(wb, ws, `Service_Data_${index + 1}`);
            });
            
            XLSX.writeFile(wb, "MANFAS_Services_Report_" + new Date().toISOString().split('T')[0] + ".xlsx");
        }
    </script>
</body>
</html>