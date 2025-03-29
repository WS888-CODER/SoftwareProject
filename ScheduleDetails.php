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
$gap = 5;

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
?>
<!DOCTYPE html>
<html lang="ar">

<head>
  <meta charset="UTF-8">
  <title>FARRAH | Schedule Details</title>
  <link rel="icon" type="image/png" href="images/logo.png">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="css/ScheduleDetails.css">
  <link rel="stylesheet" href="css/HomeStyle.css">


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



  <script>
    const slidebarBtn = document.getElementById('slidebarBtn');
    const slidebar = document.getElementById('slidebar');

    slidebarBtn.addEventListener('click', () => {
      slidebar.classList.toggle('active');
      slidebarBtn.classList.toggle('is-active');
    });
  </script>
<script>
const phpEvents = <?php echo json_encode($eventsData, JSON_UNESCAPED_UNICODE); ?>;
const destinationDetails = <?php echo json_encode($destData, JSON_UNESCAPED_UNICODE); ?>;
</script>
<script>
  const phpEvents = <?php echo json_encode($eventsData, JSON_UNESCAPED_UNICODE); ?>;
  const destinationDetails = <?php echo json_encode($destData, JSON_UNESCAPED_UNICODE); ?>;
  const tripDuration = <?php echo json_encode($duration); ?>; // Trip duration from database
</script>
<script>
$(document).ready(function () {
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

  $('#calendar').fullCalendar({
    selectable: true,
    editable: true,
    eventLimit: true,
    events: phpEvents,
    customButtons: {
      createDestination: {
        text: 'Add Destination',
        click: function () {
          $('#createEventModal').modal('show');
        }
      }
    },
    header: {
      left: 'prev,today,next',
      center: 'title',
      right: 'createDestination,month,agendaWeek,agendaDay'
    },
    buttonText: {
      today: 'Today', month: 'Month', week: 'Week', day: 'Day'
    },
    eventClick: function (event) {
      $('#eventId').val(event.id);
      $('#eventTitle').text(event.title);
      $('#eventDateTime').val(moment(event.start).format('YYYY-MM-DDTHH:mm'));
      const details = destinationDetails[event.title];
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
    eventDrop: function (event) {
      markEventAsModified({
        id: event.id,
        title: event.title,
        start: event.start.format('YYYY-MM-DD HH:mm:ss'),
        end: event.end ? event.end.format('YYYY-MM-DD HH:mm:ss') : null
      });
    },
    eventResize: function (event) {
      markEventAsModified({
        id: event.id,
        title: event.title,
        start: event.start.format('YYYY-MM-DD HH:mm:ss'),
        end: event.end ? event.end.format('YYYY-MM-DD HH:mm:ss') : null
      });
    }
  });

  $('#saveNewEvent').click(function () {
    let newDateTime = $('#newEventDateTime').val();
    let destination = $('#eventDestination').val();

    if (newDateTime && destination) {
      let newEvent = {
        id: String(Date.now()),
        title: destination,
        start: moment(newDateTime).format('YYYY-MM-DD HH:mm:ss'),
        end: moment(newDateTime).add(2, 'hours').format('YYYY-MM-DD HH:mm:ss'),
        backgroundColor: '#d0a84b',
        borderColor: '#b88d3f'
      };

      allEventsToSave.push(newEvent);
      $('#calendar').fullCalendar('renderEvent', newEvent, true);
      $('#createEventModal').modal('hide');
      $('#createEventForm')[0].reset();
    } else {
      alert('Please fill all fields.');
    }
  });

  $('#saveChanges').click(function () {
    let eventId = $('#eventId').val();
    let newDateTime = $('#eventDateTime').val();

    let event = $('#calendar').fullCalendar('clientEvents', eventId)[0];
    if (event) {
      event.start = moment(newDateTime);
      event.end = moment(newDateTime).add(2, 'hours');
      $('#calendar').fullCalendar('updateEvent', event);
      markEventAsModified({
        id: event.id,
        title: event.title,
        start: event.start.format('YYYY-MM-DD HH:mm:ss'),
        end: event.end.format('YYYY-MM-DD HH:mm:ss')
      });
    }

    $('#editEventModal').modal('hide');
  });

  $('#saveAllChanges').click(function () {
    const allEvents = $('#calendar').fullCalendar('clientEvents');
    const formattedEvents = allEvents.map(e => ({
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

  $('#deleteEvent').click(function () {
    if (confirm('Do you want to delete this destination?')) {
      let eventId = $('#eventId').val();
      $('#calendar').fullCalendar('removeEvents', eventId);
      $('#editEventModal').modal('hide');
    }
  });
});
</script>
</body>


</html>
