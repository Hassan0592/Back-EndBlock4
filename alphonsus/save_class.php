<?php
// This is all My code. Bootstrap has been used and Google Graphs.

include 'config.php'; // Include the database configuration file to establish a connection

// Check if the form was submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data and sanitize it
    $className = trim($_POST['class_name']); // Get the class name and remove extra spaces
    $classType = trim($_POST['class_type']); // Get the class type and remove extra spaces
    $capacity = (int) $_POST['capacity']; // Get the class capacity and convert it to an integer
    $notes = trim($_POST['notes']); // Get any additional notes and remove extra spaces

    // Server-side validation to ensure the capacity does not exceed 15
    if ($capacity > 15) {
        echo "<script>alert('Capacity cannot be greater than 15.'); window.history.back();</script>";
        exit; // Stop further execution if validation fails
    }

    // Prepare the SQL query to insert the new class into the database
    $stmt = $conn->prepare("
        INSERT INTO Classes (ClassName, ClassType, ClassCapacity, ClassNotes)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("ssis", $className, $classType, $capacity, $notes); // Bind the form data to the query

    // Execute the query and check if the insertion was successful
    if ($stmt->execute()) {
        // If successful, display a success message and redirect to the classes page
        echo "<script>alert('Class added successfully!'); window.location.href='view_classes.php';</script>";
    } else {
        // If there is an error, display an error message and go back to the previous page
        echo "<script>alert('Error adding class: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close(); // Close the prepared statement
} else {
    // If the script is accessed without submitting the form, redirect to the add class page
    header("Location: add_class.php");
    exit; // Stop further execution
}

$conn->close(); // Close the database connection