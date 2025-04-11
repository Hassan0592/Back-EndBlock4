<?php
include 'config.php';


$username = "admin";
$password = "admin123"; 
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO administrators (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $passwordHash);

if ($stmt->execute()) {
    echo "✅ Admin user 'admin' created successfully.";
} else {
    echo "❌ Error: " . $stmt->error;
}
?>