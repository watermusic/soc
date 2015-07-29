-- phpMyAdmin SQL Dump
-- version 4.3.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 25. Mrz 2015 um 21:47
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
-- TRUNCATE Tabelle vor dem Einfügen `soc_user`
--

TRUNCATE TABLE `soc_user`;
--
-- Daten für Tabelle `soc_user`
--

INSERT INTO `soc_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`) VALUES
(1, 'lutz', 'lutz', 'lutz.bicker@googlemail.com', 'lutz.bicker@googlemail.com', 1, '27y0x2llll5wcgoso4gkwgw8o4ssocw', '3YFWFj7brlwEqaHyiQthEX6vbFMotvOoAHEzMGjXNFKRQ02AhsJux55JR0Bd+D8bAzupkzz1oRWxCudykkJQKw==', '2015-03-20 21:57:56', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 0, NULL),
(2, 'christian', 'christian', 'christian.luepke', 'christian.luepke', 1, '1tkkd46zx78k44c0gc8kk4wsg4w8skc', 'Op+GKs7V2nrkXXdUyfBa2iQt/ydx2vkfvRp2UAcvAMF1wDgKSqg2kvFVWPtdYSw3BvpFyI+hu8llz02lCS+DJw==', NULL, 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL),
(3, 'rainer', 'rainer', 'rainer.bosse@googlemail.com', 'rainer.bosse@googlemail.com', 1, '8hdymmbxz14wcs4c8k8cg4gskgskgwo', 'nxJOfZrsiZR/Q4V7tpmpOIPtQ97sUtQgbWQiLLzR6tGBD6/hbYPClbgATQKhnVbLsLHKKHY2Wipq5fkm2gqg6w==', NULL, 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL),
(4, 'torsten', 'torsten', 'torsten.kreinhop@googlemail.com', 'torsten.kreinhop@googlemail.com', 1, 'al8vli4cvlcs4s04cosw8cwscwsokww', 'CbOQcNgj0gDHIOG7HFwCEA69QKyp3e/jRx8a56kNLfZuE60XED72oq0PyqWsSSafxmRcoHLY1VN9glIFeslXkw==', NULL, 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL),
(5, 'alex', 'alex', 'alex.peters@googlemail.com', 'alex.peters@googlemail.com', 1, 'mazf0tfbls00gk04coco0c48gog0gws', 'uV2G5Dot1smIuDxjQXZZSVt5jGlDenbzHMn/qA4Q4GESTdXrshDgZOPIA0aUIZootnU7aJ1Oc3ArNnr0wdclcw==', NULL, 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL),
(6, 'jens', 'jens', 'jens.gimmler@googlemail.com', 'jens.gimmler@googlemail.com', 1, '58rrhgsutkw0kwgwwgk0048g80cocow', 'O5LLhVe4Cg7MNEj6uIsEbr8YILgFVDARpQ76zmn9047zVWJG1+dCRc2lqSqRENnZJ4OrXOY8fv3IXoPia/DZ1g==', NULL, 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
