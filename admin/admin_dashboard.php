<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['error'] = "Please log in as an Admin to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ? AND role = 'Admin'");
    $stmt->execute([$admin_id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        $_SESSION['error'] = "Admin data not found.";
        header("Location: ../PUBLIC/login.php");
        exit();
    }

    $total_users_stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE status = 'Active'");
    $total_users_stmt->execute();
    $total_users = $total_users_stmt->fetchColumn();

    $pending_users_stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE status = 'Inactive'");
    $pending_users_stmt->execute();
    $pending_users = $pending_users_stmt->fetchColumn();

} catch (PDOException $e) {
    error_log("Admin Dashboard Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading dashboard: " . $e->getMessage();
    header("Location: ../PUBLIC/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        .dashboard-card { transition: transform 0.2s; }
        .dashboard-card:hover { transform: scale(1.05); }
        .status-badge { font-size: 0.9rem; }
    </style>
</head>
<body>
    <?php include('./includes/admin_navbar.php'); ?>

    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <div class="card bg-light shadow-sm">
                    <div class="card-body">
                        <h2 class="mb-3">Welcome, <?= htmlspecialchars($admin['username']); ?>!</h2>
                        <p class="text-muted">Admin Dashboard | Email: <?= htmlspecialchars($admin['email']); ?></p>
                        <div class="d-flex justify-content-center gap-4">
                            <span class="badge bg-success status-badge">Active Users: <?= $total_users ?></span>
                            <span class="badge bg-warning text-dark status-badge">Pending Accounts: <?= $pending_users ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4 col-lg-3">
                <div class="card dashboard-card shadow-sm">
                    <img src="../assets/images/manage_users_icon.png" class="card-img-top p-3" alt="Manage Users" style="max-height: 150px; object-fit: contain;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Manage Users</h5>
                        <p class="card-text text-muted">View, edit, or delete user accounts</p>
                        <a href="manage_users.php" class="btn btn-primary btn-sm">Manage Users</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>