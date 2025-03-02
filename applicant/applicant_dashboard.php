<?php
// Start session only here
session_start();
require_once('../config/database.php');
require_once('./includes/applicant_navbar.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Applicant') {
    $_SESSION['error'] = "Please log in to access the dashboard.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$applicant_id = $_SESSION['user_id'];

try {
    // READ: Fetch applicant data from users table (Page 25)
    $stmt = $conn->prepare("SELECT username, email, last_login FROM users WHERE id = ?");
    $stmt->execute([$applicant_id]);
    $applicant = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$applicant) {
        $_SESSION['error'] = "Applicant data not found.";
        header("Location: ../PUBLIC/login.php");
        exit();
    }

    // UPDATE: Set last_login if null
    if (empty($applicant['last_login'])) {
        $last_login = date('Y-m-d H:i:s');
        $update_stmt = $conn->prepare("UPDATE users SET last_login = ? WHERE id = ?");
        $update_stmt->execute([$last_login, $applicant_id]);
        $applicant['last_login'] = $last_login;
    }
    $_SESSION['last_login'] = $applicant['last_login'];

    // READ: Fetch pending applications (Page 5, applications table)
    $pending_stmt = $conn->prepare("SELECT COUNT(*) FROM applications WHERE applicant_id = ? AND status = 'Pending'");
    $pending_stmt->execute([$applicant_id]);
    $pending_applications = $pending_stmt->fetchColumn();

    // READ: Fetch upcoming exams (Page 12, exam_schedules table)
    $exams_stmt = $conn->prepare("SELECT COUNT(*) FROM exam_schedules WHERE applicant_id = ? AND date > NOW()");
    $exams_stmt->execute([$applicant_id]);
    $upcoming_exams = $exams_stmt->fetchColumn();

} catch (PDOException $e) {
    error_log("Dashboard Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading dashboard. Please try again.";
    header("Location: ../PUBLIC/login.php");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
                    <h2 class="mb-3">Welcome, <?= htmlspecialchars($applicant['username']); ?>!</h2>
                    <p class="text-muted">Applicant Dashboard | Email: <?= htmlspecialchars($applicant['email']); ?></p>
                    <div class="d-flex justify-content-center gap-4">
                        <span class="badge bg-warning text-dark status-badge">Pending Applications: <?= $pending_applications ?></span>
                        <span class="badge bg-info status-badge">Upcoming Exams: <?= $upcoming_exams ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Options -->
    <div class="row g-4">
    <div class="col-md-4 col-lg-3">
    <div class="card dashboard-card shadow-sm">
        <img src="../assets/images/applications_icon.png" class="card-img-top p-3" alt="Applications" style="max-height: 150px; object-fit: contain;">
        <div class="card-body text-center">
            <h5 class="card-title">My Applications</h5>
            <p class="card-text text-muted">View your submitted applications</p>
            <a href="view_applications.php" class="btn btn-primary btn-sm">View Applications</a>
        </div>
    </div>
</div>

        <div class="col-md-4 col-lg-3">
            <div class="card dashboard-card shadow-sm">
                <img src="../assets/images/vacancy_icon.png" class="card-img-top p-3" alt="Vacancies" style="max-height: 150px; object-fit: contain;">
                <div class="card-body text-center">
                    <h5 class="card-title">Job Vacancies</h5>
                    <p class="card-text text-muted">Browse and apply for available positions</p>
                    <a href="apply_vacancy.php" class="btn btn-primary btn-sm">Apply Now</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card dashboard-card shadow-sm">
                <img src="../assets/images/results_icon.png" class="card-img-top p-3" alt="Results" style="max-height: 150px; object-fit: contain;">
                <div class="card-body text-center">
                    <h5 class="card-title">Exam Results</h5>
                    <p class="card-text text-muted">Check your examination performance</p>
                    <a href="view_exam_results.php" class="btn btn-primary btn-sm">View Results</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card dashboard-card shadow-sm">
                <img src="../assets/images/posts_icon.png" class="card-img-top p-3" alt="Posts" style="max-height: 150px; object-fit: contain;">
                <div class="card-body text-center">
                    <h5 class="card-title">Announcements</h5>
                    <p class="card-text text-muted">Stay updated with latest news</p>
                    <a href="view_posts.php" class="btn btn-primary btn-sm">View Posts</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card dashboard-card shadow-sm">
                <img src="../assets/images/feedback_icon.png" class="card-img-top p-3" alt="Feedback" style="max-height: 150px; object-fit: contain;">
                <div class="card-body text-center">
                    <h5 class="card-title">Feedback</h5>
                    <p class="card-text text-muted">Share your thoughts and suggestions</p>
                    <a href="send_feedback.php" class="btn btn-primary btn-sm">Send Feedback</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Quick Stats</h5>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h6>Applications Submitted</h6>
                            <p class="fw-bold"><?= $pending_applications ?></p>
                        </div>
                        <div class="col-md-4">
                            <h6>Exams Scheduled</h6>
                            <p class="fw-bold"><?= $upcoming_exams ?></p>
                        </div>
                        <div class="col-md-4">
                            <h6>Last Login</h6>
                            <p class="fw-bold"><?= date('F j, Y', strtotime($applicant['last_login'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>