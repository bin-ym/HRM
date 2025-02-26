<?php
session_start();
require_once '../config/database.php'; // Ensure database connection file exists

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    try {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ? AND status = 'Active'");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Invalid email or password.';
            header('Location: login.php');
            exit();
        }

        // Store session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        session_regenerate_id(true);

        // Redirect users based on their role
        switch ($user['role']) {
            case 'HR Officer':
                header('Location: ../hr_officer/hr_officer_dashboard.php');
                break;
            case 'Admin':
                header('Location: ../admin/admin_dashboard.php');
                break;
            case 'Dean':
                header('Location: ../dean/dean_dashboard.php');
                break;
            case 'Employee':
                header('Location: ../employee/employee_dashboard.php');
                break;
            case 'Finance Officer':
                header('Location: ../finance/finance_dashboard.php');
                break;
            case 'Department Head':
                header('Location: ../department/department_dashboard.php');
                break;
            case 'Applicant':
                header('Location: /HRM/HRM/applicant/applicant_dashboard.php');  // Updated path
                break;
            case 'Manager':
                header('Location: ../manager/manager_dashboard.php');
                break;
            default:
                $_SESSION['error'] = 'Access Denied: Role mismatch.';
                header('Location: login.php');
        }
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database Error: ' . $e->getMessage();
        header('Location: login.php');
        exit();
    }
}
?>
