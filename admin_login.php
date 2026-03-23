<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: admin_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - SafeHer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { 
            background: #f8f9fa; 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh;
        }
        main { flex: 1; }

        .navbar-brand img {
            max-height: 36px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="images/logo.png" alt="SafeHer" class="me-2">
            <span class="visually-hidden">SafeHer</span>
        </a>
    </div>
</nav>

<main class="container d-flex justify-content-center align-items-center py-5" style="max-width:450px;">
    <div class="card shadow-sm w-100">
        <div class="card-body">
            <h3 class="mb-4 text-center">Admin Login</h3>

            <?php
            if (isset($_POST['login'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                if ($username == "admin" && $password == "admin123") {
                    $_SESSION['admin'] = "Administrator";
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    echo '<div class="alert alert-danger">Invalid admin credentials.</div>';
                }
            }
            ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username <span style="color:red">*</span></label>
                    <input type="text" class="form-control" name="username" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password <span style="color:red">*</span></label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>