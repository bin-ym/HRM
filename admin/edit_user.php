<?php
session_start();
require_once('../config/database.php'); // Centralized DB connection

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['error'] = "Access denied. Please log in as an Admin.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

// Function to fetch user data based on ID
function getUserData($conn, $id) {
    $sql = "SELECT id, username, email, role, status FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get user ID from URL
$id = isset($_GET['id']) ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) : 0;
$user = $id ? getUserData($conn, $id) : null;

if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: manage_users.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    try {
        $sql = "UPDATE users SET username = ?, email = ?, role = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$username, $email, $role, $status, $id])) {
            $_SESSION['success'] = "User updated successfully!";
            header("Location: manage_users.php");
            exit();
        } else {
            $_SESSION['error'] = "Error updating user.";
        }
    } catch (PDOException $e) {
        error_log("Edit User Error: " . $e->getMessage());
        $_SESSION['error'] = "Error updating user: " . $e->getMessage();
    }
}

include('./includes/admin_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-edit me-2"></i>Edit User</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm p-4 mx-auto" style="max-width: 500px;">
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" pattern="[a-zA-Z0-9_]{3,20}" required>
                    <div class="invalid-feedback">Username must be 3-20 characters (letters, numbers, underscores only).</div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="Employee" <?= $user['role'] === 'Employee' ? 'selected' : ''; ?>>Employee</option>
                        <option value="Manager" <?= $user['role'] === 'Manager' ? 'selected' : ''; ?>>Manager</option>
                        <option value="HR Officer" <?= $user['role'] === 'HR Officer' ? 'selected' : ''; ?>>HR Officer</option>
                        <option value="HR Admin" <?= $user['role'] === 'HR Admin' ? 'selected' : ''; ?>>HR Admin</option>
                        <option value="Dean" <?= $user['role'] === 'Dean' ? 'selected' : ''; ?>>Dean</option>
                        <option value="Department Head" <?= $user['role'] === 'Department Head' ? 'selected' : ''; ?>>Department Head</option>
                        <option value="Finance Officer" <?= $user['role'] === 'Finance Officer' ? 'selected' : ''; ?>>Finance Officer</option>
                        <option value="Applicant" <?= $user['role'] === 'Applicant' ? 'selected' : ''; ?>>Applicant</option>
                    </select>
                    <div class="invalid-feedback">Please select a role.</div>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Active" <?= $user['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                        <option value="Inactive" <?= $user['status'] === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                    <div class="invalid-feedback">Please select a status.</div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Update User</button>
                <a href="manage_users.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
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