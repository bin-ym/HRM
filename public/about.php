<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="About Debark University Human Resource Management System">
  <meta name="keywords" content="Debark University, HRM, About HRM">
  <meta name="author" content="Debark University">
  <title>About Debark University HRM</title>
  <link rel="shortcut icon" href="../assets/images/favicon.ico" />
  <link rel="stylesheet" href="../assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>

  <!-- Navbar -->
  <?php include('../includes/navbar.php'); ?>

  <div class="container my-5">
    <h1 class="text-center">About Debark University HRM</h1>
    <p class="lead">
      The Human Resource Management system at Debark University is designed to streamline HR processes, manage employee data, handle recruitment, and ensure effective communication among all stakeholders.
    </p>
    <h3>Our Mission</h3>
    <p>
      To provide a comprehensive and efficient system that supports the University's HR functions, from recruitment to employee management.
    </p>
    <h3>Key Features</h3>
    <ul>
      <li>Efficient Employee Record Management</li>
      <li>Recruitment and Job Vacancy Management</li>
      <li>Employee Communication and Interaction</li>
      <li>Payroll and Benefits Administration</li>
      <li>Performance and Leave Management</li>
    </ul>
    <h3>Our Vision</h3>
    <p>
      To be the leading HRM system in Ethiopia, providing an intuitive and effective platform for managing human resources across universities and organizations.
    </p>
  </div>

  <!-- Footer -->
  <?php include('../includes/footer.php'); ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
