-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 28, 2025 at 03:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `greenearth`
--

-- --------------------------------------------------------

--
-- Table structure for table `certificate_settings`
--

CREATE TABLE `certificate_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `logo_greenearth` varchar(255) DEFAULT 'images/logo.png',
  `logo_kenya` varchar(255) DEFAULT 'images/kenya-logo.png',
  `signature_ceo` varchar(255) DEFAULT 'images/default-signature.png',
  `ceo_name` varchar(255) DEFAULT 'John Doe'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `location`, `event_date`, `created_at`) VALUES
(1, 'Community Tree Planting Day', 'Join us for a day of planting trees in Kitale!', 'Kitale National Park', '2023-11-15', '2025-02-15 12:55:49'),
(2, 'Reforestation Drive', 'Help restore the forest in Eldoret!', 'Eldoret Forest Reserve', '2023-12-01', '2025-02-15 12:55:49'),
(3, 'Community Tree Planting Day', 'Join us for a day of planting trees in Kitale!', 'Kitale', '2023-11-15', '2025-02-16 05:21:44'),
(4, 'Reforestation Drive', 'Help restore the forest in Eldoret!', 'Eldoret', '2023-12-01', '2025-02-16 05:21:44'),
(5, 'Green Earth Festival', 'A celebration of sustainability and conservation.', 'Nairobi', '2023-11-25', '2025-02-16 05:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `event_participation`
--

CREATE TABLE `event_participation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `attended` enum('yes','no') DEFAULT 'no',
  `participation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

CREATE TABLE `event_registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_registrations`
--

INSERT INTO `event_registrations` (`id`, `user_id`, `event_id`, `registered_at`) VALUES
(1, 4, 1, '2025-02-16 05:16:57'),
(2, 4, 3, '2025-02-16 05:24:25'),
(3, 4, 2, '2025-02-16 05:24:41'),
(4, 4, 3, '2025-02-16 05:28:35'),
(5, 4, 3, '2025-02-16 05:37:02'),
(6, 4, 3, '2025-02-16 05:41:44'),
(7, 4, 5, '2025-02-16 05:43:10'),
(8, 4, 3, '2025-02-16 05:43:34'),
(9, 4, 3, '2025-02-16 06:19:16'),
(10, 4, 4, '2025-02-16 07:05:28'),
(11, 4, 4, '2025-02-16 07:41:49'),
(12, 4, 3, '2025-02-16 08:18:18');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `seedling_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `seedling_id`, `quantity`, `total_price`, `order_date`) VALUES
(1, 4, 4, 5, 250.00, '2025-02-16 07:06:27');

-- --------------------------------------------------------

--
-- Table structure for table `program_enrollments`
--

CREATE TABLE `program_enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_enrollments`
--

INSERT INTO `program_enrollments` (`id`, `user_id`, `program_id`, `enrolled_at`) VALUES
(1, 4, 1, '2025-02-16 05:37:37'),
(2, 4, 2, '2025-02-16 05:41:02'),
(3, 4, 2, '2025-02-16 06:19:32');

-- --------------------------------------------------------

--
-- Table structure for table `seedlings`
--

CREATE TABLE `seedlings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `region` varchar(255) NOT NULL,
  `height` enum('tall','short') DEFAULT NULL,
  `fruit` enum('edible','non-edible') DEFAULT NULL,
  `purpose` enum('ornamental','timber','shade') DEFAULT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seedlings`
--

INSERT INTO `seedlings` (`id`, `name`, `description`, `price`, `image`, `created_at`, `region`, `height`, `fruit`, `purpose`, `stock`) VALUES
(1, 'Moringa Tree', 'Fast-growing tree with nutritional leaves and seeds.', 50.00, 'images/moringa.jpg', '2025-02-15 12:55:48', '', NULL, 'non-edible', 'ornamental', 0),
(2, 'Acacia Tortilis', 'Drought-resistant tree suitable for arid regions.', 75.00, 'images/acacia.jpg', '2025-02-15 12:55:48', '', NULL, 'non-edible', 'ornamental', 0),
(3, 'Eucalyptus', 'Rapidly growing tree used for timber and oil production.', 60.00, 'images/eucalyptus.jpg', '2025-02-15 12:55:48', '', NULL, 'non-edible', 'ornamental', 0),
(4, 'Moringa Tree', 'Fast-growing tree with nutritional leaves.', 50.00, 'images/moringa.jpg', '2025-02-15 14:36:28', 'nairobi', NULL, 'non-edible', 'ornamental', 0),
(5, 'Acacia Tortilis', 'Drought-resistant tree suitable for arid regions.', 75.00, 'images/acacia.jpg', '2025-02-15 14:36:28', 'kitale', NULL, 'non-edible', 'ornamental', 0),
(6, 'Eucalyptus', 'Rapidly growing tree used for timber and oil production.', 60.00, 'images/eucalyptus.jpg', '2025-02-15 14:36:28', 'eldoret', NULL, 'non-edible', 'ornamental', 0),
(7, 'Moringa Tree', 'Fast-growing tree with nutritional leaves.', 50.00, 'images/moringa.jpg', '2025-02-15 14:44:19', 'nairobi', NULL, 'non-edible', 'ornamental', 0),
(8, 'Acacia Tortilis', 'Drought-resistant tree suitable for arid regions.', 75.00, 'images/acacia.jpg', '2025-02-15 14:44:19', 'kitale', NULL, 'non-edible', 'ornamental', 0),
(9, 'Eucalyptus', 'Rapidly growing tree used for timber and oil production.', 60.00, 'images/eucalyptus.jpg', '2025-02-15 14:44:19', 'eldoret', NULL, 'non-edible', 'ornamental', 0),
(10, 'Moringa Tree', '<p>Fast-growing tree with nutritional leaves.</p>', 50.00, 'images/moringa.jpg', '2025-02-15 15:21:59', 'Central Highlands', 'short', 'edible', 'ornamental', 100),
(11, 'Acacia Tortilis', '<p>Drought-resistant tree suitable for arid regions.</p>', 75.00, 'images/seedlings/africantulip.jpg', '2025-02-15 15:21:59', 'Central Highlands', 'tall', 'non-edible', 'shade', 0),
(12, 'Eucalyptus', 'Rapidly growing tree used for timber and oil production.', 60.00, 'images/eucalyptus.jpg', '2025-02-15 15:21:59', 'eldoret', 'tall', 'non-edible', 'timber', 0);

-- --------------------------------------------------------

--
-- Table structure for table `titles`
--

CREATE TABLE `titles` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `titles`
--

INSERT INTO `titles` (`id`, `title`) VALUES
(1, 'Mr'),
(2, 'Mrs'),
(3, 'Ms'),
(4, 'Dr'),
(5, 'Prof');

-- --------------------------------------------------------

--
-- Table structure for table `training_programs`
--

CREATE TABLE `training_programs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_programs`
--

INSERT INTO `training_programs` (`id`, `title`, `description`, `duration`, `location`, `created_at`) VALUES
(1, 'Sustainable Forestry', 'Learn how to manage forests sustainably.', '2 weeks', 'Kitale', '2025-02-16 05:37:18'),
(2, 'Eco-Entrepreneurship', 'Start your own green business with our training.', '1 month', 'Nairobi', '2025-02-16 05:37:18'),
(3, 'Climate Adaptation', 'Adapt to climate change with practical strategies.', '3 days', 'Eldoret', '2025-02-16 05:37:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `title` varchar(10) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `town` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `title`, `first_name`, `last_name`, `phone`, `town`) VALUES
(1, 'admin', 'admin@greenearth.com', '*01A6717B58FF5C7EAFFF6CB7C96F7428EA65FE4C', 'admin', '2025-02-15 12:55:49', NULL, '', '', NULL, NULL),
(2, 'user1', 'user1@example.com', '*A0F874BC7F54EE086FCE60A37CE7887D8B31086B', 'user', '2025-02-15 12:55:49', NULL, '', '', NULL, NULL),
(3, 'root', 'ray@gmail.com', '', 'user', '2025-02-15 15:41:39', NULL, '', '', NULL, NULL),
(4, '', 'harrisonwekesa09@gmail.com', '$2y$10$8GutG6cgNriZQ/z1i.nRB.V9HE9Nneye1H7o73DeSsFOIwJ0yoQXu', 'admin', '2025-02-15 16:13:17', 'Mr.', 'Harrison', 'Wanjala', '0741947264', 'Kitale');

-- --------------------------------------------------------

--
-- Table structure for table `user_event_participation`
--

CREATE TABLE `user_event_participation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `participation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_program_participation`
--

CREATE TABLE `user_program_participation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `participation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certificate_settings`
--
ALTER TABLE `certificate_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_participation`
--
ALTER TABLE `event_participation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `seedling_id` (`seedling_id`);

--
-- Indexes for table `program_enrollments`
--
ALTER TABLE `program_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `seedlings`
--
ALTER TABLE `seedlings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `titles`
--
ALTER TABLE `titles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training_programs`
--
ALTER TABLE `training_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_event_participation`
--
ALTER TABLE `user_event_participation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `user_program_participation`
--
ALTER TABLE `user_program_participation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `program_id` (`program_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `event_participation`
--
ALTER TABLE `event_participation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `program_enrollments`
--
ALTER TABLE `program_enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `seedlings`
--
ALTER TABLE `seedlings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `titles`
--
ALTER TABLE `titles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `training_programs`
--
ALTER TABLE `training_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_event_participation`
--
ALTER TABLE `user_event_participation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_program_participation`
--
ALTER TABLE `user_program_participation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event_participation`
--
ALTER TABLE `event_participation`
  ADD CONSTRAINT `event_participation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_participation_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD CONSTRAINT `event_registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `event_registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`seedling_id`) REFERENCES `seedlings` (`id`);

--
-- Constraints for table `program_enrollments`
--
ALTER TABLE `program_enrollments`
  ADD CONSTRAINT `program_enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `program_enrollments_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `training_programs` (`id`);

--
-- Constraints for table `user_event_participation`
--
ALTER TABLE `user_event_participation`
  ADD CONSTRAINT `user_event_participation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_event_participation_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_program_participation`
--
ALTER TABLE `user_program_participation`
  ADD CONSTRAINT `user_program_participation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_program_participation_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `training_programs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
