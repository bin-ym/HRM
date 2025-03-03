<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Dean') {
    $_SESSION['error'] = "Please log in as a Dean to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT t.id, t.employee_id, t.description, t.due_date, t.status, u.username 
                            FROM tasks t 
                            JOIN users u ON t.employee_id = u.id 
                            ORDER BY t.due_date ASC");
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Manage Department Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading department tasks: " . $e->getMessage();
}

include('./includes/dean_navbar.php'); // Adjusted path to match your structure
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Department - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-building me-2"></i>Manage Department Tasks</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (empty($tasks)): ?>
            <p class="text-center">No department tasks found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Task ID</th>
                            <th>Employee</th>
                            <th>Description</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?= htmlspecialchars($task['id']); ?></td>
                                <td><?= htmlspecialchars($task['username']); ?></td>
                                <td><?= htmlspecialchars($task['description']); ?></td>
                                <td><?= htmlspecialchars(date('F j, Y', strtotime($task['due_date']))); ?></td>
                                <td>
                                    <span class="badge <?= $task['status'] === 'Completed' ? 'bg-success' : 'bg-warning'; ?>">
                                        <?= htmlspecialchars($task['status']); ?>
                                    </span>
                                </td>
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