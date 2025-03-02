<?php
session_start();
require_once('../config/database.php');
require_once('./includes/applicant_navbar.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Applicant') {
    $_SESSION['error'] = "Please log in to access this page.";
    header("Location: ../public/login.php");
    exit();
}

try {
    $sql = "SELECT post_id, title, content, created_at FROM posts ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Posts Error: " . $e->getMessage());
    $_SESSION['error'] = "Error loading posts. Please try again.";
    header("Location: applicant_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">HRM Announcements</h2>
        <?php if (empty($posts)): ?>
            <p class="text-center">No announcements available.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card my-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($post['title']); ?></h5>
                        <p class="card-text"><?= htmlspecialchars(substr($post['content'], 0, 200)) . "..."; ?></p>
                        <p class="text-muted small">Posted on: <?= date('F j, Y', strtotime($post['created_at'])); ?></p>
                        <a href="post_details.php?post_id=<?= $post['post_id']; ?>" class="btn btn-info btn-sm">Read More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>