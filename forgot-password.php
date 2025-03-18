<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection (make sure it's correct)
include 'db.php';

// Ensure the response is in JSON format
header('Content-Type: application/json');

// Get raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is received
if ($data === null || !isset($data['username']) || !isset($data['newPassword'])) {
    echo json_encode(["status" => "error", "message" => "Invalid data. Please try again."]);
    exit();
}

// Prepare and update the password in the database
$username = $data['username'];
$newPassword = $data['newPassword'];
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("UPDATE user SET password = ? WHERE Name = ?");
$stmt->bind_param("ss", $hashedPassword, $username);

// Execute and check if the query was successful
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Password has been successfully reset."]);
} else {
    echo json_encode(["status" => "error", "message" => "Something went wrong. Please try again."]);
}

$stmt->close();
?>
