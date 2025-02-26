<?php
session_start();
include('../config/database.php'); // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars($_POST['phone']);
    $job_id = htmlspecialchars($_POST['job_id']);

    // Validate email
    if ($email === false) {
        $_SESSION['error'] = "Invalid email address.";
        header("Location: apply_job.php");
        exit();
    }

    // Handle resume upload
    $target_dir = "../uploads/resumes/";

    // Ensure the directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0775, true);
    }

    $resume_name = basename($_FILES["resume"]["name"]);
    $target_file = $target_dir . $resume_name;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Debug: Check if file is selected
    if (empty($_FILES["resume"]["name"])) {
        $_SESSION['error'] = "Please select a resume file.";
        header("Location: apply_job.php");
        exit();
    }

    // Check file already exists (avoid overwriting)
    if (file_exists($target_file)) {
        $unique_name = time() . "_" . $resume_name; // Append timestamp
        $target_file = $target_dir . $unique_name;
    }

    // Allow only PDF and DOCX files
    if (!in_array($fileType, ["pdf", "docx"])) {
        $_SESSION['error'] = "Only PDF and DOCX files are allowed.";
        $uploadOk = 0;
    }

    // Check file size (max 5MB)
    if ($_FILES["resume"]["size"] > 5 * 1024 * 1024) {
        $_SESSION['error'] = "File size exceeds the limit (5MB).";
        $uploadOk = 0;
    }

    // Debugging: Check file details
    error_log("Upload Attempt: Name=" . $_FILES["resume"]["name"] . ", Temp=" . $_FILES["resume"]["tmp_name"] . ", Size=" . $_FILES["resume"]["size"]);

    if ($uploadOk == 1) {
        if (is_uploaded_file($_FILES["resume"]["tmp_name"])) {
            if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
                // Debugging: Confirm upload success
                error_log("File uploaded successfully: " . $target_file);

                try {
                    // Insert candidate data into the database
                    $sql = "INSERT INTO Candidates (first_name, last_name, email, phone, resume_url) 
                            VALUES (:first_name, :last_name, :email, :phone, :resume_url)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':first_name' => $first_name,
                        ':last_name' => $last_name,
                        ':email' => $email,
                        ':phone' => $phone,
                        ':resume_url' => $target_file
                    ]);

                    $candidate_id = $conn->lastInsertId();

                    // Insert application data into the Applications table
                    $sql_application = "INSERT INTO Applications (candidate_id, job_id, application_status, submission_date) 
                                        VALUES (:candidate_id, :job_id, 'pending', NOW())";
                    $stmt_application = $conn->prepare($sql_application);
                    $stmt_application->execute([
                        ':candidate_id' => $candidate_id,
                        ':job_id' => $job_id
                    ]);

                    $_SESSION['success'] = "Application submitted successfully!";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Database error: " . $e->getMessage();
                    error_log("Database Error: " . $e->getMessage());
                }

                // Close statements (not necessary with PDO, but included for clarity)
                $stmt = null;
                $stmt_application = null;
            } else {
                $_SESSION['error'] = "Failed to move uploaded file.";
                error_log("Upload Error: move_uploaded_file() failed for " . $_FILES["resume"]["tmp_name"]);
            }
        } else {
            $_SESSION['error'] = "Possible file upload attack!";
            error_log("Upload Security Issue: is_uploaded_file() returned false.");
        }
    }

    // Redirect back to the form
    header("Location: apply_job.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body>
    <img src="assets/images/Debark.jpg" class="img-fluid" alt="Debark University HRM Banner" />
    <?php include('../includes/navbar.php'); ?>

    <div class="container my-5">
        <h2 class="text-center">Apply for Job Opening</h2>

        <!-- Display success/error messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="apply_job.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number:</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>

            <div class="mb-3">
                <label for="job_id" class="form-label">Select Job Position:</label>
                <select class="form-select" id="job_id" name="job_id" required>
                    <option value="" disabled selected>Select a Job</option>
                    <?php
                    $sql_jobs = "SELECT job_id, job_title FROM jobs WHERE job_status = 'Active'";
                    $result_jobs = $conn->query($sql_jobs);

                    if ($result_jobs && $result_jobs->rowCount() > 0) {
                        while ($row_job = $result_jobs->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . htmlspecialchars($row_job['job_id']) . '">' . htmlspecialchars($row_job['job_title']) . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>No jobs available at this time.</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="resume" class="form-label">Upload Resume (PDF or DOCX):</label>
                <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.docx" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit Application</button>
        </form>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>