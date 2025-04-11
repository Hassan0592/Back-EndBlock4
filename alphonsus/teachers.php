<?php
// This is all My code. Bootstrap has been used and Google Graphs.

include 'config.php'; // Include the database configuration file to establish a connection
include 'header.php'; // Include the header file for the page layout

// Fetch all teachers with their assigned class
$query = "
    SELECT 
        t.TeacherID, 
        t.Title, 
        t.FirstName, 
        t.Surname, 
        t.EmailAddress, 
        t.ContactNumber, 
        c.ClassName
    FROM Teachers t
    LEFT JOIN Classes c ON t.ClassID = c.ClassID
    ORDER BY t.Surname ASC
";
$result = $conn->query($query); // Execute the query and store the result
?>

<div class="container mt-5">
    <h2 class="mb-4">Teacher List</h2> <!-- Page heading -->

    <!-- Button to add a new teacher -->
    <div class="mb-3 text-end">
        <a href="add_teacher.php" class="btn custom-btn">Add New Teacher</a>
    </div>

    <!-- Table to display the list of teachers -->
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Name</th> <!-- Full name of the teacher -->
                <th>Email</th> <!-- Email address of the teacher -->
                <th>Contact</th> <!-- Contact number of the teacher -->
                <th>Assigned Class</th> <!-- Class assigned to the teacher -->
                <th>Actions</th> <!-- Edit and delete options -->
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): // Loop through each teacher record ?>
                <tr>
                    <td><?= htmlspecialchars($row['Title'] . ' ' . $row['FirstName'] . ' ' . $row['Surname']) ?></td> <!-- Display the teacher's full name -->
                    <td><?= htmlspecialchars($row['EmailAddress']) ?></td> <!-- Display the teacher's email address -->
                    <td><?= htmlspecialchars($row['ContactNumber']) ?></td> <!-- Display the teacher's contact number -->
                    <td><?= $row['ClassName'] ?? 'Unassigned' ?></td> <!-- Display the assigned class or 'Unassigned' if none -->
                    <td>
                        <!-- Button to edit the teacher's details -->
                        <a href="edit_teacher.php?id=<?= $row['TeacherID'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <!-- Button to delete the teacher with a confirmation prompt -->
                        <a href="delete_teacher.php?id=<?= $row['TeacherID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
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