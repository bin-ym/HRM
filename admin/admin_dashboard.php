<?php
session_start();
require_once '../config/database.php';

// Ensure user is logged in as an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['error'] = "Access denied. Please log in as an Admin.";
    header("Location: ../public/login.php");
    exit();
}

include('./includes/navbar.php');

// Fetch dynamic data (replace these with actual database queries)
$stmt = $conn->prepare("SELECT COUNT(*) AS total_jobs FROM jobs");
$stmt->execute();
$totalJobs = $stmt->fetch(PDO::FETCH_ASSOC)['total_jobs'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total_applicants FROM applicants");
$stmt->execute();
$totalApplicants = $stmt->fetch(PDO::FETCH_ASSOC)['total_applicants'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total_employees FROM employees");
$stmt->execute();
$totalEmployees = $stmt->fetch(PDO::FETCH_ASSOC)['total_employees'];

$stmt = $conn->prepare("SELECT COUNT(*) AS pending_tasks FROM tasks WHERE status = 'Pending'");
$stmt->execute();
$pendingTasks = $stmt->fetch(PDO::FETCH_ASSOC)['pending_tasks'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h1 class="text-center">Admin Dashboard</h1>
    
    <!-- Dashboard Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Jobs</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $totalJobs; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-header">Total Applicants</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $totalApplicants; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Employees</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $totalEmployees; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Pending Tasks</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $pendingTasks; ?></h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Action Buttons -->
    <div class="row">
        <div class="col-md-4">
            <a href="manage_users.php" class="btn btn-primary w-100">Manage Users</a>
        </div>
        <div class="col-md-4">
            <a href="view_jobs.php" class="btn btn-secondary w-100">View Job Listings</a>
        </div>
        <div class="col-md-4">
            <a href="view_applicants.php" class="btn btn-success w-100">View Applicants</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <a href="approve_requests.php" class="btn btn-warning w-100">Approve Requests</a>
        </div>
        <div class="col-md-4">
            <a href="generate_reports.php" class="btn btn-info w-100">Generate Reports</a>
        </div>
        <div class="col-md-4">
            <a href="manage_roles.php" class="btn btn-dark w-100">Manage Roles</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <a href="monitor_activity.php" class="btn btn-primary w-100">Monitor Activity</a>
        </div>
        <div class="col-md-4">
            <a href="manage_notifications.php" class="btn btn-secondary w-100">Manage Notifications</a>
        </div>
        <div class="col-md-4">
            <a href="view_feedback.php" class="btn btn-success w-100">View Feedback</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <a href="system_settings.php" class="btn btn-danger w-100">System Settings</a>
        </div>
        <div class="col-md-4">
            <a href="../public/login.php" class="btn btn-danger w-100">Logout</a>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
