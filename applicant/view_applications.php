<?php
session_start();
require_once('../config/database.php');
require_once('./includes/applicant_navbar.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Applicant') {
    $_SESSION['error'] = "Please log in to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$applicant_id = $_SESSION['user_id'];

try {
    $sql = "SELECT a.id, a.job_id, a.status, a.created_at, a.resume_path, j.job_title 
            FROM applications a 
            JOIN job_openings j ON a.job_id = j.job_id 
            WHERE a.applicant_id = ? 
            ORDER BY a.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$applicant_id]);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Applications Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading applications: " . $e->getMessage();
    header("Location: applicant_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">My Applications</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (empty($applications)): ?>
            <p class="text-center">You have not submitted any applications yet.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Job Title</th>
                            <th>Status</th>
                            <th>Submitted On</th>
                            <th>Resume</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td><?= htmlspecialchars($app['job_title']); ?></td>
                                <td><?= htmlspecialchars($app['status']); ?></td>
                                <td><?= htmlspecialchars(date('F j, Y, H:i', strtotime($app['created_at']))); ?></td>
                                <td>
                                    <?php if ($app['resume_path']): ?>
                                        <a href="<?= htmlspecialchars($app['resume_path']); ?>" target="_blank" class="btn btn-info btn-sm">View</a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>