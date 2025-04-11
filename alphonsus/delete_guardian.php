<?php
// This is all My code. Bootstrap has been used and Google Graphs.

// Include the database configuration file to establish a connection
include 'config.php';

// Check if a valid guardian ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $guardianID = $_GET['id']; // Get the guardian ID from the URL

    // Check if the guardian is assigned to any pupils
    $check = $conn->query("SELECT * FROM Pupils WHERE Guardian1ID = $guardianID OR Guardian2ID = $guardianID");

    // If the guardian is assigned to one or more pupils, prevent deletion
    if ($check->num_rows > 0) {
        echo "<script>alert('Cannot delete: Guardian is still assigned to one or more pupils.'); window.location.href='view_guardians.php';</script>";
    } else {
        // If the guardian is not assigned to any pupils, proceed to delete
        $stmt = $conn->prepare("DELETE FROM Guardian WHERE GuardianID = ?");
        $stmt->bind_param("i", $guardianID); // Bind the guardian ID to the query

        // Execute the delete query and check if it was successful
        if ($stmt->execute()) {
            echo "<script>alert('Guardian deleted successfully.'); window.location.href='view_guardians.php';</script>";
        } else {
            // Display an error message if the deletion fails
            echo "<script>alert('Error deleting guardian: " . $stmt->error . "'); window.location.href='view_guardians.php';</script>";
        }

        $stmt->close(); // Close the prepared statement
    }
} else {
    // If no valid guardian ID is provided, display an error message
    echo "<script>alert('Invalid request.'); window.location.href='view_guardians.php';</script>";
}

// Close the database connection
$conn->close();
?>
