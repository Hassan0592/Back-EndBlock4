<?php
// This is all My code. Bootstrap has been used and Google Graphs.

// Include the database configuration file to establish a connection
include 'config.php';

// Check if a class ID is provided in the URL
if (isset($_GET['id'])) {
    $class_id = $_GET['id']; // Get the class ID from the URL

    // Check if the class has any pupils assigned to it
    $check_query = "SELECT COUNT(*) AS pupil_count FROM Pupils WHERE ClassID = ?";
    $stmt = $conn->prepare($check_query); // Prepare the query to prevent SQL injection
    $stmt->bind_param("i", $class_id); // Bind the class ID to the query
    $stmt->execute(); // Execute the query
    $result = $stmt->get_result(); // Get the result of the query
    $row = $result->fetch_assoc(); // Fetch the result as an associative array

    // If the class has pupils assigned, prevent deletion
    if ($row['pupil_count'] > 0) {
        echo "<div class='alert alert-danger'>Cannot delete class with pupils assigned.</div>";
        // Redirect back to the classes page after 2 seconds
        header("refresh:2;url=view_classes.php");
    } else {
        // If no pupils are assigned, proceed to delete the class
        $delete_query = "DELETE FROM Classes WHERE ClassID = ?";
        $stmt = $conn->prepare($delete_query); // Prepare the delete query
        $stmt->bind_param("i", $class_id); // Bind the class ID to the query

        // Execute the delete query and check if it was successful
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Class deleted successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error deleting class.</div>";
        }
        // Redirect back to the classes page after 2 seconds
        header("refresh:2;url=view_classes.php");
    }
} else {
    // If no class ID is provided in the URL, display an error message
    echo "<div class='alert alert-danger'>No class ID provided.</div>";
}

// Close the database connection
$conn->close();
?>