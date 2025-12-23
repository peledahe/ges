-- MySQL dump 10.13  Distrib 8.0.44, for Linux (x86_64)
--
-- Host: localhost    Database: ges
-- ------------------------------------------------------
-- Server version	8.0.44-0ubuntu0.24.04.1

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
-- Table structure for table `areas`
--

DROP TABLE IF EXISTS `areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `areas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `areas_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `areas_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `areas`
--

LOCK TABLES `areas` WRITE;
/*!40000 ALTER TABLE `areas` DISABLE KEYS */;
INSERT INTO `areas` VALUES (1,1,'Recepción','Área de ingreso',0,'2025-12-22 21:11:10','2025-12-22 23:52:25'),(2,1,'Mecánica General','Trabajos de Mecànica en general',2,'2025-12-22 21:11:11','2025-12-22 23:52:25'),(3,2,'Recepción','Área de ingreso',0,'2025-12-22 21:11:11','2025-12-22 21:11:11'),(4,1,'Enderezado y Pintura','Enderezado y Pintura',1,'2025-12-22 21:11:10','2025-12-22 23:52:25'),(5,1,'Presupuesto',NULL,3,'2025-12-22 23:51:54','2025-12-22 23:52:25');
/*!40000 ALTER TABLE `areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('ges-cache-9ad275765e3703e2639379327bf6b3ae','i:1;',1766473117),('ges-cache-9ad275765e3703e2639379327bf6b3ae:timer','i:1766473117;',1766473117),('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab','i:1;',1766427805),('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1766427805;',1766427805),('laravel-cache-9ad275765e3703e2639379327bf6b3ae','i:1;',1766436227),('laravel-cache-9ad275765e3703e2639379327bf6b3ae:timer','i:1766436227;',1766436227),('laravel-cache-a3f2564a6d23ac4c2a00b443c4c6705f','i:1;',1766433050),('laravel-cache-a3f2564a6d23ac4c2a00b443c4c6705f:timer','i:1766433050;',1766433050);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checklist_templates`
--

DROP TABLE IF EXISTS `checklist_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `checklist_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `items` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checklist_templates_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `checklist_templates_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checklist_templates`
--

LOCK TABLES `checklist_templates` WRITE;
/*!40000 ALTER TABLE `checklist_templates` DISABLE KEYS */;
INSERT INTO `checklist_templates` VALUES (1,1,'Recepción Estándar','[{\"items\": [\"Emblema\", \"Luz de Placa\", \"Tapicería de Baúl\", \"Herramientas\", \"Llave de Chucho\", \"Tricket\", \"Llanta de Repuesto\"], \"section\": \"Parte Trasera/Baúl\"}, {\"items\": [\"Antena\", \"Emblema\", \"Spoiler Delantero\", \"Luces Delanteras\", \"Plumillas\"], \"section\": \"Parte Frontal\"}]','2025-12-22 21:11:11','2025-12-22 21:11:11');
/*!40000 ALTER TABLE `checklist_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"aa73394c-216f-428b-90b3-b3446d072ed9\",\"displayName\":\"App\\\\Notifications\\\\OrderReceived\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Vehicle\\\";s:2:\\\"id\\\";a:1:{i:0;i:5;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:31:\\\"App\\\\Notifications\\\\OrderReceived\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\WorkOrder\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"f0902858-6b80-4cff-961e-c6adfab3b26c\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1766426692,\"delay\":null}',0,NULL,1766426692,1766426692),(2,'default','{\"uuid\":\"0f0e468d-3c67-49c2-9160-94fef0453ecf\",\"displayName\":\"App\\\\Notifications\\\\WorkOrderUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Vehicle\\\";s:2:\\\"id\\\";a:1:{i:0;i:5;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\WorkOrderUpdated\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\WorkOrder\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:1:{i:0;s:7:\\\"vehicle\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"08084024-d746-43ff-a041-9144777b1a9f\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1766426812,\"delay\":null}',0,NULL,1766426812,1766426812),(3,'default','{\"uuid\":\"4689246c-7754-4a75-abc8-138243266cbe\",\"displayName\":\"App\\\\Notifications\\\\WorkOrderUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Vehicle\\\";s:2:\\\"id\\\";a:1:{i:0;i:5;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\WorkOrderUpdated\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\WorkOrder\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:1:{i:0;s:7:\\\"vehicle\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"94c4c384-0e9a-4fbf-ad7c-c75af888b537\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1766426834,\"delay\":null}',0,NULL,1766426834,1766426834),(4,'default','{\"uuid\":\"360ffe49-44c3-424d-8e51-7c6a21b26dae\",\"displayName\":\"App\\\\Notifications\\\\WorkOrderUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Vehicle\\\";s:2:\\\"id\\\";a:1:{i:0;i:5;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\WorkOrderUpdated\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\WorkOrder\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:1:{i:0;s:7:\\\"vehicle\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"2177567d-8c08-4946-8305-f09275446588\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1766426842,\"delay\":null}',0,NULL,1766426842,1766426842),(5,'default','{\"uuid\":\"c236c560-0cf4-42bb-aed8-0cb6b4168dde\",\"displayName\":\"App\\\\Notifications\\\\WorkOrderUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Vehicle\\\";s:2:\\\"id\\\";a:1:{i:0;i:5;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\WorkOrderUpdated\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\WorkOrder\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:1:{i:0;s:7:\\\"vehicle\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"2832b9c3-57a7-45c3-8391-93d66492f020\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1766426853,\"delay\":null}',0,NULL,1766426853,1766426853),(6,'default','{\"uuid\":\"084930c7-bc90-401f-987c-34ffdb92dd76\",\"displayName\":\"App\\\\Notifications\\\\WorkOrderUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Vehicle\\\";s:2:\\\"id\\\";a:1:{i:0;i:5;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\WorkOrderUpdated\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\WorkOrder\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:1:{i:0;s:7:\\\"vehicle\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"2c97a6f5-ec22-4ec7-a788-fdb270b2c390\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1766426862,\"delay\":null}',0,NULL,1766426862,1766426862),(7,'default','{\"uuid\":\"c9bee721-af35-4dc0-bd46-6008db98f7ca\",\"displayName\":\"App\\\\Notifications\\\\WorkOrderUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Vehicle\\\";s:2:\\\"id\\\";a:1:{i:0;i:5;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\WorkOrderUpdated\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\WorkOrder\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:1:{i:0;s:7:\\\"vehicle\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"4ecce4ce-87e6-4e0d-bfae-a4548a56f8ff\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1766426864,\"delay\":null}',0,NULL,1766426864,1766426864),(8,'default','{\"uuid\":\"a0135453-da14-4201-bb53-dc7c89fbbe7c\",\"displayName\":\"App\\\\Notifications\\\\WorkOrderUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Vehicle\\\";s:2:\\\"id\\\";a:1:{i:0;i:5;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\WorkOrderUpdated\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\WorkOrder\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:1:{i:0;s:7:\\\"vehicle\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"7fdb9ba0-632e-4e71-bd57-1f713771b255\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1766426863,\"delay\":null}',0,NULL,1766426863,1766426863),(9,'default','{\"uuid\":\"e0cc507d-abd3-414e-8e8c-e883b6fd2529\",\"displayName\":\"App\\\\Notifications\\\\WorkOrderUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Vehicle\\\";s:2:\\\"id\\\";a:1:{i:0;i:5;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\WorkOrderUpdated\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\WorkOrder\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:1:{i:0;s:7:\\\"vehicle\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"1d37b91b-0175-46d0-acbd-776b128c2462\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1766426865,\"delay\":null}',0,NULL,1766426865,1766426865);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_12_22_150056_add_two_factor_columns_to_users_table',1),(5,'2025_12_22_150124_create_personal_access_tokens_table',1),(6,'2025_12_22_150440_create_tenants_table',2),(7,'2025_12_22_150441_create_areas_table',2),(8,'2025_12_22_150442_create_vehicles_table',2),(9,'2025_12_22_150442_create_work_orders_table',2),(10,'2025_12_22_150443_create_checklist_templates_table',2),(11,'2025_12_22_150450_create_work_order_details_table',2),(12,'2025_12_22_150451_create_work_order_photos_table',2),(13,'2025_12_22_150453_create_work_order_checklists_table',2),(14,'2025_12_22_150454_create_work_order_damages_table',2),(15,'2025_12_22_150949_add_tenant_id_to_users_table',3),(16,'2025_12_22_170622_add_notes_to_work_orders_table',4),(17,'2025_12_22_173311_add_logo_path_to_tenants_table',5),(18,'2025_12_22_174804_add_order_to_areas_table',6),(20,'2025_12_22_181127_add_roles_fields_to_users_table',7),(21,'2025_12_22_182153_update_work_order_statuses_enum',7);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('DqdR4MS8zwZxM4zOU8ECsKja9Z2b1TO84lZdeLCr',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiSUVWSGx4NG5TMWdjdFdaTnlQUWs1V0R1TlpnOHRORU5Ic21jcll6SCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoyMToicGFzc3dvcmRfaGFzaF9zYW5jdHVtIjtzOjYwOiIkMnkkMTIkUGNVYmZQekVJaE9kU3V2NVJaRVFRZVljYy9CNmVDTU56NzhWRkI4NUVBMGhWczFrU1o1eFciO3M6OToidGVuYW50X2lkIjtpOjE7fQ==',1766473315),('ZfPIitDOSzh4ANbM8sv8tb87ymOqho1W5brcOI9T',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoienp6dFdwNkVqcTEzVXVNaW95QkhBblNvejZla3BtS09EQk51cjVXSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766469330);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tenants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `logo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `users_limit` int NOT NULL DEFAULT '5',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenants_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenants`
--

LOCK TABLES `tenants` WRITE;
/*!40000 ALTER TABLE `tenants` DISABLE KEYS */;
INSERT INTO `tenants` VALUES (1,'Taller Mecánico A','taller-a','Admin A','admin@tallera.com','12345678','Zona 1','logos/NUOASnb6Pg9YqUuINT16v6rB1JiQPaOXhvLnSXr1.png',5,1,'2025-12-22 21:11:09','2025-12-23 00:22:28'),(2,'Taller Mecánico B','taller-b','Admin B','admin@tallerb.com','87654321','Zona 10',NULL,5,1,'2025-12-22 21:11:09','2025-12-22 21:11:09');
/*!40000 ALTER TABLE `tenants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `area_id` bigint unsigned DEFAULT NULL,
  `can_view_contact_info` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `users_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'Admin A','admin@tallera.com','admin',NULL,1,NULL,'$2y$12$PcUbfPzEIhOdSuv5RZEQQeYcc/B6eCMNz78VFB85EA0hVs1kSZ5xW',NULL,NULL,NULL,'aRU58BOsCPaa5kkCnctgkkxiGN57OoZ9lXLvPyS7KFQIhB8BxrtYyQ6MX1iZ',NULL,NULL,'2025-12-22 21:11:10','2025-12-23 02:43:15'),(2,2,'Admin B','admin@tallerb.com','client',NULL,0,NULL,'$2y$12$L9ocIMHVmKWN5RiJe5swPOkNxKXtFEkBVb2stiD/h.HNMbLtt4Wg6',NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-22 21:11:10','2025-12-22 21:11:10'),(4,1,'Juan Receptor','receptor@tallera.com','reception',1,1,NULL,'$2y$12$/LGFA0Id3FIiTciCVBAFVek6BVzir23ViKkASAOulIgOVAMLlXXay',NULL,NULL,NULL,'vsZ4GWwk9wdTgtZJFvDFZpFkahzCkmVYPkUDeKznssipJGBifpiNGRri9aGh',NULL,NULL,'2025-12-23 01:48:22','2025-12-23 01:48:22');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `plate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` int DEFAULT NULL,
  `engine_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chassis_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doors_qty` int DEFAULT NULL,
  `cc` int DEFAULT NULL,
  `owner_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_nit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicles_tenant_id_foreign` (`tenant_id`),
  KEY `vehicles_plate_index` (`plate`),
  CONSTRAINT `vehicles_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
INSERT INTO `vehicles` VALUES (1,1,'324GkR',NULL,'Toyota','Rav 4','Camioneta','suv','Verde',2015,NULL,NULL,5,NULL,'Juan Perez','59696181','perry@insayd.com',NULL,'Casa 135 sector A05','2025-12-22 21:11:11','2025-12-22 22:39:46'),(4,1,'123abc',NULL,'toyota','xls','sedan','sedan','gris',2000,NULL,NULL,5,NULL,'Pedro Pelaez','59696181','perry@insayd.com',NULL,'Casa 135 sector A05','2025-12-22 23:18:52','2025-12-22 23:18:52'),(5,1,'124ABC',NULL,'Subaru','XL','Hatchback','hashback','Gris',2000,NULL,NULL,4,NULL,'Pedro Pelaez','59696181','perry@insayd.com',NULL,'Casa 135 sector A05','2025-12-23 00:04:52','2025-12-23 00:04:52');
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_order_checklists`
--

DROP TABLE IF EXISTS `work_order_checklists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `work_order_checklists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `work_order_id` bigint unsigned NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('correct','wear','bad','missing') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'correct',
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_order_checklists_work_order_id_foreign` (`work_order_id`),
  CONSTRAINT `work_order_checklists_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_order_checklists`
--

LOCK TABLES `work_order_checklists` WRITE;
/*!40000 ALTER TABLE `work_order_checklists` DISABLE KEYS */;
INSERT INTO `work_order_checklists` VALUES (1,1,'Parte Trasera/Baúl','Emblema','wear',NULL,'2025-12-22 22:39:46','2025-12-22 22:39:46'),(2,1,'Parte Trasera/Baúl','Luz de Placa','wear',NULL,'2025-12-22 22:39:46','2025-12-22 22:39:46'),(3,1,'Parte Trasera/Baúl','Tapicería de Baúl','correct',NULL,'2025-12-22 22:39:46','2025-12-22 22:39:46'),(4,1,'Parte Trasera/Baúl','Herramientas','correct',NULL,'2025-12-22 22:39:46','2025-12-22 22:39:46'),(5,1,'Parte Trasera/Baúl','Llave de Chucho','correct',NULL,'2025-12-22 22:39:46','2025-12-22 22:39:46'),(6,1,'Parte Trasera/Baúl','Tricket','wear',NULL,'2025-12-22 22:39:46','2025-12-22 22:39:46'),(7,1,'Parte Trasera/Baúl','Llanta de Repuesto','correct',NULL,'2025-12-22 22:39:46','2025-12-22 22:39:46'),(8,1,'Parte Frontal','Antena','correct',NULL,'2025-12-22 22:39:46','2025-12-22 22:39:46'),(9,1,'Parte Frontal','Emblema','wear',NULL,'2025-12-22 22:39:46','2025-12-22 22:39:46'),(10,1,'Parte Frontal','Spoiler Delantero','correct',NULL,'2025-12-22 22:39:46','2025-12-22 22:39:46'),(11,1,'Parte Frontal','Luces Delanteras','correct',NULL,'2025-12-22 22:39:46','2025-12-22 22:39:46'),(12,1,'Parte Frontal','Plumillas','correct',NULL,'2025-12-22 22:39:46','2025-12-22 22:39:46'),(25,3,'Parte Trasera/Baúl','Emblema','missing',NULL,'2025-12-22 23:18:52','2025-12-22 23:18:52'),(26,3,'Parte Trasera/Baúl','Luz de Placa','wear',NULL,'2025-12-22 23:18:52','2025-12-22 23:18:52'),(27,3,'Parte Trasera/Baúl','Tapicería de Baúl','wear',NULL,'2025-12-22 23:18:52','2025-12-22 23:18:52'),(28,3,'Parte Trasera/Baúl','Herramientas','missing',NULL,'2025-12-22 23:18:52','2025-12-22 23:18:52'),(29,3,'Parte Trasera/Baúl','Llave de Chucho','missing',NULL,'2025-12-22 23:18:52','2025-12-22 23:18:52'),(30,3,'Parte Trasera/Baúl','Tricket','missing',NULL,'2025-12-22 23:18:52','2025-12-22 23:18:52'),(31,3,'Parte Trasera/Baúl','Llanta de Repuesto','missing',NULL,'2025-12-22 23:18:52','2025-12-22 23:18:52'),(32,3,'Parte Frontal','Antena','correct',NULL,'2025-12-22 23:18:52','2025-12-22 23:18:52'),(33,3,'Parte Frontal','Emblema','wear',NULL,'2025-12-22 23:18:52','2025-12-22 23:18:52'),(34,3,'Parte Frontal','Spoiler Delantero','wear',NULL,'2025-12-22 23:18:52','2025-12-22 23:18:52'),(35,3,'Parte Frontal','Luces Delanteras','correct',NULL,'2025-12-22 23:18:52','2025-12-22 23:18:52'),(36,3,'Parte Frontal','Plumillas','correct',NULL,'2025-12-22 23:18:52','2025-12-22 23:18:52'),(37,4,'Parte Trasera/Baúl','Emblema','missing',NULL,'2025-12-23 00:04:52','2025-12-23 00:04:52'),(38,4,'Parte Trasera/Baúl','Luz de Placa','missing',NULL,'2025-12-23 00:04:52','2025-12-23 00:04:52'),(39,4,'Parte Trasera/Baúl','Tapicería de Baúl','missing',NULL,'2025-12-23 00:04:52','2025-12-23 00:04:52'),(40,4,'Parte Trasera/Baúl','Herramientas','missing',NULL,'2025-12-23 00:04:52','2025-12-23 00:04:52'),(41,4,'Parte Trasera/Baúl','Llave de Chucho','missing',NULL,'2025-12-23 00:04:52','2025-12-23 00:04:52'),(42,4,'Parte Trasera/Baúl','Tricket','wear',NULL,'2025-12-23 00:04:52','2025-12-23 00:04:52'),(43,4,'Parte Trasera/Baúl','Llanta de Repuesto','wear',NULL,'2025-12-23 00:04:52','2025-12-23 00:04:52'),(44,4,'Parte Frontal','Antena','wear',NULL,'2025-12-23 00:04:52','2025-12-23 00:04:52'),(45,4,'Parte Frontal','Emblema','correct',NULL,'2025-12-23 00:04:52','2025-12-23 00:04:52'),(46,4,'Parte Frontal','Spoiler Delantero','missing',NULL,'2025-12-23 00:04:52','2025-12-23 00:04:52'),(47,4,'Parte Frontal','Luces Delanteras','correct',NULL,'2025-12-23 00:04:52','2025-12-23 00:04:52'),(48,4,'Parte Frontal','Plumillas','wear',NULL,'2025-12-23 00:04:52','2025-12-23 00:04:52');
/*!40000 ALTER TABLE `work_order_checklists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_order_damages`
--

DROP TABLE IF EXISTS `work_order_damages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `work_order_damages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `work_order_id` bigint unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `x_coord` int NOT NULL,
  `y_coord` int NOT NULL,
  `view_angle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_order_damages_work_order_id_foreign` (`work_order_id`),
  CONSTRAINT `work_order_damages_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_order_damages`
--

LOCK TABLES `work_order_damages` WRITE;
/*!40000 ALTER TABLE `work_order_damages` DISABLE KEYS */;
/*!40000 ALTER TABLE `work_order_damages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_order_details`
--

DROP TABLE IF EXISTS `work_order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `work_order_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `work_order_id` bigint unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('repuesto','mano_obra','otro') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pendiente','aprobado','rechazado','completado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_order_details_work_order_id_foreign` (`work_order_id`),
  CONSTRAINT `work_order_details_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_order_details`
--

LOCK TABLES `work_order_details` WRITE;
/*!40000 ALTER TABLE `work_order_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `work_order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_order_photos`
--

DROP TABLE IF EXISTS `work_order_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `work_order_photos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `work_order_id` bigint unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_order_photos_work_order_id_foreign` (`work_order_id`),
  CONSTRAINT `work_order_photos_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_order_photos`
--

LOCK TABLES `work_order_photos` WRITE;
/*!40000 ALTER TABLE `work_order_photos` DISABLE KEYS */;
INSERT INTO `work_order_photos` VALUES (1,3,'work-orders/3/B0nbpXvO0yh3MTjMCsS0LsqOTonZAlGvrhhSAXrV.png','general','2025-12-22 23:18:52','2025-12-22 23:18:52'),(2,3,'work-orders/3/deG6snfPJyIqLscuZacK7xLWYcmADhb0ZFHK6c6t.webp','general','2025-12-22 23:18:52','2025-12-22 23:18:52'),(3,3,'work-orders/3/damage_map_1766423932.png','damage_map','2025-12-22 23:18:52','2025-12-22 23:18:52'),(4,4,'work-orders/4/lH5ANoiqYVbjVFJ8FyItDprDvLZR6gnpQ33R0IYP.jpg','general','2025-12-23 00:04:52','2025-12-23 00:04:52'),(5,4,'work-orders/4/damage_map_1766426692.png','damage_map','2025-12-23 00:04:52','2025-12-23 00:04:52');
/*!40000 ALTER TABLE `work_order_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_orders`
--

DROP TABLE IF EXISTS `work_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `work_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `vehicle_id` bigint unsigned NOT NULL,
  `current_area_id` bigint unsigned DEFAULT NULL,
  `status` enum('recibido','presupuesto','en_espera','trabajando','revision','terminado') COLLATE utf8mb4_unicode_ci DEFAULT 'recibido',
  `payment_type` enum('aseguradora','particular') COLLATE utf8mb4_unicode_ci NOT NULL,
  `insurance_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mileage` int DEFAULT NULL,
  `fuel_level` enum('E','1/4','1/2','3/4','F') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `received_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_orders_tenant_id_foreign` (`tenant_id`),
  KEY `work_orders_vehicle_id_foreign` (`vehicle_id`),
  KEY `work_orders_current_area_id_foreign` (`current_area_id`),
  CONSTRAINT `work_orders_current_area_id_foreign` FOREIGN KEY (`current_area_id`) REFERENCES `areas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `work_orders_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `work_orders_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_orders`
--

LOCK TABLES `work_orders` WRITE;
/*!40000 ALTER TABLE `work_orders` DISABLE KEYS */;
INSERT INTO `work_orders` VALUES (1,1,1,NULL,'recibido','aseguradora',NULL,121212,'1/4',NULL,'2025-12-22 22:39:46',NULL,'2025-12-22 22:39:46','2025-12-22 22:39:46'),(3,1,4,NULL,'recibido','aseguradora',NULL,121212,'E','corregir daños de abolladoras','2025-12-22 23:18:52',NULL,'2025-12-22 23:18:52','2025-12-22 23:18:52'),(4,1,5,4,'recibido','aseguradora',NULL,1212121,'E','reparacion de motro over hall','2025-12-23 00:04:52',NULL,'2025-12-23 00:04:52','2025-12-23 00:10:23');
/*!40000 ALTER TABLE `work_orders` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-23  1:20:20
