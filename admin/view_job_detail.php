<?php
session_start();
require_once '../config/database.php';

// Ensure user is logged in as an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['error'] = "Access denied. Please log in as an Admin.";
    header("Location: ../public/login.php");
    exit();
}

// Get the job ID from the URL
if (isset($_GET['id'])) {
    $job_id = $_GET['id'];

    // Fetch job details from the database
    $query = "SELECT * FROM jobs WHERE job_id = :job_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
    $stmt->execute();
    $job = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$job) {
        $_SESSION['error'] = "Job not found.";
        header("Location: view_jobs.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Job ID is required.";
    header("Location: view_jobs.php");
    exit();
}

include './includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container my-5">
    <h1 class="text-center">Job Details</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2><?php echo htmlspecialchars($job['job_title']); ?></h2>
        </div>
        <div class="card-body">
            <p><strong>Job ID:</strong> <?php echo htmlspecialchars($job['job_id']); ?></p>
            <p><strong>Department:</strong> <?php echo htmlspecialchars($job['job_department']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($job['job_status']); ?></p>
            <p><strong>Created At:</strong> <?php echo htmlspecialchars($job['job_created_at']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($job['job_description']); ?></p>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="view_jobs.php" class="btn btn-secondary">Back to Job Listings</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
