<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Department Head') {
    $_SESSION['error'] = "Please log in as a Department Head to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = filter_input(INPUT_POST, 'application_id', FILTER_SANITIZE_NUMBER_INT);
    $ranking = filter_input(INPUT_POST, 'ranking', FILTER_SANITIZE_NUMBER_INT);

    try {
        $sql = "UPDATE applications SET ranking = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$ranking, $application_id])) {
            $_SESSION['success'] = "Job ranking submitted successfully!";
            header("Location: submit_job_ranking.php");
            exit();
        } else {
            $_SESSION['error'] = "Error submitting job ranking.";
        }
    } catch (PDOException $e) {
        error_log("Job Ranking Error: " . $e->getMessage());
        $_SESSION['error'] = "Error submitting job ranking: " . $e->getMessage();
    }
}

try {
    $stmt = $conn->prepare("SELECT a.id, a.applicant_id, a.job_id, a.status, u.username, j.job_title, a.ranking 
                            FROM applications a 
                            JOIN users u ON a.applicant_id = u.id 
                            JOIN job_openings j ON a.job_id = j.job_id 
                            WHERE a.status = 'Pending' 
                            ORDER BY a.created_at DESC");
    $stmt->execute();
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Fetch Applications Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading applications: " . $e->getMessage();
}

include('./includes/department_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Job Ranking - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-star me-2"></i>Submit Job Ranking</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (empty($applications)): ?>
            <p class="text-center">No pending applications to rank.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Applicant</th>
                            <th>Job Title</th>
                            <th>Current Ranking</th>
                            <th>Submit Ranking</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td><?= htmlspecialchars($app['id']); ?></td>
                                <td><?= htmlspecialchars($app['username']); ?></td>
                                <td><?= htmlspecialchars($app['job_title']); ?></td>
                                <td><?= htmlspecialchars($app['ranking'] ?? 'Not Ranked'); ?></td>
                                <td>
                                    <form method="POST" class="d-flex align-items-center">
                                        <input type="hidden" name="application_id" value="<?= $app['id']; ?>">
                                        <select name="ranking" class="form-select me-2" style="width: 100px;">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">Rank</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <div class="text-center mt-3">
            <a href="department_head_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>