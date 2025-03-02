<?php
session_start();
require_once('../config/database.php');
require_once('../vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['error'] = "Access denied. Please log in as an Admin.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

function generateOTP($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $otp;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    $otp = generateOTP();
    $password = password_hash($otp, PASSWORD_ARGON2ID);

    try {
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $check_stmt->execute([$username, $email]);
        if ($check_stmt->fetchColumn() > 0) {
            $_SESSION['error'] = "Username or email already exists.";
            header("Location: create_user.php");
            exit();
        }

        $sql = "INSERT INTO users (username, email, password, role, status, created_at, is_temp_password) 
                VALUES (?, ?, ?, ?, ?, NOW(), 1)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$username, $email, $password, $role, $status])) {
            $mail = new PHPMailer(true);
            // $mail->SMTPDebug = 2; // Comment out or set to 0 in production
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'binyam.tagel@gmail.com';
                $mail->Password = 'mxab gkzy uydt taoo'; // Your App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('binyam.tagel@gmail.com', 'Debark HRM');
                $mail->addAddress($email, $username);
                $mail->isHTML(true);
                $mail->Subject = 'Your One-Time Password for Debark University HRM';
                $mail->Body = "Dear $username,<br><br>Your account has been created. Please use the following one-time password to log in:<br><strong>$otp</strong><br><br>You must change this password on your first login.<br><br>Login here: <a href='http://localhost/HRM/HRM/PUBLIC/login.php'>Login</a><br><br>Regards,<br>Debark University HRM Team";
                $mail->AltBody = "Dear $username,\n\nYour account has been created. Please use this one-time password to log in: $otp\n\nYou must change it on your first login.\n\nLogin here: http://localhost/HRM/HRM/PUBLIC/login.php\n\nRegards,\nDebark University HRM Team";

                $mail->send();
                $_SESSION['success'] = "User created successfully! OTP has been sent to $email.";
            } catch (Exception $e) {
                $_SESSION['error'] = "User created, but email failed: " . $mail->ErrorInfo;
            }
            header("Location: manage_users.php");
            exit();
        } else {
            $_SESSION['error'] = "Error creating user.";
        }
    } catch (PDOException $e) {
        error_log("Create User Error: " . $e->getMessage());
        $_SESSION['error'] = "Error creating user: " . $e->getMessage();
    }
}

include('./includes/admin_navbar.php'); // Standardized path
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-user-plus me-2"></i>Create New User</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm p-4 mx-auto" style="max-width: 500px;">
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" pattern="[a-zA-Z0-9_]{3,20}" required>
                    <div class="invalid-feedback">Username must be 3-20 characters (letters, numbers, underscores only).</div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="" disabled selected>Select a role</option>
                        <option value="Employee">Employee</option>
                        <option value="Manager">Manager</option>
                        <option value="HR Officer">HR Officer</option>
                        <option value="HR Admin">HR Admin</option>
                        <option value="Dean">Dean</option>
                        <option value="Department Head">Department Head</option>
                        <option value="Finance Officer">Finance Officer</option>
                        <option value="Applicant">Applicant</option>
                    </select>
                    <div class="invalid-feedback">Please select a role.</div>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="" disabled selected>Select status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                    <div class="invalid-feedback">Please select a status.</div>
                </div>
                <div class="alert alert-info">A one-time password will be emailed to the user.</div>
                <button type="submit" class="btn btn-primary w-100">Create User</button>
                <a href="manage_users.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
            </form>
        </div>
    </div>

    <?php include('./includes/footer.php'); ?>

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