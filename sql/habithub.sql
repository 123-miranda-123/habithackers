-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 14, 2025 at 12:58 AM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `habithub`
--

-- --------------------------------------------------------

--
-- Table structure for table `company_habits`
--

CREATE TABLE `company_habits` (
  `id` int NOT NULL,
  `habit_type_id` int NOT NULL,
  `time_frame` enum('daily','weekly','monthly') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `goal` int NOT NULL DEFAULT '0',
  `progress` int NOT NULL DEFAULT '0',
  `last_updated` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_habit_progress`
--

CREATE TABLE `company_habit_progress` (
  `id` int NOT NULL,
  `habit_type_id` int NOT NULL,
  `progress` int NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `habit_types`
--

CREATE TABLE `habit_types` (
  `id` int NOT NULL,
  `habit_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `habit_types`
--

INSERT INTO `habit_types` (`id`, `habit_name`, `unit`) VALUES
(1, 'Exercise', 'Minutes'),
(2, 'Reading', 'Pages'),
(3, 'Journaling', 'Entries'),
(4, 'Meditation', 'Minutes'),
(5, 'Hydration', 'Cups'),
(6, 'Sleep', 'Hours'),
(7, 'Project', 'Hours'),
(8, 'Skill Learning', 'Hours');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `captain_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_habits`
--

CREATE TABLE `team_habits` (
  `id` int NOT NULL,
  `team_id` int NOT NULL,
  `habit_type_id` int NOT NULL,
  `time_frame` enum('daily','weekly','monthly') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `goal` int NOT NULL DEFAULT '0',
  `progress` int NOT NULL DEFAULT '0',
  `last_updated` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_habit_progress`
--

CREATE TABLE `team_habit_progress` (
  `id` int NOT NULL,
  `team_id` int NOT NULL,
  `habit_type_id` int NOT NULL,
  `progress` int NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `team_id` int NOT NULL,
  `role` enum('Member','Captain') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit_conversion`
--

CREATE TABLE `unit_conversion` (
  `id` int NOT NULL,
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `factor` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit_conversion`
--

INSERT INTO `unit_conversion` (`id`, `unit`, `factor`) VALUES
(1, 'Minutes', 1.00),
(2, 'Pages', 2.00),
(3, 'Entries', 5.00),
(4, 'Cups', 15.00),
(5, 'Hours', 60.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_habits`
--

CREATE TABLE `user_habits` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `habit_type_id` int NOT NULL,
  `time_frame` enum('Daily','Weekly','Monthly') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `goal` int NOT NULL DEFAULT '0',
  `progress` int NOT NULL DEFAULT '0',
  `last_updated` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_habit_progress`
--

CREATE TABLE `user_habit_progress` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `habit_type_id` int NOT NULL,
  `progress` int NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_habits`
--
ALTER TABLE `company_habits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habit_type_id` (`habit_type_id`);

--
-- Indexes for table `company_habit_progress`
--
ALTER TABLE `company_habit_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habit_type_id` (`habit_type_id`);

--
-- Indexes for table `habit_types`
--
ALTER TABLE `habit_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `captain_id` (`captain_id`);

--
-- Indexes for table `team_habits`
--
ALTER TABLE `team_habits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habit_type_id` (`habit_type_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `team_habit_progress`
--
ALTER TABLE `team_habit_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `habit_type_id` (`habit_type_id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `unit_conversion`
--
ALTER TABLE `unit_conversion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_habits`
--
ALTER TABLE `user_habits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habit_type_id` (`habit_type_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_habit_progress`
--
ALTER TABLE `user_habit_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `habit_type_id` (`habit_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_habits`
--
ALTER TABLE `company_habits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_habit_progress`
--
ALTER TABLE `company_habit_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `habit_types`
--
ALTER TABLE `habit_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team_habits`
--
ALTER TABLE `team_habits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_habit_progress`
--
ALTER TABLE `team_habit_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unit_conversion`
--
ALTER TABLE `unit_conversion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_habits`
--
ALTER TABLE `user_habits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_habit_progress`
--
ALTER TABLE `user_habit_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `company_habits`
--
ALTER TABLE `company_habits`
  ADD CONSTRAINT `company_habits_ibfk_1` FOREIGN KEY (`habit_type_id`) REFERENCES `habit_types` (`id`);

--
-- Constraints for table `company_habit_progress`
--
ALTER TABLE `company_habit_progress`
  ADD CONSTRAINT `company_habit_progress_ibfk_1` FOREIGN KEY (`habit_type_id`) REFERENCES `habit_types` (`id`);

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`captain_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team_habits`
--
ALTER TABLE `team_habits`
  ADD CONSTRAINT `team_habits_ibfk_1` FOREIGN KEY (`habit_type_id`) REFERENCES `habit_types` (`id`),
  ADD CONSTRAINT `team_habits_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team_habit_progress`
--
ALTER TABLE `team_habit_progress`
  ADD CONSTRAINT `team_habit_progress_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`),
  ADD CONSTRAINT `team_habit_progress_ibfk_2` FOREIGN KEY (`habit_type_id`) REFERENCES `habit_types` (`id`);

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_members_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_habits`
--
ALTER TABLE `user_habits`
  ADD CONSTRAINT `user_habits_ibfk_1` FOREIGN KEY (`habit_type_id`) REFERENCES `habit_types` (`id`),
  ADD CONSTRAINT `user_habits_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_habit_progress`
--
ALTER TABLE `user_habit_progress`
  ADD CONSTRAINT `user_habit_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_habit_progress_ibfk_2` FOREIGN KEY (`habit_type_id`) REFERENCES `habit_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
