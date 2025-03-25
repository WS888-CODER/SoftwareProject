<?php

session_start(); // Start the session

// Enable error reporting for debugging (optional)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include 'db.php'; 

// Set response header to indicate that we're sending JSON
header('Content-Type: application/json'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs and sanitize them
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Check if username and password are not empty
    if (empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Both username and password are required."]);
        exit();
    }

    // Prepare SQL statement to check if the user exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE Name = ?");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database error. Please try again."]);
        exit();
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['Password'])) {
            // Set session variables on successful login
            $_SESSION['UserID'] = $user['UserID'];
            $_SESSION['UserName'] = $user['Name'];

            // Return JSON response for success (no debugging information in JSON)
            echo json_encode([
                "status" => "success",
                "message" => "Login successful. Session is set!"
            ]);

            // End session handling, save the session
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

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
