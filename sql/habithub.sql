-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 13, 2025 at 02:55 AM
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
-- Table structure for table `company_goals`
--

CREATE TABLE `company_goals` (
  `id` int NOT NULL,
  `habit_id` int NOT NULL,
  `goal_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_progress`
--

CREATE TABLE `company_progress` (
  `id` int NOT NULL,
  `habit_id` int NOT NULL,
  `progress_amount` decimal(10,2) NOT NULL,
  `progress_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `habit_types`
--

CREATE TABLE `habit_types` (
  `id` int NOT NULL,
  `habit_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `unit` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
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
(1, 'habithackers', 2);

-- --------------------------------------------------------

--
-- Table structure for table `team_goals`
--

CREATE TABLE `team_goals` (
  `id` int NOT NULL,
  `team_id` int NOT NULL,
  `habit_id` int NOT NULL,
  `goal_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 3, 1, 'Member');

-- --------------------------------------------------------

--
-- Table structure for table `team_progress`
--

CREATE TABLE `team_progress` (
  `id` int NOT NULL,
  `team_id` int NOT NULL,
  `habit_id` int NOT NULL,
  `progress_amount` decimal(10,2) NOT NULL,
  `progress_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Miranda Wang', 'miranda@gmail.com', '$2y$10$7y4MsnadopPDC233nC5YSeu9y5.sf99LmBprxtOLa6HIisKOrX.Tm', 'admin'),
(2, 'Team Captain', 'captain@gmail.com', '$2y$10$UG2nzt0UGiEhaKTbOY6JGOq/JdLFdrjSeVe6157aPcWS3W5PouPUO', 'captain'),
(3, 'Real Member', 'member@gmail.com', '$2y$10$44ZnixnHA5y.D0Pq8/nYE.Jz8f7HfjpRPvvA9qjAiY99b9/djvQyy', 'member');

-- --------------------------------------------------------

--
-- Table structure for table `user_goals`
--

CREATE TABLE `user_goals` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `habit_id` int NOT NULL,
  `goal_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_progress`
--

CREATE TABLE `user_progress` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `habit_id` int NOT NULL,
  `progress_amount` decimal(10,2) NOT NULL,
  `progress_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_goals`
--
ALTER TABLE `company_goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habit_id` (`habit_id`);

--
-- Indexes for table `company_progress`
--
ALTER TABLE `company_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habit_id` (`habit_id`);

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
  ADD KEY `team_id` (`team_id`),
  ADD KEY `habit_id` (`habit_id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `team_progress`
--
ALTER TABLE `team_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `habit_id` (`habit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_goals`
--
ALTER TABLE `user_goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `habit_id` (`habit_id`);

--
-- Indexes for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `habit_id` (`habit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_goals`
--
ALTER TABLE `company_goals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_progress`
--
ALTER TABLE `company_progress`
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team_goals`
--
ALTER TABLE `team_goals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team_progress`
--
ALTER TABLE `team_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_goals`
--
ALTER TABLE `user_goals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_progress`
--
ALTER TABLE `user_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `company_goals`
--
ALTER TABLE `company_goals`
  ADD CONSTRAINT `company_goals_ibfk_1` FOREIGN KEY (`habit_id`) REFERENCES `habit_types` (`id`);

--
-- Constraints for table `company_progress`
--
ALTER TABLE `company_progress`
  ADD CONSTRAINT `company_progress_ibfk_1` FOREIGN KEY (`habit_id`) REFERENCES `habit_types` (`id`);

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
  ADD CONSTRAINT `team_goals_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`),
  ADD CONSTRAINT `team_goals_ibfk_2` FOREIGN KEY (`habit_id`) REFERENCES `habit_types` (`id`);

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_members_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team_progress`
--
ALTER TABLE `team_progress`
  ADD CONSTRAINT `team_progress_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`),
  ADD CONSTRAINT `team_progress_ibfk_2` FOREIGN KEY (`habit_id`) REFERENCES `habit_types` (`id`);

--
-- Constraints for table `user_goals`
--
ALTER TABLE `user_goals`
  ADD CONSTRAINT `user_goals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_goals_ibfk_2` FOREIGN KEY (`habit_id`) REFERENCES `habit_types` (`id`);

--
-- Constraints for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_progress_ibfk_2` FOREIGN KEY (`habit_id`) REFERENCES `habit_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
