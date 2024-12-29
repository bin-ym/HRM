<?php include('./includes/header.php'); ?>
<?php include('./includes/navbar.php'); ?>
<div class="container-fluid">
    <div class="row">
        <?php include('./includes/sidebar.php'); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2>Manage Employees</h2>
            <a href="add_employee.php" class="btn btn-primary mb-3">Add New Employee</a>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through employees -->
                    <tr>
                        <td>John Doe</td>
                        <td>HR Manager</td>
                        <td>HR Department</td>
                        <td>Active</td>
                        <td>
                            <a href="edit_employee.php?id=1" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_employee.php?id=1" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Jane Smith</td>
                        <td>Software Developer</td>
                        <td>IT Department</td>
                        <td>Inactive</td>
                        <td>
                            <a href="edit_employee.php?id=2" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_employee.php?id=2" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>
</div>
<?php include('./includes/footer.php'); ?>
