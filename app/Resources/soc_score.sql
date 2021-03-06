-- phpMyAdmin SQL Dump
-- version 4.3.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 25. Mrz 2015 um 21:49
-- Server-Version: 5.6.23
-- PHP-Version: 5.6.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `soc`
--

--
-- TRUNCATE Tabelle vor dem Einfügen `soc_score`
--

TRUNCATE TABLE `soc_score`;
--
-- Daten für Tabelle `soc_score`
--

INSERT INTO `soc_score` (`id`, `player`, `matchday`, `score`) VALUES
(1, 1, 1, 42),
(2, 2, 1, -4),
(3, 3, 1, 15),
(4, 4, 1, 29),
(5, 5, 1, 16),
(6, 6, 1, 37),
(7, 1, 2, 20),
(8, 2, 2, 15),
(9, 3, 2, 21),
(10, 4, 2, -5),
(11, 5, 2, 21),
(12, 6, 2, 45),
(13, 1, 3, 40),
(14, 2, 3, 28),
(15, 3, 3, 24),
(16, 4, 3, 41),
(17, 5, 3, 25),
(18, 6, 3, 39),
(19, 1, 4, 27),
(20, 2, 4, 16),
(21, 3, 4, -24),
(22, 4, 4, 2),
(23, 5, 4, -14),
(24, 6, 4, -4),
(25, 1, 5, 42),
(26, 2, 5, 3),
(27, 3, 5, 32),
(28, 4, 5, 66),
(29, 5, 5, 24),
(30, 6, 5, 14),
(31, 1, 6, 55),
(32, 2, 6, 36),
(33, 3, 6, 9),
(34, 4, 6, 35),
(35, 5, 6, 0),
(36, 6, 6, 8),
(37, 1, 7, 24),
(38, 2, 7, -4),
(39, 3, 7, 0),
(40, 4, 7, 20),
(41, 5, 7, 46),
(42, 6, 7, 44),
(43, 1, 8, 59),
(44, 2, 8, 26),
(45, 3, 8, -50),
(46, 4, 8, 22),
(47, 5, 8, 3),
(48, 6, 8, 26),
(49, 1, 9, 53),
(50, 2, 9, 30),
(51, 3, 9, 6),
(52, 4, 9, 2),
(53, 5, 9, 16),
(54, 6, 9, 36),
(55, 1, 10, 40),
(56, 2, 10, 44),
(57, 3, 10, 25),
(58, 4, 10, 41),
(59, 5, 10, 25),
(60, 6, 10, 0),
(61, 1, 11, 35),
(62, 2, 11, 11),
(63, 3, 11, 7),
(64, 4, 11, 15),
(65, 5, 11, 48),
(66, 6, 11, 21),
(67, 1, 12, 27),
(68, 2, 12, 22),
(69, 3, 12, -4),
(70, 4, 12, 32),
(71, 5, 12, 19),
(72, 6, 12, 6),
(73, 1, 13, 41),
(74, 2, 13, 41),
(75, 3, 13, -4),
(76, 4, 13, 24),
(77, 5, 13, 10),
(78, 6, 13, 42),
(79, 1, 14, 28),
(80, 2, 14, 59),
(81, 3, 14, 3),
(82, 4, 14, 13),
(83, 5, 14, 28),
(84, 6, 14, 25),
(85, 1, 15, 22),
(86, 2, 15, 31),
(87, 3, 15, 5),
(88, 4, 15, 20),
(89, 5, 15, 21),
(90, 6, 15, 21),
(91, 1, 16, 31),
(92, 2, 16, 32),
(93, 3, 16, 39),
(94, 4, 16, 42),
(95, 5, 16, 35),
(96, 6, 16, 65),
(97, 1, 17, 27),
(98, 2, 17, 15),
(99, 3, 17, 14),
(100, 4, 17, 3),
(101, 5, 17, 5),
(102, 6, 17, 8),
(103, 1, 18, 19),
(104, 2, 18, 1),
(105, 3, 18, -2),
(106, 4, 18, -3),
(107, 5, 18, -11),
(108, 6, 18, 14),
(109, 1, 19, 33),
(110, 2, 19, 2),
(111, 3, 19, -50),
(112, 4, 19, 17),
(113, 5, 19, 23),
(114, 6, 19, 10),
(115, 1, 20, 35),
(116, 2, 20, 30),
(117, 3, 20, -17),
(118, 4, 20, 26),
(119, 5, 20, -2),
(120, 6, 20, 11),
(121, 1, 21, 14),
(122, 2, 21, 32),
(123, 3, 21, 18),
(124, 4, 21, 57),
(125, 5, 21, 17),
(126, 6, 21, 25),
(127, 1, 22, 10),
(128, 2, 22, 51),
(129, 3, 22, -50),
(130, 4, 22, 26),
(131, 5, 22, 38),
(132, 6, 22, 9),
(133, 1, 23, 55),
(134, 2, 23, 65),
(135, 3, 23, -5),
(136, 4, 23, 33),
(137, 5, 23, 39),
(138, 6, 23, 39),
(139, 1, 24, 36),
(140, 2, 24, 18),
(141, 3, 24, 63),
(142, 4, 24, -28),
(143, 5, 24, -9),
(144, 6, 24, 25),
(145, 1, 25, 92),
(146, 2, 25, 27),
(147, 3, 25, 35),
(148, 4, 25, 4),
(149, 5, 25, 13),
(150, 6, 25, 45);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
