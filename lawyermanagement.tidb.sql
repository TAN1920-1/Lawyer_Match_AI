-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2026 at 05:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lawyermanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `administrator_id` varchar(20) NOT NULL,
  `city` varchar(40) NOT NULL,
  `address` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`administrator_id`, `city`, `address`) VALUES
('Admin010101', 'Nashik', 'Shramik Nagar, Nashik');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) NOT NULL,
  `admin_id` varchar(50) NOT NULL,
  `target_id` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `action`, `admin_id`, `target_id`, `details`, `timestamp`) VALUES
(1, 'ADD_LAWYER', 'Firm69a066bb01338', 'Lawyer69a43d14e4f19', 'Added lawyer Tanishq Kailas Rikame', '2026-03-01 13:20:20');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(20) NOT NULL,
  `description` varchar(300) NOT NULL,
  `client_id` varchar(20) NOT NULL,
  `lawyer_id` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `talk_room_code` varchar(64) DEFAULT NULL,
  `talk_display_name` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `date`, `description`, `client_id`, `lawyer_id`, `status`, `talk_room_code`, `talk_display_name`) VALUES
(2, '2025-12-24', 'ok test', 'Client5cbab2a1987da', 'Lawyer5cba0637a2a93', 'Accepted', NULL, NULL),
(3, '2025-12-22', 'test ok', 'Client5cbab2a1987da', 'Lawyer5cba06796f40b', 'Accepted', NULL, NULL),
(4, '2025-12-17', 'eygdshjdfcd', 'Client5cbab2a1987da', 'Lawyer5cbb38fddeafa', 'Accepted', NULL, NULL),
(5, '2025-12-24', 'ok ', 'Client5cbb36c2cfd64', 'Lawyer5cbb38fddeafa', 'Accepted', NULL, NULL),
(6, '2025-12-17', 'jdncdjkfc', 'Client5cbb36c2cfd64', 'Lawyer5cba0637a2a93', 'Accepted', NULL, NULL),
(9, '2026-02-20', 'okkkk', 'Client69822ffed8e09', 'Lawyer5cba0723cc8f9', 'Pending', NULL, NULL),
(10, '2026-02-20', 'okkkk', 'Client69822ffed8e09', 'Lawyer5cba0723cc8f9', 'Pending', NULL, NULL),
(11, '2026-02-20', 'okkkk', 'Client69822ffed8e09', 'Lawyer5cba0723cc8f9', 'Pending', NULL, NULL),
(12, '2026-02-20', 'okkkk', 'Client69822ffed8e09', 'Lawyer5cba0723cc8f9', 'Pending', NULL, NULL),
(13, '2026-02-21', 'okkkk thnkss', 'Client69822ffed8e09', 'Lawyer5cba0723cc8f9', 'Accepted', NULL, NULL),
(14, '2026-02-14', 'hello sir we need a help', 'Client6988d4e733a95', 'Lawyer5cba0637a2a93', 'Accepted', NULL, NULL),
(15, '2026-02-21', 'help me for investment....', 'Client6988dc4957630', 'Lawyer6988d6fddc5f6', 'Accepted', NULL, NULL),
(16, '2026-02-28', 'hello sir...', 'Client6989ae084e3b0', 'Lawyer6988ddc4dff68', 'Accepted', NULL, NULL),
(17, '2026-02-28', 'please accept', 'Client6988dc4957630', 'Lawyer698a1ccf99081', 'Rejected', NULL, NULL),
(18, '2026-02-27', 'hello', '', 'Lawyer69a00a7febc7e', 'Pending', NULL, NULL),
(19, '2026-02-28', 'hello', 'Client69a069e9ed9f9', 'Lawyer69a00a7febc7e', 'Accepted', 'harsh', 'entropy quartz protector'),
(20, '2026-02-28', 'hello', 'Client69a069e9ed9f9', 'Lawyer69a00b95c48cf', 'Accepted', 'abc', 'Equinox Uranium Dictator'),
(21, '2026-02-26', 'cdsbt', 'Client69a069e9ed9f9', 'Lawyer69a00a7febc7e', 'Rejected', NULL, NULL),
(22, '2026-02-26', 'cdsbt', 'Client69a069e9ed9f9', 'Lawyer69a00a7febc7e', 'Rejected', 'abc', 'ehbchwc'),
(23, '2026-03-07', 'dfthyjrte', 'Client69a069e9ed9f9', 'Lawyer69a007090542b', 'Pending', NULL, NULL),
(24, '2026-03-26', 'i want lawyer', 'Client69a069e9ed9f9', 'Lawyer69a00a7febc7e', 'Accepted', 'jeet', 'Chaos Tungsten Silhouette'),
(25, '2026-03-20', 'i have a civil case', 'Client69a069e9ed9f9', 'Lawyer69b2cc65ccbab', 'Accepted', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `client_id` varchar(20) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `full_address` varchar(200) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip_code` varchar(50) NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`client_id`, `contact_number`, `full_address`, `city`, `zip_code`, `image`) VALUES
('Client5cb6315a228dd', '7823434230', 'DK stedium Thane West', 'thane', '400675', '41463624_394349607765000_4230660266800.png'),
('Client5cbab2a1987da', '7980003423', 'swami narayan nagar', 'Bhusawal', '1000', '20190420074817_24774766_1437963912919320_8170347638881673647_n.jpg '),
('Client5cbb36c2cfd64', '7982343423', 'Indira Nagar,Nashik', 'Nashik', '1234 ', '20190420171202_Entity Relationship Diagram.jpeg '),
('Client69822da0040f6', '8788267746', 'satpur', 'Mumbai', '422001 ', '20260203181720_2.jpg '),
('Client69822ffed8e09', '8788267746', 'satpur', 'Nashik', '4220 ', '20260203182726_2.jpg '),
('Client6983060c36e83', '8788267746', 'prof colony', 'Sambhaji Nagar', '422003 ', '20260204094044_3.jpg '),
('Client6988d4e733a95', '8788267746', 'prof colony', 'Nagpur', '422004 ', '20260208192439_3.jpg '),
('Client6988dc4957630', '8788267746', 'cidco', 'Nashik', '422005 ', '20260208195609_h7.jpg '),
('Client6989ae084e3b0', '8788267746', 'prof colony', 'Nashik', '422001 ', '20260209105104_h2.jpeg '),
('Client699f4dc24d620', '106106106', 'hidden leaf', 'Nashik', '422003 ', '20260225203010_kakashi sensei.jpg '),
('Client69a069e9ed9f9', '1234567898', 'baban nivas, adgaon', 'Nashik', '422003 ', '20260226164233_saturo gojo wall 1.jpg '),
('Client69b64c02e7dfc', '4518451863', 'bodhle nagar, nashik', 'Nashik', '422005 ', '20260315070450_ChatGPT Image May 17, 2025, 12_13_19 AM.png ');

-- --------------------------------------------------------

--
-- Table structure for table `firm`
--

CREATE TABLE `firm` (
  `firm_id` varchar(50) NOT NULL,
  `firm_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `num_lawyers` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `speciality` varchar(255) DEFAULT NULL,
  `education` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `firm`
--

INSERT INTO `firm` (`firm_id`, `firm_name`, `email`, `contact`, `num_lawyers`, `address`, `city`, `zip_code`, `speciality`, `education`, `description`, `image`) VALUES
('Firm69a066bb01338', 'Nashik Lawyers Firm', 'tan@gmail.com', '1234432121', 7, 'C.B.S. , near Court, Nashik ', 'Nashik', '422003', 'IT Law', '3 lawyers LLB and 4 are LLM', 'Total members=7, Expert in cyber security cases and IT laws', '20260301150047_law firm.jpg'),
('Firm69b6558d23666', 'ALC LAW ltd.', 'depressedtanu2003@gmail.com', '6363636363', 7, 'Jatra Hotel,Adgaon,  Nashik', 'Nashik', '422003', 'Company Law', '5 members are LLM with more than 10 years+ experience\r\n2 members are LLB with 5 years+ experience', 'handled more than 50+ company cases', '20260315074533_kakashi sensei.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `firm_booking`
--

CREATE TABLE `firm_booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` varchar(50) DEFAULT NULL,
  `firm_id` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `talk_room_code` varchar(64) DEFAULT NULL,
  `talk_display_name` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `firm_booking`
--

INSERT INTO `firm_booking` (`id`, `client_id`, `firm_id`, `date`, `description`, `status`, `talk_room_code`, `talk_display_name`) VALUES
(1, 'Client69a069e9ed9f9', 'Firm69a066bb01338', '2026-02-26', 'i want a IT specialists ', 'Accepted', NULL, NULL),
(2, 'Client69a069e9ed9f9', 'Firm69a066bb01338', '2026-02-26', 'abcbcbc', 'Accepted', NULL, NULL),
(3, 'Client69a069e9ed9f9', 'Firm69a066bb01338', '2026-02-26', 'abcabc\r\n', 'Accepted', NULL, NULL),
(4, 'Client69a069e9ed9f9', 'Firm69a066bb01338', '2026-02-26', 'abc', 'Accepted', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lawyer`
--

CREATE TABLE `lawyer` (
  `lawyer_id` varchar(20) NOT NULL,
  `contact_Number` varchar(15) NOT NULL,
  `university_College` varchar(100) NOT NULL,
  `degree` varchar(100) NOT NULL,
  `passing_year` varchar(100) NOT NULL,
  `full_address` varchar(200) NOT NULL,
  `city` varchar(50) NOT NULL,
  `zip_code` varchar(50) NOT NULL,
  `practise_Length` varchar(100) NOT NULL,
  `case_handle` varchar(500) NOT NULL,
  `speciality` varchar(100) NOT NULL,
  `image` varchar(150) NOT NULL,
  `experience_years` int(11) DEFAULT 0,
  `education` text DEFAULT NULL,
  `certifications` text DEFAULT NULL,
  `firm_requirements` text DEFAULT NULL,
  `avg_rating` decimal(3,2) DEFAULT 0.00,
  `review_count` int(11) DEFAULT 0,
  `hourly_rate` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lawyer`
--

INSERT INTO `lawyer` (`lawyer_id`, `contact_Number`, `university_College`, `degree`, `passing_year`, `full_address`, `city`, `zip_code`, `practise_Length`, `case_handle`, `speciality`, `image`, `experience_years`, `education`, `certifications`, `firm_requirements`, `avg_rating`, `review_count`, `hourly_rate`) VALUES
('Lawyer699ffa7260908', '9123456701', 'ILS Law College', 'LLB', '2007', 'College Rd, Pune', 'Mumbai', '411004', '11-15 years', 'Criminal matter,Civil matter,', 'Criminal Law', '20260226090807_lawyer 1.png', 0, NULL, NULL, NULL, 4.80, 0, 1500.00),
('Lawyer699ffed220992', '9876543321', 'K K wagh college of Law', 'LLM', '2010', 'Amrutdham, Nashik', 'Delhi', '422003', '6-10 years', 'Commercial matter,Others,', 'Commercial Law', '20260226090538_lawyer 12.png', 0, NULL, NULL, NULL, 4.50, 0, 1200.00),
('Lawyer69a000b552193', '9123456707', 'North Maharashtra University Law Dept', 'LLB', '2005', 'MIDC, Bhusawal', 'Mumbai', '425201', '16-20 years', 'Family Law,Labour Law,Others,', 'Labour Law', '20260226091341_lawyer 14.png', 0, NULL, NULL, NULL, 4.20, 0, 1000.00),
('Lawyer69a007090542b', '9123456730', 'K K wagh college of Law', 'LLM', '2009', 'Adgaon, Nashik', 'Bangalore', '422003', '6-10 years', 'Family Law,Property Law,Others,', 'Property Law', '20260226094325_ChatGPT Image Feb 26, 2026, 02_06_00 PM.png', 0, NULL, NULL, NULL, 3.50, 0, 800.00),
('Lawyer69a0094a85ab8', '9889877821', 'SDM Law College', 'LLM', '2016', 'Jadeja Nivas, Rajkot', 'Pune', '360001', '6-10 years', 'Writ Jurisdiction,Commercial matter,', 'Writ Jurisdiction', '20260226095018_ChatGPT Image Feb 26, 2026, 02_02_49 PM.png', 0, NULL, NULL, NULL, 4.00, 0, 1100.00),
('Lawyer69a00a7febc7e', '9595979723', 'M. K. Gandhi College of Law, Sambhaji Nagar', 'LLM', '2005', 'Paithan Gate, Sambhaji Nagar', 'Mumbai', '431005', 'Most Senior', 'Criminal matter,Civil matter,Company law,Family Law,Religious Matter,Property Law,Others,', 'Criminal Law', '20260226095527_lawyer 11.png', 0, NULL, NULL, NULL, 4.90, 1, 2500.00),
('Lawyer69a00b95c48cf', '8521479630', 'Army Institute of Law, Surat', 'LLB', '2023', 'Variya Nivas, Surat', 'Delhi', '395004', '1-5 years', 'Taxation Matter,Others,', 'Taxation Law', '20260226100005_men.jpeg', 0, NULL, NULL, NULL, 3.80, 0, 900.00),
('Lawyer69a43b824d4d4', '0000000000', '', '', '', '', 'Chennai', '', '', 'BAR999', 'Testing', '', 5, 'Test Uni', 'None', 'None', 4.10, 0, 1300.00),
('Lawyer69a43d14e4f19', '09850323733', '', '', '', '', 'Mumbai', '', '', '101', 'criminal', '', 9, 'kkw poly', 'crimial law expert', 'avcbcndsbkva', 4.70, 0, 1800.00),
('Lawyer69b2cc65ccbab', '9850323733', 'Savitribai Phule Pune University Law Dept', 'LLB', '2011', 'madsanghvi, nashik', 'Kolkata', '422003', '6-10 years', 'Civil matter,Others,', 'Civil Law', '20260312152333_client 7.png', 0, NULL, NULL, NULL, 4.30, 0, 1400.00),
('Lawyer69b544c07b568', '', '', '', '', '', 'Mumbai', '', '1-5 years', '', 'Criminal Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.80, 0, 0.00),
('Lawyer69b544c089ce5', '', '', '', '', '', 'Delhi', '', '1-5 years', '', 'Family Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.70, 0, 0.00),
('Lawyer69b544c0973d3', '', '', '', '', '', 'Chennai', '', '1-5 years', '', 'Corporate Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.00, 0, 0.00),
('Lawyer69b544c0a5ddd', '', '', '', '', '', 'Pune', '', '1-5 years', '', 'Civil Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.20, 0, 0.00),
('Lawyer69b544c0b3d1b', '', '', '', '', '', 'Bangalore', '', '6-10 years', '', 'IT Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.90, 0, 0.00),
('Lawyer69b544c0c1b36', '', '', '', '', '', 'Hyderabad', '', '6-10 years', '', 'Property Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.00, 0, 0.00),
('Lawyer69b544c0cfa62', '', '', '', '', '', 'Kolkata', '', '6-10 years', '', 'Commercial Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.90, 0, 0.00),
('Lawyer69b544c0dcf4f', '', '', '', '', '', 'Kochi', '', '6-10 years', '', 'Labour Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.00, 0, 0.00),
('Lawyer69b544c0ea4f5', '', '', '', '', '', 'Ahmedabad', '', '11-15 years', '', 'Taxation Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.00, 0, 0.00),
('Lawyer69b544c1037ea', '', '', '', '', '', 'Pune', '', '11-15 years', '', 'Contract Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.80, 0, 0.00),
('Lawyer69b544c111976', '', '', '', '', '', 'Delhi', '', '11-15 years', '', 'Criminal Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.70, 0, 0.00),
('Lawyer69b544c11ef05', '', '', '', '', '', 'Kolkata', '', '11-15 years', '', 'Civil Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.20, 0, 0.00),
('Lawyer69b544c12c125', '', '', '', '', '', 'Chennai', '', '16-20 years', '', 'Company Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.30, 0, 0.00),
('Lawyer69b544c13948d', '', '', '', '', '', 'Mumbai', '', '16-20 years', '', 'Writ Jurisdiction', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.20, 0, 0.00),
('Lawyer69b544c146d40', '', '', '', '', '', 'Lucknow', '', '16-20 years', '', 'Religious Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.40, 0, 0.00),
('Lawyer69b544c1546ef', '', '', '', '', '', 'Bhubaneswar', '', '16-20 years', '', 'Investment Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.50, 0, 0.00),
('Lawyer69b544c1622f5', '', '', '', '', '', 'Chandigarh', '', 'Most Senior', '', 'Criminal Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 5.00, 0, 0.00),
('Lawyer69b544c16fb1b', '', '', '', '', '', 'Jaipur', '', 'Most Senior', '', 'Construction Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.60, 0, 0.00),
('Lawyer69b544c17d19f', '', '', '', '', '', 'Kolkata', '', 'Most Senior', '', 'Corporate Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.90, 0, 0.00),
('Lawyer69b544c18a70e', '', '', '', '', '', 'Indore', '', 'Most Senior', '', 'Family Law', 'close-up-lawyer-signing-papers.jpg', 0, NULL, NULL, NULL, 4.50, 0, 0.00),
('Lawyer69b64e975768e', '', '', '', '', '', '', '', '', '', '', '', 0, NULL, NULL, NULL, 0.00, 0, 0.00),
('Lawyer69b65076e70af', '1818181818', 'NATIONAL LAW UNIVERSITY, DELHI', 'LLB', '2007', 'C.B.S. , Nashik', 'Nashik', '422003', '16-20 years', 'Construction law,Family Law,Property Law,Others,', 'Property Law', '20260315072350_profile-new.jpg', 0, NULL, NULL, NULL, 0.00, 0, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `lawyer_ratings`
--

CREATE TABLE `lawyer_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `client_id` varchar(20) NOT NULL,
  `lawyer_id` varchar(20) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lawyer_ratings`
--

INSERT INTO `lawyer_ratings` (`id`, `booking_id`, `client_id`, `lawyer_id`, `rating`, `feedback`, `created_at`) VALUES
(1, 24, 'Client69a069e9ed9f9', 'Lawyer69a00a7febc7e', 4, 'nathuram godse has very good experience and nice knowledge about cases\r\n', '2026-03-12 12:29:00');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `booking_id`, `type`, `is_read`, `created_at`) VALUES
(1, 'Lawyer69b2cc65ccbab', 'New Booking Request', 'You have received a new booking request from harshwardhan malode for 2026-03-20.', 25, 'new_booking', 1, '2026-03-12 14:28:43'),
(2, 'Client69a069e9ed9f9', 'Booking Accepted', 'Your booking for 2026-03-20 has been accepted by naruto Patil. You can now start the discussion.', 25, 'booking_accepted', 1, '2026-03-12 14:57:42');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `u_id` varchar(20) NOT NULL,
  `first_Name` varchar(100) NOT NULL,
  `last_Name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `firm_id` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `first_Name`, `last_Name`, `email`, `password`, `status`, `role`, `firm_id`, `is_active`, `dob`) VALUES
('Admin010101', 'admin', 'admin', 'admin@gmail.com', '$2y$10$i0woFbGmmKDs2q/BvbK0OOZC.mXcLeYR96LqMK3AmxHZywaene8.O', 'Active', 'Admin', NULL, 1, NULL),
('Client69a069e9ed9f9', 'harshwardhan', 'malode', 'baban@gmail.com', '$2y$10$i0woFbGmmKDs2q/BvbK0OOZC.mXcLeYR96LqMK3AmxHZywaene8.O', 'Active', 'User', NULL, 1, NULL),
('Client69b64c02e7dfc', 'Umesh', 'Chaudhari', 'triggeredfan41@gmail.com', '$2y$10$JQSOsl5/S41PDY6kAtTPY.prtMeVIi9Y2z5tXrrxYxwSvzHiPdIY6', 'Active', 'User', NULL, 1, NULL),
('Firm69a066bb01338', 'Nashik Lawyers Firm', '', 'tan@gmail.com', '$2y$10$i0woFbGmmKDs2q/BvbK0OOZC.mXcLeYR96LqMK3AmxHZywaene8.O', 'Active', 'FIRM_ADMIN', NULL, 1, NULL),
('Firm69b6558d23666', 'ALC LAW ltd.', '', 'depressedtanu2003@gmail.com', '$2y$10$pESA.2TmoAuwoBa4FKdK0OMJ3iMfVBFndHj8K3kijaC8hE4KqRo0q', 'Active', 'FIRM_ADMIN', 'Firm69b6558d23666', 1, NULL),
('Lawyer699ffa7260908', 'Amit ', 'Kulkarni', 'amit.k@gmail.com', '$2y$10$i0woFbGmmKDs2q/BvbK0OOZC.mXcLeYR96LqMK3AmxHZywaene8.O', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer699ffed220992', 'Sarvangi', 'Gupta', 'sg@gmail.com', '$2y$10$i0woFbGmmKDs2q/BvbK0OOZC.mXcLeYR96LqMK3AmxHZywaene8.O', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69a000b552193', 'Rahul', 'Pawar', 'rahul.p@gmail.com', '$2y$10$i0woFbGmmKDs2q/BvbK0OOZC.mXcLeYR96LqMK3AmxHZywaene8.O', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69a007090542b', 'Nilesh', 'Mahajan', 'nilesh.m@gmail.com', '$2y$10$i0woFbGmmKDs2q/BvbK0OOZC.mXcLeYR96LqMK3AmxHZywaene8.O', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69a0094a85ab8', 'Prakash', 'Ahuja', 'prakasha@gmail.com', '$2y$10$i0woFbGmmKDs2q/BvbK0OOZC.mXcLeYR96LqMK3AmxHZywaene8.O', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69a00a7febc7e', 'Nathuram', 'Godse', 'nathugod@gmail.com', '$2y$10$i0woFbGmmKDs2q/BvbK0OOZC.mXcLeYR96LqMK3AmxHZywaene8.O', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69a00b95c48cf', 'Shubham ', 'Shinde', 'shubhams@gmail.com', '$2y$10$i0woFbGmmKDs2q/BvbK0OOZC.mXcLeYR96LqMK3AmxHZywaene8.O', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69a43d14e4f19', 'Tanishq Kailas Rikame', '', 'tanishqrikame41@gmail.com', '$2y$10$i0woFbGmmKDs2q/BvbK0OOZC.mXcLeYR96LqMK3AmxHZywaene8.O', 'Active', 'FIRM_LAWYER', 'Firm69a066bb01338', 1, '1996-02-01'),
('Lawyer69b2cc65ccbab', 'naruto', 'Patil', 'lawyerlatex.ai@gmail.com', '$2y$10$i0woFbGmmKDs2q/BvbK0OOZC.mXcLeYR96LqMK3AmxHZywaene8.O', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c07b568', 'Amit', 'Sharma', 'amit.sharma@indianlawyer.com', '$2y$10$5fOymMiUK77lGvmSqeHaue.i31v4xJbTXWHFQvOBXWltUX1Wv1kti', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c089ce5', 'Priya', 'Patel', 'priya.patel@indianlawyer.com', '$2y$10$YHlP4zWKwaxhL8oqeOifHuchsdrnKorMhaoYqGhZwXYiy5fe23.nS', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c0973d3', 'Rajesh', 'Iyer', 'rajesh.iyer@indianlawyer.com', '$2y$10$CaCfWhu0aVLMJQrR.kcnHuJnfdhGwMXDKkEkuPdcHtH7Qw/E2nUm6', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c0a5ddd', 'Anjali', 'Deshmukh', 'anjali.deshmukh@indianlawyer.com', '$2y$10$OmNdPLwK51YPs2mL5rs62umQB6Nj36f3pdeyC1AERI/e0jqF5k/Fi', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c0b3d1b', 'Vikram', 'Singh', 'vikram.singh@indianlawyer.com', '$2y$10$WMLtfSOnrtLyYLEfvrSNgOE2ArliMBncD.gPpHAzygBcSvuCcuPV2', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c0c1b36', 'Sneha', 'Reddy', 'sneha.reddy@indianlawyer.com', '$2y$10$D4GdmQq0fxIOQZwZP1dZ6.t3t2lNFwbqlZiJd.DQLHdxko9bZqL5e', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c0cfa62', 'Sanjay', 'Gupta', 'sanjay.gupta@indianlawyer.com', '$2y$10$XK4aKK/KIwpp0IGAXgfbK.l716a3dKNVJUnCBkhal9sU385BNBL0i', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c0dcf4f', 'Meera', 'Nair', 'meera.nair@indianlawyer.com', '$2y$10$dEppbiwHCpG1woo.hmxbUexrPbEwStW.wAMi.e49PxIniadQruux.', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c0ea4f5', 'Rohan', 'Verma', 'rohan.verma@indianlawyer.com', '$2y$10$RYXhzSN0a6v.uQZYJ9LW5eUdU2CryzqF8UkWpoVgWXH1SxMGdVQoO', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c1037ea', 'Kavita', 'Joshi', 'kavita.joshi@indianlawyer.com', '$2y$10$QXUfd4HJKRESVa5/KWWOXexHyxF/h3HTSLkVvoyB4mSq2w3BF1JRm', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c111976', 'Arjun', 'Malhotra', 'arjun.malhotra@indianlawyer.com', '$2y$10$OjaxbubXXNlS1XyI2dAr7Omni5mwemmSH37z2brRBX7WETHXlG/5C', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c11ef05', 'Deepa', 'Bose', 'deepa.bose@indianlawyer.com', '$2y$10$2SuHLSWBF1CeaTXgPQcoUekQ.BKXSFVF/eEoUzFbEvwV4uE3edUzi', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c12c125', 'Harish', 'Kumar', 'harish.kumar@indianlawyer.com', '$2y$10$jYbwZ0/jk1hUF/5rnkIDzutWjpBnlX3og33lOf9crsaTxCIqm4u1K', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c13948d', 'Shanti', 'Rao', 'shanti.rao@indianlawyer.com', '$2y$10$mZULoPJPSDtjZuykLh2eNuT3MgZoJG1MggatuOSpO52V9ffgR.6bS', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c146d40', 'Abhishek', 'Pandey', 'abhishek.pandey@indianlawyer.com', '$2y$10$2i9Fu2VYa/d94O6x2hR6kuOiotf/5n6lDq6q.VDRm0XLg8/ics13i', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c1546ef', 'Sunita', 'Das', 'sunita.das@indianlawyer.com', '$2y$10$DZqdbCWEWyE1PT4CP/c9R./1y/I4QF.uSRST7yVNsQwLapc8KMo8.', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c1622f5', 'Mohit', 'Chauhan', 'mohit.chauhan@indianlawyer.com', '$2y$10$velBPWI/sgN.Cq1PMZXrkeAajUEOR9JLzNVm4kG9PU6JdCztx477a', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c16fb1b', 'Pooja', 'Kapoor', 'pooja.kapoor@indianlawyer.com', '$2y$10$VWrO7hN5vmD9WHwAlxqW8uLU4YnqYbkzvHCnvW0o5NQ6u3iBeDO/q', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c17d19f', 'Gautam', 'Sen', 'gautam.sen@indianlawyer.com', '$2y$10$ehMhRkZ4NGxk4UmpTj9JvOLCkPgIlJ1Sc1oZyyQtWoggcKgWj76SS', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b544c18a70e', 'Rashmi', 'Tiwari', 'rashmi.tiwari@indianlawyer.com', '$2y$10$/NloVqmIbn5/ZoiVskygEuq94pjeZP99uTJmH2K2m1jDh1.bOIy.i', 'Active', 'Lawyer', NULL, 1, NULL),
('Lawyer69b64e975768e', '', '', '', '$2y$10$ESYcBZ6skgJH6fOjNJ/WGuDQ2zuoPPTpL4g4uVUSiBfs2VVIdUvb.', 'Pending', 'Lawyer', NULL, 1, NULL),
('Lawyer69b65076e70af', 'umesh', 'pachpatil', 'last45name@gmail.com', '$2y$10$vS0nDEJIGVM8LAky.xXLR.Mf95Zao0t4aPwJSpRZ06QdcPm2sRDS2', 'Active', 'Lawyer', NULL, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`administrator_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `firm`
--
ALTER TABLE `firm`
  ADD PRIMARY KEY (`firm_id`);

--
-- Indexes for table `firm_booking`
--
ALTER TABLE `firm_booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lawyer`
--
ALTER TABLE `lawyer`
  ADD PRIMARY KEY (`lawyer_id`);

--
-- Indexes for table `lawyer_ratings`
--
ALTER TABLE `lawyer_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_booking_rating` (`booking_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--

--
-- AUTO_INCREMENT for table `booking`
--

--
-- AUTO_INCREMENT for table `firm_booking`
--

--
-- AUTO_INCREMENT for table `lawyer_ratings`
--

--
-- AUTO_INCREMENT for table `notifications`
--
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
