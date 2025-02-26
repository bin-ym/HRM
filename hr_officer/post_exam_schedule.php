<?php
session_start();
require_once '../config/database.php';

// Initialize form variables
$exam_title = $exam_date = $exam_time = $position = $location = '';

// Check user access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../public/login.php");
    exit();
}

// Edit exam schedule handling
if (isset($_GET['edit_id'])) {
    $exam_id = $_GET['edit_id'];
    $query = "SELECT * FROM exam_schedule WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $exam_id);
    $stmt->execute();
    $exam_schedule = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($exam_schedule) {
        $exam_title = $exam_schedule['exam_title'];
        $exam_date = $exam_schedule['exam_date'];
        $exam_time = $exam_schedule['exam_time'];
        $position = $exam_schedule['position'];
        $location = $exam_schedule['location'];
    }
}

// Insert or update exam schedule
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $exam_title = $_POST['exam_title'] ?? '';
    $exam_date = $_POST['exam_date'] ?? '';
    $exam_time = $_POST['exam_time'] ?? '';
    $position = $_POST['position'] ?? '';
    $location = $_POST['location'] ?? '';

    if (isset($_POST['exam_id'])) { // Update existing exam schedule
        $exam_id = $_POST['exam_id'];
        $query = "UPDATE exam_schedule SET exam_title = :exam_title, exam_date = :exam_date, exam_time = :exam_time, position = :position, location = :location WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':exam_title', $exam_title);
        $stmt->bindParam(':exam_date', $exam_date);
        $stmt->bindParam(':exam_time', $exam_time);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':id', $exam_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Exam schedule updated!";
            // Redirect to clear the form after update
            header("Location: post_exam_schedule.php");
            exit();
        } else {
            $_SESSION['error'] = "Error updating schedule.";
        }
    } else { // Insert new exam schedule
        $query = "INSERT INTO exam_schedule (exam_title, exam_date, exam_time, position, location) 
                  VALUES ('$exam_title', '$exam_date', '$exam_time', '$position', '$location')";
        if ($conn->query($query)) {
            $_SESSION['success'] = "Exam schedule posted!";
            header("Location: post_exam_schedule.php");
            exit();
        } else {
            $_SESSION['error'] = "Error posting schedule.";
        }
    }
}

// Delete exam schedule handling
if (isset($_GET['delete_id'])) {
    $exam_id = $_GET['delete_id'];
    $query = "DELETE FROM exam_schedule WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $exam_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Exam schedule deleted!";
        header("Location: post_exam_schedule.php");
        exit();
    } else {
        $_SESSION['error'] = "Error deleting schedule.";
    }
}

// Fetch exam schedules
$query = "SELECT * FROM exam_schedule";
$stmt = $conn->prepare($query);
$stmt->execute();
$exam_schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

include './includes/hr_navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post and View Exam Schedules</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <!-- Form to Post or Edit Exam Schedule -->
    <h2 class="text-center mb-4"><?= isset($exam_schedule) ? 'Edit Exam Schedule' : 'Post Exam Schedule'; ?></h2>
    <form method="post">
        <div class="mb-3">
            <label>Exam Title</label>
            <input type="text" name="exam_title" class="form-control" value="<?= htmlspecialchars($exam_title); ?>" required>
        </div>
        <div class="mb-3">
            <label>Exam Date</label>
            <input type="date" name="exam_date" class="form-control" value="<?= htmlspecialchars($exam_date); ?>" required>
        </div>
        <div class="mb-3">
            <label>Exam Time</label>
            <input type="time" name="exam_time" class="form-control" value="<?= htmlspecialchars($exam_time); ?>" required>
        </div>
        <div class="mb-3">
            <label>Position</label>
            <input type="text" name="position" class="form-control" value="<?= htmlspecialchars($position); ?>" required>
        </div>
        <div class="mb-3">
            <label>Location</label>
            <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($location); ?>" required>
        </div>
        <button type="submit" class="btn btn-success"><?= isset($exam_schedule) ? 'Update Schedule' : 'Post Schedule'; ?></button>
        
        <?php if (isset($exam_schedule)): ?>
            <input type="hidden" name="exam_id" value="<?= $exam_id; ?>">
        <?php endif; ?>
    </form>

    <!-- Display Existing Exam Schedules -->
    <h2 class="text-center mt-5 mb-4">View Exam Schedules</h2>

    <?php if (!empty($exam_schedules)) { ?>
        <table class="table table-striped table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Exam Title</th>
                    <th>Exam Date</th>
                    <th>Exam Time</th>
                    <th>Position</th>
                    <th>Location</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    <?php foreach ($exam_schedules as $row) { ?>
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
    <?php } ?>
</tbody>
        </table>
    <?php } else { ?>
        <div class="alert alert-warning text-center">No exam schedules found.</div>
    <?php } ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
