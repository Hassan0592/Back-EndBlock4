<?php
// This is all My code. Bootstrap has been used and Google Graphs.

include 'config.php'; // Include the database configuration file to establish a connection
include 'header.php'; // Include the header file for the page layout

// Fetch all classes along with the current pupil count for each class
$query = "
    SELECT 
        c.ClassID, 
        c.ClassName, 
        c.ClassCapacity, 
        (SELECT COUNT(*) FROM Pupils p WHERE p.ClassID = c.ClassID) AS pupil_count
    FROM Classes c
    ORDER BY c.ClassName ASC
";
$result = $conn->query($query); // Execute the query and store the result
?>

<div class="container mt-5">
    <h2 class="mb-4">Class List</h2> <!-- Page heading -->

    <!-- Button to add a new class -->
    <div class="mb-3 text-end">
        <a href="add_class.php" class="btn btn-success">Add New Class</a>
    </div>

    <!-- Table to display the list of classes -->
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Class Name</th> <!-- Name of the class -->
                <th>Capacity</th> <!-- Maximum capacity of the class -->
                <th>Current Pupils</th> <!-- Number of pupils currently in the class -->
                <th>Actions</th> <!-- Options to manage the class -->
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): // Loop through each class record ?>
                <tr>
                    <td><?= htmlspecialchars($row['ClassName']) ?></td> <!-- Display the class name -->
                    <td><?= $row['ClassCapacity'] ?></td> <!-- Display the class capacity -->
                    <td><?= $row['pupil_count'] ?> / <?= $row['ClassCapacity'] ?></td> <!-- Display the current pupil count and capacity -->
                    <td>
                        <!-- Button to view pupils in the class -->
                        <a href="pupils.php?class_id=<?= $row['ClassID'] ?>" class="btn btn-custom btn-sm">View Pupils</a>
                        <!-- Button to update attendance records for the class -->
                        <a href="Edit_Attendance.php?class_id=<?= $row['ClassID'] ?>" class="btn btn-custom btn-sm">Update Attendance Record</a>
                        <!-- Button to view attendance reports for the class -->
                        <a href="View_Attendance.php?class_id=<?= $row['ClassID'] ?>" class="btn btn-custom btn-sm">View Attendance Report</a>
                        <!-- Button to edit the class details -->
                        <a href="edit_class.php?id=<?= $row['ClassID'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <!-- Button to delete the class with a confirmation prompt -->
                        <a href="delete_class.php?id=<?= $row['ClassID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; // End of the loop ?>
        </tbody>
    </table>
</div>

<?php
$conn->close(); // Close the database connection
include 'footer.php'; // Include the footer file for the page
?>