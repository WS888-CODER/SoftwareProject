<?php
include "db.php"; // Connect to database

// Get user inputs
$budget = $_POST["budget-personalized"];
$destinationType = $_POST["destination-personalized"];
$duration = $_POST["duration-personalized"];
$userID = $_SESSION['userID']; // Assume user is logged in

// Fetch destinations matching the user’s preference
$query = "SELECT * FROM destination WHERE Type = '$destinationType' ORDER BY TimeNeeded ASC LIMIT $duration";
$result = mysqli_query($conn, $query);

$destinations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $destinations[] = $row;
}

// Insert into tripschedule table
$tripID = uniqid("trip_");
foreach ($destinations as $index => $destination) {
    $date = date('Y-m-d', strtotime("+$index days"));
    $tripQuery = "INSERT INTO tripschedule (tripID, userID, date, time) 
                  VALUES ('$tripID', '$userID', '$date', '09:00:00')";
    mysqli_query($conn, $tripQuery);
}

// Return data as JSON
echo json_encode($destinations);
?>
