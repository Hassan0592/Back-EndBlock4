<!DOCTYPE html>
<html lang="en">
<head>
    <!-- This is all My code. Bootstrap has been used and Google Graphs. -->

    <!-- Meta tags for character encoding and responsive design -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Page title -->
    <title>St Alphonsus Primary School</title>

    <!-- Link to Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
/* Ensure the body and html take up the full height */
html, body {
    height: 100%; /* Makes the page take up the full height of the viewport */
    margin: 0; /* Removes default margin */
    display: flex; /* Enables flexbox layout */
    flex-direction: column; /* Stacks elements vertically */
}

/* Main content area */
main {
    flex: 1; /* Pushes the footer to the bottom of the page */
}

/* Navbar Overrides */
.navbar {
    background-color: #636b2f !important; /* Custom color for the navbar */
}

.navbar-brand,
.nav-link {
    color: white !important; /* Makes the text in the navbar white */
    font-weight: bold !important; /* Makes the text bold */
}

.navbar-toggler {
    border-color: white !important; /* Changes the border color of the toggle button */
}

.navbar-toggler-icon {
    filter: invert(1) !important; /* Makes the toggle icon white */
}

.nav-link:hover {
    text-decoration: underline !important; /* Adds an underline when hovering over links */
}

/* Button Overrides */
.custom-btn {
    background-color: #636b2f !important; /* Matches the navbar color */
    color: white !important; /* Makes the button text white */
    border-radius: 8px; /* Rounds the button corners */
    padding: 12px 20px; /* Adds padding inside the button */
    font-weight: bold !important; /* Makes the button text bold */
    transition: all 0.3s ease; /* Adds a smooth transition effect */
    border: none !important; /* Removes the button border */
}

.custom-btn:hover {
    background-color: #4e561f !important; /* Darkens the button color on hover */
    transform: scale(1.05); /* Slightly enlarges the button on hover */
}

/* Outline Buttons */
.custom-btn-outline {
    border: 2px solid #636b2f !important; /* Adds a border matching the navbar color */
    color: #636b2f !important; /* Makes the text match the navbar color */
    border-radius: 8px; /* Rounds the button corners */
    padding: 10px 20px; /* Adds padding inside the button */
    font-weight: bold !important; /* Makes the button text bold */
    transition: all 0.3s ease; /* Adds a smooth transition effect */
}

.custom-btn-outline:hover {
    background-color: #636b2f !important; /* Fills the button with the navbar color on hover */
    color: white !important; /* Changes the text color to white on hover */
    transform: scale(1.05); /* Slightly enlarges the button on hover */
}

/* Custom Button Styles */
.btn-custom {
    background-color: #636b2f !important; /* Matches the navbar color */
    color: white !important; /* Makes the button text white */
    border: none !important; /* Removes the button border */
    border-radius: 5px; /* Rounds the button corners */
    font-weight: bold; /* Makes the button text bold */
    transition: all 0.3s ease; /* Adds a smooth transition effect */
}

.btn-custom:hover {
    background-color: #4e561f !important; /* Darkens the button color on hover */
    color: white !important; /* Keeps the text white on hover */
    transform: scale(1.05); /* Slightly enlarges the button on hover */
}

/* Card Overrides */
.card {
    border: none !important; /* Removes the card border */
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1); /* Adds a subtle shadow to the card */
    transition: transform 0.3s ease; /* Adds a smooth transition effect */
}

.card:hover {
    transform: translateY(-5px); /* Moves the card slightly upward on hover */
}

.card-body {
    text-align: center !important; /* Centers the text inside the card */
}

/* Footer */
.footer {
    background-color: #636b2f !important; /* Matches the navbar color */
    color: white !important; /* Makes the footer text white */
    text-align: center; /* Centers the footer text */
    padding: 15px 0; /* Adds padding inside the footer */
    font-weight: bold !important; /* Makes the footer text bold */
    margin-top: auto; /* Ensures the footer stays at the bottom */
}
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">St Alphonsus School</a> <!-- Brand name -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span> <!-- Toggle button for smaller screens -->
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto"> <!-- Navbar links aligned to the right -->
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="view_classes.php">Classes</a></li>
                    <li class="nav-item"><a class="nav-link" href="teachers.php">Teachers</a></li>
                    <li class="nav-item"><a class="nav-link" href="guardian.php">Guardians</a></li>
                    <li class="nav-item"><a class="nav-link" href="books.php">Books</a></li>
                    <li class="nav-item"><a class="nav-link" href="attendance.php">Attendance</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
