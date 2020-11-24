-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Nov 24, 2020 at 11:06 AM
-- Server version: 5.7.32
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `planItdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `NightTypes`
--

CREATE TABLE `NightTypes` (
  `id` int(11) NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `displayName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `NightTypes`
--

INSERT INTO `NightTypes` (`id`, `value`, `displayName`) VALUES
(1, 'solo', 'me, myself and I 🙋‍♂️'),
(2, 'dateNight', 'me and my date 🌹'),
(3, 'girlsNight', 'me and the girls 🍷'),
(4, 'boysNight', 'me and the boys 🍻'),
(5, 'familyNight', 'me and my family 👪');

-- --------------------------------------------------------

--
-- Table structure for table `PlannedMovieNights`
--

CREATE TABLE `PlannedMovieNights` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `movieId` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `PlannedMovieNights`
--

INSERT INTO `PlannedMovieNights` (`id`, `userId`, `movieId`, `name`) VALUES
(1, 1, 24, 'Scary movie night :S');

-- --------------------------------------------------------

--
-- Table structure for table `StepOneMovieOptions`
--

CREATE TABLE `StepOneMovieOptions` (
  `id` int(11) NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'How option is identified in code',
  `displayName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'How option is displayed to user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `StepOneMovieOptions`
--

INSERT INTO `StepOneMovieOptions` (`id`, `value`, `displayName`) VALUES
(1, 'cry', 'crying in a corner'),
(2, 'action', 'pumping with adrenaline'),
(3, 'scary', 'hiding under a blanket');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'ward.van.bever@gmail.com', '$2y$10$WDwuRM6tIFZyLQmJIEgs0.oibW1uW7wAMbLS7b6r2/rAWa/eBKE3m');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `NightTypes`
--
ALTER TABLE `NightTypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `PlannedMovieNights`
--
ALTER TABLE `PlannedMovieNights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `StepOneMovieOptions`
--
ALTER TABLE `StepOneMovieOptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `NightTypes`
--
ALTER TABLE `NightTypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `PlannedMovieNights`
--
ALTER TABLE `PlannedMovieNights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `StepOneMovieOptions`
--
ALTER TABLE `StepOneMovieOptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
