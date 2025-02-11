<?php 
include('../includes/header.php'); 
include('../includes/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $applicant_name = $_POST['applicant_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Update profile in the database
    $sql = "UPDATE applicants SET name = :name, email = :email, address = :address WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $applicant_name,
        ':email' => $email,
        ':address' => $address,
        ':id' => $_SESSION['applicant_id'], // Assuming applicant_id is stored in session
    ]);

    echo "<div class='alert alert-success'>Profile updated successfully!</div>";
}

// Fetch applicant details from the database
$sql = "SELECT * FROM applicants WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $_SESSION['applicant_id']]);
$applicant = $stmt->fetch();

?>

<div class="container">
    <h2 class="my-4">Update Your Profile</h2>

    <form action="profile.php" method="POST">
        <div class="mb-3">
            <label for="applicant_name" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="applicant_name" name="applicant_name" value="<?= $applicant['name'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $applicant['email'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="4"><?= $applicant['address'] ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
