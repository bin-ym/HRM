<?php
// Start a session to track user login state
session_start();

// Define site-wide constants
define('SITE_NAME', 'Debark University HRM');
define('BASE_URL', 'http://localhost/HRM/'); // Adjust according to your project setup

// Set default timezone
date_default_timezone_set('Africa/Addis_Ababa');

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
