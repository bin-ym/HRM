<?php
include('./includes/db_connect.php');
include('./includes/navbar.php');
?>

<div class="container-fluid">
    <div class="row">
        <?php include('./includes/sidebar.php'); ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2 class="my-4">Manage Applicants</h2>

            <!-- Display Success or Error Messages -->
            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
            <?php endif; ?>

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
                    <?php
                    try {
                        // Fetch applicants (make sure column names match your database)
                        $sql = "SELECT a.application_id, 
                                       CONCAT(c.first_name, ' ', c.last_name) AS candidate_name, 
                                       j.job_title, 
                                       a.application_status 
                                FROM applications a
                                JOIN candidates c ON a.candidate_id = c.candidate_id
                                JOIN jobs j ON a.job_id = j.job_id
                                ORDER BY a.application_id DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (!empty($applicants)) {
                            foreach ($applicants as $applicant) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($applicant['candidate_name']) . "</td>
                                    <td>" . htmlspecialchars($applicant['job_title']) . "</td>
                                    <td>" . htmlspecialchars($applicant['application_status']) . "</td>
                                    <td>";

                                if ($applicant['application_status'] === 'pending') {
                                    echo "<a href='approve_application.php?application_id=" . intval($applicant['application_id']) . "' class='btn btn-success btn-sm'>Approve</a>
                                          <a href='reject_application.php?application_id=" . intval($applicant['application_id']) . "' class='btn btn-danger btn-sm'>Reject</a>";
                                } else {
                                    echo "<a href='view_application.php?application_id=" . intval($applicant['application_id']) . "' class='btn btn-info btn-sm'>View</a>";
                                }

                                echo "</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No applicants found</td></tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='4' class='text-center text-danger'>Error fetching applicants: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<?php include('./includes/footer.php'); ?>
