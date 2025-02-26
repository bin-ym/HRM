<?php
// Database configuration
$host = 'localhost';
$dbname = 'hrm';       // Correct database name
$username = 'root';    // Replace with your database username
$password = '';        // Replace with your database password

try {
    // Create a new PDO instance with the correct variable names
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
