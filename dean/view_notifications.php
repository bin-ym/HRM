<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Dean') {
    $_SESSION['error'] = "Please log in as a Dean to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT a.id, a.applicant_id, a.job_id, a.status, a.created_at, u.username, j.job_title 
                            FROM applications a 
                            JOIN users u ON a.applicant_id = u.id 
                            JOIN job_openings j ON a.job_id = j.job_id 
                            WHERE a.status = 'Pending' 
                            ORDER BY a.created_at DESC");
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("View Notifications Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading notifications: " . $e->getMessage();
}

include('./includes/dean_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Notifications - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-bell me-2"></i>Notifications</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (empty($notifications)): ?>
            <p class="text-center">No pending notifications found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Applicant</th>
                            <th>Job Title</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notifications as $notif): ?>
                            <tr>
                                <td><?= htmlspecialchars($notif['id']); ?></td>
                                <td><?= htmlspecialchars($notif['username']); ?></td>
                                <td><?= htmlspecialchars($notif['job_title']); ?></td>
                                <td>
                                    <span class="badge bg-warning"><?= htmlspecialchars($notif['status']); ?></span>
                                </td>
                                <td><?= htmlspecialchars(date('F j, Y, H:i', strtotime($notif['created_at']))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <div class="text-center mt-3">
            <a href="dean_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>