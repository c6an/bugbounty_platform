-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: hackingcamp
-- ------------------------------------------------------
-- Server version       8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `free_board`
--

DROP TABLE IF EXISTS `free_board`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `free_board` (
  `board_id` int NOT NULL AUTO_INCREMENT,
  `board_title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `board_content` text COLLATE utf8mb4_general_ci NOT NULL,
  `board_date` date NOT NULL,
  `board_views` int DEFAULT NULL,
  `board_locked` int DEFAULT NULL,
  `secret_pw` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`board_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `free_board`
--

LOCK TABLES `free_board` WRITE;
/*!40000 ALTER TABLE `free_board` DISABLE KEYS */;
INSERT INTO `free_board` VALUES (1,'공지사항','c6an','<p>취약점을 제보하고 HVE 획득하자.</p>\r\n\r\n<p>방법</p>\r\n\r\n<p>1.&nbsp;Devtools을 이용해 취약한 코드 확인한다.<br />\r\n2. 취약한 UI를 확인한다.</p>\r\n\r\n※테스트 계정 사용을 권장한다.\r\n','2025-08-23',33,0,NULL),(2,'TEST','c6an','<p>비밀글을 확인했다<br />\r\n<br />\r\n<미션><br />\r\n본인 계정이 아닌 다른 계정의 글에 대한 수정/삭제 권한을 획득해라.\r\n','2025-08-23',23,1,'$2y$10$SQNTjlSniU9dvEBGfz0QJ.nisKvsQ/rQNRpTvs2FihaPIXDWv9CCK'),(5,'asdasdasdsad','asdasda','<p>ㄴㅇㅁㄴㅇㅁㄴㅇㅁㄴㅇㄴㅁㅇ</p>\r\n','2025-08-26',0,1,'$2y$10$bruvJOejMC7b2N6kq3pk3.3lNf7GcGn.J90eqlA6svFXNNHATQxuK'),(12,'testtest','asdasdasas','<p><img alt=\"\" src=\"/uploads/asdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasd.jpg\" style=\"height:512px; width:512px\" /></p>\r\n','2025-08-27',1,0,NULL);
/*!40000 ALTER TABLE `free_board` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report`
--

DROP TABLE IF EXISTS `report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `report` (
  `report_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `user_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `report_title` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `endpoint` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `url` varchar(1024) COLLATE utf8mb4_general_ci NOT NULL,
  `vuln_type` enum('xss','sqlli','openredirection','IDOR','CSRF','File Upload') COLLATE utf8mb4_general_ci NOT NULL,
  `poc` mediumtext COLLATE utf8mb4_general_ci NOT NULL,
  `report_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report`
--

LOCK TABLES `report` WRITE;
/*!40000 ALTER TABLE `report` DISABLE KEYS */;
/*!40000 ALTER TABLE `report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `user_id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `user_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `user_pw` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_no` int NOT NULL AUTO_INCREMENT,
  `role` enum('user','admin','maker') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`user_no`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('c6an','admin','$2y$10$Cm9r4EMSnywV7HC0MHiiOuRfw5Z7H8xkuUjSNfnQY3d0MmY15bL66','2025-08-23 21:12:10',2,'user'),('ba','ba','$2y$10$8MlCdWU4qu00PGKwpcE9C.EoOp0Lo/bCxkdcT5AUpdWGT3En.d6oG','2025-08-24 04:07:30',3,'user'),('admin','admin','$2y$10$O6pz67PMuNUEledm6yDB2ubEtBiDD/IKtiYX9yv0wCxu1so44BAbG','2025-08-24 05:04:44',4,'user'),('asdasda','asdasda','$2y$10$VGK8AHghQWhY0b2M8vefhO0hLtws4gFipTTOgw.mFdpsXod1LIbjq','2025-08-26 14:43:24',7,'user'),('asd','asd','$2y$10$/1RGpnnXDvi5OPTOlvAnveP9VbPS1NISo4ViAAup.ky7VRXA00LlW','2025-08-26 16:29:29',9,'user'),('aaasdasda','aaasdasda','$2y$10$r2P.Ej6/aSPmTgmJJOrqRupzCgVKj9VytIISGkKuYkW6w/ZtOjoce','2025-08-27 20:54:03',11,'user'),('asdasdasas','asdasdasas','$2y$10$V.jpgCqeZHQ34jAdXComIOnpu/o3K0r9miJ3XtCiZPVQXT/syNjGu','2025-08-27 20:55:26',12,'user');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;