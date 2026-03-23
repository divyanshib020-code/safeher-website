<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include("db.php");

// Fetch logged-in user details
$userName = $_SESSION['user'];
$userResult = $conn->query("SELECT * FROM users WHERE name='$userName'");
$userRow = $userResult->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SafeHer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="welcome.php">
    <img src="images/logo.png" height="32" class="me-2">
</a>
            <div>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <!-- SOS success alert -->
        <?php if (isset($_GET['alert']) && $_GET['alert'] == 'success') { ?>
            <div class="alert alert-success">🚨 SOS Alert Sent Successfully!</div>
        <?php } ?>

        <!-- Welcome message -->
        <h2 class="mb-4">Welcome, <?php echo $userRow['name']; ?> 🎉</h2>
        <p>Email: <?php echo $userRow['email']; ?></p>

        <!-- Quick action buttons (NOW 4 BUTTONS) -->
        <div class="row g-4 my-4">

            <div class="col-md-3">
                <a href="sos.php" class="btn btn-danger w-100 py-3">🚨 Send SOS</a>
            </div>

            <div class="col-md-3">
                <a href="contacts.php" class="btn btn-outline-primary w-100 py-3">📞 Manage Contacts</a>
            </div>

            <div class="col-md-3">
                <a href="alerts.php" class="btn btn-outline-success w-100 py-3">📋 View My Alerts</a>
            </div>

            <!-- ⭐ NEW PROFILE BUTTON -->
            <div class="col-md-3">
                <a href="profile.php" class="btn btn-outline-dark w-100 py-3">👤 My Profile</a>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="py-3 bg-dark text-white text-center">
        <small>© <?php echo date("Y"); ?> SafeHer. All rights reserved.</small>
    </footer>
</body>
</html>