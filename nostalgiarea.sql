-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 12 jan. 2024 à 20:40
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `nostalgiarea`
--

-- --------------------------------------------------------

--
-- Structure de la table `connections`
--

DROP TABLE IF EXISTS `connections`;
CREATE TABLE IF NOT EXISTS `connections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` int NOT NULL,
  `date` datetime NOT NULL,
  `os` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `connections`
--

INSERT INTO `connections` (`id`, `user`, `date`, `os`) VALUES
(20, 41, '2024-01-12 20:34:11', 'Windows NT'),
(19, 41, '2024-01-12 20:33:51', 'Windows NT');

-- --------------------------------------------------------

--
-- Structure de la table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `firstname` text COLLATE utf8mb4_general_ci NOT NULL,
  `lastname` text COLLATE utf8mb4_general_ci NOT NULL,
  `password` text COLLATE utf8mb4_general_ci NOT NULL,
  `reassigned` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `customers`
--

INSERT INTO `customers` (`id`, `username`, `firstname`, `lastname`, `password`, `reassigned`) VALUES
(34, 'admin', 'Admin', 'Admin', 'b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86', 1),
(41, 'adelyoussouf.ay@gmail.com', 'Adel', 'YOUSSOUF ALI', '32652e99d41a5561f87c7550461f8c4f595d2a30af4048cd90c06f67251149adf3b20188bccc8b4aa9ffb6b860df4306300e5916595bebf67680f6332656112b', 1);

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `price` float NOT NULL,
  `date` datetime NOT NULL,
  `address` text COLLATE utf8mb4_general_ci NOT NULL,
  `shipping` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `price`, `date`, `address`, `shipping`) VALUES
(1, 41, 74, '2024-01-12 21:34:55', 'Address', 'DHL'),
(2, 41, 74, '2024-01-12 21:35:13', 'Address', 'DHL');

-- --------------------------------------------------------

--
-- Structure de la table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`) VALUES
(48, 2, 2, 2),
(47, 2, 8, 2),
(46, 1, 8, 2),
(45, 1, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `platform` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `image`, `platform`, `price`, `description`, `quantity`) VALUES
(2, 'Ben 10', 'Ben 10.jpg', 'psp', 20, 'Ben 10: Protector of Earth is an action-adventure video game developed by High Voltage Software and published by D3 Publisher and is based on the animated television series Ben 10. This is the second Ben 10 game following the HyperScan game, is the first official game in the franchise.', 1),
(8, 'Grand Theft Auto Liberty City Stories', 'Grand Theft Auto Liberty City Stories.jpg', 'ps2', 5, 'Grand Theft Auto: Liberty City Stories is a 2005 action-adventure game developed in a collaboration between Rockstar Leeds and Rockstar North, and published by Rockstar Games.', 519),
(9, 'Prö Evolütion Säccer 6', 'Prö Evolütion Säccer 6.jpg', 'ps2', 10, 'Pro Evolution Soccer 6, known as Winning Eleven: Pro Evolution Soccer 2007 in the United States, is a video game developed and published by Konami.', 4979),
(4, 'Crazy Taxi', 'Crazy Taxi.jpg', 'pc', 12, 'Crazy Taxi[c] is a racing video game developed by Hitmaker and published by Sega. It is the first game in the Crazy Taxi series. The game was first released in arcades in 1999 and then was ported to the Dreamcast in 2000.', 160),
(5, 'FIFA 13', 'FIFA 13.jpg', 'ps3', 18, 'FIFA 13 is a football simulation video game developed by EA Canada and published by Electronic Arts worldwide under the EA Sports label. The game was released in late September 2012 in most regions with the Japanese release being in October.', 245),
(7, 'Call of Duty Ghosts', 'Call of Duty Ghosts.jpg', 'xbox 360', 25, 'Call of Duty: Ghosts is a 2013 first-person shooter video game developed by Infinity Ward and published by Activision. It is the tenth major installment in the Call of Duty series and the sixth developed by Infinity Ward.', 350),
(10, 'Sleeping Dogs', 'Sleeping Dogs.jpg', 'xbox 360', 13, 'Sleeping Dogs is a 2012 action-adventure video game developed by United Front Games and published by Square Enix. It was released for PlayStation 3, Xbox 360 and Windows. Set in contemporary Hong Kong, the story follows martial artist and undercover police officer Wei Shen who infiltrates the Sun On Yee Triad organization.', 2599),
(11, 'FIFA 16', 'FIFA 16.jpg', 'xbox one', 20, 'FIFA 16 is a football simulation video game developed by EA Canada and published by Electronic Arts under the EA Sports label.', 1452),
(12, 'Smackdown vs Raw 2010', 'Smackdown vs Raw 2010.jpg', 'ps2', 6, 'WWE SmackDown vs. Raw 2010 (also known as WWE SmackDown vs. Raw 2010 featuring ECW) is a professional wrestling video game developed by Yuke&#039;s.', 5661),
(13, 'Wall-E', 'Wall-E.jpg', 'psp', 2, 'WALL-E (stylized as WALL·E) is a platform video game developed by Heavy Iron Studios and published by THQ, based on the 2008 film of the same name.', 2150),
(17, 'Sims 2', 'Sims 2.jpg', 'ps2', 12, 'a good game', 21);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
