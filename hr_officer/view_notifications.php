<?php
session_start();
require_once '../config/database.php'; // Includes the PDO connection ($conn)

// Ensure user is logged in as HR Officer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../public/login.php");
    exit();
}

// Fetch notifications securely
$query = "SELECT * FROM notifications WHERE LOWER(user_role) = 'hr officer' ORDER BY created_at DESC";
$stmt = $conn->prepare($query); // Change $db to $conn as per the database.php
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

include './includes/hr_navbar.php'; // Fixed navbar path
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Officer Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container my-5">
    <h1 class="text-center">HR Officer Notifications</h1>

    <?php if (count($result) > 0): ?>
        <ul class="list-group mt-4">
            <?php foreach ($result as $row): ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($row['title']) ?></strong> - 
                    <?= htmlspecialchars($row['message']) ?> 
                    <span class="text-muted float-end"><?= htmlspecialchars($row['created_at']) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-center text-muted mt-3">No notifications available.</p>
    <?php endif; ?>

    <div class="mt-3 text-center">
        <a href="hr_officer_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
