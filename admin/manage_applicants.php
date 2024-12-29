<?php include('./includes/header.php'); ?>
<?php include('./includes/navbar.php'); ?>
<div class="container-fluid">
    <div class="row">
        <?php include('./includes/sidebar.php'); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2>Manage Applicants</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Applicant Name</th>
                        <th>Job Title</th>
                        <th>Application Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through applicants -->
                    <tr>
                        <td>Sarah Connor</td>
                        <td>Software Developer</td>
                        <td>Pending</td>
                        <td>
                            <a href="approve_application.php?id=1" class="btn btn-success btn-sm">Approve</a>
                            <a href="reject_application.php?id=1" class="btn btn-danger btn-sm">Reject</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Mike Wazowski</td>
                        <td>HR Manager</td>
                        <td>Approved</td>
                        <td>
                            <a href="view_application.php?id=2" class="btn btn-info btn-sm">View</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>
</div>
<?php include('./includes/footer.php'); ?>
