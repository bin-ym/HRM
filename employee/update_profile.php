<?php
session_start();
require_once '../config/database.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    $_SESSION['error'] = "Please log in to access this page.";
    header("Location: ../public/login.php");
    exit();
}

$employee_id = $_SESSION['user_id'];
$message = '';

try {
    $sql = "SELECT * FROM employees WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$employee_id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: employee_dashboard.php");
    exit();
}

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    $sql_update = "UPDATE employees SET first_name = ?, last_name = ?, email = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->execute([$first_name, $last_name, $email, $employee_id]);

    $_SESSION['success'] = "Profile updated successfully!";
    header("Location: update_profile.php");
    exit();
}

// Handle Password Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $sql_password = "UPDATE employees SET password = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($sql_password);
    $stmt->execute([$new_password, $employee_id]);

    $_SESSION['success'] = "Password updated successfully!";
    header("Location: update_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include('./employee_navbar.php'); ?>

    <div class="container my-5">
        <h2>Update Profile</h2>

        <!-- Success/Error Message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Update Profile Form -->
        <form method="POST">
            <div class="mb-3">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($employee['first_name']); ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Last Name</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($employee['last_name']); ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($employee['email']); ?>" class="form-control" required>
            </div>
            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
        </form>

        <hr>

        <!-- Update Password Form -->
        <h3>Update Password</h3>
        <form method="POST">
            <div class="mb-3">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <button type="submit" name="update_password" class="btn btn-warning">Update Password</button>
        </form>
    </div>
</body>
</html>
