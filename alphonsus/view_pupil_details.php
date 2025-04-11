<?php
// This is all My code. Bootstrap has been used and Google Graphs.

include 'config.php'; // Include the database configuration file to establish a connection
include 'header.php'; // Include the header file for the page layout

// Get the Pupil ID from the query string
$pupilID = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;

if (!$pupilID) { // Check if the Pupil ID is valid
    echo "<script>alert('Invalid Pupil ID.'); window.location.href='view_pupils.php';</script>"; // Show an error message and redirect
    exit; // Stop further execution
}

// Fetch pupil details
$query = "
    SELECT 
        p.*, 
        c.ClassName, 
        g1.FirstName AS Guardian1FirstName, 
        g1.Surname AS Guardian1Surname, 
        g2.FirstName AS Guardian2FirstName, 
        g2.Surname AS Guardian2Surname
    FROM Pupils p
    LEFT JOIN Classes c ON p.ClassID = c.ClassID
    LEFT JOIN Guardian g1 ON p.Guardian1ID = g1.GuardianID
    LEFT JOIN Guardian g2 ON p.Guardian2ID = g2.GuardianID
    WHERE p.PupilID = ?
";
$stmt = $conn->prepare($query); // Prepare the query to prevent SQL injection
$stmt->bind_param("i", $pupilID); // Bind the Pupil ID to the query
$stmt->execute(); // Execute the query
$result = $stmt->get_result(); // Get the result set
$pupil = $result->fetch_assoc(); // Fetch the pupil details as an associative array

if (!$pupil) { // Check if the pupil exists
    echo "<script>alert('Pupil not found.'); window.location.href='view_pupils.php';</script>"; // Show an error message and redirect
    exit; // Stop further execution
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Pupil Details</h2> <!-- Page heading -->

    <!-- Card to display pupil details -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($pupil['Title'] . ' ' . $pupil['FirstName'] . ' ' . $pupil['Surname']) ?></h5> <!-- Display the pupil's full name -->
            <p class="card-text"><strong>Date of Birth:</strong> <?= date('d/m/Y', strtotime($pupil['DOB'])) ?></p> <!-- Display the pupil's date of birth -->
            <p class="card-text"><strong>Class:</strong> <?= htmlspecialchars($pupil['ClassName'] ?? 'N/A') ?></p> <!-- Display the pupil's class name -->
            <p class="card-text"><strong>Address:</strong> <?= htmlspecialchars($pupil['Address_Line1'] . ', ' . $pupil['Address_Line2'] . ', ' . $pupil['Address_Line3'] . ', ' . $pupil['Postcode']) ?></p> <!-- Display the pupil's address -->
            <p class="card-text"><strong>Guardian 1:</strong> <?= htmlspecialchars($pupil['Guardian1FirstName'] . ' ' . $pupil['Guardian1Surname'] ?? 'N/A') ?></p> <!-- Display the first guardian's name -->
            <p class="card-text"><strong>Guardian 2:</strong> <?= htmlspecialchars($pupil['Guardian2FirstName'] . ' ' . $pupil['Guardian2Surname'] ?? 'N/A') ?></p> <!-- Display the second guardian's name -->
            <p class="card-text"><strong>Medical Allergies:</strong> <?= htmlspecialchars($pupil['Medical_Allergy'] ?? 'None recorded') ?></p> <!-- Display the pupil's medical allergies -->
            <p class="card-text"><strong>Medical Vaccinations:</strong> <?= htmlspecialchars($pupil['Medical_Vaccination'] ?? 'None recorded') ?></p> <!-- Display the pupil's medical vaccinations -->
            <p class="card-text"><strong>Notes:</strong> <?= htmlspecialchars($pupil['Notes'] ?? 'None') ?></p> <!-- Display any additional notes -->
        </div>
    </div>

    <!-- Buttons to edit the pupil or go back to the pupil list -->
    <div class="mt-4">
        <a href="edit_pupil.php?id=<?= $pupil['PupilID'] ?>" class="btn btn-warning">Edit Pupil</a> <!-- Button to edit the pupil -->
        <a href="view_pupils.php" class="btn btn-secondary">Back to Pupil List</a> <!-- Button to go back to the pupil list -->
    </div>
</div>

<?php 
$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
include 'footer.php'; // Include the footer file for the page
?>