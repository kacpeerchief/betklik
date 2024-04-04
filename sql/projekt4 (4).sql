-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2024 at 03:18 PM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projekt4`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `bets`
--

CREATE TABLE `bets` (
  `bet_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_name` varchar(100) NOT NULL,
  `event_name2` varchar(100) NOT NULL,
  `bet_type` varchar(50) NOT NULL,
  `result` varchar(50) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin_bet` tinyint(1) NOT NULL DEFAULT 0,
  `team1_odds` decimal(10,2) DEFAULT 10.00,
  `team2_odds` decimal(10,2) DEFAULT 10.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bets`
--

INSERT INTO `bets` (`bet_id`, `user_id`, `event_name`, `event_name2`, `bet_type`, `result`, `timestamp`, `is_admin_bet`, `team1_odds`, `team2_odds`) VALUES
(55, 9, 'es', 'es', 'es', NULL, '2024-03-11 08:52:07', 0, 10.00, 10.00),
(56, 9, 'es', 'es', 'Piłka nożna', NULL, '2024-03-11 08:58:25', 0, 10.00, 10.00),
(57, 9, 'Polska', 'Argentyna', 'Tenis', NULL, '2024-03-11 08:58:41', 0, 10.00, 10.00),
(58, 9, 'es', 'es', '', NULL, '2024-03-11 08:59:52', 0, 10.00, 10.00),
(59, 9, 'es', 'es', 'Piłka nożna', NULL, '2024-03-11 09:01:27', 0, 10.00, 10.00),
(60, 9, '12', '12', 'Tenis', NULL, '2024-03-11 10:02:48', 0, 10.00, 10.00),
(61, 9, 'aaaaaaaaa', 'aaaaaa', 'Boks', NULL, '2024-03-11 10:03:13', 0, 10.00, 10.00),
(62, 9, 'SKT', 'EG', 'Esport', NULL, '2024-03-11 11:40:18', 0, 10.00, 10.00),
(63, 9, 'xd', 'xd', 'Baseball', NULL, '2024-03-11 12:01:00', 0, 10.00, 10.00),
(64, 9, 'xpep', 'xpep', 'Boks', NULL, '2024-03-11 12:05:33', 0, 10.00, 10.00),
(65, 20, 'Polska', 'Polska', 'Baseball', NULL, '2024-03-14 13:36:35', 0, 10.00, 10.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `transaction_history`
--

CREATE TABLE `transaction_history` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `transaction_type` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `balance`, `is_admin`) VALUES
(1, 'kacpik2pl', '$2y$10$mcT4yAzt7m4Aq7OBVLwmU.KRLKvKW4HoxEMyoOQJUlNbLlXdGugLO', 0.00, 0),
(2, 'xd', '$2y$10$2Uj.nWFKETkTBR01sP0pY.3V1POaAXzfrSTMm9yT3NbcjUUVpHUWO', 0.00, 0),
(4, 'kacpik2pl1', '123', 0.00, 0),
(5, '123', '123', 150.00, 0),
(7, 'xd123', 'xd123', 0.00, 0),
(8, 'pyk', 'pyk', 0.00, 0),
(9, 'essa', 'essa', 110.00, 1),
(10, 'pogczamp', 'pogczamp', 0.00, 1),
(11, 'koks', 'koks', 15.00, 0),
(18, 'xd1234', '$2y$10$mHK4SC5FVvO/aWRldmlcOOUWTwGGI634yx8T/WU1vj2153MjlkhfG', 0.00, 1),
(19, ' ', '$2y$10$bywk//mR88jxHc2UzE0K8eSCX9c5tvDbTbjSQm8hn8Y0AtJ6hBThe', 0.00, 1),
(20, 'micheal', 'micheal', 15.00, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_bets`
--

CREATE TABLE `user_bets` (
  `bet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `bet_amount` decimal(10,2) NOT NULL,
  `bet_type` varchar(50) NOT NULL,
  `result` varchar(50) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_bets`
--

INSERT INTO `user_bets` (`bet_id`, `user_id`, `event_name`, `bet_amount`, `bet_type`, `result`, `timestamp`) VALUES
(55, 9, '', 15.00, '', 'win', '2024-03-11 11:59:08'),
(56, 9, '', 15.00, '', 'draw', '2024-03-11 12:05:04'),
(58, 9, '', 51.00, '', 'win', '2024-03-11 12:03:57'),
(60, 9, '', 15.00, '', 'win', '2024-03-11 11:58:26'),
(62, 20, '', 15.00, '', 'win', '2024-03-14 13:39:37'),
(63, 9, '', 15.00, '', 'draw', '2024-03-11 12:02:54'),
(64, 9, '', 56.00, '', 'win', '2024-03-11 12:05:43');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiration_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `token`, `expiration_date`, `created_at`) VALUES
(54, 5, '2b9c3e95eb1e77b9bf8afd8d4aba12c01f9352f131be83eeff031e7f7e8d13b7', '2024-03-14 18:42:19', '2024-03-13 17:42:19'),
(55, 5, '3e4b2887dda97d6cf15b0ec8de88f70447a1fbf214973fae24043a40f49c52a4', '2024-03-14 18:52:18', '2024-03-13 17:52:18'),
(56, 5, '55155961b9debff7ddac3c11304dd853f25e2153dd798057a91e395c433a12ae', '2024-03-14 18:52:38', '2024-03-13 17:52:38'),
(57, 5, 'c8c4448b29942fd3f79d470ddb51b84bfa08fa900fcb4899ba41ff15e29457dd', '2024-03-14 18:57:08', '2024-03-13 17:57:08'),
(58, 5, '7afa2230b7651324923b3a7dfa575712ccc22786eec009b6731f386ea103f1e9', '2024-03-14 19:00:31', '2024-03-13 18:00:31'),
(59, 5, '316d6d983d2d3baa07607828b2e989de86ab5fb296227aff5e107b419160a094', '2024-03-14 19:03:13', '2024-03-13 18:03:13'),
(60, 5, 'db679b9450f11ea2a642963aa0477e8a3955941a8204e4c1866d983f5ee27475', '2024-03-14 19:04:05', '2024-03-13 18:04:05'),
(61, 5, '4f91a8901e6696046862abd7c675367f2b9a79fd25f26730443eafa5d4073c4b', '2024-03-14 19:06:22', '2024-03-13 18:06:22'),
(72, 20, '251e02fc9162be2e4819ca0264029971bc98472c2eae9dc5d067043c6f8d8c6e', '2024-03-15 14:39:33', '2024-03-14 13:39:33');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `bets`
--
ALTER TABLE `bets`
  ADD PRIMARY KEY (`bet_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeksy dla tabeli `user_bets`
--
ALTER TABLE `user_bets`
  ADD PRIMARY KEY (`bet_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bets`
--
ALTER TABLE `bets`
  MODIFY `bet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `transaction_history`
--
ALTER TABLE `transaction_history`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_bets`
--
ALTER TABLE `user_bets`
  MODIFY `bet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bets`
--
ALTER TABLE `bets`
  ADD CONSTRAINT `bets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD CONSTRAINT `transaction_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
