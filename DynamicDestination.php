<?php
include 'db_connection.php'; // Include database connection
// Enable error reporting for debugging (remove or adjust in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Get destination ID from query string
$destinationID = isset($_GET['DestinationID']) ? $_GET['DestinationID'] : '';

$query = "SELECT * FROM destination WHERE DestinationID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $destinationID); // "s" because DestinationID is VARCHAR
$stmt->execute();
$result = $stmt->get_result();
$destination = $result->fetch_assoc();


if (!$destination) {
    die("Destination not found!");
}

// Fetch destination images
$queryImages = "SELECT DestinationImage FROM destinationimage WHERE DestinationID = ?";
$stmt = $conn->prepare($queryImages);
$stmt->bind_param("i", $destinationID);
$stmt->execute();
$resultImages = $stmt->get_result();
$images = $resultImages->fetch_all(MYSQLI_ASSOC);

// Fetch things to do
$queryThings = "SELECT ThingsToDo FROM thingstodo WHERE DestinationID = ?";
$stmt = $conn->prepare($queryThings);
$stmt->bind_param("i", $destinationID);
$stmt->execute();
$resultThings = $stmt->get_result();
$thingsToDo = $resultThings->fetch_all(MYSQLI_ASSOC);

// Fetch reviews with user names
$queryReviews = "SELECT r.*, u.Name FROM review r 
                 JOIN user u ON r.UserID = u.UserID 
                 WHERE r.DestinationID = ?";
$stmt = $conn->prepare($queryReviews);
$stmt->bind_param("s", $destinationID);
$stmt->execute();
$resultReviews = $stmt->get_result();
$reviews = $resultReviews->fetch_all(MYSQLI_ASSOC);
$totalReviews = count($reviews);
$averageRating = $totalReviews ? array_sum(array_column($reviews, 'Rating')) / $totalReviews : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($destination['Name']); ?></title>
  <link rel="stylesheet" href="css/DestnitionStyle.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <style>
    body,
    html {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: url('<?php echo htmlspecialchars($destination['BackgroundPhoto']); ?>') no-repeat center center/cover;
      background-attachment: fixed;
      color: white;
    }
    .star {
  cursor: pointer;
  font-size: 2em;
  color: gray;
}

.star.selected {
  color: yellow;
}
  </style>
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
      <li><a href="MySchedules.html">My schedules</a></li>
      <li><a class="sign-out" href="index.html">Sign out </a></li>
    </ul>
  </div>

  <!-- header -->
  <header>
    <div class="header-left">
      <div class="logo">Farrah</div>
      <div class="search-icon" id="searchIcon">
        <svg viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8" stroke="#fff" stroke-width="2" fill="none" />
          <line x1="16" y1="16" x2="22" y2="22" stroke="#fff" stroke-width="2" />
        </svg>
      </div>
    </div>
    <nav>
      <ul>
        <li><a href="Home.html">Home</a></li>
        <li><a href="Destnition.html">Destinations</a></li>
      </ul>
    </nav>
  </header>

  <div class="background">
    <div class="overlay"></div>
  </div>

  <div class="destination-header">
    <div class="destination-info">
      <h1><?php echo htmlspecialchars($destination['Name']); ?></h1><br>
      <p><strong><?php echo htmlspecialchars($destination['Sentence']); ?></strong></p>
    </div>
    <div class="destination-images photo-container1">
      <?php
$i = 1; // Initialize counter

foreach ($images as $img) { ?>
      <img class="review-photo1" src="<?php echo htmlspecialchars($img['DestinationImage']); ?>" alt="Destination Image" onclick="openModal('photo<?php echo $i; ?>')">
    <?php
    $i++; // Increment counter
}
?>
    </div>
  </div>
  <br><br><br><br><br><br><br><br><br><br><br><br><br>

  <div class="black-container">
    <h2 style="color: #f5b90d; text-align: center; text-align: left;">Weather Condition</h2> <br><br>

    <div class="weather-cards">
      <div class="weather-card">
        <i class="fas fa-temperature-high"></i>
        <p><strong>Temperature:</strong> <span id="temperature"></span>°C</p>
      </div>
      <div class="weather-card">
        <i class="fas fa-hand-holding-water"></i>
        <p><strong>Feels Like:</strong> <span id="feelsLike"></span>°C</p>
      </div>
      <div class="weather-card">
        <i class="fas fa-cloud-sun"></i>
        <p><strong>Condition:</strong> <span id="condition"></span></p>
      </div>
      <div class="weather-card">
        <i class="fas fa-tint"></i>
        <p><strong>Humidity:</strong> <span id="humidity"></span>%</p>
      </div>
      <div class="weather-card">
        <i class="fas fa-wind"></i>
        <p><strong>Wind Speed:</strong> <span id="windSpeed"></span> m/s</p>
      </div>
      <div class="weather-card">
        <i class="fas fa-eye"></i>
        <p><strong>Visibility:</strong> <span id="visibility"></span> km</p>
      </div>
      <div class="weather-card">
        <i class="fas fa-sun"></i>
        <p><strong>Sunrise:</strong> <span id="sunrise"></span></p>
      </div>
      <div class="weather-card">
        <i class="fas fa-cloud-sun"></i>
        <p><strong>Sunset:</strong> <span id="sunset"></span></p>
      </div>

    </div>

    <h2 class="last-updated">Last Updated: <span id="lastUpdated"></span></h2>
  </div>

  <br><br><br><br>

  <div class="black-container">
    <h2 style="color: #f5b90d;">About <?php echo htmlspecialchars($destination['Name']); ?></h2> <br>
    <p><?php echo htmlspecialchars($destination['Description']); ?></p>
  </div>
  <br><br>

  <div class="info-map-container">
    <div class="black-container info">
      <h2 style="color: #f5b90d;">More Information</h2> <br>
      <ul>
        <li><strong>City:</strong> <?php echo htmlspecialchars($destination['City']); ?> </li>
        <li><strong>Region:</strong> <?php echo htmlspecialchars($destination['Region']); ?></li>
        <li><strong>Type:</strong> <?php echo htmlspecialchars($destination['Type']); ?></li>
        <li><strong>Time needed:</strong> <?php echo htmlspecialchars($destination['TimeNeeded']); ?></li>
        <li><strong>Things to do:</strong>
          <?php foreach ($thingsToDo as $thing) { ?>
            <pre><?php echo htmlspecialchars($thing['ThingsToDo']); ?></pre>
        <?php } ?>

      </ul>
    </div>

    <div class="black-container map-container">
      <h2 class="map-title">
        <pre>Location                                                               </pre>
      </h2><br>
      <div id="map" class="map-preview"></div>
      <a href="#" id="enlargeMapLink" class="enlarge-map-link">Click to Enlarge Map</a>
    </div>

    <!-- Modal for Enlarge View -->
    <div id="mapModal" class="map-modal">
      <div class="map-modal-content">
        <span id="closeMapModal" class="map-modal-close">&times;</span>
        <div id="modalMap" class="modal-map"></div>
      </div>
    </div>

  </div>
  <br><br>
  <div class="black-container reviews-container">
    <div class="reviews">
      <h2 style="color: #f5b90d;">Reviews of Visitors</h2><br>
      <div class="ratings">
        <p>⭐ <?php echo number_format($averageRating, 1); ?> (<?php echo $totalReviews; ?> reviews)</p>
      </div>
      <?php foreach ($reviews as $review) { ?>
      <div class="review">
        <p><strong style="color: #f5b90d;"><?php echo htmlspecialchars($review['Name']); ?></strong><br><?php echo str_repeat('★', $review['Rating']) . str_repeat('☆', 5 - $review['Rating']); ?><br> <span class="date"><?php echo htmlspecialchars($review['Date']); ?></span></p>
        <p><?php echo htmlspecialchars($review['Comment']); ?></p><br>
        <div class="photo-container">
          <?php
          ;
$videoIndex = 1; // ✅ New counter for videos

$queryReviewMedia = "SELECT ReviewImage FROM reviewimage WHERE ReviewID = ?";
$stmt = $conn->prepare($queryReviewMedia);
$stmt->bind_param("s", $review['ReviewID']);
$stmt->execute();
$resultMedia = $stmt->get_result();
while ($media = $resultMedia->fetch_assoc()) {
    $filePath = htmlspecialchars($media['ReviewImage']);
    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION)); // ✅ Get file type

   if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) { 
    // Image - Use the existing $ii variable
    echo "<img class='review-photo reviewPhoto' src='$filePath' onclick=\"openModal('photo$i')\">";
    $i++; 
} elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg'])) {
    // Video - Use new $videoIndex variable
    echo "<video src='$filePath' class='review-video reviewPhoto' onclick=\"openModal('video$videoIndex')\"></video>";
    $videoIndex++; 
}

}
?>

        </div>
      </div>
     <?php } ?>
</div>
    <!-- Modal Structure -->
    <div id="modal" class="modal">
      <span class="close" onclick="closeModal()">&times;</span>
      <img id="modal-content" class="modal-content">
      <video id="modal-video" class="modal-content" controls></video>
    </div>

    <!-- review form-->
    <div class="add-review">
       <form action="Destination.php?DestinationID=<?php echo $destinationID; ?>" method="post" enctype="multipart/form-data">
    <h2 style="color: #f5b90d;">Add a Review</h2><br>
    <p>add your rating:</p>
    <div class="star-rating">
    <span class="star" data-value="1">&#9733;</span>
    <span class="star" data-value="2">&#9733;</span>
    <span class="star" data-value="3">&#9733;</span>
    <span class="star" data-value="4">&#9733;</span>
    <span class="star" data-value="5">&#9733;</span>
  </div><br>
    <!-- Hidden input to store the chosen rating -->
    <input type="hidden" name="rating" id="rating" value="0">
    
    <p>add your comment:</p>
    <textarea name="comment" class="comment" placeholder="Write your comment"></textarea>
    
    <p>insert photos:</p>
    <!-- The input name 'photo' here (adjust if multiple images are allowed) -->
    <input type="file" name="photo">
    
    <button type="submit" id="submit">Submit</button>
  </form>

    </div>
  </div>

  <script src="DestinationScript.js"></script>


  <br><br>
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

  <script src="DestinationScript.js"></script>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

  <script>
    const slidebarBtn = document.getElementById('slidebarBtn');
    const slidebar = document.getElementById('slidebar');
    slidebarBtn.addEventListener('click', () => {
      slidebar.classList.toggle('active');
      slidebarBtn.classList.toggle('is-active');
    });

    const apiKey = "63ecd3ddb653464781ede6b7c4ed1e33";
    const latitude = <?php echo $destination['Latitude']; ?>;
    const longitude = <?php echo $destination['Longitude']; ?>;
    const weatherUrl = `https://api.weatherbit.io/v2.0/current?lat=${latitude}&lon=${longitude}&key=${apiKey}`;

    function fetchWeather() {
      fetch(weatherUrl)
        .then(response => response.json())
        .then(data => {
          const weather = data.data[0];
          document.getElementById("temperature").innerText = weather.temp;
          document.getElementById("feelsLike").innerText = weather.app_temp;
          document.getElementById("condition").innerText = weather.weather.description;
          document.getElementById("humidity").innerText = weather.rh;
          document.getElementById("windSpeed").innerText = weather.wind_spd;
          document.getElementById("visibility").innerText = weather.vis;
          document.getElementById("sunrise").innerText = weather.sunrise;
          document.getElementById("sunset").innerText = weather.sunset;

          // Update last updated time
          document.getElementById("lastUpdated").innerText = new Date().toLocaleTimeString();
        })
        .catch(error => {
          console.error("Error fetching weather data:", error);
          document.getElementById("weather-info").innerText = "Failed to load weather data.";
        });
    }

    // Fetch weather initially
    fetchWeather();

    // Update every 5 minutes (300,000 milliseconds)
    setInterval(fetchWeather, 60000);

    // Initialize the map
    var map = L.map('map').setView([latitude, longitude], 13); // Coordinates for Heet Cave

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add a marker for Heet Cave
    var marker = L.marker([latitude, longitude]).addTo(map)
      .bindPopup("<b><?php echo htmlspecialchars($destination['Name']); ?></b><br><?php echo htmlspecialchars($destination['City']); ?>, Saudi Arabia.")
      .openPopup();

    // When the map is clicked, show the link to enlarge the map
    map.on('click', function () {
      document.getElementById("enlargeMapLink").style.display = "inline";
    });

    // Enlarge map when the link is clicked
    document.getElementById("enlargeMapLink").addEventListener("click", function (event) {
      event.preventDefault();
      // Hide the small map
      document.getElementById("map").style.display = "none";

      // Show the modal
      document.getElementById("mapModal").style.display = "block";

      // Initialize modal map
      var modalMap = L.map('modalMap').setView([27.5000, 41.7000], 13); // Same coordinates

      // Add OpenStreetMap tiles to the modal map
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(modalMap);

      // Add a marker for Heet Cave in the modal map
      L.marker([latitude, longitude]).addTo(modalMap)
        .bindPopup("<b><?php echo htmlspecialchars($destination['Name']); ?></b><br><?php echo htmlspecialchars($destination['City']); ?>, Saudi Arabia.")
        .openPopup();
    });

    // Close the modal when the user clicks the close button
    document.getElementById("closeMapModal").addEventListener("click", function () {
      // Hide the modal
      document.getElementById("mapModal").style.display = "none";

      // Show the small map again
      document.getElementById("map").style.display = "block";
    });
    
    
    document.addEventListener('DOMContentLoaded', function () {
      const stars = document.querySelectorAll('.star');
      const ratingInput = document.getElementById('rating');

      // Ensure the input starts at 0.
      ratingInput.value = 0;

      stars.forEach(star => {
        star.addEventListener('click', function () {
          // Remove 'selected' class from all stars.
          stars.forEach(s => s.classList.remove('selected'));
          // Add 'selected' only to the clicked star.
          this.classList.add('selected');
          // Update hidden input with the clicked star's value.
          ratingInput.value = this.getAttribute('data-value');
          console.log("Rating set to:", ratingInput.value);
        });
      });
    });
  </script>


</body>

</html>