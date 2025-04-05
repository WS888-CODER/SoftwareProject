<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    header('Location: login.html');
    exit();
}

$userId = $_SESSION['UserID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $Region = isset($_POST['region-personalized']) ? trim($_POST['region-personalized']) : '';
    $selectedTypes = isset($_POST['destination-personalized']) ? $_POST['destination-personalized'] : '';
    $selectedActivities = isset($_POST['things-to-do']) ? $_POST['things-to-do'] : [];
    $tripStartDate = $_POST["start-date-personalized"]; // User-selected start date

    // Retrieve duration (default to 7 days)
    $duration = isset($_POST['duration-personalized']) ? $_POST['duration-personalized'] : 7;
    $dailyAvailableHours = 6;
    $totalAvailableHours = $duration * $dailyAvailableHours;

    // Fetch destinations from the database based on the selected region
    $sqlDestinations = "SELECT DestinationID, Name, Description, Type, TimeNeeded, BackgroundPhoto FROM destination WHERE Region = ?";
    $stmt = $conn->prepare($sqlDestinations);
    $stmt->bind_param("s", $Region);
    $stmt->execute();
    $destinationsResult = $stmt->get_result();

    $filteredDestinations = [];
    $totalTimeNeeded = 0;

    // Process the destinations
    if ($destinationsResult->num_rows > 0) {
        while ($row = $destinationsResult->fetch_assoc()) {
            $destinationID = $row['DestinationID'];
            $destinationType = strtolower(trim($row['Type']));
            $timeNeeded = isset($row['TimeNeeded']) ? intval($row['TimeNeeded']) : 0;

            // Fetch activities for the current destination
            $sqlActivities = "SELECT ThingsToDo FROM thingstodo WHERE DestinationID = ?";
            $stmtActivities = $conn->prepare($sqlActivities);
            $stmtActivities->bind_param("s", $destinationID);
            $stmtActivities->execute();
            $activitiesResult = $stmtActivities->get_result();

            $activities = [];
            while ($activity = $activitiesResult->fetch_assoc()) {
                $activities[] = strtolower(trim(preg_replace('/\s+/', ' ', $activity['ThingsToDo'])));
            }

            // Normalize user-selected activities and types
            $normalizedSelectedActivities = array_map('strtolower', array_map('trim', $selectedActivities));
            $normalizedSelectedTypes = is_array($selectedTypes) ? array_map('strtolower', array_map('trim', $selectedTypes)) : [strtolower(trim($selectedTypes))];

            // Check for activity and type match
            $activitiesMatch = empty($selectedActivities) || array_filter($activities, function ($act) use ($normalizedSelectedActivities) {
                foreach ($normalizedSelectedActivities as $selected) {
                    if (strpos($act, $selected) !== false) return true;
                }
                return false;
            });

            $typeMatch = empty($selectedTypes) || array_filter($normalizedSelectedTypes, function ($selectedType) use ($destinationType) {
                return strpos($destinationType, $selectedType) !== false;
            });

            // If match found and within time limit, add to filtered destinations
            if (($activitiesMatch || $typeMatch) && ($totalTimeNeeded + $timeNeeded <= $totalAvailableHours)) {
                $filteredDestinations[] = $row;
                $totalTimeNeeded += $timeNeeded;
            }
        }
    }

    // **❌ No matching destinations alert**
    if (empty($filteredDestinations)) {
        echo "<script>alert('❌ No destinations match your preferences. Please try different inputs.'); window.location.href='myschedules.php';</script>";
        exit();
    }

    // Now create a new schedule without checking for duplicates (always create a schedule)
    $newScheduleID = uniqid('schedule_', true);
    $startDate = date('Y-m-d');

    // Insert into tripscheduler table to create a new schedule
    $sqlInsertSchedule = "INSERT INTO tripscheduler (ScheduleID, UserID, Date, StartDate, Duration) VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsertSchedule);
    $stmtInsert->bind_param("ssssi", $newScheduleID, $userId, $startDate, $tripStartDate, $duration);

    if ($stmtInsert->execute()) {
        echo "<script>alert('✅ New schedule created successfully!'); window.location.href='myschedules.php';</script>";
    } else {
        echo "<script>alert('Error creating schedule.'); window.location.href='myschedules.php';</script>";
    }

    $stmtInsert->close();

    // Insert filtered destinations into the 'contains' table for the new schedule
    foreach ($filteredDestinations as $destination) {
        $destinationID = $destination['DestinationID'];

        // Check if this destination is already in the 'contains' table for the new schedule
        $sqlCheckContains = "SELECT 1 FROM contains WHERE ScheduleID = ? AND DestinationID = ?";
        $stmtCheckContains = $conn->prepare($sqlCheckContains);
        $stmtCheckContains->bind_param("ss", $newScheduleID, $destinationID);
        $stmtCheckContains->execute();
        $stmtCheckContains->store_result();

        if ($stmtCheckContains->num_rows == 0) {
            // If destination doesn't exist in the schedule, add it
            $sqlInsertContains = "INSERT INTO contains (ScheduleID, DestinationID) VALUES (?, ?)";
            $stmtInsertContains = $conn->prepare($sqlInsertContains);
            $stmtInsertContains->bind_param("ss", $newScheduleID, $destinationID);
            $stmtInsertContains->execute();
            $stmtInsertContains->close();
        }

        $stmtCheckContains->close();
    }

    // Redirect to avoid form resubmission (handled by alert already)
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FARRAH | My Schedules </title>
  <link rel="icon" type="image/png" href="images/logo.png">
  <link rel="stylesheet" href="css/MySchedules.css">
</head>

<body>

  <!-- slide bar and header code goes here -->
 <div class="slidebar-btn" id="slidebarBtn">
    <span class="bar"></span>
    <span class="bar"></span>
    <span class="bar"></span>
  </div>

  <!--slide bar -->
  <div class="slidebar" id="slidebar">
    <ul>
      <br><br>
      <li><a href="PersonalInformation.html">Personal information</a></li><br>
      <li><a href="personalizedSchedules.html">FARRAH Personalized schedule</a></li><br>
      <li><a href="myschedules.php">My schedules</a></li>
      <li><a class='sign-out' href="Logout.php">Sign out </a></li>

    </ul>
  </div>


  <!-- header -->
  <header>
    <div class="header-left">
      <div class="logo">Farrah</div>
    </div>
    <nav>
      <ul>
        <li><a style="text-decoration: none;" href="Home.html">Home</a></li>
        <li><a style="text-decoration: none;" href="Destnition.html">Destinations</a></li>
      </ul>
    </nav>
  </header>
  <div class="main-container">
    <h2>You can select your schedule from <br> <strong>FARRAH Personalized Schedule</strong></h2>
    <a href="personalizedSchedules.html" style="text-decoration: none;">
      <button class="discover-btn">Discover Now!!</button>
    </a>
<!-- Schedule Wrapper -->


    <?php
    $userID = $_SESSION['UserID']; // Assuming session holds UserID

    // Fetch all schedules of the current user
    $sqlFetchSchedules = "SELECT ScheduleID, Date, Time FROM tripscheduler WHERE UserID = ? ORDER BY Date DESC";
    if ($stmtSchedules = $conn->prepare($sqlFetchSchedules)) {
        $stmtSchedules->bind_param("s", $userID);
        $stmtSchedules->execute();
        $stmtSchedules->store_result(); // ✅ Store results
        $stmtSchedules->bind_result($scheduleID, $scheduleDate, $scheduleTime);

        // Loop through each schedule and display it
        while ($stmtSchedules->fetch()) { // ✅ Now, we don't close $stmtSchedules too early
            ?>
            <div class="schedule-wrapper">
            <div class="schedule-row">
                <h1 class="schedule-name"><?php echo "Schedule #" . substr($scheduleID, 9, 6); ?></h1>

                <div class="buttons-container">
                    <a href="ScheduleDetails.php?schedule_id=<?php echo $scheduleID; ?>" style="text-decoration: none;">
                        <button class="ScheduleDetails-button">More details</button>
                    </a>
                    <form action="delete_schedule.php" method="POST" style="display:inline;">
                        <input type="hidden" name="schedule_id" value="<?php echo $scheduleID; ?>">
                        <button class="trash-btn" type="submit" >&#128465;</button>
                    </form>
                </div>
</div>
                <br>
                <div class="cards-slider">
                    <button class="slider-btn prev-btn">&#9664;</button>
                    <div class="cards-container">
                        <?php
                        // Fetch destinations linked to this schedule from "contains" table
                        $sqlFetchDestinations = "SELECT d.DestinationID, d.Name, d.Description, d.BackgroundPhoto
                                                 FROM contains c 
                                                 JOIN destination d ON c.DestinationID = d.DestinationID
                                                 WHERE c.ScheduleID = ?";
                        if ($stmtDestinations = $conn->prepare($sqlFetchDestinations)) {
                            $stmtDestinations->bind_param("s", $scheduleID);
                            $stmtDestinations->execute();
                            $resultDestinations = $stmtDestinations->get_result();

                            while ($destination = $resultDestinations->fetch_assoc()) {
                                ?>
                                <!-- Destination Card -->
                                <div class="card">
                                    <img src="<?php echo $destination['BackgroundPhoto']; ?>" alt="Destination">
                                    <div class="card-content">
                                        <h3><?php echo $destination['Name']; ?></h3>
                                        <p>
                                            <?php
                                            $maxLength = 80;
                                            $description = $destination['Description'];
                                            if (strlen($description) > $maxLength) {
                                                $description = substr($description, 0, $maxLength) . '...';
                                            }
                                            echo $description;
                                            ?>
                                        </p>
                                        <a href="DynamicDestination.php?DestinationID=<?php echo $destination['DestinationID']; ?>">Read More</a>
                                    </div>
                                </div>
                                <?php
                            }
                            $stmtDestinations->close(); // ✅ Close after use
                        }
                        ?>
                    </div>
                    <button class="slider-btn next-btn">&#9654;</button>
                </div>

                <br>
                <div class="schedule-info">
                    <?php
                    // Display formatted schedule date and time
                    if (!empty($scheduleDate) && !empty($scheduleTime)) {
                        echo '<p class="schedule-time">' . date('F j, Y', strtotime($scheduleDate)) . ' at ' . date('h:i A', strtotime($scheduleTime)) . '</p>';
                    } else {
                        echo '<p class="schedule-time">Date & Time not found</p>';
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        $stmtSchedules->close(); // ✅ Now we close $stmtSchedules only AFTER the loop
    } else {
        echo "<p>No schedules found.</p>";
    }
    
    ?>
</div>


<footer id="footer" class="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">
          <!-- About us -->
          <div class="col">
            <div class="single-footer">
              <h2>About Us</h2>
              <p>
                Welcome to Farrah Travel, your gateway to exploring the hidden treasures of Saudi Arabia.
                We are dedicated to providing curated travel experiences, insightful guides, and exceptional
                services to ensure every journey becomes an unforgettable adventure.
              </p>
            </div>
          </div>
          <!-- Vision -->
          <div class="col">
            <div class="single-footer">
              <h2>Vision</h2>
              <p>
                Our vision is to showcase the natural and cultural wonders of Saudi Arabia,
                creating memorable travel experiences for every explorer.
              </p>
            </div>
          </div>
          <!-- Contact Us-->
          <div class="col">
            <h2>Contact Us</h2>
            <ul class="social">
              <li>
                <img src="images/facebook-icon.png" alt="facebook" />
                <span>FarrahTravel</span>
              </li>
              <li>
                <img src="images/x-icon.png" alt="x" />
                <span>@FarrahTravel</span>
              </li>
              <li>
                <img src="images/instagram-icon.png" alt="instagram" />
                <span>@Farrah_Travel</span>
              </li>
              <li>
                <img src="images/gmail-icon.png" alt="gmail" />
                <span>info@farrahtravel.com</span>
              </li>
              <li>
                <img src="images/phone-icon.png" alt="phone" />
                <span>+966 555 123 456</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!--  copyright -->
    <div class="copyright">
      <div class="container">
        <div class="row">
          <div class="copyright-content">
            <p>
              © 2025 | All Rights Reserved by
              <span>Farrah Travel</span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </footer>
</body>
<script>
    const slidebarBtn = document.getElementById('slidebarBtn');
    const slidebar = document.getElementById('slidebar');
    slidebarBtn.addEventListener('click', () => {
      slidebar.classList.toggle('active');
      slidebarBtn.classList.toggle('is-active');
    });
    </script>

    <script>
  document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".schedule-wrapper").forEach((scheduleWrapper) => {
    const prevBtn = scheduleWrapper.querySelector(".prev-btn");
    const nextBtn = scheduleWrapper.querySelector(".next-btn");
    const cardsContainer = scheduleWrapper.querySelector(".cards-container");
    const trashBtn = scheduleWrapper.querySelector(".trash-btn");

    if (prevBtn && nextBtn && cardsContainer && trashBtn) {
      let scrollAmount = 0;
      const step = 250;

      function updateButtonsVisibility() {
        prevBtn.style.display = scrollAmount <= 0 ? "none" : "block";
        nextBtn.style.display = scrollAmount + scheduleWrapper.clientWidth >= cardsContainer.scrollWidth ? "none" : "block";
      }

      nextBtn.addEventListener("click", function () {
        if (scrollAmount + scheduleWrapper.clientWidth < cardsContainer.scrollWidth) {
          scrollAmount += step;
          cardsContainer.style.transform = `translateX(-${scrollAmount}px)`;
        }
        updateButtonsVisibility();
      });

      prevBtn.addEventListener("click", function () {
        if (scrollAmount > 0) {
          scrollAmount -= step;
          cardsContainer.style.transform = `translateX(-${scrollAmount}px)`;
        }
        updateButtonsVisibility();
      });

      trashBtn.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent form submission

        if (confirm("Are you sure you want to delete this schedule?")) {
          let form = trashBtn.closest("form");
          let formData = new FormData(form);

          fetch(form.action, {
            method: "POST",
            body: formData
          })
          .then(response => response.json()) // Parse response as JSON
          .then(data => {
            if (data.success) {
              alert(data.message); // Show success message
              scheduleWrapper.remove(); // Remove from UI
            } else {
              alert("Error: " + data.message); // Show error message
            }
          })
          .catch(error => {
            alert("Request failed! Check console for details.");
            console.error(error);
          });
        }
      });

      updateButtonsVisibility();
    }
  });
});


    </script>
</html>