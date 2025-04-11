<?php
// This is all My code. Bootstrap has been used and Google Graphs.

include 'config.php'; // Include the database configuration file
include 'header.php'; // Include the header file for the page layout

// Get the current date
$currentDate = date('Y-m-d');

// Get the class ID from the URL
$classID = $_GET['class_id'];

// Check if the form was submitted with SQL data
if (isset($_POST['resultsql'])) {
    $resultsql = $_POST['resultsql']; // Retrieve the SQL query from the POST request
    echo "Received: " . $resultsql; // Debugging: Display the received SQL query
    $Insresult = $conn->prepare("$resultsql"); // Prepare the SQL query

    $Insresult->execute(); // Execute the SQL query
    header("Location: index.php"); // Redirect to the index page after execution
    exit(); // Ensure no further code is executed
} else {
    // If no SQL data is submitted, do nothing
}
?>

<div class="container mt-5">
    <h2>Manage Attendance</h2>
    <hr>

    <!-- Display Attendance Records -->
    <h3>Attendance Records</h3>
    <table id="attendtable" class="table table-bordered">
        <thead>
            <tr>
                <th>Pupil ID</th>
                <th>Class ID</th>
                <th>Student name</th>
                <th>Date</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Query to fetch pupils in the selected class
        $selcstmt = "SELECT ClassID, PupilID, Title, FirstName, Surname FROM pupils WHERE ClassID = '".$classID."'";
        $result = $conn->prepare($selcstmt); // Prepare the query
        $result->execute(); // Execute the query
        $AttendanceResult = $result->get_result(); // Get the result set

        // Loop through each pupil and display their attendance details
        while ($row = $AttendanceResult->fetch_assoc()) {
            echo "<tr>
                <td>{$row['PupilID']}</td>
                <td>{$row['ClassID']}</td>
                <td>{$row['Title']} {$row['FirstName']} {$row['Surname']}</td>
                <td>{$currentDate}</td>
                <td><select><option value='Present'>Present</option><option value='Absent'>Absent</option></select></td>
                <td><textarea name='notes' rows='1' cols='60'></textarea></td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script>
// Function to generate and execute the SQL query for saving attendance
function generateAndExecuteSQL() {
    const table = document.getElementById('attendtable'); // Get the attendance table
    const rows = table.getElementsByTagName('tr'); // Get all rows in the table
    let datarow = rows.length - 1; // Calculate the number of data rows
    const sqlStatements = [];
    let string1 = "INSERT INTO attendance (PupilID, ClassID, AttendanceDate, Status, Notes) VALUES ";
    let string2 = ""; 

    // Loop through each row to build the SQL query
    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
        const cells = rows[i].getElementsByTagName('td'); // Get all cells in the row
        const PupilID = cells[0].innerText; // Get the Pupil ID
        const ClassID = cells[1].innerText; // Get the Class ID
        const name = cells[2].innerText; // Get the student's name
        const att_date = cells[3].innerText; // Get the attendance date
        const att_status = cells[4].querySelector('select').value; // Get the attendance status
        const att_notes = cells[5].innerText; // Get the notes

        // Build the SQL query string
        if (i < datarow) {
            string2 = string2.concat(`('${PupilID}', '${ClassID}', '${att_date}', '${att_status}', '${att_notes}'),`);
        } else {
            string2 = string2.concat(`('${PupilID}', '${ClassID}', '${att_date}', '${att_status}', '${att_notes}');`);
        }
    }

    let resultsql = string1.concat(string2); // Combine the query parts
    console.log(`${resultsql}`); // Debugging: Log the generated SQL query

    // Send the SQL query to the server using a POST request
    fetch('Edit_Attendance.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'resultsql=' + encodeURIComponent(resultsql),
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Debugging: Log the server response
        alert('Class Attendance updated successfully!'); // Notify the user
    })
    .catch(error => console.error('Error:', error)); // Handle errors
}
</script>

<!-- Button to save attendance -->
<button onclick="generateAndExecuteSQL()">Save Attendance</button>

<?php 
include 'footer.php'; // Include the footer file
?>


