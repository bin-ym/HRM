<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Dean') {
    $_SESSION['error'] = "Please log in as a Dean to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT a.id, a.applicant_id, a.job_id, a.status, a.resume_path, a.created_at, u.username, j.job_title 
                            FROM applications a 
                            JOIN users u ON a.applicant_id = u.id 
                            JOIN job_openings j ON a.job_id = j.job_id 
                            WHERE a.status = 'Pending' 
                            ORDER BY a.created_at DESC");
    $stmt->execute();
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("View Applications Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading applications: " . $e->getMessage();
}

include('../includes/dean_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applications - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-users me-2"></i>Pending Applications</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (empty($applications)): ?>
            <p class="text-center">No pending applications found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Application ID</th>
                            <th>Applicant</th>
                            <th>Job Title</th>
                            <th>Status</th>
                            <th>Resume</th>
                            <th>Submission Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td><?= htmlspecialchars($app['id']); ?></td>
                                <td><?= htmlspecialchars($app['username']); ?></td>
                                <td><?= htmlspecialchars($app['job_title']); ?></td>
                                <td>
                                    <span class="badge bg-warning"><?= htmlspecialchars($app['status']); ?></span>
                                </td>
                                <td>
                                    <?php if ($app['resume_path']): ?>
                                        <a href="<?= htmlspecialchars($app['resume_path']); ?>" target="_blank" class="btn btn-info btn-sm">View Resume</a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars(date('F j, Y, H:i', strtotime($app['created_at']))); ?></td>
                                <td>
                                    <a href="decision.php?app_id=<?= $app['id']; ?>" class="btn btn-primary btn-sm">Make Decision</a>
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