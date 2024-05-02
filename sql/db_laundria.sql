-- MariaDB dump 10.19  Distrib 10.4.21-MariaDB, for osx10.10 (x86_64)
--
-- Host: localhost    Database: db_laundria_test
-- ------------------------------------------------------
-- Server version	10.4.21-MariaDB

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
-- Table structure for table `CompanyProfiles`
--

DROP TABLE IF EXISTS `CompanyProfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CompanyProfiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `addr` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` char(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CompanyProfiles`
--

LOCK TABLES `CompanyProfiles` WRITE;
/*!40000 ALTER TABLE `CompanyProfiles` DISABLE KEYS */;
INSERT INTO `CompanyProfiles` VALUES (1,'Barokah Laundry','Jl. MT. Haryono Gg.XXI No.23, RT.04/RW.06, Dinoyo, Kec. Lowokwaru','galuhrmdh@gmail.com','085732992240');
/*!40000 ALTER TABLE `CompanyProfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Customers`
--

DROP TABLE IF EXISTS `Customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `addr` text NOT NULL,
  `phone` char(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Customers`
--

LOCK TABLES `Customers` WRITE;
/*!40000 ALTER TABLE `Customers` DISABLE KEYS */;
INSERT INTO `Customers` VALUES (1,'Customer Unit Test','Jl. Mawar No. 8','085645781435'),(2,'Heri Wahyudi','Jl. Agus Salim No.32','085645781329'),(3,'Suyono','Jl. Agus Salim Gg. II No. 23','085645786123'),(12,'Ridwan','Jl. Wukir No. 11','085436781649'),(13,'Test Tambah','Jl. Kenanga No. 28','085634111765'),(14,'Efendi Malik','Jl. Bromo No. 21','085678123511'),(15,'Ayu Rahma','Jl. Bromo No.11','085724817115'),(16,'Dina Rizky','Jl. Semeru No. 5','085732811776'),(18,'Customer Unit Test 2','Jl. Mawar No. 8','085645781435');
/*!40000 ALTER TABLE `Customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Employees`
--

DROP TABLE IF EXISTS `Employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `addr` text NOT NULL,
  `phone` char(20) NOT NULL,
  `role` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Employees`
--

LOCK TABLES `Employees` WRITE;
/*!40000 ALTER TABLE `Employees` DISABLE KEYS */;
INSERT INTO `Employees` VALUES (1,'Administrator','admin','admin@example.com','$2b$10$R6JCgdc/3yHG/2DAAGxYruSQV4EbJopkiG8khdUzPVg3bPF3TJ0DW','Jl. Jakarta No. 12','081333320001',1),(2,'Galuh Ramadhan','karyawan','karyawan@getnada.com','$2y$10$VsP2IV6rdiTcFhmw1bh/KezFTELI9yaX/KYFV8jpv2KCjpCELgx2y','-','086546712987',2),(3,'test1','admin1','test@example.com','$2y$10$EHYQpDd..OFc1zaMr9pices0v80mLLA8u.6RimNTBwBrGQOiCHCUK','test1','321',2),(4,'testttttaaa','test2','test2@example.com','$2y$10$Ag2vwaoIRKyRB2QmwEDZPeejc8a2MJ4jgBzwcWYgsmWpXDJbv8vwG','-','085615111222',2),(7,'test1','admin12','test1@example.com','$2y$10$UXhLhy8pyPI69Xf8OREM7e7zS3N8lwClpxgRnogKUxpsbZHDCTbYO','test1','321',2);
/*!40000 ALTER TABLE `Employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LaundryPackages`
--

DROP TABLE IF EXISTS `LaundryPackages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LaundryPackages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(19,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LaundryPackages`
--

LOCK TABLES `LaundryPackages` WRITE;
/*!40000 ALTER TABLE `LaundryPackages` DISABLE KEYS */;
INSERT INTO `LaundryPackages` VALUES (2,'Cuci Kering',6500.0000),(3,'Cuci Setrika',10000.0000),(21,'laundry package unit test 2',1000.0000);
/*!40000 ALTER TABLE `LaundryPackages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PaymentTypes`
--

DROP TABLE IF EXISTS `PaymentTypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PaymentTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PaymentTypes`
--

LOCK TABLES `PaymentTypes` WRITE;
/*!40000 ALTER TABLE `PaymentTypes` DISABLE KEYS */;
INSERT INTO `PaymentTypes` VALUES (3,'TUNAI'),(4,'TRANSFER'),(5,'KARTU KREDIT'),(6,'PIUTANG');
/*!40000 ALTER TABLE `PaymentTypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Sales`
--

DROP TABLE IF EXISTS `Sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_number` char(15) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `phone` char(20) NOT NULL,
  `addr` text NOT NULL,
  `laundry_package_id` int(11) NOT NULL,
  `laundry_package_price` decimal(19,4) NOT NULL,
  `weight` decimal(19,4) NOT NULL,
  `pickup_date` date NOT NULL,
  `payment_type_id` int(11) NOT NULL,
  `payment_status` int(5) NOT NULL,
  `total` decimal(19,4) NOT NULL,
  `order_status` int(5) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Sales`
--

LOCK TABLES `Sales` WRITE;
/*!40000 ALTER TABLE `Sales` DISABLE KEYS */;
INSERT INTO `Sales` VALUES (4,'LDRYBBCZZ0YV',1,'085645765123','Jl. Kenanga No. 12',2,6000.0000,2.0000,'2022-03-28',3,2,12000.0000,5,'2022-03-26 20:38:39','2022-03-29 17:38:34'),(5,'LDRYBBC3I64U',1,'085543781321','Jl. Ijen No. 32',2,6000.0000,1.0000,'2022-03-31',3,1,6000.0000,3,'2022-03-29 17:36:28','2022-03-29 17:39:23'),(6,'LDRYBBDAWBGH',2,'085645716111','Jl. Danau Maninjau No. 12',2,6500.0000,2.0000,'2022-04-03',3,2,13000.0000,5,'2022-04-01 11:10:57','2022-04-08 09:55:42'),(7,'LDRYBBEP23OZ',3,'085645781534','Perum Griyashanta No. 12',3,10000.0000,2.0000,'2022-05-19',3,2,20000.0000,2,'2022-05-16 18:34:49','2022-05-16 20:57:44'),(8,'LDRYBBE4QX02',2,'085418172611','gang macan',3,10000.0000,2.0000,'2022-06-02',3,1,20000.0000,2,'2022-05-30 06:22:00','2024-01-12 17:46:12'),(9,'ORD431HGF',1,'085645781432','Jl. Melati No. 32',2,6500.0000,2.0000,'2022-03-23',3,1,13000.0000,1,'2023-04-18 16:20:46','2023-04-18 16:20:46'),(10,'LDRYBCFM2UPV',2,'085645781329','Jl. Agus Salim No.32',2,6500.0000,1.5000,'2023-06-16',3,1,9750.0000,1,'2023-06-13 19:30:21','2023-06-13 19:30:21'),(11,'LDRYBCKS5889',13,'085634111765','Jl. Kenanga No. 28',2,6500.0000,3.0000,'2023-11-22',3,2,19500.0000,2,'2023-11-19 19:50:06','2024-01-12 17:46:00'),(12,'LDRYBDAL5033',12,'085436781649','Jl. Wukir No. 11',3,10000.0000,1.5000,'2024-01-15',3,1,15000.0000,1,'2024-01-12 17:46:43','2024-01-12 17:46:43'),(13,'LDRYBDAQ8041',19,'12312414','edit',3,10000.0000,3.0000,'2024-01-20',3,2,30000.0000,1,'2024-01-17 12:54:33','2024-01-17 12:54:33'),(14,'LDRYBDAQ0649',18,'085634111769','Jl. Kenanga No. 28',3,10000.0000,2.5000,'2024-01-20',3,2,25000.0000,1,'2024-01-17 12:58:12','2024-01-17 12:58:12'),(15,'LDRYBDAQ4983',12,'085436781649','Jl. Wukir No. 11',3,10000.0000,1.0000,'2024-01-20',3,2,10000.0000,1,'2024-01-17 14:38:57','2024-01-17 14:38:57'),(16,'ORD431HGF',1,'085645781432','Jl. Melati No. 32',2,6500.0000,2.0000,'2022-03-23',3,1,13000.0000,1,'2024-03-27 16:55:49','2024-03-27 16:55:49'),(17,'LDRYBDC23506',2,'085645781329','Jl. Agus Salim No.32',2,6500.0000,2.0000,'0000-00-00',3,2,13000.0000,1,'2024-03-28 08:53:26','2024-03-28 08:53:26'),(18,'LDRYBDC24507',1,'085645781435','Jl. Mawar No. 8',2,6500.0000,3.0000,'2024-03-31',3,2,19500.0000,1,'2024-03-28 08:56:51','2024-03-28 08:56:51'),(19,'LDRYBDDV8556',2,'085645781329','Jl. Agus Salim No.32',3,10000.0000,2.0000,'2024-04-25',3,1,20000.0000,1,'2024-04-22 12:08:25','2024-04-22 12:08:25');
/*!40000 ALTER TABLE `Sales` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-02 14:28:52
