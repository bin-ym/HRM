<?php
session_start();

// Ensure the database configuration file exists
if (!file_exists('../config/database.php')) {
    die("Database configuration file not found.");
}

require_once '../config/database.php'; // Include the database configuration

// Check if the user is logged in and has the 'HR Officer' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../public/login.php");
    exit();
}

// Handle form submission to add a new vacancy
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['job_title']) && isset($_POST['description']) && isset($_POST['deadline']) && isset($_POST['department'])) {
        $job_title = $_POST['job_title'];
        $description = $_POST['description'];
        $deadline = $_POST['deadline'];
        $department = $_POST['department'];

        // Insert query for adding a new vacancy
        $query = "INSERT INTO vacancies (job_title, description, deadline, created_at, department, status) 
                  VALUES (:job_title, :description, :deadline, NOW(), :department, 'Open')"; 

        if ($conn instanceof PDO) {
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':job_title', $job_title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':deadline', $deadline);
            $stmt->bindParam(':department', $department);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Vacancy posted successfully!";
                header("Location: announce_vacancy.php"); // Redirect to avoid resubmission
                exit();
            } else {
                $_SESSION['error'] = "Error posting vacancy.";
            }
        } else {
            $_SESSION['error'] = "Database connection failed.";
        }
    } else {
        $_SESSION['error'] = "Please fill all the fields.";
    }
}

// Edit vacancy handling
if (isset($_GET['edit_id'])) {
    $vacancy_id = $_GET['edit_id'];
    $query = "SELECT * FROM vacancies WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $vacancy_id);
    $stmt->execute();
    $vacancy = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vacancy) {
        $job_title = $vacancy['job_title'];
        $description = $vacancy['description'];
        $deadline = $vacancy['deadline'];
        $department = $vacancy['department'];
    }
}

// Update vacancy
if (isset($_POST['update_vacancy'])) {
    $vacancy_id = $_POST['vacancy_id'];
    $job_title = $_POST['job_title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $department = $_POST['department'];

    $update_query = "UPDATE vacancies SET job_title = :job_title, description = :description, deadline = :deadline, department = :department WHERE id = :id";
    $stmt = $conn->prepare($update_query);
    $stmt->bindParam(':job_title', $job_title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':deadline', $deadline);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':id', $vacancy_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Vacancy updated successfully!";
        header("Location: announce_vacancy.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating vacancy.";
    }
}

// Delete vacancy
if (isset($_GET['delete_id'])) {
    $vacancy_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM vacancies WHERE id = :id";
    $stmt = $conn->prepare($delete_query);
    $stmt->bindParam(':id', $vacancy_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Vacancy deleted successfully!";
        header("Location: announce_vacancy.php");
        exit();
    } else {
        $_SESSION['error'] = "Error deleting vacancy.";
    }
}

// Fetch all existing vacancies
$query = "SELECT * FROM vacancies";
$stmt = $conn->prepare($query);
$stmt->execute();
$vacancies = $stmt->fetchAll(PDO::FETCH_ASSOC);

include './includes/hr_navbar.php'; // Ensure the correct path to navbar.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announce Vacancy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Post New Job Vacancy</h2>
    <form method="post">
        <div class="mb-3">
            <label>Job Title</label>
            <input type="text" name="job_title" class="form-control" value="<?= isset($job_title) ? htmlspecialchars($job_title) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" required><?= isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
        </div>
        <div class="mb-3">
            <label>Application Deadline</label>
            <input type="date" name="deadline" class="form-control" value="<?= isset($deadline) ? $deadline : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label>Department</label>
            <input type="text" name="department" class="form-control" value="<?= isset($department) ? htmlspecialchars($department) : ''; ?>" required>
        </div>
        <button type="submit" class="btn btn-success"><?= isset($vacancy) ? 'Update Vacancy' : 'Post Vacancy'; ?></button>
        
        <?php if (isset($vacancy)): ?>
            <input type="hidden" name="vacancy_id" value="<?= $vacancy_id; ?>">
            <input type="hidden" name="update_vacancy">
        <?php endif; ?>
    </form>

    <hr>

    <!-- Table to display vacancies -->
    <h3 class="text-center mt-4">Vacancies</h3>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Description</th>
                <th>Deadline</th>
                <th>Department</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vacancies as $vacancy): ?>
                <tr>
                    <td><?= htmlspecialchars($vacancy['job_title']); ?></td>
                    <td><?= htmlspecialchars($vacancy['description']); ?></td>
                    <td><?= htmlspecialchars($vacancy['deadline']); ?></td>
                    <td><?= htmlspecialchars($vacancy['department']); ?></td>
                    <td><?= htmlspecialchars($vacancy['status']); ?></td>
                    <td><?= htmlspecialchars($vacancy['created_at']); ?></td>
                    <td>
                        <!-- Edit Button -->
                        <a href="?edit_id=<?= $vacancy['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        
                        <!-- Delete Button -->
                        <a href="?delete_id=<?= $vacancy['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this vacancy?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
