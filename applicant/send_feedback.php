<?php
session_start();
include('../config/database.php');
include('./includes/applicant_navbar.php');

$applicant_id = $_SESSION['applicant_id']; // Safely get the applicant ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = htmlspecialchars($_POST['message']);
    $feedback_type = htmlspecialchars($_POST['feedback_type']); // Feedback type (complaint, suggestion, etc.)

    // SQL query to insert feedback into the Feedback table
    $sql = "INSERT INTO Feedback (feedback_text, feedback_type) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    // Execute the query, passing the applicant's ID, the message, and feedback type
    if ($stmt->execute([$applicant_id, $message, $feedback_type])) {
        $_SESSION['success'] = "Feedback sent successfully!";
    } else {
        $_SESSION['error'] = "Error sending feedback.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Feedback - Debark University HRM</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center">Send Feedback</h2>
        
        <!-- Display success or error message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Feedback Form -->
        <form action="" method="POST">
            <div class="mb-3">
                <label for="feedback_type" class="form-label">Feedback Type</label>
                <select name="feedback_type" id="feedback_type" class="form-select" required>
                    <option value="complaint">Complaint</option>
                    <option value="suggestion">Suggestion</option>
                    <option value="inquiry">Inquiry</option>
                </select>
            </div>

            <div class="mb-3">
                <textarea name="message" class="form-control" rows="5" required placeholder="Your feedback..."></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </form>
    </div>
</body>
<?php include('../includes/footer.php'); ?>
</html>
