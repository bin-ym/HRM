<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Department Head') {
    $_SESSION['error'] = "Please log in as a Department Head to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$dept_head_id = $_SESSION['user_id'];

try {
    // Fetch Department Head data
    $stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ? AND role = 'Department Head'");
    $stmt->execute([$dept_head_id]);
    $dept_head = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dept_head) {
        $_SESSION['error'] = "Department Head data not found.";
        header("Location: ../PUBLIC/login.php");
        exit();
    }

    // Count pending employee requests
    $request_stmt = $conn->prepare("SELECT COUNT(*) FROM leave_requests WHERE status = 'pending'");
    $request_stmt->execute();
    $pending_requests = $request_stmt->fetchColumn();

    // Count employees without attendance or marked Absent for today
    $attendance_stmt = $conn->prepare("SELECT COUNT(*) 
                                       FROM users u 
                                       LEFT JOIN attendance a ON u.id = a.employee_id AND a.date = CURDATE() 
                                       WHERE u.role = 'Employee' AND (a.status IS NULL OR a.status = 'Absent')");
    $attendance_stmt->execute();
    $pending_attendance = $attendance_stmt->fetchColumn();

} catch (PDOException $e) {
    error_log("Department Head Dashboard Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading dashboard: " . $e->getMessage();
    header("Location: ../PUBLIC/login.php");
    exit();
}

include('./includes/department_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Head Dashboard - Debark University HRM</title>
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
                        <h2 class="mb-3">Welcome, <?= htmlspecialchars($dept_head['username']); ?>!</h2>
                        <p class="text-muted">Department Head Dashboard | Email: <?= htmlspecialchars($dept_head['email']); ?></p>
                        <div class="d-flex justify-content-center gap-4">
                            <span class="badge bg-warning text-dark status-badge">Pending Requests: <?= $pending_requests ?></span>
                            <span class="badge bg-info status-badge">Pending Attendance: <?= $pending_attendance ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Options -->
        <div class="row g-4">
            <!-- Submit Job Ranking -->
            <div class="col-md-3">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Submit Job Ranking</h5>
                        <p class="card-text text-muted">Rank job applicants</p>
                        <a href="submit_job_ranking.php" class="btn btn-primary btn-sm">Rank</a>
                    </div>
                </div>
            </div>
            <!-- Employee Requests -->
            <div class="col-md-3">
                <div class="card dashboard-card shadow-sm">
                     <div class="card-body text-center">
                        <h5 class="card-title">Employee Requests</h5>
                        <p class="card-text text-muted">Approve leave requests</p>
                        <a href="employee_requests.php" class="btn btn-primary btn-sm">Review</a>
                    </div>
                </div>
            </div>
            <!-- View Feedback -->
            <div class="col-md-3">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">View Feedback</h5>
                        <p class="card-text text-muted">See employee feedback</p>
                        <a href="view_feedback.php" class="btn btn-primary btn-sm">View</a>
                    </div>
                </div>
            </div>
            <!-- Fill Attendance -->
            <div class="col-md-3">
                <div class="card dashboard-card shadow-sm">
                     <div class="card-body text-center">
                        <h5 class="card-title">Fill Attendance</h5>
                        <p class="card-text text-muted">Record employee attendance</p>
                        <a href="fill_attendance.php" class="btn btn-primary btn-sm">Fill</a>
                    </div>
                </div>
            </div>
            <!-- View Employee Info -->
            <div class="col-md-3">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">View Employee Info</h5>
                        <p class="card-text text-muted">See employee details</p>
                        <a href="view_employee_info.php" class="btn btn-primary btn-sm">View</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>