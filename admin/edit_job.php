<?php
session_start();
require_once './includes/db_connect.php';

// Validate job_id from URL
if (!isset($_GET['job_id']) || !is_numeric($_GET['job_id']) || intval($_GET['job_id']) <= 0) {
    die("Invalid job ID.");
}

$job_id = intval($_GET['job_id']);

// Fetch job details from the database
$sql = "SELECT * FROM jobs WHERE job_id = :job_id";
$stmt = $conn->prepare($sql);
$stmt->execute([':job_id' => $job_id]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job_id) {
    die("Job not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_title = htmlspecialchars($_POST['job_title']);
    $job_department = htmlspecialchars($_POST['job_department']);
    $job_status = htmlspecialchars($_POST['job_status']);

    if (empty($job_title) || empty($job_department) || empty($job_status)) {
        $_SESSION['error'] = "All fields are required!";
    } else {
        try {
            $sql = "UPDATE jobs 
                    SET job_title = :job_title, 
                        job_department = :job_department, 
                        job_status = :job_status 
                    WHERE job_id = :job_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':job_title' => $job_title,
                ':job_department' => $job_department,
                ':job_status' => $job_status,
                ':job_id' => $job_id,
            ]);

            $_SESSION['success'] = "Job updated successfully!";
            header('Location: manage_jobs.php');
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating job: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Posting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('./includes/navbar.php'); ?>

    <div class="container my-5">
        <h2 class="text-center mb-4">Edit Job Posting</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php elseif (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="job_title" class="form-label">Job Title</label>
                <input type="text" class="form-control" id="job_title" name="job_title" value="<?php echo htmlspecialchars($job['job_title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="job_department" class="form-label">Department</label>
                <input type="text" class="form-control" id="job_department" name="job_department" value="<?php echo htmlspecialchars($job['job_department']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="job_status" class="form-label">Status</label>
                <select class="form-control" id="job_status" name="job_status" required>
                    <option value="Active" <?php echo ($job['job_status'] === 'Active') ? 'selected' : ''; ?>>Active</option>
                    <option value="Closed" <?php echo ($job['job_status'] === 'Closed') ? 'selected' : ''; ?>>Closed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Job</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
