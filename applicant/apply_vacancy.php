<?php
session_start();
include('../config/database.php'); 
include('./includes/applicant_navbar.php'); 

$sql = "SELECT job_id, job_title, description FROM Job_Openings WHERE status = 'Open'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   <div class="container my-5">
    <h2 class="text-center mb-4" style="font-family: 'Arial', sans-serif; color: #003366;">Available Vacancies</h2>
    <div class="row">
        <?php foreach ($jobs as $job): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg rounded" style="border: none; transition: transform 0.3s ease-in-out;">
                    <img src="https://via.placeholder.com/350x200" class="card-img-top rounded-top" alt="Job Image">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><?= htmlspecialchars($job['job_title']); ?></h5>
                        <p class="card-text"><?= htmlspecialchars(substr($job['description'], 0, 150)) . "..."; ?></p>
                        <a href="job_details.php?job_id=<?= $job['job_id']; ?>" class="btn btn-info btn-sm">See Details</a>
                        <a href="apply_job.php?job_id=<?= $job['job_id']; ?>" class="btn btn-success btn-sm float-right">Apply Now</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div> 
</body>

<!-- Footer -->
<?php include('../includes/footer.php'); ?>

<script>
    // Adding hover effect for the cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseover', () => {
            card.style.transform = 'scale(1.05)';
        });
        card.addEventListener('mouseout', () => {
            card.style.transform = 'scale(1)';
        });
    });
</script>

