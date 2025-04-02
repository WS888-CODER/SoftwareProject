<?php
include 'db.php';

if (!isset($_GET['schedule_id'])) {
    die("Schedule ID is missing.");
}

$scheduleID = $_GET['schedule_id'];
session_start();

if (!isset($_SESSION['UserID'])) {
    die("Please log in.");
}
$userID = $_SESSION['UserID'];

$sql = "SELECT d.Name, c.StartDateTime, c.EndDateTime
        FROM contains c
        JOIN destination d ON c.DestinationID = d.DestinationID
        WHERE c.ScheduleID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $scheduleID);
$stmt->execute();
$result = $stmt->get_result();

$ics = "BEGIN:VCALENDAR\r\n";
$ics .= "VERSION:2.0\r\n";
$ics .= "PRODID:-//TripPlanner//Schedule Export//EN\r\n";

while ($row = $result->fetch_assoc()) {
    $start = new DateTime($row['StartDateTime']);
    $end = new DateTime($row['EndDateTime']);

    $ics .= "BEGIN:VEVENT\r\n";
    $ics .= "UID:" . uniqid() . "@tripplanner\r\n";
    $ics .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
    $ics .= "DTSTART:" . $start->format('Ymd\THis\Z') . "\r\n";
    $ics .= "DTEND:" . $end->format('Ymd\THis\Z') . "\r\n";
    $ics .= "SUMMARY:" . htmlspecialchars($row['Name']) . "\r\n";
    $ics .= "DESCRIPTION:Event from FARRAH\r\n";
    $ics .= "END:VEVENT\r\n";
}

$ics .= "END:VCALENDAR\r\n";

// send file to browser for download
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=trip_schedule_' . $scheduleID . '.ics');
echo $ics;
exit;
?>
