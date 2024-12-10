<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Debark University Human Resource Management System">
  <meta name="keywords" content="HRM, Debark University, Human Resource Management">
  <meta name="author" content="Debark University">
  <title>Welcome to Debark University HRM</title>
  <link rel="shortcut icon" href="assets/images/Debark.jpg" />
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
  <!-- Banner Image -->
  <img src="assets/images/Debark.jpg" class="img-fluid" alt="Debark University HRM Banner" />

  <?php include('../includes/navbar.php'); ?>

  <div class="container-fluid my-4">
    <h1 class="text-center">Welcome to Debark University HRM</h1>
    <p class="text-center">
      Our Human Resource Management system streamlines HR processes for all stakeholders: HR Officers, Admins, Deans, Departments, Finance teams, Employees, and Applicants.
    </p>
    <div class="row">
      <div class="col-lg-4 col-md-6 text-center mb-4">
        <div class="card">
          <img src="assets/images/HR_officer.jpg" class="card-img-top" alt="HR Officer">
          <div class="card-body">
            <h5 class="card-title">HR Officers</h5>
            <p class="card-text">Manage and oversee employee records, attendance, and performance metrics.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 text-center mb-4">
        <div class="card">
          <img src="assets/images/admin.jpg" class="card-img-top" alt="HR Admin">
          <div class="card-body">
            <h5 class="card-title">HR Admin</h5>
            <p class="card-text">Handle the administrative functions, including user management and system maintenance.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 text-center mb-4">
        <div class="card">
          <img src="assets/images/dean.jpg" class="card-img-top" alt="Dean">
          <div class="card-body">
            <h5 class="card-title">Deans</h5>
            <p class="card-text">Support academic planning and resource allocation within the university.</p>
          </div>
        </div>
      </div>
    </div>
    <!-- Additional content can be added here -->
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
