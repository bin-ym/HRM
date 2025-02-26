<?php
session_start();
require_once '../config/database.php';

// Ensure user is logged in as a Manager
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Manager') {
    $_SESSION['error'] = "Access denied. Please log in as a Manager.";
    header("Location: ../public/login.php");
    exit();
}

include './includes/manager_navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Timesheets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container my-5">
    <h2 class="text-center">Review Employee Timesheets</h2>
    <p class="text-center">Check employee work hours and approve timesheets.</p>
    
    <!-- Placeholder for displaying timesheets -->
    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Hours Worked</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Sample Row -->
            <tr>
                <td>101</td>
                <td>John Doe</td>
                <td>2025-02-15</td>
                <td>8</td>
                <td>Pending</td>
                <td>
                    <button class="btn btn-success">Approve</button>
                    <button class="btn btn-danger">Reject</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

</body>
</html>
