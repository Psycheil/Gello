<?php
ob_start(); // Add this at the very top
include_once("../includes/sidebar.php");

// Database connection
$conn = new mysqli("localhost", "root", "", "manfas_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM machinery_services WHERE id=$delete_id");
    header("Location: machineries.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $machinery = $_POST['machinery'];
    $area = $_POST['area'];
    $amount = $_POST['amount'];
    $income = $area * $amount;

    if (isset($_POST['edit_id']) && $_POST['edit_id'] !== "") {
        // Edit existing record
        $edit_id = intval($_POST['edit_id']);
        $stmt = $conn->prepare("UPDATE machinery_services SET machinery=?, area=?, amount=?, income=? WHERE id=?");
        $stmt->bind_param("siddi", $machinery, $area, $amount, $income, $edit_id);
    } else {
        // Add new record
        $stmt = $conn->prepare("INSERT INTO machinery_services (machinery, area, amount, income) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sidd", $machinery, $area, $amount, $income);
    }
    
    $stmt->execute();
    $stmt->close();
    header("Location: machineries.php");
    exit;
}

// Fetch machinery records
$result = $conn->query("SELECT * FROM machinery_services ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Machinery Services</title>
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
            <h4>Machinery Services</h4>
            <div>
                <a href="services_revenue.php" class="btn btn-secondary btn-sm me-2">Back to Services</a>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addMachineryModal">
                    Add Machinery Service
                </button>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-success text-center">
                            <tr>
                                <th>Machineries</th>
                                <th>Area</th>
                                <th>Amount</th>
                                <th>Income</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['machinery']) ?></td>
                                <td><?= $row['area'] ?></td>
                                <td class="peso"><?= number_format($row['amount'], 2) ?>/Hectare</td>
                                <td class="peso"><?= number_format($row['income'], 2) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-btn" 
                                        data-id="<?= $row['id'] ?>"
                                        data-machinery="<?= htmlspecialchars($row['machinery']) ?>"
                                        data-area="<?= $row['area'] ?>"
                                        data-amount="<?= $row['amount'] ?>"
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

    <!-- Add Machinery Modal -->
    <div class="modal fade" id="addMachineryModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST">
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Machinery Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Machinery Type</label>
                        <select class="form-control" name="machinery" required>
                            <option value="Tractor">Tractor</option>
                            <option value="Rotavator">Rotavator</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Area (Hectares)</label>
                        <input type="number" class="form-control" name="area" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount per Hectare (₱)</label>
                        <input type="number" step="0.01" class="form-control" name="amount" required>
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
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('modalTitle').innerText = "Edit Machinery Service";
                document.getElementById('edit_id').value = this.dataset.id;
                document.querySelector('select[name="machinery"]').value = this.dataset.machinery;
                document.querySelector('input[name="area"]').value = this.dataset.area;
                document.querySelector('input[name="amount"]').value = this.dataset.amount;
                var modal = new bootstrap.Modal(document.getElementById('addMachineryModal'));
                modal.show();
            });
        });

        document.querySelector('[data-bs-target="#addMachineryModal"]').addEventListener('click', function() {
            document.getElementById('modalTitle').innerText = "Add Machinery Service";
            document.getElementById('edit_id').value = "";
            document.querySelector('select[name="machinery"]').value = "Tractor";
            document.querySelector('input[name="area"]').value = "";
            document.querySelector('input[name="amount"]').value = "";
        });
    </script>
</body>
</html>