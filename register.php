<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Send appropriate header for JSON response
header('Content-Type: application/json');

include 'db.php'; // Make sure the database connection is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required fields are set
    if (empty($_POST["username"])  empty($_POST["email"])  empty($_POST["password"]) || empty($_POST["phone"])) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit();
    }

    $name = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Securely hash password
    $phone = $_POST["phone"];

    // Generate a unique UserID
    $userID = uniqid('user_');

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT * FROM user WHERE Email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already registered!"]);
        exit();
    }

    // Insert user data
    $stmt = $conn->prepare("INSERT INTO user (UserID, Name, PhoneNumber, Email, Password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $userID, $name, $phone, $email, $password);

    if ($stmt->execute()) {
        // On success, store the UserID in session
        $_SESSION['UserID'] = $userID;
        $_SESSION['UserName'] = $name;

        
        echo json_encode(["status" => "success", "message" => "Registration successful!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed!"]);
    }

    $stmt->close();
    $conn->close();
} else {
    // If the request method isn't POST, return error
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>