-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2023 at 04:02 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `millionpixels`
--

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `fn` varchar(32) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `grade` varchar(16) DEFAULT NULL,
  `forbidden_to_participate` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `participants`
--

INSERT INTO `participants` (`fn`, `points`, `grade`, `forbidden_to_participate`) VALUES
('10001', 40000, '4', 0),
('10003', 20000, '3', 1),
('82033', 500000, '6', 0),
('99999', 50000, '5.5', 0);

--
-- Triggers `participants`
--
DELIMITER $$
CREATE TRIGGER `add_participant` AFTER INSERT ON `participants` FOR EACH ROW UPDATE users
SET is_participant = 1
WHERE fn = NEW.fn
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `remove_participant` AFTER DELETE ON `participants` FOR EACH ROW UPDATE users
SET is_participant = 0
WHERE fn = OLD.fn
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `id` int(11) NOT NULL,
  `creator_fn` varchar(32) NOT NULL,
  `points_cost` int(11) NOT NULL,
  `x_start` int(11) NOT NULL,
  `x_end` int(11) NOT NULL,
  `y_start` int(11) NOT NULL,
  `y_end` int(11) NOT NULL,
  `picture_name` varchar(128) NOT NULL,
  `link` varchar(512) DEFAULT NULL,
  `text` varchar(512) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pictures`
--

INSERT INTO `pictures` (`id`, `creator_fn`, `points_cost`, `x_start`, `x_end`, `y_start`, `y_end`, `picture_name`, `link`, `text`, `created`, `deleted`) VALUES
(20, '82033', 45000, 120, 420, 550, 700, 'i2132096988.png', 'https://www.youtube.com/', 'Линк към Youtube', '2023-01-30 00:53:26', NULL),
(21, '10001', 10000, 700, 800, 300, 400, 'i361919509.png', 'https://www.microsoft.com/bg-bg/', 'Линк към сайта на Microsoft', '2023-01-30 00:55:32', NULL),
(22, '10001', 22500, 760, 910, 730, 880, 'i283098394.png', 'https://www.facebook.com/', 'Линк към Facebook', '2023-01-30 00:57:50', NULL),
(23, '82033', 52500, 140, 490, 180, 330, 'i484849009.png', 'https://www.google.com/', 'Линк към търсачката Google', '2023-01-30 01:00:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `fn` varchar(32) NOT NULL,
  `name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `is_participant` tinyint(1) NOT NULL DEFAULT 0,
  `is_administrator` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`fn`, `name`, `email`, `password`, `is_participant`, `is_administrator`) VALUES
('10000', 'Иван', '', '$2y$10$3iNTYC0jN3ALV.ooGPBdg.MSjHi5jLq0WZvyWGO7Eo0hE2U/JNr4q', 0, 0),
('10001', 'Петър', '', '$2y$10$.E4Cay7RZyjziCumwGRglOT.86plA/cBmpCJ1N.JSD5qSqSqcrqZm', 1, 0),
('10002', 'Мария', '', '$2y$10$y0TIdZE8zjTDzGrTmLuSfOsm1YrMdgBzXWRukvU2VO3aSK4YlaFRW', 0, 1),
('10003', 'Кристиан', '', '$2y$10$ftu12QjnXWCRCqceBE/X/eNcsAEjz/o6uHLJGXjWb/cAgRXOh6iwq', 1, 0),
('82033', 'Бойко', 'boyko_borisov_1803@abv.bg', '$2y$10$foGutGn1iyDcUivya.c8h.0E2N/ntqgkLZjdtvBZNlgKrGVeAZ5Vu', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`fn`),
  ADD UNIQUE KEY `fn` (`fn`),
  ADD KEY `fn_2` (`fn`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `picture_name` (`picture_name`),
  ADD KEY `id_2` (`id`),
  ADD KEY `created` (`created`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `picture_has_creator` (`creator_fn`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`fn`),
  ADD UNIQUE KEY `fn` (`fn`),
  ADD KEY `fn_2` (`fn`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pictures`
--
ALTER TABLE `pictures`
  ADD CONSTRAINT `picture_has_creator` FOREIGN KEY (`creator_fn`) REFERENCES `participants` (`fn`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
