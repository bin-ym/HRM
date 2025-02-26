<?php
session_start(); 
require_once './includes/db_connect.php'; 

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Invalid request!";
        header('Location: create_job.php');
        exit();
    }

    $job_title = trim(htmlspecialchars($_POST['job_title']));
    $job_department = trim(htmlspecialchars($_POST['job_department']));
    $job_status = trim(htmlspecialchars($_POST['job_status']));

    if (empty($job_title) || empty($job_department) || empty($job_status)) {
        $_SESSION['error'] = "All fields are required!";
    } else {
        try {
            // Ensure connection exists
            if (!$conn) {
                throw new Exception("Database connection error.");
            }

            $sql = "INSERT INTO jobs (job_title, job_department, job_status, job_created_at) 
                    VALUES (:job_title, :job_department, :job_status, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':job_title' => $job_title,
                ':job_department' => $job_department,
                ':job_status' => $job_status
            ]);

            $_SESSION['success'] = "Job created successfully!";
            header('Location: manage_jobs.php');
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Posting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('./includes/navbar.php'); ?>

    <div class="container my-5">
        <h2 class="text-center mb-4">Create Job Posting</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php elseif (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="mb-3">
                <label for="job_title" class="form-label">Job Title</label>
                <input type="text" class="form-control" id="job_title" name="job_title" required>
            </div>
            <div class="mb-3">
                <label for="job_department" class="form-label">Department</label>
                <input type="text" class="form-control" id="job_department" name="job_department" required>
            </div>
            <div class="mb-3">
                <label for="job_status" class="form-label">Status</label>
                <select class="form-control" id="job_status" name="job_status" required>
                    <option value="Active">Active</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Job</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
