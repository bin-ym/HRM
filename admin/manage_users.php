<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['error'] = "Access denied. Please log in as an Admin.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

include('./includes/admin_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Manage Users</h2>

        <!-- Button to Create New User -->
        <div class="d-flex justify-content-end mb-3">
            <a href="create_user.php" class="btn btn-success"><i class="fas fa-user-plus me-2"></i>Create New User</a>
        </div>

        <!-- User List Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $sql = "SELECT id, username, email, role, status, last_login FROM users ORDER BY created_at DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($users) {
                            foreach ($users as $user) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($user['username']) . "</td>
                                    <td>" . htmlspecialchars($user['email']) . "</td>
                                    <td><span class='badge bg-info'>" . htmlspecialchars(ucwords(str_replace('_', ' ', $user['role']))) . "</span></td>
                                    <td><span class='badge " . ($user['status'] === 'Active' ? 'bg-success' : 'bg-danger') . "'>" . htmlspecialchars($user['status']) . "</span></td>
                                    <td>" . ($user['last_login'] ? htmlspecialchars(date('F j, Y, H:i', strtotime($user['last_login']))) : 'Never') . "</td>
                                    <td>
                                        <a href='edit_user.php?id=" . $user['id'] . "' class='btn btn-warning btn-sm me-1'><i class='fas fa-edit'></i> Edit</a>
                                        <a href='delete_user.php?id=" . $user['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\");'><i class='fas fa-trash'></i> Delete</a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center text-muted'>No users found</td></tr>";
                        }
                    } catch (PDOException $e) {
                        error_log("Manage Users Error: " . $e->getMessage());
                        echo "<tr><td colspan='6' class='text-center text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>