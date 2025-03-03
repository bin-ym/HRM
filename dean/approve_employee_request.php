<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Dean') {
    $_SESSION['error'] = "Please log in as a Dean to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$dean_id = $_SESSION['user_id'];

// Handle approval/rejection of leave requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_leave'])) {
    $request_id = filter_input(INPUT_POST, 'request_id', FILTER_SANITIZE_NUMBER_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    try {
        $status = $action === 'approve' ? 'approved' : 'rejected';
        $sql = "UPDATE leave_requests SET status = ?, approved_by = ? WHERE request_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$status, $dean_id, $request_id])) {
            $_SESSION['success'] = "Leave request $status successfully!";
            header("Location: approve_employee_request.php");
            exit();
        } else {
            $_SESSION['error'] = "Error updating leave request.";
        }
    } catch (PDOException $e) {
        error_log("Approve Leave Request Error: " . $e->getMessage());
        $_SESSION['error'] = "Error updating leave request: " . $e->getMessage();
    }
}

// Handle approval/rejection of staff requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_staff'])) {
    $request_id = filter_input(INPUT_POST, 'request_id', FILTER_SANITIZE_NUMBER_INT);
    $action = filter_input(INPUT_POST, 'action_staff', FILTER_SANITIZE_STRING); // Fixed to match form button name

    try {
        $status = $action === 'approve' ? 'approved' : 'rejected';
        $sql = "UPDATE staff_requests SET status = ?, approved_by = ? WHERE request_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$status, $dean_id, $request_id])) {
            if ($action === 'approve') {
                // Fetch staff request details
                $stmt = $conn->prepare("SELECT dept_head_id, request_description, requested_positions FROM staff_requests WHERE request_id = ?");
                $stmt->execute([$request_id]);
                $request = $stmt->fetch(PDO::FETCH_ASSOC);

                // Fetch HR Officer
                $hr_stmt = $conn->prepare("SELECT id FROM users WHERE role = 'HR Officer' LIMIT 1");
                $hr_stmt->execute();
                $hr_officer = $hr_stmt->fetch(PDO::FETCH_ASSOC);

                if ($hr_officer) {
                    $message = "Dean approved a staff request from Department Head (ID: {$request['dept_head_id']}): {$request['request_description']} for {$request['requested_positions']} positions. Please announce vacancies accordingly.";
                    $notify_stmt = $conn->prepare("INSERT INTO notifications (recipient_id, message, status, created_at) VALUES (?, ?, 'Unread', NOW())");
                    $notify_stmt->execute([$hr_officer['id'], $message]);
                } else {
                    error_log("No HR Officer found to notify.");
                }
            }
            $_SESSION['success'] = "Staff request $status successfully!";
            header("Location: approve_employee_request.php");
            exit();
        } else {
            $_SESSION['error'] = "Error updating staff request.";
        }
    } catch (PDOException $e) {
        error_log("Approve Staff Request Error: " . $e->getMessage());
        $_SESSION['error'] = "Error updating staff request: " . $e->getMessage();
    }
}

try {
    // Fetch pending leave requests
    $leave_stmt = $conn->prepare("SELECT lr.request_id, lr.employee_id, lr.start_date, lr.end_date, lr.reason, lr.status, lr.leave_category, u.username 
                                  FROM leave_requests lr 
                                  JOIN users u ON lr.employee_id = u.id 
                                  WHERE lr.status = 'pending' 
                                  ORDER BY lr.submission_date ASC");
    $leave_stmt->execute();
    $leave_requests = $leave_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch pending staff requests
    $staff_stmt = $conn->prepare("SELECT sr.request_id, sr.dept_head_id, sr.request_description, sr.requested_positions, sr.status, sr.submission_date, u.username 
                                  FROM staff_requests sr 
                                  JOIN users u ON sr.dept_head_id = u.id 
                                  WHERE sr.status = 'pending' 
                                  ORDER BY sr.submission_date ASC");
    $staff_stmt->execute();
    $staff_requests = $staff_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Fetch Requests Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading requests: " . $e->getMessage();
}

include('./includes/dean_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Requests - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-check-circle me-2"></i>Approve Requests</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Pending Staff Requests -->
        <h3 class="mb-3">Pending Staff Requests from Department Heads</h3>
        <?php if (empty($staff_requests)): ?>
            <p class="text-center">No pending staff requests found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Request ID</th>
                            <th>Department Head</th>
                            <th>Description</th>
                            <th>Positions Requested</th>
                            <th>Submission Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($staff_requests as $request): ?>
                            <tr>
                                <td><?= htmlspecialchars($request['request_id']); ?></td>
                                <td><?= htmlspecialchars($request['username']); ?></td>
                                <td><?= htmlspecialchars($request['request_description']); ?></td>
                                <td><?= htmlspecialchars($request['requested_positions']); ?></td>
                                <td><?= htmlspecialchars(date('F j, Y, H:i', strtotime($request['submission_date']))); ?></td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="request_id" value="<?= $request['request_id']; ?>">
                                        <button type="submit" name="action_staff" value="approve" class="btn btn-success btn-sm me-1"><i class="fas fa-check"></i> Approve</button>
                                        <button type="submit" name="action_staff" value="reject" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Reject</button>
                                    </form>
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