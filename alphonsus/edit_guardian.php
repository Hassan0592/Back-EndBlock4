<?php
// This is all My code. Bootstrap has been used and Google Graphs.

include 'config.php'; // Include the database configuration file
include 'header.php'; // Include the header file for the page layout

// Get the Guardian ID from the URL
$guardianId = $_GET['id'] ?? null; // Check if a Guardian ID is provided
if (!$guardianId) { // If no Guardian ID is provided, show an error and stop execution
    echo "<div class='alert alert-danger'>Invalid Guardian ID</div>";
    exit;
}

// Handle form submission to update the guardian's details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated details from the form
    $type = $_POST['guardian_type'];
    $title = $_POST['title'];
    $fname = $_POST['first_name'];
    $sname = $_POST['surname'];
    $addr1 = $_POST['address_line1'];
    $addr2 = $_POST['address_line2'];
    $addr3 = $_POST['address_line3'];
    $postcode = $_POST['postcode'];
    $contact = $_POST['contact_number'];
    $email = $_POST['email_address'];
    $notes = $_POST['notes'];

    // Prepare the SQL query to update the guardian's details
    $stmt = $conn->prepare("UPDATE Guardian SET GuardianType=?, Title=?, FirstName=?, Surname=?, Address_Line1=?, Address_Line2=?, Address_Line3=?, Postcode=?, ContactNumber=?, EmailAddress=?, Notes=? WHERE GuardianID=?");
    $stmt->bind_param("sssssssssssi", $type, $title, $fname, $sname, $addr1, $addr2, $addr3, $postcode, $contact, $email, $notes, $guardianId);

    // Execute the query and check if the update was successful
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Guardian updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}

// Fetch the guardian's current details from the database
$result = $conn->query("SELECT * FROM Guardian WHERE GuardianID = $guardianId");
$guardian = $result->fetch_assoc(); // Store the guardian's details in an associative array
?>

<div class="container mt-5">
    <h2>Edit Guardian</h2>
    <!-- Form to edit guardian details -->
    <form method="POST">
        <!-- Dropdown to select the guardian type -->
        <div class="mb-3">
            <label class="form-label">Guardian Type</label>
            <select name="guardian_type" class="form-select" required>
                <?php
                // List of possible guardian types
                $types = ['Mother', 'Father', 'Step-Mother', 'Step-Father', 'Grandparent', 'Aunt', 'Uncle', 'Other'];
                foreach ($types as $typeOption) {
                    // Mark the current type as selected
                    $selected = $guardian['GuardianType'] === $typeOption ? 'selected' : '';
                    echo "<option value='$typeOption' $selected>$typeOption</option>";
                }
                ?>
            </select>
        </div>

        <!-- Input fields for guardian details -->
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($guardian['Title']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($guardian['FirstName']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Surname</label>
            <input type="text" name="surname" class="form-control" value="<?= htmlspecialchars($guardian['Surname']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Address Line 1</label>
            <input type="text" name="address_line1" class="form-control" value="<?= htmlspecialchars($guardian['Address_Line1']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Address Line 2</label>
            <input type="text" name="address_line2" class="form-control" value="<?= htmlspecialchars($guardian['Address_Line2']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Address Line 3</label>
            <input type="text" name="address_line3" class="form-control" value="<?= htmlspecialchars($guardian['Address_Line3']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Postcode</label>
            <input type="text" name="postcode" class="form-control" value="<?= htmlspecialchars($guardian['Postcode']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact_number" class="form-control" value="<?= htmlspecialchars($guardian['ContactNumber']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email_address" class="form-control" value="<?= htmlspecialchars($guardian['EmailAddress']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control"><?= htmlspecialchars($guardian['Notes']) ?></textarea>
        </div>

        <!-- Buttons to submit or cancel the form -->
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="view_guardians.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'footer.php'; // Include the footer file ?>