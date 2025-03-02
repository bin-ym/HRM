<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    $_SESSION['error'] = "Please log in as an Employee to access this page.";
    header("Location: ../PUBLIC/login.php");
    exit();
}

$employee_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT salary_id, basic_salary, allowances, deductions, net_salary, payment_date 
                            FROM salaries WHERE employee_id = ? ORDER BY payment_date DESC");
    $stmt->execute([$employee_id]);
    $salaries = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Salary Details Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading salary details: " . $e->getMessage();
}

include('employee_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Details - Debark University HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4"><i class="fas fa-money-bill-wave me-2"></i>Salary Details</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (empty($salaries)): ?>
            <p class="text-center">No salary records found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Payment Date</th>
                            <th>Basic Salary</th>
                            <th>Allowances</th>
                            <th>Deductions</th>
                            <th>Net Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salaries as $salary): ?>
                            <tr>
                                <td><?= htmlspecialchars(date('F j, Y', strtotime($salary['payment_date']))); ?></td>
                                <td><?= htmlspecialchars(number_format($salary['basic_salary'], 2)); ?></td>
                                <td><?= htmlspecialchars(number_format($salary['allowances'], 2)); ?></td>
                                <td><?= htmlspecialchars(number_format($salary['deductions'], 2)); ?></td>
                                <td><?= htmlspecialchars(number_format($salary['net_salary'], 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <div class="text-center mt-3">
            <a href="employee_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>