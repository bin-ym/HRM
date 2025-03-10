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
    // READ: Fetch exam schedules for this applicant
    $sql = "SELECT id, date FROM exam_schedules WHERE applicant_id = ? ORDER BY date ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$applicant_id]);
    $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Exam Schedule Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading exam schedule: " . $e->getMessage();
    header("Location: applicant_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Schedule - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Upcoming Exam Schedule</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (empty($exams)): ?>
            <p class="text-center">No upcoming exams scheduled.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Exam ID</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exams as $exam): ?>
                            <tr>
                                <td><?= htmlspecialchars($exam['id']); ?></td>
                                <td><?= htmlspecialchars(date('F j, Y, H:i', strtotime($exam['date']))); ?></td>
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