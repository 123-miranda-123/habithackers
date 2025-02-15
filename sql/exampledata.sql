-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Feb 15, 2025 at 04:19 AM
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
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` int NOT NULL,
  `habit_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `team_id` int DEFAULT NULL,
  `badge_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `awarded_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_habits`
--

CREATE TABLE `company_habits` (
  `id` int NOT NULL,
  `habit_type_id` int NOT NULL,
  `time_frame` enum('Daily','Weekly','Monthly') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `goal` int NOT NULL DEFAULT '0',
  `progress` int NOT NULL DEFAULT '0',
  `last_updated` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_habits`
--

INSERT INTO `company_habits` (`id`, `habit_type_id`, `time_frame`, `goal`, `progress`, `last_updated`) VALUES
(1, 1, 'Daily', 30, 71, '2025-02-14 22:18:48'),
(2, 7, 'Weekly', 15, 0, '2025-02-14 20:42:17'),
(3, 8, 'Monthly', 50, 0, '2025-02-14 20:42:31');

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

--
-- Dumping data for table `company_habit_progress`
--

INSERT INTO `company_habit_progress` (`id`, `habit_type_id`, `progress`, `timestamp`) VALUES
(1, 1, 25, '2025-02-10 04:51:55'),
(2, 4, 30, '2025-02-10 04:52:22'),
(3, 8, 1, '2025-02-10 04:52:43'),
(4, 8, 1, '2025-02-10 04:52:57'),
(5, 3, 2, '2025-02-10 05:01:04'),
(6, 1, 60, '2025-02-10 05:02:26'),
(7, 5, 15, '2025-02-10 05:03:10'),
(8, 1, 200, '2025-02-10 05:03:25'),
(9, 6, 10, '2025-02-10 05:03:38'),
(10, 1, 15, '2025-02-15 02:43:16'),
(11, 1, 12, '2025-02-15 02:43:48'),
(12, 5, 30, '2025-02-15 02:43:57'),
(13, 6, 7, '2025-02-15 02:44:01'),
(14, 1, 14, '2025-02-15 02:44:08'),
(15, 1, 14, '2025-02-16 02:44:08'),
(16, 1, 30, '2025-02-15 04:18:48');

-- --------------------------------------------------------

--
-- Table structure for table `habits`
--

CREATE TABLE `habits` (
  `id` int NOT NULL,
  `team_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `habit_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `frequency` enum('Daily','Weekly','Monthly') COLLATE utf8mb4_general_ci NOT NULL,
  `goal` int NOT NULL,
  `progress` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `habit_logs`
--

CREATE TABLE `habit_logs` (
  `id` int NOT NULL,
  `habit_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `team_id` int DEFAULT NULL,
  `progress` int NOT NULL,
  `log_date` date NOT NULL
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
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `sent_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `captain_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `captain_id`) VALUES
(1, 'Habit Hackers', 2),
(2, 'Workday Warriors', 4);

-- --------------------------------------------------------

--
-- Table structure for table `team_goals`
--

CREATE TABLE `team_goals` (
  `id` int NOT NULL,
  `team_id` int NOT NULL,
  `goal` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_habits`
--

CREATE TABLE `team_habits` (
  `id` int NOT NULL,
  `team_id` int NOT NULL,
  `habit_type_id` int NOT NULL,
  `time_frame` enum('Daily','Weekly','Monthly') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `goal` int NOT NULL DEFAULT '0',
  `progress` int NOT NULL DEFAULT '0',
  `last_updated` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_habits`
--

INSERT INTO `team_habits` (`id`, `team_id`, `habit_type_id`, `time_frame`, `goal`, `progress`, `last_updated`) VALUES
(1, 1, 1, 'Daily', 30, 30, '2025-02-14 22:18:48'),
(2, 1, 5, 'Daily', 15, 0, '2025-02-14 20:45:00'),
(3, 1, 3, 'Monthly', 5, 0, '2025-02-14 20:45:14');

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

--
-- Dumping data for table `team_habit_progress`
--

INSERT INTO `team_habit_progress` (`id`, `team_id`, `habit_type_id`, `progress`, `timestamp`) VALUES
(1, 2, 1, 25, '2025-02-10 04:51:55'),
(2, 2, 4, 30, '2025-02-10 04:52:22'),
(3, 2, 8, 1, '2025-02-10 04:52:43'),
(4, 2, 8, 1, '2025-02-10 04:52:57'),
(5, 1, 3, 2, '2025-02-10 05:01:04'),
(6, 2, 1, 60, '2025-02-10 05:02:26'),
(7, 1, 5, 15, '2025-02-10 05:03:10'),
(8, 1, 1, 200, '2025-02-10 05:03:25'),
(9, 1, 6, 10, '2025-02-10 05:03:38'),
(10, 2, 1, 15, '2025-02-15 02:43:16'),
(11, 1, 1, 12, '2025-02-15 02:43:48'),
(12, 1, 5, 30, '2025-02-15 02:43:57'),
(13, 1, 6, 7, '2025-02-15 02:44:01'),
(14, 1, 1, 14, '2025-02-15 02:44:08'),
(15, 1, 1, 14, '2025-02-16 02:44:08'),
(16, 1, 1, 30, '2025-02-15 04:18:48');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `team_id` int NOT NULL,
  `role` enum('Member','Captain') COLLATE utf8mb4_general_ci DEFAULT 'Member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `user_id`, `team_id`, `role`) VALUES
(1, 3, 1, 'Member'),
(3, 5, 2, 'Member'),
(4, 6, 1, 'Member'),
(5, 7, 2, 'Member'),
(6, 8, 2, 'Member');

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
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Miranda Wang', 'admin@gmail.com', '$2y$10$7y4MsnadopPDC233nC5YSeu9y5.sf99LmBprxtOLa6HIisKOrX.Tm', 'Admin'),
(2, 'Peter Parker', 'captain@gmail.com', '$2y$10$UG2nzt0UGiEhaKTbOY6JGOq/JdLFdrjSeVe6157aPcWS3W5PouPUO', 'Captain'),
(3, 'Jack Wilson', 'member@gmail.com', '$2y$10$44ZnixnHA5y.D0Pq8/nYE.Jz8f7HfjpRPvvA9qjAiY99b9/djvQyy', 'Member'),
(4, 'Truman Johnson', 'trumbu12@gmail.com', '$2y$10$jFJnHjYiCo1E.e1LNLprE.6sEaGwQcvbjj6lQpLX77kjuMQCBrYne', 'Captain'),
(5, 'Diana Jones', 'diana89@gmail.com', '$2y$10$qoXcuWTTUYMhX5ZcyVGKYugAuE.y9EFXZqC2uaPAlgPjJ0LdNw38y', 'Member'),
(6, 'Eleanor Anderson', 'elear@gmail.com', '$2y$10$a4Lb4MfeOyXfNVW3Fjeq1eQdgcuknqRjs5XEaEEprU3/K6kTdYHYa', 'Member'),
(7, 'Scott Finch', 'scfinch@gmail.com', '$2y$10$zA7tyLdxOWXYoERs70i57uK.5Olyv6nKVZYj8XKMMZauVl5eAUBfu', 'Member'),
(8, 'Daniel Brown', 'daniel@gmail.com', '$2y$10$4TfKS3eFXkAbNBoYISpLSeHB.UPtXf8a7so.gSEMjPq4LLkuSrIdK', 'Member');

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

--
-- Dumping data for table `user_habits`
--

INSERT INTO `user_habits` (`id`, `user_id`, `habit_type_id`, `time_frame`, `goal`, `progress`, `last_updated`) VALUES
(1, 5, 1, 'Daily', 30, 0, '2025-02-14 20:43:02'),
(2, 5, 4, 'Weekly', 60, 30, '2025-02-09 22:52:22'),
(3, 5, 8, 'Daily', 3, 0, '2025-02-14 20:43:02'),
(4, 6, 3, 'Monthly', 4, 2, '2025-02-09 23:01:04'),
(5, 7, 1, 'Daily', 60, 0, '2025-02-14 20:43:02'),
(6, 3, 5, 'Daily', 10, 30, '2025-02-14 20:43:57'),
(7, 3, 1, 'Daily', 120, 56, '2025-02-14 22:18:48'),
(8, 3, 6, 'Daily', 8, 7, '2025-02-14 20:44:01'),
(9, 8, 1, 'Daily', 30, 15, '2025-02-14 20:43:16');

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
-- Dumping data for table `user_habit_progress`
--

INSERT INTO `user_habit_progress` (`id`, `user_id`, `habit_type_id`, `progress`, `timestamp`) VALUES
(1, 5, 1, 25, '2025-02-10 04:51:55'),
(2, 5, 4, 30, '2025-02-10 04:52:22'),
(3, 5, 8, 1, '2025-02-10 04:52:43'),
(4, 5, 8, 1, '2025-02-10 04:52:57'),
(5, 6, 3, 2, '2025-02-10 05:01:04'),
(6, 7, 1, 60, '2025-02-10 05:02:26'),
(7, 3, 5, 15, '2025-02-10 05:03:10'),
(8, 3, 1, 200, '2025-02-10 05:03:25'),
(9, 3, 6, 10, '2025-02-10 05:03:38'),
(10, 8, 1, 15, '2025-02-15 02:43:16'),
(11, 3, 1, 12, '2025-02-15 02:43:48'),
(12, 3, 5, 30, '2025-02-15 02:43:57'),
(13, 3, 6, 7, '2025-02-15 02:44:01'),
(14, 3, 1, 14, '2025-02-15 02:44:08'),
(15, 3, 1, 14, '2025-02-16 02:44:08'),
(16, 3, 1, 30, '2025-02-15 04:18:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habit_id` (`habit_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `team_id` (`team_id`);

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
-- Indexes for table `habits`
--
ALTER TABLE `habits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `habit_logs`
--
ALTER TABLE `habit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habit_id` (`habit_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `habit_types`
--
ALTER TABLE `habit_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `captain_id` (`captain_id`);

--
-- Indexes for table `team_goals`
--
ALTER TABLE `team_goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`);

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
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_habits`
--
ALTER TABLE `company_habits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company_habit_progress`
--
ALTER TABLE `company_habit_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `habits`
--
ALTER TABLE `habits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `habit_logs`
--
ALTER TABLE `habit_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `habit_types`
--
ALTER TABLE `habit_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `team_goals`
--
ALTER TABLE `team_goals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_habits`
--
ALTER TABLE `team_habits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `team_habit_progress`
--
ALTER TABLE `team_habit_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `unit_conversion`
--
ALTER TABLE `unit_conversion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_habits`
--
ALTER TABLE `user_habits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_habit_progress`
--
ALTER TABLE `user_habit_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `badges`
--
ALTER TABLE `badges`
  ADD CONSTRAINT `badges_ibfk_1` FOREIGN KEY (`habit_id`) REFERENCES `habits` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `badges_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `badges_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `habits`
--
ALTER TABLE `habits`
  ADD CONSTRAINT `habits_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `habits_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `habit_logs`
--
ALTER TABLE `habit_logs`
  ADD CONSTRAINT `habit_logs_ibfk_1` FOREIGN KEY (`habit_id`) REFERENCES `habits` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `habit_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `habit_logs_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`captain_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team_goals`
--
ALTER TABLE `team_goals`
  ADD CONSTRAINT `team_goals_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `user_habits_ibfk_1` FOREIGN KEY (`habit_type_id`) REFERENCES `habit_types` (`id`);

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
