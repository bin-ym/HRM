<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Dean') {
    $_SESSION['error'] = "Please log in as a Dean to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$app_id = filter_input(INPUT_GET, 'app_id', FILTER_SANITIZE_NUMBER_INT);

if (!$app_id) {
    $_SESSION['error'] = "Invalid application ID.";
    header("Location: view_applications.php");
    exit();
}

try {
    // Fetch application details
    $stmt = $conn->prepare("SELECT a.id, a.applicant_id, a.job_id, a.status, a.resume_path, u.username, j.job_title 
                            FROM applications a 
                            JOIN users u ON a.applicant_id = u.id 
                            JOIN job_openings j ON a.job_id = j.job_id 
                            WHERE a.id = ?");
    $stmt->execute([$app_id]);
    $application = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$application) {
        $_SESSION['error'] = "Application not found.";
        header("Location: view_applications.php");
        exit();
    }
} catch (PDOException $e) {
    error_log("Decision Fetch Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading application: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $decision = filter_input(INPUT_POST, 'decision', FILTER_SANITIZE_STRING);

    if ($decision === 'approve' || $decision === 'reject') {
        try {
            $new_status = $decision === 'approve' ? 'Approved' : 'Rejected';
            $sql = "UPDATE applications SET status = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$new_status, $app_id])) {
                $_SESSION['success'] = "Decision recorded successfully!";
                header("Location: view_applications.php");
                exit();
            } else {
                $_SESSION['error'] = "Error recording decision.";
            }
        } catch (PDOException $e) {
            error_log("Decision Update Error: " . $e->getMessage());
            $_SESSION['error'] = "Error recording decision: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Invalid decision.";
    }
}

include('../includes/dean_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Decision - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-gavel me-2"></i>Make Decision</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if ($application): ?>
            <div class="card shadow-sm p-4">
                <h4 class="mb-3">Application Details</h4>
                <p><strong>Applicant:</strong> <?= htmlspecialchars($application['username']); ?></p>
                <p><strong>Job Title:</strong> <?= htmlspecialchars($application['job_title']); ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($application['status']); ?></p>
                <p><strong>Resume:</strong> 
                    <?php if ($application['resume_path']): ?>
                        <a href="<?= htmlspecialchars($application['resume_path']); ?>" target="_blank" class="btn btn-info btn-sm">View Resume</a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </p>
                <p><strong>Submitted:</strong> <?= htmlspecialchars(date('F j, Y, H:i', strtotime($application['created_at']))); ?></p>

                <form method="POST" class="mt-4">
                    <div class="mb-3">
                        <label class="form-label">Decision:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="decision" id="approve" value="approve" required>
                            <label class="form-check-label" for="approve">Approve</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="decision" id="reject" value="reject">
                            <label class="form-check-label" for="reject">Reject</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Submit Decision</button>
                        <a href="view_applications.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        (function () {
            'use strict';
            const forms = document.querySelectorAll('form');
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