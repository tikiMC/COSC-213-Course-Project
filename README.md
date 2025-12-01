# COSC-213-Course-Project
E-Commerce Platform - Group G - Caleb Carlson and Kieran Russell
As a group we've decided to select option 1, E-Commerce Platform. I've only just read that we were supposed to get an okay from our professor about project choice as I missed that when going through the Project.md file. I've counted that there are a total of 7 groups so hopefully there is enough varierty because of that.

Project Description:
This project is a very bare bones model of an e-commerce website that allows users to register and then login/logout. The index or "product catalog" holds all avaliable products avaliable for purchase. To add an item to your cart, you first must click on the item which will bring you to a page dedicated to the item with slightly more information about it. The system fully allows items inside the cart to be updated, increased/decreased in quanity, and removed. Every thing is session based and tied to the user account which means everything persists even after leaving the page or logging out.

Furthermore, there is an admin panel which can only be accessed by admins (to become an admin, you must intially open the database and change the is_admin column to a 1 instead of the default 0. The admin panel allows full control of products, you can edit all information about a product or even outright delete it from the catalog.

Setup: I built this platform using the WAMP architecture, as I find working in a Windows environment still far easier and straightforward when compared to Linux. So to recreate this, a prerequisite is having WAMP installed locally. Once you've installed the WAMP stack, login to myPHPAdmin with the default settings, user= root, password can be left empty, and ensure the MySQL database is selected.

User Credentials:
email: test@email.com
username: test
password: test

Admin Credentials:
email: admin@email.com
username: admin
password: admin

Scheme file:
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 01, 2025 at 04:32 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `e-commerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE IF NOT EXISTS `cart_items` (
  `cart_item_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`cart_item_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `shipping_name` varchar(255) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `shipping_city` varchar(100) NOT NULL,
  `shipping_postal` varchar(20) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price_each` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(7,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `stock`, `image_path`) VALUES
(1, 'Apple', 'A nice juicy apple full of vitamins. Guaranteed to contain zero worms!', 1.59, 12, 'apple.png'),
(2, 'Orange', 'A delicious orange. Protect yourself from scurvy with some vitamin C.', 1.39, 12, 'orange.webp'),
(3, 'Big Bird', 'The lovable character from Sesame Street. WARNING NOT A STUFFED ANIMAL.', 1234.56, 1, 'big_bird.webp'),
(4, 'Crowbar', 'Useful tool for opening sewer grates. 5/5 review from TMNT.', 39.99, 3, 'crowbar.png'),
(5, 'Dirt (1m^3)', 'Doesn\'t seem like a lot of dirt... but it is.', 64.00, 64, 'dirt_block.webp'),
(6, 'Faucet', 'Sink not included.', 104.99, 12, 'faucet.webp'),
(7, 'Muse', 'Featuring hits such as: \"Super Massive Black Hole\", \"Madness\", and \"Uprising\" Muse is an English rock band formed in 1994.', 99999.99, 1, 'muse.jpg'),
(8, 'Nail Polish', 'Paint your nails our signature der color to stand out from the crowd.', 9.99, 900, 'nail_polish.jpg'),
(9, 'Garden Rocks', 'Perfect for adding natural earthy colors to your garden.', 425.99, 20, 'rocks.png'),
(10, 'Stegosaurus', 'That\'s right, an entire ALIVE Stegosaurus (don\'t ask how we got them, we aren\'t legally required to answer.)', 16000.00, 3, 'stego.webp'),
(11, 'Stone (1m^3)', 'Seems like a lot of stone... it isn\'t.', 640.00, 64, 'stone_block.webp'),
(12, 'Unicycle', 'Believe it or not, you don\'t actually need a license to ride one of these!', 99.99, 8, 'unicycle.jpg'),
(13, 'Shiny Volcarona', 'When volcanic ash darkened the atmosphere, it is said that Volcarona\'s fire provided a replacement for the sun.', 637.00, 6, 'volcarona.png'),
(14, 'WD-40', 'The end all, be all product.', 6.97, 999, 'wd40.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `username`, `password`, `is_admin`) VALUES
(10, 'test@email.com', 'test', '$2y$10$9WiY2p/YxoRXMwCv9xLX3OeVMShco3DeFQCnzXFE/AXVpBkQZEAKe', 0),
(9, 'calebcarlson@outlook.com', 'tiki91', '$2y$10$kEQrLpIfMUHH79m2NhWwyu0tolIPLx4DhJ.OOW7C/lAnyz16t5FVG', 1);
COMMIT;

