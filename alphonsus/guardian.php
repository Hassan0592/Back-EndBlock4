<?php
// This is all My code. Bootstrap has been used and Google Graphs.

include 'config.php'; // Include the database configuration file to establish a connection
include 'header.php'; // Include the header file for the page layout

// Query to fetch all guardians from the database
$sql = "SELECT * FROM Guardian";
$result = $conn->query($sql); // Execute the query and store the result
?>

<div class="container mt-5">
    <h2>List of Guardians</h2>
    <!-- Button to add a new guardian -->
    <a href="add_guardian.php" class="btn custom-btn mb-3">+ Add New Guardian</a>

    <?php if ($result->num_rows > 0): // Check if there are any guardians in the database ?>
    <div class="table-responsive">
        <!-- Table to display the list of guardians -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th> <!-- Guardian ID -->
                    <th>Guardian Type</th> <!-- Type of guardian (e.g., Mother, Father) -->
                    <th>Title</th> <!-- Title of the guardian (e.g., Mr., Mrs.) -->
                    <th>Full Name</th> <!-- Full name of the guardian -->
                    <th>Address</th> <!-- Address of the guardian -->
                    <th>Postcode</th> <!-- Postcode of the guardian -->
                    <th>Contact</th> <!-- Contact number of the guardian -->
                    <th>Email</th> <!-- Email address of the guardian -->
                    <th>Notes</th> <!-- Additional notes about the guardian -->
                    <th>Actions</th> <!-- Edit and delete actions -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): // Loop through each guardian record ?>
                <tr>
                    <td><?= $row['GuardianID'] ?></td> <!-- Display the Guardian ID -->
                    <td><?= htmlspecialchars($row['GuardianType']) ?></td> <!-- Display the guardian type -->
                    <td><?= htmlspecialchars($row['Title']) ?></td> <!-- Display the guardian's title -->
                    <td><?= htmlspecialchars($row['FirstName'] . " " . $row['Surname']) ?></td> <!-- Display the full name -->
                    <td>
                        <!-- Display the guardian's address -->
                        <?= htmlspecialchars($row['Address_Line1']) ?><br>
                        <?= htmlspecialchars($row['Address_Line2']) ?><br>
                        <?= htmlspecialchars($row['Address_Line3']) ?>
                    </td>
                    <td><?= htmlspecialchars($row['Postcode']) ?></td> <!-- Display the postcode -->
                    <td><?= htmlspecialchars($row['ContactNumber']) ?></td> <!-- Display the contact number -->
                    <td><?= htmlspecialchars($row['EmailAddress']) ?></td> <!-- Display the email address -->
                    <td><?= nl2br(htmlspecialchars($row['Notes'])) ?></td> <!-- Display the notes -->
                    <td>
                        <!-- Button to edit the guardian -->
                        <a href="edit_guardian.php?id=<?= $row['GuardianID'] ?>" 
                           class="btn btn-sm btn-warning">Edit</a>
                        <!-- Button to delete the guardian with a confirmation prompt -->
                        <a href="delete_guardian.php?id=<?= $row['GuardianID'] ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Are you sure you want to delete this guardian?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; // End of the loop ?>
            </tbody>
        </table>
    </div>
    <?php else: // If no guardians are found, display a message ?>
        <div class="alert alert-info">No guardians found.</div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; // Include the footer file ?>