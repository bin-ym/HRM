<?php
session_start();
include('../config/database.php'); // Include database connection

// Fetch total number of jobs for pagination
$sql_total_jobs = "SELECT COUNT(*) AS total FROM jobs WHERE job_status = 'Active'";
$result_total_jobs = $conn->query($sql_total_jobs);

if (!$result_total_jobs) {
    die("Database query failed: " . implode(", ", $conn->errorInfo()));
}

// Debug: Log the total jobs
$total_jobs = $result_total_jobs->fetch(PDO::FETCH_ASSOC)['total'];
error_log("Total jobs found: " . $total_jobs);

// Pagination logic
$jobs_per_page = 3; // Number of jobs per page
$total_pages = ceil($total_jobs / $jobs_per_page);

// Ensure the current page is valid
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $jobs_per_page;

// Fetch jobs for the current page
$sql_jobs = "SELECT job_id, job_title, job_description AS job_description FROM jobs WHERE job_status = 'Active' LIMIT $jobs_per_page OFFSET $offset";
$result_jobs = $conn->query($sql_jobs);

if (!$result_jobs) {
    die("Database query failed: " . implode(", ", $conn->errorInfo()));
}

// Debug: Log the number of rows
error_log("Number of jobs on this page: " . $result_jobs->rowCount());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Job Opportunities - Debark University HRM">
    <meta name="keywords" content="Debark University, HRM, Job Opportunities">
    <meta name="author" content="Debark University">
    <title>Job Opportunities - Debark University HRM</title>
    <link rel="shortcut icon" href="../assets/images/favicon.ico" />
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
    <!-- Banner Image -->
    <img src="assets/images/Debark.jpg" class="img-fluid" alt="Debark University HRM Banner" />

    <!-- Navbar -->
    <?php include('../includes/navbar.php'); ?>

    <div class="container my-5">
        <h1 class="text-center">Current Job Openings</h1>
        <p class="text-center">Explore the available positions and apply for the job that suits your qualifications.</p>

        <!-- Display success/error messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="row">
            <?php if ($result_jobs->rowCount() > 0): ?>
                <?php while ($row = $result_jobs->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="../assets/images/job_default.jpg" class="card-img-top" alt="Job Image">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['job_title']); ?></h5>
                                <p class="card-text"><?= nl2br(htmlspecialchars(substr($row['job_description'], 0, 100))) . "..."; ?></p>
                                <a href="job_details.php?job_id=<?= htmlspecialchars($row['job_id']); ?>" class="btn btn-info">View Details</a>
                                <a href="apply_job.php?job_id=<?= htmlspecialchars($row['job_id']); ?>" class="btn btn-primary">Apply Now</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">No job openings available at the moment. Please check back later.</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $page - 1; ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $page + 1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include('../includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>