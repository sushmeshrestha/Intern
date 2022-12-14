-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2022 at 10:47 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s2`
--

-- --------------------------------------------------------

--
-- Table structure for table `table_rel_booking`
--

CREATE TABLE `table_rel_booking` (
  `id` int(11) NOT NULL,
  `bookID` int(10) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `bookDay` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `table_rel_booking`
--

INSERT INTO `table_rel_booking` (`id`, `bookID`, `start_time`, `end_time`, `bookDay`) VALUES
(10, 5, '10:15:00', '13:15:00', 4),
(11, 7, '12:15:00', '02:00:00', 6),
(12, 7, '00:00:10', '00:00:12', 3),
(13, 5, '09:00:00', '10:00:00', 4),
(14, 8, '12:00:00', '00:00:00', 0),
(15, 8, '09:00:00', '16:00:00', 0),
(16, 7, '09:00:00', '16:00:00', 5),
(17, 10, '09:00:00', '16:00:00', 4),
(18, 12, '09:00:00', '16:00:00', 4),
(19, 12, '09:00:00', '16:00:00', 2),
(20, 9, '09:00:00', '16:00:00', 7),
(21, 9, '09:00:00', '16:00:00', 6),
(22, 10, '09:00:00', '16:00:00', 5),
(23, 6, '09:00:00', '16:00:00', 4),
(24, 13, '09:00:00', '16:00:00', 3),
(25, 7, '12:00:00', '16:00:00', 6),
(26, 5, '09:00:00', '16:00:00', 3),
(27, 13, '09:00:00', '16:00:00', 4),
(28, 7, '09:00:00', '16:00:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `table_room_booking`
--

CREATE TABLE `table_room_booking` (
  `id` int(11) NOT NULL,
  `room_id` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `table_room_booking`
--

INSERT INTO `table_room_booking` (`id`, `room_id`, `start_date`, `end_date`) VALUES
(5, '08-101', '2022-11-13', '2022-11-30'),
(6, '09-202', '2022-11-13', '2022-11-18'),
(7, '10-303', '2022-11-16', '2022-11-16'),
(8, '09_301', '2022-12-01', '2022-12-31'),
(9, '309', '2022-12-01', '2022-12-31'),
(10, '09_308', '2022-12-01', '2022-12-31'),
(11, '09_306', '2022-12-01', '2022-12-31'),
(12, '09_305', '2022-12-01', '2022-12-31'),
(13, '09_304', '2022-12-01', '2022-12-31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `table_rel_booking`
--
ALTER TABLE `table_rel_booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test` (`bookID`);

--
-- Indexes for table `table_room_booking`
--
ALTER TABLE `table_room_booking`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_id` (`room_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `table_rel_booking`
--
ALTER TABLE `table_rel_booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `table_room_booking`
--
ALTER TABLE `table_room_booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `table_rel_booking`
--
ALTER TABLE `table_rel_booking`
  ADD CONSTRAINT `test` FOREIGN KEY (`bookID`) REFERENCES `table_room_booking` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
