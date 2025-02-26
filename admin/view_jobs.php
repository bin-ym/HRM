<?php
session_start();
require_once '../config/database.php';

// Ensure user is logged in as an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['error'] = "Access denied. Please log in as an Admin.";
    header("Location: ../public/login.php");
    exit();
}

// Fetch the list of jobs from the database
$query = "SELECT job_id, job_title, job_department, job_status, job_created_at FROM jobs"; // Updated query to match your table
$stmt = $conn->prepare($query);
$stmt->execute();
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

include './includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Jobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container my-5">
    <h1 class="text-center">Job Listings</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Department</th>
                <th>Status</th>
                <th>Posted Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jobs as $job): ?>
                <tr>
                    <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                    <td><?php echo htmlspecialchars($job['job_department']); ?></td>
                    <td><?php echo htmlspecialchars($job['job_status']); ?></td>
                    <td><?php echo htmlspecialchars($job['job_created_at']); ?></td>
                    <td>
                        <a href="view_job_detail.php?id=<?php echo $job['job_id']; ?>" class="btn btn-info btn-sm">View Details</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
