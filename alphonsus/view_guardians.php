<?php
// This is all My code. Bootstrap has been used and Google Graphs.

include 'config.php'; // Include the database configuration file to establish a connection
include 'header.php'; // Include the header file for the page layout

// Search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : ''; // Get the search term from the URL and sanitize it
$query = "SELECT * FROM Guardian WHERE FirstName LIKE '%$search%' OR Surname LIKE '%$search%' OR GuardianType LIKE '%$search%'"; // Query to search guardians by first name, surname, or type
$result = $conn->query($query); // Execute the query and store the result
?>

<div class="container mt-5">
    <h2 class="mb-4">All Guardians</h2> <!-- Page heading -->

    <!-- Search Form -->
    <form method="GET" action="" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search guardians..." value="<?= htmlspecialchars($search) ?>"> <!-- Input field for search -->
        <button type="submit" class="btn btn-primary">Search</button> <!-- Button to submit the search form -->
    </form>

    <!-- Table to display the list of guardians -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th> <!-- Guardian ID -->
                <th>Type</th> <!-- Type of guardian (e.g., Mother, Father) -->
                <th>Title</th> <!-- Title of the guardian (e.g., Mr., Mrs.) -->
                <th>First Name</th> <!-- First name of the guardian -->
                <th>Surname</th> <!-- Surname of the guardian -->
                <th>Contact</th> <!-- Contact number of the guardian -->
                <th>Email</th> <!-- Email address of the guardian -->
                <th>Actions</th> <!-- Options to edit or delete the guardian -->
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): // Loop through each guardian record ?>
                <tr>
                    <td><?= $row['GuardianID'] ?></td> <!-- Display the guardian's ID -->
                    <td><?= $row['GuardianType'] ?></td> <!-- Display the guardian's type -->
                    <td><?= $row['Title'] ?></td> <!-- Display the guardian's title -->
                    <td><?= $row['FirstName'] ?></td> <!-- Display the guardian's first name -->
                    <td><?= $row['Surname'] ?></td> <!-- Display the guardian's surname -->
                    <td><?= $row['ContactNumber'] ?></td> <!-- Display the guardian's contact number -->
                    <td><?= $row['EmailAddress'] ?></td> <!-- Display the guardian's email address -->
                    <td>
                        <!-- Button to edit the guardian's details -->
                        <a href="edit_guardian.php?id=<?= $row['GuardianID'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <!-- Button to delete the guardian with a confirmation prompt -->
                        <a href="delete_guardian.php?id=<?= $row['GuardianID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this guardian?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; // End of the loop ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; // Include the footer file for the page ?>