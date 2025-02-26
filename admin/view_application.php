<?php
include('./includes/db_connect.php');
include('./includes/navbar.php');

if (!isset($_GET['application_id']) || !is_numeric($_GET['application_id'])) {
    die("Invalid Application ID.");
}

$id = intval($_GET['application_id']);

$sql = "SELECT a.application_id, c.first_name, c.last_name, c.email, c.phone, j.job_title, a.application_status, a.submission_date 
        FROM applications a
        JOIN candidates c ON a.candidate_id = c.candidate_id
        JOIN jobs j ON a.job_id = j.job_id
        WHERE a.application_id = :application_id";

$stmt = $conn->prepare($sql);
$stmt->execute(['application_id' => $id]);
$applicant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$applicant) {
    die("Application not found.");
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container mt-5">
    <h2 class="mb-4 text-center">Application Details</h2>

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
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="text-center mt-4">
        <a href="manage_applicants.php" class="btn btn-secondary">Back to Applicants</a>
    </div>
</div>

<?php include('./includes/footer.php'); ?>
