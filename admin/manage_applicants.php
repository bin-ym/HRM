<?php 
include('./includes/header.php'); 
include('./includes/navbar.php'); 
include('./includes/db_connect.php'); // Include database connection
?>

<div class="container-fluid">
    <div class="row">
        <?php include('./includes/sidebar.php'); ?>
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
                        // Fetch applicants from the database
                        $sql = "SELECT id, applicant_name, job_title, status FROM applications ORDER BY id DESC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Loop through and display applicants
                        if (!empty($applicants)) {
                            foreach ($applicants as $applicant) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($applicant['applicant_name']) . "</td>
                                    <td>" . htmlspecialchars($applicant['job_title']) . "</td>
                                    <td>" . htmlspecialchars($applicant['status']) . "</td>
                                    <td>";

                                if ($applicant['status'] === 'Pending') {
                                    echo "<a href='approve_application.php?id=" . $applicant['id'] . "' class='btn btn-success btn-sm'>Approve</a>
                                          <a href='reject_application.php?id=" . $applicant['id'] . "' class='btn btn-danger btn-sm'>Reject</a>";
                                } else {
                                    echo "<a href='view_application.php?id=" . $applicant['id'] . "' class='btn btn-info btn-sm'>View</a>";
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
