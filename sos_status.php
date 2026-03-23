<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include("db.php");

// DEBUG: check if alert_id is coming
if (!isset($_GET['alert_id'])) {
    echo "No alert_id received!";
    exit();
}

$alertId = $_GET['alert_id'];

// Fetch alert
$stmt = $conn->prepare("SELECT * FROM alerts WHERE id = ?");
$stmt->bind_param("i", $alertId);
$stmt->execute();
$result = $stmt->get_result();
$alert = $result->fetch_assoc();
$stmt->close();

// DEBUG: check if alert found
if (!$alert) {
    echo "Alert not found in database!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SOS Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container text-center mt-5">
    
<h2>SOS Status</h2>

<p><strong>Alert ID:</strong> <?php echo $alert['id']; ?></p>

<?php if ($alert['acknowledged'] == 0) { ?>
<div class="alert alert-warning">
    Waiting for response...
</div>
<?php } else { ?>
<div class="alert alert-success">
    Help is on the way!
</div>
<?php } ?>

<a href="welcome.php" class="btn btn-secondary mt-3">Back</a>

</div>

<script>
setTimeout(function() {
    window.location.reload();
}, 5000);
</script>

</body>
</html>