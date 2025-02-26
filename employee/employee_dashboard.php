<?php
session_start();
require_once '../config/database.php'; // Database connection

// Ensure the user is logged in as an Employee
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    $_SESSION['error'] = "Please log in to access the dashboard.";
    header("Location: ../public/login.php");
    exit();
}

$employee_id = $_SESSION['user_id']; // Using user_id stored from login

try {
    // Fetch employee details
    $sql_employee = "SELECT * FROM employees WHERE employee_id = ?";
    $stmt_employee = $conn->prepare($sql_employee);
    $stmt_employee->execute([$employee_id]);
    $employee = $stmt_employee->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        throw new Exception("Employee not found. Please contact the administrator.");
    }

    // Fetch leave requests
    $sql_leave = "SELECT leave_type, start_date, end_date, status, reason FROM leave_requests WHERE employee_id = ?";
    $stmt_leave = $conn->prepare($sql_leave);
    $stmt_leave->execute([$employee_id]);
    $leave_requests = $stmt_leave->fetchAll(PDO::FETCH_ASSOC);

    // Fetch salary details (Correct columns: basic_salary, bonuses, deductions, net_pay)
    $sql_salary = "SELECT payment_date, basic_salary, bonuses, deductions, net_pay FROM salary WHERE employee_id = ?";
    $stmt_salary = $conn->prepare($sql_salary);
    $stmt_salary->execute([$employee_id]);
    $salary_details = $stmt_salary->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: ../public/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body>
    <!-- Employee Dashboard Navbar -->
    <?php include('./employee_navbar.php'); ?>

    <div class="container my-5">
        <h1 class="text-center">Welcome, <?= htmlspecialchars($employee['first_name'] ?? 'Employee'); ?>!</h1>
        <p class="text-center">Your Employee ID: <?= htmlspecialchars($employee['employee_id'] ?? 'N/A'); ?></p>

        <!-- Display Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Dashboard Links -->
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Update Profile</h5>
                        <p class="card-text">View and update your personal information.</p>
                        <a href="update_profile.php" class="btn btn-primary">Go to Profile</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Submit Leave Request</h5>
                        <p class="card-text">Request for leave and check approval status.</p>
                        <a href="leave_request.php" class="btn btn-warning">Request Leave</a>
                    </div>
                </div> 
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Salary Details</h5>
                        <p class="card-text">View your salary breakdown and payment history.</p>
                        <a href="salary_details.php" class="btn btn-success">View Salary</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
