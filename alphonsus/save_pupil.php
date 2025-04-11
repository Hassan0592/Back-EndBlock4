<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $title = $_POST['title'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $dob = $_POST['date_of_birth'];
    $address1 = $_POST['address_line1'];
    $address2 = $_POST['address_line2'];
    $address3 = $_POST['address_line3'];
    $postcode = $_POST['postcode'];
    $medicalAllergy = $_POST['medical_allergy'];
    $medicalVaccination = $_POST['medical_vaccination'];
    $classID = $_POST['class_id'];
    $guardian1ID = $_POST['guardian1_id'];
    $guardian2ID = !empty($_POST['guardian2_id']) ? $_POST['guardian2_id'] : null;
    $notes = $_POST['notes'];

    // ✅ Check if the selected class has space
    $checkClassStmt = $conn->prepare("
        SELECT 
            c.ClassCapacity, COUNT(p.PupilID) AS CurrentCount
        FROM Classes c
        LEFT JOIN Pupils p ON c.ClassID = p.ClassID
        WHERE c.ClassID = ?
        GROUP BY c.ClassID
    ");
    $checkClassStmt->bind_param("i", $classID);
    $checkClassStmt->execute();
    $result = $checkClassStmt->get_result();
    $classInfo = $result->fetch_assoc();
    $checkClassStmt->close();

    if ($classInfo && $classInfo['CurrentCount'] >= $classInfo['ClassCapacity']) {
        echo "<script>alert('The selected class is already full. Please choose a different class.'); window.history.back();</script>";
        exit;
    }

    // ✅ Validate Guardian 2 ID if provided
    if (!empty($guardian2ID)) {
        $guardianCheck = $conn->prepare("SELECT GuardianID FROM Guardian WHERE GuardianID = ?");
        $guardianCheck->bind_param("i", $guardian2ID);
        $guardianCheck->execute();
        $guardianCheckResult = $guardianCheck->get_result();

        if ($guardianCheckResult->num_rows === 0) {
            echo "<script>alert('Invalid Guardian 2 ID. Please select a valid guardian.'); window.history.back();</script>";
            exit;
        }
        $guardianCheck->close();
    }

    // ✅ Insert pupil
    $stmt = $conn->prepare("
        INSERT INTO Pupils (
            Title, FirstName, Surname, DOB,
            Address_Line1, Address_Line2, Address_Line3, Postcode,
            Medical_Allergy, Medical_Vaccination, ClassID, Guardian1ID, Guardian2ID, Notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "ssssssssssiiis",
        $title, $firstName, $lastName, $dob,
        $address1, $address2, $address3, $postcode,
        $medicalAllergy, $medicalVaccination, $classID, $guardian1ID, $guardian2ID, $notes
    );

    if ($stmt->execute()) {
        echo "<script>alert('Pupil added successfully!'); window.location.href='view_pupils.php';</script>";
    } else {
        echo "<script>alert('Error adding pupil: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
} else {
    header('Location: add_pupil.php');
    exit;
}

$conn->close();
?>


