<?php
// This is all My code. Bootstrap has been used and Google Graphs.

include 'config.php'; // Include the database configuration file to establish a connection
include 'header.php'; // Include the header file for the page layout

$currentDate = date('Y-m-d'); // Get the current date
$classID = $_GET['class_id']; // Get the class ID from the URL to filter attendance by class

?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> <!-- Load Google Charts library -->
<script type="text/javascript">
    google.charts.load("current", { packages: ["corechart"] }); // Load the corechart package for pie charts

    // Function to draw a pie chart for attendance
    function drawChart(rowId, present, absent) {
        var data = google.visualization.arrayToDataTable([
            ['Category', 'Percentage'], // Define the chart categories
            ['Present', present], // Percentage of days present
            ['Absent', absent] // Percentage of days absent
        ]);

        var options = {
          title: '', // No title for the chart
          is3D: true // Enable 3D effect for the pie chart
        };

        var chart = new google.visualization.PieChart(document.getElementById(rowId)); // Create the chart in the specified element
        chart.draw(data, options); // Draw the chart with the data and options
    }
</script>

<div class="container mt-5">
    <h2>Attendance Overview</h2> <!-- Page heading -->
    <hr>
    <h3>Attendance Report</h3> <!-- Subheading for the report -->
    <table id="attendtable" class="table table-bordered">
        <thead>
            <tr>
                <th>Pupil ID</th> <!-- Unique ID of the pupil -->
                <th>First Name</th> <!-- First name of the pupil -->
                <th>Surname</th> <!-- Surname of the pupil -->
                <th>Days Present</th> <!-- Total days the pupil was present -->
                <th>Days Absent</th> <!-- Total days the pupil was absent -->
                <th>Percentage</th> <!-- Pie chart showing attendance percentage -->
            </tr>
        </thead>
        <tbody>
        <?php
        // Query to calculate attendance statistics for each pupil in the selected class
        $selcstmt = "SELECT p.PupilID, p.Firstname, p.Surname, 
                        SUM(CASE WHEN a.Status = 'Present' THEN 1 ELSE 0 END) AS DaysPresent, 
                        SUM(CASE WHEN a.Status = 'Absent' THEN 1 ELSE 0 END) AS DaysAbsent,
                        SUM(CASE WHEN a.Status = 'Present' THEN 1 ELSE 0 END) * 100.0 / COUNT(*) AS PresentPercentage, 
                        SUM(CASE WHEN a.Status = 'Absent' THEN 1 ELSE 0 END) * 100.0 / COUNT(*) AS AbsentPercentage 
                    FROM Attendance a 
                    JOIN Pupils p ON a.PupilID = p.PupilID 
                    WHERE p.classID = ? 
                    GROUP BY p.PupilID, p.Firstname, p.Surname";

        $result = $conn->prepare($selcstmt); // Prepare the query to prevent SQL injection
        $result->bind_param("i", $classID); // Bind the class ID parameter for security
        $result->execute(); // Execute the query
        $AttendanceResult = $result->get_result(); // Get the result set

        $index = 0; // Initialize index for unique chart IDs

        // Loop through each pupil's attendance record
        while ($row = $AttendanceResult->fetch_assoc()) {
            echo "<tr>
                <td>{$row['PupilID']}</td> <!-- Display the pupil's ID -->
                <td>{$row['Firstname']}</td> <!-- Display the pupil's first name -->
                <td>{$row['Surname']}</td> <!-- Display the pupil's surname -->
                <td>{$row['DaysPresent']}</td> <!-- Display the total days present -->
                <td>{$row['DaysAbsent']}</td> <!-- Display the total days absent -->
                <td id=\"chart-$index\" style=\"width: 400px; height: 200px;\"> <!-- Placeholder for the pie chart -->
                    <script type=\"text/javascript\">
                        google.charts.setOnLoadCallback(() => drawChart('chart-$index', {$row['PresentPercentage']}, {$row['AbsentPercentage']})); // Draw the chart for this pupil
                    </script>
                </td>
            </tr>";
            $index++; // Increment index for the next chart
        }
        ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; // Include the footer file for the page ?>