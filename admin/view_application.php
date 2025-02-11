<?php
include('./includes/header.php');
include('./includes/db_connect.php');

// Get applicant ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch applicant data
$sql = "SELECT * FROM applications WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$applicant = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if applicant exists
if (!$applicant) {
    echo "<p class='text-danger'>Applicant not found!</p>";
    exit;
}
?>

<div class="container">
    <h2>Application Details</h2>
    <p><strong>Applicant Name:</strong> <?php echo htmlspecialchars($applicant['applicant_name']); ?></p>
    <p><strong>Job Title:</strong> <?php echo htmlspecialchars($applicant['job_title']); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($applicant['status']); ?></p>
    <p><strong>Resume:</strong> <a href="<?php echo htmlspecialchars($applicant['resume']); ?>" download>Download Resume</a></p>
    <p><strong>Cover Letter:</strong> <?php echo nl2br(htmlspecialchars($applicant['cover_letter'])); ?></p>
</div>

<?php include('./includes/footer.php'); ?>
