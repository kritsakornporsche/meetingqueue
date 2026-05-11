-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: meetingqueue_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `participants_count` int(10) unsigned DEFAULT 0,
  `phone` varchar(20) DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `department_name` varchar(100) DEFAULT NULL,
  `is_external` tinyint(1) DEFAULT 0,
  `external_org` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected','cancelled','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_start_end` (`start_time`,`end_time`),
  KEY `idx_status` (`status`),
  KEY `idx_is_external` (`is_external`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,2,5,'123','เธญเธธเธเธเธฃเธ“เนเธ—เธตเนเธ•เนเธญเธเธเธฒเธฃ: เนเธเธฃเน€เธเธเน€เธ•เธญเธฃเน, เธ—เธตเธงเธต, เธเธญเธกเธเธดเธงเน€เธ•เธญเธฃเน','2026-05-06 10:00:00','2026-05-06 15:00:00',10,'000000000',NULL,'IT Department',0,NULL,'pending','2026-05-06 06:32:08',NULL),(2,1,5,'111','เธญเธธเธเธเธฃเธ“เนเธ—เธตเนเธ•เนเธญเธเธเธฒเธฃ: เธ—เธตเธงเธต','2026-05-06 09:00:00','2026-05-06 14:00:00',10,'0111111111',NULL,'IT Department',0,NULL,'pending','2026-05-06 06:36:38',NULL),(3,3,5,'222','เธญเธธเธเธเธฃเธ“เนเธ—เธตเนเธ•เนเธญเธเธเธฒเธฃ: เนเธเธฃเน€เธเธเน€เธ•เธญเธฃเน, เธเธญเธกเธเธดเธงเน€เธ•เธญเธฃเน','2026-05-06 10:30:00','2026-05-06 13:30:00',10,'0000000000',NULL,'IT Department',0,NULL,'approved','2026-05-06 06:37:11',NULL),(4,4,5,'333','เธญเธธเธเธเธฃเธ“เนเธ—เธตเนเธ•เนเธญเธเธเธฒเธฃ: เนเธเธฃเน€เธเธเน€เธ•เธญเธฃเน, เธ—เธตเธงเธต, เธเธญเธกเธเธดเธงเน€เธ•เธญเธฃเน, เนเธกเนเธเธฃเนเธเธ','2026-05-06 11:30:00','2026-05-06 13:30:00',10,'0333333333',NULL,'IT Department',0,NULL,'pending','2026-05-06 06:37:58',NULL),(5,5,5,'444','เธญเธธเธเธเธฃเธ“เนเธ—เธตเนเธ•เนเธญเธเธเธฒเธฃ: เนเธเธฃเน€เธเธเน€เธ•เธญเธฃเน, เธ—เธตเธงเธต, เธเธญเธกเธเธดเธงเน€เธ•เธญเธฃเน','2026-05-06 12:00:00','2026-05-06 16:00:00',10,'0222222222',NULL,'IT Department',0,NULL,'approved','2026-05-06 06:39:31',NULL),(6,1,5,'เธเธเธดเธกเธฒเธเธฃเนเธชเธเธเธฑเธเธ—เธฃเนเนเธเธ•เนเธฒเธเธฒเธ','เธญเธธเธเธเธฃเธ“เนเธ—เธตเนเธ•เนเธญเธเธเธฒเธฃ: เนเธเธฃเน€เธเธเน€เธ•เธญเธฃเน, เธ—เธตเธงเธต, เธเธญเธกเธเธดเธงเน€เธ•เธญเธฃเน, เนเธกเนเธเธฃเนเธเธ, เธญเธทเนเธเน: เน€เธเธฃเธทเนเธญเธเธเธดเธกเธเน','2026-05-08 08:30:00','2026-05-08 16:30:00',10,'1234567890',NULL,'IT Department',0,NULL,'pending','2026-05-08 07:06:41',NULL),(7,6,5,'159','เน€เธ•เธฃเธตเธขเธกเธกเธทเนเธญเน€เธ—เธตเนเธขเธเนเธซเนเธเธญเธ”เธตเธเธ\n\nเธญเธธเธเธเธฃเธ“เนเธ—เธตเนเธ•เนเธญเธเธเธฒเธฃ: เนเธกเนเธเธฃเนเธเธ, เธญเธทเนเธเน: smartphone','2026-05-13 08:30:00','2026-05-13 12:00:00',10,'000000000',NULL,'IT Department',0,NULL,'approved','2026-05-11 01:43:44',NULL),(8,4,5,'586','เน€เธ•เธฃเธตเธขเธกเธญเธฒเธซเธฒเธฃเนเธซเนเธเธฃเธเธ—เธธเธเธเธ\n\nเธญเธธเธเธเธฃเธ“เนเธ—เธตเนเธ•เนเธญเธเธเธฒเธฃ: เนเธเธฃเน€เธเธเน€เธ•เธญเธฃเน, เธ—เธตเธงเธต, เธเธญเธกเธเธดเธงเน€เธ•เธญเธฃเน, เนเธกเนเธเธฃเนเธเธ, เธญเธทเนเธเน: เน€เธเธฃเธทเนเธญเธเธเธฃเธดเนเธ','2026-05-11 08:30:00','2026-05-11 12:00:00',40,'0111111111',NULL,'IT Department',0,NULL,'approved','2026-05-11 05:57:43',NULL),(9,1,5,'888','เธญเธธเธเธเธฃเธ“เนเธ—เธตเนเธ•เนเธญเธเธเธฒเธฃ: เธ—เธตเธงเธต, เนเธเธฃเน€เธเธเน€เธ•เธญเธฃเน, เธเธญเธกเธเธดเธงเน€เธ•เธญเธฃเน, เนเธกเนเธเธฃเนเธเธ','2026-05-15 08:30:00','2026-05-15 16:30:00',25,'0333333333',NULL,'IT Department',0,NULL,'pending','2026-05-11 06:29:33',NULL);
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `capacity` int(10) unsigned NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `status` enum('available','maintenance') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (1,'เธซเนเธญเธเธเธฃเธฐเธเธธเธกเน€เธญเธทเนเธญเธเธเธถเนเธ (30-40 เธเธ)',40,'',NULL,NULL,NULL,'available','2026-04-29 12:38:43'),(2,'เธซเนเธญเธเธเธฃเธฐเธเธธเธกเธ•เนเธเธเนเธฒเธง(เธจเธนเธเธขเนเน€เธชเธฃเธดเธกเธชเธธเธเนเธ—เธข 20-30 เธเธ)',30,'เธจเธนเธเธขเนเน€เธชเธฃเธดเธกเธชเธธเธเนเธ—เธข',NULL,NULL,'[\"assets\\/images\\/rooms\\/room_2_1778038844_69fab83c8bd1c.png\"]','available','2026-04-29 12:38:43'),(3,'เธซเนเธญเธเธเธฃเธฐเธเธธเธกเน€เธญเธทเนเธญเธเธซเธฅเธงเธ (10-15เธเธ)',15,'',NULL,NULL,NULL,'available','2026-04-29 12:38:43'),(4,'เธซเนเธญเธเธเธฃเธฐเธเธธเธกเน€เธญเธทเนเธญเธเธชเธฒเธกเธเธญเธข(เธเนเธฒเธเธฃเนเธฒเธเธเนเธฒเธชเธงเธฑเธชเธ”เธดเธเธฒเธฃ 40-50 เธเธ)',50,'เธเนเธฒเธเธฃเนเธฒเธเธเนเธฒเธชเธงเธฑเธชเธ”เธดเธเธฒเธฃ',NULL,NULL,NULL,'available','2026-04-29 12:38:43'),(5,'เธซเนเธญเธเธเธฃเธฐเธเธธเธกเน€เธญเธทเนเธญเธเธเธณ(เธญเธเธเนเธเธฃเนเธเธ—เธขเน) ( 10-15 เธเธ)',15,'เธญเธเธเนเธเธฃเนเธเธ—เธขเน',NULL,NULL,NULL,'available','2026-04-29 12:38:43'),(6,'เธซเนเธญเธเธเธฃเธฐเธเธธเธกเน€เธญเธทเนเธญเธเน€เธเธดเธ(10-15เธเธ)',15,'',NULL,NULL,NULL,'available','2026-04-29 12:38:43');
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `emp_code` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `position_name` varchar(100) DEFAULT NULL,
  `dept_name` varchar(100) DEFAULT NULL,
  `photo` longtext DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `emp_code` (`emp_code`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'1001','Somchai','1234567890123','Somchai','1234567890123','เธเธฒเธขเนเธเธ—เธขเนเธเธณเธเธฒเธเธเธฒเธฃ','เธญเธเธเนเธเธฃเนเธเธ—เธขเน',NULL,'user','2026-04-29 12:42:53','2026-04-29 12:42:53'),(2,'1002','Somsak','1111111111111','Somsak','1111111111111','เธเธขเธฒเธเธฒเธฅเธงเธดเธเธฒเธเธตเธเธเธณเธเธฒเธเธเธฒเธฃ','เธเนเธฒเธขเธเธฒเธฃเธเธขเธฒเธเธฒเธฅ',NULL,'user','2026-04-29 12:42:53','2026-04-29 12:42:53'),(3,'1003','Kittipong','2222222222222','Kittipong','2222222222222','เธเธฑเธเธเธฑเธ”เธเธฒเธฃเธเธฒเธเธ—เธฑเนเธงเนเธ','เธเธฃเธดเธซเธฒเธฃเธเธฒเธเธ—เธฑเนเธงเนเธ',NULL,'user','2026-04-29 12:42:53','2026-04-29 12:42:53'),(4,'1004','Nattaya','3333333333333','Nattaya','3333333333333','เน€เธ เธชเธฑเธเธเธฃเธเธณเธเธฒเธเธเธฒเธฃ','เธเธฅเธธเนเธกเธเธฒเธเน€เธ เธชเธฑเธเธเธฃเธฃเธก',NULL,'user','2026-04-29 12:42:53','2026-04-29 12:42:53'),(5,'0000','admin','admin','System','Administrator','IT Admin','IT Department',NULL,'admin','2026-04-29 12:42:53','2026-04-29 12:42:53');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-11 16:07:12
