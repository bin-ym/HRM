<?php include('./includes/header.php'); ?>
<?php include('./includes/navbar.php'); ?>
<div class="container-fluid">
    <div class="row">
        <?php include('./includes/sidebar.php'); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2>Manage Job Postings</h2>
            <a href="create_job.php" class="btn btn-primary mb-3">Create New Job Posting</a>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through job postings -->
                    <tr>
                        <td>Software Developer</td>
                        <td>IT Department</td>
                        <td>Active</td>
                        <td>
                            <a href="edit_job.php?id=1" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_job.php?id=1" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                    <tr>
                        <td>HR Manager</td>
                        <td>HR Department</td>
                        <td>Closed</td>
                        <td>
                            <a href="edit_job.php?id=2" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_job.php?id=2" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>
</div>
<?php include('./includes/footer.php'); ?>
