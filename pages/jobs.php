<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Job Opportunities - Debark University HRM">
  <meta name="keywords" content="Debark University, HRM, Job Opportunities">
  <meta name="author" content="Debark University">
  <title>Job Opportunities - Debark University HRM</title>
  <link rel="shortcut icon" href="../assets/images/favicon.ico" />
  <link rel="stylesheet" href="../assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
  <!-- Navbar -->
  <?php include('../includes/navbar.php'); ?>

  <div class="container my-5">
    <h1 class="text-center">Current Job Openings</h1>
    <p>Explore the available positions and apply for the job that suits your qualifications.</p>

    <div class="row">
      <!-- Example Job 1 -->
      <div class="col-md-4">
        <div class="card">
          <img src="../assets/images/job1.jpg" class="card-img-top" alt="Job 1">
          <div class="card-body">
            <h5 class="card-title">HR Officer</h5>
            <p class="card-text">We are looking for a passionate and experienced HR Officer to join our team.</p>
            <a href="apply_job.php?job_id=1" class="btn btn-primary">Apply Now</a>
          </div>
        </div>
      </div>
      
      <!-- Example Job 2 -->
      <div class="col-md-4">
        <div class="card">
          <img src="../assets/images/job2.jpg" class="card-img-top" alt="Job 2">
          <div class="card-body">
            <h5 class="card-title">HR Admin</h5>
            <p class="card-text">Join our team as an HR Admin and help manage all HR-related activities.</p>
            <a href="apply_job.php?job_id=2" class="btn btn-primary">Apply Now</a>
          </div>
        </div>
      </div>

      <!-- Example Job 3 -->
      <div class="col-md-4">
        <div class="card">
          <img src="../assets/images/job3.jpg" class="card-img-top" alt="Job 3">
          <div class="card-body">
            <h5 class="card-title">Finance Officer</h5>
            <p class="card-text">Looking for a qualified Finance Officer to handle all financial tasks for the department.</p>
            <a href="apply_job.php?job_id=3" class="btn btn-primary">Apply Now</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination (if you have many jobs) -->
    <nav aria-label="Page navigation example" class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#">Next</a></li>
      </ul>
    </nav>
  </div>

  <!-- Footer -->
  <?php include('../includes/footer.php'); ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
