<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Dean') {
    $_SESSION['error'] = "Please log in as a Dean to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

try {
    // Total applications
    $total_stmt = $conn->prepare("SELECT COUNT(*) FROM applications");
    $total_stmt->execute();
    $total_applications = $total_stmt->fetchColumn();

    // Approved applications
    $approved_stmt = $conn->prepare("SELECT COUNT(*) FROM applications WHERE status = 'Approved'");
    $approved_stmt->execute();
    $approved_applications = $approved_stmt->fetchColumn();

    // Rejected applications
    $rejected_stmt = $conn->prepare("SELECT COUNT(*) FROM applications WHERE status = 'Rejected'");
    $rejected_stmt->execute();
    $rejected_applications = $rejected_stmt->fetchColumn();

    // Pending applications
    $pending_stmt = $conn->prepare("SELECT COUNT(*) FROM applications WHERE status = 'Pending'");
    $pending_stmt->execute();
    $pending_applications = $pending_stmt->fetchColumn();

    // Applications by job
    $job_stmt = $conn->prepare("SELECT j.job_title, COUNT(a.id) as app_count 
                                FROM applications a 
                                JOIN job_openings j ON a.job_id = j.job_id 
                                GROUP BY j.job_id, j.job_title");
    $job_stmt->execute();
    $job_stats = $job_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Statistics Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading statistics: " . $e->getMessage();
}

include('../includes/dean_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruitment Statistics - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-chart-bar me-2"></i>Recruitment Statistics</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Applications</h5>
                        <p class="card-text display-6"><?= $total_applications ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Approved</h5>
                        <p class="card-text display-6 text-success"><?= $approved_applications ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Rejected</h5>
                        <p class="card-text display-6 text-danger"><?= $rejected_applications ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Pending</h5>
                        <p class="card-text display-6 text-warning"><?= $pending_applications ?></p>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mb-3">Applications by Job</h3>
        <?php if (empty($job_stats)): ?>
            <p class="text-center">No job-specific statistics available.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Job Title</th>
                            <th>Application Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($job_stats as $stat): ?>
                            <tr>
                                <td><?= htmlspecialchars($stat['job_title']); ?></td>
                                <td><?= htmlspecialchars($stat['app_count']); ?></td>
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