<?php // department_navbar.php ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="department_dashboard.php">Debark University HRM - Department Head</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="department_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="submit_job_ranking.php">Submit Job Ranking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="employee_requests.php">Employee Requests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_feedback.php">View Feedback</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="fill_attendance.php">Fill Attendance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_employee_info.php">View Employee Info</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_permission.php">Manage Permissions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white" href="../PUBLIC/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>