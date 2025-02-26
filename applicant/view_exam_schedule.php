<?php
session_start();
include('../config/database.php'); // Database connection
include('./includes/applicant_navbar.php'); 

$sql = "SELECT exam_id, subject, date, time, venue FROM Exam_Schedule ORDER BY date ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
    <h2 class="text-center mb-4">Upcoming Exam Schedule</h2>

    <div class="table-responsive">
        <table class="table table-striped table-bordered shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Venue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exams as $exam): ?>
                    <tr>
                        <td><?= htmlspecialchars($exam['subject']); ?></td>
                        <td><?= htmlspecialchars($exam['date']); ?></td>
                        <td><?= htmlspecialchars($exam['time']); ?></td>
                        <td><?= htmlspecialchars($exam['venue']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>


<?php include('../includes/footer.php'); ?>
