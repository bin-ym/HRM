<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    $_SESSION['error'] = "Access denied. Please log in as an HR Officer.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $job_title = filter_input(INPUT_POST, 'job_title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $deadline = filter_input(INPUT_POST, 'application_deadline', FILTER_SANITIZE_STRING);
    $department = filter_input(INPUT_POST, 'department', FILTER_SANITIZE_STRING);

    try {
        if (isset($_POST['update_vacancy']) && isset($_POST['vacancy_id'])) {
            $vacancy_id = filter_input(INPUT_POST, 'vacancy_id', FILTER_SANITIZE_NUMBER_INT);
            $sql = "UPDATE job_openings SET job_title = ?, description = ?, application_deadline = ?, department = ? WHERE job_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$job_title, $description, $deadline, $department, $vacancy_id]);
            $_SESSION['success'] = "Vacancy updated successfully!";
        } else {
            $sql = "INSERT INTO job_openings (job_title, description, application_deadline, department, status, created_at) 
                    VALUES (?, ?, ?, ?, 'Open', NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$job_title, $description, $deadline, $department]);
            $_SESSION['success'] = "Vacancy posted successfully!";
        }
        header("Location: announce_vacancy.php");
        exit();
    } catch (PDOException $e) {
        error_log("Vacancy Error: " . $e->getMessage());
        $_SESSION['error'] = "Error processing vacancy: " . $e->getMessage();
    }
}

// Handle deletion
if (isset($_GET['delete_id'])) {
    $vacancy_id = filter_input(INPUT_GET, 'delete_id', FILTER_SANITIZE_NUMBER_INT);
    try {
        $stmt = $conn->prepare("DELETE FROM job_openings WHERE job_id = ?");
        $stmt->execute([$vacancy_id]);
        $_SESSION['success'] = "Vacancy deleted successfully!";
        header("Location: announce_vacancy.php");
        exit();
    } catch (PDOException $e) {
        error_log("Delete Vacancy Error: " . $e->getMessage());
        $_SESSION['error'] = "Error deleting vacancy.";
    }
}

// Fetch edit data
$edit_vacancy = null;
if (isset($_GET['edit_id'])) {
    $vacancy_id = filter_input(INPUT_GET, 'edit_id', FILTER_SANITIZE_NUMBER_INT);
    $stmt = $conn->prepare("SELECT * FROM job_openings WHERE job_id = ?");
    $stmt->execute([$vacancy_id]);
    $edit_vacancy = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch all vacancies
try {
    $stmt = $conn->prepare("SELECT * FROM job_openings ORDER BY created_at DESC");
    $stmt->execute();
    $vacancies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Fetch Vacancies Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading vacancies.";
}

include('./includes/hr_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announce Vacancy - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4"><?= $edit_vacancy ? 'Edit Vacancy' : 'Post New Job Vacancy'; ?></h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="job_title" class="form-label">Job Title</label>
                <input type="text" name="job_title" id="job_title" class="form-control" value="<?= htmlspecialchars($edit_vacancy['job_title'] ?? ''); ?>" required>
                <div class="invalid-feedback">Please enter a job title.</div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3" required><?= htmlspecialchars($edit_vacancy['description'] ?? ''); ?></textarea>
                <div class="invalid-feedback">Please enter a description.</div>
            </div>
            <div class="mb-3">
                <label for="application_deadline" class="form-label">Application Deadline</label>
                <input type="date" name="application_deadline" id="application_deadline" class="form-control" value="<?= htmlspecialchars($edit_vacancy['application_deadline'] ?? ''); ?>" required>
                <div class="invalid-feedback">Please enter a deadline.</div>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <input type="text" name="department" id="department" class="form-control" value="<?= htmlspecialchars($edit_vacancy['department'] ?? ''); ?>" required>
                <div class="invalid-feedback">Please enter a department.</div>
            </div>
            <?php if ($edit_vacancy): ?>
                <input type="hidden" name="vacancy_id" value="<?= $edit_vacancy['job_id']; ?>">
                <input type="hidden" name="update_vacancy" value="1">
            <?php endif; ?>
            <button type="submit" class="btn btn-success"><?= $edit_vacancy ? 'Update Vacancy' : 'Post Vacancy'; ?></button>
        </form>

        <hr>
        <h3 class="text-center mt-4">Vacancies</h3>
        <?php if ($vacancies): ?>
            <div class="table-responsive">
                <table class="table table-bordered mt-4">
                    <thead class="table-dark">
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
                                <td><?= htmlspecialchars($vacancy['application_deadline']); ?></td>
                                <td><?= htmlspecialchars($vacancy['department']); ?></td>
                                <td><?= htmlspecialchars($vacancy['status']); ?></td>
                                <td><?= htmlspecialchars($vacancy['created_at']); ?></td>
                                <td>
                                    <a href="?edit_id=<?= $vacancy['job_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="?delete_id=<?= $vacancy['job_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this vacancy?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">No vacancies found.</div>
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