<?php
session_start();
include('./includes/db_connect.php'); // Database connection
include('./includes/navbar.php');

// Check if the user is an admin (basic authentication check)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php"); // Redirect to login if not an admin
    exit();
}

// Validate and sanitize the application_id from GET request
if (!isset($_GET['application_id']) || !is_numeric($_GET['application_id'])) {
    $_SESSION['error'] = "Invalid Application ID.";
    header("Location: manage_applicants.php"); // Redirect to manage applicants page
    exit();
}

$application_id = intval($_GET['application_id']);

try {
    // Prepare and execute the SQL query with a parameterized statement
    $sql = "SELECT 
        a.application_id, 
        c.first_name, 
        c.last_name, 
        c.email, 
        c.phone, 
        j.job_title, 
        a.application_status, 
        a.submission_date 
    FROM applications a
    JOIN candidates c ON a.candidate_id = c.candidate_id
    JOIN jobs j ON a.job_id = j.job_id
    WHERE a.application_id = :application_id";

    $stmt = $conn->prepare($sql);
    $stmt->execute(['application_id' => $application_id]);
    $applicant = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$applicant) {
        $_SESSION['error'] = "Application not found.";
        header("Location: manage_applicants.php"); // Redirect to manage applicants page
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error retrieving application details: " . $e->getMessage();
    error_log("Database Error: " . $e->getMessage());
    header("Location: manage_applicants.php"); // Redirect on error
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application Details - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
    <img src="../assets/images/Debark.jpg" class="img-fluid" alt="Debark University HRM Banner" />

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Application Details</h2>

        <!-- Display success/error messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success text-center"><?= htmlspecialchars($_SESSION['success']); ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Application Details Card -->
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Applicant Information</h4>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Full Name:</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($applicant['first_name'] . ' ' . $applicant['last_name']); ?></dd>

                    <dt class="col-sm-4">Email:</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($applicant['email']); ?></dd>

                    <dt class="col-sm-4">Phone:</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($applicant['phone']); ?></dd>

                    <dt class="col-sm-4">Job Title:</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($applicant['job_title']); ?></dd>

                    <dt class="col-sm-4">Application Status:</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($applicant['application_status']); ?></dd>

                    <dt class="col-sm-4">Submission Date:</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($applicant['submission_date']); ?></dd>
                </dl>

                <!-- Status Update Form -->
                <form method="POST" action="update_application_status.php" class="mt-4">
                    <input type="hidden" name="application_id" value="<?php echo htmlspecialchars($application_id); ?>">
                    <div class="mb-3">
                        <label for="status" class="form-label">Update Application Status:</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" disabled <?php echo $applicant['application_status'] === '' ? 'selected' : ''; ?>>Select Status</option>
                            <option value="pending" <?php echo $applicant['application_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo $applicant['application_status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo $applicant['application_status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center mt-4">
            <a href="manage_applicants.php" class="btn btn-secondary">Back to Applicants</a>
        </div>
    </div>

    <?php include('./includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>