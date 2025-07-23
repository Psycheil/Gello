<?php
ob_start();
include_once("../includes/sidebar.php");
require '../includes/db.php';

// Fetch members statistics
$total_members = $conn->query("SELECT COUNT(*) as total FROM members")->fetch_assoc()['total'];
$male_members = $conn->query("SELECT COUNT(*) as total FROM members WHERE gender = 'Male'")->fetch_assoc()['total'];
$female_members = $conn->query("SELECT COUNT(*) as total FROM members WHERE gender = 'Female'")->fetch_assoc()['total'];
$officers = $conn->query("SELECT COUNT(*) as total FROM members WHERE role_type = 'officer'")->fetch_assoc()['total'];
$ric_members = $conn->query("SELECT COUNT(*) as total FROM members WHERE group_membership = 'RIC'")->fetch_assoc()['total'];
$fa_members = $conn->query("SELECT COUNT(*) as total FROM members WHERE group_membership = 'FA'")->fetch_assoc()['total'];
$four_h_members = $conn->query("SELECT COUNT(*) as total FROM members WHERE group_membership = '4H'")->fetch_assoc()['total'];

// Fetch all members with calculated age
$result = $conn->query("SELECT *, 
    TIMESTAMPDIFF(YEAR, birthday, CURDATE()) as age,
    DATE_FORMAT(birthday, '%Y-%m-%d') as formatted_birthday
    FROM members 
    ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Members Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { z-index: 1040; }
        .container { padding-left: 0; padding-right: 0; }
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }
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
            <h3 class="mb-1">MANFAS Members Report</h3>
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

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card stat-card h-100" style="border-left-color: #28a745;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div>
                            <p class="stat-number"><?= $total_members ?></p>
                            <p class="stat-label">Total Members</p>
                        </div>
                        <i class="bi bi-people stat-icon ms-auto text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card h-100" style="border-left-color: #007bff;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div>
                            <p class="stat-number"><?= $male_members ?></p>
                            <p class="stat-label">Male Members</p>
                        </div>
                        <i class="bi bi-gender-male stat-icon ms-auto text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card h-100" style="border-left-color: #dc3545;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div>
                            <p class="stat-number"><?= $female_members ?></p>
                            <p class="stat-label">Female Members</p>
                        </div>
                        <i class="bi bi-gender-female stat-icon ms-auto text-danger"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card h-100" style="border-left-color: #ffc107;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div>
                            <p class="stat-number"><?= $officers ?></p>
                            <p class="stat-label">Officers</p>
                        </div>
                        <i class="bi bi-person-badge stat-icon ms-auto text-warning"></i>
                    </div>
                </div>
            </div>
            <!-- Additional Group Statistics -->
            <div class="col-md-4">
                <div class="card stat-card h-100" style="border-left-color: #17a2b8;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div>
                            <p class="stat-number"><?= $ric_members ?></p>
                            <p class="stat-label">RIC Members</p>
                        </div>
                        <i class="bi bi-people-fill stat-icon ms-auto text-info"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card h-100" style="border-left-color: #6f42c1;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div>
                            <p class="stat-number"><?= $fa_members ?></p>
                            <p class="stat-label">FA Members</p>
                        </div>
                        <i class="bi bi-people-fill stat-icon ms-auto text-purple"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card h-100" style="border-left-color: #20c997;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div>
                            <p class="stat-number"><?= $four_h_members ?></p>
                            <p class="stat-label">4H Members</p>
                        </div>
                        <i class="bi bi-people-fill stat-icon ms-auto text-teal"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Group Filter and Table Section -->
        <div class="mb-4 no-print">
            <select class="form-select form-select-sm" id="groupFilter" style="width: 150px;">
                <option value="">All Groups</option>
                <option value="officer">Officers</option>
                <option value="RIC">RIC</option>
                <option value="FA">FA</option>
                <option value="4H">4H</option>
            </select>
        </div>

        <!-- Members Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Detailed Member List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Gender</th>
                                <th>Birthday</th>
                                <th>Group</th>
                                <th>Position</th>
                                <th>Age</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['address']) ?></td>
                                <td><?= htmlspecialchars($row['contact_number']) ?></td>
                                <td><?= ucfirst(htmlspecialchars($row['gender'])) ?></td>
                                <td><?= date('M d, Y', strtotime($row['formatted_birthday'])) ?></td>
                                <td><?= htmlspecialchars($row['group_membership']) ?></td>
                                <td><?= htmlspecialchars($row['officer_position'] ?? '-') ?></td>
                                <td><?= $row['age'] ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <script>
        // Export to Excel functionality
        function exportToExcel() {
            const table = document.querySelector('table');
            const ws = XLSX.utils.table_to_sheet(table);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Members");
            XLSX.writeFile(wb, "MANFAS_Members_Report_" + new Date().toISOString().split('T')[0] + ".xlsx");
        }

        // Group filter functionality
        document.getElementById('groupFilter').addEventListener('change', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const groupCell = row.cells[5].textContent.toLowerCase(); // Group membership column
                const positionCell = row.cells[6].textContent.toLowerCase(); // Position column
                
                if (filter === 'officer') {
                    row.style.display = positionCell !== '-' ? '' : 'none';
                } else {
                    row.style.display = filter === '' || groupCell.includes(filter) ? '' : 'none';
                }
            });
        });
    </script>
</body>
</html>