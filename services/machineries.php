<?php
ob_start();
include_once("../includes/sidebar.php");

// Database connection
$conn = new mysqli("localhost", "root", "", "manfas_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM machineries WHERE id=$delete_id");
    header("Location: machineries.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $machine = $_POST['machine'];
    $amount = $_POST['amount'];
    $area = $_POST['area'];
    $current_date = date('Y-m-d');

    if (isset($_POST['edit_id']) && $_POST['edit_id'] !== "") {
        // Edit existing record
        $edit_id = intval($_POST['edit_id']);
        $stmt = $conn->prepare("UPDATE machineries SET name=?, machine=?, amount=?, area=? WHERE id=?");
        $stmt->bind_param("ssdsi", $name, $machine, $amount, $area, $edit_id);
    } else {
        // Add new record
        $stmt = $conn->prepare("INSERT INTO machineries (name, machine, amount, area, date_added) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $name, $machine, $amount, $area, $current_date);
    }
    
    $stmt->execute();
    $stmt->close();
    header("Location: machineries.php");
    exit;
}

// Fetch records
$result = $conn->query("SELECT * FROM machineries ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Machineries Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { z-index: 1040; }
        .container { padding-left: 0; padding-right: 0; }
        .peso:before { content: "₱"; margin-right: 2px; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4" style="margin-left:270px; width: calc(100% - 280px);">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Machineries Rental</h4>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addMachineModal">
                Add Rental
            </button>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-success text-center">
                            <tr>
                                <th>Name</th>
                                <th>Machine</th>
                                <th>Amount/Hectare</th>
                                <th>Area</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['machine']) ?></td>
                                <td class="peso"><?= number_format($row['amount'], 2) ?></td>
                                <td><?= htmlspecialchars($row['area']) ?> hectares</td>
                                <td><?= date('m/d/y', strtotime($row['date_added'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-btn" 
                                        data-id="<?= $row['id'] ?>"
                                        data-name="<?= htmlspecialchars($row['name']) ?>"
                                        data-machine="<?= htmlspecialchars($row['machine']) ?>"
                                        data-amount="<?= $row['amount'] ?>"
                                        data-area="<?= $row['area'] ?>"
                                        >Edit</button>
                                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Delete this record?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Machine Modal -->
    <div class="modal fade" id="addMachineModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST">
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Rental</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Machine</label>
                        <select class="form-control" name="machine" required>
                            <option value="Tractor">Tractor</option>
                            <option value="Rotavator">Rotavator</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount per Hectare (₱)</label>
                        <input type="number" class="form-control" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Area (hectares)</label>
                        <input type="text" class="form-control" name="area" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('modalTitle').innerText = "Edit Rental";
                    document.getElementById('edit_id').value = this.dataset.id;
                    document.querySelector('input[name="name"]').value = this.dataset.name;
                    document.querySelector('select[name="machine"]').value = this.dataset.machine;
                    document.querySelector('input[name="amount"]').value = this.dataset.amount;
                    document.querySelector('input[name="area"]').value = this.dataset.area;
                    new bootstrap.Modal(document.getElementById('addMachineModal')).show();
                });
            });

            document.querySelector('[data-bs-target="#addMachineModal"]').addEventListener('click', function() {
                document.getElementById('modalTitle').innerText = "Add Rental";
                document.getElementById('edit_id').value = "";
                document.querySelector('input[name="name"]').value = "";
                document.querySelector('select[name="machine"]').value = "Tractor";
                document.querySelector('input[name="amount"]').value = "";
                document.querySelector('input[name="area"]').value = "";
            });
        });
    </script>
</body>
</html>