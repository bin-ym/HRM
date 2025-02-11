<?php
// Include configuration and database connection
include('./includes/config.php');
include('./includes/db_connect.php');

// Fetch dynamic data (example: replace with actual database queries)
$totalJobs = 15; // Example: Fetch from database
$totalApplicants = 50; // Example: Fetch from database
$totalEmployees = 30; // Example: Fetch from database
$pendingTasks = 5; // Example: Fetch from database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="container-fluid d-flex flex-column" style="min-height: 100vh;">
        <!-- Top Section: Navbar -->
        <?php include('./includes/navbar.php'); ?>

        <!-- Main Content -->
        <div class="row flex-grow-1">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 p-0">
                <?php include('./includes/sidebar.php'); ?>
            </div>

            <!-- Dashboard Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <h1 class="mb-4">Admin Dashboard</h1>
                <div class="row">
                    <!-- Total Jobs Card -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h5 class="card-title">Total Jobs</h5>
                                <p class="card-text"><?php echo $totalJobs; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Applicants Card -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h5 class="card-title">Total Applicants</h5>
                                <p class="card-text"><?php echo $totalApplicants; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Employees Card -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <h5 class="card-title">Total Employees</h5>
                                <p class="card-text"><?php echo $totalEmployees; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Tasks Card -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <h5 class="card-title">Pending Tasks</h5>
                                <p class="card-text"><?php echo $pendingTasks; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-center py-3 bg-dark text-white">
            <?php include('./includes/footer.php'); ?>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>