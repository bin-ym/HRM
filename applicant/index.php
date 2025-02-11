<?php 
include('./includes/db_connect.php');  // Include database connection file
?>

<?php include('../includes/header.php'); ?>  <!-- Include header -->

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">HRM System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="apply_job.php">Apply for Job</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Available Jobs Table -->
<div class="container mt-4">
    <h1>Available Jobs</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Deadline</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch jobs from the database
            $sql = "SELECT id, job_title, location, salary, deadline FROM jobs";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $jobs = $stmt->fetchAll();

            if ($jobs) {
                foreach ($jobs as $job) {
                    echo "<tr>
                        <td>" . htmlspecialchars($job['job_title']) . "</td>
                        <td>" . (isset($job['location']) ? htmlspecialchars($job['location']) : 'Not available') . "</td>
                        <td>" . (isset($job['salary']) ? htmlspecialchars($job['salary']) : 'Not available') . "</td>
                        <td>" . (isset($job['deadline']) ? htmlspecialchars($job['deadline']) : 'Not available') . "</td>
                        <td><a href='apply_job.php?id=" . $job['id'] . "' class='btn btn-primary btn-sm'>Apply</a></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>No jobs available</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('./includes/footer.php'); ?>  <!-- Include footer -->
