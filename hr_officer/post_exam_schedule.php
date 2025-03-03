<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $exam_title = filter_input(INPUT_POST, 'exam_title', FILTER_SANITIZE_STRING);
    $exam_date = filter_input(INPUT_POST, 'exam_date', FILTER_SANITIZE_STRING);
    $exam_time = filter_input(INPUT_POST, 'exam_time', FILTER_SANITIZE_STRING);
    $position = filter_input(INPUT_POST, 'position', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);

    try {
        if (isset($_POST['exam_id'])) {
            $exam_id = filter_input(INPUT_POST, 'exam_id', FILTER_SANITIZE_NUMBER_INT);
            $sql = "UPDATE exam_schedules SET exam_title = ?, exam_date = ?, exam_time = ?, position = ?, location = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$exam_title, $exam_date, $exam_time, $position, $location, $exam_id]);
            $_SESSION['success'] = "Exam schedule updated!";
        } else {
            $sql = "INSERT INTO exam_schedules (exam_title, exam_date, exam_time, position, location) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$exam_title, $exam_date, $exam_time, $position, $location]);
            $_SESSION['success'] = "Exam schedule posted!";
        }
        header("Location: post_exam_schedule.php");
        exit();
    } catch (PDOException $e) {
        error_log("Exam Schedule Error: " . $e->getMessage());
        $_SESSION['error'] = "Error processing exam schedule: " . $e->getMessage();
    }
}

// Handle deletion
if (isset($_GET['delete_id'])) {
    $exam_id = filter_input(INPUT_GET, 'delete_id', FILTER_SANITIZE_NUMBER_INT);
    try {
        $stmt = $conn->prepare("DELETE FROM exam_schedules WHERE id = ?");
        $stmt->execute([$exam_id]);
        $_SESSION['success'] = "Exam schedule deleted!";
        header("Location: post_exam_schedule.php");
        exit();
    } catch (PDOException $e) {
        error_log("Delete Exam Schedule Error: " . $e->getMessage());
        $_SESSION['error'] = "Error deleting exam schedule.";
    }
}

// Fetch edit data with error checking
$edit_schedule = null;
if (isset($_GET['edit_id'])) {
    $exam_id = filter_input(INPUT_GET, 'edit_id', FILTER_SANITIZE_NUMBER_INT);
    try {
        $stmt = $conn->prepare("SELECT * FROM exam_schedules WHERE id = ?");
        $stmt->execute([$exam_id]);
        $edit_schedule = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$edit_schedule) {
            $_SESSION['error'] = "Exam schedule not found for ID: $exam_id.";
        }
    } catch (PDOException $e) {
        error_log("Fetch Edit Schedule Error: " . $e->getMessage());
        $_SESSION['error'] = "Error fetching exam schedule: " . $e->getMessage();
    }
}

// Fetch all exam schedules
try {
    $stmt = $conn->prepare("SELECT * FROM exam_schedules ORDER BY exam_date ASC");
    $stmt->execute();
    $exam_schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Fetch Exam Schedules Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading exam schedules: " . $e->getMessage();
}

include('./includes/hr_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Exam Schedule - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4"><?= $edit_schedule ? 'Edit Exam Schedule' : 'Post Exam Schedule'; ?></h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="exam_title" class="form-label">Exam Title</label>
                <input type="text" name="exam_title" id="exam_title" class="form-control" value="<?= htmlspecialchars($edit_schedule['exam_title'] ?? ''); ?>" required>
                <div class="invalid-feedback">Please enter an exam title.</div>
            </div>
            <div class="mb-3">
                <label for="exam_date" class="form-label">Exam Date</label>
                <input type="date" name="exam_date" id="exam_date" class="form-control" value="<?= htmlspecialchars($edit_schedule['exam_date'] ?? ''); ?>" required>
                <div class="invalid-feedback">Please enter an exam date.</div>
            </div>
            <div class="mb-3">
                <label for="exam_time" class="form-label">Exam Time</label>
                <input type="time" name="exam_time" id="exam_time" class="form-control" value="<?= htmlspecialchars($edit_schedule['exam_time'] ?? ''); ?>" required>
                <div class="invalid-feedback">Please enter an exam time.</div>
            </div>
            <div class="mb-3">
                <label for="position" class="form-label">Position</label>
                <input type="text" name="position" id="position" class="form-control" value="<?= htmlspecialchars($edit_schedule['position'] ?? ''); ?>" required>
                <div class="invalid-feedback">Please enter a position.</div>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" id="location" class="form-control" value="<?= htmlspecialchars($edit_schedule['location'] ?? ''); ?>" required>
                <div class="invalid-feedback">Please enter a location.</div>
            </div>
            <?php if ($edit_schedule): ?>
                <input type="hidden" name="exam_id" value="<?= htmlspecialchars($edit_schedule['id']); ?>">
            <?php endif; ?>
            <button type="submit" class="btn btn-success"><?= $edit_schedule ? 'Update Schedule' : 'Post Schedule'; ?></button>
        </form>

        <h2 class="text-center mt-5 mb-4">Exam Schedules</h2>
        <?php if ($exam_schedules): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Exam Title</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Position</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exam_schedules as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']); ?></td>
                                <td><?= htmlspecialchars($row['exam_title']); ?></td>
                                <td><?= htmlspecialchars($row['exam_date']); ?></td>
                                <td><?= htmlspecialchars($row['exam_time']); ?></td>
                                <td><?= htmlspecialchars($row['position']); ?></td>
                                <td><?= htmlspecialchars($row['location']); ?></td>
                                <td>
                                    <a href="?edit_id=<?= $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="?delete_id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this exam schedule?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">No exam schedules found.</div>
        <?php endif; ?>
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