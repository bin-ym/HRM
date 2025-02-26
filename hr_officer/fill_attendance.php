<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    $query = "INSERT INTO attendance (employee_id, date, status) VALUES ('$employee_id', '$date', '$status')";
    if ($conn->query($query)) {
        $_SESSION['success'] = "Attendance recorded!";
    } else {
        $_SESSION['error'] = "Error recording attendance.";
    }
}

$query_employees = "SELECT employee_id, first_name, last_name FROM employees";
$result_employees = $conn->query($query_employees);

include './includes/hr_navbar.php'; // Fixed navbar path
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fill Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Record Employee Attendance</h2>
    <form method="post">
        <div class="mb-3">
            <label>Employee</label>
            <select name="employee_id" class="form-control" required>
                <?php while ($row = $result_employees->fetch(PDO::FETCH_ASSOC)) { ?>
                    <option value="<?= $row['employee_id']; ?>"><?= htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Submit Attendance</button>
    </form>
</div>

</body>
</html>
