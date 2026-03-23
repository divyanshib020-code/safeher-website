<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include("db.php");

// Get current logged-in user ID
$userName = $_SESSION['user'];
$userResult = $conn->query("SELECT id FROM users WHERE name='$userName'");
$userRow = $userResult->fetch_assoc();
$userId = $userRow['id'];

// Handle Add Contact
if (isset($_POST['add_contact'])) {
    $contact_name = $_POST['contact_name'];
    $contact_number = $_POST['contact_number'];

    $sql = "INSERT INTO contacts (user_id, contact_name, contact_number) 
            VALUES ('$userId', '$contact_name', '$contact_number')";
    if ($conn->query($sql) === TRUE) {
        $success = "Contact added successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Handle Delete Contact
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $conn->query("DELETE FROM contacts WHERE id='$deleteId' AND user_id='$userId'");
    header("Location: contacts.php");
    exit();
}

// Fetch contacts
$result = $conn->query("SELECT * FROM contacts WHERE user_id='$userId'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Contacts - SafeHer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ⭐ REQUIRED STAR STYLE -->
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
        <div>
            <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2 class="mb-4">My Emergency Contacts</h2>

    <?php if (!empty($success)) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php } ?>
    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>

    <!-- Add Contact Form -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title">Add New Contact</h5>

            <form method="POST" action="" onsubmit="return validateContactForm()">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            Name <span class="required-star">*</span>
                        </label>
                        <input type="text" id="contact_name" name="contact_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            Phone <span class="required-star">*</span>
                        </label>
                        <input type="text" id="contact_number" name="contact_number" class="form-control" required>
                    </div>
                </div>
                <button type="submit" name="add_contact" class="btn btn-primary mt-3">Add Contact</button>
            </form>
        </div>
    </div>

    <!-- Saved Contacts -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title">Saved Contacts</h5>

            <?php if ($result->num_rows > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['contact_name']; ?></td>
                                    <td><?php echo $row['contact_number']; ?></td>
                                    <td>
                                        <a href="tel:<?php echo $row['contact_number']; ?>" class="btn btn-sm btn-success">📞 Call</a>
                                        <a href="contacts.php?delete=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Delete this contact?')">🗑️ Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            <?php } else { ?>
                <div class="alert alert-info">No contacts saved yet.</div>
            <?php } ?>
        </div>
    </div>

    <a href="welcome.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<!-- ⭐ CONTACT VALIDATION SCRIPT -->
<script>
function validateContactForm() {
    let name = document.getElementById("contact_name").value.trim();
    let phone = document.getElementById("contact_number").value.trim();

    if (name === "") {
        alert("Please enter the contact's name.");
        return false;
    }

    // Phone must be exactly 10 digits
    let phonePattern = /^[0-9]{10}$/;
    if (!phonePattern.test(phone)) {
        alert("Phone number must be exactly 10 digits.");
        return false;
    }

    return true;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>