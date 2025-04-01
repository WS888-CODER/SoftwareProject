<?php
session_start();
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["username-Register"], $_POST["email-Register"], $_POST["password-login"], $_POST["phone-login"])) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit();
    }

    $name = trim($_POST["username-Register"]);
    $email = trim($_POST["email-Register"]);
    $passwordRaw = $_POST["password-login"];
    $phone = trim($_POST["phone-login"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format."]);
        exit();
    }

    if (strlen($passwordRaw) < 8) {
        echo json_encode(["status" => "error", "message" => "Password must be at least 8 characters."]);
        exit();
    }

    if (!preg_match('/^\+966\d{9}$/', $phone)) {
        echo json_encode(["status" => "error", "message" => "Phone number must start with +966 and be 12 digits."]);
        exit();
    }

    $password = password_hash($passwordRaw, PASSWORD_DEFAULT);
    $userID = uniqid('user_');

    $checkEmail = $conn->prepare("SELECT * FROM user WHERE Email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already registered!"]);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO user (UserID, Name, PhoneNumber, Email, Password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $userID, $name, $phone, $email, $password);

    if ($stmt->execute()) {
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
