<?php
include 'config.php'; // Include the database configuration file This is all My code. bootstrap has been used and google graphs.
include 'header.php'; // Include the header file for the page layout

$success = ''; // Variable to store success messages
$error = ''; // Variable to store error messages

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $guardianType = $_POST['guardian_type']; // Type of guardian (e.g., Father, Mother)
    $title = $_POST['title']; // Title of the guardian (e.g., Mr, Mrs)
    $fname = $_POST['first_name']; // First name of the guardian
    $sname = $_POST['surname']; // Surname of the guardian
    $addr1 = $_POST['address_line1']; // Address line 1
    $addr2 = $_POST['address_line2']; // Address line 2
    $addr3 = $_POST['address_line3']; // Address line 3
    $postcode = $_POST['postcode']; // Postcode of the guardian's address
    $contact = $_POST['contact_number']; // Contact number of the guardian
    $email = $_POST['email_address']; // Email address of the guardian
    $notes = $_POST['notes']; // Additional notes about the guardian

    // Prepare the SQL query to insert the guardian into the database
    $stmt = $conn->prepare("INSERT INTO Guardian 
        (GuardianType, Title, FirstName, Surname, Address_Line1, Address_Line2, Address_Line3, Postcode, ContactNumber, EmailAddress, Notes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind the form data to the query parameters
    $stmt->bind_param("sssssssssss", $guardianType, $title, $fname, $sname, $addr1, $addr2, $addr3, $postcode, $contact, $email, $notes);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        $success = "Guardian added successfully!"; // Success message
    } else {
        $error = "Error: " . $stmt->error; // Display any SQL errors
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Add Guardian</h2>

    <!-- Display success or error messages -->
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Form to add a new guardian -->
    <form method="POST">
        <!-- Dropdown to select the guardian type -->
        <div class="mb-3">
            <label for="guardian_type" class="form-label">Guardian Type</label>
            <select name="guardian_type" id="guardian_type" class="form-select" required>
                <option value="">Select Guardian Type</option>
                <option value="Father">Father</option>
                <option value="Mother">Mother</option>
                <option value="Stepfather">Stepfather</option>
                <option value="Stepmother">Stepmother</option>
                <option value="Grandfather">Grandfather</option>
                <option value="Grandmother">Grandmother</option>
                <option value="Legal Guardian">Legal Guardian</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <!-- Dropdown to select the title -->
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <select name="title" class="form-select" required>
                <option value="">Select Title</option>
                <option value="Mr">Mr</option>
                <option value="Mrs">Mrs</option>
                <option value="Ms">Ms</option>
                <option value="Dr">Dr</option>
            </select>
        </div>

        <!-- Input for the first name -->
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>

        <!-- Input for the surname -->
        <div class="mb-3">
            <label for="surname" class="form-label">Surname</label>
            <input type="text" name="surname" class="form-control" required>
        </div>

        <!-- Input for address line 1 -->
        <div class="mb-3">
            <label for="address_line1" class="form-label">Address Line 1</label>
            <input type="text" name="address_line1" class="form-control">
        </div>

        <!-- Input for address line 2 -->
        <div class="mb-3">
            <label for="address_line2" class="form-label">Address Line 2</label>
            <input type="text" name="address_line2" class="form-control">
        </div>

        <!-- Input for address line 3 -->
        <div class="mb-3">
            <label for="address_line3" class="form-label">Address Line 3</label>
            <input type="text" name="address_line3" class="form-control">
        </div>

        <!-- Input for the postcode -->
        <div class="mb-3">
            <label for="postcode" class="form-label">Postcode</label>
            <input type="text" name="postcode" class="form-control">
        </div>

        <!-- Input for the contact number -->
        <div class="mb-3">
            <label for="contact_number" class="form-label">Contact Number</label>
            <input type="text" name="contact_number" class="form-control">
        </div>

        <!-- Input for the email address -->
        <div class="mb-3">
            <label for="email_address" class="form-label">Email Address</label>
            <input type="email" name="email_address" class="form-control">
        </div>

        <!-- Textarea for additional notes -->
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>

        <!-- Submit button to add the guardian -->
        <button type="submit" class="btn custom-btn">Add Guardian</button>
        <!-- Cancel button to go back to the main page -->
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'footer.php'; // Include the footer file ?>
