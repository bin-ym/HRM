<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    $_SESSION['error'] = "Please log in as an Employee to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$employee_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback_text = filter_input(INPUT_POST, 'feedback_text', FILTER_SANITIZE_STRING);

    try {
        $sql = "INSERT INTO feedback (user_id, feedback_text, submitted_at) 
                VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$employee_id, $feedback_text])) {
            $_SESSION['success'] = "Feedback submitted successfully!";
            header("Location: employee_dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Error submitting feedback.";
        }
    } catch (PDOException $e) {
        error_log("Send Feedback Error: " . $e->getMessage());
        $_SESSION['error'] = "Error submitting feedback: " . $e->getMessage();
    }
}

include('employee_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Feedback - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-comment me-2"></i>Send Feedback</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm p-4">
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="feedback_text" class="form-label">Your Feedback</label>
                    <textarea class="form-control" id="feedback_text" name="feedback_text" rows="5" required placeholder="Enter your feedback here"></textarea>
                    <div class="invalid-feedback">Please enter your feedback.</div>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                    <a href="employee_dashboard.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
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