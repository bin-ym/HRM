<?php
session_start();
require_once './includes/db_connect.php'; // Ensure database connection is included

// Ensure user is logged in as an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['error'] = "Access denied. Please log in as an Admin.";
    header("Location: ../public/login.php");
    exit();
}

// Fetch job postings from the database
try {
    $stmt = $conn->prepare("SELECT job_id, job_title, job_department, job_status, job_created_at FROM jobs ORDER BY job_created_at DESC");
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching jobs: " . $e->getMessage());
}

include('./includes/navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Job Postings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="text-center mb-4">Manage Job Postings</h2>

    <!-- Display Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Button to Add New Job -->
    <div class="d-flex justify-content-end mb-3">
        <a href="create_job.php" class="btn btn-success"><i class="fas fa-plus"></i> Create New Job</a>
    </div>

    <!-- Job List Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Job Title</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($jobs)): ?>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($job['job_department']); ?></td>
                            <td>
                                <span class="badge <?php echo ($job['job_status'] == 'Active') ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo htmlspecialchars($job['job_status']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($job['job_created_at']); ?></td>
                            <td>
                                <a href="edit_job.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_job.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this job?');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No jobs found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include('./includes/footer.php'); ?>
