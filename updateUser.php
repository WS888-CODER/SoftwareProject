<?php
session_start();
header('Content-Type: application/json');
include 'db.php';

if (!isset($_SESSION['UserID'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit();
}

$userID = $_SESSION['UserID'];

$data = json_decode(file_get_contents("php://input"), true);

$newName = $data['username'];
$newEmail = $data['email'];
$newPhone = $data['phone'];
$newPassword = $data['password'];

if (strlen($newPassword) < 8) {
    echo json_encode(["status" => "error", "message" => "Password must be at least 8 characters."]);
    exit();
}

$checkStmt = $conn->prepare("SELECT UserID FROM user WHERE Email = ? AND UserID != ?");
$checkStmt->bind_param("ss", $newEmail, $userID);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "📧 Email already exists. Please use a different email."]);
    $checkStmt->close();
    exit();
}
$checkStmt->close();

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE user SET Name = ?, Email = ?, PhoneNumber = ?, Password = ? WHERE UserID = ?");
$stmt->bind_param("sssss", $newName, $newEmail, $newPhone, $hashedPassword, $userID);

if ($stmt->execute()) {
    $_SESSION['UserName'] = $newName;
    echo json_encode(["status" => "success", "message" => "✅ Information updated successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "❌ Failed to update information."]);
}

$stmt->close();
$conn->close();
?>
