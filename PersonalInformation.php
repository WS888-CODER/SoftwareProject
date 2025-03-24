<?php
session_start();
header('Content-Type: application/json');
include 'db.php';

if (!isset($_SESSION['UserID'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit();
}

$userID = $_SESSION['UserID'];

$stmt = $conn->prepare("SELECT Name, Email, PhoneNumber FROM user WHERE UserID = ?");
$stmt->bind_param("s", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

echo json_encode([
    "status" => "success",
    "data" => [
        "name" => $user['Name'],
        "email" => $user['Email'],
        "phone" => $user['PhoneNumber'],
        "password" => "********"
    ]
]);
?>
