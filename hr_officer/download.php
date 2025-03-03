<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR Officer') {
    header("HTTP/1.1 403 Forbidden");
    exit("Access denied.");
}

if (isset($_GET['file'])) {
    $filename = filter_var($_GET['file'], FILTER_SANITIZE_STRING);
    $file_path = realpath("../uploads/" . $filename);

    // Security: Ensure file is within uploads directory
    $upload_dir = realpath("../uploads/");
    if ($file_path && strpos($file_path, $upload_dir) === 0 && file_exists($file_path)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        header("HTTP/1.1 404 Not Found");
        exit("File not found.");
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    exit("No file specified.");
}
?>