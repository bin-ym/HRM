<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    $_SESSION['error'] = "Please log in as an HR Officer to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$hr_officer_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ? AND role = 'HR Officer'");
    $stmt->execute([$hr_officer_id]);
    $hr_officer = $stmt->fetch(PDO::FETCH_ASSOC);

    $open_vacancies = $conn->query("SELECT COUNT(*) FROM job_openings WHERE status = 'Open'")->fetchColumn();
    $pending_applications = $conn->query("SELECT COUNT(*) FROM applications WHERE status = 'Pending'")->fetchColumn();
} catch (PDOException $e) {
    error_log("Dashboard Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading dashboard.";
}

include('./includes/hr_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Officer Dashboard - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">HR Officer Dashboard</h1>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <div class="card mb-4">
            <div class="card-body text-center">
                <h4>Welcome, <?= htmlspecialchars($hr_officer['username'] ?? 'HR Officer'); ?>!</h4>
                <p>Open Vacancies: <strong><?= $open_vacancies ?? 0; ?></strong> | Pending Applications: <strong><?= $pending_applications ?? 0; ?></strong></p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">View Applicants</h5>
                        <a href="view_applicants.php" class="btn btn-primary w-100">View Applicants</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Announce Vacancy</h5>
                        <a href="announce_vacancy.php" class="btn btn-secondary w-100">Announce Vacancy</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Post Exam Schedule</h5>
                        <a href="post_exam_schedule.php" class="btn btn-success w-100">Post Exam Schedule</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Approve Job Ranking</h5>
                        <a href="approve_job_ranking.php" class="btn btn-warning w-100">Approve Job Ranking</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">View Posts</h5>
                        <a href="view_posts.php" class="btn btn-info w-100">View Posts</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">View Feedback</h5>
                        <a href="view_feedback.php" class="btn btn-dark w-100">View Feedback</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Fill Attendance</h5>
                        <a href="fill_attendance.php" class="btn btn-primary w-100">Fill Attendance</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">View Attendance</h5>
                        <a href="view_attendance.php" class="btn btn-secondary w-100">View Attendance</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">View Employee Info</h5>
                        <a href="view_employee_info.php" class="btn btn-success w-100">View Employee Info</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">View Notifications</h5>
                        <a href="view_notifications.php" class="btn btn-danger w-100">View Notifications</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>