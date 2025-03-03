<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Department Head') {
    $_SESSION['error'] = "Please log in as a Department Head to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$dept_head_id = $_SESSION['user_id'];

// Handle approval/rejection of employee leave requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $request_id = filter_input(INPUT_POST, 'request_id', FILTER_SANITIZE_NUMBER_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    try {
        $status = $action === 'approve' ? 'approved' : 'rejected';
        $sql = "UPDATE leave_requests SET status = ?, approved_by = ? WHERE request_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$status, $dept_head_id, $request_id])) {
            $_SESSION['success'] = "Leave request $status successfully!";
            header("Location: employee_requests.php");
            exit();
        } else {
            $_SESSION['error'] = "Error updating leave request.";
        }
    } catch (PDOException $e) {
        error_log("Approve Leave Request Error: " . $e->getMessage());
        $_SESSION['error'] = "Error updating leave request: " . $e->getMessage();
    }
}

// Handle Department Head staff request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_staff_request'])) {
    $request_description = filter_input(INPUT_POST, 'request_description', FILTER_SANITIZE_STRING);
    $requested_positions = filter_input(INPUT_POST, 'requested_positions', FILTER_SANITIZE_NUMBER_INT);

    try {
        $sql = "INSERT INTO staff_requests (dept_head_id, request_description, requested_positions, status, submission_date) 
                VALUES (?, ?, ?, 'pending', NOW())";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$dept_head_id, $request_description, $requested_positions])) {
            $_SESSION['success'] = "Staff request submitted to the Dean successfully!";
            header("Location: employee_requests.php");
            exit();
        } else {
            $_SESSION['error'] = "Error submitting staff request.";
        }
    } catch (PDOException $e) {
        error_log("Submit Staff Request Error: " . $e->getMessage());
        $_SESSION['error'] = "Error submitting staff request: " . $e->getMessage();
    }
}

try {
    // Fetch pending employee leave requests (excluding Department Head's own leave requests)
    $stmt = $conn->prepare("SELECT lr.request_id, lr.employee_id, lr.start_date, lr.end_date, lr.reason, lr.status, lr.leave_category, u.username 
                            FROM leave_requests lr 
                            JOIN users u ON lr.employee_id = u.id 
                            WHERE lr.status = 'pending' AND lr.employee_id != ? 
                            ORDER BY lr.submission_date ASC");
    $stmt->execute([$dept_head_id]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Fetch Leave Requests Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading leave requests: " . $e->getMessage();
}

include('./includes/department_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Requests - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-users me-2"></i>Employee Requests</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Department Head Staff Request Form -->
        <h3 class="mb-3">Request Additional Staff</h3>
        <div class="card shadow-sm p-4 mb-5">
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="request_description" class="form-label">Request Description</label>
                    <textarea class="form-control" id="request_description" name="request_description" rows="3" required placeholder="Describe the need for additional staff"></textarea>
                    <div class="invalid-feedback">Please provide a description.</div>
                </div>
                <div class="mb-3">
                    <label for="requested_positions" class="form-label">Number of Positions Needed</label>
                    <input type="number" class="form-control" id="requested_positions" name="requested_positions" min="1" required>
                    <div class="invalid-feedback">Please enter the number of positions needed (minimum 1).</div>
                </div>
                <button type="submit" name="submit_staff_request" class="btn btn-primary">Submit to Dean</button>
            </form>
        </div>

        <!-- Employee Leave Requests Table -->
        <h3 class="mb-3">Review Employee Leave Requests</h3>
        <?php if (empty($requests)): ?>
            <p class="text-center">No pending employee leave requests found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Request ID</th>
                            <th>Employee</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Reason</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?= htmlspecialchars($request['request_id']); ?></td>
                                <td><?= htmlspecialchars($request['username']); ?></td>
                                <td><?= htmlspecialchars(date('F j, Y', strtotime($request['start_date']))); ?></td>
                                <td><?= htmlspecialchars(date('F j, Y', strtotime($request['end_date']))); ?></td>
                                <td><?= htmlspecialchars($request['reason']); ?></td>
                                <td><?= htmlspecialchars($request['leave_category']); ?></td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="request_id" value="<?= $request['request_id']; ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm me-1"><i class="fas fa-check"></i> Approve</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <div class="text-center mt-3">
            <a href="department_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        (function () {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>