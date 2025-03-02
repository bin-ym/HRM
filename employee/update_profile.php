<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    $_SESSION['error'] = "Please log in as an Employee to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$employee_id = $_SESSION['user_id'];

try {
    // Fetch employee data from users table
    $stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ? AND role = 'Employee'");
    $stmt->execute([$employee_id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        $_SESSION['error'] = "Employee profile not found.";
    }
} catch (PDOException $e) {
    error_log("Profile Fetch Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading profile: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    // Validation
    $errors = [];
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $errors['username'] = "Username must be 3-20 characters (letters, numbers, underscores only).";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    } 

    if (empty($errors)) {
        try {
            // Check username and email uniqueness (excluding current user)
            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE (username = ? OR email = ?) AND id != ?");
            $check_stmt->execute([$username, $email, $employee_id]);
            if ($check_stmt->fetchColumn() > 0) {
                $errors['email'] = "Username or email is already in use by another user.";
            } else {
                // Update users table
                $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt->execute([$username, $email, $employee_id])) {
                    $_SESSION['success'] = "Profile updated successfully!";
                    header("Location: employee_dashboard.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Error updating profile.";
                }
            }
        } catch (PDOException $e) {
            error_log("Profile Update Error: " . $e->getMessage());
            $_SESSION['error'] = "Error updating profile: " . $e->getMessage();
        }
    }
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }
}

include('employee_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-user-edit me-2"></i>Update Profile</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if ($employee): ?>
            <div class="card shadow-sm p-4">
                <form method="POST" class="needs-validation" novalidate id="profileForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control <?= isset($_SESSION['errors']['username']) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?= htmlspecialchars($employee['username']); ?>" pattern="[a-zA-Z0-9_]{3,20}" required>
                        <div class="invalid-feedback"><?= isset($_SESSION['errors']['username']) ? htmlspecialchars($_SESSION['errors']['username']) : 'Please enter a valid username (3-20 characters, letters, numbers, underscores).'; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?= isset($_SESSION['errors']['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?= htmlspecialchars($employee['email']); ?>" required>
                        <div class="invalid-feedback"><?= isset($_SESSION['errors']['email']) ? htmlspecialchars($_SESSION['errors']['email']) : 'Please enter a valid email address.'; ?></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmModal">Update Profile</button>
                        <a href="employee_dashboard.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>

            <!-- Confirmation Modal -->
            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmModalLabel">Confirm Update</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to update your profile?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" form="profileForm" class="btn btn-primary">Yes, Update</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p class="text-center">Profile data not available. Please contact HR to set up your profile.</p>
            <div class="text-center mt-3">
                <a href="employee_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['errors'])) unset($_SESSION['errors']); // Clear errors after display ?>
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
                    } else {
                        event.preventDefault();
                        new bootstrap.Modal(document.getElementById('confirmModal')).show();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>