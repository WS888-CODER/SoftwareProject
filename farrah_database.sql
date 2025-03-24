-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 24, 2025 at 12:36 AM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `farrah_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `contains`
--

CREATE TABLE `contains` (
  `ScheduleID` varchar(255) NOT NULL,
  `DestinationID` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `destination`
--

CREATE TABLE `destination` (
  `DestinationID` varchar(255) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Sentence` varchar(700) NOT NULL,
  `Latitude` float NOT NULL,
  `Longitude` float NOT NULL,
  `City` varchar(100) NOT NULL,
  `Region` varchar(100) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Description` varchar(700) NOT NULL,
  `TimeNeeded` varchar(20) NOT NULL,
  `BackgroundPhoto` varchar(800) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `destination`
--

INSERT INTO `destination` (`DestinationID`, `Name`, `Sentence`, `Latitude`, `Longitude`, `City`, `Region`, `Type`, `Description`, `TimeNeeded`, `BackgroundPhoto`) VALUES
('1', 'Heet Cave', 'Your trip to a cave that dates back thousands of years', 24.4706, 44.4136, 'Al-Jubail', 'Center', 'Mountainous', 'Heet Cave, also known as Ein Heet Cave, is located in Al-Jubail Mountain, about 40 kilometers southeast of Riyadh. It\'s famous for its stunning limestone formations and an underground lake with crystal-clear waters. The cave is a natural wonder and a popular destination for adventurers and explorers. Heet Cave has a rich history. It was discovered by local Bedouins and later explored by geologists in 1938 at the request of King Abdulaziz Al-Saud. The cave is known for its anhydrite rocks, which were first discovered during this exploration. Historically, the cave served as a water source for passing convoys due to its high water levels.', '5-6 Hours', 'images/HeetCave.jpg'),
('10', 'Rawdat Khuraim', 'An oasis of greenery in the heart of the desert, known as the \"King’s Forest.\"', 25.375, 47.263, 'Riyadh', 'center', 'oasis', 'Rawdat Khuraim is a natural reserve filled with acacia trees and seasonal water pools, making it a popular spot for picnics, birdwatching, and nature walks. It is especially lush during the rainy season.', '2-3 hours', 'images/RawdatKhuraim.jpg'),
('11', 'Wadi Hanifa', 'A historic valley turned into a scenic retreat, blending nature with Riyadh’s urban landscape.', 24.7333, 46.5833, 'Riyadh', 'center', 'mountainous', 'Wadi Hanifa is a 120 km-long valley that runs through Riyadh, featuring palm groves, walking trails, and water channels. Once a neglected area, it has been transformed into a recreational spot for locals and visitors.', '1-2 hours', 'images/WadiHanifa.jpeg'),
('12', 'Wadi Al-Ramah', 'One of the longest valleys in the Arabian Peninsula, stretching across multiple regions', 26.1, 44.4, 'Qassim', 'center', 'sandy', 'Wadi Al-Ramah extends for over 600 km, flowing from Medina to the eastern parts of Qassim. It has played a crucial role in historical trade and settlement patterns in the region.', ' 3-5 hours', 'images/WadiAlRamah.jpeg'),
('13', 'Wadi Namar', 'A hidden gem in Riyadh, featuring a tranquil lake and scenic cliffs.', 24.579, 46.6223, 'Riyadh', 'center', 'mountainous', 'Wadi Namar is a popular recreational spot in Riyadh, known for its artificial waterfall, walking trails, and peaceful atmosphere. It’s an ideal place for families and nature lovers.', '1-2 hours', 'images/WadiNamar.jpg'),
('14', 'Raghadan Forest', ' A lush green escape in the mountains of Al Baha, offering cool weather and breathtaking views.', 20.0264, 41.4438, 'Al Baha', 'center', 'oasis', 'Raghadan Forest is a dense woodland with picnic areas, hiking trails, and a scenic overlook of the surrounding mountains. It is one of the few places in Saudi Arabia with a cool and misty climate.', '2-4 hours', 'images/RaghadanForest.jpg'),
('15', 'Al-Habla, Abha', 'A historical village perched on a dramatic cliffside, accessible only by cable car.', 18.19, 42.4861, 'Abha', 'south', 'mountainous', 'Al-Habla is an ancient village located on a steep escarpment, originally inhabited by locals who used rope ladders to access their homes. It is now a popular tourist site with breathtaking views.', '2-3 hours', 'images/JibalAlHabla.jpg'),
('16', 'Farasan Islands', 'A paradise of white sandy beaches and coral reefs, rich in marine life.', 16.7, 42.1333, 'Jazan', 'west', 'marine', 'The Farasan Islands are an archipelago in the Red Sea, offering crystal-clear waters, diverse marine ecosystems, and ancient ruins. It’s a haven for divers, snorkelers, and history enthusiasts.', '1-2 days', 'images/FarasanIslands.jpg'),
('17', 'Jabal Salil, Qassim', ' A striking mountain range with unique rock formations and scenic desert views.', 26, 44, 'Qassim', 'center', 'mountainous', 'Jabal Salil is a lesser-known natural wonder featuring rugged terrain, ideal for hikers and off-road adventurers. The site provides panoramic views of the surrounding desert.', '3-5 hours', 'images/JabalSalilQassim.jpg'),
('18', 'Salbookh Dam', 'A peaceful retreat surrounded by hills, perfect for camping and stargazing', 25.2867, 46.1003, 'Riyadh', 'center', 'rocky', 'Salbookh Dam is a water reservoir in the outskirts of Riyadh, attracting visitors for its serene ambiance and natural beauty. It’s a great spot for picnics, birdwatching, and photography.', '2-3 hours', 'images/SalbookhDam.jpg'),
('19', 'Jibal Al-Louz, Tabuk', 'A majestic mountain range known for its rare snowfall and ancient rock art.', 28.6544, 35.2856, 'Tabuk', 'north', 'mountainous', 'Jibal Al-Louz is one of the highest peaks in Saudi Arabia, occasionally covered in snow during winter. The region is also home to prehistoric petroglyphs and breathtaking landscapes.', '4-6 hours', 'images/JibalAlLouz.jpg'),
('2', 'Neom', 'Embark on an unforgettable diving trip', 28, 35, 'Tabuk', 'North', 'Marine', 'NEOM is part of Saudi Arabia’s Vision 2030, it is an ambitious futuristic mega-project situated along the Red Sea coast in the northwest Tabuk Province of Saudi Arabia. It is designed to redefine urban living by integrating cutting-edge technology, sustainability, and an innovative lifestyle. NEOM aims to become a global hub for tourism, innovation, and economic growth as part of Saudi Arabia\'s Vision 2030 initiative.', 'several days', 'images/ANeom.jpg'),
('20', 'Edge of the World', ' A dramatic cliffside offering one of the most iconic views in Saudi Arabia', 24.9526, 45.9943, 'Riyadh', 'center', 'mountainous', 'The Edge of the World is a towering escarpment that provides panoramic views of the vast desert below. It is a favorite destination for hikers and adventurers seeking a thrilling experience.', '4-6 hours', 'images/EdgeOfTheWorld.jpg'),
('3', 'Al-Ahsa Oasis', 'Experience the lush tranquility of Al-Ahsa Oasis, the world\'s largest oasis', 25.3833, 49.6, 'Al-Ahsa', 'East', 'Oasis', 'A UNESCO World Heritage site, Al-Ahsa has been a thriving center of civilization for over 5,000 years. The oasis played a crucial role in trade and agriculture in the Arabian Peninsula, supplying dates and fresh water to ancient travelers.', '1-2 days', 'images/AhsaOasis.jpg'),
('4', 'Nafud Al-Zulfi', 'Embark on a thrilling desert adventure in Nafud Al-Zulfi', 26.3, 44.8, 'Riyadh', 'Center', 'Sandy', 'Nafud Al-Zulfi has archaeological evidence of human activity dating back to the Stone Age. The region was historically part of major caravan routes, and ancient petroglyphs can be found in the surrounding desert.', '3-4 Hours', 'images/Alzelfi.jpg'),
('5', 'Al-Soudah', 'Discover the cool, verdant landscapes of Al-Soudah, a high-altitude haven offering breathtaking views', 18.2167, 42.5, 'Abha', 'South', 'Mountainous', 'Part of Asir National Park, Al-Soudah has long been a cultural hub for the Asir people. Known for its unique architecture and rich traditions, the region has been inhabited for centuries and played a key role in the spice trade routes.', '1-2 days', 'images/AbhaAlsodh.jpg'),
('6', 'Aja Mountains', 'Explore the rugged beauty of the Aja Mountains, where unique rock formations and diverse wildlife create an unforgettable experience', 27.5, 41.7, 'Ha’il', 'North', 'Rocky', 'The Aja Mountains have been designated as an Important Plant Area and an Important Bird and Biodiversity Area. The region is steeped in ancient history, with inscriptions and petroglyphs that date back thousands of years, making it an important archaeological site in Saudi Arabia.', 'A full day', 'images/Hail.jpg'),
('7', 'Antarah and Abla Rock', ' A legendary rock formation tied to the love story of Antarah and Abla, echoing the poetry of the past.', 26.8926, 42.1347, 'Qassim', 'Center', 'Rocky', 'Antarah and Abla Rock is a historical site associated with the famed pre-Islamic poet Antarah ibn Shaddad. It is believed to be the place where Antarah met his beloved Abla, inspiring his renowned love poems. The site features striking rock formations, set amidst the desert landscape, making it a captivating location for history enthusiasts and poetry lovers.', '1-2 hours', 'images/AntarahRock.jpg'),
('8', 'Al-Summan Desert in Najd', ' A vast desert of golden dunes and limestone formations, offering a glimpse into Saudi Arabia’s untamed wilderness.', 26.5, 47, 'Najd', 'center', 'sandy', 'The Al-Summan Desert is a part of the larger Najd region, known for its rugged landscapes, rocky plateaus, and sweeping sand dunes. It is a remote and untouched expanse, popular among off-road adventurers and desert explorers.', '3-6 hours', 'images/AlSummanDesert.jpg'),
('9', 'Nafud Saad', 'A striking red sand desert, home to breathtaking dunes and ancient trade routes.', 25, 45.5, 'Riyadh', 'east', 'sandy', ' Nafud Saad is a part of the larger Nafud Desert, famous for its towering dunes and deep red sand. It has historical significance as a key passage for ancient caravans traveling across the Arabian Peninsula.', '2-4 hours', 'images/NafudSaadDesert.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `destinationimage`
--

CREATE TABLE `destinationimage` (
  `DestinationID` varchar(255) NOT NULL,
  `DestinationImage` varchar(800) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `destinationimage`
--

INSERT INTO `destinationimage` (`DestinationID`, `DestinationImage`) VALUES
('1', 'images/HeetCave1.jpg'),
('1', 'images/HeetCave2.jpg'),
('1', 'images/HeetCave3.jpg'),
('10', 'images/RawdatKhuraim1.jpg'),
('10', 'images/RawdatKhuraim2.jpg'),
('10', 'images/RawdatKhuraim3.jpg'),
('11', 'images/WadiHanifa1.jpg'),
('11', 'images/WadiHanifa2.jpg'),
('11', 'images/WadiHanifa3.jpg'),
('12', 'images/WadiAlRamah1.jpg'),
('12', 'images/WadiAlRamah2.jpg'),
('12', 'images/WadiAlRamah3.jpg'),
('13', 'images/WadiNamar1.jpg'),
('13', 'images/WadiNamar2.jpg'),
('13', 'images/WadiNamar3.jpg'),
('14', 'images/RaghadanForest1.jpg'),
('14', 'images/RaghadanForest2.jpg'),
('14', 'images/RaghadanForest3.jpg'),
('15', 'images/Habla1.jpg'),
('15', 'images/Habla2.jpg'),
('15', 'images/Habla3.jpg'),
('16', 'images/FarasanIslands1.jpg'),
('16', 'images/FarasanIslands2.jpg'),
('16', 'images/FarasanIslands3.jpg'),
('17', 'images/JabalSalilQassim1.jpg'),
('17', 'images/JabalSalilQassim2.jpg'),
('17', 'images/JabalSalilQassim3.jpg'),
('18', 'images/SalbookhDam1.jpg'),
('18', 'images/SalbookhDam2.jpg'),
('18', 'images/SalbookhDam3.jpg'),
('19', 'images/JibalAlLouz1.jpg'),
('19', 'images/JibalAlLouz2.jpg'),
('19', 'images/JibalAlLouz3.jpg'),
('2', 'images/Neom1.jpg'),
('2', 'images/Neom2.jpg'),
('2', 'images/Neom3.jpg'),
('20', 'images/EdgeOfTheWorld1.jpg'),
('20', 'images/EdgeOfTheWorld2.jpg'),
('20', 'images/EdgeOfTheWorld3.jpg'),
('3', 'images/Al-Ahsa1.jpg'),
('3', 'images/Al-Ahsa2.jpg'),
('3', 'images/Al-Ahsa3.jpg'),
('4', 'images/Nafud1.jpg'),
('4', 'images/Nafud2.jpg'),
('4', 'images/Nafud3.jpg'),
('5', 'images/Al-Soudah1.jpg'),
('5', 'images/Al-Soudah2.jpg'),
('5', 'images/Al-Soudah3.png'),
('6', 'images/Aja1.jpg'),
('6', 'images/Aja2.jpg'),
('6', 'images/Aja3.jpg'),
('7', 'images/Antarah1.jpg'),
('7', 'images/Antarah2.jpg'),
('7', 'images/Antarah3.jpg'),
('8', 'images/Al-Summan1.jpg'),
('8', 'images/Al-Summan2.jpg'),
('8', 'images/Al-Summan3.jpg'),
('9', 'images/NafudSaad1.jpg'),
('9', 'images/NafudSaad2.jpg'),
('9', 'images/NafudSaad3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `ReviewID` int(11) NOT NULL,
  `UserID` varchar(255) NOT NULL,
  `DestinationID` varchar(255) NOT NULL,
  `Rating` int(11) NOT NULL,
  `Comment` varchar(400) DEFAULT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`ReviewID`, `UserID`, `DestinationID`, `Rating`, `Comment`, `Date`) VALUES
(1, 'user_67ddee063cef7', '1', 5, 'An incredible adventure! The underground lake is mesmerizing, and the hike is both challenging and rewarding', '2023-11-29 09:39:33'),
(2, 'user_67ddee4cd52b2', '1', 5, 'Perfect for thrill-seekers and nature lovers. The limestone formations are a photographer\'s dream.', '2023-11-28 09:39:33'),
(3, 'user_67ddee999f957', '2', 5, 'A glimpse into the future! NEOM’s Red Sea combines luxury with sustainability in the most beautiful setting', '2025-02-15 09:45:18'),
(4, 'user_67ddeed800a0d', '2', 4, 'The water activities were fantastic! Snorkeling and diving in the Red Sea were unforgettable experiences.', '2025-02-12 09:45:18'),
(5, 'user_67ddef1e21548', '3', 4, 'Al-Ahsa Oasis is a lush paradise! The palm groves and natural springs are simply stunning', '2025-01-10 09:48:01'),
(6, 'user_67ddef9ba7376', '3', 4, 'Exploring Al-Qarah Mountain and Ibrahim Palace was a highlight. The oasis is rich in history and beauty', '2025-01-08 09:48:01'),
(7, 'user_67ddef52aa681', '4', 4, 'An exhilarating desert experience! The dune bashing and camel rides were unforgettable.', '2021-03-30 09:50:25'),
(8, 'user_67ddefde14f71', '4', 5, 'Camping under the stars in Nafud Al-Zulfi was magical. The desert landscape is awe-inspiring', '2025-03-11 09:50:25'),
(9, 'user_67ddf02bb0bb7', '5', 5, 'The cable car ride offers breathtaking views of the Asir Mountains. A refreshing escape from the heat', '2020-03-25 09:53:47'),
(10, 'user_67ddefde14f71', '5', 4, 'Hiking in Al-Soudah is a must. The cool climate and lush forests are a delight.', '2020-01-08 09:53:47'),
(11, 'user_67ddf071b2555', '6', 5, 'The Aja Mountains are a hiker\'s paradise. The unique rock formations are fascinating.', '2025-03-01 09:56:23'),
(12, 'user_67ddefde14f71', '6', 3, 'Camping here was a highlight of our trip. The night sky is incredibly clear', '2025-03-20 09:56:23'),
(13, 'user_67ddee063cef7', '7', 5, 'As a poetry lover, this place was magical! Standing where Antarah and Abla once met gave me goosebumps', '2025-03-23 06:39:05'),
(14, 'user_67ddee4cd52b2', '7', 5, 'A great historical site, but there’s not much around it. Still worth a visit for the story behind it!', '2025-03-23 06:39:05'),
(15, 'user_67ddee999f957', '8', 4, 'Absolutely breathtaking! The desert stretches endlessly, and the night sky is mesmerizing. Perfect for an off-road adventure!', '2025-03-23 06:39:05'),
(16, 'user_67ddeed800a0d', '8', 5, 'A great place for camping, but make sure to bring enough supplies. The landscape is beautiful but very remote.', '2025-03-23 06:39:05'),
(17, 'user_67ddef9ba7376', '9', 4, 'The red sand dunes are unreal! Had so much fun sandboarding. One of the best desert experiences in Saudi Arabia!', '2025-03-23 06:39:05'),
(18, 'user_67ddef9ba7376', '9', 5, 'Nice place for an adventure, but it gets extremely hot during the day. Best to visit in winter!', '2025-03-23 06:39:05'),
(19, 'user_67ddf02bb0bb7', '10', 5, 'A hidden oasis! The greenery is so refreshing in the middle of the desert. Perfect for a family picnic', '2025-03-23 06:39:05'),
(20, 'user_67ddf02bb0bb7', '10', 5, 'Loved the nature and the fresh air. It’s a great escape from the city, but it can get crowded on weekends', '2025-03-23 06:39:05'),
(21, 'user_67ddf071b2555', '11', 4, 'Such a peaceful place for an evening walk. The water channels make it feel like a little paradise in Riyadh', '2025-03-23 06:39:05'),
(22, 'user_67df8d8beeddb', '11', 5, 'The valley is massive! It feels like you’re stepping into history. Best explored after rainfall when there\'s water flow.', '2025-03-23 06:39:05'),
(23, 'user_67ddf02bb0bb7', '12', 4, 'Loved the waterfall! It’s a peaceful escape in the city. Perfect for a relaxing evening.', '2025-03-23 06:39:05'),
(24, 'user_67ddefde14f71', '12', 5, 'Beautiful lake and walking area, but sometimes it gets crowded. Best to visit in the morning.', '2025-03-23 06:39:05'),
(25, 'user_67ddee063cef7', '13', 5, 'It felt like I was in another country! So green and cool, even in the summer. Definitely coming back!', '2025-03-23 06:39:05'),
(26, 'user_67ddee4cd52b2', '13', 5, 'Amazing views! The mist makes it feel magical. Just beware of monkeys, they love to steal food!', '2025-03-23 06:39:05'),
(27, 'user_67ddee999f957', '14', 5, 'The cable car ride down was thrilling! The history of this village is fascinating.', '2025-03-23 06:39:05'),
(28, 'user_67ddef1e21548', '14', 3, 'Such a unique place! Loved the views, but wish there were more facilities for visitors', '2025-03-23 06:39:05'),
(29, 'user_67ddef1e21548', '15', 5, 'The beaches are so clean, and the water is crystal clear! Great for snorkeling and diving.', '2025-03-23 06:39:05'),
(30, 'user_67ddf02bb0bb7', '15', 4, 'A peaceful island getaway, but ferry schedules can be tricky. Plan ahead', '2025-03-23 06:39:05'),
(31, 'user_67ddefde14f71', '16', 4, 'If you love adventure, this place is for you! The rock formations are amazing', '2025-03-23 06:39:05'),
(32, 'user_67ddf071b2555', '16', 4, 'A bit of a hidden gem. Not many people, which makes it great for solitude and hiking', '2025-03-23 06:39:05'),
(33, 'user_67ddee4cd52b2', '17', 5, 'A nice spot for birdwatching and fishing. Super peaceful, but no shops nearby, so bring your own supplies.', '2025-03-23 06:39:05'),
(34, 'user_67ddee4cd52b2', '17', 4, 'Good for a day trip, but not much to do beyond enjoying nature', '2025-03-23 06:39:05'),
(35, 'user_67ddee999f957', '18', 5, 'Snow in Saudi Arabia? Yes! Seeing the white peaks in winter was unbelievable!', '2025-03-23 06:39:05'),
(36, 'user_67ddeed800a0d', '18', 3, 'Beautiful landscapes, but the drive up is a bit tricky. Make sure to have a good vehicle', '2025-03-23 06:39:05'),
(37, 'user_67ddef1e21548', '19', 5, 'The name says it all! The view is out of this world. Best visited during sunrise or sunset.', '2025-03-23 06:39:05'),
(38, 'user_67ddef9ba7376', '19', 5, 'The name says it all! The view is out of this world. Best visited during sunrise or sunset.', '2025-03-23 06:39:05'),
(39, 'user_67ddef52aa681', '20', 5, 'Amazing but gets really crowded on weekends. Bring plenty of water and go early!', '2025-03-23 06:39:05'),
(40, 'user_67ddefde14f71', '20', 4, 'Beautiful landscapes, but the drive up is a bit tricky. Make sure to have a good vehicle', '2025-03-23 06:39:05');

-- --------------------------------------------------------

--
-- Table structure for table `reviewimage`
--

CREATE TABLE `reviewimage` (
  `ReviewID` int(255) NOT NULL,
  `ReviewImage` varchar(800) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reviewimage`
--

INSERT INTO `reviewimage` (`ReviewID`, `ReviewImage`) VALUES
(1, 'images/HeetCaveReview1.jpg'),
(1, 'images/HeetCaveReview11.jpg'),
(2, 'images/HeetCaveReview2.mp4'),
(7, 'images/NafudReview1.jpg'),
(11, 'images/AjaReview1.jpg'),
(38, 'images/JibalAlLouzReview1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `thingstodo`
--

CREATE TABLE `thingstodo` (
  `DestinationID` varchar(255) NOT NULL,
  `ThingsToDo` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `thingstodo`
--

INSERT INTO `thingstodo` (`DestinationID`, `ThingsToDo`) VALUES
('1', 'Camel Riding'),
('1', 'Camping'),
('1', 'Cave Diving'),
('1', 'Hiking'),
('1', 'Photography'),
('1', 'Swimming'),
('10', 'Birdwatching (especially during migration season)\r\n'),
('10', 'Nature walks & hiking\r\n'),
('10', 'Photography of lush green landscapes\r\n'),
('10', 'Picnicking under acacia trees\r\n'),
('10', 'Seasonal camping'),
('11', 'Cycling through the scenic valley\r\n'),
('11', 'Enjoying a boat ride on the water channels\r\n'),
('11', 'Picnicking in the green spaces'),
('11', 'Strolling along the walking trails\r\n'),
('11', 'Visiting Diriyah (UNESCO World Heritage Site) nearby\r\n'),
('12', 'Birdwatching during seasonal floods\r\n'),
('12', 'Camping along the riverbanks'),
('12', 'Desert hiking & off-roading\r\n'),
('12', 'Discovering ancient ruins and historical landmarks\r\n'),
('12', 'Exploring the longest valley in the Arabian Peninsula\r\n'),
('13', 'Cycling on the designated trails'),
('13', 'Enjoying the artificial waterfall views\r\n\r\n'),
('13', 'Having a picnic by the water\r\n'),
('13', 'Photography of scenic cliffs & sunset views'),
('13', 'Walking along the lake promenade\r\n'),
('14', 'Camping in the cool climate\r\n'),
('14', 'Enjoying panoramic viewpoints\r\n'),
('14', 'Exploring the nearby Al-Baha city attractions\r\n'),
('14', 'Hiking in the misty mountains\r\n'),
('14', 'Visiting the hanging bridge and caves'),
('15', 'Enjoying breathtaking valley views\r\n'),
('15', 'Exploring the abandoned village ruins\r\n'),
('15', 'Hiking through the mountainous terrain\r\n'),
('15', 'Learning about the history of the Habala tribe'),
('15', 'Taking the cable car ride down the cliffside\r\n'),
('16', 'Birdwatching at the protected wildlife reserve'),
('16', 'Enjoying beach camping & fishing'),
('16', 'Exploring the coral reefs & marine life\r\n'),
('16', 'Snorkeling & diving in crystal-clear waters\r\n\r\n'),
('16', 'Visiting the ancient Ottoman Fort\r\n'),
('17', 'Exploring caves & hidden valleys\r\n'),
('17', 'Hiking and trekking through rocky terrain\r\n'),
('17', 'Off-road driving for adventure seekers'),
('17', 'Photography of stunning desert landscapes\r\n'),
('17', 'Rock climbing on unique formations\r\n'),
('18', 'Camping along the shores\r\n'),
('18', 'Enjoying the peaceful lake scenery\r\n'),
('18', 'Fishing and birdwatching\r\n'),
('18', 'Hiking in the surrounding hills'),
('18', 'Stargazing at night\r\n'),
('19', 'Camping with scenic views\r\n'),
('19', 'Enjoying snowfall in winter months\r\n'),
('19', 'Exploring ancient petroglyphs & inscriptions\r\n'),
('19', 'Hiking to the mountain summit\r\n'),
('19', 'Watching the sunset over the rugged peaks'),
('2', 'Discover Culture & Architecture'),
('2', 'Enjoy Desert'),
('2', 'Explore Wildlife & Eco-Tourism'),
('2', 'Sail & Enjoy Water Sports'),
('2', 'Sindalah Island'),
('2', 'Snorkeling & Diving'),
('20', 'Camping under the night sky\r\n'),
('20', 'Enjoying a 4x4 off-road adventure\r\n'),
('20', 'Hiking along the dramatic cliffs\r\n'),
('20', 'Taking panoramic desert photos\r\n'),
('20', 'Watching the breathtaking sunset from the edge'),
('3', 'Hike Al-Qarah Mountain'),
('3', 'Relax at Al Hasa Hot Springs'),
('3', 'Shop at Al Ahsa Souq'),
('3', 'Take a Heritage Tour'),
('3', 'Tour Ibrahim Palace'),
('3', 'Visit Jawatha Mosque'),
('4', 'Camp Under the Stars'),
('4', 'Capture Stunning Photos'),
('4', 'Experience Traditional Bedouin Hospitality'),
('4', 'Go on a Desert Safari'),
('4', 'Hike & Explore Desert Trails'),
('5', 'Attend the Al-Soudah Season Festival'),
('5', 'Hike in Juniper Forests'),
('5', 'Ride a Cable Car'),
('5', 'Try Paragliding'),
('5', 'Visit Cultural Villages'),
('5', 'Watch Wildlife'),
('6', 'Camp in Naturepre'),
('6', 'Discover Local Folklore'),
('6', 'Hike Scenic Trails'),
('6', 'Try Rock Climbing'),
('6', 'Watch Wildlife'),
('7', ' Camping & Stargazing'),
('7', 'Explore the Legendary Rock Formation'),
('7', 'Hiking & Climbing'),
('7', 'Photography & Sightseeing'),
('7', 'Read Antarah\'s Poetry'),
('7', 'Visit Nearby Historical Sites'),
('8', 'Camping under the stars\r\n'),
('8', 'Exploring ancient petroglyphs'),
('8', 'Off-road driving & dune bashing\r\n'),
('8', 'Photography of limestone formations\r\n'),
('8', 'Wildlife spotting (Arabian oryx & gazelles)\r\n'),
('9', ' Sandboarding & ATV riding\r\n'),
('9', 'Camping in the dunes'),
('9', 'Desert trekking\r\n'),
('9', 'Exploring ancient caravan routes\r\n'),
('9', 'Stargazing in the desert\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `tripscheduler`
--

CREATE TABLE `tripscheduler` (
  `ScheduleID` varchar(255) NOT NULL,
  `UserID` varchar(255) NOT NULL,
  `Date` datetime NOT NULL,
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` varchar(255) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `PhoneNumber` varchar(15) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Name`, `PhoneNumber`, `Email`, `Password`) VALUES
('user_67ddee063cef7', 'Tomas Worner', '+966573239038', 'Toams@gmail.com', '$2y$10$q/Wmgq0E7JWRKTlgDyKxkuGH9hTher/91Mth55qHuad8BmQBWPyOW'),
('user_67ddee4cd52b2', 'Ella Robenson', '+966553739214', 'Robenson@hotmail.com', '$2y$10$TDQgnOHOX.B.Z04h195g0u3IIPBz3HBJhjrRtBR0ykOEZUaqi9DW.'),
('user_67ddee999f957', 'Alex Johnson', '+966567335438', 'AleJohn@gmail.com', '$2y$10$OtxnbZvQ1PYFcpyC/9MQd.jmPXlQWUV8HlWD1XY7NSlyRhsXVJRDy'),
('user_67ddeed800a0d', 'Samantha Lee', '+966529933048', 'samantha@gmail.com', '$2y$10$wTBjVyqm2wTYuln04DdtM.iFCv5dHAU6JZHtdFCPBdLtQyw636OJe'),
('user_67ddef1e21548', 'Michael Brown', '+966563221038', 'mBrown@hotmail.com', '$2y$10$k0wGNZpGXJfFpelxiBx9c.ZZTVS3jwO.71ydKJS4mEJpJCKC/b2n2'),
('user_67ddef52aa681', 'Emily Davis', '+966555821088', 'Emily@gmail.com', '$2y$10$j/nRgnbYeSxDIPF1t09GneREGKrj9CxnlA6FhZB8kFDOvwIgbEJIu'),
('user_67ddef9ba7376', 'David Wilson', '+966573429044', 'David_Wilson@gmail.com', '$2y$10$D7zVtUhDsLXJYoKI58Y22eki0dYs.LPrHuz2O2WGAyCmHtxSJxLaW'),
('user_67ddefde14f71', 'Abdualziz Khalid', '+966577233668', 'AbdualzizKH@hotmail.com', '$2y$10$HcjuxIMMRiaAgwRXtbJ2t.avqfGQM/yBDxLP/sGGUCmhGzQ6KaMNC'),
('user_67ddf02bb0bb7', 'James Taylor', '+966556919033', 'JamTaylor@gmail.com', '$2y$10$CjD7IRUPWnOSgHQ3Whadq.DvUmUqIfaezyVLLufACrGliQVngnr72'),
('user_67ddf071b2555', 'Sara Mohammed', '+966553449838', 'Sara123@hotmail.com', '$2y$10$qq8dYP49tg.pDU4pJXftH.6DgqaPUgWsjvxP5qkcFwYVd9iKS.l4K'),
('user_67df8d8beeddb', 'esraa', '+966502234455', 'esraa@gmail.com', '$2y$10$3FHIQrcf3WLL4GehaiAW6.nDbs3QuoZICxVw84gD21U7aEFo9WT/O'),
('user_67e0612843494', 'rama', '+966889005443', 'alomair@gmail.com', '$2y$10$Spkiqytzm7kNw9rAwotYjOsV7AOww2SagxOhQjQ9al5eMnuW1onya');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contains`
--
ALTER TABLE `contains`
  ADD PRIMARY KEY (`ScheduleID`,`DestinationID`),
  ADD KEY `DestinationID` (`DestinationID`);

--
-- Indexes for table `destination`
--
ALTER TABLE `destination`
  ADD PRIMARY KEY (`DestinationID`);

--
-- Indexes for table `destinationimage`
--
ALTER TABLE `destinationimage`
  ADD PRIMARY KEY (`DestinationImage`),
  ADD KEY `DestinationID` (`DestinationID`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`ReviewID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `DestinationID` (`DestinationID`);

--
-- Indexes for table `reviewimage`
--
ALTER TABLE `reviewimage`
  ADD PRIMARY KEY (`ReviewImage`),
  ADD KEY `ReviewID` (`ReviewID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `ReviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `destinationimage`
--
ALTER TABLE `destinationimage`
  ADD CONSTRAINT `destinationimage_ibfk_1` FOREIGN KEY (`DestinationID`) REFERENCES `destination` (`DestinationID`) ON DELETE CASCADE;

--
-- Constraints for table `reviewimage`
--
ALTER TABLE `reviewimage`
  ADD CONSTRAINT `reviewimage_ibfk_1` FOREIGN KEY (`ReviewID`) REFERENCES `review` (`ReviewID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
