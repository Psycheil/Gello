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
    $conn->query("DELETE FROM fertilizer WHERE id=$delete_id");
    header("Location: fertilizer.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classification = $_POST['classification'];
    $quantity = $_POST['quantity'];
    $amount = $_POST['amount'];
    $current_date = date('Y-m-d H:i:s');

    if (isset($_POST['edit_id']) && $_POST['edit_id'] !== "") {
        // Edit existing record
        $edit_id = intval($_POST['edit_id']);
        $stmt = $conn->prepare("UPDATE fertilizer SET classification=?, quantity=?, amount=?, date_updated=? WHERE id=?");
        $stmt->bind_param("sidsi", $classification, $quantity, $amount, $current_date, $edit_id);
    } else {
        // Add new record
        $stmt = $conn->prepare("INSERT INTO fertilizer (classification, quantity, amount, date_added, date_updated) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sidss", $classification, $quantity, $amount, $current_date, $current_date);
    }
    
    $stmt->execute();
    $stmt->close();
    header("Location: fertilizer.php");
    exit;
}

// Fetch records
$result = $conn->query("SELECT * FROM fertilizer ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Fertilizer Services</title>
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
            <h4>Fertilizer Services</h4>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addFertilizerModal">
                Add Fertilizer
            </button>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-success text-center">
                            <tr>
                                <th>Fertilizers Classification</th>
                                <th>Quantity</th>
                                <th>Amount/Sacks</th>
                                <th>Date Added</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['classification']) ?></td>
                                <td><?= $row['quantity'] ?></td>
                                <td class="peso"><?= number_format($row['amount'], 2) ?></td>
                                <td><?= date('m/d/y h:i A', strtotime($row['date_added'])) ?></td>
                                <td><?= date('m/d/y h:i A', strtotime($row['date_updated'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-btn" 
                                        data-id="<?= $row['id'] ?>"
                                        data-classification="<?= htmlspecialchars($row['classification']) ?>"
                                        data-quantity="<?= $row['quantity'] ?>"
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

    <!-- Add Fertilizer Modal -->
    <div class="modal fade" id="addFertilizerModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST">
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Fertilizer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Fertilizers Classification</label>
                        <input type="text" class="form-control" name="classification" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount per Sack (₱)</label>
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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('modalTitle').innerText = "Edit Fertilizer";
                    document.getElementById('edit_id').value = this.dataset.id;
                    document.querySelector('input[name="classification"]').value = this.dataset.classification;
                    document.querySelector('input[name="quantity"]').value = this.dataset.quantity;
                    document.querySelector('input[name="amount"]').value = this.dataset.amount;
                    new bootstrap.Modal(document.getElementById('addFertilizerModal')).show();
                });
            });

            document.querySelector('[data-bs-target="#addFertilizerModal"]').addEventListener('click', function() {
                document.getElementById('modalTitle').innerText = "Add Fertilizer";
                document.getElementById('edit_id').value = "";
                document.querySelector('input[name="classification"]').value = "";
                document.querySelector('input[name="quantity"]').value = "";
                document.querySelector('input[name="amount"]').value = "";
            });
        });
    </script>
</body>
</html>