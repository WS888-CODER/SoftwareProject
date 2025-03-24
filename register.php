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
    // Check if required fields are set correctly
    if (empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["phone"])) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit();
    }

    $name = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $passwordRaw = $_POST["password"];
    $phone = trim($_POST["phone"]);

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format."]);
        exit();
    }

    // Password validation
    if (strlen($passwordRaw) < 8) {
        echo json_encode(["status" => "error", "message" => "Password must be at least 8 characters."]);
        exit();
    }

    // Phone validation
    if (!preg_match('/^\+966\d{9}$/', $phone)) {
        echo json_encode(["status" => "error", "message" => "Phone number must start with +966 and be 12 digits."]);
        exit();
    }

    $password = password_hash($passwordRaw, PASSWORD_DEFAULT); // Securely hash password

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
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
