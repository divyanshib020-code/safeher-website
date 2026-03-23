<?php
include("db.php");

if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        $success = "Signup successful! <a href='login.php'>Login here</a>";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup - SafeHer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ⭐ REQUIRED STAR CSS -->
    <style>
        .required-star {
            color: red;
            font-weight: bold;
            margin-left: 3px;
        }
    </style>

</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="welcome.php">
    <img src="images/logo.png" height="32" class="me-2">
</a>
    </div>
</nav>

<div class="container py-5" style="max-width: 600px;">
    <h2 class="mb-4">Create Account</h2>

    <?php if (!empty($success)) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php } ?>

    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">
                        Full Name <span class="required-star">*</span>
                    </label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Email address <span class="required-star">*</span>
                    </label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Password <span class="required-star">*</span>
                    </label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" name="signup" class="btn btn-primary w-100">Sign Up</button>
            </form>

            <p class="mt-3 mb-0">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>