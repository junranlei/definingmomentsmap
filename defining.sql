-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 05, 2021 at 11:32 PM
-- Server version: 5.7.25
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `defining`
--

-- --------------------------------------------------------

--
-- Table structure for table `apis`
--

CREATE TABLE `apis` (
  `id` int(5) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `apikey` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `apis`
--

INSERT INTO `apis` (`id`, `name`, `description`, `url`, `apikey`) VALUES
(1, 'NMA Collection API - Object', 'National Museum Australia API - Object', 'https://data.nma.gov.au/object?text={querystring}&format=simple', ''),
(2, 'NMA Collection API - Party', 'National Museum Australia API - Party', 'https://data.nma.gov.au/party?text={querystring}&format=simple', ''),
(3, 'NMA Convict Love Tokens API', 'National Museum of Australia\'s Convict Love Tokens API', 'http://love-tokens.nma.gov.au/api/search.json?q={querystring}', '');

-- --------------------------------------------------------

--
-- Table structure for table `audit_data`
--

CREATE TABLE `audit_data` (
  `id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `audit_data`
--


-- --------------------------------------------------------

--
-- Table structure for table `audit_entry`
--

CREATE TABLE `audit_entry` (
  `id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `user_id` int(11) DEFAULT '0',
  `duration` float DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `request_method` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ajax` int(1) NOT NULL DEFAULT '0',
  `route` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `memory_max` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `audit_entry`
--


-- --------------------------------------------------------

--
-- Table structure for table `audit_error`
--

CREATE TABLE `audit_error` (
  `id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `code` int(11) DEFAULT '0',
  `file` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(11) DEFAULT NULL,
  `trace` blob,
  `hash` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_javascript`
--

CREATE TABLE `audit_javascript` (
  `id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `origin` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_mail`
--

CREATE TABLE `audit_mail` (
  `id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `successful` int(11) NOT NULL,
  `from` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reply` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bcc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` blob,
  `html` blob,
  `data` longblob
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `id` int(11) NOT NULL,
  `entry_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `model_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_value` text COLLATE utf8_unicode_ci,
  `new_value` text COLLATE utf8_unicode_ci,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('SysAdmin', '1', 1589864799);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('disableHist', 2, 'disable historical fact', 'canDisableHist', NULL, 1592265316, 1592265316),
('disableMap', 2, 'disable map', 'canDisableMap', NULL, 1592284925, 1592284925),
('disableMedia', 2, 'disable media', 'canDisableMedia', NULL, 1592377486, 1592377486),
('SysAdmin', 1, 'System Administrator', NULL, NULL, 1589864593, 1589864637),
('SysAuthor', 1, 'System Author can edit and create.', NULL, NULL, 1589864629, 1592794256),
('updateHist', 2, 'update historical fact', 'isEditableHist', NULL, 1591156309, 1591156346),
('updateMap', 2, 'update map', 'isEditableMap', NULL, 1591145274, 1591145274),
('updateMedia', 2, 'update media', 'isEditableMedia', NULL, 1592377508, 1592377508),
('updateProfile', 2, 'update profile', 'isEditableProfile', NULL, 1592794247, 1592794247);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('SysAuthor', 'disableHist'),
('SysAuthor', 'disableMap'),
('SysAuthor', 'disableMedia'),
('SysAdmin', 'SysAuthor'),
('SysAuthor', 'updateHist'),
('SysAuthor', 'updateMap'),
('SysAuthor', 'updateMedia'),
('SysAuthor', 'updateProfile');

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_rule`
--

INSERT INTO `auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES
('canDisableHist', 0x4f3a32373a22636f6d6d6f6e5c726261635c4869737464697361626c6552756c65223a333a7b733a343a226e616d65223b733a31343a2263616e44697361626c6548697374223b733a393a22637265617465644174223b693a313539323236353238323b733a393a22757064617465644174223b693a313539323236353238323b7d, 1592265282, 1592265282),
('canDisableMap', 0x4f3a32363a22636f6d6d6f6e5c726261635c4d617064697361626c6552756c65223a333a7b733a343a226e616d65223b733a31333a2263616e44697361626c654d6170223b733a393a22637265617465644174223b693a313539323238343839343b733a393a22757064617465644174223b693a313539323238343839343b7d, 1592284894, 1592284894),
('canDisableMedia', 0x4f3a32383a22636f6d6d6f6e5c726261635c4d6564696164697361626c6552756c65223a333a7b733a343a226e616d65223b733a31353a2263616e44697361626c654d65646961223b733a393a22637265617465644174223b693a313539323337373432393b733a393a22757064617465644174223b693a313539323337373432393b7d, 1592377429, 1592377429),
('isEditableHist', 0x4f3a32363a22636f6d6d6f6e5c726261635c4869737475706461746552756c65223a333a7b733a343a226e616d65223b733a31343a2269734564697461626c6548697374223b733a393a22637265617465644174223b693a313539313135363333373b733a393a22757064617465644174223b693a313539313135363333373b7d, 1591156337, 1591156337),
('isEditableMap', 0x4f3a32353a22636f6d6d6f6e5c726261635c4d617075706461746552756c65223a333a7b733a343a226e616d65223b733a31333a2269734564697461626c654d6170223b733a393a22637265617465644174223b693a313539313134353234333b733a393a22757064617465644174223b693a313539313134353234333b7d, 1591145243, 1591145243),
('isEditableMedia', 0x4f3a32373a22636f6d6d6f6e5c726261635c4d6564696175706461746552756c65223a333a7b733a343a226e616d65223b733a31353a2269734564697461626c654d65646961223b733a393a22637265617465644174223b693a313539323337373435373b733a393a22757064617465644174223b693a313539323337373435373b7d, 1592377457, 1592377457),
('isEditableProfile', 0x4f3a32393a22636f6d6d6f6e5c726261635c50726f66696c6575706461746552756c65223a333a7b733a343a226e616d65223b733a31373a2269734564697461626c6550726f66696c65223b733a393a22637265617465644174223b693a313539323739343231363b733a393a22757064617465644174223b693a313539323739343231363b7d, 1592794216, 1592794216);

-- --------------------------------------------------------

--
-- Table structure for table `feature`
--

CREATE TABLE `feature` (
  `id` int(10) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `geojson` text COLLATE utf8_unicode_ci,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `histId` int(10) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flag`
--

CREATE TABLE `flag` (
  `id` int(10) NOT NULL,
  `model` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `modelId` int(10) NOT NULL,
  `times` int(5) NOT NULL DEFAULT '1',
  `timeCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timeUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flagNote`
--

CREATE TABLE `flagNote` (
  `id` int(10) NOT NULL,
  `flagId` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `note` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `historicalAssign`
--

CREATE TABLE `historicalAssign` (
  `histId` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `assignedTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` int(2) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `historicalFact`
--

CREATE TABLE `historicalFact` (
  `id` int(10) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `dateEnded` date DEFAULT NULL,
  `timeCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `urls` text COLLATE utf8_unicode_ci,
  `mainMediaId` int(10) DEFAULT NULL,
  `right2Link` int(1) NOT NULL DEFAULT '1',
  `publicPermission` int(1) NOT NULL DEFAULT '1',
  `status` int(1) NOT NULL DEFAULT '1',
  `timeUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `flag` int(1) NOT NULL DEFAULT '0',
  `source` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `historicalMapLink`
--

CREATE TABLE `historicalMapLink` (
  `histId` int(10) NOT NULL,
  `mapId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `historicalMediaLink`
--

CREATE TABLE `historicalMediaLink` (
  `histId` int(10) NOT NULL,
  `mediaId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `historicalRelated`
--

CREATE TABLE `historicalRelated` (
  `histId1` int(10) NOT NULL,
  `histId2` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `layer`
--

CREATE TABLE `layer` (
  `id` int(10) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `type` int(2) NOT NULL DEFAULT '1',
  `nameOrUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `mapId` int(10) NOT NULL,
  `date` date NOT NULL,
  `externalId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `map`
--

CREATE TABLE `map` (
  `id` int(10) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `timeCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timeUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `publicPermission` int(1) NOT NULL DEFAULT '1',
  `status` int(1) NOT NULL DEFAULT '1',
  `flag` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mapAssign`
--

CREATE TABLE `mapAssign` (
  `mapId` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `assignedTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` int(2) NOT NULL DEFAULT '1',
  `qrCode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(10) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `type` int(2) NOT NULL DEFAULT '1',
  `nameOrUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `right2Link` int(1) NOT NULL DEFAULT '1',
  `ownerId` int(10) NOT NULL,
  `isUrl` int(1) NOT NULL DEFAULT '0',
  `creator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `publicPermission` int(1) NOT NULL DEFAULT '1',
  `flag` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migration0`
--

CREATE TABLE `migration0` (
  `version` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migration0`
--

INSERT INTO `migration0` (`version`, `apply_time`) VALUES
('Da\\User\\Migration\\m000000_000001_create_user_table', 1589854610),
('Da\\User\\Migration\\m000000_000002_create_profile_table', 1589854610),
('Da\\User\\Migration\\m000000_000003_create_social_account_table', 1589854610),
('Da\\User\\Migration\\m000000_000004_create_token_table', 1589854611),
('Da\\User\\Migration\\m000000_000005_add_last_login_at', 1589854611),
('Da\\User\\Migration\\m000000_000006_add_two_factor_fields', 1589854611),
('Da\\User\\Migration\\m000000_000007_enable_password_expiration', 1589854611),
('Da\\User\\Migration\\m000000_000008_add_last_login_ip', 1589854611),
('Da\\User\\Migration\\m000000_000009_add_gdpr_consent_fields', 1589854611),
('m000000_000000_base', 1587342287),
('m130524_201442_init', 1587342290),
('m140506_102106_rbac_init', 1589854734),
('m150626_000001_create_audit_entry', 1591081582),
('m150626_000002_create_audit_data', 1591081582),
('m150626_000003_create_audit_error', 1591081582),
('m150626_000004_create_audit_trail', 1591081583),
('m150626_000005_create_audit_javascript', 1591081583),
('m150626_000006_create_audit_mail', 1591081583),
('m150714_000001_alter_audit_data', 1591081583),
('m170126_000001_alter_audit_mail', 1591081583),
('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1589854734),
('m180523_151638_rbac_updates_indexes_without_prefix', 1589854734),
('m190124_110200_add_verification_token_column_to_user_table', 1587342291);

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gravatar_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gravatar_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`user_id`, `name`, `public_email`, `gravatar_email`, `gravatar_id`, `location`, `website`, `timezone`, `bio`) VALUES
(1, 'Admin', 'test@test.com', '', 'd41d8cd98f00b204e9800998ecf8427e', 'canberra', 'http://test.com', NULL, 'test');

-- --------------------------------------------------------

--
-- Table structure for table `social_account`
--

CREATE TABLE `social_account` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `user_id` int(11) DEFAULT NULL,
  `code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `unconfirmed_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flags` int(11) NOT NULL DEFAULT '0',
  `confirmed_at` int(11) DEFAULT NULL,
  `blocked_at` int(11) DEFAULT NULL,
  `updated_at` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `last_login_at` int(11) DEFAULT NULL,
  `last_login_ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_tf_key` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_tf_enabled` tinyint(1) DEFAULT '0',
  `password_changed_at` int(11) DEFAULT NULL,
  `gdpr_consent` tinyint(1) DEFAULT '0',
  `gdpr_consent_date` int(11) DEFAULT NULL,
  `gdpr_deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password_hash`, `auth_key`, `unconfirmed_email`, `registration_ip`, `flags`, `confirmed_at`, `blocked_at`, `updated_at`, `created_at`, `last_login_at`, `last_login_ip`, `auth_tf_key`, `auth_tf_enabled`, `password_changed_at`, `gdpr_consent`, `gdpr_consent_date`, `gdpr_deleted`) VALUES
(1, 'admin', 'junran.lei@anu.edu.au', '$2y$10$HPcLZhd7R8vq62XrcI7EmO4IsMDP9MFLjr0bdOEVmnysgCAcIul3G', '25uhPISwi73Se15v25g0LqpZvQIX836e', NULL, '::1', 0, 1589855462, NULL, 1589864848, 1589855437, 1633476485, '127.0.0.1', '', 0, 1589864848, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `userLog`
--

CREATE TABLE `userLog` (
  `id` int(10) NOT NULL,
  `pageAction` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `accessTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apis`
--
ALTER TABLE `apis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit_data`
--
ALTER TABLE `audit_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_audit_data_entry_id` (`entry_id`);

--
-- Indexes for table `audit_entry`
--
ALTER TABLE `audit_entry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_route` (`route`);

--
-- Indexes for table `audit_error`
--
ALTER TABLE `audit_error`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_audit_error_entry_id` (`entry_id`),
  ADD KEY `idx_file` (`file`(180)),
  ADD KEY `idx_emailed` (`emailed`);

--
-- Indexes for table `audit_javascript`
--
ALTER TABLE `audit_javascript`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_audit_javascript_entry_id` (`entry_id`);

--
-- Indexes for table `audit_mail`
--
ALTER TABLE `audit_mail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_audit_mail_entry_id` (`entry_id`);

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_audit_trail_entry_id` (`entry_id`),
  ADD KEY `idx_audit_user_id` (`user_id`),
  ADD KEY `idx_audit_trail_field` (`model`,`model_id`,`field`),
  ADD KEY `idx_audit_trail_action` (`action`);

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `idx-auth_assignment-user_id` (`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `feature`
--
ALTER TABLE `feature`
  ADD PRIMARY KEY (`id`),
  ADD KEY `histId` (`histId`);

--
-- Indexes for table `flag`
--
ALTER TABLE `flag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flagNote`
--
ALTER TABLE `flagNote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `flagNote_flagId` (`flagId`),
  ADD KEY `flagNote_userId` (`userId`);

--
-- Indexes for table `historicalAssign`
--
ALTER TABLE `historicalAssign`
  ADD PRIMARY KEY (`histId`,`userId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `historicalFact`
--
ALTER TABLE `historicalFact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mainMediaId` (`mainMediaId`);
ALTER TABLE `historicalFact` ADD FULLTEXT KEY `title` (`title`);

--
-- Indexes for table `historicalMapLink`
--
ALTER TABLE `historicalMapLink`
  ADD PRIMARY KEY (`histId`,`mapId`),
  ADD KEY `mapId` (`mapId`);

--
-- Indexes for table `historicalMediaLink`
--
ALTER TABLE `historicalMediaLink`
  ADD PRIMARY KEY (`histId`,`mediaId`),
  ADD KEY `mediaId` (`mediaId`);

--
-- Indexes for table `historicalRelated`
--
ALTER TABLE `historicalRelated`
  ADD PRIMARY KEY (`histId1`,`histId2`),
  ADD KEY `histId2` (`histId2`);

--
-- Indexes for table `layer`
--
ALTER TABLE `layer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mapId` (`mapId`);

--
-- Indexes for table `map`
--
ALTER TABLE `map`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mapAssign`
--
ALTER TABLE `mapAssign`
  ADD PRIMARY KEY (`mapId`,`userId`),
  ADD KEY `mapassign_ibfk_2` (`userId`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_ibfk_1` (`ownerId`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `migration0`
--
ALTER TABLE `migration0`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `social_account`
--
ALTER TABLE `social_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_social_account_provider_client_id` (`provider`,`client_id`),
  ADD UNIQUE KEY `idx_social_account_code` (`code`),
  ADD KEY `fk_social_account_user` (`user_id`);

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD UNIQUE KEY `idx_token_user_id_code_type` (`user_id`,`code`,`type`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_user_username` (`username`),
  ADD UNIQUE KEY `idx_user_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apis`
--
ALTER TABLE `apis`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `audit_data`
--
ALTER TABLE `audit_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `audit_entry`
--
ALTER TABLE `audit_entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `audit_error`
--
ALTER TABLE `audit_error`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_javascript`
--
ALTER TABLE `audit_javascript`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_mail`
--
ALTER TABLE `audit_mail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feature`
--
ALTER TABLE `feature`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flag`
--
ALTER TABLE `flag`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flagNote`
--
ALTER TABLE `flagNote`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `historicalFact`
--
ALTER TABLE `historicalFact`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `layer`
--
ALTER TABLE `layer`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `map`
--
ALTER TABLE `map`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_account`
--
ALTER TABLE `social_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_data`
--
ALTER TABLE `audit_data`
  ADD CONSTRAINT `fk_audit_data_entry_id` FOREIGN KEY (`entry_id`) REFERENCES `audit_entry` (`id`);

--
-- Constraints for table `audit_error`
--
ALTER TABLE `audit_error`
  ADD CONSTRAINT `fk_audit_error_entry_id` FOREIGN KEY (`entry_id`) REFERENCES `audit_entry` (`id`);

--
-- Constraints for table `audit_javascript`
--
ALTER TABLE `audit_javascript`
  ADD CONSTRAINT `fk_audit_javascript_entry_id` FOREIGN KEY (`entry_id`) REFERENCES `audit_entry` (`id`);

--
-- Constraints for table `audit_mail`
--
ALTER TABLE `audit_mail`
  ADD CONSTRAINT `fk_audit_mail_entry_id` FOREIGN KEY (`entry_id`) REFERENCES `audit_entry` (`id`);

--
-- Constraints for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD CONSTRAINT `fk_audit_trail_entry_id` FOREIGN KEY (`entry_id`) REFERENCES `audit_entry` (`id`);

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feature`
--
ALTER TABLE `feature`
  ADD CONSTRAINT `feature_ibfk_1` FOREIGN KEY (`histId`) REFERENCES `historicalFact` (`id`);

--
-- Constraints for table `flagNote`
--
ALTER TABLE `flagNote`
  ADD CONSTRAINT `flagNote_flagId` FOREIGN KEY (`flagId`) REFERENCES `flag` (`id`),
  ADD CONSTRAINT `flagNote_userId` FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

--
-- Constraints for table `historicalFact`
--
ALTER TABLE `historicalFact`
  ADD CONSTRAINT `historicalfact_ibfk_1` FOREIGN KEY (`mainMediaId`) REFERENCES `media` (`id`);

--
-- Constraints for table `historicalMapLink`
--
ALTER TABLE `historicalMapLink`
  ADD CONSTRAINT `historicalmaplink_ibfk_1` FOREIGN KEY (`histId`) REFERENCES `historicalFact` (`id`),
  ADD CONSTRAINT `historicalmaplink_ibfk_2` FOREIGN KEY (`mapId`) REFERENCES `map` (`id`);

--
-- Constraints for table `historicalMediaLink`
--
ALTER TABLE `historicalMediaLink`
  ADD CONSTRAINT `historicalmedialink_ibfk_1` FOREIGN KEY (`histId`) REFERENCES `historicalFact` (`id`),
  ADD CONSTRAINT `historicalmedialink_ibfk_2` FOREIGN KEY (`mediaId`) REFERENCES `media` (`id`);

--
-- Constraints for table `historicalRelated`
--
ALTER TABLE `historicalRelated`
  ADD CONSTRAINT `historicalrelated_ibfk_1` FOREIGN KEY (`histId1`) REFERENCES `historicalFact` (`id`),
  ADD CONSTRAINT `historicalrelated_ibfk_2` FOREIGN KEY (`histId2`) REFERENCES `historicalFact` (`id`);

--
-- Constraints for table `mapAssign`
--
ALTER TABLE `mapAssign`
  ADD CONSTRAINT `mapassign_ibfk_1` FOREIGN KEY (`mapId`) REFERENCES `map` (`id`),
  ADD CONSTRAINT `mapassign_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`ownerId`) REFERENCES `user` (`id`);

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `fk_profile_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `social_account`
--
ALTER TABLE `social_account`
  ADD CONSTRAINT `fk_social_account_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `fk_token_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
