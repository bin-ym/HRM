<?php
// Include configuration and database connection
include('./includes/config.php');
include('./includes/db_connect.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $title = htmlspecialchars($_POST['title']);
    $department = htmlspecialchars($_POST['department']);
    $status = htmlspecialchars($_POST['status']);

    // Insert into database (example query)
    $sql = "INSERT INTO jobs (title, department, status) VALUES (:title, :department, :status)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':department' => $department,
        ':status' => $status,
    ]);

    // Redirect to manage_jobs.php
    header('Location: manage_jobs.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
    <title>Create Job Posting</title>
</head>
<body>
<?php include('./includes/navbar.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 p-0">
                <?php include('./includes/sidebar.php'); ?>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h2 class="my-4">Create New Job Posting</h2>
                <form method="POST" action="create_job.php">
                    <div class="mb-3">
                        <label for="title" class="form-label">Job Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Active">Active</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Job</button>
                </form>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <?php include('./includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>