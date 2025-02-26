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
</head>
<body>
    <img src="../assets/images/Debark.jpg" class="img-fluid" alt="Debark University HRM Banner" />
    
    <?php include('../includes/navbar.php'); ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Create an Account</h1>

        <!-- Display Messages -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="submit_signup.php" method="POST" class="mx-auto" style="max-width: 500px;">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">Select Role</label>
        <select class="form-control" id="role" name="role" required>
    <option value="Employee">Employee</option>
    <option value="HR Officer">HR Officer</option>
    <option value="HR Admin">HR Admin</option>
    <option value="Dean">Dean</option>
    <option value="Department Head">Department Head</option>
    <option value="Finance Officer">Finance Officer</option>
    <option value="Applicant">Applicant</option>
</select>

    </div>
    <button type="submit" class="btn btn-primary w-100">Sign Up</button>
</form>

    </div>

    <?php include('../includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
