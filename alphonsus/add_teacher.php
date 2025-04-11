<?php
include 'config.php'; // Include the database configuration file This is all My code. bootstrap has been used and google graphs.
include 'header.php'; // Include the header file for the page layout

$success = false; // Variable to track if the teacher was added successfully
$error = false; // Variable to store error messages

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $title = $_POST['title']; // Title of the teacher (e.g., Mr, Mrs)
    $fname = $_POST['first_name']; // First name of the teacher
    $sname = $_POST['surname']; // Surname of the teacher
    $email = $_POST['email']; // Email address of the teacher
    $contact = $_POST['contact']; // Contact number of the teacher
    $classID = $_POST['class_id'] ?? null; // Class ID to assign the teacher to (optional)

    if ($classID) {
        // Check if the selected class is already assigned to another teacher
        $checkStmt = $conn->prepare("SELECT * FROM Teachers WHERE ClassID = ?");
        $checkStmt->bind_param("i", $classID);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // If the class is already assigned, set an error message
            $error = "This class is already assigned to a teacher.";
        } else {
            // Insert the teacher into the database with the assigned class
            $stmt = $conn->prepare("INSERT INTO Teachers (Title, FirstName, Surname, EmailAddress, ContactNumber, ClassID) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $title, $fname, $sname, $email, $contact, $classID);

            if ($stmt->execute()) {
                $success = true; // Mark as successful if the query executes
            } else {
                $error = "Failed to add teacher. Please try again."; // Set an error message if the query fails
            }
        }
    } else {
        // If no class is selected, insert the teacher without assigning a class
        $stmt = $conn->prepare("INSERT INTO Teachers (Title, FirstName, Surname, EmailAddress, ContactNumber, ClassID) VALUES (?, ?, ?, ?, ?, NULL)");
        $stmt->bind_param("sssss", $title, $fname, $sname, $email, $contact);

        if ($stmt->execute()) {
            $success = true; // Mark as successful if the query executes
        } else {
            $error = "Failed to add teacher. Please try again."; // Set an error message if the query fails
        }
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Add New Teacher</h2>

    <!-- Display success or error messages -->
    <?php if ($success): ?>
        <div class="alert alert-success">Teacher added successfully!</div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Form to add a new teacher -->
    <form method="POST" class="card p-4 shadow">
        <!-- Dropdown to select the teacher's title -->
        <div class="mb-3">
            <label class="form-label">Title</label>
            <select name="title" class="form-select" required>
                <option value="">Choose...</option>
                <option value="Mr">Mr</option>
                <option value="Mrs">Mrs</option>
                <option value="Ms">Ms</option>
                <option value="Dr">Dr</option>
            </select>
        </div>

        <!-- Input for the teacher's first name -->
        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>

        <!-- Input for the teacher's surname -->
        <div class="mb-3">
            <label class="form-label">Surname</label>
            <input type="text" name="surname" class="form-control" required>
        </div>

        <!-- Input for the teacher's email address -->
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <!-- Input for the teacher's contact number -->
        <div class="mb-3">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact" class="form-control" required>
        </div>

        <!-- Dropdown to assign the teacher to a class (optional) -->
        <div class="mb-3">
            <label class="form-label">Assign to Class (optional)</label>
            <select name="class_id" class="form-select">
                <option value="">None</option>
                <?php
                // Fetch classes that are not already assigned to a teacher
                $classQuery = $conn->query("
                    SELECT ClassID, ClassName 
                    FROM Classes 
                    WHERE ClassID NOT IN (SELECT ClassID FROM Teachers WHERE ClassID IS NOT NULL)
                ");
                while ($class = $classQuery->fetch_assoc()) {
                    echo "<option value='{$class['ClassID']}'>{$class['ClassName']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Submit button to add the teacher -->
        <button type="submit" class="btn custom-btn">Add Teacher</button>
    </form>
</div>

<?php include 'footer.php'; // Include the footer file ?>
