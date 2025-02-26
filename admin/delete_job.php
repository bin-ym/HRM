<?php
// Include configuration and database connection
include('./includes/config.php');
include('./includes/db_connect.php');  // Ensure this file initializes $conn

// Fetch job ID from the URL
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;

// Ensure the job ID is valid
if ($job_id <= 0) {
    die("Invalid job ID.");
}

// Check if job exists
$sql = "SELECT job_id FROM jobs WHERE job_id = :job_id";
$stmt = $conn->prepare($sql);
$stmt->execute([':job_id' => $job_id]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    die("Job not found.");
}

// Delete job from the database
$sql = "DELETE FROM jobs WHERE job_id = :job_id";
$stmt = $conn->prepare($sql);
$stmt->execute([':job_id' => $job_id]);

// Redirect to manage_jobs.php with success message
header('Location: manage_jobs.php?deleted=success');
exit;
?>
