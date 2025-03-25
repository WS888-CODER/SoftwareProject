<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php'; // Include database connection

header('Content-Type: application/json');

// Get raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Make sure the data is decoded and not null
if ($data === null) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON received."]);
    exit();
}

// Access data from the decoded JSON
$email = $data['email'];
$newPassword = $data['newPassword'];
$confirmPassword = $data['confirmNewPassword'];

// Check if all fields are filled
if (empty($email) || empty($newPassword) || empty($confirmPassword)) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit();
}

// Sanitize input
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email format."]);
    exit();
}


// Ensure database connection is valid
if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . mysqli_connect_error()]);
    exit();
}

// Check if email exists
$checkStmt = $conn->prepare("SELECT UserID FROM user WHERE email = ?");
if (!$checkStmt) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
    exit();
}

$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows == 0) {
    echo json_encode(["status" => "error", "message" => "Email not found in the system."]);
    $checkStmt->close();
    $conn->close();
    exit();
}

// Check if passwords match
if ($newPassword !== $confirmPassword) {
    echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
    exit();
}

// Check if new password is at least 8 characters long
if (strlen($newPassword) < 8) {
    echo json_encode(["status" => "error", "message" => "Password must be at least 8 characters long."]);
    exit();
}


$checkStmt->close(); // Close the first statement before updating

// Hash the new password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Update the password in the database
$updateStmt = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
if (!$updateStmt) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
    exit();
}

$updateStmt->bind_param("ss", $hashedPassword, $email);

if ($updateStmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Password has been successfully reset."]);
} else {
    echo json_encode(["status" => "error", "message" => "Something went wrong. Please try again."]);
}

// Close resources
$updateStmt->close();
$conn->close();
?>
