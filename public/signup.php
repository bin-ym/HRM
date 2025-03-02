<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Applicant Sign Up - Debark University HRM">
    <meta name="keywords" content="Debark University, HRM, Applicant Sign Up">
    <meta name="author" content="Debark University">
    <title>Applicant Sign Up - Debark University HRM</title>
    <link rel="shortcut icon" href="../assets/images/favicon.ico" />
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <img src="../assets/images/Debark.jpg" class="img-fluid" alt="Debark University HRM Banner" />
    
    <?php include('../includes/navbar.php'); ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Applicant Registration</h1>

        <!-- Display Messages -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" role="alert"><?= htmlspecialchars($_SESSION['success']); ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="submit_signup.php" method="POST" class="mx-auto needs-validation" style="max-width: 500px;" novalidate>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" pattern="[a-zA-Z0-9_]{3,20}" required>
                <div class="invalid-feedback">Username must be 3-20 characters (letters, numbers, underscores only)</div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="invalid-feedback">Please enter a valid email address</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                <div class="invalid-feedback">Password must be at least 8 characters with 1 uppercase, 1 lowercase, and 1 number</div>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <div class="invalid-feedback">Passwords must match</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register as Applicant</button>
        </form>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        // Bootstrap form validation
        (function () {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    const password = form.querySelector('#password').value
                    const confirmPassword = form.querySelector('#confirm_password').value
                    
                    if (password !== confirmPassword) {
                        form.querySelector('#confirm_password').setCustomValidity('Passwords do not match')
                    } else {
                        form.querySelector('#confirm_password').setCustomValidity('')
                    }

                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>