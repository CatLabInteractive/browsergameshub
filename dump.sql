-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 05 jan 2017 om 14:12
-- Serverversie: 5.5.52-0ubuntu0.12.04.1-log
-- PHP-versie: 5.3.10-1ubuntu3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `k000171_3_browsergameshub`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `b_browsergames`
--

CREATE TABLE IF NOT EXISTS `b_browsergames` (
  `b_id` int(11) NOT NULL AUTO_INCREMENT,
  `b_token` varchar(50) DEFAULT NULL,
  `b_name` varchar(100) NOT NULL,
  `b_genre` varchar(50) NOT NULL,
  `b_url` varchar(255) NOT NULL,
  `b_lastCheck` datetime NOT NULL,
  `b_failures` tinyint(4) NOT NULL,
  `b_isValid` tinyint(1) NOT NULL,
  `b_setting` varchar(30) NOT NULL,
  `b_status` varchar(30) NOT NULL,
  `b_timing` varchar(30) NOT NULL,
  `b_openid` tinyint(1) NOT NULL DEFAULT '0',
  `b_revisit` tinyint(4) NOT NULL,
  `b_certified` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`b_id`),
  UNIQUE KEY `b_token` (`b_token`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102 ;

--
-- Gegevens worden uitgevoerd voor tabel `b_browsergames`
--

INSERT INTO `b_browsergames` (`b_id`, `b_token`, `b_name`, `b_genre`, `b_url`, `b_lastCheck`, `b_failures`, `b_isValid`, `b_setting`, `b_status`, `b_timing`, `b_openid`, `b_revisit`, `b_certified`) VALUES
(2, 'dolumar', 'Dolumar', 'strategy', 'http://master.dolumar.be/serverlist/list/', '2017-01-05 03:00:03', 0, 1, 'fantasy', 'open', 'realtime', 1, 1, '0'),
(5, 'aragon-online', 'Aragon Online', 'strategy', 'http://aragon-online.net/info.xml', '2017-01-04 16:00:10', 0, 1, 'fantasy', 'beta', 'ticks', 0, 1, '0'),
(76, 'aberoth', 'Aberoth', 'rpg', 'http://www.aberoth.com/browser-games-hub-info.xml', '2017-01-04 16:00:05', 0, 1, 'fantasy', 'release', 'realtime', 0, 1, '0'),
(41, 'world-of-avalon', 'World Of Avalon', 'rpg', 'http://www.worldofavalon.net/gameinfo.xml', '2017-01-04 16:00:12', 0, 1, 'ancient world', 'alpha', 'realtime', 0, 1, '0'),
(43, 'devanaita', 'DevanaIta', '', 'http://devanaita.altervista.org/info.xml', '2017-01-04 16:00:07', 0, 1, '', '', '', 0, 1, '0'),
(42, 'visual-utopia', 'Visual Utopia', 'strategy', 'http://visual-utopia.com/bghub.xml', '2017-01-04 16:00:08', 0, 1, 'fantasy', 'release', 'ticks', 0, 1, '0'),
(101, 'quizwitz', 'QuizWitz', 'trivia', 'http://www.quizwitz.com/info.xml', '2017-01-04 16:00:11', 0, 1, '', '', '', 0, 1, '0'),
(91, 'warmides-online', 'Warmides Online', 'strategy', 'http://www.warmides.com/promo/info.xml', '2017-01-05 09:00:05', 0, 1, 'fantasy', 'beta', 'realtime', 0, 1, '0'),
(75, 'kingdoms', 'Crown of Conquest', 'rpg', 'http://www.kingdoms-game.com/static/browser_games_hub.xml', '2017-01-05 09:00:04', 0, 1, 'fantasy', 'beta', 'rounds', 0, 1, '0'),
(88, 'solar-empire-infinium', 'Solar Empire Infinium', 'rpg', 'http://sei.weblulz.net/bgh.xml', '2017-01-04 16:00:07', 0, 1, 'space', 'release', 'rounds', 0, 1, '0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;