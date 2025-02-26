<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../public/login.php");
    exit();
}

// Corrected query to fetch feedback with user details
$query = "
    SELECT feedback.feedback_text, feedback.submitted_at, users.username AS submitted_by
    FROM feedback
    JOIN users ON feedback.user_id = users.id
    ORDER BY feedback.submitted_at DESC LIMIT 0, 25
";

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->execute();

// Fetch the result as an associative array
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

include './includes/hr_navbar.php'; // Fixed navbar path
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Employee & Applicant Feedback</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Submitted By</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feedbacks as $row) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['submitted_by']); ?></td>
                    <td><?= htmlspecialchars($row['feedback_text']); ?></td>
                    <td><?= htmlspecialchars($row['submitted_at']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
