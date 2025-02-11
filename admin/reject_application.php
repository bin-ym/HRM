<?php
// Include database connection
include('./includes/db_connect.php');

// Get application ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    try {
        // Update application status to "Rejected"
        $sql = "UPDATE applications SET status = 'Rejected' WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        // Redirect back to manage_applicants.php
        header('Location: manage_applicants.php?message=Application Rejected Successfully');
        exit;
    } catch (PDOException $e) {
        die("Error updating status: " . $e->getMessage());
    }
} else {
    die("Invalid Application ID");
}
?>
