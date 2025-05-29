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
    $email = trim($_POST["email-login"]);
    $password = trim($_POST["password-login"]);

    // Check if email and password are not empty
    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Both email and password are required."]);
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format."]);
        exit();
    }

    // Prepare SQL statement to check if the email exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE Email = ?");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database error. Please try again."]);
        exit();
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['Password'])) {
            // Set session variables on successful login
            $_SESSION['UserID'] = $user['UserID'];
            $_SESSION['UserEmail'] = $user['Email']; // Change Name to Email

            // Return JSON response for success (no debugging information in JSON)
            echo json_encode([
                "status" => "success",
                "message" => "Login successful."
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
