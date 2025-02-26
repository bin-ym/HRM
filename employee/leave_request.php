<?php
session_start();
require_once '../config/database.php';

// Ensure the user is logged in as an Employee
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    $_SESSION['error'] = "Please log in to access the dashboard.";
    header("Location: ../public/login.php");
    exit();
}

$employee_id = $_SESSION['user_id']; // Using user_id stored from login

try {
    // Updated query with correct column names
    $sql_leave = "SELECT request_id, leave_type, start_date, end_date, status, reason FROM leave_requests WHERE employee_id = ?";
    $stmt_leave = $conn->prepare($sql_leave);
    $stmt_leave->execute([$employee_id]);
    $leave_requests = $stmt_leave->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Leave Requests - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="employee_dashboard.php">HRM Employee</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="employee_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="update_profile.php">Update Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="leave_request.php">Leave Request</a></li>
                    <li class="nav-item"><a class="nav-link" href="salary_details.php">Salary Details</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="../public/logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="text-center">Your Leave Requests</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leave_requests as $leave): ?>
                    <tr>
                        <td><?= htmlspecialchars($leave['leave_type']); ?></td>
                        <td><?= htmlspecialchars($leave['start_date']); ?></td>
                        <td><?= htmlspecialchars($leave['end_date']); ?></td>
                        <td><?= htmlspecialchars($leave['status']); ?></td>
                        <td><?= htmlspecialchars($leave['reason']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
