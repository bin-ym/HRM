<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
</head>
<body>

    <div class="container-fluid d-flex flex-column" style="height: 100vh;">
        <!-- Top Section -->
        <div class="row">
            <div class="col-12">
                <!-- Include Navbar -->
                <?php include('./includes/navbar.php'); ?>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="row flex-grow-1">
            <!-- Sidebar on the Left -->
            <div class="col-md-3 col-lg-2 p-0">
                <!-- Include Sidebar -->
                <?php include('./includes/sidebar.php'); ?>
            </div>

            <!-- Main Content on the Right -->
            <div class="col-md-9 col-lg-10 p-4">
                <!-- Main Dashboard Content -->
                <h1 class="mb-4">Admin Dashboard</h1>
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Jobs</h5>
                                <p class="card-text">15</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Applicants</h5>
                                <p class="card-text">50</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Employees</h5>
                                <p class="card-text">30</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Pending Tasks</h5>
                                <p class="card-text">5</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <footer class="text-center mt-5 py-3 bg-dark text-white">
            <!-- Include Footer -->
            <?php include('./includes/footer.php'); ?>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
