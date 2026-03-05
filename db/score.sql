-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 05 Mar 2026, 21:11:30
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `score`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin_score`
--

CREATE TABLE `admin_score` (
  `admin_id` int(11) NOT NULL,
  `eposta` varchar(100) NOT NULL,
  `password` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `administrator` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pit_scout_data`
--

CREATE TABLE `pit_scout_data` (
  `id` int(11) NOT NULL,
  `tournament_key` varchar(50) NOT NULL,
  `team_key` varchar(50) NOT NULL,
  `scout_name` varchar(100) NOT NULL,
  `robot_weight` decimal(5,2) DEFAULT NULL,
  `robot_dimensions` varchar(20) DEFAULT NULL,
  `drivetrain_type` varchar(50) DEFAULT NULL,
  `swerve_type` varchar(50) DEFAULT NULL,
  `mechanism_type` varchar(20) DEFAULT NULL,
  `auto_climb` tinyint(1) DEFAULT 0,
  `teleop_climb` tinyint(1) DEFAULT 0,
  `scout_comments` text DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `score_weights`
--

CREATE TABLE `score_weights` (
  `id` int(11) NOT NULL,
  `epa` float NOT NULL DEFAULT 30,
  `auto` float NOT NULL DEFAULT 20,
  `teleop` float NOT NULL DEFAULT 40,
  `climb` float NOT NULL DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `score_weights`
--

INSERT INTO `score_weights` (`id`, `epa`, `auto`, `teleop`, `climb`) VALUES
(1, 35, 20, 40, 5);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `scout_data`
--

CREATE TABLE `scout_data` (
  `id` int(11) NOT NULL,
  `tournament_key` varchar(50) NOT NULL,
  `match_key` varchar(50) NOT NULL,
  `team_key` varchar(50) NOT NULL,
  `scout_name` varchar(100) NOT NULL,
  `auto_fuel` int(11) DEFAULT 0,
  `auto_climb` varchar(20) DEFAULT 'none',
  `auto_path` longtext DEFAULT NULL,
  `teleop_fuel` int(11) DEFAULT 0,
  `teleop_shooting` varchar(20) DEFAULT NULL,
  `teleop_climb` varchar(20) DEFAULT 'none',
  `teleop_driver` varchar(20) DEFAULT NULL,
  `teleop_robot_role` varchar(20) DEFAULT NULL,
  `teleop_defense_quality` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admin_score`
--
ALTER TABLE `admin_score`
  ADD PRIMARY KEY (`admin_id`);

--
-- Tablo için indeksler `pit_scout_data`
--
ALTER TABLE `pit_scout_data`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `score_weights`
--
ALTER TABLE `score_weights`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `scout_data`
--
ALTER TABLE `scout_data`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin_score`
--
ALTER TABLE `admin_score`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `pit_scout_data`
--
ALTER TABLE `pit_scout_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `score_weights`
--
ALTER TABLE `score_weights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `scout_data`
--
ALTER TABLE `scout_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
