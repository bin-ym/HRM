<?php
session_start();
require_once('../config/database.php');
require_once('./includes/applicant_navbar.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Applicant') {
    $_SESSION['error'] = "Please log in to access this page.";
    header("Location: ../public/login.php");
    exit();
}

if (!isset($_GET['job_id'])) {
    $_SESSION['error'] = "No job specified.";
    header("Location: apply_vacancy.php");
    exit();
}

$job_id = filter_input(INPUT_GET, 'job_id', FILTER_SANITIZE_NUMBER_INT);

try {
    // READ operation
    $sql = "SELECT job_title, description, qualifications, skills_required, application_deadline, status 
            FROM job_openings WHERE job_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$job_id]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$job) {
        $_SESSION['error'] = "Job not found.";
        header("Location: apply_vacancy.php");
        exit();
    }
} catch (PDOException $e) {
    error_log("Job Details Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading job details. Please try again.";
    header("Location: apply_vacancy.php");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><?= htmlspecialchars($job['job_title']); ?></h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Description</h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($job['description'] ?? '')); ?></p>

                <h5 class="mt-3">Qualifications</h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($job['qualifications'] ?? '')); ?></p>

                <h5 class="mt-3">Skills Required</h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($job['skills_required'] ?? '')); ?></p>

                <h5 class="mt-3">Application Deadline</h5>
                <p class="card-text"><?= $job['application_deadline'] ? date('F j, Y', strtotime($job['application_deadline'])) : 'N/A'; ?></p>

                <h5 class="mt-3">Status</h5>
                <p class="card-text"><?= htmlspecialchars($job['status'] ?? 'Open'); ?></p>

                <a href="apply_job.php?job_id=<?= $job_id; ?>" class="btn btn-success mt-3">Apply Now</a>
                <a href="apply_vacancy.php" class="btn btn-secondary mt-3">Back to Vacancies</a>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>