<?php
ob_start();
include_once("../includes/sidebar.php");
require '../includes/db.php';

// Fetch membership statistics
$total_membership_fee = $conn->query("SELECT SUM(membership_fee) as total FROM membership_payments")->fetch_assoc()['total'] ?? 0;
$total_cbu = $conn->query("SELECT SUM(cbu) as total FROM membership_payments")->fetch_assoc()['total'] ?? 0;

// Modified monthly due calculation
$monthly_dues_result = $conn->query("SELECT monthly_due FROM membership_payments");
$total_monthly_due = 0;
while ($row = $monthly_dues_result->fetch_assoc()) {
    $monthly_dues = json_decode($row['monthly_due'], true);
    if (is_array($monthly_dues)) {
        $total_monthly_due += array_sum($monthly_dues);
    }
}

// Fetch services revenue
$fertilizer_revenue = $conn->query("SELECT SUM(revenue) as total FROM fertilizer_sales")->fetch_assoc()['total'] ?? 0;
$machinery_revenue = $conn->query("SELECT SUM(income) as total FROM machinery_services")->fetch_assoc()['total'] ?? 0;
$total_services = $fertilizer_revenue + $machinery_revenue;

// Calculate total revenue
$total_revenue = $total_membership_fee + $total_monthly_due + $total_services;

// Fetch recent transactions
$recent_fertilizer = $conn->query("SELECT * FROM fertilizer_sales ORDER BY id DESC LIMIT 5");
$recent_machinery = $conn->query("SELECT * FROM machinery_services ORDER BY id DESC LIMIT 5");
$recent_membership = $conn->query("SELECT * FROM membership_payments ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Financial Report</title>
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
            <h3 class="mb-1">MANFAS Financial Report</h3>
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

        <!-- Revenue Overview -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card stat-card h-100" style="border-left-color: #28a745;">
                    <div class="card-body">
                        <h6 class="text-muted">Total Revenue</h6>
                        <h3 class="peso mb-0"><?= number_format($total_revenue, 2) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card h-100" style="border-left-color: #007bff;">
                    <div class="card-body">
                        <h6 class="text-muted">Membership Income</h6>
                        <h3 class="peso mb-0"><?= number_format($total_membership_fee + $total_monthly_due, 2) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card h-100" style="border-left-color: #dc3545;">
                    <div class="card-body">
                        <h6 class="text-muted">Services Revenue</h6>
                        <h3 class="peso mb-0"><?= number_format($total_services, 2) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card h-100" style="border-left-color: #ffc107;">
                    <div class="card-body">
                        <h6 class="text-muted">Total CBU</h6>
                        <h3 class="peso mb-0"><?= number_format($total_cbu, 2) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Breakdown -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Services Revenue Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="servicesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Revenue Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart"></canvas>
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
                <ul class="nav nav-tabs" id="transactionTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#membership">Membership</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#fertilizer">Fertilizer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#machinery">Machinery</a>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <!-- Membership Tab -->
                    <div class="tab-pane fade show active" id="membership">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Membership Fee</th>
                                        <th>Monthly Due</th>
                                        <th>CBU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $recent_membership->fetch_assoc()): 
                                        $monthly_dues = json_decode($row['monthly_due'], true);
                                        $monthly_total = array_sum($monthly_dues);
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td class="peso"><?= number_format($row['membership_fee'], 2) ?></td>
                                        <td class="peso"><?= number_format($monthly_total, 2) ?></td>
                                        <td class="peso"><?= number_format($row['cbu'], 2) ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Fertilizer Tab -->
                    <div class="tab-pane fade" id="fertilizer">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Classification</th>
                                        <th>Quantity</th>
                                        <th>Price/sack</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $recent_fertilizer->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['classification']) ?></td>
                                        <td><?= $row['quantity'] ?></td>
                                        <td class="peso"><?= number_format($row['price'], 2) ?></td>
                                        <td class="peso"><?= number_format($row['revenue'], 2) ?></td>
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
                                        <th>Machinery</th>
                                        <th>Area</th>
                                        <th>Amount/Hectare</th>
                                        <th>Income</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $recent_machinery->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['machinery']) ?></td>
                                        <td><?= $row['area'] ?></td>
                                        <td class="peso"><?= number_format($row['amount'], 2) ?></td>
                                        <td class="peso"><?= number_format($row['income'], 2) ?></td>
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
    <script>
        // Services Revenue Chart
        new Chart(document.getElementById('servicesChart'), {
            type: 'pie',
            data: {
                labels: ['Fertilizer Sales', 'Machinery Services'],
                datasets: [{
                    data: [<?= $fertilizer_revenue ?>, <?= $machinery_revenue ?>],
                    backgroundColor: ['#20c997', '#6f42c1']
                }]
            }
        });

        // Revenue Distribution Chart
        new Chart(document.getElementById('revenueChart'), {
            type: 'pie',
            data: {
                labels: ['Membership Fees', 'Monthly Dues', 'Services Revenue'],
                datasets: [{
                    data: [
                        <?= $total_membership_fee ?>, 
                        <?= $total_monthly_due ?>, 
                        <?= $total_services ?>
                    ],
                    backgroundColor: ['#007bff', '#28a745', '#dc3545']
                }]
            }
        });

        // Export to Excel functionality
        function exportToExcel() {
            const tables = document.querySelectorAll('.table');
            const wb = XLSX.utils.book_new();
            
            tables.forEach((table, index) => {
                const ws = XLSX.utils.table_to_sheet(table);
                XLSX.utils.book_append_sheet(wb, ws, `Data_${index + 1}`);
            });
            
            XLSX.writeFile(wb, "MANFAS_Financial_Report_" + new Date().toISOString().split('T')[0] + ".xlsx");
        }
    </script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
</body>
</html>