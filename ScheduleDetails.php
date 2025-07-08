<?php
session_start();
include 'db.php';

if (!isset($_SESSION['UserID'])) {
    echo "Please log in";
    exit;
}

if (!isset($_GET['schedule_id'])) {
    echo "The schedule is not set.";
    exit;
}

$user_id = $_SESSION['UserID'];
$schedule_id = $_GET['schedule_id'];

$eventsData = [];
$destData = [];

$schedule_sql = "SELECT StartDate, Duration FROM tripscheduler WHERE ScheduleID = ? AND UserID = ?";
$schedule_stmt = $conn->prepare($schedule_sql);
$schedule_stmt->bind_param("ss", $schedule_id, $user_id);
$schedule_stmt->execute();
$schedule_result = $schedule_stmt->get_result();
$schedule_info = $schedule_result->fetch_assoc();

$startDate = $schedule_info['StartDate'];
$duration = $schedule_info['Duration'];
$dailyStartHour = 8;
$dailyEndHour = 21;
$gap = 4;

if ($startDate && $duration) {
    $sql = "SELECT d.DestinationID, d.Name, d.Description, d.BackgroundPhoto, c.StartDateTime, c.EndDateTime, d.TimeNeeded
            FROM contains c
            JOIN destination d ON c.DestinationID = d.DestinationID
            WHERE c.ScheduleID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $schedule_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $unscheduled = [];
    while ($row = $result->fetch_assoc()) {
        $destId = $row['DestinationID'];
        $name = $row['Name'];
        $desc = $row['Description'] ?? '';
        $image = $row['BackgroundPhoto'] ?? '';
        $timeNeeded = $row['TimeNeeded'] ?? 2;

        if ($row['StartDateTime'] == null || $row['EndDateTime'] == null) {
            $unscheduled[] = [
                'DestinationID' => $destId,
                'Name' => $name,
                'TimeNeeded' => $timeNeeded
            ];
        } else {
            $eventsData[] = [
                'id' => $destId,
                'title' => $name,
                'start' => $row['StartDateTime'],
                'end' => $row['EndDateTime'],
                'backgroundColor' => '#d0a84b',
                'borderColor' => '#b88d3f'
            ];
        }

        $destData[$name] = [
            'image' => $image,
            'description' => $desc,
            'readMore' => "DynamicDestination.php?DestinationID=" . urlencode($destId)
        ];
    }

    if (!empty($unscheduled)) {
        shuffle($unscheduled);
        $currentDate = new DateTime($startDate);
        $currentHour = $dailyStartHour;

        foreach ($unscheduled as $destination) {
            $timeNeeded = (int)$destination['TimeNeeded'];

            if ($currentHour + $timeNeeded > $dailyEndHour) {
                $currentDate->modify('+1 day');
                $currentHour = $dailyStartHour;
            }

            $startTime = clone $currentDate;
            $startTime->setTime($currentHour, 0);

            $endTime = clone $startTime;
            $endTime->modify("+{$timeNeeded} hour");

            $startStr = $startTime->format('Y-m-d H:i:s');
            $endStr = $endTime->format('Y-m-d H:i:s');

            $update_sql = "UPDATE contains SET StartDateTime = ?, EndDateTime = ? WHERE ScheduleID = ? AND DestinationID = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssss", $startStr, $endStr, $schedule_id, $destination['DestinationID']);
            $update_stmt->execute();

            $eventsData[] = [
                'id' => $destination['DestinationID'],
                'title' => $destination['Name'],
                'start' => $startStr,
                'end' => $endStr,
                'backgroundColor' => '#d0a84b',
                'borderColor' => '#b88d3f'
            ];

            $currentHour += ($timeNeeded + $gap);
        }
    }
}

$schedule_query = "SELECT ScheduleID, UserID FROM tripscheduler WHERE UserID = ?";
$stmt2 = $conn->prepare($schedule_query);
$stmt2->bind_param("s", $user_id);
$stmt2->execute();
$schedule_result = $stmt2->get_result();
$dest_sql = "SELECT Name FROM destination";
$dest_stmt = $conn->prepare($dest_sql);
$dest_stmt->execute();
$dest_result = $dest_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="ar">

<head>
  <meta charset="UTF-8">
  <title>FARRAH | Schedule Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="images/logo.png">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="css/ScheduleDetails.css"> 
  <link rel="stylesheet" href="css/HomeStyle.css">
  <link rel="stylesheet" href="css/baiscStyle.css"> 
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.css" rel="stylesheet" />

</head>

<body>

  <!-- Slider bar-->
  <div class="slidebar-btn" id="slidebarBtn">
    <span class="bar"></span>
    <span class="bar"></span>
    <span class="bar"></span>
  </div>

  <div class="slidebar" id="slidebar">
    <ul>
      <br><br>
      <li><a href="PersonalInformation.html">Personal information</a></li><br>
      <li><a href="personalizedSchedules.html">FARRAH Personalized schedule</a></li><br>
      <li><a href="myschedules.php">My schedules</a></li>
      <li><a class="sign-out" href="Logout.php">Sign out </a></li>
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

  <!-- calendar -->
  <div class="container">
    <div id="calendar"></div>
  </div>
  
  <!-- add destination -->
  <div id="createEventModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New Destination</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
          <form id="createEventForm">          
            <!-- Choose Destination -->
            <label>Choose Destination:</label>
            <select id="eventDestination" class="form-control" required>
              <?php if ($dest_result && $dest_result->num_rows > 0): ?>
                <?php while ($dest = $dest_result->fetch_assoc()): ?>
                  <option value="<?php echo htmlspecialchars($dest['Name']); ?>">
                    <?php echo htmlspecialchars($dest['Name']); ?>
                  </option>
                <?php endwhile; ?>
              <?php else: ?>
                <option disabled>No destinations available</option>
              <?php endif; ?>
            </select>

            <!--Destination Date & Time -->
            <label>Destination Date & Time:</label>
            <input type="datetime-local" id="newEventDateTime" class="form-control" required>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" id="saveNewEvent" class="btn btn-success">Add Destination</button>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center" style="margin-top: 10px;">
    <a href="download_schedule_ics.php?schedule_id=<?= $schedule_id ?>" class="btn btn-Download" target="_blank">
      📅 Download the schedule to your calendar
    </a>
    <button type="button" id="saveAllChanges" class="btn btn-primary">Save All Changes</button>
  </div>

  <!-- Edit Destination-->
  <div id="editEventModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Destination</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="eventForm">
            <!-- Schedule Image -->
            <div class="destination-image-container">
              <img id="destinationImage" src="" alt="Destination Image" class="destination-image">
            </div>

            <!-- Destination Title -->
            <label>Destination Title:</label>
            <p id="eventTitle" class="destination-title"></p>

            <!-- Description -->
            <label>Description:</label>
            <p id="destinationDescription"></p>

            <!-- Read More Link -->
            <a id="readMoreLink" href="DynamicDestination.php?DestinationID=?" target="_blank" class="btn btn-info">Read More</a>
            <br>

            <!-- Destination Date & Time -->
            <label>Destination Date & Time:</label>
            <input type="datetime-local" id="eventDateTime" class="form-control" required>

            <input type="hidden" id="eventId">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" id="saveChanges" class="btn btn-success">Save Changes</button>
          <button type="button" id="deleteEvent" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <!-- footer -->
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
  <script src="https://cdn.jsdelivr.net/npm/input-moment@1.0.0/dist/input-moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/3.25.0/minified.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js"></script>

  <script>
    const slidebarBtn = document.getElementById('slidebarBtn');
    const slidebar = document.getElementById('slidebar');

    slidebarBtn.addEventListener('click', () => {
      slidebar.classList.toggle('active');
      slidebarBtn.classList.toggle('is-active');
    });
  </script>

<script>
  const destinationDetails = <?php echo json_encode($destData, JSON_UNESCAPED_UNICODE); ?>;
  const tripDuration = <?php echo json_encode($duration); ?>;
  const tripStartDate = moment(<?php echo json_encode($startDate); ?>);
  
  document.addEventListener("DOMContentLoaded", () => {
    const datetimeInput = document.getElementById("newEventDateTime");
    const editInput = document.getElementById("eventDateTime");

    if (tripStartDate && typeof moment !== "undefined") {
      const minDateTime = tripStartDate.format("YYYY-MM-DDTHH:mm");

      if (datetimeInput) {
        datetimeInput.setAttribute("min", minDateTime);
      }

      if (editInput) {
        editInput.setAttribute("min", minDateTime);
      }
    }
  });

  document.addEventListener('DOMContentLoaded', function() {
    try {
      const calendarEl = document.getElementById('calendar');
      const phpEvents = <?php echo json_encode($eventsData, JSON_UNESCAPED_UNICODE); ?>;
      let allEventsToSave = [];
      let modifiedEvents = [];

      function markEventAsModified(event) {
        const index = modifiedEvents.findIndex(e => e.id === event.id);
        if (index !== -1) {
          modifiedEvents[index] = event;
        } else {
          modifiedEvents.push(event);
        }
      }

      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        editable: true,
        events: phpEvents,
        customButtons: {
          createDestination: {
            text: 'Add Destination',
            click: function() {
              $('#createEventModal').modal('show');
            }
          }
        },
        headerToolbar: {
          left: 'prev,today,next',
          center: 'title',
          right: 'createDestination,dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
          today: 'Today',
          month: 'Month',
          week: 'Week',
          day: 'Day'
        },
        eventClick: function(info) {
          $('#eventId').val(info.event.id);
          $('#eventTitle').text(info.event.title);
          $('#eventDateTime').val(moment(info.event.start).format('YYYY-MM-DDTHH:mm'));
          const details = destinationDetails[info.event.title];
          if (details) {
            $('#destinationImage').attr('src', details.image);
            $('#destinationDescription').text(details.description);
            $('#readMoreLink').attr('href', details.readMore);
          } else {
            $('#destinationImage').attr('src', '');
            $('#destinationDescription').text('No description available.');
            $('#readMoreLink').attr('href', '#');
          }
          $('#editEventModal').modal('show');
        },
        eventDrop: function(info) {
          if (moment(info.event.start).isBefore(tripStartDate)) {
            alert('❌ You cannot schedule the event before the trip start date.');
            info.revert();
            return;
          }
          markEventAsModified({
            id: info.event.id,
            title: info.event.title,
            start: info.event.startStr,
            end: info.event.endStr
          });
        },
        eventResize: function(info) {
          if (moment(info.event.start).isBefore(tripStartDate)) {
            alert('❌ You cannot change the event timing before the trip start date.');
            info.revert();
            return;
          }
          markEventAsModified({
            id: info.event.id,
            title: info.event.title,
            start: info.event.startStr,
            end: info.event.endStr
          });
        },
        eventContent: function(arg) {
          // Custom event rendering for better mobile display
          if (window.innerWidth < 768) {
            return {
              html: `<div style="font-size:0.8em;padding:2px;white-space:normal;">${arg.event.title}</div>`
            };
          }
        },
        eventContent: function(arg) {
          return {
            html: `<div class="fc-event-title-container">
                    <div class="fc-event-title">${arg.event.title}</div>
                  </div>`
          };
        },
        dayMaxEventRows: 4, 
        dayMaxEvents: true 
      
      });

      calendar.render();

      $('#saveNewEvent').click(function() {
        let newDateTime = $('#newEventDateTime').val();
        let destination = $('#eventDestination').val();

        if (!newDateTime || !destination) {
          alert('Please fill in all fields.');
          return;
        }

        const eventStart = moment(newDateTime);
        if (eventStart.isBefore(tripStartDate)) {
          alert('❌ You cannot add a destination before the trip start date.');
          return;
        }

        let newEvent = {
          id: String(Date.now()),
          title: destination,
          start: eventStart.format('YYYY-MM-DD HH:mm:ss'),
          end: eventStart.clone().add(2, 'hours').format('YYYY-MM-DD HH:mm:ss'),
          backgroundColor: '#d0a84b',
          borderColor: '#b88d3f'
        };

        allEventsToSave.push(newEvent);
        calendar.addEvent(newEvent);
        $('#createEventModal').modal('hide');
        $('#createEventForm')[0].reset();
      });

      $('#saveChanges').click(function() {
        let eventId = $('#eventId').val();
        let newDateTime = $('#eventDateTime').val();
        
        if (moment(newDateTime).isBefore(tripStartDate)) {
          alert('❌ You cannot change the destination date to be before the start of the trip.');
          return;
        }

        let event = calendar.getEventById(eventId);
        if (event) {
          event.setStart(moment(newDateTime).toDate());
          event.setEnd(moment(newDateTime).add(2, 'hours').toDate());
          markEventAsModified({
            id: event.id,
            title: event.title,
            start: event.startStr,
            end: event.endStr
          });
        }
        $('#editEventModal').modal('hide');
      });

      $('#deleteEvent').click(function() {
        if (confirm('Do you want to delete this destination?')) {
          let eventId = $('#eventId').val();
          calendar.getEventById(eventId).remove();
          modifiedEvents = modifiedEvents.filter(e => e.id !== eventId);
          allEventsToSave = allEventsToSave.filter(e => e.id !== eventId);
          $('#editEventModal').modal('hide');
        }
      });

      $('#saveAllChanges').click(function() {
        const allEvents = calendar.getEvents();
        const formattedEvents = allEvents.map(e => ({
          id: e.id,
          title: e.title,
          start: moment(e.start).format('YYYY-MM-DD HH:mm:ss'),
          end: e.end ? moment(e.end).format('YYYY-MM-DD HH:mm:ss') : null
        }));

        fetch('saveAllEvents.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            schedule_id: '<?php echo $schedule_id; ?>',
            events: formattedEvents
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            alert('Your changes have been saved successfully ✅');
          } else {
            alert('Failed to save: ' + data.message);
          }
        })
        .catch(err => {
          console.error(err);
          alert('An error occurred while saving.');
        });
      });

      // Handle window resize for better mobile experience
      window.addEventListener('resize', function() {
        calendar.updateSize();
      });

    } catch (e) {
      console.error('Error initializing calendar:', e);
      alert('An error occurred while loading the calendar. Please refresh the page.');
    }
  });
</script>
</body>
</html>