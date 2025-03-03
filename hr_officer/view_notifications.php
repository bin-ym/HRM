<?php
session_start();
require_once('../config/database.php');

// Ensure user is logged in as HR Officer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    $_SESSION['error'] = "Access denied. Please log in as an HR Officer.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$hr_officer_id = $_SESSION['user_id'];

try {
    // Fetch notifications for the logged-in HR Officer
    $query = "SELECT n.id, n.message, n.status, n.created_at 
              FROM notifications n 
              WHERE n.recipient_id = ? 
              ORDER BY n.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute([$hr_officer_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("View Notifications Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading notifications: " . $e->getMessage();
}

include('./includes/hr_navbar.php'); // Adjusted path to match your structure
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Officer Notifications - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">HR Officer Notifications</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (!empty($notifications)): ?>
            <div class="list-group mt-4">
                <?php foreach ($notifications as $notification): ?>
                    <div class="list-group-item <?= $notification['status'] === 'Unread' ? 'list-group-item-info' : ''; ?>">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Notification #<?= htmlspecialchars($notification['id']); ?></h5>
                            <small><?= htmlspecialchars($notification['created_at']); ?></small>
                        </div>
                        <p class="mb-1"><?= htmlspecialchars($notification['message']); ?></p>
                        <small>Status: <?= htmlspecialchars($notification['status']); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-muted mt-3">No notifications available.</p>
        <?php endif; ?>

        <div class="mt-3 text-center">
            <a href="hr_officer_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>