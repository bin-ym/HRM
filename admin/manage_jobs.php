<?php
// Include configuration and database connection
include('./includes/config.php');
include('./includes/db_connect.php');

// Fetch job postings from the database (example)
$jobs = [
    ['id' => 1, 'title' => 'Software Developer', 'department' => 'IT Department', 'status' => 'Active'],
    ['id' => 2, 'title' => 'HR Manager', 'department' => 'HR Department', 'status' => 'Closed'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
    <title>Manage Job Postings</title>
</head>
<body>
    <div class="container-fluid">
        <!-- Navbar -->
        <div class="row">
            <div class="col-12">
                <?php include('./includes/navbar.php'); ?>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 p-0">
                <?php include('./includes/sidebar.php'); ?>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h2 class="my-4">Manage Job Postings</h2>
                <a href="create_job.php" class="btn btn-primary mb-3">Create New Job Posting</a>

                <!-- Job Postings Table -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($job['title']); ?></td>
                            <td><?php echo htmlspecialchars($job['department']); ?></td>
                            <td><?php echo htmlspecialchars($job['status']); ?></td>
                            <td>
                                <a href="edit_job.php?id=<?php echo $job['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_job.php?id=<?php echo $job['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this job?');">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <?php include('./includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>