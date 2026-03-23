<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include("db.php");

// Get current user ID
$userName = $_SESSION['user'];
$userResult = $conn->query("SELECT id FROM users WHERE name='$userName'");
$userRow = $userResult->fetch_assoc();
$userId = $userRow['id'];

// If SOS button pressed
if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $sql = "INSERT INTO alerts (user_id, latitude, longitude)
            VALUES ('$userId', '$latitude', '$longitude')";

    if ($conn->query($sql) === TRUE) {

        // ✅ NEW CHANGE: get alert ID and redirect to status page
        $alert_id = $conn->insert_id;
        header("Location: sos_status.php?alert_id=" . $alert_id);
        exit();

    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SOS Alert - SafeHer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="welcome.php">
            <img src="images/logo.png" height="32" class="me-2">
        </a>
        <div>
            <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-5 text-center">
    <h2 class="mb-4">Emergency SOS 🚨</h2>

    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>

    <form method="POST" id="sosForm">
        <input type="hidden" id="lat" name="latitude">
        <input type="hidden" id="long" name="longitude">

        <button type="button" class="btn btn-danger btn-lg px-5 py-4" onclick="sendSOS()">
            🚨 Send SOS
        </button>
    </form>

    <div class="mt-4">
        <div class="alert alert-warning d-inline-block" role="alert">
            Keep location enabled. Your alert will be saved with your current location.
        </div>
    </div>

    <a href="welcome.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
</div>

<script>
function sendSOS() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById("lat").value = position.coords.latitude;
            document.getElementById("long").value = position.coords.longitude;
            document.getElementById("sosForm").submit();
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
