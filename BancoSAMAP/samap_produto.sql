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
-- Table structure for table `produto`
--

DROP TABLE IF EXISTS `produto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produto` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `CODIGO` int DEFAULT NULL,
  `NOME` varchar(100) DEFAULT NULL,
  `PRECO` decimal(10,2) DEFAULT NULL,
  `IMG` blob,
  `IMG_CAMINHO` varchar(4000) DEFAULT NULL,
  `VERIFICA_OFERTA` varchar(1) DEFAULT NULL,
  `PRECO_OFERTA` decimal(10,2) DEFAULT NULL,
  `DESCRICAO` varchar(1000) DEFAULT NULL,
  `ID_LOJA` int DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produto`
--

LOCK TABLES `produto` WRITE;
/*!40000 ALTER TABLE `produto` DISABLE KEYS */;
INSERT INTO `produto` VALUES (1,1,'Notebook',4000.00,NULL,'imagens/produtos/notebook.png','1',3500.00,'Este é um notebook muito bom para trabalho e jogos casuais!',2),(2,2,'Pelúcia do Kenny',150.00,NULL,'imagens/produtos/pelucia.png',NULL,NULL,'Essa é uma pelúcia do Kenny!',2),(3,3,'Iphone 13',4000.00,NULL,'imagens/produtos/iphone.png','1',3500.00,'O iPhone 13 oferece desempenho excepcional, câmeras avançadas e uma tela Super Retina XDR, proporcionando uma experiência fluida e imersiva.',2),(4,4,'Fiat Uno 4 Portas',15000.00,NULL,'imagens/produtos/produto-uno.png','1',7500.00,'O Fiat Uno 4 Portas é o carro ideal para quem busca praticidade e economia no dia a dia, e ainda ativa o modo turbo de forma surpreendente ao ser colocado uma escada em cima dele, proporcionando um desempenho ainda mais impressionante.',2),(5,5,'Red Dead Redemption 2',249.00,NULL,'imagens/produtos/jogo.png',NULL,NULL,'Red Dead Redemption 2 é um épico de ação e aventura em um mundo aberto, onde você vive a vida de um fora da lei no final do século XIX, enfrentando dilemas morais e desafiando o implacável avanço da civilização.',2),(6,6,'Whey Protein',149.00,NULL,'imagens/produtos/whey.png','1',97.00,'O Whey Protein é um suplemento de alta qualidade, rico em proteínas de rápida absorção, ideal para promover o ganho muscular, recuperação pós-treino e auxiliar na manutenção de uma dieta equilibrada.',2),(7,7,'Microfone',234.00,NULL,'imagens/produtos/microfone.png','1',209.00,'O microfone de mesa é ideal para gravações de áudio claras e precisas, oferecendo captação de som de alta qualidade com design compacto e fácil de posicionar, perfeito para podcasts, transmissões e videoconferências.',2),(8,8,'Mochila McQueen',199.00,NULL,'imagens/produtos/mochila.png',NULL,NULL,'A mochila McQueen é perfeita para os fãs do personagem, combinando design moderno e resistente com imagens vibrantes do famoso carro de corrida, ideal para crianças que buscam estilo e praticidade no dia a dia.',2),(9,9,'Placa de Vídeo',3499.00,NULL,'imagens/produtos/placavideo.png','1',2999.00,'A placa de vídeo oferece desempenho gráfico superior, ideal para jogos, edição de vídeos e tarefas que exigem alta performance, garantindo imagens nítidas, fluidas e uma experiência visual imersiva.',4),(10,10,'Mouse Razer',349.00,NULL,'imagens/produtos/mouse.png','1',189.00,'O mouse Razer oferece precisão extrema e desempenho de alto nível, com design ergonômico e iluminação personalizável, proporcionando uma experiência de jogo imersiva e responsiva.',4),(11,11,'Teclado Attack Shark',150.00,NULL,'imagens/produtos/teclado.png',NULL,NULL,' O teclado Attack Shark combina design robusto com teclas responsivas, oferecendo desempenho rápido e preciso, ideal para gamers que buscam conforto, durabilidade e uma experiência de jogo de alta performance.',4),(12,12,'Tênis Nike',250.00,NULL,'imagens/produtos/tenis.png','1',198.00,'O tênis Nike combina design moderno, conforto e tecnologia avançada, proporcionando desempenho excepcional tanto para atividades esportivas quanto para o uso diário.',2),(13,13,'Aspirador de Pó',350.00,NULL,'imagens/produtos/aspirador.png',NULL,NULL,'O aspirador de pó oferece potente sucção e praticidade, facilitando a limpeza de diversos ambientes com eficiência e design compacto.',2),(14,14,'Bola de Basquete',95.00,NULL,'imagens/produtos/bola.png',NULL,NULL,' A bola de basquete oferece excelente aderência e controle, ideal para partidas de alto desempenho e treinos intensos.',2),(15,15,'Cadeira Ergonômica',750.00,NULL,'imagens/produtos/cadeira.png','1',650.00,' A cadeira ergonômica proporciona conforto e suporte adequado para longos períodos de trabalho, ajudando a manter a postura correta e reduzir o cansaço.',2),(16,100,'Geladeira',3000.00,NULL,'imagens/produtos/geladeira.png','1',2500.00,'Essa é uma ótima geladeira',2);
/*!40000 ALTER TABLE `produto` ENABLE KEYS */;
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
