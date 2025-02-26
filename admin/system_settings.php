<?php
session_start();
require_once '../config/database.php';

// Ensure user is logged in as Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['error'] = "Access denied. Please log in as an Admin.";
    header("Location: ../public/login.php");
    exit();
}

include './includes/navbar.php';

// Handle system settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Example: Update system settings
    $siteName = htmlspecialchars($_POST['site_name']);
    $adminEmail = htmlspecialchars($_POST['admin_email']);
    
    // Update settings in the database (You can replace with your actual database update logic)
    $sql = "UPDATE settings SET site_name = :site_name, admin_email = :admin_email WHERE id = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':site_name' => $siteName, ':admin_email' => $adminEmail]);

    $_SESSION['success'] = "Settings updated successfully.";
    header('Location: system_settings.php');
    exit();
}

// Fetch current settings (Example)
$sql = "SELECT * FROM settings WHERE id = 1"; // Assume there's a settings table with system-wide settings
$stmt = $conn->query($sql);
$settings = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container my-5">
    <h1 class="text-center">System Settings</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="site_name" class="form-label">Site Name</label>
            <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="admin_email" class="form-label">Admin Email</label>
            <input type="email" class="form-control" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($settings['admin_email']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
