<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include("db.php");

// ✅ Handle acknowledge action safely
if (isset($_GET['ack'])) {
    $alert_id = (int) $_GET['ack']; // 🔒 prevent injection

    if ($alert_id > 0) {
        $conn->query("UPDATE alerts SET acknowledged=1 WHERE id=$alert_id");
    }

    // ✅ Redirect to avoid duplicate action on refresh
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard - SafeHer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background: #f8f9fa; }
    .navbar-brand img { max-height: 36px; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="admin_dashboard.php">
      <img src="images/logo.png" class="me-2">
    </a>

    <div>
      <span class="text-white me-3">
        Admin: <?php echo htmlspecialchars($_SESSION['admin']); ?>
      </span>
      <a href="admin_logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h4 class="mb-3">All SOS Alerts</h4>

  <div class="card shadow-sm">
    <div class="card-body">

      <table class="table table-bordered align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>User</th>
            <th>Location</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
        <?php
        $sql = "SELECT alerts.*, users.name AS user_name
                FROM alerts
                JOIN users ON alerts.user_id = users.id
                ORDER BY alerts.alert_time DESC";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
          <tr>
            <td><?php echo $row['id']; ?></td>

            <td><?php echo htmlspecialchars($row['user_name']); ?></td>

            <td>
              <a href="https://maps.google.com/?q=<?php echo $row['latitude']; ?>,<?php echo $row['longitude']; ?>" target="_blank">
                View Map
              </a>
            </td>

            <td><?php echo $row['alert_time']; ?></td>

            <!-- ✅ STATUS -->
            <td>
              <?php if ($row['acknowledged'] == 0) { ?>
                <span class="badge bg-warning text-dark">Pending</span>
              <?php } else { ?>
                <span class="badge bg-success">Acknowledged</span>
              <?php } ?>
            </td>

            <!-- ✅ ACTION -->
            <td>
              <?php if ($row['acknowledged'] == 0) { ?>
                <a href="admin_dashboard.php?ack=<?php echo $row['id']; ?>"
                   class="btn btn-success btn-sm"
                   onclick="return confirm('Mark this alert as acknowledged?');">
                   ✔ Acknowledge
                </a>
              <?php } else { ?>
                <span class="text-muted">Done</span>
              <?php } ?>
            </td>

          </tr>
        <?php
            }
        } else {
        ?>
          <tr>
            <td colspan="6" class="text-center text-muted">No alerts found</td>
          </tr>
        <?php } ?>
        </tbody>
      </table>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>