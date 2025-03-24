<?php

session_start(); // Start the session

// Enable error reporting for debugging (optional)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the user exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE Name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Check password
        if (password_verify($password, $user['Password'])) {
            // Set session variables on successful login
            $_SESSION['UserID'] = $user['UserID'];
            $_SESSION['UserName'] = $user['Name'];

            // Return JSON response for success (no debugging information in JSON)
            echo json_encode([
                "status" => "success",
                "message" => "Login successful. Session is set!"
            ]);

            // End the session handling, save the session
            session_write_close();
            exit();
        } else {
            // Incorrect password
            echo json_encode(["status" => "error", "message" => "Incorrect password!"]);
        }
    } else {
        // User not found
        echo json_encode(["status" => "error", "message" => "User not found!"]);
    }

    $stmt->close();
    $conn->close();
}
?>
