-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 23, 2025 at 03:55 AM
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
('2', 'Neom', 'Embark on an unforgettable diving trip', 28, 35, 'Tabuk', 'North', 'Marine', 'NEOM is part of Saudi Arabia’s Vision 2030, it is an ambitious futuristic mega-project situated along the Red Sea coast in the northwest Tabuk Province of Saudi Arabia. It is designed to redefine urban living by integrating cutting-edge technology, sustainability, and an innovative lifestyle. NEOM aims to become a global hub for tourism, innovation, and economic growth as part of Saudi Arabia\'s Vision 2030 initiative.', 'several days', 'images/ANeom.jpg'),
('3', 'Al-Ahsa Oasis', 'Experience the lush tranquility of Al-Ahsa Oasis, the world\'s largest oasis', 25.3833, 49.6, 'Al-Ahsa', 'East', 'Oasis', 'A UNESCO World Heritage site, Al-Ahsa has been a thriving center of civilization for over 5,000 years. The oasis played a crucial role in trade and agriculture in the Arabian Peninsula, supplying dates and fresh water to ancient travelers.', '1-2 days', 'images/AhsaOasis.jpg'),
('4', 'Nafud Al-Zulfi', 'Embark on a thrilling desert adventure in Nafud Al-Zulfi', 26.3, 44.8, 'Riyadh', 'Center', 'Sandy', 'Nafud Al-Zulfi has archaeological evidence of human activity dating back to the Stone Age. The region was historically part of major caravan routes, and ancient petroglyphs can be found in the surrounding desert.', '3-4 Hours', 'images/Alzelfi.jpg'),
('5', 'Al-Soudah', 'Discover the cool, verdant landscapes of Al-Soudah, a high-altitude haven offering breathtaking views', 18.2167, 42.5, 'Abha', 'South', 'Mountainous', 'Part of Asir National Park, Al-Soudah has long been a cultural hub for the Asir people. Known for its unique architecture and rich traditions, the region has been inhabited for centuries and played a key role in the spice trade routes.', '1-2 days', 'images/AbhaAlsodh.jpg'),
('6', 'Aja Mountains', 'Explore the rugged beauty of the Aja Mountains, where unique rock formations and diverse wildlife create an unforgettable experience', 27.5, 41.7, 'Ha’il', 'North', 'Rocky', 'The Aja Mountains have been designated as an Important Plant Area and an Important Bird and Biodiversity Area. The region is steeped in ancient history, with inscriptions and petroglyphs that date back thousands of years, making it an important archaeological site in Saudi Arabia.', 'A full day', 'images/Hail.jpg');

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
('2', 'images/Neom1.jpg'),
('2', 'images/Neom2.jpg'),
('2', 'images/Neom3.jpg'),
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
('6', 'images/Aja3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `ReviewID` int(255) NOT NULL,
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
(12, 'user_67ddefde14f71', '6', 3, 'Camping here was a highlight of our trip. The night sky is incredibly clear', '2025-03-20 09:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `reviewimage`
--

CREATE TABLE `reviewimage` (
  `ReviewID` varchar(255) NOT NULL,
  `ReviewImage` varchar(800) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reviewimage`
--

INSERT INTO `reviewimage` (`ReviewID`, `ReviewImage`) VALUES
('1', 'images/HeetCaveReview1.jpg'),
('1', 'images/HeetCaveReview11.jpg'),
('11', 'images/AjaReview1.jpg'),
('2', 'images/HeetCaveReview2.mp4'),
('7', 'images/NafudReview1.jpg');

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
('2', 'Discover Culture & Architecture'),
('2', 'Enjoy Desert'),
('2', 'Explore Wildlife & Eco-Tourism'),
('2', 'Sail & Enjoy Water Sports'),
('2', 'Sindalah Island'),
('2', 'Snorkeling & Diving'),
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
('6', 'Watch Wildlife');

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
('user_67ddf071b2555', 'Sara Mohammed', '+966553449838', 'Sara123@hotmail.com', '$2y$10$qq8dYP49tg.pDU4pJXftH.6DgqaPUgWsjvxP5qkcFwYVd9iKS.l4K');

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
-- Indexes for table `thingstodo`
--
ALTER TABLE `thingstodo`
  ADD PRIMARY KEY (`ThingsToDo`,`DestinationID`),
  ADD KEY `DestinationID` (`DestinationID`);

--
-- Indexes for table `tripscheduler`
--
ALTER TABLE `tripscheduler`
  ADD PRIMARY KEY (`ScheduleID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `ReviewID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contains`
--
ALTER TABLE `contains`
  ADD CONSTRAINT `contains_ibfk_1` FOREIGN KEY (`ScheduleID`) REFERENCES `tripscheduler` (`ScheduleID`) ON DELETE CASCADE,
  ADD CONSTRAINT `contains_ibfk_2` FOREIGN KEY (`DestinationID`) REFERENCES `destination` (`DestinationID`) ON DELETE CASCADE;

--
-- Constraints for table `destinationimage`
--
ALTER TABLE `destinationimage`
  ADD CONSTRAINT `destinationimage_ibfk_1` FOREIGN KEY (`DestinationID`) REFERENCES `destination` (`DestinationID`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`DestinationID`) REFERENCES `destination` (`DestinationID`) ON DELETE CASCADE;

--
-- Constraints for table `thingstodo`
--
ALTER TABLE `thingstodo`
  ADD CONSTRAINT `thingstodo_ibfk_1` FOREIGN KEY (`DestinationID`) REFERENCES `destination` (`DestinationID`) ON DELETE CASCADE;

--
-- Constraints for table `tripscheduler`
--
ALTER TABLE `tripscheduler`
  ADD CONSTRAINT `tripscheduler_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
