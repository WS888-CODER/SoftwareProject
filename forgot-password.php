<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
    exit();
}

$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$newPassword = trim($data['newPassword']);
$confirmPassword = trim($data['confirmNewPassword']);

if (empty($email) || empty($newPassword) || empty($confirmPassword)) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email format."]);
    exit();
}

if ($newPassword !== $confirmPassword) {
    echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
    exit();
}

if (strlen($newPassword) < 8) {
    echo json_encode(["status" => "error", "message" => "Password must be at least 8 characters long."]);
    exit();
}

// Check if email exists
$checkStmt = $conn->prepare("SELECT UserID FROM user WHERE Email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows == 0) {
    echo json_encode(["status" => "error", "message" => "Email not found in the system."]);
    $checkStmt->close();
    exit();
}
$checkStmt->close();

// Hash the password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Update the password
$updateStmt = $conn->prepare("UPDATE user SET Password = ? WHERE Email = ?");
$updateStmt->bind_param("ss", $hashedPassword, $email);

if ($updateStmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Password reset successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Something went wrong."]);
}

$updateStmt->close();
$conn->close();
?>
