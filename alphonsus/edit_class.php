<?php
// This is all My code. Bootstrap has been used and Google Graphs.

include 'config.php'; // Include the database configuration file
include 'header.php'; // Include the header file for the page layout

// Fetch the class details
if (isset($_GET['id'])) { // Check if a class ID is provided in the URL
    $class_id = $_GET['id']; // Get the class ID from the URL
    $query = "SELECT * FROM Classes WHERE ClassID = ?"; // Query to fetch class details
    $stmt = $conn->prepare($query); // Prepare the query to prevent SQL injection
    $stmt->bind_param("i", $class_id); // Bind the class ID to the query
    $stmt->execute(); // Execute the query
    $result = $stmt->get_result(); // Get the result of the query
    $class = $result->fetch_assoc(); // Fetch the class details as an associative array
}

// Update the class information
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Check if the form was submitted
    $class_name = $_POST['class_name']; // Get the updated class name from the form
    $class_capacity = $_POST['class_capacity']; // Get the updated class capacity from the form
    $notes = $_POST['notes']; // Get the updated notes from the form

    // Query to update the class details in the database
    $update_query = "UPDATE Classes SET ClassName = ?, ClassCapacity = ?, ClassNotes = ? WHERE ClassID = ?";
    $stmt = $conn->prepare($update_query); // Prepare the query to prevent SQL injection
    $stmt->bind_param("sisi", $class_name, $class_capacity, $notes, $class_id); // Bind the form data to the query

    if ($stmt->execute()) { // Execute the query and check if it was successful
        echo "<div class='alert alert-success'>Class updated successfully!</div>"; // Display a success message
        header("refresh:2;url=view_classes.php"); // Redirect to the classes page after 2 seconds
    } else {
        echo "<div class='alert alert-danger'>Error updating class.</div>"; // Display an error message if the update fails
    }
}
?>

<div class="container mt-5">
    <h2>Edit Class</h2>
    <!-- Form to edit class details -->
    <form method="POST" action="">
        <!-- Input for the class name -->
        <div class="mb-3">
            <label for="class_name" class="form-label">Class Name</label>
            <input type="text" class="form-control" id="class_name" name="class_name" value="<?= $class['ClassName'] ?>" required>
        </div>
        <!-- Input for the class capacity -->
        <div class="mb-3">
            <label for="class_capacity" class="form-label">Class Capacity</label>
            <input type="number" class="form-control" id="class_capacity" name="class_capacity" value="<?= $class['ClassCapacity'] ?>" required>
        </div>
        <!-- Textarea for class notes -->
        <div class="mb-3">
            <label for="notes" class="form-label">Class Notes</label>
            <textarea class="form-control" id="notes" name="notes"><?= $class['ClassNotes'] ?></textarea>
        </div>
        <!-- Submit button to update the class -->
        <button type="submit" class="btn btn-primary">Update Class</button>
        <!-- Cancel button to go back to the classes page -->
        <a href="view_classes.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php
$conn->close(); // Close the database connection
include 'footer.php'; // Include the footer file
?>