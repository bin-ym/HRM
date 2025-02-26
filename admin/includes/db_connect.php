<?php
// Database configuration
$host = 'localhost';
$dbname = 'hrm';
$username = 'root';
$password = '';

try {
    // Create a PDO connection using $conn
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Database connection failed - " . $e->getMessage());
}
?>
