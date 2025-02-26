<?php
session_start();
include('../config/database.php');
include('./includes/applicant_navbar.php'); 


// Get the applicant's ID from the session
$applicant_id = $_SESSION['applicant_id']; 

// Fetch applicant data to ensure the applicant exists
$sql = "SELECT name, email FROM applicants WHERE applicant_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$applicant_id]);
$applicant = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch exam results for the logged-in applicant
$sql = "SELECT exam_id, subject, score, status FROM exam_results WHERE applicant_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$applicant_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <!-- <h2 class="text-center">Exam Results for <?= htmlspecialchars($applicant['']); ?></h2> -->

        <?php if (empty($results)): ?>
            <p class="text-center">No exam results found for this applicant.</p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
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
        <?php endif; ?>
    </div>
</body>

<?php include('../includes/footer.php'); ?>
</html>
