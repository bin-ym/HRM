<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../public/login.php");
    exit();
}

$query = "SELECT * FROM job_rankings WHERE status = 'Pending'";
$stmt = $conn->query($query); // PDO query method

include './includes/hr_navbar.php'; // Fixed navbar path
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approve Job Ranking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Approve Job Ranking</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Ranking</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Corrected line ?>
                <tr>
                    <td><?= htmlspecialchars($row['applicant_name']); ?></td>
                    <td><?= htmlspecialchars($row['ranking']); ?></td>
                    <td>
                        <a href="approve_ranking.php?id=<?= $row['id']; ?>" class="btn btn-success">Approve</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
