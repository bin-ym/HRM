<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../public/login.php");
    exit();
}

$query = "SELECT * FROM employees";
$result = $conn->query($query);

include './includes/hr_navbar.php';
?>
