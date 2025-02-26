<?php
// Get the current page name dynamically
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="hr_officer_dashboard.php">HR Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'view_applicants.php') ? 'active' : '' ?>" href="view_applicants.php">Applicants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'announce_vacancy.php') ? 'active' : '' ?>" href="announce_vacancy.php">Vacancy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'post_exam_schedule.php') ? 'active' : '' ?>" href="post_exam_schedule.php">Exam Schedule</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'view_notifications.php') ? 'active' : '' ?>" href="view_notifications.php">Notifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white px-3" href="../public/login.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
