<?php 
include('./includes/header.php'); 
include('./includes/navbar.php'); 
include('./includes/db_connect.php'); // Include database connection
?>

<div class="container-fluid">
    <div class="row">
        <?php include('./includes/sidebar.php'); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2 class="my-4">Manage Users</h2>

            <!-- Button to add a new user -->
            <a href="add_user.php" class="btn btn-success mb-3">Add New User</a>

            <!-- User List Table -->
            <table class="table table-striped">
                <thead>
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
                    // Fetch users from the database
                    $sql = "SELECT * FROM users";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $users = $stmt->fetchAll();

                    // Loop through and display users
                    if ($users) {
                        foreach ($users as $user) {
                            echo "<tr>
                                <td>" . htmlspecialchars($user['username']) . "</td>
                                <td>" . htmlspecialchars($user['email']) . "</td>
                                <td>" . htmlspecialchars($user['role']) . "</td>
                                <td>" . htmlspecialchars($user['status']) . "</td>
                                <td>
                                    <a href='edit_user.php?id=" . $user['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='delete_user.php?id=" . $user['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<?php include('./includes/footer.php'); ?>
