<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    $_SESSION['error'] = "Please log in as an Employee to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$employee_id = $_SESSION['user_id'];

try {
    // Fetch employee data from users table
    $stmt = $conn->prepare("SELECT username, email, last_login FROM users WHERE id = ? AND role = 'Employee'");
    $stmt->execute([$employee_id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        $_SESSION['error'] = "Employee data not found.";
        header("Location: ../PUBLIC/login.php");
        exit();
    }

    // Fetch additional employee details (if using employees table)
    $emp_stmt = $conn->prepare("SELECT first_name, last_name, position, hire_date FROM employees WHERE employee_id = ?");
    $emp_stmt->execute([$employee_id]);
    $emp_details = $emp_stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch pending leave requests
    $leave_stmt = $conn->prepare("SELECT COUNT(*) FROM leave_requests WHERE employee_id = ? AND status = 'pending'");
    $leave_stmt->execute([$employee_id]);
    $pending_leaves = $leave_stmt->fetchColumn();

} catch (PDOException $e) {
    error_log("Employee Dashboard Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading dashboard: " . $e->getMessage();
    header("Location: ../PUBLIC/login.php");
    exit();
}

include('employee_navbar.php'); // Employee-specific navbar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - Debark University HRM</title>
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
                        <h2 class="mb-3">Welcome, <?= htmlspecialchars($employee['username']); ?>!</h2>
                        <p class="text-muted">Employee Dashboard | Email: <?= htmlspecialchars($employee['email']); ?></p>
                        <?php if ($emp_details): ?>
                            <p class="text-muted">Name: <?= htmlspecialchars($emp_details['first_name'] . ' ' . $emp_details['last_name']); ?> | Position: <?= htmlspecialchars($emp_details['position']); ?></p>
                        <?php endif; ?>
                        <div class="d-flex justify-content-center gap-4">
                            <span class="badge bg-warning text-dark status-badge">Pending Leave Requests: <?= $pending_leaves ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Options -->
        <div class="row g-4">
            <!-- Submit Leave Request -->
            <div class="col-md-4 col-lg-3">
                <div class="card dashboard-card shadow-sm">
                    <img src="../assets/images/leave_icon.png" class="card-img-top p-3" alt="Leave Request" style="max-height: 150px; object-fit: contain;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Leave Request</h5>
                        <p class="card-text text-muted">Submit a new leave request</p>
                        <a href="leave_request.php" class="btn btn-primary btn-sm">Request Leave</a>
                    </div>
                </div>
            </div>

            <!-- View Salary Details -->
            <div class="col-md-4 col-lg-3">
                <div class="card dashboard-card shadow-sm">
                    <img src="../assets/images/salary_icon.png" class="card-img-top p-3" alt="Salary" style="max-height: 150px; object-fit: contain;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Salary Details</h5>
                        <p class="card-text text-muted">View your salary information</p>
                        <a href="salary_details.php" class="btn btn-primary btn-sm">View Salary</a>
                    </div>
                </div>
            </div>

            <!-- Update Profile -->
            <div class="col-md-4 col-lg-3">
                <div class="card dashboard-card shadow-sm">
                    <img src="../assets/images/profile_icon.png" class="card-img-top p-3" alt="Profile" style="max-height: 150px; object-fit: contain;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Update Profile</h5>
                        <p class="card-text text-muted">Edit your personal details</p>
                        <a href="update_profile.php" class="btn btn-primary btn-sm">Update Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>