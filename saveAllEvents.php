<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['schedule_id']) || !isset($data['events'])) {
    echo json_encode(['status' => 'error', 'message' => 'Incomplete data']);
    exit;
}

$scheduleID = $data['schedule_id'];
$events = $data['events'];

$deleteStmt = $conn->prepare("DELETE FROM contains WHERE ScheduleID = ?");
$deleteStmt->bind_param("s", $scheduleID);
$deleteStmt->execute();
$deleteStmt->close();

$insertStmt = $conn->prepare("INSERT INTO contains (ScheduleID, DestinationID, StartDateTime, EndDateTime) VALUES (?, ?, ?, ?)");

foreach ($events as $event) {
    $destinationName = $event['title'];
    $start = $event['start'];
    $end = $event['end'];

    $destQuery = $conn->prepare("SELECT DestinationID FROM destination WHERE Name = ?");
    $destQuery->bind_param("s", $destinationName);
    $destQuery->execute();
    $destQuery->bind_result($destinationID);
    $destQuery->fetch();
    $destQuery->close();

    if ($destinationID) {
        $insertStmt->bind_param("ssss", $scheduleID, $destinationID, $start, $end);
        $insertStmt->execute();
    }
}

$insertStmt->close();

echo json_encode(['status' => 'success', 'message' => 'Destinations saved successfully ✅']);
