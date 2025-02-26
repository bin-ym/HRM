<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Forgot Password - Debark University HRM">
  <meta name="keywords" content="Debark University, HRM, Forgot Password">
  <meta name="author" content="Debark University">
  <title>Forgot Password</title>
  <link rel="shortcut icon" href="../assets/images/favicon.ico" />
  <link rel="stylesheet" href="../assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
<img src="assets/images/Debark.jpg" class="img-fluid" alt="Debark University HRM Banner" />
  <!-- Navbar -->
  <?php include('../includes/navbar.php'); ?>

  <div class="container my-5">
    <h1 class="text-center">Forgot Password</h1>
    <p class="text-center">Enter your registered email address, and we will send you instructions to reset your password.</p>

    <form action="process_forgot_password.php" method="POST" class="mx-auto" style="max-width: 500px;">
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Send Reset Instructions</button>
    </form>
  </div>

  <!-- Footer -->
  <?php include('../includes/footer.php'); ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
