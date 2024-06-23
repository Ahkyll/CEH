-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2024 at 04:33 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `event_date` varchar(255) DEFAULT NULL,
  `event_details` varchar(500) DEFAULT NULL,
  `event_image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `user_id`, `event_name`, `event_date`, `event_details`, `event_image`) VALUES
(1, 2, 'Event_Sample1', '2024-05-02', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'assets/img65b8fb93c1e5c_387549243_983390816092180_5197055744357479997_n.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `resource_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `resource_title` varchar(255) DEFAULT NULL,
  `resource_category` varchar(255) DEFAULT NULL,
  `resource_file` longblob DEFAULT NULL,
  `resource_link` varchar(500) DEFAULT NULL,
  `resource_picture` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`resource_id`, `user_id`, `resource_title`, `resource_category`, `resource_file`, `resource_link`, `resource_picture`) VALUES
(1, 1, 'HCI2', 'study_guides', 0x6173736574732f696d672f7265736f75726365734843492d322e706466, '', 0x6173736574732f696d672f7265736f75726365733338373437323035395f3237383838323233373934353239325f373339353833313135393434323339363932335f6e2e6a7067),
(2, 1, 'Algorithms for Dummies', 'study_guides', 0x6173736574732f696d672f7265736f7572636573312e706e67, '', 0x6173736574732f696d672f7265736f7572636573312e706e67),
(7, 1, 'sample2', 'research_papers', 0x6173736574732f696d672f7265736f7572636573436861707465722d442d434349542d30342e70707478, '', 0x6173736574732f696d672f7265736f75726365733338373437323035395f3237383838323233373934353239325f373339353833313135393434323339363932335f6e2e6a7067);

-- --------------------------------------------------------

--
-- Table structure for table `tb_comments`
--

CREATE TABLE `tb_comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_content` text DEFAULT NULL,
  `comment_date` date DEFAULT NULL,
  `comment_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_comments`
--

INSERT INTO `tb_comments` (`comment_id`, `post_id`, `user_id`, `comment_content`, `comment_date`, `comment_time`) VALUES
(45, 11, 1, 'dfdsfsdf', '2024-01-28', '2024-01-28 08:03:06'),
(46, 11, 1, 'asd', '2024-01-28', '2024-01-28 08:03:20'),
(47, 11, 3, 'hi', '2024-01-28', '2024-01-28 08:45:29'),
(48, 11, 1, 'hey', '2024-01-29', '2024-01-29 11:57:44'),
(52, 11, 1, 'hello', '2024-01-31', '2024-01-31 00:50:09'),
(53, 19, 1, 'naa nganoman?', '2024-01-31', '2024-01-31 01:21:16');

-- --------------------------------------------------------

--
-- Table structure for table `tb_forum`
--

CREATE TABLE `tb_forum` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_content` text NOT NULL,
  `post_date` date NOT NULL,
  `post_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_forum`
--

INSERT INTO `tb_forum` (`post_id`, `user_id`, `post_content`, `post_date`, `post_time`) VALUES
(11, 1, 'sdfds', '2024-01-28', '2024-01-28 08:03:04'),
(12, 1, 'sdfdsfsdf', '2024-01-30', '2024-01-30 10:04:19'),
(19, 6, 'naa moy codes sa html?', '2024-01-31', '2024-01-31 01:20:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `profile_picture` longblob NOT NULL,
  `section` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `is_admin` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `username`, `name`, `profile_picture`, `section`, `year`, `is_admin`, `created_at`) VALUES
(1, 'gomo.cpsu@gmail.com', '$2y$10$QhJnC6SpQdxpxEtdRH7w8uUe7kmnAoY8Bz2DL0cIHH.SYqTcbTu/.', 'Ahkyl', 'Lykah May', 0x6173736574732f696d672f70726f66696c6567202832292e6a7067, 'A', '2nd', 0, '2024-01-30 14:25:01'),
(2, 'admin@sample.com', '$2y$10$60JSfeKIXIhLdM821wQNau/rBIg7ki35TlVpPyUM.B9d.JcLmKdli', 'Admin', '', '', '', '', 1, '2024-01-30 14:25:01'),
(3, 'ahkyl@gmail.com', '$2y$10$ZXqHV2GkwFwalizdFFPLAezIKBlMuZfFJd4RwR2O56HDn24z5zPkm', 'May', '', '', '', '2nd', 0, '2024-01-30 14:25:01'),
(4, 'sample1@sample.com', '$2y$10$fwvmzrWrCmMHrxdgzhtjY.pCjHL67/E/G8BYroI5.k4bsxfHIVqFO', 'Akira', '', '', '', '1st', 0, '2024-01-30 14:29:31'),
(5, 'sample2@sample.com', '$2y$10$Zu6QDG8wjESiBKdefRL03eAdjMIGE50pfHuwxqhKZ/qN9ScvFSP1y', 'Jane', '', '', '', '2nd', 0, '2024-01-30 14:30:05'),
(6, 'nino1@gmail.com', '$2y$10$kqqhl8o6B6/MSpRwDI41lu/LZLofgN8wB2Mx3Yg5WqO5VfZMFqrg2', 'nino', '', '', '', '1st', 0, '2024-01-31 01:16:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`resource_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tb_comments`
--
ALTER TABLE `tb_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_tb_comments_tb_forum` (`post_id`);

--
-- Indexes for table `tb_forum`
--
ALTER TABLE `tb_forum`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_comments`
--
ALTER TABLE `tb_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `tb_forum`
--
ALTER TABLE `tb_forum`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `tb_comments`
--
ALTER TABLE `tb_comments`
  ADD CONSTRAINT `fk_tb_comments_tb_forum` FOREIGN KEY (`post_id`) REFERENCES `tb_forum` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `tb_forum` (`post_id`),
  ADD CONSTRAINT `tb_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `tb_forum`
--
ALTER TABLE `tb_forum`
  ADD CONSTRAINT `tb_forum_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
