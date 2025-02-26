<?php
session_start();
require_once('../config/database.php');
require_once('./includes/applicant_navbar.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Applicant') {
    $_SESSION['error'] = "Please log in to access the dashboard.";
    header("Location: ../public/login.php");
    exit();
}

$applicant_id = $_SESSION['user_id'];
// Fetch applicant data from the database
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$applicant_id]);
$applicant = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if applicant data is found
if (!$applicant) {
    $_SESSION['error'] = "Applicant data not found.";
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="text-center">Welcome, <?= htmlspecialchars($applicant['username']); ?>!</h2>

    <div class="row">
        <!-- View Exam Schedule -->
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">View Exam Schedule</h5>
                    <p class="card-text">Check the schedule for upcoming exams.</p>
                    <a href="view_exam_schedule.php" class="btn btn-primary">View Schedule</a>
                </div>
            </div>
        </div>

        <!-- Apply for Vacancy -->
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Apply for Vacancy</h5>
                    <p class="card-text">Browse and apply for job vacancies.</p>
                    <a href="apply_vacancy.php" class="btn btn-primary">Apply Now</a>
                </div>
            </div>
        </div>

        <!-- View Exam Results -->
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">View Exam Results</h5>
                    <p class="card-text">Check your exam results and status.</p>
                    <a href="view_exam_results.php" class="btn btn-primary">View Results</a>
                </div>
            </div>
        </div>

        <!-- View Posts -->
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">View Posts</h5>
                    <p class="card-text">Read announcements and updates.</p>
                    <a href="view_posts.php" class="btn btn-primary">View Posts</a>
                </div>
            </div>
        </div>

        <!-- Send Feedback -->
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Send Feedback</h5>
                    <p class="card-text">Share your feedback with us.</p>
                    <a href="send_feedback.php" class="btn btn-primary">Send Feedback</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include('../includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
