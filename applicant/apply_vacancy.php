<?php
session_start();
require_once('../config/database.php');
require_once('./includes/applicant_navbar.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Applicant') {
    $_SESSION['error'] = "Please log in to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$applicant_id = $_SESSION['user_id'];

try {
    // Modified SQL to exclude jobs past the application deadline
    $sql = "SELECT job_id, job_title, description, qualifications, skills_required, application_deadline, status 
            FROM job_openings 
            WHERE status = 'Open' AND (application_deadline IS NULL OR application_deadline >= NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Vacancy Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading vacancies: " . $e->getMessage();
    header("Location: applicant_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Vacancy - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4" style="font-family: 'Arial', sans-serif; color: #003366;">Available Vacancies</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (empty($jobs)): ?>
            <p class="text-center">No open vacancies available at this time.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($jobs as $job): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-lg rounded" style="border: none; transition: transform 0.3s ease-in-out;">
                            <img src="https://via.placeholder.com/350x200" class="card-img-top rounded-top" alt="Job Image">
                            <div class="card-body">
                                <h5 class="card-title text-primary"><?= htmlspecialchars($job['job_title']); ?></h5>
                                <p class="card-text"><strong>Description:</strong> <?= htmlspecialchars(substr($job['description'] ?? '', 0, 100)) . "..."; ?></p>
                                <p class="card-text"><strong>Qualifications:</strong> <?= htmlspecialchars(substr($job['qualifications'] ?? '', 0, 100)) . "..."; ?></p>
                                <p class="card-text"><strong>Skills:</strong> <?= htmlspecialchars(substr($job['skills_required'] ?? '', 0, 100)) . "..."; ?></p>
                                <p class="card-text"><strong>Deadline:</strong> <?= $job['application_deadline'] ? date('F j, Y', strtotime($job['application_deadline'])) : 'N/A'; ?></p>
                                <div class="d-flex justify-content-between">
                                    <a href="job_details.php?job_id=<?= $job['job_id']; ?>" class="btn btn-info btn-sm">See Details</a>
                                    <a href="apply_job.php?job_id=<?= $job['job_id']; ?>" class="btn btn-success btn-sm">Apply Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('mouseover', () => card.style.transform = 'scale(1.05)');
            card.addEventListener('mouseout', () => card.style.transform = 'scale(1)');
        });
    </script>
</body>
</html>