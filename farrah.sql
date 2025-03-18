-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: 17 مارس 2025 الساعة 21:34
-- إصدار الخادم: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `farrah`
--

-- --------------------------------------------------------

--
-- بنية الجدول `contains`
--

CREATE TABLE `contains` (
  `ScheduleID` varchar(255) NOT NULL,
  `DestinationID` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `destination`
--

CREATE TABLE `destination` (
  `DestinationID` varchar(255) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `City` varchar(100) NOT NULL,
  `Description` varchar(700) NOT NULL,
  `Location` varchar(300) NOT NULL,
  `TimeNeeded` varchar(20) NOT NULL,
  `Rating` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `destinationimage`
--

CREATE TABLE `destinationimage` (
  `DestinationID` varchar(255) NOT NULL,
  `DestinationImage` varchar(800) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `review`
--

CREATE TABLE `review` (
  `ReviewID` varchar(255) NOT NULL,
  `UserID` varchar(255) NOT NULL,
  `DestinationID` varchar(255) NOT NULL,
  `Rating` int(11) NOT NULL,
  `Comment` varchar(400) DEFAULT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `reviewimage`
--

CREATE TABLE `reviewimage` (
  `ReviewID` varchar(255) NOT NULL,
  `ReviewImage` varchar(800) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `thingstodo`
--

CREATE TABLE `thingstodo` (
  `DestinationID` varchar(255) NOT NULL,
  `ThingsToDo` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `tripscheduler`
--

CREATE TABLE `tripscheduler` (
  `ScheduleID` varchar(255) NOT NULL,
  `UserID` varchar(255) NOT NULL,
  `Date` datetime NOT NULL,
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `type`
--

CREATE TABLE `type` (
  `DestinationID` varchar(255) NOT NULL,
  `Type` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `user`
--

CREATE TABLE `user` (
  `UserID` varchar(255) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `PhoneNumber` varchar(15) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`Type`,`DestinationID`),
  ADD KEY `DestinationID` (`DestinationID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- قيود الجداول المحفوظة
--

--
-- القيود للجدول `contains`
--
ALTER TABLE `contains`
  ADD CONSTRAINT `contains_ibfk_1` FOREIGN KEY (`ScheduleID`) REFERENCES `tripscheduler` (`ScheduleID`) ON DELETE CASCADE,
  ADD CONSTRAINT `contains_ibfk_2` FOREIGN KEY (`DestinationID`) REFERENCES `destination` (`DestinationID`) ON DELETE CASCADE;

--
-- القيود للجدول `destinationimage`
--
ALTER TABLE `destinationimage`
  ADD CONSTRAINT `destinationimage_ibfk_1` FOREIGN KEY (`DestinationID`) REFERENCES `destination` (`DestinationID`) ON DELETE CASCADE;

--
-- القيود للجدول `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`DestinationID`) REFERENCES `destination` (`DestinationID`) ON DELETE CASCADE;

--
-- القيود للجدول `reviewimage`
--
ALTER TABLE `reviewimage`
  ADD CONSTRAINT `reviewimage_ibfk_1` FOREIGN KEY (`ReviewID`) REFERENCES `review` (`ReviewID`) ON DELETE CASCADE;

--
-- القيود للجدول `thingstodo`
--
ALTER TABLE `thingstodo`
  ADD CONSTRAINT `thingstodo_ibfk_1` FOREIGN KEY (`DestinationID`) REFERENCES `destination` (`DestinationID`) ON DELETE CASCADE;

--
-- القيود للجدول `tripscheduler`
--
ALTER TABLE `tripscheduler`
  ADD CONSTRAINT `tripscheduler_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE;

--
-- القيود للجدول `type`
--
ALTER TABLE `type`
  ADD CONSTRAINT `type_ibfk_1` FOREIGN KEY (`DestinationID`) REFERENCES `destination` (`DestinationID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
