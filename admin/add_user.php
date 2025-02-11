<?php 
include('./includes/header.php'); 
include('./includes/navbar.php'); 
include('./includes/db_connect.php'); // Include database connection
?>

<div class="container">
    <h2 class="my-4">Add New User</h2>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
        $role = $_POST['role'];
        $status = $_POST['status'];

        // Check if email already exists
        $checkEmail = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $checkEmail->execute([$email]);

        if ($checkEmail->rowCount() > 0) {
            echo "<div class='alert alert-danger'>Email already exists!</div>";
        } else {
            // Insert new user into the database
            $sql = "INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$username, $email, $password, $role, $status])) {
                echo "<div class='alert alert-success'>User added successfully! <a href='manage_users.php'>Go back</a></div>";
            } else {
                echo "<div class='alert alert-danger'>Failed to add user.</div>";
            }
        }
    }
    ?>

    <form method="POST">
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
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
        <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include('./includes/footer.php'); ?>
