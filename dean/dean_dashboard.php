<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Dean') {
    $_SESSION['error'] = "Please log in as a Dean to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$dean_id = $_SESSION['user_id'];

try {
    // Fetch Dean data
    $stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ? AND role = 'Dean'");
    $stmt->execute([$dean_id]);
    $dean = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dean) {
        $_SESSION['error'] = "Dean data not found.";
        header("Location: ../PUBLIC/login.php");
        exit();
    }

    // Count pending notifications (e.g., new applications)
    $notif_stmt = $conn->prepare("SELECT COUNT(*) FROM applications WHERE status = 'Pending'");
    $notif_stmt->execute();
    $pending_notifications = $notif_stmt->fetchColumn();

    // Count pending leave requests
    $leave_stmt = $conn->prepare("SELECT COUNT(*) FROM leave_requests WHERE status = 'pending'");
    $leave_stmt->execute();
    $pending_leaves = $leave_stmt->fetchColumn();

} catch (PDOException $e) {
    error_log("Dean Dashboard Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading dashboard: " . $e->getMessage();
    header("Location: ../PUBLIC/login.php");
    exit();
}

include('./includes/dean_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dean Dashboard - Debark University HRM</title>
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
    <div class="container my-5">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <div class="card bg-light shadow-sm">
                    <div class="card-body">
                        <h2 class="mb-3">Welcome, <?= htmlspecialchars($dean['username']); ?>!</h2>
                        <p class="text-muted">Dean Dashboard | Email: <?= htmlspecialchars($dean['email']); ?></p>
                        <div class="d-flex justify-content-center gap-4">
                            <span class="badge bg-warning text-dark status-badge">Pending Notifications: <?= $pending_notifications ?></span>
                            <span class="badge bg-info status-badge">Pending Leave Requests: <?= $pending_leaves ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Options -->
        <div class="row g-4">
            <!-- View Notifications -->
            <div class="col-md-3">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">View Notifications</h5>
                        <p class="card-text text-muted">Check application updates</p>
                        <a href="view_notifications.php" class="btn btn-primary btn-sm">View</a>
                    </div>
                </div>
            </div>
            <!-- View Posts -->
            <div class="col-md-3">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">View Posts</h5>
                        <p class="card-text text-muted">See current job openings</p>
                        <a href="view_posts.php" class="btn btn-primary btn-sm">View</a>
                    </div>
                </div>
            </div>
            <!-- Manage Department -->
            <div class="col-md-3">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Manage Department</h5>
                        <p class="card-text text-muted">Oversee department tasks</p>
                        <a href="manage_department.php" class="btn btn-primary btn-sm">Manage</a>
                    </div>
                </div>
            </div>
            <!-- Approve Employee Request -->
            <div class="col-md-3">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Approve Requests</h5>
                        <p class="card-text text-muted">Review employee leave requests</p>
                        <a href="approve_employee_request.php" class="btn btn-primary btn-sm">Approve</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>