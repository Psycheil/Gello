<?php
ob_start(); // Add this at the very top
include_once("../includes/sidebar.php"); // Ensure sidebar is always visible

// Define months array at the top level
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

// Database connection (update with your credentials)
$conn = new mysqli("localhost", "root", "", "manfas_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for Add/Edit
// In the POST handling section, replace the date assignment with current date
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $membership_fee = $_POST['membership_fee'];
    $cbu = $_POST['cbu'];
    $date = date('Y-m-d'); // Automatically use current date
    $monthly_dues = [];
    for ($i = 1; $i <= 12; $i++) {
        $monthly_dues[] = $_POST["month_$i"] !== "" ? $_POST["month_$i"] : 0;
    }
    $monthly_due_json = json_encode($monthly_dues);

    if (isset($_POST['edit_id']) && $_POST['edit_id'] !== "") {
        // Edit existing payment
        $edit_id = intval($_POST['edit_id']);
        $stmt = $conn->prepare("UPDATE membership_payments SET name=?, monthly_due=?, membership_fee=?, cbu=? WHERE id=?");
        $stmt->bind_param("ssddi", $name, $monthly_due_json, $membership_fee, $cbu, $edit_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Add new payment
        $stmt = $conn->prepare("INSERT INTO membership_payments (name, monthly_due, membership_fee, cbu) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdd", $name, $monthly_due_json, $membership_fee, $cbu);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: membership_fee.php");
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM membership_payments WHERE id=$delete_id");
    header("Location: membership_fee.php");
    exit;
}

// Fetch payments
$result = $conn->query("SELECT * FROM membership_payments ORDER BY name ASC, date_added DESC");

// Add this after the existing database queries
$members_result = $conn->query("SELECT name FROM members ORDER BY name ASC");
$members = [];
while($member = $members_result->fetch_assoc()) {
    $members[] = $member['name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Membership Fee Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .peso:before { content: "₱"; margin-right: 2px; }
        .sidebar { z-index: 1040; } /* Ensure sidebar is above modal */
        .table { font-size: 0.85rem; } /* Smaller font size */
        .table td, .table th { padding: 0.4rem; } /* Reduced padding */
        .container { padding-left: 0; padding-right: 0; } /* Reduce container padding */
    </style>
</head>
<body class="bg-light">
    <div class="container py-3" style="margin-left:270px; width: calc(100% - 280px);">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Membership Fee Payments</h4>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                Add Payment
            </button>
        </div>

        <!-- Add search bar -->
        <div class="mb-3">
            <input type="text" class="form-control form-control-sm w-25" id="searchBar" placeholder="Search payments...">
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle bg-white text-center">
                <thead class="table-success">
                    <tr>
                        <th>Name</th>
                        <th>Monthly Due</th>
                        <th>Membership Fee</th>
                        <th>CBU (₱)</th>
                        <th>Date Added</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): 
                        $monthly_dues = json_decode($row['monthly_due'], true);
                        if (!is_array($monthly_dues)) $monthly_dues = array_fill(0,12,0);
                        $monthly_total = array_sum($monthly_dues);
                        $paid_months = [];
                        foreach ($monthly_dues as $i => $amt) {
                            if (floatval($amt) > 0) $paid_months[] = $months[$i];
                        }
                    ?>
                    <tr>
                        <td class="text-start"><?= htmlspecialchars($row['name']) ?></td>
                        <td class="text-center">
                            <div><span class="peso"><?= number_format($monthly_total, 2) ?></span></div>
                            <?php if (!empty($paid_months)): ?>
                                <div style="font-size:0.92em;color:#388e3c;">PAID: <?= implode(', ', $paid_months) ?></div>
                            <?php else: ?>
                                <div style="font-size:0.92em;color:#b71c1c;">No payments</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="peso"><?= number_format($row['membership_fee'], 2) ?></div>
                            <div style="font-size:0.92em;">/ YEARLY</div>
                        </td>
                        <td class="peso"><?= number_format($row['cbu'], 2) ?></td>
                        <td><?= date('m/d/y h:i A', strtotime($row['date_added'])) ?></td>
                        <td><?= date('m/d/y h:i A', strtotime($row['last_updated'])) ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-btn" 
                                data-id="<?= $row['id'] ?>"
                                data-name="<?= htmlspecialchars($row['name']) ?>"
                                data-membership_fee="<?= $row['membership_fee'] ?>"
                                data-cbu="<?= $row['cbu'] ?>"
                                data-monthly_due='<?= htmlspecialchars($row['monthly_due']) ?>'
                                >Edit</button>
                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this payment?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST">
          <input type="hidden" name="edit_id" id="edit_id">
          <div class="modal-header">
            <h5 class="modal-title" id="addPaymentModalLabel">Add/Edit Membership Payment</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control" id="name" name="name" list="membersList" required>
                  <datalist id="membersList">
                      <?php foreach($members as $member): ?>
                          <option value="<?= htmlspecialchars($member) ?>">
                      <?php endforeach; ?>
                  </datalist>
              </div>
              <div class="mb-3">
                  <label class="form-label">Monthly Due (₱ per month)</label>
                  <div class="row">
                      <?php foreach ($months as $idx => $m): ?>
                      <div class="col-3 mb-2">
                          <div class="input-group">
                              <span class="input-group-text">₱</span>
                              <input type="number" step="0.01" min="0" class="form-control" name="month_<?= $idx+1 ?>" id="month_<?= $idx+1 ?>" placeholder="<?= $m ?>">
                          </div>
                      </div>
                      <?php endforeach; ?>
                  </div>
              </div>
              <div class="mb-3">
                  <label for="membership_fee" class="form-label">Membership Fee (₱)</label>
                  <div class="input-group">
                      <span class="input-group-text">₱</span>
                      <input type="number" step="0.01" min="0" class="form-control" id="membership_fee" name="membership_fee" required>
                  </div>
              </div>
              <div class="mb-3">
                  <label for="cbu" class="form-label">CBU (₱)</label>
                  <div class="input-group">
                      <span class="input-group-text">₱</span>
                      <input type="number" step="0.01" min="0" class="form-control" id="cbu" name="cbu" required>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Save Payment</button>
          </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        // Add search functionality
        document.getElementById('searchBar').addEventListener('input', function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // Edit button handler
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('addPaymentModalLabel').innerText = "Edit Membership Payment";
                document.getElementById('edit_id').value = this.dataset.id;
                document.getElementById('name').value = this.dataset.name;
                document.getElementById('membership_fee').value = this.dataset.membership_fee;
                document.getElementById('cbu').value = this.dataset.cbu;
                // Remove any date-related code here
                let monthly = JSON.parse(this.dataset.monthly_due);
                for(let i=1; i<=12; i++) {
                    document.getElementById('month_'+i).value = monthly[i-1] > 0 ? monthly[i-1] : '';
                }
                var modal = new bootstrap.Modal(document.getElementById('addPaymentModal'));
                modal.show();
            });
        });
    
        // Reset modal on open for add
        document.querySelector('[data-bs-target="#addPaymentModal"]').addEventListener('click', function() {
            document.getElementById('addPaymentModalLabel').innerText = "Add Membership Payment";
            document.getElementById('edit_id').value = "";
            document.getElementById('name').value = "";
            document.getElementById('membership_fee').value = "";
            document.getElementById('cbu').value = "";
            for(let i=1; i<=12; i++) {
                document.getElementById('month_'+i).value = "";
            }
        });
        </script>
    </body>
    </html>