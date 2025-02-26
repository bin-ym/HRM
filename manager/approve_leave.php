<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Manager') {
    $_SESSION['error'] = "Unauthorized Access!";
    header("Location: ../public/login.php");
    exit();
}

$manager_id = $_SESSION['user_id'];

// Fetch pending leave requests
$sql = "SELECT * FROM leave_requests WHERE status = 'Pending'";
$stmt = $conn->query($sql);
$leave_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Approve or Reject Leave
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $request_id = $_POST['request_id'];
    $status = ($_POST['action'] === 'approve') ? 'Approved' : 'Rejected';

    $update_sql = "UPDATE leave_requests SET status = ? WHERE request_id = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->execute([$status, $request_id]);

    header("Location: approve_leave.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Leave Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <?php include './includes/manager_navbar.php'; ?>

    <div class="container my-5">
        <h1 class="text-center">Approve Leave Requests</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leave_requests as $leave): ?>
                    <tr>
                        <td><?= htmlspecialchars($leave['employee_id']); ?></td>
                        <td><?= htmlspecialchars($leave['leave_type']); ?></td>
                        <td><?= htmlspecialchars($leave['start_date']); ?></td>
                        <td><?= htmlspecialchars($leave['end_date']); ?></td>
                        <td><?= htmlspecialchars($leave['reason']); ?></td>
                        <td><?= htmlspecialchars($leave['status']); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="request_id" value="<?= $leave['request_id']; ?>">
                                <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
