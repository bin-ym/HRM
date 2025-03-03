<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Department Head') {
    $_SESSION['error'] = "Please log in as a Department Head to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$dept_head_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT lr.request_id, lr.start_date, lr.end_date, lr.reason, lr.status, lr.leave_category, lr.submission_date, u.username AS approved_by 
                            FROM leave_requests lr 
                            LEFT JOIN users u ON lr.approved_by = u.id 
                            WHERE lr.employee_id = ? 
                            ORDER BY lr.submission_date DESC");
    $stmt->execute([$dept_head_id]);
    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Manage Permission Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading permissions: " . $e->getMessage();
}

include('../includes/department_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Permissions - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-user-lock me-2"></i>Manage Permissions</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <p class="text-center mb-4">View your submitted leave requests and their approval status by the Dean.</p>

        <?php if (empty($permissions)): ?>
            <p class="text-center">No leave requests found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Request ID</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Reason</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th>Approved By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($permissions as $perm): ?>
                            <tr>
                                <td><?= htmlspecialchars($perm['request_id']); ?></td>
                                <td><?= htmlspecialchars(date('F j, Y', strtotime($perm['start_date']))); ?></td>
                                <td><?= htmlspecialchars(date('F j, Y', strtotime($perm['end_date']))); ?></td>
                                <td><?= htmlspecialchars($perm['reason']); ?></td>
                                <td><?= htmlspecialchars($perm['leave_category']); ?></td>
                                <td>
                                    <span class="badge <?= $perm['status'] === 'approved' ? 'bg-success' : ($perm['status'] === 'rejected' ? 'bg-danger' : 'bg-warning'); ?>">
                                        <?= htmlspecialchars($perm['status']); ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars(date('F j, Y, H:i', strtotime($perm['submission_date']))); ?></td>
                                <td><?= htmlspecialchars($perm['approved_by'] ?? 'Pending'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <div class="text-center mt-3">
            <a href="employee_requests.php" class="btn btn-primary me-2">Request Leave</a>
            <a href="department_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>