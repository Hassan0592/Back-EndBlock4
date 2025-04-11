<?php
session_start(); // Start the session This is all My code. bootstrap has been used and google graphs.
include 'config.php'; // Include the database configuration file

$error = ""; // Variable to store error messages

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']); // Sanitize username input
    $password = trim($_POST['password']); // Sanitize password input

    // Prepare a query to fetch the admin details based on the username
    $stmt = $conn->prepare("SELECT * FROM administrators WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        // If a user is found, fetch their details
        $admin = $result->fetch_assoc();

        // Verify the entered password against the hashed password in the database
        if (password_verify($password, $admin['password'])) {
            // If the password is correct, set session variables to log the admin in
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;

            // Redirect to the admin dashboard (index.php)
            header("Location: index.php");
            exit();
        } else {
            // If the password is incorrect, set an error message
            $error = "Invalid credentials. Please try again.";
        }
    } else {
        // If no user is found, set an error message
        $error = "User not found. Please check your username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login - St Alphonsus</title>
    <!-- Include Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <!-- Card for the login form -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Administrator Login</h4>
                </div>
                <div class="card-body">
                    <!-- Display error messages if any -->
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <!-- Login form -->
                    <form method="post" action="">
                        <!-- Input for the username -->
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <!-- Input for the password -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-success w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
