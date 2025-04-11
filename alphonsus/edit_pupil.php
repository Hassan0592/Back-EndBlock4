<?php
// This is all My code. Bootstrap has been used and Google Graphs.

include 'config.php'; // Include the database configuration file
include 'header.php'; // Include the header file for the page layout

// Get the Pupil ID from the URL and validate it
$pupilID = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;

if (!$pupilID) { // If no valid Pupil ID is provided, show an error and redirect
    echo "<script>alert('Invalid Pupil ID.'); window.location.href='view_pupils.php';</script>";
    exit;
}

// Fetch the pupil's current details from the database
$stmt = $conn->prepare("SELECT * FROM Pupils WHERE PupilID = ?");
$stmt->bind_param("i", $pupilID);
$stmt->execute();
$result = $stmt->get_result();
$pupil = $result->fetch_assoc();

if (!$pupil) { // If the pupil is not found, show an error and redirect
    echo "<script>alert('Pupil not found.'); window.location.href='view_pupils.php';</script>";
    exit;
}

// Fetch the list of available classes
$classQuery = "SELECT ClassID, ClassName FROM Classes";
$classResult = $conn->query($classQuery);

// Fetch the list of available guardians
$guardianQuery = "SELECT GuardianID, FirstName, Surname FROM Guardian";
$guardianResult = $conn->query($guardianQuery);

// Handle form submission to update the pupil's details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated details from the form
    $title = $_POST['title'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $dob = $_POST['date_of_birth'];
    $address1 = $_POST['address_line1'];
    $address2 = $_POST['address_line2'];
    $address3 = $_POST['address_line3'];
    $postcode = $_POST['postcode'];
    $medicalAllergy = $_POST['medical_allergy'];
    $medicalVaccination = $_POST['medical_vaccination'];
    $classID = $_POST['class_id'];
    $guardian1ID = $_POST['guardian1_id'];
    $guardian2ID = !empty($_POST['guardian2_id']) ? $_POST['guardian2_id'] : null; // Optional second guardian
    $notes = $_POST['notes'];

    // Prepare the SQL query to update the pupil's details
    $stmt = $conn->prepare("
        UPDATE Pupils SET
        Title = ?, FirstName = ?, Surname = ?, DOB = ?,
        Address_Line1 = ?, Address_Line2 = ?, Address_Line3 = ?, Postcode = ?,
        Medical_Allergy = ?, Medical_Vaccination = ?, ClassID = ?, Guardian1ID = ?, Guardian2ID = ?, Notes = ?
        WHERE PupilID = ?
    ");
    $stmt->bind_param(
        "ssssssssssiiisi",
        $title, $firstName, $lastName, $dob,
        $address1, $address2, $address3, $postcode,
        $medicalAllergy, $medicalVaccination, $classID, $guardian1ID, $guardian2ID, $notes, $pupilID
    );

    // Execute the query and check if the update was successful
    if ($stmt->execute()) {
        echo "<script>alert('Pupil updated successfully!'); window.location.href='view_pupils.php';</script>";
    } else {
        echo "<script>alert('Error updating pupil: " . $stmt->error . "');</script>";
    }

    $stmt->close(); // Close the prepared statement
}
?>

<div class="container mt-5">
    <h2>Edit Pupil</h2>
    <!-- Form to edit pupil details -->
    <form method="POST">
        <!-- Input for the pupil's title -->
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($pupil['Title']) ?>" required>
        </div>
        <!-- Input for the pupil's first name -->
        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($pupil['FirstName']) ?>" required>
        </div>
        <!-- Input for the pupil's last name -->
        <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($pupil['Surname']) ?>" required>
        </div>
        <!-- Input for the pupil's date of birth -->
        <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" class="form-control" name="date_of_birth" value="<?= htmlspecialchars($pupil['DOB']) ?>" required>
        </div>
        <!-- Input for the pupil's address -->
        <div class="mb-3">
            <label class="form-label">Address Line 1</label>
            <input type="text" class="form-control" name="address_line1" value="<?= htmlspecialchars($pupil['Address_Line1']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Address Line 2</label>
            <input type="text" class="form-control" name="address_line2" value="<?= htmlspecialchars($pupil['Address_Line2']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Address Line 3</label>
            <input type="text" class="form-control" name="address_line3" value="<?= htmlspecialchars($pupil['Address_Line3']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Postcode</label>
            <input type="text" class="form-control" name="postcode" value="<?= htmlspecialchars($pupil['Postcode']) ?>">
        </div>
        <!-- Input for medical details -->
        <div class="mb-3">
            <label class="form-label">Medical Allergy</label>
            <input type="text" class="form-control" name="medical_allergy" value="<?= htmlspecialchars($pupil['Medical_Allergy']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Medical Vaccination</label>
            <input type="text" class="form-control" name="medical_vaccination" value="<?= htmlspecialchars($pupil['Medical_Vaccination']) ?>">
        </div>
        <!-- Dropdown for selecting the class -->
        <div class="mb-3">
            <label class="form-label">Class</label>
            <select class="form-select" name="class_id" required>
                <option value="">Select Class</option>
                <?php while ($class = $classResult->fetch_assoc()): ?>
                    <option value="<?= $class['ClassID'] ?>" <?= $class['ClassID'] == $pupil['ClassID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($class['ClassName']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <!-- Dropdowns for selecting guardians -->
        <div class="mb-3">
            <label class="form-label">Guardian 1</label>
            <select class="form-select" name="guardian1_id" required>
                <option value="">Select Guardian</option>
                <?php mysqli_data_seek($guardianResult, 0); while ($guardian = $guardianResult->fetch_assoc()): ?>
                    <option value="<?= $guardian['GuardianID'] ?>" <?= $guardian['GuardianID'] == $pupil['Guardian1ID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($guardian['FirstName'] . ' ' . $guardian['Surname']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Guardian 2 (optional)</label>
            <select class="form-select" name="guardian2_id">
                <option value="">Select Guardian</option>
                <?php
                $guardianResult2 = $conn->query($guardianQuery);
                while ($guardian = $guardianResult2->fetch_assoc()): ?>
                    <option value="<?= $guardian['GuardianID'] ?>" <?= $guardian['GuardianID'] == $pupil['Guardian2ID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($guardian['FirstName'] . ' ' . $guardian['Surname']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <!-- Textarea for notes -->
        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea class="form-control" name="notes"><?= htmlspecialchars($pupil['Notes']) ?></textarea>
        </div>
        <!-- Submit and cancel buttons -->
        <button type="submit" class="btn btn-primary">Update Pupil</button>
        <a href="view_pupils.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php 
$conn->close(); // Close the database connection
include 'footer.php'; // Include the footer file
?>