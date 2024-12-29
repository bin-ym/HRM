<?php include('./includes/header.php'); ?>
<?php include('./includes/navbar.php'); ?>
<div class="container-fluid">
    <div class="row">
        <?php include('./includes/sidebar.php'); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2>Settings</h2>
            <form action="save_settings.php" method="POST">
                <div class="mb-3">
                    <label for="siteTitle" class="form-label">Site Title</label>
                    <input type="text" class="form-control" id="siteTitle" name="siteTitle" value="Debark University HRM">
                </div>
                <div class="mb-3">
                    <label for="adminEmail" class="form-label">Admin Email</label>
                    <input type="email" class="form-control" id="adminEmail" name="adminEmail" value="admin@debarkuniversity.edu">
                </div>
                <div class="mb-3">
                    <label for="timezone" class="form-label">Timezone</label>
                    <select class="form-select" id="timezone" name="timezone">
                        <option selected value="Africa/Addis_Ababa">Africa/Addis Ababa</option>
                        <option value="UTC">UTC</option>
                        <option value="Europe/London">Europe/London</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
        </main>
    </div>
</div>
<?php include('./includes/footer.php'); ?>
