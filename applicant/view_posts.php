<?php
session_start();
include('../config/database.php'); 
include('./includes/applicant_navbar.php'); 

// Fetch posts from the database
$sql = "SELECT post_id, title, content, created_at FROM Posts ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   <div class="container my-5">
    <h2 class="text-center">HRM Posts</h2>
    <?php foreach ($posts as $post): ?>
        <div class="card my-3">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($post['title']); ?></h5>
                <p class="card-text"><?= htmlspecialchars(substr($post['content'], 0, 200)) . "..."; ?></p>
                <a href="post_details.php?post_id=<?= $post['post_id']; ?>" class="btn btn-info">Read More</a>
            </div>
        </div>
    <?php endforeach; ?>
</div> 
</body>


<?php include('../includes/footer.php'); ?>
