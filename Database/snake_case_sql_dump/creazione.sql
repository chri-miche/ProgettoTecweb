-- MySQL dump 10.13  Distrib 5.5.62, for Win64 (AMD64)
--
-- Host: localhost    Database: webbirddb
-- ------------------------------------------------------
-- Server version 5.5.5-10.4.17-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `approvazione`
--

DROP TABLE IF EXISTS `approvazione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `approvazione` (
  `utente_id` int(10) unsigned NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `likes` tinyint(1) NOT NULL,
  PRIMARY KEY (`utente_id`,`content_id`),
  KEY `content_id` (`content_id`),
  CONSTRAINT `approvazione_ibfk_1` FOREIGN KEY (`utente_id`) REFERENCES `utente` (`id`) ON DELETE CASCADE,
  CONSTRAINT `approvazione_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `contenuto` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `citazione`
--

DROP TABLE IF EXISTS `citazione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `citazione` (
  `tag_id` int(10) unsigned NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`tag_id`,`post_id`),
  KEY `post_id` (`post_id`),
  CONSTRAINT `citazione_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE,
  CONSTRAINT `citazione_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`content_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `commento`
--

DROP TABLE IF EXISTS `commento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commento` (
  `content_id` int(10) unsigned NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`content_id`,`post_id`),
  KEY `post_id` (`post_id`),
  CONSTRAINT `commento_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `contenuto` (`id`) ON DELETE CASCADE,
  CONSTRAINT `commento_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`content_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `conservazione`
--

DROP TABLE IF EXISTS `conservazione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conservazione` (
  `codice` varchar(2) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `prob_estinzione` int(11) DEFAULT NULL,
  `descrizione` text DEFAULT NULL,
  PRIMARY KEY (`codice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contenuto`
--

DROP TABLE IF EXISTS `contenuto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contenuto` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 1,
  `is_archived` tinyint(1) NOT NULL,
  `content` text NOT NULL,
  `data` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `contenuto_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utente` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `famiglia`
--

DROP TABLE IF EXISTS `famiglia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `famiglia` (
  `tag_id` int(10) unsigned NOT NULL,
  `ord_id` int(10) unsigned NOT NULL,
  `nome_scientifico` varchar(40) NOT NULL,
  PRIMARY KEY (`tag_id`),
  KEY `ord_id` (`ord_id`),
  CONSTRAINT `famiglia_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE,
  CONSTRAINT `famiglia_ibfk_2` FOREIGN KEY (`ord_id`) REFERENCES `ordine` (`tag_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `genere`
--

DROP TABLE IF EXISTS `genere`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `genere` (
  `tag_id` int(10) unsigned NOT NULL,
  `fam_id` int(10) unsigned NOT NULL,
  `nome_scientifico` varchar(40) NOT NULL,
  PRIMARY KEY (`tag_id`),
  KEY `fam_id` (`fam_id`),
  CONSTRAINT `genere_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE,
  CONSTRAINT `genere_ibfk_2` FOREIGN KEY (`fam_id`) REFERENCES `famiglia` (`tag_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `immaginipost`
--

DROP TABLE IF EXISTS `immaginipost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `immaginipost` (
  `post_id` int(10) unsigned NOT NULL,
  `percorso_immagine` varchar(200) NOT NULL,
  PRIMARY KEY (`post_id`,`percorso_immagine`),
  CONSTRAINT `immaginipost_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`content_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ordine`
--

DROP TABLE IF EXISTS `ordine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ordine` (
  `tag_id` int(10) unsigned NOT NULL,
  `nome_scientifico` varchar(40) NOT NULL,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `nome_scientifico` (`nome_scientifico`),
  CONSTRAINT `ordine_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `content_id` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  PRIMARY KEY (`content_id`),
  CONSTRAINT `post_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `contenuto` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `seguito`
--

DROP TABLE IF EXISTS `seguito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seguito` (
  `seguito_id` int(10) unsigned NOT NULL,
  `seguace_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`seguito_id`,`seguace_id`),
  KEY `seguace_id` (`seguace_id`),
  CONSTRAINT `seguito_ibfk_1` FOREIGN KEY (`seguace_id`) REFERENCES `utente` (`id`) ON DELETE CASCADE,
  CONSTRAINT `seguito_ibfk_2` FOREIGN KEY (`seguito_id`) REFERENCES `utente` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `specie`
--

DROP TABLE IF EXISTS `specie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specie` (
  `tag_id` int(10) unsigned NOT NULL,
  `gen_id` int(10) unsigned NOT NULL,
  `nome_scientifico` varchar(40) NOT NULL,
  `nome_comune` varchar(40) DEFAULT NULL,
  `percorso_immagine` varchar(80) NOT NULL,
  `conservazione_id` varchar(2) NOT NULL,
  `peso_medio` int(10) unsigned NOT NULL,
  `altezza_media` int(10) unsigned NOT NULL,
  `descrizione` text NOT NULL,
  PRIMARY KEY (`tag_id`),
  KEY `gen_id` (`gen_id`),
  KEY `conservazione_id` (`conservazione_id`),
  CONSTRAINT `specie_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE,
  CONSTRAINT `specie_ibfk_2` FOREIGN KEY (`gen_id`) REFERENCES `genere` (`tag_id`) ON DELETE CASCADE,
  CONSTRAINT `specie_ibfk_3` FOREIGN KEY (`conservazione_id`) REFERENCES `conservazione` (`codice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `utente`
--

DROP TABLE IF EXISTS `utente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utente` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(25) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(14) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `immagine_profilo` varchar(40) NOT NULL DEFAULT 'imgs/default.png',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

