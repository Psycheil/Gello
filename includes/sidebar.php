<?php
$currentPage = basename($_SERVER['PHP_SELF']); // Detect current page
?>

<!-- sidebar.php -->
<div class="p-3 vh-100" style="width: 250px; position: fixed; background-color: #1B5E20;">
    <h5 class="mb-4 text-white fw-semibold"><i class="bi bi-house-door me-2"></i>MANFAS</h5>
    <ul class="nav flex-column">
        <li class="nav-item mb-1">
            <a href="../members/manage_members.php" 
               class="nav-link <?= $currentPage === 'manage_members.php' ? 'active-link' : 'text-white' ?>">
                <i class="bi bi-people-fill me-2"></i>Members
            </a>
        </li>
        
        <!-- Finance Collapse -->
        <li class="nav-item mb-1">
            <a class="nav-link text-white" data-bs-toggle="collapse" href="#financeCollapse"
               role="button" aria-expanded="false" aria-controls="financeCollapse">
                <i class="bi bi-cash-coin me-2"></i>Finance
                <i class="bi bi-chevron-down float-end"></i>
            </a>
            <div class="collapse ps-4" id="financeCollapse">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../finance/membership_fee.php">
                            <i class="bi bi-credit-card me-2"></i>Membership Fee
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../finance/services_revenue.php">
                            <i class="bi bi-receipt me-2"></i>Services Revenue
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Services Collapse -->
        <li class="nav-item mb-1">
            <a class="nav-link text-white" data-bs-toggle="collapse" href="#servicesCollapse"
               role="button" aria-expanded="false" aria-controls="servicesCollapse">
                <i class="bi bi-gear me-2"></i>Services
                <i class="bi bi-chevron-down float-end"></i>
            </a>
            <div class="collapse ps-4" id="servicesCollapse">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../services/livestock.php">
                            <i class="bi bi-egg me-2"></i>Livestock
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../services/machineries.php">
                            <i class="bi bi-truck me-2"></i>Machineries Rental
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../services/fertilizer.php">
                            <i class="bi bi-basket me-2"></i>Fertilizer
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Reports -->
        <li class="nav-item mb-1">
            <a class="nav-link text-white" href="../reports/index.php">
                <i class="bi bi-file-earmark-text me-2"></i>Reports
            </a>
        </li>

        <li class="nav-item">
            <a href="../logout.php" 
               class="nav-link <?= $currentPage === 'logout.php' ? 'active-link' : 'text-white' ?>">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </a>
        </li>
    </ul>
</div>

<!-- Add this to your main page's <head> section -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Add this before closing body tag -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .nav-link {
        color: #E0E0E0 !important;
        padding: 0.5rem 0.75rem;
        border-radius: 4px;
        font-size: 0.95rem;
        transition: background-color 0.2s ease;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.05);
        text-decoration: none;
    }

    /* === OPTION 1: Deep Aesthetic Green === */
    .active-link {
        background-color: #2E7D32;
        font-weight: 500;
        color: #FFFFFF !important;
    }

    /* === OPTION 2: Sidebar Blue === */
    /* 
    .active-link {
        background-color: #1565C0;
        font-weight: 500;
        color: #FFFFFF !important;
    }
    */

    .active-link i {
        color: #FFFFFF !important;
    }
</style>
