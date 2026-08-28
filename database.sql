-- ==========================================================
-- FRC SCOUT APP - Database Schema
-- Version: 2.0 (Multi-Tenant & TBA API Ready)
-- ==========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Table: admin_score (Users / Team Members)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_score` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `eposta` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `team_number` varchar(50) NOT NULL,
  `administrator` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `uniq_eposta` (`eposta`),
  KEY `idx_team_number` (`team_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: team_settings (Team Configuration & TBA API Keys)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `team_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_key` varchar(50) NOT NULL,
  `tba_api_key` text DEFAULT NULL,
  `active_year` int(11) NOT NULL DEFAULT 2026,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_team_key` (`team_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: password_resets (Password Reset Tokens)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eposta` varchar(191) NOT NULL,
  `token` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `idx_eposta` (`eposta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: team_join_requests (Team Transfer & Join Requests)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `team_join_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(150) NOT NULL,
  `user_email` varchar(191) NOT NULL,
  `current_team` varchar(50) NOT NULL,
  `target_team` varchar(50) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `request_note` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_target_team_status` (`target_team`,`status`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: score_weights (Custom Strategy Weights)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `score_weights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_key` varchar(50) NOT NULL,
  `epa` decimal(5,2) NOT NULL DEFAULT 30.00,
  `auto` decimal(5,2) NOT NULL DEFAULT 20.00,
  `teleop` decimal(5,2) NOT NULL DEFAULT 40.00,
  `climb` decimal(5,2) NOT NULL DEFAULT 10.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_team_weight` (`team_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: scout_data (Match Scouting Records)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `scout_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tournament_key` varchar(50) NOT NULL,
  `match_key` varchar(50) NOT NULL,
  `team_key` varchar(50) NOT NULL,
  `scouted_by_team` varchar(50) NOT NULL,
  `is_practice` tinyint(1) NOT NULL DEFAULT 0,
  `scout_name` varchar(100) DEFAULT NULL,
  `auto_fuel` int(11) NOT NULL DEFAULT 0,
  `auto_climb` varchar(50) NOT NULL DEFAULT 'none',
  `auto_path` text DEFAULT NULL,
  `teleop_fuel` int(11) NOT NULL DEFAULT 0,
  `teleop_climb` varchar(50) NOT NULL DEFAULT 'none',
  `teleop_feed_quality` varchar(50) DEFAULT NULL,
  `teleop_damper_quality` varchar(50) DEFAULT NULL,
  `cycle_speed` varchar(50) DEFAULT NULL,
  `teleop_robot_role` varchar(50) DEFAULT NULL,
  `teleop_defense_quality` varchar(50) DEFAULT NULL,
  `driver_evasion` varchar(50) DEFAULT NULL,
  `breakdown_reason` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_scout_search` (`tournament_key`,`team_key`,`scouted_by_team`),
  KEY `idx_match_team` (`match_key`,`team_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: pit_scout_data (Pit Scouting & Hardware Specs)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pit_scout_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tournament_key` varchar(50) NOT NULL,
  `team_key` varchar(50) NOT NULL,
  `scouted_by_team` varchar(50) NOT NULL,
  `scout_name` varchar(100) DEFAULT NULL,
  `robot_weight` decimal(5,2) DEFAULT NULL,
  `robot_dimensions` varchar(100) DEFAULT NULL,
  `drivetrain_type` varchar(100) DEFAULT NULL,
  `swerve_type` varchar(100) DEFAULT NULL,
  `mechanism_type` varchar(100) DEFAULT NULL,
  `hopper_capacity` int(11) DEFAULT 0,
  `auto_climb` tinyint(1) DEFAULT 0,
  `teleop_climb` tinyint(1) DEFAULT 0,
  `scout_comments` text DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `bps` decimal(4,1) DEFAULT 0.0,
  `mentor_comments` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_pit_entry` (`tournament_key`,`team_key`,`scouted_by_team`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
