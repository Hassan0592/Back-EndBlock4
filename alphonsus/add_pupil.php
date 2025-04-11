<?php 
include 'config.php'; // Include the database configuration file This is all My code. bootstrap has been used and google graphs.
include 'header.php'; // Include the header file for the page layout

// Fetch classes with pupil count and capacity
$classQuery = "
    SELECT c.ClassID, c.ClassName, c.ClassCapacity, 
           COUNT(p.PupilID) AS CurrentCount
    FROM Classes c
    LEFT JOIN Pupils p ON c.ClassID = p.ClassID
    GROUP BY c.ClassID
";
$classResult = $conn->query($classQuery); // Execute the query to fetch class details

// Fetch guardians
$guardianQuery = "SELECT GuardianID, FirstName, Surname FROM Guardian";
$guardianResult = $conn->query($guardianQuery); // Execute the query to fetch guardian details
?>

<div class="container mt-5">
    <h2 class="mb-4">Add New Pupil</h2>

    <!-- Button to navigate back to the list of pupils -->
    <div class="mb-3 text-end">
        <a href="view_pupils.php" class="btn btn-primary">View Pupils</a>
    </div>

    <!-- Form to add a new pupil -->
    <form method="POST" action="save_pupil.php">
        <!-- Input for the pupil's title -->
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" name="title" required>
        </div>

        <!-- Input for the pupil's first name -->
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" name="first_name" required>
        </div>

        <!-- Input for the pupil's last name -->
        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" name="last_name" required>
        </div>

        <!-- Input for the pupil's date of birth -->
        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" name="date_of_birth" required 
                   min="<?= date('Y-m-d', strtotime('-11 years')) ?>" 
                   max="<?= date('Y-m-d', strtotime('-4 years')) ?>">
            <div class="form-text">Pupil must be between 4 and 11 years old.</div>
        </div>

        <!-- Input for the pupil's address -->
        <div class="mb-3">
            <label for="address_line1" class="form-label">Address Line 1</label>
            <input type="text" class="form-control" name="address_line1">
        </div>
        <div class="mb-3">
            <label for="address_line2" class="form-label">Address Line 2</label>
            <input type="text" class="form-control" name="address_line2">
        </div>
        <div class="mb-3">
            <label for="address_line3" class="form-label">Address Line 3</label>
            <input type="text" class="form-control" name="address_line3">
        </div>
        <div class="mb-3">
            <label for="postcode" class="form-label">Postcode</label>
            <input type="text" class="form-control" name="postcode">
        </div>

        <!-- Input for medical information -->
        <div class="mb-3">
            <label for="medical_allergy" class="form-label">Medical Allergy</label>
            <input type="text" class="form-control" name="medical_allergy">
        </div>
        <div class="mb-3">
            <label for="medical_vaccination" class="form-label">Medical Vaccination</label>
            <input type="text" class="form-control" name="medical_vaccination">
        </div>

        <!-- Dropdown to select the class -->
        <div class="mb-3">
            <label for="class_id" class="form-label">Class</label>
            <select class="form-select" name="class_id" required>
                <option value="">Select Class</option>
                <?php while($class = $classResult->fetch_assoc()): 
                    $label = $class['ClassName'] . " ({$class['CurrentCount']}/{$class['ClassCapacity']})";
                    $disabled = ($class['CurrentCount'] >= $class['ClassCapacity']) ? 'disabled' : '';
                ?>
                    <option value="<?= $class['ClassID'] ?>" <?= $disabled ?>><?= $label ?></option>
                <?php endwhile; ?>
            </select>
            <div class="form-text">Only classes with available space are selectable.</div>
        </div>

        <!-- Dropdown to select the first guardian -->
        <div class="mb-3">
            <label for="guardian1_id" class="form-label">Guardian 1</label>
            <select class="form-select" name="guardian1_id" required>
                <option value="">Select Guardian</option>
                <?php while($guardian = $guardianResult->fetch_assoc()): ?>
                    <option value="<?= $guardian['GuardianID'] ?>">
                        <?= $guardian['FirstName'] . ' ' . $guardian['Surname'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Dropdown to select the second guardian (optional) -->
        <div class="mb-3">
            <label for="guardian2_id" class="form-label">Guardian 2 (optional)</label>
            <select class="form-select" name="guardian2_id">
                <option value="">Select Guardian</option>
                <?php
                // Re-run guardian query for second dropdown
                $guardianResult2 = $conn->query($guardianQuery);
                while($guardian = $guardianResult2->fetch_assoc()): ?>
                    <option value="<?= $guardian['GuardianID'] ?>">
                        <?= $guardian['FirstName'] . ' ' . $guardian['Surname'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Textarea for additional notes -->
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" name="notes"></textarea>
        </div>

        <!-- Submit button to add the pupil -->
        <button type="submit" class="btn btn-success">Add Pupil</button>
    </form>
</div>

<?php include 'footer.php'; // Include the footer file ?>
