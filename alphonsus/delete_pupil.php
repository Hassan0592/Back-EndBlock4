<?php
include 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $pupilID = $_GET['id'];

    // Prepare the SQL query to delete the pupil
    $stmt = $conn->prepare("DELETE FROM Pupils WHERE PupilID = ?");
    $stmt->bind_param("i", $pupilID);

    // Execute the query and handle the result
    if ($stmt->execute()) {
        echo "<script>alert('Pupil deleted successfully.'); window.location.href='view_pupils.php';</script>";
    } else {
        echo "<script>alert('Error deleting pupil: " . $stmt->error . "'); window.location.href='view_pupils.php';</script>";
    }

    $stmt->close();
} else {
    // If no valid ID is provided
    echo "<script>alert('Invalid request.'); window.location.href='view_pupils.php';</script>";
}

$conn->close();
?>