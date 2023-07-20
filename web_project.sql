-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 20 juil. 2023 à 10:36
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `web_project`
--

-- --------------------------------------------------------

--
-- Structure de la table `auctions`
--

DROP TABLE IF EXISTS `auctions`;
CREATE TABLE IF NOT EXISTS `auctions` (
  `Auctions_Id` int NOT NULL AUTO_INCREMENT,
  `Categorie` varchar(25) NOT NULL,
  `Size` int NOT NULL,
  `Color` varchar(15) NOT NULL,
  `Starting_Date` date NOT NULL,
  `Finish_Date` date NOT NULL,
  `Actual_Bid` int NOT NULL,
  `Price` int NOT NULL,
  `Product_Id` int NOT NULL,
  `auction_score` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`Auctions_Id`),
  KEY `Product_Id` (`Product_Id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `auctions`
--

INSERT INTO `auctions` (`Auctions_Id`, `Categorie`, `Size`, `Color`, `Starting_Date`, `Finish_Date`, `Actual_Bid`, `Price`, `Product_Id`, `auction_score`) VALUES
(15, 'Sneakers', 11, 'white', '2023-07-19', '2023-08-24', 0, 111, 119, 1),
(16, 'Sneakers', 11, 'white', '2023-07-19', '2023-08-24', 0, 111, 120, 1),
(17, 'Sneakers', 11, 'white', '2023-07-19', '2023-08-24', 0, 111, 121, 1),
(18, 'Sneakers', 11, 'white', '2023-07-19', '2023-08-24', 0, 111, 122, 1),
(19, 'Sneakers', 11, 'white', '2023-07-19', '2023-08-24', 0, 111, 123, 1),
(20, 'Sneakers', 11, 'white', '2023-07-19', '2023-08-24', 0, 111, 124, 1),
(21, 'tshirt', 0, 'white', '2023-07-19', '0000-00-00', 0, 111, 131, 1),
(22, 'tshirt', 0, 'white', '2023-07-19', '0000-00-00', 0, 111, 132, 1),
(23, 'tshirt', 0, 'white', '2023-07-19', '0000-00-00', 0, 111, 133, 1),
(24, 'tshirt', 0, 'white', '2023-07-19', '0000-00-00', 0, 111, 134, 1),
(25, '', 0, '', '0000-00-00', '0000-00-00', 0, 0, 135, 1);

-- --------------------------------------------------------

--
-- Structure de la table `best_offer`
--

DROP TABLE IF EXISTS `best_offer`;
CREATE TABLE IF NOT EXISTS `best_offer` (
  `Best_Offer_Id` int NOT NULL AUTO_INCREMENT,
  `Categorie` varchar(25) NOT NULL,
  `Size` int NOT NULL,
  `Color` varchar(15) NOT NULL,
  `Proposition_Price` int NOT NULL,
  `Number_Of_Negociation` int NOT NULL,
  `Product_Id` int NOT NULL,
  PRIMARY KEY (`Best_Offer_Id`),
  KEY `Product_Id` (`Product_Id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `best_offer`
--

INSERT INTO `best_offer` (`Best_Offer_Id`, `Categorie`, `Size`, `Color`, `Proposition_Price`, `Number_Of_Negociation`, `Product_Id`) VALUES
(11, 'T-shirt', 0, 'black', 250, 0, 128),
(8, 'Sneakers', 12, 'white', 123, 0, 109),
(13, 'Sneakers', 0, 'red', 250, 0, 130),
(14, '', 0, '', 0, 0, 135),
(15, 'Sneakers', 7, 'black', 150, 0, 140),
(16, 'Sneakers', 5, 'white', 111, 0, 141),
(17, 'Sneakers', 5, 'white', 111, 0, 142);

-- --------------------------------------------------------

--
-- Structure de la table `buy_now`
--

DROP TABLE IF EXISTS `buy_now`;
CREATE TABLE IF NOT EXISTS `buy_now` (
  `Buy_Now_Id` int NOT NULL AUTO_INCREMENT,
  `Categorie` varchar(25) NOT NULL,
  `Size` int NOT NULL,
  `Color` varchar(20) NOT NULL,
  `Price` int NOT NULL,
  `Product_Id` int NOT NULL,
  PRIMARY KEY (`Buy_Now_Id`),
  KEY `Product_Id` (`Product_Id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `buy_now`
--

INSERT INTO `buy_now` (`Buy_Now_Id`, `Categorie`, `Size`, `Color`, `Price`, `Product_Id`) VALUES
(12, 'Sneakers', 5, 'black', 222, 125),
(13, 'Sneakers', 5, 'black', 222, 126),
(14, 'Sneakers', 5, 'black', 222, 127),
(15, 'Sneakers', 7, 'white', 222, 135),
(16, 'tshirt', 0, 'white', 222, 136),
(17, 'Sneakers', 8, 'White', 321, 146);

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `Cart_Id` int NOT NULL AUTO_INCREMENT,
  `Quantity` int NOT NULL,
  `Product_Id` int NOT NULL,
  `User_Id` int NOT NULL,
  PRIMARY KEY (`Cart_Id`,`Product_Id`),
  KEY `Product_Id` (`Product_Id`),
  KEY `User_Id` (`User_Id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `Id_Img` int NOT NULL AUTO_INCREMENT,
  `Product_Id` int NOT NULL,
  `File_Name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`Id_Img`),
  KEY `Product_Id` (`Product_Id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`Id_Img`, `Product_Id`, `File_Name`) VALUES
(1, 100, '64b5851967099.jpg'),
(2, 103, '64b6645058d32.png'),
(3, 104, '64b6671fc4d55.png'),
(4, 106, '64b676c0349a6.png'),
(5, 107, '64b67cbc79640.png'),
(6, 108, '64b6ae6862ab8.png'),
(7, 109, '64b6ae7956fe8.png'),
(8, 110, '64b6e5dbc5f20.png'),
(9, 111, '64b6e85cd1a52.png'),
(10, 112, '64b7205b19af2.png'),
(11, 113, '64b723a120061.png'),
(12, 114, '64b726271cd8d.png'),
(13, 115, '64b7267d4d917.png'),
(14, 116, '64b729e2cc350.png'),
(15, 119, '64b7eb408aab2.png'),
(16, 120, '64b7eb935a871.png'),
(17, 121, '64b7ebbd8aa21.png'),
(18, 122, '64b7ec2d104bb.png'),
(19, 123, '64b7ec8ab70ca.png'),
(20, 124, '64b7ecd3f3c7f.png'),
(21, 125, '64b7ed7250e16.png'),
(22, 126, '64b7ed793a912.png'),
(23, 127, '64b7ed7d30c90.png'),
(24, 128, '64b7ee0f29aa9.png'),
(25, 129, '64b7fae95c2c0.png'),
(26, 130, '64b7fb30b865f.png'),
(27, 131, '64b7fc6b97efb.png'),
(28, 132, '64b7fcda60fef.png'),
(29, 133, '64b7fd18ec13c.png'),
(30, 134, '64b7fdb42012e.png'),
(31, 135, '64b80bd4b31e6.png'),
(32, 136, '64b8329bdfd05.png'),
(33, 140, '64b8358143659.png'),
(34, 141, '64b858d36a0b1.png'),
(35, 142, '64b8597e889a7.png'),
(36, 143, '64b868ca49291.png'),
(37, 144, '64b87144e6f88.png'),
(38, 145, '64b87179ca52b.png'),
(39, 146, '64b90733b8b16.jpg'),
(40, 147, '64b9075f2b621.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `Product_Id` int NOT NULL AUTO_INCREMENT,
  `Categorie` varchar(20) NOT NULL,
  `User_Id` int NOT NULL,
  `Price` int NOT NULL,
  `Product_Description` varchar(100) NOT NULL,
  `Product_Title` varchar(40) NOT NULL,
  PRIMARY KEY (`Product_Id`),
  KEY `User_Id` (`User_Id`)
) ENGINE=MyISAM AUTO_INCREMENT=148 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`Product_Id`, `Categorie`, `User_Id`, `Price`, `Product_Description`, `Product_Title`) VALUES
(146, 'Sneakers', 26, 321, 'Never used pair', 'run-on-shoesjpg'),
(145, 'Sneakers', 0, 300, 'Description :fefef', 'Jordan 1 retro limited'),
(144, 'Sneakers', 0, 300, 'Description :fefef', 'Jordan 1 retro limited'),
(143, 'Sneakers', 0, 300, 'Description :fefef', 'Jordan 1 retro limited'),
(142, 'Sneakers', 26, 111, 'Description :', 'test title'),
(141, 'Sneakers', 26, 111, 'Description :', 'test title'),
(140, 'Sneakers', 24, 150, 'test sneakers with', 'test sneakers with'),
(139, 'Sneakers', 24, 0, 'Description :', 'test'),
(138, 'Sneakers', 24, 0, 'Description :', 'test'),
(137, 'Sneakers', 24, 0, 'Description :', 'test'),
(135, 'Sneakers', 26, 222, 'test all selltype', 'test all selltype'),
(134, 'tshirt', 24, 111, 'great t-shirt !', 't-shirt'),
(133, 'tshirt', 24, 111, 'great t-shirt !', 't-shirt'),
(132, 'tshirt', 24, 111, 'great t-shirt !', 't-shirt'),
(130, 'Sneakers', 24, 250, 'first tshirt description', 'first tshirt'),
(131, 'tshirt', 24, 111, 'great t-shirt !', 't-shirt'),
(128, 'T-shirt', 24, 250, 'jamais porté !', 'T-shirt wankil !'),
(125, 'Sneakers', 24, 222, 'paire neuve !', 'dunk low'),
(126, 'Sneakers', 24, 222, 'paire neuve !', 'dunk low'),
(127, 'Sneakers', 24, 222, 'paire neuve !', 'dunk low'),
(136, 'tshirt', 26, 222, 'test with js', 'test with js'),
(119, 'Sneakers', 24, 111, 'first product', 'first product'),
(120, 'Sneakers', 24, 111, 'first product', 'first product'),
(121, 'Sneakers', 24, 111, 'first product', 'first product'),
(122, 'Sneakers', 24, 111, 'first product', 'first product'),
(123, 'Sneakers', 24, 111, 'first product', 'first product'),
(124, 'Sneakers', 24, 111, 'first product', 'first product'),
(109, 'Sneakers', 24, 123, 'Description : test add img', 'test add img');

-- --------------------------------------------------------

--
-- Structure de la table `sneakers`
--

DROP TABLE IF EXISTS `sneakers`;
CREATE TABLE IF NOT EXISTS `sneakers` (
  `Sneakers_Id` int NOT NULL AUTO_INCREMENT,
  `Size` int NOT NULL,
  `Color` varchar(10) NOT NULL,
  `Product_Id` int NOT NULL,
  `Type` varchar(20) NOT NULL,
  PRIMARY KEY (`Sneakers_Id`),
  KEY `Product_Id` (`Product_Id`)
) ENGINE=MyISAM AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `sneakers`
--

INSERT INTO `sneakers` (`Sneakers_Id`, `Size`, `Color`, `Product_Id`, `Type`) VALUES
(109, 8, 'White', 146, 'Low'),
(108, 3, 'blue', 145, 'High'),
(107, 3, 'blue', 144, 'High'),
(106, 3, 'blue', 143, 'High'),
(105, 5, 'white', 142, 'Low'),
(104, 5, 'white', 141, 'Low'),
(103, 7, 'black', 140, 'Mid'),
(102, 3, '', 139, 'Mid'),
(101, 3, '', 138, 'Mid'),
(100, 3, '', 137, 'Mid'),
(99, 7, 'white', 135, 'low'),
(98, 0, 'red', 130, 'v-neck'),
(97, 0, 'red', 129, 'v-neck'),
(96, 5, 'black', 127, 'low'),
(95, 5, 'black', 126, 'low'),
(94, 5, 'black', 125, 'low'),
(93, 11, 'white', 124, 'mid'),
(92, 11, 'white', 123, 'mid'),
(91, 0, '', 118, 'low'),
(90, 0, '', 117, 'mid'),
(89, 11, 'white', 115, 'high'),
(88, 11, 'white', 114, ''),
(75, 0, '', 100, ''),
(87, 12, 'white', 113, ''),
(86, 12, 'white', 111, ''),
(78, 11, 'black', 103, ''),
(79, 3, 'white', 104, ''),
(85, 11, 'white', 110, ''),
(81, 0, '', 106, ''),
(82, 12, 'white', 107, ''),
(83, 12, 'white', 108, ''),
(84, 12, 'white', 109, '');

-- --------------------------------------------------------

--
-- Structure de la table `tshirt`
--

DROP TABLE IF EXISTS `tshirt`;
CREATE TABLE IF NOT EXISTS `tshirt` (
  `Tshirt_Id` int NOT NULL AUTO_INCREMENT,
  `Size` varchar(10) NOT NULL,
  `Color` varchar(20) NOT NULL,
  `Product_Id` int NOT NULL,
  `Type` varchar(20) NOT NULL,
  PRIMARY KEY (`Tshirt_Id`),
  KEY `Product_Id` (`Product_Id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tshirt`
--

INSERT INTO `tshirt` (`Tshirt_Id`, `Size`, `Color`, `Product_Id`, `Type`) VALUES
(1, 's', 'black', 1, ''),
(2, 'm', 'white', 2, ''),
(3, 'l', 'red', 3, ''),
(4, 's', 'black', 4, ''),
(30, 'L', 'white', 136, 'V Cut'),
(29, 's', 'white', 134, 'v-neck'),
(28, 's', 'white', 133, 'v-neck'),
(27, 'l', 'black', 128, 'v-neck'),
(26, '12', 'white', 116, ''),
(25, '11', 'White', 112, '');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `User_Id` int NOT NULL AUTO_INCREMENT,
  `Mail` varchar(30) NOT NULL,
  `Pwd` varchar(20) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Address` varchar(40) NOT NULL,
  `City` varchar(20) NOT NULL,
  `Postal_Code` varchar(20) NOT NULL,
  `Country` varchar(20) NOT NULL,
  `Telephone_Number` int NOT NULL,
  `User_Type` int NOT NULL,
  `Payment_Type` varchar(20) DEFAULT NULL,
  `Card_Number` varchar(16) DEFAULT NULL,
  `Card_Name` varchar(50) DEFAULT NULL,
  `Card_Expiration_Date` varchar(10) DEFAULT NULL,
  `Security_Code` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`User_Id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`User_Id`, `Mail`, `Pwd`, `Name`, `Address`, `City`, `Postal_Code`, `Country`, `Telephone_Number`, `User_Type`, `Payment_Type`, `Card_Number`, `Card_Name`, `Card_Expiration_Date`, `Security_Code`) VALUES
(2, '', 'password2', '', 'Adresse 2', 'New York', '10001', 'United States', 0, 2, NULL, NULL, NULL, NULL, NULL),
(4, '', 'password4', '', 'Adresse 4', 'London', 'SW1A 1AA', 'United Kingdom', 0, 1, NULL, NULL, NULL, NULL, NULL),
(5, '', 'password5', '', 'Adresse 5', 'Sydney', '2000', 'Australia', 0, 2, NULL, NULL, NULL, NULL, NULL),
(6, '', 'password6', '', 'Adresse 6', 'Berlin', '10117', 'Germany', 0, 3, NULL, NULL, NULL, NULL, NULL),
(7, '', 'password7', '', 'Adresse 7', 'Toronto', 'M5H 1W2', 'Canada', 0, 1, NULL, NULL, NULL, NULL, NULL),
(8, '', 'password8', '', 'Adresse 8', 'Rome', '00184', 'Italy', 0, 2, NULL, NULL, NULL, NULL, NULL),
(9, '', 'password9', '', 'Adresse 9', 'Cape Town', '8001', 'South Africa', 0, 3, NULL, NULL, NULL, NULL, NULL),
(11, '', 'password11', '', 'Adresse 11', 'Barcelona', '08001', 'Spain', 0, 2, NULL, NULL, NULL, NULL, NULL),
(12, '', 'password12', '', 'Adresse 12', 'Moscow', '101000', 'Russia', 0, 3, NULL, NULL, NULL, NULL, NULL),
(36, 'yassinetestadd@gmail.com', '123', 'Yassine BOUTOUIL', '8 rue Xavier Lalonde', 'Gonesse', '95500', 'France', 134456141, 1, NULL, NULL, NULL, NULL, NULL),
(26, 'yassineadmin@gmail.com', '123456', 'Yassine Admin Name and surname', 'Yassine Admin Address', 'Yassine Admin City', 'Yassine Admin Postal', 'Yassine Country', 0, 3, 'MasterCard', '9876543210987654', 'Jane Smith', '06/28', '456'),
(25, 'yassinebuyer@gmail.com', 'mlnrtgomin4520', 'Yassine Boutouil', '8 rue Xavier', 'Gonesse', '95500', 'France', 2147483647, 1, 'PayPal', '1111222233334444', 'Alex Johnson', '09/23', '789'),
(24, 'yassineseller@gmail.com', '3549teadiu', 'Yassine', 'to fill', 'to fill', 'to fill', 'to fill', 0, 2, 'Visa', '1234567890123456', 'John Doe', '12/25', '123'),
(35, 'testadd@gmail.com', '147258', '', 'To fill', 'To fill', 'To fill', 'To fill', 0, 1, NULL, NULL, NULL, NULL, NULL),
(38, 'testaddwithcookie@gmail.com', 'testaddwithcookie', '', 'To fill', 'To fill', 'To fill', 'To fill', 0, 1, NULL, NULL, NULL, NULL, NULL),
(39, 'testmail@gmail.com', 'testnewuser19/07', 'Yassine Bout', '', '', '', '', 0, 1, NULL, NULL, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
