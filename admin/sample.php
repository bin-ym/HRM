<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Responsive Sidebar and Navbar</title>
    <style>
        /* Sidebar Styles */
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Prevents horizontal scrolling */
        }

        #sidebar {
            position: fixed;
            width: 250px;
            background: linear-gradient(180deg, #3b4a52, #2a353b);
            color: white;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 20px;
            box-shadow: 2px 0px 8px rgba(0, 0, 0, 0.4);
            z-index: 1000;
            min-height: 100vh;
            height: 100%;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
        }

        #sidebar.hidden {
            transform: translateX(-100%);
        }

        #sidebar ul {
            list-style-type: none;
            padding-left: 0;
            margin: 0;
        }

        #sidebar ul li {
            margin-bottom: 20px;
        }

        #sidebar ul li a {
            color: #ccc;
            text-decoration: none;
            padding: 15px 25px;
            display: block;
            font-size: 18px;
            transition: background-color 0.3s ease, padding-left 0.3s ease;
        }

        #sidebar ul li a:hover {
            background-color: #007bff;
            color: white;
            padding-left: 35px;
            border-radius: 5px;
        }

        #sidebar ul li a i {
            margin-right: 10px;
            font-size: 20px;
        }

        #sidebar ul li a.active {
            background-color: #0056b3;
            color: white;
        }

        /* Navbar Styles */
        .navbar {
            margin-left: 250px;
            border-radius: 0;
            position: fixed;
            top: 0;
            width: calc(100% - 250px);
            z-index: 1050;
            transition: margin-left 0.3s ease-in-out;
        }

        .navbar .navbar-left {
            background-color: #0056b3;
            color: white;
            padding: 0 15px;
            display: flex;
            align-items: center;
        }

        .navbar .navbar-left i {
            font-size: 24px;
        }

        .navbar.collapsed {
            margin-left: 0;
            width: 100%;
        }

        /* Main Content */
        #main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            background-color: #f8f9fa;
            min-height: calc(100vh - 60px);
            overflow: auto;
            transition: margin-left 0.3s ease-in-out;
        }

        #main-content.collapsed {
            margin-left: 0;
        }

        /* Footer */
        footer {
            background-color: #222;
            color: white;
            text-align: center;
            padding: 10px;
            position: relative;
            width: 100%;
            clear: both;
        }

        /* Mobile View */
        @media (max-width: 768px) {
            #sidebar {
                width: 100%;
                height: auto;
            }

            .navbar {
                margin-left: 0;
                width: 100%;
            }

            #main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="navbar-left">
            <button id="sidebarToggle" class="btn btn-primary"><i class="fa fa-bars"></i></button>
        </div>
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="admin_dashboard.php"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_jobs.php"><i class="fa fa-briefcase"></i> Manage Jobs</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_users.php"><i class="fa fa-users"></i> Manage Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_applicants.php"><i class="fa fa-user-check"></i> Manage Applicants</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php"><i class="fa fa-user"></i> Profile</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar">
        <ul>
            <li><a href="admin_dashboard.php" class="active"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_jobs.php"><i class="fa fa-briefcase"></i> Manage Jobs</a></li>
            <li><a href="manage_users.php"><i class="fa fa-users"></i> Manage Users</a></li>
            <li><a href="manage_applicants.php"><i class="fa fa-user-check"></i> Manage Applicants</a></li>
            <li><a href="profile.php"><i class="fa fa-user"></i> Profile</a></li>
            <li><a href="settings.php"><i class="fa fa-cogs"></i> Settings</a></li>
            <li><a href="C:/xampp/htdocs/HRM/public/index.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="container-fluid" id="main-content">
        <h1>Welcome to the Admin Dashboard</h1>
        <p>Content goes here...</p>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Your Company Name. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("main-content");
        const navbar = document.querySelector(".navbar");
        const toggleButton = document.getElementById("sidebarToggle");

        toggleButton.addEventListener("click", () => {
            sidebar.classList.toggle("hidden");
            mainContent.classList.toggle("collapsed");
            navbar.classList.toggle("collapsed");
        });
    </script>
</body>

</html>
