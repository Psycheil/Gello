<?php
ob_start(); // Add this at the very top to fix headers issue
include_once("../includes/sidebar.php");

// Database connection
$conn = new mysqli("localhost", "root", "", "manfas_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM livestock WHERE id=$delete_id");
    header("Location: livestock.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $livestock_type = $_POST['livestock_type'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $current_date = date('Y-m-d');

    if (isset($_POST['edit_id']) && $_POST['edit_id'] !== "") {
        // Edit existing record
        $edit_id = intval($_POST['edit_id']);
        $stmt = $conn->prepare("UPDATE livestock SET name=?, livestock_type=?, gender=?, age=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $livestock_type, $gender, $age, $edit_id);
    } else {
        // Add new record
        $stmt = $conn->prepare("INSERT INTO livestock (name, livestock_type, gender, age, date_added) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $livestock_type, $gender, $age, $current_date);
    }
    
    $stmt->execute();
    $stmt->close();
    header("Location: livestock.php");
    exit;
}

// Fetch livestock records
$result = $conn->query("SELECT * FROM livestock ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Livestock Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { z-index: 1040; }
        .container { padding-left: 0; padding-right: 0; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4" style="margin-left:270px; width: calc(100% - 280px);">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Livestock Services</h4>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addLivestockModal">
                Add Livestock
            </button>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-success text-center">
                            <tr>
                                <th>Members Name</th>
                                <th>Livestock</th>
                                <th>Livestocks Gender</th>
                                <th>Livestock Age</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['livestock_type']) ?></td>
                                <td><?= htmlspecialchars($row['gender']) ?></td>
                                <td><?= htmlspecialchars($row['age']) ?></td>
                                <td><?= date('m/d/y', strtotime($row['date_added'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-btn" 
                                        data-id="<?= $row['id'] ?>"
                                        data-name="<?= htmlspecialchars($row['name']) ?>"
                                        data-livestock="<?= htmlspecialchars($row['livestock_type']) ?>"
                                        data-gender="<?= htmlspecialchars($row['gender']) ?>"
                                        data-age="<?= htmlspecialchars($row['age']) ?>"
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

    <!-- Add Livestock Modal -->
    <div class="modal fade" id="addLivestockModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST">
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Livestock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Members Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Livestock Type</label>
                        <input type="text" class="form-control" name="livestock_type" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Livestocks Gender</label>
                        <select class="form-control" name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Livestock Age</label>
                        <input type="text" class="form-control" name="age" placeholder="e.g., 2 months, 1 year" required>
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
        // Edit button functionality
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('modalTitle').innerText = "Edit Livestock";
                    document.getElementById('edit_id').value = this.dataset.id;
                    document.querySelector('input[name="name"]').value = this.dataset.name;
                    document.querySelector('input[name="livestock_type"]').value = this.dataset.livestock;
                    document.querySelector('select[name="gender"]').value = this.dataset.gender;
                    document.querySelector('input[name="age"]').value = this.dataset.age;
                    new bootstrap.Modal(document.getElementById('addLivestockModal')).show();
                });
            });

            // Add button reset functionality
            document.querySelector('[data-bs-target="#addLivestockModal"]').addEventListener('click', function() {
                document.getElementById('modalTitle').innerText = "Add Livestock";
                document.getElementById('edit_id').value = "";
                document.querySelector('input[name="name"]').value = "";
                document.querySelector('input[name="livestock_type"]').value = "";
                document.querySelector('select[name="gender"]').value = "Male";
                document.querySelector('input[name="age"]').value = "";
            });
        });
    </script>
</body>
</html>