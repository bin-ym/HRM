<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    $_SESSION['error'] = "Access denied. Please log in as an HR Officer.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT a.id, a.applicant_id, a.job_id, a.status, a.resume_path, a.created_at, u.username, j.job_title 
                            FROM applications a 
                            JOIN users u ON a.applicant_id = u.id 
                            JOIN job_openings j ON a.job_id = j.job_id 
                            ORDER BY a.created_at DESC");
    $stmt->execute();
    $applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("View Applicants Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading applicants: " . $e->getMessage();
}

include('./includes/hr_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicants - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Applicant List</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if ($applicants): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Applicant</th>
                            <th>Job Title</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Resume</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applicants as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']); ?></td>
                                <td><?= htmlspecialchars($row['username']); ?></td>
                                <td><?= htmlspecialchars($row['job_title']); ?></td>
                                <td><?= htmlspecialchars($row['status']); ?></td>
                                <td><?= htmlspecialchars($row['created_at']); ?></td>
                                <td>
                                    <?php if ($row['resume_path'] && file_exists("../uploads/{$row['resume_path']}")): ?>
                                        <a href="../uploads/<?= htmlspecialchars($row['resume_path']); ?>" target="_blank" class="btn btn-primary btn-sm">View</a>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['resume_path'] && file_exists("../uploads/{$row['resume_path']}")): ?>
                                        <a href="download.php?file=<?= urlencode($row['resume_path']); ?>" class="btn btn-success btn-sm">Download</a>
                                    <?php else: ?>
                                        <button class="btn btn-success btn-sm disabled">Download</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">No applicants found.</div>
        <?php endif; ?>
        <div class="text-center mt-3">
            <a href="hr_officer_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>