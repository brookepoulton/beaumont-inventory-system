-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 24, 2025 at 05:04 PM
-- Server version: 10.3.39-MariaDB-0ubuntu0.20.04.2
-- PHP Version: 7.4.3-4ubuntu2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `495f4bpoulton`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryID`, `name`) VALUES
(1, 'Necklaces'),
(2, 'Rings'),
(3, 'Earrings'),
(4, 'Bracelets'),
(7, 'Brooches'),
(8, 'Charms'),
(18, 'Locklet');

-- --------------------------------------------------------

--
-- Table structure for table `product_inventory`
--

CREATE TABLE `product_inventory` (
  `itemID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `FKsupplierID` int(11) NOT NULL,
  `FKcategoryID` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_inventory`
--

INSERT INTO `product_inventory` (`itemID`, `name`, `description`, `quantity`, `price`, `FKsupplierID`, `FKcategoryID`, `status`) VALUES
(1, 'Lyla\'s Gold Necklace', 'Elegant 24K gold necklace with intricate design.', 8, '299.99', 4, 1, 1),
(2, 'Lily\'s Diamond Ring', 'Stunning diamond ring with a classic design.', 11, '1299.99', 3, 2, 1),
(3, 'Alicia\'s Gold Necklace', 'Elegant 24K gold necklace with intricate design.', 10, '149.99', 1, 1, 1),
(4, 'Sophia\'s Silver Ring', 'Sterling silver ring with a beautiful gemstone.', 9, '69.99', 11, 2, 1),
(14, 'Emily\'s Diamond Anklet', 'An amazing anklet', 35, '59.99', 9, 4, 1),
(15, 'Grace\'s Gold Bracelet', 'Gold bracelet', 48, '99.99', 10, 4, 0),
(22, 'Brenda\'s Ruby Necklace', 'A stunning array of vibrant red rubies set in a delicate metal design.', 19, '89.99', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales_data`
--

CREATE TABLE `sales_data` (
  `salesID` int(11) NOT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `quantity_sold` int(11) NOT NULL DEFAULT 0,
  `cost` decimal(10,2) NOT NULL,
  `FKItemID` int(11) NOT NULL,
  `FKUserID` int(11) NOT NULL,
  `type` enum('sale','restock') DEFAULT 'sale'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_data`
--

INSERT INTO `sales_data` (`salesID`, `date`, `quantity_sold`, `cost`, `FKItemID`, `FKUserID`, `type`) VALUES
(13, '2024-11-02 20:08:39', 1, '1299.99', 2, 1, 'sale'),
(14, '2024-11-02 20:09:41', 1, '299.99', 1, 1, 'sale'),
(15, '2024-11-02 20:14:20', 2, '1299.99', 2, 1, 'sale'),
(19, '2024-11-02 20:39:22', 50, '1299.99', 2, 1, 'sale'),
(22, '2024-11-03 19:39:20', 1, '1299.99', 2, 1, 'sale'),
(23, '2024-11-08 16:28:00', 3, '299.99', 1, 1, 'sale'),
(25, '2024-11-08 16:51:44', 2, '299.99', 1, 1, 'sale'),
(26, '2024-11-08 16:57:50', 1, '299.99', 1, 1, 'sale'),
(35, '2024-11-08 17:18:15', 2, '299.99', 1, 16, 'sale'),
(39, '2024-11-11 13:55:58', 1, '299.99', 1, 1, 'sale'),
(40, '2024-11-11 14:04:43', 1, '0.00', 2, 1, 'restock'),
(41, '2024-11-11 14:04:43', 1, '0.00', 3, 1, 'restock'),
(43, '2024-11-12 14:06:46', 2, '99.99', 15, 1, 'sale'),
(47, '2024-11-27 17:18:40', 20, '59.99', 14, 1, 'sale'),
(48, '2024-11-29 15:04:19', 20, '0.00', 22, 1, 'restock'),
(49, '2024-12-03 14:03:14', 3, '0.00', 2, 21, 'restock'),
(50, '2024-12-04 09:13:37', 1, '0.00', 1, 22, 'restock'),
(51, '2024-12-04 09:14:23', 2, '299.99', 1, 22, 'sale'),
(52, '2024-12-10 14:36:53', 10, '59.99', 14, 1, 'sale'),
(53, '2024-12-10 14:37:47', 20, '0.00', 14, 1, 'restock'),
(54, '2024-12-11 08:17:15', 2, '0.00', 22, 22, 'restock'),
(55, '2024-12-11 08:19:04', 8, '299.99', 1, 22, 'sale'),
(56, '2024-12-11 08:19:04', 3, '149.99', 3, 22, 'sale'),
(57, '2024-12-11 08:30:55', 2, '0.00', 2, 1, 'restock'),
(58, '2024-12-11 13:17:17', 5, '59.99', 14, 1, 'sale'),
(59, '2024-12-11 13:17:17', 10, '89.99', 22, 1, 'sale'),
(60, '2024-12-11 13:17:55', 3, '0.00', 1, 1, 'restock'),
(61, '2024-12-11 13:17:55', 2, '0.00', 3, 1, 'restock'),
(62, '2024-12-13 13:50:46', 10, '59.99', 14, 1, 'sale'),
(63, '2024-12-13 13:50:46', 5, '89.99', 22, 1, 'sale'),
(64, '2024-12-13 13:51:17', 2, '0.00', 3, 1, 'restock'),
(65, '2024-12-13 13:51:17', 2, '0.00', 4, 1, 'restock');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplierID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contactInfo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplierID`, `name`, `contactInfo`) VALUES
(1, 'Jewelry World', 'contact@jewelryworld.com'),
(2, 'Elegant Gems', 'info@elegantgems.com'),
(3, 'Precious Metal Suppliers', 'supportforyou@preciousmetal.com'),
(4, 'Trendy Jewelry Co.', 'salesteam@trendyjewelry.com'),
(7, 'Gold Star Supplies', 'infoforyou@goldstarsupplies.com'),
(8, 'Gemstone Importers', 'contact@gemstoneimporters.com'),
(9, 'Quality Jewelry Supplies', 'ask@qualityjewelry.com'),
(10, 'Designer Findings', 'salestest@designerfindings.com'),
(11, 'Emily\'s Jewls', 'contact@emjewels.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `username` varchar(75) DEFAULT NULL,
  `password` varchar(75) NOT NULL,
  `adminstatus` tinyint(4) NOT NULL DEFAULT 0,
  `isActive` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `password`, `adminstatus`, `isActive`) VALUES
(1, 'admin', '$2y$10$X2DgWQRM3/20sEFJV4ediuZE8HuMqugcIDOtpVNvPXXqQh0idsLni', 1, 1),
(4, 'someoneisgreat', '$2y$10$Pov2sfFNwuphSEbP93lYuO8aReIJXzDKjYgdmdXTte299BoAd6Ywq', 0, 1),
(8, 'kyler123', '$2y$10$DfCjOBYeHw/B.U8qbmp.wuyI3HTzDMVoqz/zuKIcxzl/WhX3/mstW', 1, 1),
(9, 'kyler1234', '$2y$10$NAdpkJoRhCO3uh//Mo/ir.WidXvuOlaynzqtJ5x/t3xyTlNkZxHIC', 0, 1),
(14, 'bpoulton', '$2y$10$/RxpmgY81VfvfErejTJicO/S/I0TXOphDGkgtubtZQSBZNsClob1y', 1, 1),
(16, 'coastal123', '$2y$10$Rpb4czNCNoD9BABmOM492.dqYN5qPjs4jE24xyQ.lQMCtgGDwKdOe', 1, 1),
(17, 'katie123', '$2y$10$.I0wGBxNkr/K3gJJ3N0pZe1soOsgwKG7TN9l5QVs0AQvWjHKD76ee', 1, 1),
(18, 'katie1234', '$2y$10$ViW0jsEJrd/KEo8E/.1E3eeSoo5W1963CQgzHuDlJQTlTfzCo7OoS', 0, 1),
(19, 'testing2', '$2y$10$jrZ0yqpTVXtBfzY4omYIDuEood4bzGSgWj.K21QZH5xNreRzUblRO', 0, 0),
(20, 'hsmith', '$2y$10$krOSRvEJlrtEjPxDLiKvXuoPa/ZEg1p2bBu7dBviuaVtWJITXz/x6', 0, 1),
(21, 'jennis', '$2y$10$bG0RnSUeiRDdEkm3d7xw/uh0cDHcawzadZ/bFZpitf3k0w9mx8qPa', 0, 1),
(22, 'user', '$2y$10$WyBRy2yvVP7jx7QlYslTEefFsazsflAxX.w7pvdUFPIC.peyZ2RAe', 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD PRIMARY KEY (`itemID`),
  ADD KEY `fk_product_inventory_supplier` (`FKsupplierID`),
  ADD KEY `fk_category` (`FKcategoryID`);

--
-- Indexes for table `sales_data`
--
ALTER TABLE `sales_data`
  ADD PRIMARY KEY (`salesID`),
  ADD KEY `fk_sales_data_product` (`FKItemID`),
  ADD KEY `fk_sales_data_user` (`FKUserID`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplierID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `product_inventory`
--
ALTER TABLE `product_inventory`
  MODIFY `itemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `sales_data`
--
ALTER TABLE `sales_data`
  MODIFY `salesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`FKcategoryID`) REFERENCES `categories` (`categoryID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_inventory_supplier` FOREIGN KEY (`FKsupplierID`) REFERENCES `suppliers` (`supplierID`) ON UPDATE CASCADE;

--
-- Constraints for table `sales_data`
--
ALTER TABLE `sales_data`
  ADD CONSTRAINT `fk_sales_data_product` FOREIGN KEY (`FKItemID`) REFERENCES `product_inventory` (`itemID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sales_data_user` FOREIGN KEY (`FKUserID`) REFERENCES `users` (`userID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
