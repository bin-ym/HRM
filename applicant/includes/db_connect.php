<?php
try {
    // Use the correct database name here
    $pdo = new PDO('mysql:host=localhost;dbname=hrm', 'root', '');  // Ensure the database name is correct
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Enable error handling
} catch (PDOException $e) {
    // Handle connection errors
    die("Could not connect to the database: " . $e->getMessage());
}
?>
