<?php
session_start();
require_once('../config/database.php');
require_once('./includes/applicant_navbar.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Applicant') {
    $_SESSION['error'] = "Please log in to send feedback.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$applicant_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    // Hardcode feedback_type as 'Applicant' since this is the applicant dashboard
    $feedback_type = 'Applicant';

    try {
        $sql = "INSERT INTO feedback (user_id, feedback_text, feedback_type) 
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$applicant_id, $message, $feedback_type])) {
            $_SESSION['success'] = "Feedback sent successfully!";
            header("Location: applicant_dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Error sending feedback.";
        }
    } catch (PDOException $e) {
        error_log("Feedback Error: " . $e->getMessage());
        $_SESSION['error'] = "Error sending feedback: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Feedback - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Send Feedback</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="" method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="message" class="form-label">Your Feedback</label>
                        <textarea name="message" id="message" class="form-control" rows="5" required placeholder="Enter your feedback here..."></textarea>
                        <div class="invalid-feedback">Feedback message is required.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                    <a href="applicant_dashboard.php" class="btn btn-secondary">Cancel</a>
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