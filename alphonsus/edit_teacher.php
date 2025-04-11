<?php
// This is all My code. Bootstrap has been used and Google Graphs.

include 'config.php'; // Include the database configuration file to establish a connection
include 'header.php'; // Include the header file for the page layout

// Fetch teacher details
if (isset($_GET['id'])) { // Check if a teacher ID is provided in the URL
    $teacher_id = $_GET['id']; // Get the teacher ID from the URL
    $query = "SELECT * FROM Teachers WHERE TeacherID = ?"; // Query to fetch teacher details
    $stmt = $conn->prepare($query); // Prepare the query to prevent SQL injection
    $stmt->bind_param("i", $teacher_id); // Bind the teacher ID to the query
    $stmt->execute(); // Execute the query
    $result = $stmt->get_result(); // Get the result of the query
    $teacher = $result->fetch_assoc(); // Fetch the teacher details as an associative array
}

// Update teacher information
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Check if the form was submitted
    $firstname = $_POST['firstname']; // Get the updated first name from the form
    $surname = $_POST['surname']; // Get the updated surname from the form
    $email = $_POST['email']; // Get the updated email address from the form
    $contact = $_POST['contact']; // Get the updated contact number from the form
    $salary = $_POST['salary']; // Get the updated salary from the form
    $class_id = $_POST['class_id']; // Get the updated class ID from the form

    // Query to update the teacher's details in the database
    $update_query = "
        UPDATE Teachers 
        SET FirstName = ?, Surname = ?, EmailAddress = ?, ContactNumber = ?, Salary = ?, ClassID = ?
        WHERE TeacherID = ?";
    $stmt = $conn->prepare($update_query); // Prepare the query to prevent SQL injection
    $stmt->bind_param("ssssiii", $firstname, $surname, $email, $contact, $salary, $class_id, $teacher_id); // Bind the form data to the query

    if ($stmt->execute()) { // Execute the query and check if it was successful
        echo "<div class='alert alert-success'>Teacher updated successfully!</div>"; // Display a success message
        header("refresh:2;url=teachers.php"); // Redirect to the teachers page after 2 seconds
    } else {
        echo "<div class='alert alert-danger'>Error updating teacher.</div>"; // Display an error message if the update fails
    }
}
?>

<div class="container mt-5">
    <h2>Edit Teacher</h2>
    <!-- Form to edit teacher details -->
    <form method="POST" action="">
        <!-- Input for the teacher's first name -->
        <div class="mb-3">
            <label>First Name</label>
            <input type="text" class="form-control" name="firstname" value="<?= $teacher['FirstName'] ?>" required>
        </div>
        <!-- Input for the teacher's surname -->
        <div class="mb-3">
            <label>Surname</label>
            <input type="text" class="form-control" name="surname" value="<?= $teacher['Surname'] ?>" required>
        </div>
        <!-- Input for the teacher's email address -->
        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" name="email" value="<?= $teacher['EmailAddress'] ?>" required>
        </div>
        <!-- Input for the teacher's contact number -->
        <div class="mb-3">
            <label>Contact</label>
            <input type="text" class="form-control" name="contact" value="<?= $teacher['ContactNumber'] ?>" required>
        </div>
        <!-- Input for the teacher's salary -->
        <div class="mb-3">
            <label>Salary</label>
            <input type="text" class="form-control" name="salary" value="<?= $teacher['Salary'] ?>" required>
        </div>
        <!-- Input for the teacher's class ID -->
        <div class="mb-3">
            <label>Class ID</label>
            <input type="number" class="form-control" name="class_id" value="<?= $teacher['ClassID'] ?>">
        </div>
        <!-- Submit button to update the teacher's details -->
        <button type="submit" class="btn btn-primary">Update Teacher</button>
        <!-- Cancel button to go back to the teachers page -->
        <a href="teachers.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php
$conn->close();
include 'footer.php'; 
?>