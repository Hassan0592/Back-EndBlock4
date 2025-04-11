<?php
// This is all My code. Bootstrap has been used and Google Graphs.

// Include the database configuration and header files
include 'config.php';
include 'header.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $pupilID = $_POST['pupil_id']; // Pupil ID entered by the user
    $classID = $_POST['class_id']; // Class ID entered by the user
    $attendanceDate = $_POST['attendance_date']; // Date of attendance
    $status = $_POST['status']; // Attendance status (Present/Absent)
    $notes = $_POST['notes']; // Optional notes about the attendance

    // Validate if the Pupil ID exists in the Pupils table
    $checkPupil = $conn->prepare("SELECT PupilID FROM Pupils WHERE PupilID = ?");
    $checkPupil->bind_param("i", $pupilID);
    $checkPupil->execute();
    $pupilResult = $checkPupil->get_result();

    // Validate if the Class ID exists in the Classes table
    $checkClass = $conn->prepare("SELECT ClassID FROM Classes WHERE ClassID = ?");
    $checkClass->bind_param("i", $classID);
    $checkClass->execute();
    $classResult = $checkClass->get_result();

    // If both Pupil ID and Class ID are valid
    if ($pupilResult->num_rows > 0 && $classResult->num_rows > 0) {
        // Insert the attendance record into the Attendance table
        $query = "INSERT INTO Attendance (PupilID, ClassID, AttendanceDate, Status, Notes) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iisss", $pupilID, $classID, $attendanceDate, $status, $notes);

        // Check if the query executed successfully
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Attendance recorded successfully!</div>";
        } else {
            // Display an error message if the query fails
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
    } else {
        // Display a warning if the Pupil ID or Class ID is invalid
        echo "<div class='alert alert-warning'>Invalid Pupil or Class ID!</div>";
    }
}
?>

<div class="container mt-5">
    <h2>Manage Attendance</h2>

    <!-- Attendance Form -->
    <form method="POST" action="">
        <!-- Input for Pupil ID -->
        <div class="mb-3">
            <label>Pupil ID</label>
            <input type="number" class="form-control" name="pupil_id" required>
        </div>
        <!-- Input for Class ID -->
        <div class="mb-3">
            <label>Class ID</label>
            <input type="number" class="form-control" name="class_id" required>
        </div>
        <!-- Input for Attendance Date -->
        <div class="mb-3">
            <label>Attendance Date</label>
            <input type="date" class="form-control" name="attendance_date" required>
        </div>
        <!-- Dropdown for Attendance Status -->
        <div class="mb-3">
            <label>Status</label>
            <select class="form-control" name="status" required>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
            </select>
        </div>
        <!-- Textarea for Notes -->
        <div class="mb-3">
            <label>Notes</label>
            <textarea class="form-control" name="notes"></textarea>
        </div>
        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Record Attendance</button>
    </form>

    <hr>

    <!-- Display Attendance Records -->
    <h3>Attendance Records</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Pupil ID</th>
                <th>Class ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Fetch all attendance records from the Attendance table
        $result = $conn->query("SELECT * FROM Attendance");

        // Loop through each record and display it in the table
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['PupilID']}</td>
                <td>{$row['ClassID']}</td>
                <td>{$row['AttendanceDate']}</td>
                <td>{$row['Status']}</td>
                <td>{$row['Notes']}</td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>