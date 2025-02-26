<?php
session_start();
require_once '../config/database.php';

// Ensure the user is logged in as an Employee
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    $_SESSION['error'] = "Please log in to access the dashboard.";
    header("Location: ../public/login.php");
    exit();
}

$employee_id = $_SESSION['user_id']; // Using user_id stored from login

try {
    // Fetch salary details (fix column names)
    $sql_salary = "SELECT payment_date, basic_salary, bonuses, deductions, net_pay FROM salary WHERE employee_id = ?";
    $stmt_salary = $conn->prepare($sql_salary);
    $stmt_salary->execute([$employee_id]);
    $salary_details = $stmt_salary->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: ../public/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Details - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body>
    
<?php include('./employee_navbar.php'); ?>
    <div class="container my-5">
        <h1 class="text-center">Your Salary History</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Payment Date</th>
                    <th>Basic Salary</th>
                    <th>Bonuses</th>
                    <th>Deductions</th>
                    <th>Net Pay</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salary_details as $salary): ?>
                    <tr>
                        <td><?= htmlspecialchars($salary['payment_date']); ?></td>
                        <td>$<?= number_format($salary['basic_salary'], 2); ?></td>
                        <td>$<?= number_format($salary['bonuses'], 2); ?></td>
                        <td>$<?= number_format($salary['deductions'], 2); ?></td>
                        <td>$<?= number_format($salary['net_pay'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
