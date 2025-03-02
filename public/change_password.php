<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to change your password.";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } elseif (!preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/', $new_password)) {
        $_SESSION['error'] = "Password must be at least 8 characters with 1 uppercase, 1 lowercase, and 1 number.";
    } else {
        try {
            $hashed_password = password_hash($new_password, PASSWORD_ARGON2ID);
            $stmt = $conn->prepare("UPDATE users SET password = ?, is_temp_password = 0 WHERE id = ?");
            if ($stmt->execute([$hashed_password, $user_id])) {
                $_SESSION['success'] = "Password changed successfully! Please log in with your new password.";
                session_unset();
                session_destroy();
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['error'] = "Error updating password.";
            }
        } catch (PDOException $e) {
            error_log("Change Password Error: " . $e->getMessage());
            $_SESSION['error'] = "Error updating password: " . $e->getMessage();
        }
    }
}

include('../includes/navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-key me-2"></i>Change Your Password</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm p-4 mx-auto" style="max-width: 400px;">
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3 position-relative">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                    <span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;" onclick="togglePassword('new_password', 'toggleIconNew')">
                        <i class="fas fa-eye" id="toggleIconNew"></i>
                    </span>
                    <div class="invalid-feedback">Password must be at least 8 characters with 1 uppercase, 1 lowercase, and 1 number.</div>
                </div>
                <div class="mb-3 position-relative">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    <span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;" onclick="togglePassword('confirm_password', 'toggleIconConfirm')">
                        <i class="fas fa-eye" id="toggleIconConfirm"></i>
                    </span>
                    <div class="invalid-feedback">Please confirm your password.</div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Change Password</button>
            </form>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        function togglePassword(fieldId, iconId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(iconId);
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

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