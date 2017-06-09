-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2016 at 04:17 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eshopdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_username` varchar(24) NOT NULL,
  `admin_name` varchar(32) NOT NULL,
  `admin_password` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_username`, `admin_name`, `admin_password`) VALUES
('admin', 'folashade', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_partner_id` int(11) NOT NULL,
  `order_customer_id` int(11) NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `order_product_quantity` int(24) NOT NULL,
  `order_total` int(24) NOT NULL,
  `order_delivery_address` varchar(1024) NOT NULL,
  `order_email` varchar(1024) NOT NULL,
  `order_phone_number` varchar(24) NOT NULL,
  `order_timestamp` int(11) NOT NULL,
  `order_processed` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_partner_id`, `order_customer_id`, `order_product_id`, `order_product_quantity`, `order_total`, `order_delivery_address`, `order_email`, `order_phone_number`, `order_timestamp`, `order_processed`) VALUES
(7, 28, 0, 12, 1, 0, 'lagos', 'taiwoabegunde@live.com', '+2348067846033', 1470005910, 0),
(8, 28, 0, 12, 1, 0, 'lagos', 'taiwoabegunde@live.com', '+2348067846033', 1470006055, 0),
(9, 28, 0, 5, 1, 0, 'lagos', 'taiwoabegunde@live.com', '+2348067846033', 1470149299, 0),
(10, 28, 0, 2, 1, 0, 'lagos', 'taiwoabegunde@live.com', '+2348067846033', 1470149488, 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `ext_product_id` int(11) NOT NULL,
  `product_name` varchar(128) NOT NULL,
  `product_description` varchar(1024) NOT NULL,
  `product_image_url` varchar(1024) NOT NULL,
  `product_quantity` int(24) NOT NULL,
  `product_price` int(24) NOT NULL,
  `product_removed` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `vendor_id`, `ext_product_id`, `product_name`, `product_description`, `product_image_url`, `product_quantity`, `product_price`, `product_removed`) VALUES
(1, 28, 0, 'Caviar Sleeve Knit', 'Patterned Crew neck.', 'http://localhost/eshop/images/products/KTZPatternedCrewneck.jpg', 100, 999, 0),
(2, 28, 0, 'Classic Sweater', 'Drawstring-Crewneck, with classic iconic denim front.', 'http://localhost/eshop/images/products/Drawstring-Crewneck.jpg', 20, 1999, 0),
(4, 28, 0, 'Shirt-formal', 'mens-stylish, formal shirt.', 'http://localhost/eshop/images/products/Shirt-formal.jpg', 50, 2499, 0),
(5, 28, 0, 'Cardigan', 'phenomenoncardigan.', 'http://localhost/eshop/images/products/cardigan.jpg', 50, 999, 0),
(6, 28, 0, 'Jeans', 'Mens-Jeans-Pant-.', 'http://localhost/eshop/images/products/Jeans.jpg', 60, 4999, 0),
(7, 28, 0, 'ladies-shoes', 'high-heel, Size 43-ladies-shoes', 'http://localhost/eshop/images/products/ladies-shoes.jpg', 35, 3999, 0),
(8, 28, 0, 'Diamond necklace', 'A diamond necklace.', 'http://localhost/eshop/images/products/Diamondnecklace.jpg', 2, 59999, 0),
(9, 28, 0, 'Sleeveless V-neck dress', 'Sleeveless Big Hem , V-neck dress.', 'http://localhost/eshop/images/products/V-neck.jpg', 15, 7999, 0),
(10, 28, 0, 'Body Con dress', 'Round Neck Body Con Dress.', 'http://localhost/eshop/images/products/BodyConDress.jpg', 20, 2999, 0),
(12, 28, 0, 'Floral dress', 'Floral Print Dress With Belt.', 'http://localhost/eshop/images/products/Floraldress.jpg', 10, 8499, 0),
(13, 28, 0, 'Twin Handlebag ', 'Double Twin Handle, Handlebag with inner bag.', 'http://localhost/eshop/images/products/TwinHandlebag.jpg', 210, 1299, 0),
(14, 28, 0, 'ear ring', 'Water Drop Ear Ring.', 'http://localhost/eshop/images/products/earring.jpg', 28, 999, 0),
(17, 29, 1, 'Tomatoes', 'A bowl of local Tomatoes', 'http://localhost/eshop/images/products/TOMATOES.jpg', 8, 500, 0),
(18, 29, 1, 'egg', 'A crate of egg', 'http://localhost/eshop/images/products/egg.jpg', 8, 500, 0),
(19, 29, 3, 'Pepper', 'A bowl of fresh pepper', 'http://localhost/eshop/images/products/Pepper.jpg', 7, 500, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(32) CHARACTER SET utf8 NOT NULL,
  `user_email` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `user_password` varchar(512) CHARACTER SET utf8 NOT NULL,
  `user_code` varchar(100) CHARACTER SET utf8 NOT NULL,
  `user_timestamp` int(11) NOT NULL,
  `user_confirmed` int(11) NOT NULL DEFAULT '0',
  `user_string` varchar(35) CHARACTER SET utf8 NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_code`, `user_timestamp`, `user_confirmed`, `user_string`) VALUES
(1, 'Demo User', 'coolfola4real2004@yahoo.co.uk', '$2y$12$240365984546d32cf19d8uu95v3jQy0Yh.pFdWCMY/mi1XXiyOKky', '1546d32cf19d2e', 1416442575, 1, '0'),
(2, 'Folashade', 'coolfola4real@yahoo.co.uk', '$2y$12$5390315125546d38ab5dbup77oeiUg7zFkXsRZk4gBnq9Dt.n/Mcq', '1546d38ab5dacb', 1416444075, 1, '0'),
(3, 'Shade', 'folashade@gmail.com', '$2y$12$863834224546d390ae1cauMxtOPfApDAc3LxrjJkRaOX.Iq34P.ZW', '1546d390ae1c5d', 1416444170, 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` int(11) NOT NULL,
  `vendor_name` varchar(32) NOT NULL,
  `vendor_email` varchar(1024) NOT NULL,
  `vendor_url` varchar(1024) NOT NULL,
  `vendor_commission` int(11) NOT NULL,
  `vendor_code` varchar(100) NOT NULL,
  `vendor_key` varchar(20) NOT NULL,
  `vendor_confirmed` int(11) NOT NULL DEFAULT '0',
  `vendor_removed` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`vendor_id`, `vendor_name`, `vendor_email`, `vendor_url`, `vendor_commission`, `vendor_code`, `vendor_key`, `vendor_confirmed`, `vendor_removed`) VALUES
(28, 'eShop', 'coolfola4real2004@yahoo.co.uk', 'http://localhost/eshop/api/products.json', 0, '', '518012', 1, 0),
(29, 'Demo Partner', 'demopartner@gmail.com', 'http://localhost/eshop/products.xml', 5, '1546d31a8e468c', '346820', 1, 0),
(30, 'My Shop', 'coolfola4real2004@yahoo.co.uk', '', 24, '1546ded594f8bb', '', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
