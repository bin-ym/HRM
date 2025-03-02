<?php // employee_navbar.php ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="employee_dashboard.php">Debark University HRM - Employee</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="employee_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="leave_request.php">Leave Request</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="salary_details.php">Salary Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="update_profile.php">Update Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white" href="../PUBLIC/login.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>