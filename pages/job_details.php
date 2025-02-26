<?php
session_start();
include('../config/database.php'); // Include database connection

// Check if job_id is provided
if (!isset($_GET['job_id']) || empty($_GET['job_id'])) {
    header("Location: jobs.php"); // Redirect if no job_id
    exit();
}

$job_id = intval($_GET['job_id']); // Sanitize input

// Fetch job details
$sql = "SELECT job_title, description AS job_description FROM Job_Openings WHERE job_id = :job_id AND status = 'Open'";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
$stmt->execute();
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    $_SESSION['error'] = "Job not found or no longer available.";
    header("Location: jobs.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body>
    <!-- Navbar -->
    <?php include('../includes/navbar.php'); ?>

    <div class="container my-5">
        <h1 class="text-center"><?= htmlspecialchars($job['job_title']); ?></h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Job Description</h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($job['job_description'])); ?></p>
                <a href="apply_job.php?job_id=<?= $job_id; ?>" class="btn btn-primary">Apply Now</a>
                <a href="jobs.php" class="btn btn-secondary">Back to Job Openings</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>