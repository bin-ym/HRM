<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Help - Debark University Human Resource Management">
  <meta name="keywords" content="Debark University, HRM Help, Support">
  <meta name="author" content="Debark University">
  <title>Help - Debark University HRM</title>
  <link rel="shortcut icon" href="../assets/images/favicon.ico" />
  <link rel="stylesheet" href="../assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
  <!-- Navbar -->
  <?php include('../includes/navbar.php'); ?>

  <div class="container my-5">
    <h1 class="text-center">Need Help?</h1>
    <p>If you're facing any issues, please refer to our FAQs below or contact support.</p>
    
    <h3>Frequently Asked Questions</h3>
    <ul>
      <li><strong>How do I register for the HRM system?</strong> Visit the <a href="signup.php">Sign Up</a> page and provide the necessary details.</li>
      <li><strong>How can I apply for a job?</strong> Head over to the <a href="jobs.php">Job Openings</a> section.</li>
      <li><strong>How do I access my account?</strong> Use your registered email and password to log in from the <a href="login.php">Login</a> page.</li>
    </ul>
  </div>

  <!-- Footer -->
  <?php include('../includes/footer.php'); ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>