<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Manager') {
    $_SESSION['error'] = "Access denied. Please log in as a Manager.";
    header("Location: ../public/login.php");
    exit();
}

include './includes/manager_navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Evaluation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container my-5">
    <h2 class="text-center">Employee Performance Evaluation</h2>
    <p class="text-center">Assess employee performance based on project contribution and teamwork.</p>

    <form class="mt-4">
        <div class="mb-3">
            <label for="employee" class="form-label">Select Employee</label>
            <select class="form-control" id="employee">
                <option value="101">John Doe</option>
                <option value="102">Jane Smith</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="rating" class="form-label">Performance Rating (1-5)</label>
            <input type="number" class="form-control" id="rating" min="1" max="5">
        </div>
        <div class="mb-3">
            <label for="comments" class="form-label">Comments</label>
            <textarea class="form-control" id="comments" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit Evaluation</button>
    </form>
</div>

</body>
</html>
