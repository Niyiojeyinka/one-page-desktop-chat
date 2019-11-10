-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2019 at 03:44 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `socialchat`
--

-- --------------------------------------------------------

--
-- Table structure for table `conversation`
--

CREATE TABLE `conversation` (
  `id` int(11) NOT NULL,
  `last_updated` varchar(128) NOT NULL,
  `receiver_id` int(10) NOT NULL,
  `sender_id` int(10) NOT NULL,
  `status` enum('active','inactive','blocked') DEFAULT NULL,
  `blocker_id` varchar(128) DEFAULT NULL,
  `time` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conversation`
--

INSERT INTO `conversation` (`id`, `last_updated`, `receiver_id`, `sender_id`, `status`, `blocker_id`, `time`) VALUES
(1, '1573200032', 2, 1, 'active', NULL, '1573200032'),
(3, '1573210132', 1, 3, 'active', NULL, '1573210132');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `type` varchar(128) NOT NULL,
  `time` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `text` text,
  `receiver_id` int(10) NOT NULL,
  `sender_id` int(10) NOT NULL,
  `conversation_id` varchar(128) NOT NULL,
  `status` enum('sent','seen') DEFAULT NULL,
  `type` enum('textonly','textobj') NOT NULL,
  `time` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `text`, `receiver_id`, `sender_id`, `conversation_id`, `status`, `type`, `time`) VALUES
(1, 'Hello How far', 2, 1, '1', 'sent', 'textonly', '1573210032'),
(2, 'Another message', 1, 2, '1', 'sent', 'textonly', '1573210132'),
(3, 'Want to get things  right', 1, 3, '3', 'sent', 'textonly', '1573210132');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(128) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `text` text,
  `phone` varchar(128) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  `address` varchar(128) NOT NULL,
  `friends` text NOT NULL,
  `profile_picture` varchar(128) DEFAULT NULL,
  `lastlog` varchar(128) NOT NULL,
  `time` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `password`, `text`, `phone`, `email`, `address`, `friends`, `profile_picture`, `lastlog`, `time`) VALUES
(1, 'Olaniyi', 'Ojeyinka', 'niyi', 'test', 'I Create Cool Solutions for the World to use', NULL, 'test@test.com', 'Earth ,Universe', '[2,3]', 'profile1.jpg', '1573200032', '1573200032'),
(2, 'Olan', 'yinka', 'philip', 'test', 'I Create Cool Solutions for the World to use', NULL, 'test1@test.com', 'Earth ,Universe', '[]', 'profile2.jpg', '1573200032', '1573200032'),
(3, 'John', 'Ojeyinka', 'niyji', 'test', 'I Create Cool Solutions for the World to use', NULL, 'test2@test.com', 'Earth ,Universe', '[]', 'profile3.jpg', '1573200032', '1573200032');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
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
-- AUTO_INCREMENT for table `conversation`
--
ALTER TABLE `conversation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
