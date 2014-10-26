-- phpMyAdmin SQL Dump
-- version 4.2.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas generowania: 26 Paź 2014, 22:01
-- Wersja serwera: 10.0.14-MariaDB-log
-- Wersja PHP: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `silab`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `family_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `postal_code` varchar(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `employees`
--

INSERT INTO `employees` (`id`, `name`, `surname`, `gender`, `family_name`, `email`, `postal_code`) VALUES
(1, 'Radosław', 'Niewiadomski', 'male', '', 'rniewiadomski@gmail.com', '32-863'),
(2, 'Justyna', 'Tyszka', 'female', 'Ząbek', 'jtyszka@wp.pl', '76-909'),
(3, 'Szymon', 'Wątkiewicz', 'male', '', 'swatkiewicz@wp.pl', '32-805'),
(4, 'Urszula', 'Kreska', 'female', 'Walec', 'ukreska@yahoo.com', '32-322'),
(5, 'Iwona', 'Mickiewicz', 'female', 'Drwal', 'imickiewicz@interia.pl', '43-542'),
(6, 'Oskar', 'Krawiec', 'male', '', 'okrawiec@poczta.pl', '32-543'),
(7, 'Katarzyna', 'Wójcik', 'female', 'Rudak', 'kwojcik@o2.pl', '43-657'),
(8, 'Robert', 'Tyszka', 'male', '', 'rtyszka@gmail.com', '44-490'),
(9, 'Jan', 'Woźniak', 'male', '', 'jwozniak@wp.pl', '32-328'),
(10, 'Joanna', 'Wątkiewicz', 'female', 'Rumak', 'jwatkiewicz@zus.gov.pl', '56-879'),
(11, 'Barbara', 'Reszta', 'female', 'Turbin', 'basia@buziaczek.pl', '83-947'),
(12, 'Ignacy', 'Krautz', 'male', '', 'krautz@polizei.de', '32-435');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password_hash` varchar(128) DEFAULT NULL,
  `access_level` int(1) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `login`, `password_hash`, `access_level`, `name`, `surname`) VALUES
(1, 'administrator', '107d8f1d20142ff8cc9f7337adf9f092d2760ea2c8139edc6c5094ce0d073eca5db07a33e351fd7558300b5725f62970e79edf2da62eb2a206d4260609017105', 4, 'Jan', 'Adminowski'),
(2, 'maria1', 'b8240d0257734acd834805ca9604a9f24db8e5f7c1e35ca628268a29da010a3e2605a40e8f3f62253d8e9cc8a66ad164b85e0fd676d2e0f96d1670aea41c0d00', 1, 'Maria', 'Pierwsza'),
(3, 'zbigniew2', 'd5bd0ad8ac955b1f53b2595850f40434802eb5e7af9b1a95dc2ad0160bf63bf65eeca6631e214fd2c6f90c0f627711d1e51bca9a3b0f3a6d36c5b611ad31f49d', 2, 'Zbigniew', 'Drugi'),
(4, 'franciszek3', '26784f9c0cdf0cd6d2119d71b75012858fade33282f3b16ab480fd2a75cf046a4e17a088c1459abc50877fe949f3ef656fe25ca72116449633fba494d6330223', 3, 'Franciszek', 'Trzeci'),
(5, 'administrator2', '107d8f1d20142ff8cc9f7337adf9f092d2760ea2c8139edc6c5094ce0d073eca5db07a33e351fd7558300b5725f62970e79edf2da62eb2a206d4260609017105', 4, 'Radosław', 'Adminowicz'),
(6, 'katarzyna3', '26784f9c0cdf0cd6d2119d71b75012858fade33282f3b16ab480fd2a75cf046a4e17a088c1459abc50877fe949f3ef656fe25ca72116449633fba494d6330223', 3, 'Katarzyna', 'Trzecia'),
(7, 'mateusz1', 'b8240d0257734acd834805ca9604a9f24db8e5f7c1e35ca628268a29da010a3e2605a40e8f3f62253d8e9cc8a66ad164b85e0fd676d2e0f96d1670aea41c0d00', 1, 'Mateusz', 'Pierwszy'),
(8, 'administrator3', '107d8f1d20142ff8cc9f7337adf9f092d2760ea2c8139edc6c5094ce0d073eca5db07a33e351fd7558300b5725f62970e79edf2da62eb2a206d4260609017105', 4, 'Agata', 'Adminowska');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `employees`
--
ALTER TABLE `employees`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
