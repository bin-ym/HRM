<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'hrm'; // Ensure the database name is in lowercase
$username = 'root';
$password = '';

try {
    // Establish a database connection using PDO
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Enable exceptions for errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Fetch results as associative arrays
            PDO::ATTR_EMULATE_PREPARES => false,  // Use real prepared statements
            PDO::ATTR_PERSISTENT => true  // Persistent connection (optional)
        ]
    );
} catch (PDOException $e) {
    // If connection fails, display an error message
    exit("❌ Database connection failed: " . $e->getMessage());
}
?>
