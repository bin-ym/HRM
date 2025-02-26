<?php
session_start();
require_once '../config/database.php'; // Ensure the database configuration is included

// Ensure user is logged in as HR Officer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../public/login.php");
    exit();
}

// Corrected path for navbar inclusion
include './includes/hr_navbar.php';

// Check if the database connection is set
if (!isset($conn)) {
    die("Database connection is not set. Check your database.php file.");
}

// Fetch applicants using PDO
$query = "SELECT * FROM applicants";
$stmt = $conn->prepare($query);
$stmt->execute();
$applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Applicant List</h2>

    <?php if (!empty($applicants)) { ?>
        <table class="table table-striped table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Created At</th>
                    <th>Resume</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applicants as $row) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']); ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['address']); ?></td>
                        <td><?= htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <!-- View Resume Button -->
                            <a href="../uploads/<?= htmlspecialchars($row['resume']); ?>" target="_blank" class="btn btn-primary btn-sm">View</a>
                        </td>
                        <td>
                            <!-- Download Resume Button -->
                            <a href="../uploads/<?= htmlspecialchars($row['resume']); ?>" download class="btn btn-success btn-sm">Download</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <div class="alert alert-warning text-center">No applicants found.</div>
    <?php } ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
