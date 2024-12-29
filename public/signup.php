<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Sign Up - Debark University HRM">
  <meta name="keywords" content="Debark University, HRM, Sign Up">
  <meta name="author" content="Debark University">
  <title>Sign Up - Debark University HRM</title>
  <link rel="shortcut icon" href="../assets/images/favicon.ico" />
  <link rel="stylesheet" href="../assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
  <!-- Navbar -->
  <?php include('../includes/navbar.php'); ?>

  <div class="container my-5">
    <h1 class="text-center mb-4">Create an Account</h1>
    <p class="text-center text-muted">Fill in the form below to register for an account.</p>
    
    <!-- Sign Up Form -->
    <form action="submit_signup.php" method="POST" class="mx-auto" style="max-width: 500px;">
      <!-- Username -->
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
      </div>
      
      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
      </div>
      
      <!-- Password -->
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter a strong password" required>
      </div>
      
      <!-- Confirm Password -->
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
      </div>
      
      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary w-100">Sign Up</button>
      
      <!-- Link to Login -->
      <p class="text-center mt-3">
        Already have an account? <a href="../pages/login.php">Login here</a>.
      </p>
    </form>
  </div>

  <!-- Footer -->
  <?php include('../includes/footer.php'); ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Simple client-side password validation
    document.querySelector('form').addEventListener('submit', function (e) {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirm_password').value;
      if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
      }
    });
  </script>
</body>
</html>
