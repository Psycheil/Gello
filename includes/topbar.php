<!-- includes/topbar.php -->
<nav class="navbar navbar-light bg-white shadow-sm px-3">
    <span class="navbar-brand mb-0 h5">MANFAS Dashboard</span>
    <div>
        <span class="me-3">Welcome, <?= $_SESSION['username'] ?? 'User' ?>!</span>
        <a href="/logout.php" class="btn btn-sm btn-outline-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</nav>
