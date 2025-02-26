<?php
session_start();
include('../config/database.php'); // Database connection

try {
    $sql = "SELECT 
        c.candidate_id,
        c.first_name,
        c.last_name,
        c.email,
        c.phone,
        j.job_title,
        a.application_status,
        a.submission_date
    FROM 
        candidates c
    INNER JOIN 
        Applications a ON c.candidate_id = a.candidate_id
    INNER JOIN 
        jobs j ON a.job_id = j.job_id
    ORDER BY 
        a.submission_date DESC";
    
    $result = $conn->query($sql);

    if (!$result) {
        throw new PDOException("Query failed: " . implode(", ", $conn->errorInfo()));
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error retrieving applications: " . $e->getMessage();
    error_log("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Job Applications - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body>
    <img src="assets/images/Debark.jpg" class="img-fluid" alt="Debark University HRM Banner" />
    <?php include('../includes/navbar.php'); ?>

    <div class="container my-5">
        <h2 class="text-center">Job Applications</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Candidate ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Job Title</th>
                        <th>Status</th>
                        <th>Submission Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->rowCount() > 0): ?>
                        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['candidate_id']); ?></td>
                                <td><?= htmlspecialchars($row['first_name']); ?></td>
                                <td><?= htmlspecialchars($row['last_name']); ?></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                                <td><?= htmlspecialchars($row['phone']); ?></td>
                                <td><?= htmlspecialchars($row['job_title']); ?></td>
                                <td><?= htmlspecialchars($row['application_status']); ?></td>
                                <td><?= htmlspecialchars($row['submission_date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No applications found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>