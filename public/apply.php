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

    // Handle resume upload
    $target_dir = "../uploads/resumes/";
    $target_file = $target_dir . basename($_FILES["resume"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        $_SESSION['error'] = "File already exists. Please rename your file and try again.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($fileType != "pdf" && $fileType != "docx") {
        $_SESSION['error'] = "Only PDF and DOCX files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $_SESSION['error'] = "Your resume was not uploaded.";
    } else {
        // Try to upload the file
        if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
            // Insert candidate data into the database
            $sql = "INSERT INTO Candidates (first_name, last_name, email, phone, resume_url) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $target_file);

            if ($stmt->execute()) {
                // Get the candidate ID
                $candidate_id = $conn->insert_id;

                // Insert application data into the Applications table
                $sql_application = "INSERT INTO Applications (candidate_id, job_id, application_status, submission_date) 
                                    VALUES (?, ?, 'pending', NOW())";
                $stmt_application = $conn->prepare($sql_application);
                $stmt_application->bind_param("ii", $candidate_id, $job_id);

                if ($stmt_application->execute()) {
                    $_SESSION['success'] = "Application submitted successfully!";
                } else {
                    $_SESSION['error'] = "Error submitting application: " . $stmt_application->error;
                }
            } else {
                $_SESSION['error'] = "Error saving candidate data: " . $stmt->error;
            }

            // Close statements
            $stmt->close();
            $stmt_application->close();
        } else {
            $_SESSION['error'] = "There was an error uploading your resume.";
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
    <?php include('../includes/navbar.php'); ?>

    <div class="container my-5">
        <h2 class="text-center">Apply for Job Opening</h2>

        <!-- Display success/error messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="apply_job.php" method="POST" enctype="multipart/form-data">
            <!-- First Name -->
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>

            <!-- Last Name -->
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <!-- Phone Number -->
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number:</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>

            <!-- Job Selection -->
            <div class="mb-3">
                <label for="job_id" class="form-label">Select Job Position:</label>
                <select class="form-select" id="job_id" name="job_id" required>
                    <option value="" disabled selected>Select a Job</option>
                    <?php
                    // Fetch job openings from the database
                    $sql_jobs = "SELECT job_id, job_title FROM Job_Openings WHERE status = 'Open'";
                    $result_jobs = $conn->query($sql_jobs);

                    if ($result_jobs->num_rows > 0) {
                        while ($row_job = $result_jobs->fetch_assoc()) {
                            echo '<option value="' . $row_job['job_id'] . '">' . $row_job['job_title'] . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>No jobs available at this time.</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Resume Upload -->
            <div class="mb-3">
                <label for="resume" class="form-label">Upload Resume (PDF or DOCX):</label>
                <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.docx" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Submit Application</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>