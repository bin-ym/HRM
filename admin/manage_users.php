<?php 
session_start();
require_once './includes/db_connect.php'; // Ensure database connection is included

// Ensure user is logged in as an HR Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['error'] = "Access denied. Please log in as an Admin.";
    header("Location: ../public/login.php");
    exit();
}

include('./includes/navbar.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="text-center mb-4">Manage Users</h2>

    <!-- Button to Add New User -->
    <div class="d-flex justify-content-end mb-3">
        <a href="add_user.php" class="btn btn-success"><i class="fas fa-user-plus"></i> Add New User</a>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    // Ensure $pdo is set before executing queries
                    if (!isset($conn)) {
                        throw new Exception("Database connection not available.");
                    }

                    // Fetch users from the database
                    $sql = "SELECT id, username, email, role, status FROM users";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($users) {
                        foreach ($users as $user) {
                            echo "<tr>
                                <td>" . htmlspecialchars($user['username']) . "</td>
                                <td>" . htmlspecialchars($user['email']) . "</td>
                                <td><span class='badge bg-info'>" . htmlspecialchars(ucwords($user['role'])) . "</span></td>
                                <td>
                                    <span class='badge " . ($user['status'] === 'Active' ? 'bg-success' : 'bg-danger') . "'>" . htmlspecialchars($user['status']) . "</span>
                                </td>
                                <td>
                                    <a href='edit_user.php?id=" . $user['id'] . "' class='btn btn-warning btn-sm'><i class='fas fa-edit'></i> Edit</a>
                                    <a href='delete_user.php?id=" . $user['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\");'><i class='fas fa-trash'></i> Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center text-muted'>No users found</td></tr>";
                    }
                } catch (Exception $e) {
                    echo "<tr><td colspan='5' class='text-center text-danger'>Error: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('./includes/footer.php'); ?>
