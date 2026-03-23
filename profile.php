<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include("db.php");

// Get current logged-in user
$userName = $_SESSION['user'];

// Fetch user details (secure way)
$stmt = $conn->prepare("SELECT id, name, email, created_at FROM users WHERE name = ?");
$stmt->bind_param("s", $userName);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

$userId = $user['id'];

// Handle update profile (name/email)
if (isset($_POST['update_profile'])) {
    $new_name = trim($_POST['name']);
    $new_email = trim($_POST['email']);

    if ($new_name === "" || $new_email === "") {
        $profile_error = "Name and email cannot be empty.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $profile_error = "Enter a valid email.";
    } else {
        $u_stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $u_stmt->bind_param("ssi", $new_name, $new_email, $userId);
        if ($u_stmt->execute()) {
            $profile_success = "Profile updated successfully.";
            $_SESSION['user'] = $new_name;
            $user['name'] = $new_name;
            $user['email'] = $new_email;
        } else {
            $profile_error = "Update failed.";
        }
        $u_stmt->close();
    }
}

// Handle change password
if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($new !== $confirm) {
        $pass_error = "New passwords do not match.";
    } else {
        // Check old password
        $pstmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $pstmt->bind_param("i", $userId);
        $pstmt->execute();
        $res2 = $pstmt->get_result()->fetch_assoc();
        $pstmt->close();

        if (!password_verify($current, $res2['password'])) {
            $pass_error = "Current password is incorrect.";
        } else {
            $new_hashed = password_hash($new, PASSWORD_DEFAULT);
            $up = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $up->bind_param("si", $new_hashed, $userId);
            if ($up->execute()) {
                $pass_success = "Password updated successfully.";
            } else {
                $pass_error = "Password update failed.";
            }
            $up->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - SafeHer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .required-star { color: red; margin-left: 3px; font-weight: bold; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="welcome.php">
    <img src="images/logo.png" height="32" class="me-2">
</a>
        <div><a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a></div>
    </div>
</nav>

<div class="container py-5" style="max-width:900px;">
    <h2 class="mb-4">My Profile</h2>

    <!-- Messages -->
    <?php if (!empty($profile_success)) { ?>
        <div class="alert alert-success"><?php echo $profile_success; ?></div>
    <?php } ?>
    <?php if (!empty($profile_error)) { ?>
        <div class="alert alert-danger"><?php echo $profile_error; ?></div>
    <?php } ?>
    <?php if (!empty($pass_success)) { ?>
        <div class="alert alert-success"><?php echo $pass_success; ?></div>
    <?php } ?>
    <?php if (!empty($pass_error)) { ?>
        <div class="alert alert-danger"><?php echo $pass_error; ?></div>
    <?php } ?>

    <div class="row g-4">
        <!-- User info -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Account Info</h5>
                    <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
                    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                    <p><strong>Created:</strong>
                        <?php echo !empty($user['created_at']) ? $user['created_at'] : "N/A"; ?>
                    </p>
                    <a href="welcome.php" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>

        <!-- Update forms -->
        <div class="col-md-7">
            <!-- Update Profile -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5>Update Profile</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Name <span class="required-star">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Email <span class="required-star">*</span></label>
                            <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Change Password</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Current Password <span class="required-star">*</span></label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>New Password <span class="required-star">*</span></label>
                            <input type="password" name="new_password" class="form-control" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label>Confirm Password <span class="required-star">*</span></label>
                            <input type="password" name="confirm_password" class="form-control" required minlength="6">
                        </div>
                        <button type="submit" name="change_password" class="btn btn-warning">Change Password</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>