<?php
// This is all My code. Bootstrap has been used and Google Graphs.

session_start(); // Start the session to manage user authentication

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // If the admin is not logged in, redirect them to the login page
    header('Location: admin_login.php');
    exit; // Stop further execution of the script
}

include 'header.php'; // Include the header file for the page layout and navigation
?>

<div class="container mt-5">
    <div class="text-center">
        <!-- Welcome message for the admin -->
        <h1 class="display-4 fw-bold">Welcome to St Alphonsus Primary School System</h1>
        <p class="lead">Use the navigation menu to access different sections and manage school records efficiently.</p>

        <!-- Quick Navigation Buttons -->
        <div class="d-flex justify-content-center gap-3 mt-4">
            <!-- Button to view classes -->
            <a href="view_classes.php" class="btn custom-btn">View Classes</a>
            <!-- Button to manage teachers -->
            <a href="teachers.php" class="btn custom-btn">Teachers</a>
            <!-- Button to manage attendance -->
            <a href="attendance.php" class="btn custom-btn">Attendance</a>
        </div>

        <!-- Card Section -->
        <div class="row mt-5">
            <!-- Card for managing pupils -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Pupils</h5> <!-- Title of the card -->
                        <p class="card-text">Manage pupil information and guardianship records.</p> <!-- Description -->
                        <a href="add_pupil.php" class="btn custom-btn-outline">Add/manage Pupils</a> <!-- Button to manage pupils -->
                    </div>
                </div>
            </div>

            <!-- Card for managing books -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Books</h5> <!-- Title of the card -->
                        <p class="card-text">Check and manage book loans and returns.</p> <!-- Description -->
                        <a href="books.php" class="btn custom-btn-outline">Manage Books</a> <!-- Button to manage books -->
                    </div>
                </div>
            </div>

            <!-- Card for managing teachers -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Teachers</h5> <!-- Title of the card -->
                        <p class="card-text">Manage teacher information and assignments.</p> <!-- Description -->
                        <a href="teachers.php" class="btn custom-btn-outline">Manage Teachers</a> <!-- Button to manage teachers -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; // Include the footer file for the page ?>
