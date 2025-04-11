<?php
// This is all My code. Bootstrap has been used and Google Graphs.

// Include the database configuration and header files
include 'config.php';
include 'header.php';

// Function to get the name of a pupil by their ID
function getNameById($conn, $id) {
    // Query the Pupils table to fetch the first name and surname
    $stmt = $conn->prepare("SELECT FirstName, Surname FROM Pupils WHERE PupilID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($fname, $sname);
    if ($stmt->fetch()) {
        $stmt->close();
        return "Pupil: $fname $sname"; // Return the pupil's full name
    }
    $stmt->close();

    return "Unknown"; // Return "Unknown" if no pupil is found
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle returning a book
    if (isset($_POST['return_book'])) {
        $bookID = $_POST['book_id']; // Get the book ID from the form
        $query = "UPDATE Books SET LoanedOut = 'N', BookedBy = NULL WHERE BookID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $bookID);
        $stmt->execute();
        echo "<div class='alert alert-success'>Book returned successfully.</div>";
    } 
    // Handle updating a book's loan status
    elseif (isset($_POST['update_book'])) {
        $bookID = $_POST['book_id']; // Get the book ID from the form
        $loanedOut = $_POST['loaned_out']; // Get the loaned-out status
        $bookedBy = empty($_POST['booked_by']) ? NULL : $_POST['booked_by']; // Get the pupil ID or set it to NULL

        // Check if the book is already loaned out
        $loanCheck = $conn->prepare("SELECT LoanedOut FROM Books WHERE BookID = ?");
        $loanCheck->bind_param("i", $bookID);
        $loanCheck->execute();
        $loanCheck->bind_result($currentLoanedOut);
        $loanCheck->fetch();
        $loanCheck->close();

        if ($loanedOut == 'Y' && $currentLoanedOut == 'Y') {
            // Display a warning if the book is already loaned out
            echo "<div class='alert alert-warning'>This book is already loaned out.</div>";
        } else {
            // Validate the pupil ID if provided
            $isValid = true;
            if ($bookedBy !== NULL) {
                $isValid = false;

                // Check if the pupil ID exists in the Pupils table
                $checkPupil = $conn->prepare("SELECT PupilID FROM Pupils WHERE PupilID = ?");
                $checkPupil->bind_param("i", $bookedBy);
                $checkPupil->execute();
                $checkPupil->store_result();
                if ($checkPupil->num_rows > 0) {
                    $isValid = true; // Mark as valid if the pupil exists
                }
                $checkPupil->close();
            }

            if ($isValid || $bookedBy === NULL) {
                // Update the book's loan status and booked-by details
                $query = "UPDATE Books SET LoanedOut = ?, BookedBy = ? WHERE BookID = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sii", $loanedOut, $bookedBy, $bookID);
                $stmt->execute();
                echo "<div class='alert alert-success'>Book updated successfully.</div>";
            } else {
                // Display an error if the pupil ID is invalid
                echo "<div class='alert alert-danger'>Invalid Booked By ID. Must be an existing Pupil.</div>";
            }
        }
    }
}
?>

<div class="container mt-5">
    <h2>Manage Books</h2>
    <!-- Button to assign a new book -->
    <div class="mb-3 text-end">
        <a href="add_book.php" class="btn custom-btn">Assign New Book</a>
    </div>

    <!-- Table to display books -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Category</th>
                <th>Title</th>
                <th>Loaned Out</th>
                <th>Booked By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Fetch all books from the Books table
        $result = $conn->query("SELECT * FROM Books");

        // Loop through each book and display its details
        while ($row = $result->fetch_assoc()) {
            $bookedByText = $row['BookedBy'] ? "{$row['BookedBy']}<br><small>" . getNameById($conn, $row['BookedBy']) . "</small>" : "-";

            echo "<tr>
                <td>{$row['BookID']}</td>
                <td>{$row['BookCategory']}</td>
                <td>{$row['BookTitle']}</td>
                <td>{$row['LoanedOut']}</td>
                <td>$bookedByText</td>
                <td>";

            // If the book is loaned out, display a return button
            if ($row['LoanedOut'] === 'Y') {
                echo "
                    <form method='POST' action='' style='display:inline-block;'>
                        <input type='hidden' name='book_id' value='{$row['BookID']}'>
                        <button type='submit' name='return_book' class='btn btn-success btn-sm'>Return</button>
                    </form>
                ";
            }

            // Display a form to update the book's loan status and booked-by details
            echo "
                <form method='POST' action='' class='mt-2'>
                    <input type='hidden' name='book_id' value='{$row['BookID']}'>
                    <select name='loaned_out' class='form-select form-select-sm mb-1'>
                        <option value='Y' " . ($row['LoanedOut'] == 'Y' ? 'selected' : '') . ">Yes</option>
                        <option value='N' " . ($row['LoanedOut'] == 'N' ? 'selected' : '') . ">No</option>
                    </select>
                    <input type='number' name='booked_by' class='form-control form-control-sm mb-1' placeholder='Enter Pupil ID' value='{$row['BookedBy']}'>
                    <button type='submit' name='update_book' class='btn btn-primary btn-sm'>Update</button>
                </form>";

            echo "</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>