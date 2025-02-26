<?php
session_start();
require_once '../config/database.php';

// Ensure user is logged in as an HR Officer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    $_SESSION['error'] = "Access denied. Please log in as an HR Officer.";
    header("Location: ../public/login.php");
    exit();
}

include './includes/hr_navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Officer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container my-5">
    <h1 class="text-center">HR Officer Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <a href="view_applicants.php" class="btn btn-primary w-100">View Applicants</a>
        </div>
        <div class="col-md-4">
            <a href="announce_vacancy.php" class="btn btn-secondary w-100">Announce Vacancy</a>
        </div>
        <div class="col-md-4">
            <a href="post_exam_schedule.php" class="btn btn-success w-100">Post Exam Schedule</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <a href="approve_job_ranking.php" class="btn btn-warning w-100">Approve Job Ranking</a>
        </div>
        <div class="col-md-4">
            <a href="view_posts.php" class="btn btn-info w-100">View Posts</a>
        </div>
        <div class="col-md-4">
            <a href="view_feedback.php" class="btn btn-dark w-100">View Feedback</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <a href="fill_attendance.php" class="btn btn-primary w-100">Fill Attendance</a>
        </div>
        <div class="col-md-4">
            <a href="view_attendance.php" class="btn btn-secondary w-100">View Attendance</a>
        </div>
        <div class="col-md-4">
            <a href="view_employee_info.php" class="btn btn-success w-100">View Employee Info</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <a href="view_notifications.php" class="btn btn-danger w-100">View Notifications</a>
        </div>
        <div class="col-md-4">
            <a href="../public/login.php" class="btn btn-danger w-100">Logout</a>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
