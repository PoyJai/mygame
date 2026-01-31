-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2026 at 05:31 PM
-- Server version: 12.1.2-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aesthetic_games_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `genre` varchar(50) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `image_url_2` varchar(255) DEFAULT NULL,
  `image_url_3` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `long_description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `release_date` date DEFAULT NULL,
  `developer` varchar(255) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 0.0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `title`, `description`, `genre`, `image_url`, `image_url_2`, `image_url_3`, `created_at`, `long_description`, `price`, `release_date`, `developer`, `rating`) VALUES
(1, 'Minecraft', 'สร้างโลก เอาชีวิตรอด ผจญภัย', 'Sandbox', 'https://image.api.playstation.com/vulcan/ap/rnd/202407/0401/670c294ded3baf4fa11068db2ec6758c63f7daeb266a35a1.png?w=440', NULL, NULL, '2011-11-18 18:46:09', 'เกมแนว Sandbox ที่ผู้เล่นสามารถสร้าง สำรวจ และเอาชีวิตรอดในโลกบล็อกได้อย่างอิสระ เล่นได้ทั้งโหมดเดี่ยวและออนไลน์ เน้นความคิดสร้างสรรค์', 0.10, '2011-11-18', 'Mojang', 9.5),
(2, 'GTA V', 'อาชญากร 3 คนในเมืองโลกเปิด Los Santos ทำภารกิจหรือใช้ชีวิตอิสระได้', 'Open-World Action', 'https://www.gamespot.com/a/uploads/original/mig/6/8/4/4/2286844-gtalogo-big_61199_screen.jpg', NULL, NULL, '2025-12-14 18:46:09', 'เกมโลกเปิดที่เล่าเรื่องอาชญากร 3 คนซึ่งมีชีวิตและเป้าหมายแตกต่างกัน ผู้เล่นสามารถทำภารกิจหลักหรือใช้ชีวิตอิสระในเมือง Los Santos ที่สมจริง เกมมีเนื้อเรื่องเข้มข้นและกิจกรรมหลากหลาย', 1890.00, '2013-09-17', 'Rockstar', 9.7),
(3, 'Tetris', 'เรียงบล็อกลบแถว ฝึกสมาธิและการตัดสินใจ เล่นได้ไม่รู้จบ', 'Puzzle', 'https://tetris.com/_next/image?url=https%3A%2F%2Fwww.datocms-assets.com%2F145957%2F1744295598-tetris-firetv-appicon-728x728_728x728.png&w=2048&q=75', NULL, NULL, '2025-12-14 18:46:09', 'กมปริศนาคลาสสิกที่ผู้เล่นต้องเรียงบล็อกให้เต็มแถวเพื่อลบคะแนน เกมใช้ทักษะการคิดและความเร็วในการตัดสินใจ เล่นได้ทุกวัยและไม่มีวันจบ', 300.00, '1984-06-06', 'Nintendo', 9.0),
(4, 'Wii Sports', 'กีฬาควบคุมด้วยการเคลื่อนไหว เล่นง่าย เหมาะทุกวัย', 'Sports', 'https://gex.co.uk/43452-large_default/wii-sports-carded-sleeve.jpg', NULL, NULL, '2025-12-14 18:46:09', 'เกมกีฬาที่ใช้การเคลื่อนไหวของผู้เล่นผ่านคอนโทรลเลอร์ ผู้เล่นสามารถเล่นกีฬา เช่น เทนนิส โบว์ลิ่ง และเบสบอล เกมเหมาะสำหรับครอบครัวและการเล่นเป็นกลุ่ม', 1600.00, '2017-11-19', 'Nintendo', 8.8),
(5, 'PUBG: Battlegrounds', 'เอาชีวิตรอด 100 คนจนเหลือผู้ชนะคนสุดท้าย', 'Battle Royale', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSyjLTaKmi4LmHgos-l_Ri29h2HEV80craHRQ&s', NULL, NULL, '2025-12-14 18:46:09', 'เกม Battle Royale ที่ผู้เล่น 100 คนถูกปล่อยลงบนแผนที่เดียวกัน ผู้เล่นต้องหาอาวุธและเอาชีวิตรอดจนเหลือคนสุดท้าย เกมเน้นความตื่นเต้นและกลยุทธ์', 950.00, '2017-12-20', 'Krafton', 8.5),
(6, 'Mario Kart 8 Deluxe', 'แข่งรถแฟนตาซีใช้อาวุธและไอเทม สายปาร์ตี้สนุกมาก', 'Racing', 'https://cdn.cdkeys.com/496x700/media/catalog/product/s/t/streamer-life-simulator-free-download-steam-repacks_4_.jpg', NULL, NULL, '2025-12-14 18:46:09', 'แข่งรถแฟนตาซีพร้อมไอเทมโจมตีคู่แข่ง\r\nสนุก วุ่นวาย เหมาะกับการเล่นเป็นกลุ่ม\r\n\r\n', 1890.00, '2017-04-28', 'Nintendo\r\n', 9.2),
(7, 'Wilderness Heart', 'เกมเอาชีวิตรอดและสร้างที่พักอาศัยในป่า', 'Survival', 'https://placehold.co/400x250/F97316/ffffff?text=Survival+Crafting', NULL, NULL, '2025-12-14 18:46:09', NULL, 0.00, NULL, NULL, 0.0),
(8, 'Harvest Valley', 'เกมทำฟาร์มที่ผ่อนคลายและสร้างชุมชนของคุณเอง', 'Simulation', 'https://placehold.co/400x250/34D399/ffffff?text=Farming+Sim', NULL, NULL, '2025-12-14 18:46:09', NULL, 0.00, NULL, NULL, 0.0),
(9, 'Shadow Falls', 'เกมแนวสืบสวนสอบสวนแบบ Noir สุดคลาสสิก', 'Visual Novel', 'https://placehold.co/400x250/9CA3AF/ffffff?text=Noir+Mystery', NULL, NULL, '2025-12-14 18:46:09', NULL, 0.00, NULL, NULL, 0.0),
(10, 'Northern Lords', 'เกมกลยุทธ์สร้างอาณาจักรไวกิ้งและพิชิตดินแดน', 'Strategy', 'https://placehold.co/400x250/D97706/ffffff?text=Viking+Raid', NULL, NULL, '2025-12-14 18:46:09', NULL, 0.00, NULL, NULL, 0.0),
(11, 'Starbound Echoes', 'เกม RPG แฟนตาซีสไตล์ญี่ปุ่นที่เน้นเรื่องราว', 'JRPG', 'https://placehold.co/400x250/7C3AED/ffffff?text=Fantasy+JRPG', NULL, NULL, '2025-12-14 18:46:09', NULL, 0.00, NULL, NULL, 0.0),
(12, 'Gravity Jump', 'เกมแพลตฟอร์มที่ท้าทายด้วยกลไกการสลับแรงโน้มถ่วง', 'Platformer', 'https://placehold.co/400x250/DB2777/ffffff?text=Platform+Puzzle', NULL, NULL, '2025-12-14 18:46:09', NULL, 0.00, NULL, NULL, 0.0),
(13, 'Chronos Gate', 'เกม Action RPG ที่เดินทางข้ามกาลเวลาเพื่อแก้ไขอดีต', 'Action RPG', 'https://placehold.co/400x250/60A5FA/ffffff?text=Time+Travel+RPG', NULL, NULL, '2025-12-14 18:46:09', NULL, 0.00, NULL, NULL, 0.0),
(14, 'Aether Gardens', 'เกมสร้างสวนลอยฟ้าและจัดการทรัพยากร', 'Management Sim', 'https://placehold.co/400x250/FBBF24/ffffff?text=Sky+Garden+Sim', NULL, NULL, '2025-12-14 18:46:09', NULL, 0.00, NULL, NULL, 0.0),
(15, 'Rogue Planet', 'เกมยิงมุมมองบุคคลที่หนึ่งแนวสำรวจดาวเคราะห์ที่ถูกทิ้งร้าง', 'FPS/Exploration', 'https://placehold.co/400x250/EF4444/ffffff?text=Sci-Fi+Shooter', NULL, NULL, '2025-12-14 18:46:09', NULL, 0.00, NULL, NULL, 0.0),
(16, 'Silent Cartographer', 'เกมลึกลับที่ต้องสร้างแผนที่โลกที่ไม่มีใครเคยเห็นมาก่อน', 'Puzzle/Exploration', 'https://placehold.co/400x250/10B981/ffffff?text=Map+Making+Game', NULL, NULL, '2025-12-14 18:46:09', NULL, 0.00, NULL, NULL, 0.0),
(17, 'Neon Skyline II', 'การผจญภัยในโลกไซเบอร์พังก์ที่เต็มไปด้วยแสงนีออน', 'Action RPG', 'https://placehold.co/400x250/334155/ffffff?text=Cyberpunk+City', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(18, 'Emerald Trails II', 'เกมสำรวจป่าลึกลับและแก้ไขปริศนาโบราณ', 'Adventure', 'https://placehold.co/400x250/22C55E/ffffff?text=Mystic+Forest', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(19, 'Dune Drifters II', 'เกมแข่งรถที่ใช้ฟิสิกส์สมจริงท่ามกลางทะเลทราย', 'Racing', 'https://placehold.co/400x250/EAB308/ffffff?text=Desert+Racer', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(20, 'Pixel Blasters II', 'เกมยิงอวกาศสไตล์ย้อนยุค (Shmup) ที่เร้าใจ', 'Arcade', 'https://placehold.co/400x250/F43F5E/ffffff?text=Retro+Space', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(21, 'Geometric Flow II', 'เกมปริศนาที่ต้องใช้ตรรกะและรูปแบบนามธรรม', 'Puzzle', 'https://placehold.co/400x250/5B21B6/ffffff?text=Abstract+Puzzle', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(22, 'Deep Blue II', 'เกมดำน้ำสำรวจโลกใต้ทะเลที่กว้างใหญ่', 'Exploration', 'https://placehold.co/400x250/00BCD4/ffffff?text=Ocean+Exploration', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(23, 'Wilderness Heart II', 'เกมเอาชีวิตรอดและสร้างที่พักอาศัยในป่า', 'Survival', 'https://placehold.co/400x250/F97316/ffffff?text=Survival+Crafting', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(24, 'Harvest Valley II', 'เกมทำฟาร์มที่ผ่อนคลายและสร้างชุมชนของคุณเอง', 'Simulation', 'https://placehold.co/400x250/34D399/ffffff?text=Farming+Sim', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(25, 'Shadow Falls II', 'เกมแนวสืบสวนสอบสวนแบบ Noir สุดคลาสสิก', 'Visual Novel', 'https://placehold.co/400x250/9CA3AF/ffffff?text=Noir+Mystery', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(26, 'Northern Lords II', 'เกมกลยุทธ์สร้างอาณาจักรไวกิ้งและพิชิตดินแดน', 'Strategy', 'https://placehold.co/400x250/D97706/ffffff?text=Viking+Raid', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(27, 'Starbound Echoes II', 'เกม RPG แฟนตาซีสไตล์ญี่ปุ่นที่เน้นเรื่องราว', 'JRPG', 'https://placehold.co/400x250/7C3AED/ffffff?text=Fantasy+JRPG', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(28, 'Gravity Jump II', 'เกมแพลตฟอร์มที่ท้าทายด้วยกลไกการสลับแรงโน้มถ่วง', 'Platformer', 'https://placehold.co/400x250/DB2777/ffffff?text=Platform+Puzzle', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(29, 'Chronos Gate II', 'เกม Action RPG ที่เดินทางข้ามกาลเวลาเพื่อแก้ไขอดีต', 'Action RPG', 'https://placehold.co/400x250/60A5FA/ffffff?text=Time+Travel+RPG', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(30, 'Aether Gardens II', 'เกมสร้างสวนลอยฟ้าและจัดการทรัพยากร', 'Management Sim', 'https://placehold.co/400x250/FBBF24/ffffff?text=Sky+Garden+Sim', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(31, 'Rogue Planet II', 'เกมยิงมุมมองบุคคลที่หนึ่งแนวสำรวจดาวเคราะห์ที่ถูกทิ้งร้าง', 'FPS/Exploration', 'https://placehold.co/400x250/EF4444/ffffff?text=Sci-Fi+Shooter', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(32, 'Silent Cartographer II', 'เกมลึกลับที่ต้องสร้างแผนที่โลกที่ไม่มีใครเคยเห็นมาก่อน', 'Puzzle/Exploration', 'https://placehold.co/400x250/10B981/ffffff?text=Map+Making+Game', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(48, 'Neon Skyline III', 'การผจญภัยในโลกไซเบอร์พังก์ที่เต็มไปด้วยแสงนีออน', 'Action RPG', 'https://placehold.co/400x250/334155/ffffff?text=Cyberpunk+City', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(49, 'Emerald Trails III', 'เกมสำรวจป่าลึกลับและแก้ไขปริศนาโบราณ', 'Adventure', 'https://placehold.co/400x250/22C55E/ffffff?text=Mystic+Forest', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(50, 'Dune Drifters III', 'เกมแข่งรถที่ใช้ฟิสิกส์สมจริงท่ามกลางทะเลทราย', 'Racing', 'https://placehold.co/400x250/EAB308/ffffff?text=Desert+Racer', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(51, 'Pixel Blasters III', 'เกมยิงอวกาศสไตล์ย้อนยุค (Shmup) ที่เร้าใจ', 'Arcade', 'https://placehold.co/400x250/F43F5E/ffffff?text=Retro+Space', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(52, 'Geometric Flow III', 'เกมปริศนาที่ต้องใช้ตรรกะและรูปแบบนามธรรม', 'Puzzle', 'https://placehold.co/400x250/5B21B6/ffffff?text=Abstract+Puzzle', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(53, 'Deep Blue III', 'เกมดำน้ำสำรวจโลกใต้ทะเลที่กว้างใหญ่', 'Exploration', 'https://placehold.co/400x250/00BCD4/ffffff?text=Ocean+Exploration', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(54, 'Wilderness Heart III', 'เกมเอาชีวิตรอดและสร้างที่พักอาศัยในป่า', 'Survival', 'https://placehold.co/400x250/F97316/ffffff?text=Survival+Crafting', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(55, 'Harvest Valley III', 'เกมทำฟาร์มที่ผ่อนคลายและสร้างชุมชนของคุณเอง', 'Simulation', 'https://placehold.co/400x250/34D399/ffffff?text=Farming+Sim', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(56, 'Shadow Falls III', 'เกมแนวสืบสวนสอบสวนแบบ Noir สุดคลาสสิก', 'Visual Novel', 'https://placehold.co/400x250/9CA3AF/ffffff?text=Noir+Mystery', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(57, 'Northern Lords III', 'เกมกลยุทธ์สร้างอาณาจักรไวกิ้งและพิชิตดินแดน', 'Strategy', 'https://placehold.co/400x250/D97706/ffffff?text=Viking+Raid', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(58, 'Starbound Echoes III', 'เกม RPG แฟนตาซีสไตล์ญี่ปุ่นที่เน้นเรื่องราว', 'JRPG', 'https://placehold.co/400x250/7C3AED/ffffff?text=Fantasy+JRPG', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(59, 'Gravity Jump III', 'เกมแพลตฟอร์มที่ท้าทายด้วยกลไกการสลับแรงโน้มถ่วง', 'Platformer', 'https://placehold.co/400x250/DB2777/ffffff?text=Platform+Puzzle', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(60, 'Chronos Gate III', 'เกม Action RPG ที่เดินทางข้ามกาลเวลาเพื่อแก้ไขอดีต', 'Action RPG', 'https://placehold.co/400x250/60A5FA/ffffff?text=Time+Travel+RPG', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(61, 'Aether Gardens III', 'เกมสร้างสวนลอยฟ้าและจัดการทรัพยากร', 'Management Sim', 'https://placehold.co/400x250/FBBF24/ffffff?text=Sky+Garden+Sim', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(62, 'Rogue Planet III', 'เกมยิงมุมมองบุคคลที่หนึ่งแนวสำรวจดาวเคราะห์ที่ถูกทิ้งร้าง', 'FPS/Exploration', 'https://placehold.co/400x250/EF4444/ffffff?text=Sci-Fi+Shooter', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(63, 'Silent Cartographer III', 'เกมลึกลับที่ต้องสร้างแผนที่โลกที่ไม่มีใครเคยเห็นมาก่อน', 'Puzzle/Exploration', 'https://placehold.co/400x250/10B981/ffffff?text=Map+Making+Game', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(64, 'Neon Skyline II III', 'การผจญภัยในโลกไซเบอร์พังก์ที่เต็มไปด้วยแสงนีออน', 'Action RPG', 'https://placehold.co/400x250/334155/ffffff?text=Cyberpunk+City', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(65, 'Emerald Trails II III', 'เกมสำรวจป่าลึกลับและแก้ไขปริศนาโบราณ', 'Adventure', 'https://placehold.co/400x250/22C55E/ffffff?text=Mystic+Forest', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(66, 'Dune Drifters II III', 'เกมแข่งรถที่ใช้ฟิสิกส์สมจริงท่ามกลางทะเลทราย', 'Racing', 'https://placehold.co/400x250/EAB308/ffffff?text=Desert+Racer', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(67, 'Pixel Blasters II III', 'เกมยิงอวกาศสไตล์ย้อนยุค (Shmup) ที่เร้าใจ', 'Arcade', 'https://placehold.co/400x250/F43F5E/ffffff?text=Retro+Space', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(68, 'Geometric Flow II III', 'เกมปริศนาที่ต้องใช้ตรรกะและรูปแบบนามธรรม', 'Puzzle', 'https://placehold.co/400x250/5B21B6/ffffff?text=Abstract+Puzzle', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(69, 'Deep Blue II III', 'เกมดำน้ำสำรวจโลกใต้ทะเลที่กว้างใหญ่', 'Exploration', 'https://placehold.co/400x250/00BCD4/ffffff?text=Ocean+Exploration', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(70, 'Wilderness Heart II III', 'เกมเอาชีวิตรอดและสร้างที่พักอาศัยในป่า', 'Survival', 'https://placehold.co/400x250/F97316/ffffff?text=Survival+Crafting', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(71, 'Harvest Valley II III', 'เกมทำฟาร์มที่ผ่อนคลายและสร้างชุมชนของคุณเอง', 'Simulation', 'https://placehold.co/400x250/34D399/ffffff?text=Farming+Sim', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(72, 'Shadow Falls II III', 'เกมแนวสืบสวนสอบสวนแบบ Noir สุดคลาสสิก', 'Visual Novel', 'https://placehold.co/400x250/9CA3AF/ffffff?text=Noir+Mystery', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(73, 'Northern Lords II III', 'เกมกลยุทธ์สร้างอาณาจักรไวกิ้งและพิชิตดินแดน', 'Strategy', 'https://placehold.co/400x250/D97706/ffffff?text=Viking+Raid', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(74, 'Starbound Echoes II III', 'เกม RPG แฟนตาซีสไตล์ญี่ปุ่นที่เน้นเรื่องราว', 'JRPG', 'https://placehold.co/400x250/7C3AED/ffffff?text=Fantasy+JRPG', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(75, 'Gravity Jump II III', 'เกมแพลตฟอร์มที่ท้าทายด้วยกลไกการสลับแรงโน้มถ่วง', 'Platformer', 'https://placehold.co/400x250/DB2777/ffffff?text=Platform+Puzzle', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(76, 'Chronos Gate II III', 'เกม Action RPG ที่เดินทางข้ามกาลเวลาเพื่อแก้ไขอดีต', 'Action RPG', 'https://placehold.co/400x250/60A5FA/ffffff?text=Time+Travel+RPG', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(77, 'Aether Gardens II III', 'เกมสร้างสวนลอยฟ้าและจัดการทรัพยากร', 'Management Sim', 'https://placehold.co/400x250/FBBF24/ffffff?text=Sky+Garden+Sim', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(78, 'Rogue Planet II III', 'เกมยิงมุมมองบุคคลที่หนึ่งแนวสำรวจดาวเคราะห์ที่ถูกทิ้งร้าง', 'FPS/Exploration', 'https://placehold.co/400x250/EF4444/ffffff?text=Sci-Fi+Shooter', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(79, 'Silent Cartographer II III', 'เกมลึกลับที่ต้องสร้างแผนที่โลกที่ไม่มีใครเคยเห็นมาก่อน', 'Puzzle/Exploration', 'https://placehold.co/400x250/10B981/ffffff?text=Map+Making+Game', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(111, 'Neon Skyline IV', 'การผจญภัยในโลกไซเบอร์พังก์ที่เต็มไปด้วยแสงนีออน', 'Action RPG', 'https://placehold.co/400x250/334155/ffffff?text=Cyberpunk+City', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(112, 'Emerald Trails IV', 'เกมสำรวจป่าลึกลับและแก้ไขปริศนาโบราณ', 'Adventure', 'https://placehold.co/400x250/22C55E/ffffff?text=Mystic+Forest', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(113, 'Dune Drifters IV', 'เกมแข่งรถที่ใช้ฟิสิกส์สมจริงท่ามกลางทะเลทราย', 'Racing', 'https://placehold.co/400x250/EAB308/ffffff?text=Desert+Racer', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(114, 'Pixel Blasters IV', 'เกมยิงอวกาศสไตล์ย้อนยุค (Shmup) ที่เร้าใจ', 'Arcade', 'https://placehold.co/400x250/F43F5E/ffffff?text=Retro+Space', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(115, 'Geometric Flow IV', 'เกมปริศนาที่ต้องใช้ตรรกะและรูปแบบนามธรรม', 'Puzzle', 'https://placehold.co/400x250/5B21B6/ffffff?text=Abstract+Puzzle', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(116, 'Deep Blue IV', 'เกมดำน้ำสำรวจโลกใต้ทะเลที่กว้างใหญ่', 'Exploration', 'https://placehold.co/400x250/00BCD4/ffffff?text=Ocean+Exploration', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(117, 'Wilderness Heart IV', 'เกมเอาชีวิตรอดและสร้างที่พักอาศัยในป่า', 'Survival', 'https://placehold.co/400x250/F97316/ffffff?text=Survival+Crafting', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(118, 'Harvest Valley IV', 'เกมทำฟาร์มที่ผ่อนคลายและสร้างชุมชนของคุณเอง', 'Simulation', 'https://placehold.co/400x250/34D399/ffffff?text=Farming+Sim', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(119, 'Shadow Falls IV', 'เกมแนวสืบสวนสอบสวนแบบ Noir สุดคลาสสิก', 'Visual Novel', 'https://placehold.co/400x250/9CA3AF/ffffff?text=Noir+Mystery', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(120, 'Northern Lords IV', 'เกมกลยุทธ์สร้างอาณาจักรไวกิ้งและพิชิตดินแดน', 'Strategy', 'https://placehold.co/400x250/D97706/ffffff?text=Viking+Raid', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(121, 'Starbound Echoes IV', 'เกม RPG แฟนตาซีสไตล์ญี่ปุ่นที่เน้นเรื่องราว', 'JRPG', 'https://placehold.co/400x250/7C3AED/ffffff?text=Fantasy+JRPG', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(122, 'Gravity Jump IV', 'เกมแพลตฟอร์มที่ท้าทายด้วยกลไกการสลับแรงโน้มถ่วง', 'Platformer', 'https://placehold.co/400x250/DB2777/ffffff?text=Platform+Puzzle', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(123, 'Chronos Gate IV', 'เกม Action RPG ที่เดินทางข้ามกาลเวลาเพื่อแก้ไขอดีต', 'Action RPG', 'https://placehold.co/400x250/60A5FA/ffffff?text=Time+Travel+RPG', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(124, 'Aether Gardens IV', 'เกมสร้างสวนลอยฟ้าและจัดการทรัพยากร', 'Management Sim', 'https://placehold.co/400x250/FBBF24/ffffff?text=Sky+Garden+Sim', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(125, 'Rogue Planet IV', 'เกมยิงมุมมองบุคคลที่หนึ่งแนวสำรวจดาวเคราะห์ที่ถูกทิ้งร้าง', 'FPS/Exploration', 'https://placehold.co/400x250/EF4444/ffffff?text=Sci-Fi+Shooter', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(126, 'Silent Cartographer IV', 'เกมลึกลับที่ต้องสร้างแผนที่โลกที่ไม่มีใครเคยเห็นมาก่อน', 'Puzzle/Exploration', 'https://placehold.co/400x250/10B981/ffffff?text=Map+Making+Game', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(127, 'Neon Skyline II IV', 'การผจญภัยในโลกไซเบอร์พังก์ที่เต็มไปด้วยแสงนีออน', 'Action RPG', 'https://placehold.co/400x250/334155/ffffff?text=Cyberpunk+City', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(128, 'Emerald Trails II IV', 'เกมสำรวจป่าลึกลับและแก้ไขปริศนาโบราณ', 'Adventure', 'https://placehold.co/400x250/22C55E/ffffff?text=Mystic+Forest', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(129, 'Dune Drifters II IV', 'เกมแข่งรถที่ใช้ฟิสิกส์สมจริงท่ามกลางทะเลทราย', 'Racing', 'https://placehold.co/400x250/EAB308/ffffff?text=Desert+Racer', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(130, 'Pixel Blasters II IV', 'เกมยิงอวกาศสไตล์ย้อนยุค (Shmup) ที่เร้าใจ', 'Arcade', 'https://placehold.co/400x250/F43F5E/ffffff?text=Retro+Space', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(131, 'Geometric Flow II IV', 'เกมปริศนาที่ต้องใช้ตรรกะและรูปแบบนามธรรม', 'Puzzle', 'https://placehold.co/400x250/5B21B6/ffffff?text=Abstract+Puzzle', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(132, 'Deep Blue II IV', 'เกมดำน้ำสำรวจโลกใต้ทะเลที่กว้างใหญ่', 'Exploration', 'https://placehold.co/400x250/00BCD4/ffffff?text=Ocean+Exploration', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(133, 'Wilderness Heart II IV', 'เกมเอาชีวิตรอดและสร้างที่พักอาศัยในป่า', 'Survival', 'https://placehold.co/400x250/F97316/ffffff?text=Survival+Crafting', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(134, 'Harvest Valley II IV', 'เกมทำฟาร์มที่ผ่อนคลายและสร้างชุมชนของคุณเอง', 'Simulation', 'https://placehold.co/400x250/34D399/ffffff?text=Farming+Sim', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(135, 'Shadow Falls II IV', 'เกมแนวสืบสวนสอบสวนแบบ Noir สุดคลาสสิก', 'Visual Novel', 'https://placehold.co/400x250/9CA3AF/ffffff?text=Noir+Mystery', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(136, 'Northern Lords II IV', 'เกมกลยุทธ์สร้างอาณาจักรไวกิ้งและพิชิตดินแดน', 'Strategy', 'https://placehold.co/400x250/D97706/ffffff?text=Viking+Raid', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(137, 'Starbound Echoes II IV', 'เกม RPG แฟนตาซีสไตล์ญี่ปุ่นที่เน้นเรื่องราว', 'JRPG', 'https://placehold.co/400x250/7C3AED/ffffff?text=Fantasy+JRPG', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(138, 'Gravity Jump II IV', 'เกมแพลตฟอร์มที่ท้าทายด้วยกลไกการสลับแรงโน้มถ่วง', 'Platformer', 'https://placehold.co/400x250/DB2777/ffffff?text=Platform+Puzzle', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(139, 'Chronos Gate II IV', 'เกม Action RPG ที่เดินทางข้ามกาลเวลาเพื่อแก้ไขอดีต', 'Action RPG', 'https://placehold.co/400x250/60A5FA/ffffff?text=Time+Travel+RPG', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(140, 'Aether Gardens II IV', 'เกมสร้างสวนลอยฟ้าและจัดการทรัพยากร', 'Management Sim', 'https://placehold.co/400x250/FBBF24/ffffff?text=Sky+Garden+Sim', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(141, 'Rogue Planet II IV', 'เกมยิงมุมมองบุคคลที่หนึ่งแนวสำรวจดาวเคราะห์ที่ถูกทิ้งร้าง', 'FPS/Exploration', 'https://placehold.co/400x250/EF4444/ffffff?text=Sci-Fi+Shooter', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(142, 'Silent Cartographer II IV', 'เกมลึกลับที่ต้องสร้างแผนที่โลกที่ไม่มีใครเคยเห็นมาก่อน', 'Puzzle/Exploration', 'https://placehold.co/400x250/10B981/ffffff?text=Map+Making+Game', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(143, 'Neon Skyline III IV', 'การผจญภัยในโลกไซเบอร์พังก์ที่เต็มไปด้วยแสงนีออน', 'Action RPG', 'https://placehold.co/400x250/334155/ffffff?text=Cyberpunk+City', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(144, 'Emerald Trails III IV', 'เกมสำรวจป่าลึกลับและแก้ไขปริศนาโบราณ', 'Adventure', 'https://placehold.co/400x250/22C55E/ffffff?text=Mystic+Forest', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(145, 'Dune Drifters III IV', 'เกมแข่งรถที่ใช้ฟิสิกส์สมจริงท่ามกลางทะเลทราย', 'Racing', 'https://placehold.co/400x250/EAB308/ffffff?text=Desert+Racer', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(146, 'Pixel Blasters III IV', 'เกมยิงอวกาศสไตล์ย้อนยุค (Shmup) ที่เร้าใจ', 'Arcade', 'https://placehold.co/400x250/F43F5E/ffffff?text=Retro+Space', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(147, 'Geometric Flow III IV', 'เกมปริศนาที่ต้องใช้ตรรกะและรูปแบบนามธรรม', 'Puzzle', 'https://placehold.co/400x250/5B21B6/ffffff?text=Abstract+Puzzle', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(148, 'Deep Blue III IV', 'เกมดำน้ำสำรวจโลกใต้ทะเลที่กว้างใหญ่', 'Exploration', 'https://placehold.co/400x250/00BCD4/ffffff?text=Ocean+Exploration', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(149, 'Wilderness Heart III IV', 'เกมเอาชีวิตรอดและสร้างที่พักอาศัยในป่า', 'Survival', 'https://placehold.co/400x250/F97316/ffffff?text=Survival+Crafting', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(150, 'Harvest Valley III IV', 'เกมทำฟาร์มที่ผ่อนคลายและสร้างชุมชนของคุณเอง', 'Simulation', 'https://placehold.co/400x250/34D399/ffffff?text=Farming+Sim', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(151, 'Shadow Falls III IV', 'เกมแนวสืบสวนสอบสวนแบบ Noir สุดคลาสสิก', 'Visual Novel', 'https://placehold.co/400x250/9CA3AF/ffffff?text=Noir+Mystery', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(152, 'Northern Lords III IV', 'เกมกลยุทธ์สร้างอาณาจักรไวกิ้งและพิชิตดินแดน', 'Strategy', 'https://placehold.co/400x250/D97706/ffffff?text=Viking+Raid', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(153, 'Starbound Echoes III IV', 'เกม RPG แฟนตาซีสไตล์ญี่ปุ่นที่เน้นเรื่องราว', 'JRPG', 'https://placehold.co/400x250/7C3AED/ffffff?text=Fantasy+JRPG', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(154, 'Gravity Jump III IV', 'เกมแพลตฟอร์มที่ท้าทายด้วยกลไกการสลับแรงโน้มถ่วง', 'Platformer', 'https://placehold.co/400x250/DB2777/ffffff?text=Platform+Puzzle', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(155, 'Chronos Gate III IV', 'เกม Action RPG ที่เดินทางข้ามกาลเวลาเพื่อแก้ไขอดีต', 'Action RPG', 'https://placehold.co/400x250/60A5FA/ffffff?text=Time+Travel+RPG', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(156, 'Aether Gardens III IV', 'เกมสร้างสวนลอยฟ้าและจัดการทรัพยากร', 'Management Sim', 'https://placehold.co/400x250/FBBF24/ffffff?text=Sky+Garden+Sim', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(157, 'Rogue Planet III IV', 'เกมยิงมุมมองบุคคลที่หนึ่งแนวสำรวจดาวเคราะห์ที่ถูกทิ้งร้าง', 'FPS/Exploration', 'https://placehold.co/400x250/EF4444/ffffff?text=Sci-Fi+Shooter', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(158, 'Silent Cartographer III IV', 'เกมลึกลับที่ต้องสร้างแผนที่โลกที่ไม่มีใครเคยเห็นมาก่อน', 'Puzzle/Exploration', 'https://placehold.co/400x250/10B981/ffffff?text=Map+Making+Game', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(159, 'Neon Skyline II III IV', 'การผจญภัยในโลกไซเบอร์พังก์ที่เต็มไปด้วยแสงนีออน', 'Action RPG', 'https://placehold.co/400x250/334155/ffffff?text=Cyberpunk+City', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(160, 'Emerald Trails II III IV', 'เกมสำรวจป่าลึกลับและแก้ไขปริศนาโบราณ', 'Adventure', 'https://placehold.co/400x250/22C55E/ffffff?text=Mystic+Forest', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(161, 'Dune Drifters II III IV', 'เกมแข่งรถที่ใช้ฟิสิกส์สมจริงท่ามกลางทะเลทราย', 'Racing', 'https://placehold.co/400x250/EAB308/ffffff?text=Desert+Racer', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(162, 'Pixel Blasters II III IV', 'เกมยิงอวกาศสไตล์ย้อนยุค (Shmup) ที่เร้าใจ', 'Arcade', 'https://placehold.co/400x250/F43F5E/ffffff?text=Retro+Space', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(163, 'Geometric Flow II III IV', 'เกมปริศนาที่ต้องใช้ตรรกะและรูปแบบนามธรรม', 'Puzzle', 'https://placehold.co/400x250/5B21B6/ffffff?text=Abstract+Puzzle', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(164, 'Deep Blue II III IV', 'เกมดำน้ำสำรวจโลกใต้ทะเลที่กว้างใหญ่', 'Exploration', 'https://placehold.co/400x250/00BCD4/ffffff?text=Ocean+Exploration', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(165, 'Wilderness Heart II III IV', 'เกมเอาชีวิตรอดและสร้างที่พักอาศัยในป่า', 'Survival', 'https://placehold.co/400x250/F97316/ffffff?text=Survival+Crafting', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(166, 'Harvest Valley II III IV', 'เกมทำฟาร์มที่ผ่อนคลายและสร้างชุมชนของคุณเอง', 'Simulation', 'https://placehold.co/400x250/34D399/ffffff?text=Farming+Sim', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(167, 'Shadow Falls II III IV', 'เกมแนวสืบสวนสอบสวนแบบ Noir สุดคลาสสิก', 'Visual Novel', 'https://placehold.co/400x250/9CA3AF/ffffff?text=Noir+Mystery', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(168, 'Northern Lords II III IV', 'เกมกลยุทธ์สร้างอาณาจักรไวกิ้งและพิชิตดินแดน', 'Strategy', 'https://placehold.co/400x250/D97706/ffffff?text=Viking+Raid', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(169, 'Starbound Echoes II III IV', 'เกม RPG แฟนตาซีสไตล์ญี่ปุ่นที่เน้นเรื่องราว', 'JRPG', 'https://placehold.co/400x250/7C3AED/ffffff?text=Fantasy+JRPG', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(170, 'Gravity Jump II III IV', 'เกมแพลตฟอร์มที่ท้าทายด้วยกลไกการสลับแรงโน้มถ่วง', 'Platformer', 'https://placehold.co/400x250/DB2777/ffffff?text=Platform+Puzzle', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(171, 'Chronos Gate II III IV', 'เกม Action RPG ที่เดินทางข้ามกาลเวลาเพื่อแก้ไขอดีต', 'Action RPG', 'https://placehold.co/400x250/60A5FA/ffffff?text=Time+Travel+RPG', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(172, 'Aether Gardens II III IV', 'เกมสร้างสวนลอยฟ้าและจัดการทรัพยากร', 'Management Sim', 'https://placehold.co/400x250/FBBF24/ffffff?text=Sky+Garden+Sim', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(173, 'Rogue Planet II III IV', 'เกมยิงมุมมองบุคคลที่หนึ่งแนวสำรวจดาวเคราะห์ที่ถูกทิ้งร้าง', 'FPS/Exploration', 'https://placehold.co/400x250/EF4444/ffffff?text=Sci-Fi+Shooter', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0),
(174, 'Silent Cartographer II III IV', 'เกมลึกลับที่ต้องสร้างแผนที่โลกที่ไม่มีใครเคยเห็นมาก่อน', 'Puzzle/Exploration', 'https://placehold.co/400x250/10B981/ffffff?text=Map+Making+Game', NULL, NULL, '2025-12-14 18:46:20', NULL, 0.00, NULL, NULL, 0.0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `slip_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `username`, `fullname`, `email`, `address`, `phone`, `total_price`, `status`, `slip_image`, `created_at`) VALUES
(10, 'poyjai', 'poy', 'tae7165933za@gmail.com', '  ', '0987654321', 0.10, 'waiting_verify', 'slip_10_1769874717.jpg', '2026-01-31 15:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `game_title` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `game_id`, `game_title`, `price`) VALUES
(10, 10, 1, 'Minecraft', 0.10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=238;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
