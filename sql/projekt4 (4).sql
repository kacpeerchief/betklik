-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2024 at 03:40 PM
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
  `event_name` varchar(12) NOT NULL,
  `event_name2` varchar(12) NOT NULL,
  `bet_type` varchar(50) NOT NULL,
  `result` varchar(50) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `team1_odds` decimal(10,2) DEFAULT NULL,
  `team2_odds` decimal(10,2) DEFAULT NULL,
  `draw_odds` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bets`
--

INSERT INTO `bets` (`bet_id`, `user_id`, `event_name`, `event_name2`, `bet_type`, `result`, `timestamp`, `team1_odds`, `team2_odds`, `draw_odds`) VALUES
(100, 34, 'Polska', 'Argentyna', 'Piłka nożna', 'Druzyna 1', '2024-04-07 18:39:56', 3.95, 5.59, 7.57),
(101, 34, 'aaaaaaaaaa', 'aaaaaaaaaaaa', 'Piłka nożna', 'Druzyna 1', '2024-04-07 18:49:37', 6.64, 5.53, 3.50),
(102, 34, '15', '15', 'Piłka nożna', 'Druzyna 1', '2024-04-07 18:55:35', 4.31, 3.44, 4.45),
(103, 34, 'xdddddd', 'xdddddd', 'Piłka nożna', 'Druzyna 1', '2024-04-07 19:03:49', 3.86, 7.72, 5.32),
(104, 34, 'xddd', 'xddd', 'Piłka nożna', 'Druzyna 1', '2024-04-07 19:18:53', 2.98, 7.14, 8.23),
(105, 34, 'szym', 'xd', 'Piłka nożna', NULL, '2024-04-07 19:18:58', 6.14, 6.00, 6.30),
(106, 34, 'oki', 'Polska', 'Hokej', NULL, '2024-04-07 19:19:03', 3.63, 5.80, 6.91);

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
(34, 'adminbetklik', 'adminbetklik', 3554.86, 1),
(40, 'adxd', 'xdada', 0.00, 0),
(41, '123', '123', 99999970.89, 0),
(42, '321', '321', 0.00, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_bets`
--

CREATE TABLE `user_bets` (
  `id_zakladu` int(11) NOT NULL,
  `bet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `event_name2` varchar(50) NOT NULL,
  `bet_amount` decimal(10,2) NOT NULL,
  `result` varchar(50) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `odds` decimal(10,2) DEFAULT NULL,
  `paid_out` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_bets`
--

INSERT INTO `user_bets` (`id_zakladu`, `bet_id`, `user_id`, `event_name`, `event_name2`, `bet_amount`, `result`, `timestamp`, `odds`, `paid_out`) VALUES
(113, 100, 34, 'Polska', 'Argentyna', 433.00, 'Druzyna 1', '2024-04-07 18:40:20', 4.38, 1),
(114, 100, 41, 'Polska', 'Argentyna', 15.00, 'Druzyna 1', '2024-04-07 18:40:29', 4.16, 1),
(115, 101, 34, 'aaaaaaaaaa', 'aaaaaaaaaaaa', 15.00, 'Druzyna 1', '2024-04-07 18:49:48', 7.36, 1),
(116, 101, 41, 'aaaaaaaaaa', 'aaaaaaaaaaaa', 15.00, 'Druzyna 1', '2024-04-07 18:50:04', 6.99, 1),
(117, 102, 34, '15', '15', 15.00, 'Druzyna 1', '2024-04-07 18:55:38', 4.78, 1),
(118, 102, 41, '15', '15', 15.00, 'Druzyna 1', '2024-04-07 18:55:45', 4.54, 1),
(119, 103, 34, 'xdddddd', 'xdddddd', 15.00, 'Druzyna 1', '2024-04-07 19:03:52', 4.49, 1),
(120, 103, 42, 'xdddddd', 'xdddddd', 15.00, 'Druzyna 2', '2024-04-07 19:04:07', 7.00, 1),
(121, 103, 41, 'xdddddd', 'xdddddd', 15.00, 'Druzyna 1', '2024-04-07 19:04:15', 4.06, 1),
(122, 104, 34, 'xddd', 'xddd', 15.00, 'Druzyna 1', '2024-04-07 19:19:09', 3.47, 1),
(123, 104, 41, 'xddd', 'xddd', 15.00, 'Druzyna 1', '2024-04-07 19:19:17', 3.30, 1),
(124, 104, 42, 'xddd', 'xddd', 15.00, 'Druzyna 2', '2024-04-07 19:19:34', 6.80, 1);

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
(244, 41, '52091a27324d853ab00a354cd856809d00ff4edc958a0e97a2b0172cf3b535b5', '2024-04-09 15:28:14', '2024-04-08 13:28:14');

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
  ADD PRIMARY KEY (`id_zakladu`),
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
  MODIFY `bet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `transaction_history`
--
ALTER TABLE `transaction_history`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `user_bets`
--
ALTER TABLE `user_bets`
  MODIFY `id_zakladu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

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
