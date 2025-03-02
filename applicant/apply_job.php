<?php
session_start();
require_once('../config/database.php');
require_once('./includes/applicant_navbar.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Applicant') {
    $_SESSION['error'] = "Please log in to apply.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

if (!isset($_GET['job_id'])) {
    $_SESSION['error'] = "No job specified.";
    header("Location: apply_vacancy.php");
    exit();
}

$job_id = filter_input(INPUT_GET, 'job_id', FILTER_SANITIZE_NUMBER_INT);
$applicant_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Check if already applied
        $check_sql = "SELECT COUNT(*) FROM applications WHERE applicant_id = ? AND job_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->execute([$applicant_id, $job_id]);
        if ($check_stmt->fetchColumn() > 0) {
            $_SESSION['error'] = "You have already applied for this job.";
            header("Location: apply_vacancy.php");
            exit();
        }

        // Handle file upload
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/resumes/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $resume_name = $applicant_id . '_' . time() . '_' . basename($_FILES['resume']['name']);
            $resume_path = $upload_dir . $resume_name;

            if (move_uploaded_file($_FILES['resume']['tmp_name'], $resume_path)) {
                // CREATE: Insert application with resume path
                $sql = "INSERT INTO applications (applicant_id, job_id, status, resume_path, created_at) 
                        VALUES (?, ?, 'Pending', ?, NOW())";
                $stmt = $conn->prepare($sql);
                if ($stmt->execute([$applicant_id, $job_id, $resume_path])) {
                    $_SESSION['success'] = "Application submitted successfully!";
                    header("Location: applicant_dashboard.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Failed to upload resume.";
            }
        } else {
            $_SESSION['error'] = "Please upload a resume.";
        }
    } catch (PDOException $e) {
        error_log("Apply Job Error: " . $e->getMessage());
        $_SESSION['error'] = "Error submitting application: " . $e->getMessage();
    }
}

try {
    $sql = "SELECT job_title FROM job_openings WHERE job_id = ? AND status = 'Open'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$job_id]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$job) {
        $_SESSION['error'] = "Job not found or no longer open.";
        header("Location: apply_vacancy.php");
        exit();
    }
} catch (PDOException $e) {
    error_log("Apply Job Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading job details.";
    header("Location: apply_vacancy.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Apply for <?= htmlspecialchars($job['job_title']); ?></h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="resume" class="form-label">Upload Resume (PDF only, max 5MB)</label>
                        <input type="file" class="form-control" id="resume" name="resume" accept=".pdf" required>
                        <div class="invalid-feedback">Please upload a valid PDF resume.</div>
                    </div>
                    <button type="submit" class="btn btn-success">Submit Application</button>
                    <a href="apply_vacancy.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        (function () {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>