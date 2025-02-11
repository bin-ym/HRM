<?php
include('./includes/db_connect.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id) {
    $sql = "UPDATE applications SET status = 'Approved' WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
}

header('Location: manage_applicants.php');
exit;
?>
