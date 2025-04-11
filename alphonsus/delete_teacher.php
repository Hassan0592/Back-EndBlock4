<?php
include 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $teacherID = $_GET['id'];

    // Delete teacher
    $stmt = $conn->prepare("DELETE FROM Teachers WHERE TeacherID = ?");
    $stmt->bind_param("i", $teacherID);

    if ($stmt->execute()) {
        echo "<script>alert('Teacher deleted successfully.'); window.location.href='teachers.php';</script>";
    } else {
        echo "<script>alert('Error deleting teacher.'); window.location.href='teachers.php';</script>";
    }
} else {
    // If ID not provided or invalid
    echo "<script>alert('Invalid request.'); window.location.href='teachers.php';</script>";
}
?>