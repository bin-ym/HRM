<?php
session_start();
require_once '../config/database.php';

// Ensure user is logged in as a Manager
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Manager') {
    $_SESSION['error'] = "Please log in to access the dashboard.";
    header("Location: ../public/login.php");
    exit();
}

$manager_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <?php include './includes/manager_navbar.php'; ?>

    <div class="container my-5">
        <h1 class="text-center">Manager Dashboard</h1>

        <div class="row">
            <div class="col-md-4">
                <a href="approve_leave.php" class="btn btn-primary w-100">Approve Leave Requests</a>
            </div>
            <div class="col-md-4">
                <a href="review_timesheets.php" class="btn btn-secondary w-100">Review Timesheets</a>
            </div>
            <div class="col-md-4">
                <a href="performance_evaluation.php" class="btn btn-success w-100">Performance Evaluation</a>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-4">
                <a href="assign_tasks.php" class="btn btn-info w-100">Assign Tasks</a>
            </div>
            <div class="col-md-4">
                <a href="provide_feedback.php" class="btn btn-warning w-100">Provide Feedback</a>
            </div>
            <div class="col-md-4">
                <a href="../public/login.php" class="btn btn-danger w-100">Logout</a>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
