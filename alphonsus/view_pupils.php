<?php
// This is all My code. Bootstrap has been used and Google Graphs.

include 'config.php'; // Include the database configuration file to establish a connection
include 'header.php'; // Include the header file for the page layout

// Handle search input
$search = isset($_GET['search']) ? trim($_GET['search']) : ''; // Get the search term from the URL and trim any extra spaces

// Fetch all pupils with class and guardian information, filtered by search input
$query = "
    SELECT 
        p.*, 
        c.ClassName, 
        g1.FirstName AS Guardian1FirstName, 
        g1.Surname AS Guardian1Surname, 
        g2.FirstName AS Guardian2FirstName, 
        g2.Surname AS Guardian2Surname
    FROM Pupils p
    LEFT JOIN Classes c ON p.ClassID = c.ClassID
    LEFT JOIN Guardian g1 ON p.Guardian1ID = g1.GuardianID
    LEFT JOIN Guardian g2 ON p.Guardian2ID = g2.GuardianID
    WHERE p.FirstName LIKE ? OR p.Surname LIKE ? OR CONCAT(p.FirstName, ' ', p.Surname) LIKE ?
    ORDER BY p.Surname ASC
";
$stmt = $conn->prepare($query); // Prepare the query to prevent SQL injection
$searchTerm = "%$search%"; // Add wildcards to the search term for partial matching
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm); // Bind the search term to the query
$stmt->execute(); // Execute the query
$result = $stmt->get_result(); // Get the result set
?>

<div class="container mt-5">
    <h2 class="mb-4">Pupil List</h2> <!-- Page heading -->

    <!-- Search Bar -->
    <form method="GET" action="" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by name..." value="<?= htmlspecialchars($search) ?>"> <!-- Input field for search -->
            <button type="submit" class="btn btn-primary">Search</button> <!-- Button to submit the search form -->
        </div>
    </form>

    <!-- Add New Pupil Button -->
    <div class="mb-3 text-end">
        <a href="add_pupil.php" class="btn btn-success">Add New Pupil</a> <!-- Button to add a new pupil -->
    </div>

    <?php if ($result->num_rows > 0): // Check if there are any pupils in the database ?>
        <div class="table-responsive">
            <!-- Table to display the list of pupils -->
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Pupil ID</th> <!-- Unique ID of the pupil -->
                        <th>Name</th> <!-- Full name of the pupil -->
                        <th>Date of Birth</th> <!-- Pupil's date of birth -->
                        <th>Class</th> <!-- Class the pupil is assigned to -->
                        <th>Guardian 1</th> <!-- First guardian's name -->
                        <th>Guardian 2</th> <!-- Second guardian's name -->
                        <th>Medical Info</th> <!-- Medical details of the pupil -->
                        <th>Actions</th> <!-- Edit and delete options -->
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): // Loop through each pupil record ?>
                        <tr>
                            <td><?= $row['PupilID'] ?></td> <!-- Display the pupil's ID -->
                            <td>
                                <!-- Link to view detailed information about the pupil -->
                                <a href="view_pupil_details.php?id=<?= $row['PupilID'] ?>">
                                    <?= $row['Title'] ?> <?= $row['FirstName'] ?> <?= $row['Surname'] ?>
                                </a>
                            </td>
                            <td><?= date('d/m/Y', strtotime($row['DOB'])) ?></td> <!-- Format and display the date of birth -->
                            <td><?= $row['ClassName'] ?? 'N/A' ?></td> <!-- Display the class name or 'N/A' if not assigned -->
                            <td>
                                <?= $row['Guardian1FirstName'] ? $row['Guardian1FirstName'] . ' ' . $row['Guardian1Surname'] : 'N/A' ?> <!-- Display the first guardian's name or 'N/A' -->
                            </td>
                            <td>
                                <?= $row['Guardian2FirstName'] ? $row['Guardian2FirstName'] . ' ' . $row['Guardian2Surname'] : 'N/A' ?> <!-- Display the second guardian's name or 'N/A' -->
                            </td>
                            <td>
                                <?php if ($row['Medical_Allergy'] || $row['Medical_Vaccination']): ?>
                                    <!-- Button to view medical details in a modal -->
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                        data-bs-target="#medicalModal<?= $row['PupilID'] ?>">
                                        View Details
                                    </button>
                                <?php else: ?>
                                    None recorded <!-- Display 'None recorded' if no medical info is available -->
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Button to edit the pupil's details -->
                                <a href="edit_pupil.php?id=<?= $row['PupilID'] ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <!-- Button to delete the pupil with a confirmation prompt -->
                                <a href="delete_pupil.php?id=<?= $row['PupilID'] ?>" class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Are you sure you want to delete this pupil?')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </td>
                        </tr>

                        <!-- Medical Info Modal -->
                        <div class="modal fade" id="medicalModal<?= $row['PupilID'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Medical Information</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6>Allergies:</h6>
                                        <p><?= $row['Medical_Allergy'] ?: 'None recorded' ?></p>
                                        <h6>Vaccinations:</h6>
                                        <p><?= $row['Medical_Vaccination'] ?: 'None recorded' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No pupils found matching your search.</div>
    <?php endif; ?>
</div>

<?php 
$stmt->close();
$conn->close();
include 'footer.php'; 
?>