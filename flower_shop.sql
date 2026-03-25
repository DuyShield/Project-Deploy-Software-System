-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2026 at 02:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `flower_shop`
--
CREATE DATABASE IF NOT EXISTS `flower_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `flower_shop`;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `username`, `password`, `role`, `email`) VALUES
(1, 'admin', '$2y$10$X/C.vCzHZOzbyTPLMq7eJ.jPJ8bQhYPvECRLfDKmhQLcylolx0N1i', 'admin', 'admin123@gmail.com'),
(2, 'user', 'user123', 'user', 'user123@gmail.com'),
(3, 'DuyCoderS', '$2y$10$YI7L.Ta0G21etX9lUz4.oexZy1K5l8vtyZHeBKvZCiSdDwHiu4uiK', 'user', 'phuocduy565@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id_product` int(11) NOT NULL,
  `name_product` varchar(128) NOT NULL,
  `description_product` text NOT NULL,
  `price_product` int(11) NOT NULL,
  `image` text NOT NULL,
  `id_category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id_product`, `name_product`, `description_product`, `price_product`, `image`, `id_category`) VALUES
(1, 'Hoa Hồng', 'Hoa hồng là biểu tượng của tình yêu và vẻ đẹp, với cánh hoa mềm mại, màu sắc rực rỡ và hương thơm quyến rũ.', 120000, 'hoa_hong.jpg', 1),
(2, 'Hoa mai', 'Cây được dùng để trang trí trong những ngày lễ tết. Gốc cây cằn cỗi, lớp vỏ xù xì, những đường gân nổi lên đan chéo nhau trên mặt đất rồi mới đâm xuống phô diễn sức mạnh của rễ cội. Thân cây cứng cáp, khỏe mạnh được khoác lên một lớp áo màu nâu sẫm.', 210000, 'hoa_mai.jpg', 1),
(3, 'Hoa sen', 'Mô tả vẻ đẹp của hoa sen trong mùa hè, từ cánh hoa đến lá sen mềm mại, hương thơm nhẹ nhàng lan tỏa. - Ý nghĩa sâu sắc của hoa sen, tượng trưng cho lòng kiên cường, bất khuất của dân tộc Việt Nam.', 70000, 'hoa_sen.jpg', 1),
(4, 'Hoa đào', 'Là biểu tượng của mùa xuân và Tết Nguyên Đán trong văn hóa Việt Nam.', 31000, 'hoa_dao.jpg', 1),
(5, 'Hoa Cẩm Tú', 'Kích thước, hình dạng và cấu trúc của hoa, gồm từng cánh hoa nhỏ gộp lại thành từng chùm tròn lớn. Màu sắc đa dạng từ xanh, hồng, trắng, tím; cách chúng thay đổi theo độ pH của đất.', 325000, 'hoa_cam_tu.jpg', 1),
(6, 'Hoa tulip', 'Là loài hoa nổi tiếng với vẻ đẹp thanh tao, kiêu sa và rực rỡ.', 50000, 'hoa_tulip.jpg', 1),
(7, 'Hoa linh lang', 'Còn gọi là hoa lan chuông là một loại hoa lan nổi tiếng và được ưa chuộng trên toàn thế giới không? Hoa linh lan có hình dáng như những chiếc chuông xinh xắn, lại thêm hương thơm ngọt ngào và quyến rũ.', 152000, 'hoa_linh_lang.jpg', 1),
(8, 'Hoa hướng dương', 'loài cây chỉ sống một năm, thuộc họ Asteraceae, có bông hoa to lớn, cao tới 3 mét, đường kính bông hoa lên tới 30cm.', 300000, 'hoa_huong_duong.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
