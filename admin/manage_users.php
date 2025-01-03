<?php include('./includes/header.php'); ?>
<?php include('./includes/navbar.php'); ?>
<div class="container-fluid">
    <div class="row">
        <?php include('./includes/sidebar.php'); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2>Manage Users</h2>
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
                    <!-- Loop through users -->
                    <tr>
                        <td>John Doe</td>
                        <td>john.doe@example.com</td>
                        <td>Employee</td>
                        <td>Active</td>
                        <td>
                            <a href="edit_user.php?id=1" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_user.php?id=1" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Jane Smith</td>
                        <td>jane.smith@example.com</td>
                        <td>Admin</td>
                        <td>Active</td>
                        <td>
                            <a href="edit_user.php?id=2" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_user.php?id=2" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>
</div>
<?php include('./includes/footer.php'); ?>
