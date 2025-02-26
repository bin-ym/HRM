<?php
include('./includes/db_connect.php');

try {
    if (!isset($_GET['application_id']) || !is_numeric($_GET['application_id'])) {
        throw new Exception("Invalid or missing application ID.");
    }

    $id = intval($_GET['application_id']);

    // Check if application exists
    $sql_check = "SELECT COUNT(*) FROM applications WHERE application_id = :application_id";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute(['application_id' => $id]);
    $applicationExists = $stmt_check->fetchColumn();

    if ($applicationExists == 0) {
        throw new Exception("Application not found.");
    }

    // Update application status
    $sql = "UPDATE applications SET application_status = 'rejected' WHERE application_id = :application_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['application_id' => $id]);

    header("Location: manage_applicants.php?message=Application Rejected Successfully");
    exit;
} catch (Exception $e) {
    header("Location: manage_applicants.php?message=" . urlencode("Error: " . $e->getMessage()));
    exit;
}
