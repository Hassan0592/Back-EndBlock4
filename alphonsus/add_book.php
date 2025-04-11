<?php
include 'config.php'; // Include the database configuration file This is all My code. bootstrap has been used and google graphs.
include 'header.php'; // Include the header file for the page layout

$success = ''; // Variable to store success messages
$error = ''; // Variable to store error messages

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookID = $_POST['book_id']; // Get the selected book ID from the form
    $bookedBy = empty($_POST['booked_by']) ? NULL : $_POST['booked_by']; // Get the pupil ID or set it to NULL if empty

    // Initialize a flag to validate the pupil ID
    $isValid = false;

    if ($bookedBy !== NULL) {
        // Check if the entered pupil ID exists in the Pupils table
        $checkPupil = $conn->prepare("SELECT PupilID FROM Pupils WHERE PupilID = ?");
        $checkPupil->bind_param("i", $bookedBy);
        $checkPupil->execute();
        $checkPupil->store_result();
        if ($checkPupil->num_rows > 0) {
            $isValid = true; // Mark as valid if the pupil exists
        }
        $checkPupil->close();
    }

    if ($isValid) {
        // Debugging: Log the bookedBy value in the browser console
        echo "<script>console.log('BookedBy: " . $bookedBy . "');</script>";

        // Update the book record to mark it as loaned out and assign it to the pupil
        $stmt = $conn->prepare("UPDATE Books SET LoanedOut = 'Y', BookedBy = ? WHERE BookID = ?");
        $stmt->bind_param("ii", $bookedBy, $bookID);

        if ($stmt->execute()) {
            $success = "Book successfully assigned!"; // Success message
        } else {
            $error = "Error: " . $stmt->error; // Display any SQL errors
        }
    } else {
        $error = "Invalid ID entered. Please enter a valid Pupil ID."; // Error message for invalid pupil ID
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Assign a Book</h2>

    <!-- Display success or error messages -->
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Form to assign a book to a pupil -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="book_id" class="form-label">Select a Book</label>
            <select class="form-select" name="book_id" id="book_id" required>
                <option value="">-- Choose a Book --</option>
                <?php
                // Fetch all books that are not currently loaned out
                $booksResult = $conn->query("SELECT BookID, BookTitle FROM Books WHERE LoanedOut = 'N'");
                while ($book = $booksResult->fetch_assoc()) {
                    echo "<option value='{$book['BookID']}'>{$book['BookTitle']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="booked_by" class="form-label">Enter Pupil</label>
            <input type="number" class="form-control" name="booked_by" id="booked_by" required>
        </div>

        <!-- Submit button to assign the book -->
        <button type="submit" class="btn custom-btn">Assign Book</button>
        <!-- Back button to return to the books page -->
        <a href="books.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<?php include 'footer.php'; // Include the footer file ?>
