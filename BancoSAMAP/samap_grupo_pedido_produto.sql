-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: localhost    Database: samap
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `grupo_pedido_produto`
--

DROP TABLE IF EXISTS `grupo_pedido_produto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupo_pedido_produto` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `ID_PEDIDO` int DEFAULT NULL,
  `ID_PRODUTO` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID_PEDIDO` (`ID_PEDIDO`),
  KEY `ID_PRODUTO` (`ID_PRODUTO`),
  CONSTRAINT `grupo_pedido_produto_ibfk_1` FOREIGN KEY (`ID_PEDIDO`) REFERENCES `pedido` (`ID`),
  CONSTRAINT `grupo_pedido_produto_ibfk_2` FOREIGN KEY (`ID_PRODUTO`) REFERENCES `produto` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_pedido_produto`
--

LOCK TABLES `grupo_pedido_produto` WRITE;
/*!40000 ALTER TABLE `grupo_pedido_produto` DISABLE KEYS */;
INSERT INTO `grupo_pedido_produto` VALUES (1,2,1),(2,2,1),(3,3,1),(4,3,1),(5,4,1),(6,4,1),(7,5,1),(8,5,1),(9,6,1),(10,6,1),(11,7,1),(12,7,1),(13,8,1),(14,8,1),(15,9,1),(16,9,1),(17,10,1),(18,10,1),(19,11,1),(20,11,1),(21,12,1),(22,12,1),(23,13,1),(24,13,1),(25,14,1),(26,14,1),(27,15,1),(28,15,1),(29,16,1),(30,16,1),(31,16,6),(32,16,6),(33,16,10),(34,16,9),(35,16,9),(36,17,10),(37,17,4),(38,17,4),(39,18,10),(40,18,4),(41,18,4),(42,18,3),(43,18,11),(44,19,10),(45,19,4),(46,19,4),(47,19,3),(48,19,11),(49,19,6),(50,20,10),(51,20,5),(52,20,3),(53,20,6),(54,21,3),(55,21,6),(56,21,4),(57,21,10),(58,22,3),(59,22,10),(60,22,6);
/*!40000 ALTER TABLE `grupo_pedido_produto` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-08 20:56:24
