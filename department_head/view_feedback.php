<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Department Head') {
    $_SESSION['error'] = "Please log in as a Department Head to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT f.user_id, f.feedback_text, f.submitted_at, u.username 
                            FROM feedback f 
                            JOIN users u ON f.user_id = u.id 
                            ORDER BY f.submitted_at DESC");
    $stmt->execute();
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("View Feedback Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading feedback: " . $e->getMessage();
}

include('./includes/department_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-comment me-2"></i>View Feedback</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (empty($feedbacks)): ?>
            <p class="text-center">No feedback found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>User ID</th>
                            <th>User</th>
                            <th>Feedback Text</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($feedbacks as $feedback): ?>
                            <tr>
                                <td><?= htmlspecialchars($feedback['user_id']); ?></td>
                                <td><?= htmlspecialchars($feedback['username']); ?></td>
                                <td><?= htmlspecialchars($feedback['feedback_text']); ?></td>
                                <td><?= htmlspecialchars(date('F j, Y, H:i', strtotime($feedback['submitted_at']))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <div class="text-center mt-3">
            <a href="department_head_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>