<?php
session_start(); // Start the session

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include 'db.php';

header('Content-Type: application/json'); // Set response type to JSON

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["schedule_id"])) {
    $scheduleID = $_POST["schedule_id"];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete from 'contains' table first
        $sql1 = "DELETE FROM contains WHERE ScheduleID = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("s", $scheduleID);
        $stmt1->execute();
        $stmt1->close();

        // Delete from 'tripscheduler' table
        $sql2 = "DELETE FROM tripscheduler WHERE ScheduleID = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("s", $scheduleID);
        $stmt2->execute();
        $stmt2->close();

        // Commit transaction if both deletions were successful
        $conn->commit();

        echo json_encode(["success" => true, "message" => "Schedule deleted successfully!"]);
    } catch (Exception $e) {
        $conn->rollback(); // Rollback if any issue occurs
        echo json_encode(["success" => false, "message" => "Error deleting schedule: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

// Close the connection
$conn->close();
?>
