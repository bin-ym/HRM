<?php include('./includes/header.php'); ?>
<?php include('./includes/navbar.php'); ?>
<div class="container-fluid">
    <div class="row">
        <?php include('./includes/sidebar.php'); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2>Manage Tasks</h2>
            <a href="create_task.php" class="btn btn-primary mb-3">Create New Task</a>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Task Title</th>
                        <th>Assigned Employee</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through tasks -->
                    <tr>
                        <td>Update Payroll</td>
                        <td>John Doe</td>
                        <td>2024-12-15</td>
                        <td>In Progress</td>
                        <td>
                            <a href="edit_task.php?id=1" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_task.php?id=1" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Prepare Reports</td>
                        <td>Jane Smith</td>
                        <td>2024-12-20</td>
                        <td>Completed</td>
                        <td>
                            <a href="view_task.php?id=2" class="btn btn-info btn-sm">View</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>
</div>
<?php include('./includes/footer.php'); ?>
