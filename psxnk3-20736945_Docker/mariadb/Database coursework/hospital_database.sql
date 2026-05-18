-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Generation Time: Dec 11, 2025 at 10:55 PM
-- Server version: 10.8.8-MariaDB-1:10.8.8+maria~ubu2204
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `changes_log`
--

CREATE TABLE `changes_log` (
  `logid` int(11) NOT NULL,
  `userid` varchar(50) DEFAULT NULL,
  `actiontype` varchar(50) DEFAULT NULL,
  `targetid` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ipaddress` varchar(45) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `changes_log`
--

INSERT INTO `changes_log` (`logid`, `userid`, `actiontype`, `targetid`, `details`, `ipaddress`, `timestamp`) VALUES
(1, 'SYSTEM', 'SUBMIT_REQUEST', 'QM289', 'Doctor submitted a monthly parking request', '192.168.65.1', '2025-12-07 13:50:02'),
(2, 'SYSTEM', 'SUBMIT_REQUEST', 'QM299', 'Doctor submitted a yearly parking request', '192.168.65.1', '2025-12-07 13:53:07'),
(3, 'QM345', 'SUBMIT_REQUEST', 'QM345', 'Doctor submitted a monthly parking request', '192.168.65.1', '2025-12-07 14:02:26'),
(4, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: n', '192.168.65.1', '2025-12-07 16:08:47'),
(5, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: ne', '192.168.65.1', '2025-12-07 16:09:04'),
(6, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: ne', '192.168.65.1', '2025-12-07 16:13:48'),
(7, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: ma', '192.168.65.1', '2025-12-07 16:13:56'),
(8, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: ma', '192.168.65.1', '2025-12-07 16:20:44'),
(9, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: ma', '192.168.65.1', '2025-12-07 16:21:14'),
(10, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: ma', '192.168.65.1', '2025-12-07 16:21:19'),
(11, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: ma', '192.168.65.1', '2025-12-07 16:27:42'),
(12, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: ma', '192.168.65.1', '2025-12-07 16:27:57'),
(13, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: ko', '192.168.65.1', '2025-12-07 16:28:14'),
(14, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: co', '192.168.65.1', '2025-12-07 16:28:20'),
(15, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: cl', '192.168.65.1', '2025-12-07 16:28:30'),
(16, 'QM345', 'PRESCRIBE_TEST', 'W21814', 'Prescribed Test ID 15 to Patient W21814', '192.168.65.1', '2025-12-07 16:42:34'),
(18, 'QM345', 'ADD_PATIENT', '32456RTY', 'Doctor added a new patient', '192.168.65.1', '2025-12-07 20:51:41'),
(19, 'QM345', 'ADD_TEST', 'Endoscopy', 'Doctor added a new test', '192.168.65.1', '2025-12-07 20:55:40'),
(20, 'QM345', 'ADD_TEST', 'Eye sight', 'Doctor added a new test', '192.168.65.1', '2025-12-08 08:08:15'),
(21, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: e', '192.168.65.1', '2025-12-08 08:09:13'),
(22, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: e', '192.168.65.1', '2025-12-08 08:13:51'),
(23, 'QM345', 'DELETE_TEST', '17', 'Deleted test ID 17 via Search Page', '192.168.65.1', '2025-12-08 08:13:56'),
(24, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: e', '192.168.65.1', '2025-12-08 08:13:56'),
(25, 'QM345', 'DELETE_TEST', '17', 'Deleted test ID 17 via Search Page', '192.168.65.1', '2025-12-08 08:15:16'),
(26, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: e', '192.168.65.1', '2025-12-08 08:15:16'),
(27, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: k', '192.168.65.1', '2025-12-08 08:15:24'),
(28, 'QM345', 'DELETE_TEST', '16', 'Deleted test ID 16 via Search Page', '192.168.65.1', '2025-12-08 08:15:28'),
(29, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: k', '192.168.65.1', '2025-12-08 08:15:28'),
(30, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: k', '192.168.65.1', '2025-12-08 08:15:44'),
(31, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: l', '192.168.65.1', '2025-12-08 08:15:49'),
(32, 'QM345', 'DELETE_TEST', '15', 'Deleted test ID 15 via Search Page', '192.168.65.1', '2025-12-08 08:15:55'),
(33, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: l', '192.168.65.1', '2025-12-08 08:15:55'),
(34, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: l', '192.168.65.1', '2025-12-08 08:16:01'),
(35, 'QM345', 'DELETE_TEST', '14', 'Deleted test ID 14 via Search Page', '192.168.65.1', '2025-12-08 08:16:04'),
(36, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: l', '192.168.65.1', '2025-12-08 08:16:04'),
(37, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: l', '192.168.65.1', '2025-12-08 08:16:11'),
(38, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: j', '192.168.65.1', '2025-12-08 08:21:38'),
(39, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: k', '192.168.65.1', '2025-12-08 08:21:40'),
(40, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: k', '192.168.65.1', '2025-12-08 08:21:41'),
(41, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: l', '192.168.65.1', '2025-12-08 08:21:45'),
(42, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: l', '192.168.65.1', '2025-12-08 08:23:02'),
(43, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: l', '192.168.65.1', '2025-12-08 08:23:02'),
(44, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: h', '192.168.65.1', '2025-12-08 08:30:49'),
(45, 'QM345', 'UPDATE_TEST', '11', 'Renamed test ID 11 to \'mammography\'', '192.168.65.1', '2025-12-08 08:31:45'),
(46, 'QM345', 'UPDATE_TEST', '11', 'Renamed test ID 11 to \'mammography\'', '192.168.65.1', '2025-12-08 08:33:18'),
(47, 'QM345', 'UPDATE_TEST', '11', 'Renamed test ID 11 to \'mammography\'', '192.168.65.1', '2025-12-08 08:33:24'),
(48, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: jh=', '192.168.65.1', '2025-12-08 08:33:40'),
(49, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: e', '192.168.65.1', '2025-12-08 08:33:43'),
(50, 'QM345', 'UPDATE_TEST', '18', 'Renamed test ID 18 to \'Eye sight\'', '192.168.65.1', '2025-12-08 08:33:48'),
(51, 'QM345', 'UPDATE_TEST', '18', 'Renamed test ID 18 to \'eye sight\'', '192.168.65.1', '2025-12-08 08:34:07'),
(52, 'QM345', 'UPDATE_TEST', '18', 'Renamed test ID 18 to \'eye sight\'', '192.168.65.1', '2025-12-08 09:18:12'),
(53, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: k', '192.168.65.1', '2025-12-08 09:18:52'),
(54, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: k', '192.168.65.1', '2025-12-08 09:22:11'),
(55, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: k', '192.168.65.1', '2025-12-08 09:22:13'),
(56, 'QM345', 'UPDATE_PATIENT', 'W21758', 'Updated details for Alex Kai', '192.168.65.1', '2025-12-08 09:22:22'),
(57, 'QM345', 'DELETE_PATIENT', 'W21758', 'Deleted patient record', '192.168.65.1', '2025-12-08 09:22:31'),
(58, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: k', '192.168.65.1', '2025-12-08 09:24:40'),
(59, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: k', '192.168.65.1', '2025-12-08 09:24:42'),
(60, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: k', '192.168.65.1', '2025-12-08 09:24:48'),
(61, 'QM345', 'DELETE_PATIENT', 'W20616', 'Deleted patient record', '192.168.65.1', '2025-12-08 09:24:54'),
(62, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: e', '192.168.65.1', '2025-12-08 09:26:20'),
(63, 'QM345', 'DELETE_PATIENT', 'W21814', 'Deleted patient record', '192.168.65.1', '2025-12-08 09:26:24'),
(64, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: e', '192.168.65.1', '2025-12-08 09:28:09'),
(65, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: k', '192.168.65.1', '2025-12-08 09:28:11'),
(66, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: k', '192.168.65.1', '2025-12-08 09:48:53'),
(67, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: g', '192.168.65.1', '2025-12-08 10:50:28'),
(68, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: t', '192.168.65.1', '2025-12-08 10:50:33'),
(69, 'QM345', 'CHANGE PASSWORD', 'admin', 'User changed password', '192.168.65.1', '2025-12-08 19:33:21'),
(70, 'mceards', 'SEARCH', 'Patient Table', 'User searched database for: jg]', '192.168.65.1', '2025-12-08 20:28:10'),
(71, 'mceards', 'SEARCH', 'Patient Table', 'User searched database for: iy', '192.168.65.1', '2025-12-08 20:28:18'),
(72, 'mceards', 'SEARCH', 'Test Table', 'User searched database for: uh', '192.168.65.1', '2025-12-08 20:28:26'),
(73, 'mceards', 'SEARCH', 'Test Table', 'User searched database for: i', '192.168.65.1', '2025-12-08 20:28:32'),
(74, 'mceards', 'SEARCH', 'Test Table', 'User searched database for: lok', '192.168.65.1', '2025-12-08 20:28:43'),
(75, 'mceards', 'SEARCH', 'Test Table', 'User searched database for: l', '192.168.65.1', '2025-12-08 20:28:46'),
(76, 'mceards', 'SEARCH', 'Patient Table', 'User searched database for: h', '192.168.65.1', '2025-12-08 20:31:05'),
(77, 'mceards', 'SEARCH', 'Test Table', 'User searched database for: l', '192.168.65.1', '2025-12-08 20:31:12'),
(78, 'mceards', 'SEARCH', 'Test Table', 'User searched database for: l', '192.168.65.1', '2025-12-08 20:32:59'),
(79, 'mceards', 'SEARCH', 'Patient Table', 'User searched database for: y', '192.168.65.1', '2025-12-08 20:34:23'),
(80, 'mceards', 'SEARCH', 'Test Table', 'User searched database for: h', '192.168.65.1', '2025-12-08 20:34:30'),
(81, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-09 08:36:49'),
(82, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-09 08:36:56'),
(83, 'QM345', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-09 09:23:52'),
(84, 'QM345', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-09 09:50:00'),
(85, 'QM478', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-09 09:52:37'),
(86, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-09 09:55:08'),
(87, 'QM965', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-09 10:13:22'),
(88, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-09 10:17:33'),
(89, 'QM345', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-09 10:37:33'),
(90, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: h', '192.168.65.1', '2025-12-09 10:46:17'),
(91, 'QM345', 'DELETE_PATIENT', '32456RTY', 'Deleted patient record', '192.168.65.1', '2025-12-09 10:46:30'),
(92, 'QM345', 'SEARCH', 'Patient Table', 'User searched database for: j', '192.168.65.1', '2025-12-09 10:54:45'),
(93, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: j', '192.168.65.1', '2025-12-09 11:02:53'),
(94, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: i', '192.168.65.1', '2025-12-09 11:03:27'),
(95, 'QM345', 'SEARCH', 'Test Table', 'User searched database for: li', '192.168.65.1', '2025-12-09 11:04:17'),
(96, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: liz ', '192.168.65.1', '2025-12-09 11:04:23'),
(97, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: Liz', '192.168.65.1', '2025-12-09 11:04:44'),
(98, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: Liz', '192.168.65.1', '2025-12-09 11:06:04'),
(99, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: Liz', '192.168.65.1', '2025-12-09 11:07:33'),
(100, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W20616', '192.168.65.1', '2025-12-09 11:08:21'),
(101, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W20616', '192.168.65.1', '2025-12-09 11:08:22'),
(102, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W20616', '192.168.65.1', '2025-12-09 11:08:22'),
(103, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W20616', '192.168.65.1', '2025-12-09 11:08:24'),
(104, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W20616', '192.168.65.1', '2025-12-09 11:08:25'),
(105, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21814', '192.168.65.1', '2025-12-09 11:09:44'),
(106, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21814', '192.168.65.1', '2025-12-09 11:09:45'),
(107, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21814', '192.168.65.1', '2025-12-09 11:09:45'),
(108, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:10:05'),
(109, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:11:24'),
(110, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:11:26'),
(111, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:11:26'),
(112, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:11:26'),
(113, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:11:26'),
(114, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:11:26'),
(115, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:11:26'),
(116, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:11:27'),
(117, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:11:27'),
(118, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:11:27'),
(119, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:11:28'),
(120, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:11:28'),
(121, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:11:28'),
(122, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:11:28'),
(123, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:12:15'),
(124, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:12:16'),
(125, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:12:20'),
(126, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:12:20'),
(127, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:12:20'),
(128, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:12:20'),
(129, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:12:21'),
(130, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:12:22'),
(131, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:12:22'),
(132, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:12:22'),
(133, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:12:22'),
(134, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:12:22'),
(135, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:12:23'),
(136, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-09 11:12:23'),
(137, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W21895', '192.168.65.1', '2025-12-09 11:12:33'),
(138, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: liz', '192.168.65.1', '2025-12-09 11:14:46'),
(139, 'QM345', 'UPDATE_PRESCRIPTION', 'W21895', 'Updated prescription. Changed from (P:W21895/T:5) to (P:W21895/T:5)', '192.168.65.1', '2025-12-09 11:16:47'),
(140, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: k', '192.168.65.1', '2025-12-09 19:24:52'),
(141, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: j', '192.168.65.1', '2025-12-09 19:24:55'),
(142, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: l', '192.168.65.1', '2025-12-09 19:27:29'),
(143, 'QM345', 'UPDATE_PATIENT', 'W21028', 'Updated details for Max Wilson', '192.168.65.1', '2025-12-09 19:27:33'),
(144, 'QM345', 'ADD_TEST', 'Scan', 'Doctor added a new test', '192.168.65.1', '2025-12-09 19:48:31'),
(145, 'QM345', 'ADD_PATIENT', 'W37496', 'Doctor added a new patient', '192.168.65.1', '2025-12-09 19:50:03'),
(146, 'QM345', 'PRESCRIBE_TEST', 'W37496', 'Prescribed Test ID 19 to Patient W37496', '192.168.65.1', '2025-12-09 19:50:12'),
(147, 'QM345', 'UPDATE_PRESCRIPTION', 'W37496', 'Updated prescription. Changed from (Patient:W37496/Test:19) to (Patient:W37496/Test:19)', '192.168.65.1', '2025-12-09 19:52:34'),
(148, 'QM345', 'DELETE_PRESCRIPTION', 'W21814', 'Deleted prescription: Patient W21814, Test 15 on 2025-12-07', '192.168.65.1', '2025-12-09 19:58:20'),
(149, 'QM345', 'DELETE_PRESCRIPTION', 'W20616', 'Deleted prescription: Patient W20616, Test 6 on 2023-10-01', '192.168.65.1', '2025-12-09 19:58:33'),
(150, 'QM345', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-09 19:59:00'),
(151, 'Unknown User', 'SEARCH', 'Patient Table', 'User searched for: l', '192.168.65.1', '2025-12-09 20:24:07'),
(152, 'Unknown User', 'SEARCH', 'Patient Table', 'User searched for: W', '192.168.65.1', '2025-12-09 20:28:58'),
(153, 'Unknown User', 'SEARCH', 'Patient Table', 'User searched for: W', '192.168.65.1', '2025-12-09 20:34:06'),
(154, 'Unknown User', 'SEARCH', 'Patient Table', 'User searched for: W', '192.168.65.1', '2025-12-09 20:34:51'),
(155, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: W', '192.168.65.1', '2025-12-09 20:39:46'),
(156, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: h', '192.168.65.1', '2025-12-09 20:59:49'),
(157, 'QM345', 'SEARCH', 'Patient Table', 'User searched for: l', '192.168.65.1', '2025-12-09 20:59:58'),
(158, 'QM345', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 09:04:48'),
(159, 'QM345', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 09:19:48'),
(160, 'QM345', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 09:26:15'),
(161, 'admin', 'SEARCH', 'Test Table', 'User searched database for: h', '192.168.65.1', '2025-12-10 09:29:06'),
(162, 'admin', 'SEARCH', 'Patient Table', 'User searched for: h', '192.168.65.1', '2025-12-10 09:29:25'),
(163, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 09:35:19'),
(164, 'admin', 'SEARCH', 'Patient Table', 'User searched for: l', '192.168.65.1', '2025-12-10 10:06:14'),
(165, 'admin', 'SEARCH', 'Patient Table', 'User searched for: m', '192.168.65.1', '2025-12-10 10:07:28'),
(166, 'admin', 'SEARCH', 'Patient Table', 'User searched for: d', '192.168.65.1', '2025-12-10 10:07:44'),
(167, 'admin', 'SEARCH', 'Patient Table', 'User searched for: d', '192.168.65.1', '2025-12-10 10:08:01'),
(168, 'admin', 'SEARCH', 'Patient Table', 'User searched for: d', '192.168.65.1', '2025-12-10 10:08:01'),
(169, 'admin', 'SEARCH', 'Patient Table', 'User searched for: m', '192.168.65.1', '2025-12-10 10:08:10'),
(170, 'admin', 'SEARCH', 'Patient Table', 'User searched for: l', '192.168.65.1', '2025-12-10 10:11:53'),
(171, 'admin', 'SEARCH', 'Patient Table', 'User searched for: ;;lkj', '192.168.65.1', '2025-12-10 10:12:12'),
(172, 'QM345', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 10:16:10'),
(173, 'QM345', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 10:21:27'),
(174, 'assdafghjk (Unknown)', 'LOGIN_FAILED', 'Users Table', 'Failed login attempt using username: assdafghjk', '192.168.65.1', '2025-12-10 10:21:31'),
(175, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 10:21:37'),
(176, 'admin', 'SEARCH', 'Patient Table', 'User searched for: ef', '192.168.65.1', '2025-12-10 10:21:46'),
(177, 'admin', 'SEARCH', 'Patient Table', 'User searched for: l', '192.168.65.1', '2025-12-10 10:23:15'),
(178, 'QM345', 'ADD_TEST', 'Stool test', 'Doctor added a new test', '192.168.65.1', '2025-12-10 10:42:58'),
(179, 'QM345', 'SEARCH', 'Test Table', 'User searched for: k', '192.168.65.1', '2025-12-10 10:46:42'),
(180, 'QM345', 'SEARCH', 'Test Table', 'User searched for: fu', '192.168.65.1', '2025-12-10 10:46:45'),
(181, 'QM345', 'SEARCH', 'Test Table', 'User searched for: fu', '192.168.65.1', '2025-12-10 10:56:05'),
(182, 'QM345', 'SEARCH', 'Test Table', 'User searched for: stoo', '192.168.65.1', '2025-12-10 10:56:22'),
(183, 'QM345', 'DELETE_TEST', '20', 'Deleted test ID 20 via Search Page', '192.168.65.1', '2025-12-10 10:56:26'),
(184, 'QM345', 'SEARCH', 'Test Table', 'User searched for: stoo', '192.168.65.1', '2025-12-10 10:56:26'),
(185, 'QM345', 'SEARCH', 'Test Table', 'User searched for: fgb', '192.168.65.1', '2025-12-10 10:56:52'),
(186, 'QM345', 'SEARCH', 'Test Table', 'User searched for: blood', '192.168.65.1', '2025-12-10 10:56:56'),
(187, 'QM345', 'SEARCH', 'Test Table', 'User searched for: blood', '192.168.65.1', '2025-12-10 10:58:11'),
(188, 'QM345', 'UPDATE_TEST', '1', 'Renamed test ID 1 to \'Blood count general\'', '192.168.65.1', '2025-12-10 10:58:19'),
(189, 'QM345', 'UPDATE_PRESCRIPTION', 'W20620', 'Updated prescription. Changed from (W37496/19) to (W20620/19)', '192.168.65.1', '2025-12-10 11:03:02'),
(190, 'QM345', 'DELETE_PRESCRIPTION', 'W20620', 'Deleted prescription: Patient W20620, Test 19 on 2025-12-09', '192.168.65.1', '2025-12-10 11:03:32'),
(191, 'QM345', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 11:04:23'),
(192, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 11:04:35'),
(193, 'admin', 'SUBMIT_REQUEST', 'QM300', 'Doctor submitted a yearly parking request', '192.168.65.1', '2025-12-10 11:06:02'),
(194, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 11:06:59'),
(195, 'moorland', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 11:07:55'),
(196, 'moorland', 'SUBMIT_REQUEST', 'moorland', 'Doctor submitted a monthly parking request', '192.168.65.1', '2025-12-10 11:08:19'),
(197, 'moorland', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 11:08:36'),
(198, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 11:08:45'),
(199, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 11:08:58'),
(200, 'moorland', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 11:09:21'),
(201, 'moorland', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 11:10:40'),
(202, 'QM965 (Unknown)', 'LOGIN_FAILED', 'Users Table', 'Failed login attempt using username: QM965', '192.168.65.1', '2025-12-10 11:10:59'),
(203, 'QM965', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 11:11:18'),
(204, 'QM965', 'SUBMIT_REQUEST', 'QM965', 'Doctor submitted a yearly parking request', '192.168.65.1', '2025-12-10 11:11:29'),
(205, 'QM965', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 11:12:31'),
(206, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 11:12:35'),
(207, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 11:12:58'),
(208, 'QM965', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 11:13:14'),
(209, 'QM965', 'UPDATE_PROFILE', 'QM965', 'User updated their own doctor profile', '192.168.65.1', '2025-12-10 11:16:09'),
(210, 'QM965', 'CHANGE_PASSWORD', 'admin', 'User changed their password', '192.168.65.1', '2025-12-10 11:18:48'),
(211, 'QM965', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 11:19:35'),
(212, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 11:23:40'),
(213, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 11:23:53'),
(214, 'SYSTEM', 'RESET_PASSWORD', 'admin', 'User reset password', '192.168.65.1', '2025-12-10 11:24:03'),
(215, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 11:24:25'),
(216, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 11:24:52'),
(217, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 13:48:53'),
(218, 'admin', 'APPROVE_PERMIT', 'QT001', 'Admin approved parking permit for QT001', '192.168.65.1', '2025-12-10 14:01:56'),
(219, 'admin', 'SEARCH', 'Patient Table', 'User searched for: j', '192.168.65.1', '2025-12-10 15:15:30'),
(220, 'admin', 'UPDATE_DOCTOR', 'QM004', 'Admin updated profile for doctor QM004 (Jason Atkin)', '192.168.65.1', '2025-12-10 15:20:25'),
(221, 'admin', 'UPDATE_DOCTOR', 'QM004', 'Admin updated profile for doctor QM004 (Jason Atkin)', '192.168.65.1', '2025-12-10 15:21:06'),
(222, 'admin', 'SEARCH', 'Patient Table', 'User searched for: ki', '192.168.65.1', '2025-12-10 15:55:47'),
(223, 'admin', 'ADD_TEST', 'mammology', 'Doctor added a new test', '192.168.65.1', '2025-12-10 15:56:12'),
(224, 'admin', 'SEARCH', 'Test Table', 'User searched for: mam', '192.168.65.1', '2025-12-10 15:56:21'),
(225, 'admin', 'DELETE_TEST', '21', 'Deleted test ID 21 via Search Page', '192.168.65.1', '2025-12-10 15:56:27'),
(226, 'admin', 'SEARCH', 'Test Table', 'User searched for: mam', '192.168.65.1', '2025-12-10 15:56:27'),
(227, 'admin', 'SEARCH', 'Test Table', 'User searched for: k', '192.168.65.1', '2025-12-10 16:00:35'),
(228, 'admin', 'SEARCH', 'Test Table', 'User searched for: l', '192.168.65.1', '2025-12-10 16:00:38'),
(229, 'admin', 'DELETE_TEST', '1', 'Deleted test ID 1 via Search Page', '192.168.65.1', '2025-12-10 16:16:19'),
(230, 'admin', 'SEARCH', 'Test Table', 'User searched for: l', '192.168.65.1', '2025-12-10 16:16:19'),
(231, 'admin', 'SEARCH', 'Patient Table', 'User searched for: m', '192.168.65.1', '2025-12-10 16:16:32'),
(232, 'admin', 'SEARCH', 'Test Table', 'User searched for: pap', '192.168.65.1', '2025-12-10 16:16:39'),
(233, 'admin', 'DELETE_TEST', '8', 'Deleted test ID 8 via Search Page', '192.168.65.1', '2025-12-10 16:17:37'),
(234, 'admin', 'SEARCH', 'Test Table', 'User searched for: pap', '192.168.65.1', '2025-12-10 16:17:37'),
(235, 'admin', 'SEARCH', 'Patient Table', 'User searched for: max', '192.168.65.1', '2025-12-10 16:17:52'),
(236, 'admin', 'SEARCH', 'Patient Table', 'User searched for: max', '192.168.65.1', '2025-12-10 16:24:44'),
(237, 'admin', 'SEARCH', 'Test Table', 'User searched for: ct ', '192.168.65.1', '2025-12-10 16:24:53'),
(238, 'admin', 'SEARCH', 'Test Table', 'User searched for: ct ', '192.168.65.1', '2025-12-10 16:25:05'),
(239, 'admin', 'DELETE_PRESCRIPTION', 'W21814', 'Deleted prescription: Patient W21814, Test 3 on 2023-02-18', '192.168.65.1', '2025-12-10 16:28:29'),
(240, 'admin', 'SEARCH', 'Patient Table', 'User searched for: peter', '192.168.65.1', '2025-12-10 16:29:01'),
(241, 'admin', 'SEARCH', 'Patient Table', 'User searched for: peter', '192.168.65.1', '2025-12-10 16:30:09'),
(242, 'admin', 'SEARCH', 'Patient Table', 'User searched for: k', '192.168.65.1', '2025-12-10 16:30:52'),
(243, 'admin', 'SEARCH', 'Patient Table', 'User searched for: m', '192.168.65.1', '2025-12-10 16:30:56'),
(244, 'admin', 'SEARCH', 'Patient Table', 'User searched for: MAX', '192.168.65.1', '2025-12-10 16:31:53'),
(245, 'admin', 'SEARCH', 'Test Table', 'User searched for: G', '192.168.65.1', '2025-12-10 16:32:15'),
(246, 'admin', 'SEARCH', 'Patient Table', 'User searched for: MAX', '192.168.65.1', '2025-12-10 16:32:25'),
(247, 'admin', 'SEARCH', 'Test Table', 'User searched for: CT ', '192.168.65.1', '2025-12-10 16:32:29'),
(248, 'admin', 'SEARCH', 'Test Table', 'User searched for: CT ', '192.168.65.1', '2025-12-10 16:32:36'),
(249, 'admin', 'SEARCH', 'Patient Table', 'User searched for: HG', '192.168.65.1', '2025-12-10 16:32:43'),
(250, 'admin', 'SEARCH', 'Patient Table', 'User searched for: jk', '192.168.65.1', '2025-12-10 16:32:49'),
(251, 'admin', 'SEARCH', 'Patient Table', 'User searched for: k', '192.168.65.1', '2025-12-10 16:32:52'),
(252, 'admin', 'SEARCH', 'Patient Table', 'User searched for: W21961', '192.168.65.1', '2025-12-10 16:33:31'),
(253, 'admin', 'SEARCH', 'Patient Table', 'User searched for: max ', '192.168.65.1', '2025-12-10 16:38:26'),
(254, 'admin', 'SEARCH', 'Patient Table', 'User searched for: Max', '192.168.65.1', '2025-12-10 16:38:31'),
(255, 'admin', 'SEARCH', 'Test Table', 'User searched for: colo', '192.168.65.1', '2025-12-10 16:49:36'),
(256, 'admin', 'SEARCH', 'Test Table', 'User searched for: colo', '192.168.65.1', '2025-12-10 16:49:40'),
(257, 'admin', 'UPDATE_DOCTOR', 'QM004', 'Admin updated profile for doctor QM004', '192.168.65.1', '2025-12-10 17:06:45'),
(258, 'admin', 'ADD_DOCTOR', 'QM843', 'Admin added new doctor (QM843)', '192.168.65.1', '2025-12-10 17:07:52'),
(259, 'admin', 'ADD_DOCTOR', 'QM643', 'Admin added new doctor (QM643)', '192.168.65.1', '2025-12-10 17:11:07'),
(260, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 17:11:39'),
(261, 'QM965 (Unknown)', 'LOGIN_FAILED', 'Users Table', 'Failed login attempt using username: QM965', '192.168.65.1', '2025-12-10 17:11:55'),
(262, 'QM965', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 17:12:12'),
(263, 'QM965', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 17:12:29'),
(264, 'QM643', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 17:12:37'),
(265, 'QM643', 'SUBMIT_REQUEST', 'QM643', 'Doctor submitted a yearly parking request', '192.168.65.1', '2025-12-10 17:12:51'),
(266, 'QM643', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 17:12:56'),
(267, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 17:13:02'),
(268, 'admin', 'APPROVE_PERMIT', 'QM643', 'Admin approved parking permit for QM643', '192.168.65.1', '2025-12-10 17:13:11'),
(269, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 17:13:12'),
(270, 'QM643', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 17:13:30'),
(271, 'QM643', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 18:02:50'),
(272, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 18:59:53'),
(273, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 19:45:32'),
(274, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 19:46:09'),
(275, 'admin', 'SEARCH', 'Test Table', 'User searched for: Blood', '192.168.65.1', '2025-12-10 19:46:54'),
(276, 'admin', 'ADD_TEST', 'Blood Count блад каунт', 'Doctor added a new test', '192.168.65.1', '2025-12-10 19:47:13'),
(277, 'admin', 'SEARCH', 'Test Table', 'User searched for: блад', '192.168.65.1', '2025-12-10 19:47:34'),
(278, 'admin', 'DELETE_TEST', '22', 'Deleted test ID 22 via Search Page', '192.168.65.1', '2025-12-10 19:47:47'),
(279, 'admin', 'SEARCH', 'Test Table', 'User searched for: блад', '192.168.65.1', '2025-12-10 19:47:47'),
(280, 'admin', 'SEARCH', 'Patient Table', 'User searched for: 1', '192.168.65.1', '2025-12-10 19:47:53'),
(281, 'admin', 'UPDATE_PATIENT', 'W21028', 'Updated details for Max Wilson', '192.168.65.1', '2025-12-10 19:48:15'),
(282, 'admin', 'SEARCH', 'Patient Table', 'User searched for: 1', '192.168.65.1', '2025-12-10 19:48:21'),
(283, 'admin', 'UPDATE_PATIENT', 'W21028', 'Updated details for Max Wilson', '192.168.65.1', '2025-12-10 19:48:33'),
(284, 'admin', 'SEARCH', 'Test Table', 'User searched for: идщщв', '192.168.65.1', '2025-12-10 19:50:25'),
(285, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 19:53:16'),
(286, 'Unknown User', 'SEARCH', 'Test Table', 'User searched for: blood', '192.168.65.1', '2025-12-10 19:53:41'),
(287, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 19:53:55'),
(288, 'QM843 (Unknown)', 'LOGIN_FAILED', 'Users Table', 'Failed login attempt using username: QM843', '192.168.65.1', '2025-12-10 19:54:31'),
(289, 'QM843', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 19:54:53'),
(290, 'QM843', 'SUBMIT_REQUEST', 'QM843', 'Doctor submitted a monthly parking request', '192.168.65.1', '2025-12-10 19:55:08'),
(291, 'QM843', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 19:55:11'),
(292, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 19:55:16'),
(293, 'admin', 'REJECT_PERMIT', 'QM843', 'Admin rejected permit. Reason: Not a valid car registration', '192.168.65.1', '2025-12-10 19:55:33'),
(294, 'admin', 'DELETE_PRESCRIPTION', 'W21895', 'Deleted prescription: Patient W21895, Test 5 on 2023-06-08', '192.168.65.1', '2025-12-10 20:50:09'),
(295, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 21:06:27'),
(296, 'moorland', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 21:06:44'),
(297, 'moorland', 'PRESCRIBE_TEST', 'W21895', 'Prescribed Test ID 19 to Patient W21895', '192.168.65.1', '2025-12-10 21:09:55'),
(298, 'moorland', 'UPDATE_PRESCRIPTION', 'W21895', 'Updated prescription. Changed from (W21895/19) to (W21895/13)', '192.168.65.1', '2025-12-10 21:10:18'),
(299, 'moorland', 'UPDATE_PRESCRIPTION', 'W21758', 'Updated prescription. Changed from (W21758/12) to (W21758/10)', '192.168.65.1', '2025-12-10 21:10:29'),
(300, 'moorland', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 21:10:39'),
(301, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 21:10:44'),
(302, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 21:11:51'),
(303, 'moorland', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 21:12:19'),
(304, 'moorland', 'DELETE_PRESCRIPTION', 'W21895', 'Deleted prescription: Patient W21895, Test 13 on 2025-12-10', '192.168.65.1', '2025-12-10 21:12:26'),
(305, 'moorland', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-10 21:14:32'),
(306, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-10 21:14:37'),
(307, 'admin', 'DELETE_PRESCRIPTION', 'W21895', 'Deleted prescription: Patient W21895, Test 7 on 2023-06-09', '192.168.65.1', '2025-12-10 21:14:49'),
(308, 'admin', 'DELETE_PRESCRIPTION', 'W21814', 'Deleted prescription: Patient W21814, Test 3 on 2023-02-17', '192.168.65.1', '2025-12-10 21:21:38'),
(309, 'admin', 'DELETE_PRESCRIPTION', 'W21961', 'Deleted prescription: Patient W21961, Test 4 on 2019-10-18', '192.168.65.1', '2025-12-10 21:21:54'),
(310, 'admin', 'DELETE_PRESCRIPTION', 'W21895', 'Deleted prescription: Patient W21895, Test 5 on 2023-06-07', '192.168.65.1', '2025-12-10 21:22:13'),
(311, 'admin', 'UPDATE_DOCTOR', 'admin', 'Admin updated profile for doctor admin', '192.168.65.1', '2025-12-10 21:37:36'),
(312, 'admin', 'UPDATE_DOCTOR', 'admin', 'Admin updated profile for doctor admin', '192.168.65.1', '2025-12-10 21:38:03'),
(313, 'admin', 'PRESCRIBE_TEST', 'W21895', 'Prescribed Test ID 6 to Patient W21895', '192.168.65.1', '2025-12-10 21:38:47'),
(314, 'admin', 'UPDATE_PRESCRIPTION', 'W21895', 'Updated prescription. Changed from (W21895/6) to (W21895/9)', '192.168.65.1', '2025-12-10 21:39:00'),
(315, 'admin', 'DELETE_PRESCRIPTION', 'W21895', 'Deleted prescription: Patient W21895, Test 9 on 2025-12-10', '192.168.65.1', '2025-12-10 21:39:13'),
(316, 'admin', 'SUBMIT_REQUEST', 'admin', 'Doctor submitted a yearly parking request', '192.168.65.1', '2025-12-10 21:39:23'),
(317, 'admin', 'SEARCH', 'Patient Table', 'User searched for: l', '192.168.65.1', '2025-12-10 21:41:27'),
(318, 'admin', 'SEARCH', 'Patient Table', 'User searched for: W21028', '192.168.65.1', '2025-12-10 21:42:07'),
(319, 'admin', 'UPDATE_PATIENT', 'W21028', 'Updated details for Max Wilson', '192.168.65.1', '2025-12-10 21:42:45'),
(320, 'admin', 'DELETE_PRESCRIPTION', 'W21028', 'Deleted prescription: Patient W21028, Test 3 on 2021-11-07', '192.168.65.1', '2025-12-10 21:42:57'),
(321, 'admin', 'UPDATE_DOCTOR', 'jelina', 'Admin updated profile for doctor jelina', '192.168.65.1', '2025-12-11 08:18:44'),
(322, 'admin', 'ADD_DOCTOR', 'QM570', 'Admin added new doctor (QM570)', '192.168.65.1', '2025-12-11 08:36:40'),
(323, 'admin', 'PRESCRIBE_TEST', 'W21814', 'Prescribed Test ID 11 to Patient W21814', '192.168.65.1', '2025-12-11 08:36:57'),
(324, 'admin', 'DELETE_PRESCRIPTION', 'W21814', 'Deleted prescription: Patient W21814, Test 11 on 2025-12-11', '192.168.65.1', '2025-12-11 08:37:10'),
(325, 'admin', 'SEARCH', 'Patient Table', 'User searched for: lkjh', '192.168.65.1', '2025-12-11 08:38:41'),
(326, 'admin', 'SEARCH', 'Patient Table', 'User searched for: k', '192.168.65.1', '2025-12-11 08:38:44'),
(327, 'admin', 'SEARCH', 'Test Table', 'User searched for: k', '192.168.65.1', '2025-12-11 08:39:01'),
(328, 'admin', 'ADD_TEST', '[pkjh', 'Doctor added a new test', '192.168.65.1', '2025-12-11 08:39:59'),
(329, 'admin', 'SEARCH', 'Test Table', 'User searched for: kjhg', '192.168.65.1', '2025-12-11 08:40:07'),
(330, 'admin', 'SEARCH', 'Test Table', 'User searched for: h', '192.168.65.1', '2025-12-11 08:40:27'),
(331, 'admin', 'DELETE_TEST', '23', 'Deleted test ID 23 via Search Page', '192.168.65.1', '2025-12-11 08:40:34'),
(332, 'admin', 'SEARCH', 'Test Table', 'User searched for: h', '192.168.65.1', '2025-12-11 08:40:34'),
(333, 'admin', 'PRESCRIBE_TEST', 'W21758', 'Prescribed Test ID 6 to Patient W21758', '192.168.65.1', '2025-12-11 08:52:52'),
(334, 'admin', 'DELETE_PRESCRIPTION', 'W21758', 'Deleted prescription: Patient W21758, Test 6 on 2025-12-11', '192.168.65.1', '2025-12-11 08:53:26'),
(335, 'admin', 'PRESCRIBE_TEST', 'W21895', 'Prescribed Test ID 7 to Patient W21895', '192.168.65.1', '2025-12-11 08:54:55'),
(336, 'admin', 'UPDATE_PRESCRIPTION', 'W21895', 'Updated prescription. Changed from (W21895/7) to (W21895/19)', '192.168.65.1', '2025-12-11 08:55:03'),
(337, 'admin', 'UPDATE_PRESCRIPTION', 'W20620', 'Updated prescription. Changed from (W21895/19) to (W20620/19)', '192.168.65.1', '2025-12-11 08:55:12'),
(338, 'admin', 'SEARCH', 'Test Table', 'User searched for: i', '192.168.65.1', '2025-12-11 08:55:36'),
(339, 'admin', 'SEARCH', 'Test Table', 'User searched for: blood', '192.168.65.1', '2025-12-11 16:55:43'),
(340, 'admin', 'SEARCH', 'Test Table', 'User searched for: scan', '192.168.65.1', '2025-12-11 16:55:50'),
(341, 'admin', 'SEARCH', 'Test Table', 'User searched for: scan', '192.168.65.1', '2025-12-11 16:55:53'),
(342, 'admin', 'SEARCH', 'Test Table', 'User searched for: 1', '192.168.65.1', '2025-12-11 16:58:24'),
(343, 'admin', 'ADD_TEST', 'uihi', 'Doctor added a new test', '192.168.65.1', '2025-12-11 16:58:31'),
(344, 'admin', 'SEARCH', 'Test Table', 'User searched for: u', '192.168.65.1', '2025-12-11 16:58:41'),
(345, 'admin', 'UPDATE_TEST', '24', 'Renamed test ID 24 to \'uihi\'', '192.168.65.1', '2025-12-11 16:59:02'),
(346, 'admin', 'UPDATE_TEST', '24', 'Renamed test ID 24 to \'uihi66\'', '192.168.65.1', '2025-12-11 16:59:06'),
(347, 'admin', 'PRESCRIBE_TEST', 'W20620', 'Prescribed Test ID 24 to Patient W20620', '192.168.65.1', '2025-12-11 16:59:26'),
(348, 'admin', 'SEARCH', 'Test Table', 'User searched for: ui', '192.168.65.1', '2025-12-11 16:59:32'),
(349, 'admin', 'SEARCH', 'Test Table', 'User searched for: ui', '192.168.65.1', '2025-12-11 16:59:36'),
(350, 'admin', 'UPDATE_TEST', '24', 'Renamed test ID 24 to \'uihi667\'', '192.168.65.1', '2025-12-11 16:59:52'),
(351, 'admin', 'DELETE_PRESCRIPTION', 'W20620', 'Deleted prescription: Patient W20620, Test 24 on 2025-12-11', '192.168.65.1', '2025-12-11 17:00:03'),
(352, 'admin', 'SEARCH', 'Test Table', 'User searched for: ui', '192.168.65.1', '2025-12-11 17:00:09'),
(353, 'admin', 'DELETE_TEST', '24', 'Deleted test ID 24 via Search Page', '192.168.65.1', '2025-12-11 17:00:13'),
(354, 'admin', 'SEARCH', 'Test Table', 'User searched for: ui', '192.168.65.1', '2025-12-11 17:00:13'),
(355, 'admin', 'UPDATE_DOCTOR', 'QM004', 'Admin updated profile for doctor QM004', '192.168.65.1', '2025-12-11 17:00:26'),
(356, 'admin', 'SEARCH', 'Patient Table', 'User searched for: 1', '192.168.65.1', '2025-12-11 17:00:32'),
(357, 'admin', 'UPDATE_DOCTOR', 'QM004', 'Admin updated profile for doctor QM004', '192.168.65.1', '2025-12-11 17:01:24'),
(358, 'admin', 'UPDATE_DOCTOR', 'QM004', 'Admin updated profile for doctor QM004', '192.168.65.1', '2025-12-11 17:01:47'),
(359, 'admin', 'SEARCH', 'Patient Table', 'User searched for: 1', '192.168.65.1', '2025-12-11 17:01:54'),
(360, 'admin', 'UPDATE_PRESCRIPTION', 'W20620', 'Updated prescription. Changed from (W20620/19) to (W20620/18)', '192.168.65.1', '2025-12-11 17:02:24'),
(361, 'admin', 'SEARCH', 'Test Table', 'User searched for: 1', '192.168.65.1', '2025-12-11 17:03:42'),
(362, 'admin', 'SEARCH', 'Test Table', 'User searched for: a', '192.168.65.1', '2025-12-11 17:03:45'),
(363, 'admin', 'SEARCH', 'Test Table', 'User searched for: b', '192.168.65.1', '2025-12-11 17:03:56'),
(364, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-11 17:04:08'),
(365, 'QM570', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-11 17:04:35'),
(366, 'QM570', 'SUBMIT_REQUEST', 'QM570', 'Doctor submitted a monthly parking request', '192.168.65.1', '2025-12-11 17:05:00'),
(367, 'QM570', 'SEARCH', 'Patient Table', 'User searched for: b', '192.168.65.1', '2025-12-11 17:05:45'),
(368, 'QM570', 'SEARCH', 'Patient Table', 'User searched for: 1', '192.168.65.1', '2025-12-11 17:05:48'),
(369, 'QM570', 'SEARCH', 'Patient Table', 'User searched for: Alice', '192.168.65.1', '2025-12-11 17:05:52'),
(370, 'QM570', 'SEARCH', 'Patient Table', 'User searched for: 1', '192.168.65.1', '2025-12-11 17:05:55'),
(371, 'QM570', 'SEARCH', 'Patient Table', 'User searched for: W', '192.168.65.1', '2025-12-11 17:06:17'),
(372, 'QM570', 'SEARCH', 'Patient Table', 'User searched for: A 2', '192.168.65.1', '2025-12-11 17:06:46'),
(373, 'QM570', 'SEARCH', 'Patient Table', 'User searched for: A2', '192.168.65.1', '2025-12-11 17:06:50'),
(374, 'QM570', 'SEARCH', 'Patient Table', 'User searched for: A', '192.168.65.1', '2025-12-11 17:06:53'),
(375, 'QM570', 'UPDATE_PATIENT', 'W20616', 'Updated details for Alice Wool9', '192.168.65.1', '2025-12-11 17:07:09'),
(376, 'QM570', 'SEARCH', 'Patient Table', 'User searched for: 9', '192.168.65.1', '2025-12-11 17:07:14'),
(377, 'QM570', 'UPDATE_PATIENT', 'W20616', 'Updated details for Alice Wool', '192.168.65.1', '2025-12-11 17:07:32'),
(378, 'QM570', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-11 17:07:52'),
(379, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-11 17:07:58'),
(380, 'admin', 'APPROVE_PERMIT', 'admin', 'Admin approved parking permit for admin', '192.168.65.1', '2025-12-11 17:08:22'),
(381, 'admin', 'REJECT_PERMIT', 'QM570', 'Admin rejected permit. Reason: bishkek', '192.168.65.1', '2025-12-11 17:08:32'),
(382, 'admin', 'CHANGE_PASSWORD', 'admin', 'User changed their password', '192.168.65.1', '2025-12-11 17:11:50'),
(383, 'admin', 'CHANGE_PASSWORD', 'admin', 'User changed their password', '192.168.65.1', '2025-12-11 17:12:01'),
(384, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-11 17:12:02'),
(385, 'admin (Unknown)', 'LOGIN_FAILED', 'Users Table', 'Failed login attempt using username: admin', '192.168.65.1', '2025-12-11 17:12:08'),
(386, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-11 17:12:14'),
(387, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-11 17:12:19'),
(388, 'SYSTEM', 'RESET_PASSWORD', 'admin', 'User reset password', '192.168.65.1', '2025-12-11 17:12:31'),
(389, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-11 17:12:46'),
(390, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-11 19:55:48'),
(391, 'moorland', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-11 19:55:59'),
(392, 'moorland', 'PRESCRIBE_TEST', 'W20620', 'Prescribed Test ID 19 to Patient W20620', '192.168.65.1', '2025-12-11 20:09:54'),
(393, 'moorland', 'SEARCH', 'Patient Table', 'User searched for: max', '192.168.65.1', '2025-12-11 20:18:12'),
(394, 'moorland', 'SEARCH', 'Test Table', 'User searched for: blood', '192.168.65.1', '2025-12-11 20:21:05'),
(395, 'moorland', 'SEARCH', 'Test Table', 'User searched for: ultr', '192.168.65.1', '2025-12-11 20:21:10'),
(396, 'moorland', 'SEARCH', 'Patient Table', 'User searched for: max', '192.168.65.1', '2025-12-11 20:22:16'),
(397, 'moorland', 'ADD_PATIENT', 'W36578', 'User added new patient: Maria Wolkov', '192.168.65.1', '2025-12-11 20:23:17'),
(398, 'moorland', 'SEARCH', 'Patient Table', 'User searched for: maria', '192.168.65.1', '2025-12-11 20:23:24'),
(399, 'moorland', 'DELETE_PATIENT', 'W36578', 'Deleted patient record', '192.168.65.1', '2025-12-11 20:24:37'),
(400, 'moorland', 'SEARCH', 'Patient Table', 'User searched for: j', '192.168.65.1', '2025-12-11 20:24:47'),
(401, 'moorland', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-11 20:38:27'),
(402, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-11 20:38:32'),
(403, 'admin', 'SEARCH', 'Patient Table', 'User searched for: j', '192.168.65.1', '2025-12-11 20:38:53'),
(404, 'admin', 'SEARCH', 'Patient Table', 'User searched for: maria', '192.168.65.1', '2025-12-11 20:38:54'),
(405, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-11 20:40:02'),
(406, 'QM643 (Unknown)', 'LOGIN_FAILED', 'Users Table', 'Failed login attempt using username: QM643', '192.168.65.1', '2025-12-11 20:40:23'),
(407, 'QM643 (Unknown)', 'LOGIN_FAILED', 'Users Table', 'Failed login attempt using username: QM643', '192.168.65.1', '2025-12-11 20:40:50'),
(408, 'QM643', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-11 20:41:05'),
(409, 'QM643', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-11 20:41:19'),
(410, 'QM570', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-11 20:41:28'),
(411, 'QM570', 'SUBMIT_REQUEST', 'QM570', 'Doctor submitted a yearly parking request', '192.168.65.1', '2025-12-11 20:45:10'),
(412, 'QM570', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-11 20:50:41'),
(413, 'admin (Unknown)', 'LOGIN_FAILED', 'Users Table', 'Failed login attempt using username: admin', '192.168.65.1', '2025-12-11 20:50:45'),
(414, 'admin (Unknown)', 'LOGIN_FAILED', 'Users Table', 'Failed login attempt using username: admin', '192.168.65.1', '2025-12-11 20:50:51'),
(415, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-11 20:51:03'),
(416, 'admin', 'ADD_DOCTOR', 'QM734', 'Admin added new doctor (QM734)', '192.168.65.1', '2025-12-11 20:56:02'),
(417, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-11 21:46:13'),
(418, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-11 21:48:09'),
(419, 'admin', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-11 21:52:40'),
(420, 'admin', 'LOGOUT', 'System', 'User logged out successfully', '192.168.65.1', '2025-12-11 22:52:18'),
(421, 'moorland', 'LOGIN', 'Users Table', 'User logged in successfully', '192.168.65.1', '2025-12-11 22:52:36');

-- --------------------------------------------------------

--
-- Table structure for table `consultant_status`
--

CREATE TABLE `consultant_status` (
  `statusname` text NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `consultant_status`
--

INSERT INTO `consultant_status` (`statusname`, `id`) VALUES
('not_consultant', 0),
('consultant', 1);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `specialisationname` text DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT uuid(),
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`specialisationname`, `id`, `location`) VALUES
('Cardiology', 1, 'Floor A, Room 101, QMC, Derby Rd, Nottingham NG7 2UH'),
('Radiology', 2, 'Floor B, Room 201, QMC, Derby Rd, Nottingham NG7 2UH'),
('Pediatrics', 3, 'South Block, Floor 1, QMC, Derby Rd, Nottingham NG7 2UH'),
('Oncology', 4, 'East Wing, Floor 3, QMC, Derby Rd, Nottingham NG7 2UH'),
('Neurology', 5, 'West Block, Floor 2, QMC, Derby Rd, Nottingham NG7 2UH'),
('Orthopedics', 6, 'Floor C, Room 301, QMC, Derby Rd, Nottingham NG7 2UH'),
('Dermatology', 7, 'North Wing, Floor 1, QMC, Derby Rd, Nottingham NG7 2UH'),
('Psychiatry', 8, 'South Block, Floor 3, Room 301, QMC, Derby Rd, Nottingham NG7 2UH'),
('Anesthesiology', 9, 'Main Theatre Block, Floor B, Room 210, QMC, Derby Rd, Nottingham NG7 2UH'),
('Gastroenterology', 10, 'East Wing, Floor 2, Room 220, QMC, Derby Rd, Nottingham NG7 2UH'),
('General Surgery', 11, 'West Block, Floor 4, Room 401, QMC, Derby Rd, Nottingham NG7 2UH'),
('Emergency Medicine', 12, 'Ground Floor, Main Entrance, QMC, Derby Rd, Nottingham NG7 2UH'),
('Urology', 13, 'South Block, Floor 4, Room 410, QMC, Derby Rd, Nottingham NG7 2UH'),
('Ophthalmology', 14, 'EENT Centre, Floor A, Room 150, QMC, Derby Rd, Nottingham NG7 2UH');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `staffno` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `specialisation` int(11) NOT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `pay` int(11) NOT NULL,
  `gender` int(11) DEFAULT NULL,
  `consultantstatus` int(11) NOT NULL,
  `address` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`staffno`, `firstname`, `lastname`, `specialisation`, `qualification`, `pay`, `gender`, `consultantstatus`, `address`) VALUES
('admin', 'Test', 'None', 2, 'CCT', 0, 0, 0, 'Main Hospital Server Room, Nottingham'),
('CH007', 'Steve', 'Fan', 2, NULL, 67000, 0, 1, '45 The Barnum Nottingham NG2 6TY'),
('GT067', 'Julie', 'Ford', 1, 'CCT', 66000, 1, 1, NULL),
('jelina', 'Jelina', 'Anderson', 1, 'CCT', 0, 1, 0, '88 High Street, Nottingham, NG1 4BB'),
('mceards', 'Mercedes', 'Kaylo', 4, 'CCT', 89000, 1, 1, '12 Taris Road, Nottingham, NG8 6GH'),
('moorland', 'Zoe', 'Moorland', 4, 'CCT', 56000, 1, 1, '8 Chetwin Road, Nottingham, HT6 4FR'),
('QM003', 'Joel ', 'Grahan', 2, '', 44000, 0, 0, '1 Chatsworth Avenue, Carlton, Nottingham, NG4'),
('QM004', 'Jason', 'Atkin', 9, '', 60000, 0, 0, '102 Leeming Lane South, Mansfield Woodhouse, Mansfield'),
('QM009', 'Grazziela', 'Luis', 1, 'CCT', 62000, 1, 1, '16 Lenton Boulevard, Lenton, Nottingham, NG7 2ES'),
('QM122', 'David', 'Ulrik', 2, NULL, 46000, 0, 0, '3 Rolleston Drive, Nottingham'),
('QM123', 'Mattson', 'Maer', 4, 'CCT', 58000, 0, 0, 'Nottingham'),
('QM224', 'Peter', 'Ithar', 4, 'CCT', 58900, 0, 1, 'Derby'),
('QM267', 'Andrew', 'Xin', 2, 'CCT', 58000, 0, 1, '44 Dunlop Avenue, Lenton, Nottingham NG1 5AW'),
('QM299', 'Sasha', 'Proklova', 5, 'CCT', 87000, 1, 0, 'Nottingham'),
('QM300', 'Joy', 'Liz', 1, 'CCT', 52000, 1, 0, '55 Wishford Avenue, Lenton, Nottingham'),
('QM345', 'Kate', 'Drew', 13, 'CCT', 35000, 1, 1, 'Nottingham'),
('QM443', 'Daniel', 'Lorence', 6, 'CCT', 49000, 0, 1, 'Nottingham'),
('QM450', 'Trisha', 'Lovti', 6, 'CCT', 78000, 1, 1, 'Derby'),
('QM456', 'Kate', 'Hewitson', 2, 'CCT', 45000, 1, 0, 'Derby'),
('QM570', 'Tanya', 'Athewr', 6, 'MBBS', 49000, 1, 0, 'Nottingham'),
('QM643', 'Pierre', 'Jais', 4, 'MBB', 39990, 0, 0, 'Derby'),
('QM734', 'Christina', 'Ally', 6, 'CCT', 50000, 1, 1, 'Nottingham'),
('QM843', 'Alina', 'Kravt', 5, 'MBB', 54000, 1, 0, 'Notttingham'),
('QM965', 'John', 'Trotsky', 3, 'CCT', 57800, 0, 0, '40 Derby Road, Nottingham JU7 6HY'),
('QT001', 'Martin', 'Peter', 2, NULL, 48000, 0, 0, '47 Derby Road, Nottingham, NG1 5AW');

-- --------------------------------------------------------

--
-- Table structure for table `gender`
--

CREATE TABLE `gender` (
  `gendername` text DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gender`
--

INSERT INTO `gender` (`gendername`, `id`) VALUES
('Male', 0),
('Female', 1);

-- --------------------------------------------------------

--
-- Table structure for table `parking_permit`
--

CREATE TABLE `parking_permit` (
  `parkingid` int(11) NOT NULL,
  `doctorid` varchar(100) NOT NULL,
  `carregistrationnumber` varchar(50) DEFAULT NULL,
  `permitchoice` varchar(50) NOT NULL,
  `permitactivationdate` date NOT NULL,
  `permitenddate` date NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parking_permit`
--

INSERT INTO `parking_permit` (`parkingid`, `doctorid`, `carregistrationnumber`, `permitchoice`, `permitactivationdate`, `permitenddate`, `amount`) VALUES
(5, 'GT067', 'KI567GF', 'yearly', '2025-12-07', '2026-12-07', 200.00),
(6, 'QM122', 'LO567Y', 'yearly', '2025-12-07', '2026-12-07', 200.00),
(7, 'QM450', 'JK8756L9', 'yearly', '2025-12-07', '2026-12-07', 200.00),
(10, 'QM345', 'LY678FG', 'monthly', '2025-12-09', '2026-12-09', 20.00),
(11, 'moorland', 'Kl5 8JK', 'monthly', '2025-12-10', '2026-12-10', 20.00),
(12, 'QT001', 'KC576U', 'monthly', '2025-12-10', '2026-12-10', 20.00),
(13, 'QM643', 'IK678Gh', 'yearly', '2025-12-10', '2026-12-10', 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `parking_request`
--

CREATE TABLE `parking_request` (
  `doctorid` varchar(100) NOT NULL,
  `carregistrationnumber` varchar(50) DEFAULT NULL,
  `permitchoice` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `reasonifrejected` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parking_request`
--

INSERT INTO `parking_request` (`doctorid`, `carregistrationnumber`, `permitchoice`, `status`, `reasonifrejected`) VALUES
('CH007', 'KJ456NK', 'monthly', 'rejected', 'Have already been issued a parking space'),
('GT067', 'KI567GF', 'yearly', 'approved', NULL),
('moorland', 'Kl5 8JK', 'monthly', 'approved', NULL),
('QM004', 'KT674I', 'monthly', 'rejected', 'Missing doctor registration'),
('QM009', 'LI674YH', 'monthly', 'rejected', 'Previous permit hasn\'t expired'),
('QM122', 'LO567Y', 'yearly', 'approved', NULL),
('QM299', 'LO897Yh', 'yearly', 'rejected', 'Incorrect car registration number'),
('QM300', 'KJ5 HJ4', 'yearly', 'rejected', 'Previous permit has not expired yet'),
('QM345', 'LY678FG', 'monthly', 'approved', NULL),
('QM450', 'JK8756L9', 'yearly', 'approved', NULL),
('QM456', 'JI786L', 'yearly', 'rejected', 'No documents presented'),
('QM570', 'RJ7 J5', 'yearly', 'submitted', NULL),
('QM643', 'IK678Gh', 'yearly', 'approved', NULL),
('QM843', 'my beautiful car', 'monthly', 'rejected', 'Not a valid car registration'),
('QM965', 'KL4 8HJ', 'yearly', 'rejected', 'Car documents pending'),
('QT001', 'KC576U', 'monthly', 'approved', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `NHSno` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `phone` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `emergencyphone` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`NHSno`, `firstname`, `lastname`, `phone`, `address`, `age`, `gender`, `emergencyphone`) VALUES
('W20616', 'Alice', 'Wool', '07564837290', 'Nottingham', 0, '1', ''),
('W20620', 'Nazia', 'Rafiq', '07798522777', '1 Pelham Crescent, Beeston NG9', 37, '1', NULL),
('W21028', 'Max', 'Wilson', '07740312868', '4 Lake Street, Nottingham, NG7 4BT', 32, '0', ''),
('W21758', 'Peter', 'Till', '0674382949', 'Derby', 0, '0', NULL),
('W21814', 'Jose', 'Carreros', '07546389201', 'Nottingham', 0, '0', NULL),
('W21895', 'Liz', 'Felton', '074 56 733 487', '100 Hawton Crescent, Wollaton, NG8 1BZ', 23, '1', NULL),
('W21961', 'Jeremie ', 'Clos', '07754312868', '22 Hawton Crescent, Wollaton, NG8 1BZ', 45, '0', NULL),
('W37496', 'Kate', 'Rozhik', '076589032', '23 Hale Road, Nottingham, NJ8 6GH', 45, '0', '');

-- --------------------------------------------------------

--
-- Table structure for table `patient_examination`
--

CREATE TABLE `patient_examination` (
  `patientid` varchar(100) NOT NULL,
  `doctorid` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_examination`
--

INSERT INTO `patient_examination` (`patientid`, `doctorid`, `date`, `time`) VALUES
('W20616', 'CH007', '2023-12-21', '11:23:11'),
('W20616', 'QM004', '2022-10-18', '10:23:19'),
('W20616', 'QM267', '2022-02-02', '08:23:19'),
('W20620', 'GT067', '2023-06-18', '07:06:05'),
('W20620', 'QM300', '2023-11-08', '09:09:19'),
('W21028', 'QM003', '2021-11-08', '09:23:19'),
('W21758', 'GT067', '2020-11-11', '11:23:05'),
('W21814', 'QM122', '2023-12-12', '02:02:10'),
('W21814', 'QT001', '2016-03-03', '08:18:18'),
('W21895', 'QM003', '2019-11-19', '08:09:10'),
('W21895', 'QM009', '2021-11-19', '08:08:08');

-- --------------------------------------------------------

--
-- Table structure for table `patient_test`
--

CREATE TABLE `patient_test` (
  `pid` varchar(100) NOT NULL,
  `testid` int(11) NOT NULL,
  `date` varchar(100) NOT NULL,
  `report` varchar(100) DEFAULT NULL,
  `doctorid` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_test`
--

INSERT INTO `patient_test` (`pid`, `testid`, `date`, `report`, `doctorid`) VALUES
('W20620', 18, '2025-12-11', NULL, 'admin'),
('W20620', 19, '2025-12-11', NULL, 'moorland'),
('W21758', 6, '', NULL, 'CH007'),
('W21758', 10, '', NULL, 'moorland'),
('W21814', 5, '', NULL, 'QM009');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `testid` int(11) NOT NULL,
  `testname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`testid`, `testname`) VALUES
(10, 'Biopsy'),
(5, 'Colonoscopy'),
(3, 'CT scan'),
(18, 'eye sight'),
(6, 'Genetic testing'),
(7, 'Hematocrit'),
(12, 'Lumbar puncture'),
(11, 'mammography'),
(19, 'Scan'),
(13, 'thyroid function test'),
(4, 'Ultrasonography'),
(2, 'Urinalysis'),
(9, 'X-ray');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(50) NOT NULL,
  `password` char(60) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', 'admin3', 'admin'),
('jelina', 'iron99', 'admin'),
('mceards', 'lord456', 'doctor'),
('moorland', 'buzz48', 'doctor'),
('QM570', 'qLUE1Pk5', 'doctor'),
('QM643', 'xTzuNMUW', 'doctor'),
('QM734', 'DqSZjcWn', 'doctor'),
('QM843', 'lZf1zq30', 'doctor'),
('QM965', 'RhVDaHWB', 'doctor');

-- --------------------------------------------------------

--
-- Table structure for table `ward`
--

CREATE TABLE `ward` (
  `wardid` int(11) NOT NULL,
  `wardname` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `noofbeds` int(11) DEFAULT NULL,
  `departmentid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ward`
--

INSERT INTO `ward` (`wardid`, `wardname`, `address`, `phone`, `noofbeds`, `departmentid`) VALUES
(1, 'Cardiology - Acute Wing', 'Floor A, Room 101, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9001', 25, 1),
(2, 'Cardiology - Recovery', 'Floor A, Room 105, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9002', 15, 1),
(3, 'Radiology - Imaging Center', 'Floor B, Room 201, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9003', 10, 2),
(4, 'Radiology - MRI Suite', 'Floor B, Room 204, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9004', 5, 2),
(5, 'Pediatrics - General', 'South Block, Floor 1, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9005', 30, 3),
(6, 'Pediatrics - NICU', 'South Block, Floor 2, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9006', 12, 3),
(7, 'Oncology - Chemotherapy', 'East Wing, Floor 3, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9007', 20, 4),
(8, 'Oncology - Inpatient', 'East Wing, Floor 4, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9008', 22, 4),
(9, 'Neurology - Stroke Unit', 'West Block, Floor 2, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9009', 18, 5),
(10, 'Neurology - Rehab', 'West Block, Floor 3, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9010', 14, 5),
(11, 'Orthopedics - Fracture Clinic', 'Floor C, Room 301, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9011', 28, 6),
(12, 'Orthopedics - Surgical Recovery', 'Floor C, Room 305, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9012', 20, 6),
(13, 'Dermatology - Outpatient', 'North Wing, Floor 1, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9013', 10, 7),
(14, 'Dermatology - Surgery', 'North Wing, Floor 2, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9014', 8, 7),
(15, 'Emergency - Trauma', 'Ground Floor, Main Entrance, QMC, Derby Rd, Nottingham NG7 2UH', '0115 999 0001', 40, 12),
(16, 'Emergency - Triage', 'Ground Floor, Annex B, QMC, Derby Rd, Nottingham NG7 2UH', '0115 999 0002', 15, 12),
(17, 'Psychiatry - Acute Care', 'South Block, Floor 3, Room 301, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9017', 15, 8),
(18, 'Psychiatry - Assessment', 'South Block, Floor 3, Room 305, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9018', 12, 8),
(19, 'Anesthesiology - Pre-Op', 'Main Theatre Block, Floor B, Room 210, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9019', 10, 9),
(20, 'Anesthesiology - PACU', 'Main Theatre Block, Floor B, Room 215, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9020', 8, 9),
(21, 'Gastroenterology - Endoscopy', 'East Wing, Floor 2, Room 220, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9021', 18, 10),
(22, 'Gastroenterology - Inpatient', 'East Wing, Floor 2, Room 225, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9022', 20, 10),
(23, 'Gen Surgery - Male Ward', 'West Block, Floor 4, Room 401, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9023', 25, 11),
(24, 'Gen Surgery - Female Ward', 'West Block, Floor 4, Room 405, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9024', 25, 11),
(25, 'Urology - Surgical Ward', 'South Block, Floor 4, Room 410, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9025', 15, 13),
(26, 'Urology - Outpatient', 'South Block, Floor 4, Room 412, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9026', 10, 13),
(27, 'Ophthalmology - Clinic', 'EENT Centre, Floor A, Room 150, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9027', 12, 14),
(28, 'Ophthalmology - Day Surgery', 'EENT Centre, Floor A, Room 155, QMC, Derby Rd, Nottingham NG7 2UH', '0115 970 9028', 10, 14);

-- --------------------------------------------------------

--
-- Table structure for table `ward_patient_admission`
--

CREATE TABLE `ward_patient_admission` (
  `pid` varchar(100) NOT NULL,
  `wardid` int(11) NOT NULL,
  `consultantid` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ward_patient_admission`
--

INSERT INTO `ward_patient_admission` (`pid`, `wardid`, `consultantid`, `date`, `time`, `status`) VALUES
('W20616', 1, 'QM004', '2022-10-07', '09:23:19', 1),
('W20616', 2, 'QM122', '2023-10-01', '07:23:19', 1),
('W20616', 3, 'QM009', '2018-12-07', '08:13:55', 1),
('W20616', 5, 'QM267', '2022-06-07', '21:23:19', 0),
('W20620', 4, 'QM267', '2021-10-07', '08:08:08', 1),
('W21028', 2, 'CH007', '2021-11-07', '08:23:19', 0),
('W21758', 2, 'QM122', '2018-11-27', '23:55:56', 0),
('W21758', 4, 'QT001', '2023-09-29', '08:23:19', 1),
('W21814', 3, 'QM003', '2023-02-17', '08:33:33', 1),
('W21895', 4, 'CH007', '2023-06-07', '21:23:19', 0),
('W21961', 5, 'QM009', '2019-10-18', '08:34:19', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `changes_log`
--
ALTER TABLE `changes_log`
  ADD PRIMARY KEY (`logid`);

--
-- Indexes for table `consultant_status`
--
ALTER TABLE `consultant_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`staffno`);

--
-- Indexes for table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parking_permit`
--
ALTER TABLE `parking_permit`
  ADD PRIMARY KEY (`parkingid`),
  ADD UNIQUE KEY `doctorid` (`doctorid`);

--
-- Indexes for table `parking_request`
--
ALTER TABLE `parking_request`
  ADD PRIMARY KEY (`doctorid`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`NHSno`);

--
-- Indexes for table `patient_examination`
--
ALTER TABLE `patient_examination`
  ADD PRIMARY KEY (`patientid`,`doctorid`,`date`,`time`),
  ADD KEY `fk_exam_doctor` (`doctorid`);

--
-- Indexes for table `patient_test`
--
ALTER TABLE `patient_test`
  ADD PRIMARY KEY (`pid`,`testid`,`date`),
  ADD KEY `fk_test_reference` (`testid`),
  ADD KEY `fk_doctor_id_ref` (`doctorid`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`testid`),
  ADD UNIQUE KEY `testname` (`testname`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `ward`
--
ALTER TABLE `ward`
  ADD PRIMARY KEY (`wardid`),
  ADD KEY `fk_ward_department` (`departmentid`);

--
-- Indexes for table `ward_patient_admission`
--
ALTER TABLE `ward_patient_admission`
  ADD PRIMARY KEY (`pid`,`wardid`,`consultantid`,`date`),
  ADD KEY `fk_admission_ward` (`wardid`),
  ADD KEY `fk_admission_consultant` (`consultantid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `changes_log`
--
ALTER TABLE `changes_log`
  MODIFY `logid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=422;

--
-- AUTO_INCREMENT for table `parking_permit`
--
ALTER TABLE `parking_permit`
  MODIFY `parkingid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `testid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `ward`
--
ALTER TABLE `ward`
  MODIFY `wardid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `parking_permit`
--
ALTER TABLE `parking_permit`
  ADD CONSTRAINT `fk_doctor_permit_approved` FOREIGN KEY (`doctorid`) REFERENCES `doctor` (`staffno`) ON DELETE CASCADE;

--
-- Constraints for table `parking_request`
--
ALTER TABLE `parking_request`
  ADD CONSTRAINT `fk_doctor` FOREIGN KEY (`doctorid`) REFERENCES `doctor` (`staffno`) ON DELETE CASCADE;

--
-- Constraints for table `patient_examination`
--
ALTER TABLE `patient_examination`
  ADD CONSTRAINT `fk_exam_doctor` FOREIGN KEY (`doctorid`) REFERENCES `doctor` (`staffno`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_patient_reference` FOREIGN KEY (`patientid`) REFERENCES `patient` (`NHSno`) ON UPDATE CASCADE;

--
-- Constraints for table `patient_test`
--
ALTER TABLE `patient_test`
  ADD CONSTRAINT `fk_doctor_id_ref` FOREIGN KEY (`doctorid`) REFERENCES `doctor` (`staffno`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_test_patient` FOREIGN KEY (`pid`) REFERENCES `patient` (`NHSno`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_test_reference` FOREIGN KEY (`testid`) REFERENCES `test` (`testid`) ON UPDATE CASCADE;

--
-- Constraints for table `ward`
--
ALTER TABLE `ward`
  ADD CONSTRAINT `fk_ward_department` FOREIGN KEY (`departmentid`) REFERENCES `department` (`id`);

--
-- Constraints for table `ward_patient_admission`
--
ALTER TABLE `ward_patient_admission`
  ADD CONSTRAINT `fk_admission_consultant` FOREIGN KEY (`consultantid`) REFERENCES `doctor` (`staffno`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_admission_patient` FOREIGN KEY (`pid`) REFERENCES `patient` (`NHSno`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_admission_ward` FOREIGN KEY (`wardid`) REFERENCES `ward` (`wardid`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
