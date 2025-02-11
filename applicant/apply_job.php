<?php 
include('./includes/header.php'); 
include('./includes/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_id = $_POST['job_id'];
    $applicant_name = $_POST['applicant_name'];
    $email = $_POST['email'];
    $cover_letter = $_POST['cover_letter'];

    // Insert application data into the database
    $sql = "INSERT INTO applications (job_id, applicant_name, email, cover_letter) 
            VALUES (:job_id, :applicant_name, :email, :cover_letter)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':job_id' => $job_id,
        ':applicant_name' => $applicant_name,
        ':email' => $email,
        ':cover_letter' => $cover_letter,
    ]);

    echo "<div class='alert alert-success'>Application submitted successfully!</div>";
}

?>

<div class="container">
    <h2 class="my-4">Apply for Job</h2>

    <?php
    // Fetch job details from the database based on job_id
    if (isset($_GET['id'])) {
        $job_id = $_GET['id'];
        $sql = "SELECT * FROM jobs WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $job_id]);
        $job = $stmt->fetch();
    }
    ?>

    <form action="apply_job.php" method="POST">
        <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
        <div class="mb-3">
            <label for="applicant_name" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="applicant_name" name="applicant_name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="cover_letter" class="form-label">Cover Letter</label>
            <textarea class="form-control" id="cover_letter" name="cover_letter" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Application</button>
    </form>
</div>

<?php include('./includes/footer.php'); ?>
