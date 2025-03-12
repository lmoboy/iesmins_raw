-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               8.0.40 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping database structure for storedb
DROP DATABASE IF EXISTS `storedb`;
CREATE DATABASE IF NOT EXISTS `storedb` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `storedb`;

-- Dumping structure for table storedb.categories
DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table storedb.categories
REPLACE INTO `categories` (`id`, `name`, `description`) VALUES
  (1, 'Electronics', 'Electronic devices and accessories'),
  (2, 'Accessories', 'Various accessories for daily use'),
  (3, 'Footwear', 'Shoes and related footwear items');

-- Dumping structure for table storedb.products
DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `category_id` int NOT NULL,
  `price` float NOT NULL,
  `quantity` int NOT NULL,
  `image` varchar(255) DEFAULT 'wtf.png',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table storedb.products
REPLACE INTO `products` (`id`, `name`, `description`, `category_id`, `price`, `quantity`, `image`) VALUES
  (9, 'Classic White Sneakers', 'Comfortable and stylish white sneakers for everyday wear', 3, 79.99, 25, 'classical-white-sneakers.jpg'),
  (10, 'Leather Messenger Bag', 'Premium leather messenger bag perfect for work or travel', 2, 129.99, 15, 'leather-messenger-bag.jpg'),
  (11, 'Wireless Headphones', 'High-quality wireless headphones with noise cancellation', 1, 199.99, 30, 'wireless-headphones.jpg'),
  (12, 'Smart Watch', 'Feature-rich smartwatch with fitness tracking capabilities', 1, 249.99, 20, 'smart-watch.jpg'),
  (13, 'Laptop Backpack', 'Durable backpack with padded laptop compartment', 2, 89.99, 40, 'laptop-backpack.jpg'),
  (14, 'Portable Power Bank', 'High-capacity power bank for charging multiple devices', 1, 49.99, 50, 'portable-power-bank.jpg'),
  (15, 'Bluetooth Speaker', 'Portable Bluetooth speaker with rich sound quality', 1, 159.99, 35, 'bluetooth-speaker.jpeg'),
  (16, 'Gaming Mouse', 'High-precision gaming mouse with customizable buttons', 1, 69.99, 45, 'gaming-mouse.jpg'),
  (17, 'Laptop', 'High-performance laptop with latest specifications', 1, 999.99, 5, 'laptop.jpeg');

-- Dumping structure for table storedb.orders
DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `total_price` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping structure for table storedb.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table storedb.users: ~1 rows (approximately)
REPLACE INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
	(1, 'Pidor Aleksandrovich', 'admin@admin.admin', '$2y$12$GiM0.jwW4SPgvZ053ez./eQZNRKPVZNfC04nK/2o2yIuCt/YrT8SW', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
