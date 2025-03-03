<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    $_SESSION['error'] = "Please log in as an Employee to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$employee_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_STRING);
    $end_date = filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_STRING);
    $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING);
    $leave_category = filter_input(INPUT_POST, 'leave_category', FILTER_SANITIZE_STRING);
    $leave_type = filter_input(INPUT_POST, 'leave_type', FILTER_SANITIZE_STRING);

    // Validate dates
    $today = date('Y-m-d');
    if (strtotime($start_date) < strtotime($today)) {
        $_SESSION['error'] = "Start date cannot be in the past.";
    } elseif (strtotime($start_date) > strtotime($end_date)) {
        $_SESSION['error'] = "Start date must be before end date.";
    } else {
        try {
            $sql = "INSERT INTO leave_requests (employee_id, start_date, end_date, reason, status, submission_date, leave_category, leave_type) 
                    VALUES (?, ?, ?, ?, 'pending', NOW(), ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$employee_id, $start_date, $end_date, $reason, $leave_category, $leave_type])) {
                $_SESSION['success'] = "permission submitted successfully!";
                header("Location: employee_dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Error submitting permission.";
            }
        } catch (PDOException $e) {
            error_log("permission Error: " . $e->getMessage());
            $_SESSION['error'] = "Error submitting permission: " . $e->getMessage();
        }
    }
}

// Handle status filter
$status_filter = isset($_GET['status']) ? filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING) : 'all';
$where_clause = $status_filter === 'all' ? '' : " AND status = ?";
$params = $status_filter === 'all' ? [$employee_id] : [$employee_id, $status_filter];

// Fetch existing permissions
try {
    $sql = "SELECT request_id, start_date, end_date, reason, status, submission_date, leave_category, leave_type 
            FROM leave_requests WHERE employee_id = ? $where_clause ORDER BY submission_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $leave_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Fetch permissions Error: " . $e->getMessage());
    $_SESSION['error'] = "Error fetching permissions: " . $e->getMessage();
}

include('employee_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>permission - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-calendar-alt me-2"></i>permissions</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- permissions Table -->
        <h3 class="mb-3">Your permissions</h3>
        <div class="mb-4">
            <label for="status_filter" class="form-label">Filter by Status:</label>
            <select class="form-select w-25 d-inline-block" id="status_filter" onchange="window.location='leave_request.php?status='+this.value">
                <option value="all" <?= $status_filter === 'all' ? 'selected' : ''; ?>>All</option>
                <option value="pending" <?= $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="approved" <?= $status_filter === 'approved' ? 'selected' : ''; ?>>Approved</option>
                <option value="rejected" <?= $status_filter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
            </select>
        </div>
        <?php if (empty($leave_requests)): ?>
            <p class="text-center">No permissions found for this filter.</p>
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
                            <th>Type</th>
                            <th>Status</th>
                            <th>Submission Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leave_requests as $request): ?>
                            <tr>
                                <td><?= htmlspecialchars($request['request_id']); ?></td>
                                <td><?= htmlspecialchars(date('F j, Y', strtotime($request['start_date']))); ?></td>
                                <td><?= htmlspecialchars(date('F j, Y', strtotime($request['end_date']))); ?></td>
                                <td><?= htmlspecialchars($request['reason']); ?></td>
                                <td><?= htmlspecialchars($request['leave_category']); ?></td>
                                <td><?= htmlspecialchars($request['leave_type']); ?></td>
                                <td>
                                    <span class="badge <?= $request['status'] === 'approved' ? 'bg-success' : ($request['status'] === 'rejected' ? 'bg-danger' : 'bg-warning'); ?>">
                                        <?= htmlspecialchars($request['status']); ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars(date('F j, Y, H:i', strtotime($request['submission_date']))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- permission Form -->
        <h3 class="mt-5 mb-3">Submit New permission</h3>
        <div class="card shadow-sm p-4">
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" min="<?= date('Y-m-d'); ?>" required>
                    <div class="invalid-feedback">Please select a valid start date (not in the past).</div>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" min="<?= date('Y-m-d'); ?>" required>
                    <div class="invalid-feedback">Please select a valid end date (after start date).</div>
                </div>
                <div class="mb-3">
                    <label for="reason" class="form-label">Reason</label>
                    <textarea class="form-control" id="reason" name="reason" rows="3" required placeholder="Enter reason for permistion"></textarea>
                    <div class="invalid-feedback">Please provide a reason.</div>
                </div>
                <div class="mb-3">
                    <label for="leave_category" class="form-label">permision Category</label>
                    <select class="form-select" id="leave_category" name="leave_category" required>
                        <option value="" disabled selected>Select category</option>
                        <option value="Vacation">Vacation</option>
                        <option value="Sick">Sick</option>
                        <option value="Personal">Personal</option>
                    </select>
                    <div class="invalid-feedback">Please select a category.</div>
                </div>
                <div class="mb-3">
                    <label for="leave_type" class="form-label">permission Type</label>
                    <select class="form-select" id="leave_type" name="leave_type" required>
                        <option value="" disabled selected>Select type</option>
                        <option value="Paid">Paid</option>
                        <option value="Unpaid">Unpaid</option>
                    </select>
                    <div class="invalid-feedback">Please select a type.</div>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                    <a href="employee_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </form>
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