<?php
session_start();
require_once('../config/database.php');
require_once('./includes/applicant_navbar.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Applicant') {
    $_SESSION['error'] = "Please log in to access this page.";
    header("Location: ../public/login.php");
    exit();
}

$applicant_id = $_SESSION['user_id'];

try {
    // Fetch applicant data
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$applicant_id]);
    $applicant = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$applicant) {
        $_SESSION['error'] = "Applicant not found.";
        header("Location: ../public/login.php");
        exit();
    }

    // Fetch exam results
    $sql = "SELECT exam_id, subject, score, status FROM exam_results WHERE applicant_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$applicant_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Exam Results Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading exam results. Please try again.";
    header("Location: applicant_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Exam Results for <?= htmlspecialchars($applicant['username']); ?></h2>

        <?php if (empty($results)): ?>
            <p class="text-center">No exam results found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Subject</th>
                            <th>Score</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $result): ?>
                            <tr>
                                <td><?= htmlspecialchars($result['subject']); ?></td>
                                <td><?= htmlspecialchars($result['score']); ?></td>
                                <td><?= htmlspecialchars($result['status']); ?></td>
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