-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 20, 2026 at 12:32 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lara_db_officemanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `state_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `manager_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive','closed','under_maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`id`, `name`, `code`, `description`, `email`, `phone`, `address`, `zip_code`, `country_id`, `state_id`, `city_id`, `manager_name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Reichert Inc Branch', 'BR-7224', 'Sed illum maiores fugiat placeat voluptatem quasi earum ut.', 'dietrich.emie@okuneva.com', '+1-938-615-1154', '6224 Lowe Parkway Suite 663', '98488', NULL, NULL, NULL, 'Miss Nora Reilly DDS', 'active', '2026-10-03 04:15:48', '2026-10-03 04:15:48', NULL),
(2, 'Stanton Ltd Branch', 'BR-3504', 'Doloribus veniam dicta numquam iure aut.', 'alta55@barrows.biz', '1-973-632-5426', '93264 Lueilwitz Cape Apt. 855', '41686-7746', NULL, NULL, NULL, 'Miss Makayla Hegmann IV', 'active', '2026-10-03 04:15:48', '2026-10-03 04:15:48', NULL),
(3, 'Schaefer-Lind Branch', 'BR-5429', 'Voluptas earum dolor et repudiandae.', 'cassin.kaleigh@carroll.com', '309.693.1166', '9707 Quigley Burg Suite 374', '55324-2333', NULL, NULL, NULL, 'Dr. Damion Mayer I', 'active', '2026-10-03 04:15:49', '2026-10-03 04:15:49', NULL),
(4, 'Gaylord-Graham Branch', 'BR-4421', 'Distinctio quia facilis necessitatibus recusandae magnam explicabo quaerat.', 'ejohns@nolan.com', '541-401-1086', '6284 Diego Mission Apt. 326', '48528', NULL, NULL, NULL, 'Kali Breitenberg', 'active', '2026-10-03 04:15:49', '2026-10-03 04:15:49', NULL),
(5, 'Mitchell LLC Branch', 'BR-0290', 'Nostrum aut est laborum sed soluta.', 'lucius06@abbott.com', '+1-940-910-5534', '5911 Valentine Crescent', '86773-0189', NULL, NULL, NULL, 'Kali Fay', 'active', '2026-10-03 04:15:49', '2026-10-03 04:15:49', NULL),
(6, 'Schulist, Bartell and Hahn Branch', 'BR-4164', 'Ducimus minus veritatis voluptatibus nihil molestiae.', 'della.blick@lindgren.com', '820.384.7986', '51518 Hill Lakes', '32746-4735', NULL, NULL, NULL, 'Steve Hammes', 'active', '2026-10-03 04:15:49', '2026-10-03 04:15:49', NULL),
(7, 'Casper, Kris and Herzog Branch', 'BR-5331', 'Rem nisi qui et voluptas.', 'miguel.erdman@nienow.org', '1-657-970-3799', '771 Blick Drive', '97295-2343', NULL, NULL, NULL, 'Prof. Cecilia Hegmann', 'active', '2026-10-03 04:15:49', '2026-10-03 04:15:49', NULL),
(8, 'Bruen, Ernser and O\'Hara Branch', 'BR-2124', 'Alias aut odio dolor magnam dolorem sapiente.', 'warren.thiel@runte.com', '828-581-2644', '839 Stanton Ford Suite 076', '13992', NULL, NULL, NULL, 'Jefferey Borer', 'active', '2026-10-03 04:15:49', '2026-10-03 04:15:49', NULL),
(9, 'O\'Connell Ltd Branch', 'BR-9855', 'Debitis aut et molestiae commodi sit consectetur rerum.', 'omayert@williamson.com', '1-380-845-2815', '5129 Walter Expressway', '23765-7737', NULL, NULL, NULL, 'Dr. Nyasia Klein DVM', 'active', '2026-10-03 04:15:49', '2026-10-03 04:15:49', NULL),
(10, 'Pouros and Sons Branch', 'BR-1495', 'Asperiores distinctio ad eos alias est accusamus quia.', 'marianna.ondricka@smith.com', '615.632.8696', '51077 Blick Cliffs Apt. 847', '18089', NULL, NULL, NULL, 'Miss Linnie Toy', 'active', '2026-10-03 04:15:49', '2026-10-03 04:15:49', NULL),
(11, 'Waters-Gulgowski Branch', 'BR-0085', 'Reiciendis eius harum corrupti ut dolorem.', 'adoyle@dibbert.com', '386-433-6380', '4555 Torey Squares', '48249-1711', NULL, NULL, NULL, 'Prof. Kody Hintz MD', 'active', '2026-10-03 04:15:49', '2026-10-03 04:15:49', NULL),
(12, 'Muller-O\'Reilly Branch', 'BR-9878', 'Velit consequuntur atque ipsa omnis cumque perspiciatis.', 'johns.gideon@bailey.com', '308-631-3823', '209 Baumbach Dam', '94932-6542', NULL, NULL, NULL, 'Bennett Kemmer', 'active', '2026-10-03 04:15:49', '2026-10-03 04:15:49', NULL),
(13, 'Purdy Ltd Branch', 'BR-0168', 'Quod in sint saepe beatae.', 'flemke@huel.com', '(551) 562-1678', '46439 Bins Mission Apt. 166', '02920-4638', NULL, NULL, NULL, 'Maximus Rau', 'active', '2026-10-03 04:15:49', '2026-10-03 04:15:49', NULL),
(14, 'Moore-VonRueden Branch', 'BR-7807', 'Molestiae dignissimos aspernatur modi rem quas.', 'ned96@kunde.com', '+14756025614', '45432 Russel Light Suite 521', '18601-8547', NULL, NULL, NULL, 'Michel Walsh III', 'active', '2026-10-03 04:15:49', '2026-10-03 04:15:49', NULL),
(15, 'Windler, Gottlieb and O\'Conner Branch', 'BR-9071', 'Maiores sed exercitationem consequatur ex in autem necessitatibus.', 'luettgen.mandy@abernathy.com', '281-431-3739', '62327 Parker Garden', '23729', NULL, NULL, NULL, 'Dr. Blake Armstrong III', 'active', '2026-10-03 04:15:50', '2026-10-03 04:15:50', NULL),
(16, 'Hahn-Rodriguez Branch', 'BR-4024', 'Voluptatem et non eius a rerum nesciunt.', 'tgleichner@will.info', '+1 (480) 359-3093', '332 Braeden Villages Suite 534', '44147', NULL, NULL, NULL, 'Frida Aufderhar', 'active', '2026-10-03 04:15:50', '2026-10-03 04:15:50', NULL),
(17, 'Muller-Lowe Branch', 'BR-2166', 'Aut impedit sunt eius repudiandae aut.', 'marvin.emmalee@cartwright.com', '+1-938-818-6366', '79297 Feil Club Suite 232', '61395', NULL, NULL, NULL, 'Pink Armstrong', 'active', '2026-10-03 04:15:50', '2026-10-03 04:15:50', NULL),
(18, 'Howell Group Branch', 'BR-9660', 'Et occaecati non deleniti at sapiente.', 'metz.myriam@douglas.com', '(210) 689-8121', '740 Conroy Path Apt. 492', '13040', NULL, NULL, NULL, 'Verdie Turcotte IV', 'active', '2026-10-03 04:15:50', '2026-10-03 04:15:50', NULL),
(19, 'Cassin, Dooley and Rosenbaum Branch', 'BR-2490', 'Facilis ex inventore exercitationem autem tenetur.', 'nwiza@breitenberg.com', '+1-417-605-5798', '782 Alf Glens', '67659-1236', NULL, NULL, NULL, 'Mrs. Rosina Greenholt', 'active', '2026-10-03 04:15:50', '2026-10-03 04:15:50', NULL),
(20, 'Leannon, Bednar and Hegmann Branch', 'BR-2720', 'Consequuntur facere minima enim eos aut et excepturi et.', 'gina87@collins.com', '1-325-627-7269', '2784 Rogers Curve Suite 384', '87691', NULL, NULL, NULL, 'Evans O\'Reilly', 'active', '2026-10-03 04:15:50', '2026-10-03 04:15:50', NULL),
(21, 'Hills Inc Branch', 'BR-8023', 'Hic quidem natus quaerat enim iure voluptatibus.', 'concepcion16@example.net', '1-930-203-0805', '7386 Schuyler View Suite 040', '07769-7130', NULL, NULL, NULL, 'Mrs. Petra Lehner DDS', 'active', '2026-10-03 04:27:22', '2026-10-03 04:27:22', NULL),
(22, 'Braun, Stroman and Renner Branch', 'BR-6020', 'Magnam explicabo exercitationem corrupti adipisci omnis.', 'moriah.feest@example.net', '(754) 370-2657', '2418 Foster Camp Suite 649', '70346-1867', NULL, NULL, NULL, 'Lenore Batz', 'active', '2026-10-03 04:27:22', '2026-10-03 04:27:22', NULL),
(23, 'Pacocha Inc Branch', 'BR-8569', 'A est est quidem occaecati quia nulla sed aspernatur.', 'hudson.veum@example.org', '720-578-8343', '432 Feil Crossing Suite 443', '45907', NULL, NULL, NULL, 'Albina Feeney', 'active', '2026-10-03 04:27:22', '2026-10-03 04:27:22', NULL),
(24, 'Bernier-Lemke Branch', 'BR-2823', 'Dolorem aspernatur quidem accusamus voluptatem eaque.', 'tadams@example.org', '669-682-1921', '51394 Robel Trafficway', '73015', NULL, NULL, NULL, 'Abbie Brakus', 'active', '2026-10-03 04:27:23', '2026-10-03 04:27:23', NULL),
(25, 'Terry-Labadie Branch', 'BR-9951', 'Et beatae omnis eos ut.', 'ehagenes@example.org', '(539) 227-7177', '4092 Hessel Station', '51783-9984', NULL, NULL, NULL, 'Dr. Carolanne Howe I', 'active', '2026-10-03 04:27:23', '2026-10-03 04:27:23', NULL),
(26, 'Walker PLC Branch', 'BR-6096', 'Perferendis consequatur eius tempora dolore et perspiciatis.', 'bethany32@example.net', '531-844-9427', '418 DuBuque Grove Apt. 708', '58873-2294', NULL, NULL, NULL, 'Desmond Christiansen', 'active', '2026-10-03 04:27:23', '2026-10-03 04:27:23', NULL),
(27, 'Jakubowski Group Branch', 'BR-8511', 'Non aperiam repudiandae ex.', 'cryan@example.org', '+1-814-781-6140', '8793 O\'Kon Circles', '51062-5360', NULL, NULL, NULL, 'Dawson Collier PhD', 'active', '2026-10-03 04:27:23', '2026-10-03 04:27:23', NULL),
(28, 'Crooks and Sons Branch', 'BR-3647', 'Dolor et illo numquam.', 'collins.electa@example.com', '341-760-9408', '85374 Aida Village', '26453', NULL, NULL, NULL, 'Adelle Larson', 'active', '2026-10-03 04:27:23', '2026-10-03 04:27:23', NULL),
(29, 'Bahringer-Brakus Branch', 'BR-8009', 'Nostrum perspiciatis et sit totam.', 'efren.mohr@example.org', '+1-931-420-4258', '743 Deja Summit Suite 395', '03779', NULL, NULL, NULL, 'Camren Jaskolski MD', 'active', '2026-10-03 04:27:23', '2026-10-03 04:27:23', NULL),
(30, 'Rippin Inc Branch', 'BR-6488', 'Commodi id incidunt minus explicabo.', 'morgan47@example.org', '+1-616-710-1181', '716 Carleton Mall Suite 620', '71592-5060', 23, NULL, NULL, 'Jennifer Kerluke I', 'active', '2026-10-03 04:27:24', '2026-10-13 11:01:19', NULL),
(31, 'Balistreri-Homenick Branch', 'BR-0585', 'Minima vel et est omnis occaecati qui veritatis.', 'torrance49@example.net', '743.441.5230', '5175 Friedrich Lodge Suite 055', '23703-2310', NULL, NULL, NULL, 'Frank Luettgen IV', 'active', '2026-10-03 04:27:24', '2026-10-03 04:27:24', NULL),
(32, 'Zemlak, Ferry and Hirthe Branch', 'BR-5388', 'Necessitatibus exercitationem dolores maxime voluptate tempore aliquam expedita repellat.', 'ialtenwerth@example.net', '+15059539092', '53935 Shaniya Corners Apt. 538', '26171', NULL, NULL, NULL, 'Jordan Hartmann MD', 'active', '2026-10-03 04:27:24', '2026-10-03 04:27:24', NULL),
(33, 'Hermann, Stroman and Beatty Branch', 'BR-3862', 'Qui aspernatur reprehenderit amet ut asperiores.', 'lydia09@example.com', '781-330-6200', '1974 Medhurst Courts Suite 193', '45720-6011', NULL, NULL, NULL, 'Ms. Viviane Wuckert', 'active', '2026-10-03 04:27:24', '2026-10-03 04:27:24', NULL),
(34, 'Fisher-Haag Branch', 'BR-6789', 'Dicta rerum id modi nisi omnis.', 'bettie19@example.net', '+1.434.855.5095', '82043 Wilderman Dale Suite 431', '28946', NULL, NULL, NULL, 'Dr. Arno Bailey', 'active', '2026-10-03 04:27:24', '2026-10-03 04:27:24', NULL),
(35, 'Gislason, Blanda and Predovic Branch', 'BR-6965', 'Quia qui eum nihil.', 'bailee77@example.net', '+1.321.468.7558', '8464 Waters Canyon Suite 004', '87784', NULL, NULL, NULL, 'Darius Bartell DDS', 'active', '2026-10-03 04:27:24', '2026-10-03 04:27:24', NULL),
(36, 'Nienow Inc Branch', 'BR-9840', 'Dolore fuga nam et eos enim ipsam blanditiis.', 'emerald.terry@example.com', '(716) 366-7232', '48458 Earl Village Apt. 583', '20786', NULL, NULL, NULL, 'Denis Hoppe', 'active', '2026-10-03 04:27:24', '2026-10-03 04:27:24', NULL),
(37, 'Hamill Inc Branch', 'BR-0296', 'Qui vero totam et aut expedita error quibusdam voluptas.', 'esperanza.grady@example.org', '(309) 588-2612', '980 Wunsch Inlet Apt. 077', '88681-4917', NULL, NULL, NULL, 'Miss Vivian Grant PhD', 'active', '2026-10-03 04:27:24', '2026-10-03 04:27:24', NULL),
(38, 'Douglas Group Branch', 'BR-9350', 'Ut dolores quos consequatur.', 'ajones@example.net', '1-857-958-4217', '491 Kutch Prairie Suite 450', '37577', NULL, NULL, NULL, 'Ova Stroman', 'active', '2026-10-03 04:27:24', '2026-10-03 04:27:24', NULL),
(39, 'Aufderhar PLC Branch', 'BR-1669', 'A dolore aut placeat ut animi.', 'kassulke.korbin@example.net', '+1 (520) 362-7760', '7289 Daniel River', '97336', NULL, NULL, NULL, 'Pete Boehm', 'active', '2026-10-03 04:27:24', '2026-10-03 04:27:24', NULL),
(40, 'O\'Kon, Senger and Hayes Branch', 'BR-9736', 'Non non ut enim commodi sed.', 'sjones@example.com', '941-918-1011', '9842 Crona Tunnel', '14590', NULL, NULL, NULL, 'Marquise Becker', 'active', '2026-10-03 04:27:24', '2026-10-03 04:27:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `state_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Porterport', 'Et est ipsum voluptatem natus est velit omnis nobis modi ratione qui eos.', '2026-10-03 04:13:45', '2026-10-03 04:13:45'),
(2, 13, 'Lake Augustusview', 'Sunt quasi debitis deserunt quia est atque nobis quo et ex necessitatibus et distinctio vel.', '2026-10-03 04:13:46', '2026-10-03 04:13:46'),
(3, 7, 'Everetteport', 'Reprehenderit sit et consequatur et molestias eaque aliquam.', '2026-10-03 04:13:46', '2026-10-03 04:13:46'),
(4, 1, 'New Hayley', 'Sed velit hic rerum dolores officiis mollitia aliquid cupiditate sapiente animi odio a vel est.', '2026-10-03 04:13:46', '2026-10-03 04:13:46'),
(5, 5, 'Elodyton', 'Adipisci ut quod numquam itaque est eos voluptatem fugiat vel facilis.', '2026-10-03 04:13:46', '2026-10-03 04:13:46'),
(6, 15, 'West Orloland', 'Esse molestias fugit alias sint iure perspiciatis qui tempora harum quis unde qui voluptatem impedit sint.', '2026-10-03 04:13:46', '2026-10-03 04:13:46'),
(7, 10, 'Darrylberg', 'Ipsa atque expedita a est sed voluptatem impedit voluptatum et nesciunt consequatur qui illo.', '2026-10-03 04:13:46', '2026-10-03 04:13:46'),
(8, 19, 'New Erin', 'Laborum vitae error sint ut corrupti alias sit recusandae hic expedita beatae rerum et sint voluptas iste.', '2026-10-03 04:13:46', '2026-10-03 04:13:46'),
(9, 7, 'Steuberview', 'Quis fugit velit occaecati quia officia aut nesciunt saepe maxime et.', '2026-10-03 04:13:46', '2026-10-03 04:13:46'),
(10, 3, 'New Delta', 'Maxime ut sunt voluptatibus consequatur sit aut esse sed.', '2026-10-03 04:13:47', '2026-10-03 04:13:47'),
(11, 16, 'Port Lilian', 'Possimus sint sed aliquam esse porro dolorum enim fugiat hic consectetur eius quo.', '2026-10-03 04:13:47', '2026-10-03 04:13:47'),
(12, 8, 'Roobville', 'Sapiente nesciunt voluptatem ut cum excepturi voluptatem ratione iusto quidem quam et similique quos itaque odit.', '2026-10-03 04:13:47', '2026-10-03 04:13:47'),
(13, 6, 'South Josephineborough', 'Incidunt aut accusamus autem maxime accusantium blanditiis laborum sit.', '2026-10-03 04:13:47', '2026-10-03 04:13:47'),
(14, 2, 'Port Jeffery', 'Explicabo voluptatibus tempore eaque adipisci assumenda qui rerum aperiam totam sint earum at.', '2026-10-03 04:13:48', '2026-10-03 04:13:48'),
(15, 16, 'South Ruthton', 'Nisi et et et qui est impedit nostrum qui praesentium accusantium laudantium quibusdam quia excepturi ea deserunt.', '2026-10-03 04:13:48', '2026-10-03 04:13:48'),
(16, 15, 'West Jessieburgh', 'Quibusdam expedita unde labore soluta labore asperiores culpa minima vel ut.', '2026-10-03 04:13:48', '2026-10-03 04:13:48'),
(17, 1, 'Lake Bradburgh', 'Et eius provident minus vero eaque eum accusantium laboriosam dolor repellat est sed exercitationem.', '2026-10-03 04:13:48', '2026-10-03 04:13:48'),
(18, 10, 'South Jedediah', 'Autem autem dolores velit sapiente doloremque eaque cumque delectus blanditiis.', '2026-10-03 04:13:48', '2026-10-03 04:13:48'),
(19, 20, 'New Mitchel', 'Nobis omnis modi officiis qui animi omnis officia sed eligendi.', '2026-10-03 04:13:48', '2026-10-03 04:13:48'),
(20, 6, 'Ernsermouth', 'Aliquam consequatur aliquid sed numquam sint unde similique.', '2026-10-03 04:13:48', '2026-10-03 04:13:48'),
(21, 2, 'Kuhlmanborough', 'Quidem tempora dolores autem nam nostrum magnam ad tempore et magnam rerum.', '2026-10-03 04:13:49', '2026-10-03 04:13:49'),
(22, 19, 'Cordellview', 'Deserunt tenetur iure impedit earum earum qui maiores qui odit hic dolore.', '2026-10-03 04:13:49', '2026-10-03 04:13:49'),
(23, 17, 'Schmidtshire', 'Quo dolorem aliquid dolor maiores repellat tempore ab voluptates quas quos assumenda.', '2026-10-03 04:13:49', '2026-10-03 04:13:49'),
(24, 7, 'Madisynmouth', 'Eos blanditiis nam necessitatibus nesciunt quo perferendis illo corporis velit sit at porro minima omnis.', '2026-10-03 04:13:49', '2026-10-03 04:13:49'),
(25, 12, 'Marquisemouth', 'Repellat rerum esse magnam sit dolor autem dicta molestiae quas ut.', '2026-10-03 04:13:49', '2026-10-03 04:13:49'),
(26, 18, 'Lake Katlynn', 'Doloribus ad beatae eius et facilis eos iusto blanditiis.', '2026-10-03 04:13:50', '2026-10-03 04:13:50'),
(27, 5, 'Laurianneview', 'Est magni iusto quas mollitia aut saepe voluptatibus aspernatur eveniet voluptates debitis odio ex accusantium.', '2026-10-03 04:13:50', '2026-10-03 04:13:50'),
(28, 13, 'East Theresia', 'Consequatur animi ipsam voluptas repudiandae doloribus consectetur sunt et quisquam quos aut voluptatem.', '2026-10-03 04:13:50', '2026-10-03 04:13:50'),
(29, 19, 'Abigailbury', 'Illo labore non atque quia placeat rem magni et soluta.', '2026-10-03 04:13:51', '2026-10-03 04:13:51'),
(30, 16, 'East Heber', 'Voluptate odit totam ipsam dolores numquam ratione illum minus aliquam ipsam est eum magnam magnam sit ad.', '2026-10-03 04:13:51', '2026-10-03 04:13:51'),
(31, 14, 'Kirstenmouth', 'Illo et qui beatae cum quis quae a.', '2026-10-03 04:13:51', '2026-10-03 04:13:51'),
(32, 8, 'Faheytown', 'Labore non accusantium dolor vel excepturi labore voluptatibus sit asperiores aliquid ab et.', '2026-10-03 04:13:51', '2026-10-03 04:13:51'),
(33, 17, 'Saigeville', 'Tenetur ea ducimus veritatis enim quia omnis et adipisci repudiandae quia perspiciatis sapiente expedita.', '2026-10-03 04:13:51', '2026-10-03 04:13:51'),
(34, 4, 'Lake Dorianland', 'Dolorum alias ut rerum id quia quidem veritatis ducimus modi commodi alias architecto accusantium.', '2026-10-03 04:13:51', '2026-10-03 04:13:51'),
(35, 3, 'Beckerborough', 'Sunt asperiores nam et debitis voluptas praesentium voluptatem.', '2026-10-03 04:13:51', '2026-10-03 04:13:51'),
(36, 10, 'West Maverick', 'Consequuntur quis odio harum sint sed ut cupiditate laudantium dignissimos ullam sint dolore aut harum quia corrupti.', '2026-10-03 04:13:52', '2026-10-03 04:13:52'),
(37, 13, 'Port Bobbie', 'Illum qui atque velit consectetur consequatur reiciendis commodi.', '2026-10-03 04:13:52', '2026-10-03 04:13:52'),
(38, 1, 'South Mylene', 'Rerum quos in ea rem ea eligendi ab illo.', '2026-10-03 04:13:52', '2026-10-03 04:13:52'),
(39, 7, 'East Tyrashire', 'Minus eos qui et voluptas qui consequuntur id sint nesciunt fugiat asperiores a quia.', '2026-10-03 04:13:52', '2026-10-03 04:13:52'),
(40, 19, 'Steviefort', 'Ipsa natus suscipit quia aut ex et qui ducimus dolorem.', '2026-10-03 04:13:52', '2026-10-03 04:13:52'),
(41, 9, 'Lake Estell', 'Incidunt ad at et inventore placeat molestiae consequatur dolorem accusamus ipsam.', '2026-10-03 04:13:53', '2026-10-03 04:13:53'),
(42, 20, 'Hartmanntown', 'Autem sed voluptas eligendi rerum nihil eos cum incidunt.', '2026-10-03 04:13:53', '2026-10-03 04:13:53'),
(43, 17, 'Lake Paul', 'Quidem magnam qui dicta et dolore ut consequatur nemo labore.', '2026-10-03 04:13:53', '2026-10-03 04:13:53'),
(44, 16, 'Pourosport', 'Non deleniti nobis dolor perferendis nam corrupti ut numquam dignissimos possimus cupiditate non non.', '2026-10-03 04:13:54', '2026-10-03 04:13:54'),
(45, 13, 'Martinview', 'Aut et commodi vitae facilis et explicabo harum at voluptates esse et facilis dignissimos assumenda quisquam quae.', '2026-10-03 04:13:54', '2026-10-03 04:13:54'),
(46, 14, 'Rodriguezstad', 'Fuga omnis mollitia voluptatem dolores est excepturi fugit excepturi dolore.', '2026-10-03 04:13:55', '2026-10-03 04:13:55'),
(47, 18, 'Port Shannaside', 'Non repellat dolor error non et rerum quo nobis ipsum possimus qui nesciunt.', '2026-10-03 04:13:55', '2026-10-03 04:13:55'),
(48, 4, 'Handhaven', 'Eum hic non sint ut ad similique hic et sit reiciendis sint accusamus magni nihil.', '2026-10-03 04:13:55', '2026-10-03 04:13:55'),
(49, 16, 'South Mariaburgh', 'Voluptates tenetur dolores nam accusamus magni dignissimos ex autem qui molestias repellat omnis.', '2026-10-03 04:13:55', '2026-10-03 04:13:55'),
(50, 19, 'West Jermey', 'Eligendi ab iure ut et quia fugiat dolores numquam sit libero velit sunt minus eveniet est voluptas.', '2026-10-03 04:13:55', '2026-10-03 04:13:55');

-- --------------------------------------------------------

--
-- Table structure for table `clients_infos`
--

CREATE TABLE `clients_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternate_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `status` enum('active','inactive','prospect','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_code`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'AD', 'Andorra', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(2, 'AE', 'United Arab Emirates', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(3, 'AF', 'Afghanistan', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(4, 'AG', 'Antigua & Barbuda', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(5, 'AI', 'Anguilla', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(6, 'AL', 'Albania', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(7, 'AM', 'Armenia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(8, 'AO', 'Angola', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(9, 'AQ', 'Antarctica', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(10, 'AR', 'Argentina', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(11, 'AS', 'Samoa (American)', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(12, 'AT', 'Austria', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(13, 'AU', 'Australia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(14, 'AW', 'Aruba', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(15, 'AX', 'Åland Islands', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(16, 'AZ', 'Azerbaijan', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(17, 'BA', 'Bosnia & Herzegovina', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(18, 'BB', 'Barbados', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(19, 'BD', 'Bangladesh', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(20, 'BE', 'Belgium', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(21, 'BF', 'Burkina Faso', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(22, 'BG', 'Bulgaria', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(23, 'BH', 'Bahrain', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(24, 'BI', 'Burundi', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(25, 'BJ', 'Benin', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(26, 'BL', 'St Barthelemy', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(27, 'BM', 'Bermuda', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(28, 'BN', 'Brunei', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(29, 'BO', 'Bolivia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(30, 'BQ', 'Caribbean NL', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(31, 'BR', 'Brazil', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(32, 'BS', 'Bahamas', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(33, 'BT', 'Bhutan', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(34, 'BV', 'Bouvet Island', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(35, 'BW', 'Botswana', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(36, 'BY', 'Belarus', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(37, 'BZ', 'Belize', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(38, 'CA', 'Canada', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(39, 'CC', 'Cocos (Keeling) Islands', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(40, 'CD', 'Congo (Dem. Rep.)', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(41, 'CF', 'Central African Rep.', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(42, 'CG', 'Congo (Rep.)', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(43, 'CH', 'Switzerland', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(44, 'CI', 'Côte d’Ivoire', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(45, 'CK', 'Cook Islands', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(46, 'CL', 'Chile', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(47, 'CM', 'Cameroon', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(48, 'CN', 'China', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(49, 'CO', 'Colombia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(50, 'CR', 'Costa Rica', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(51, 'CU', 'Cuba', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(52, 'CV', 'Cape Verde', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(53, 'CW', 'Curaçao', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(54, 'CX', 'Christmas Island', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(55, 'CY', 'Cyprus', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(56, 'CZ', 'Czech Republic', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(57, 'DE', 'Germany', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(58, 'DJ', 'Djibouti', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(59, 'DK', 'Denmark', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(60, 'DM', 'Dominica', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(61, 'DO', 'Dominican Republic', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(62, 'DZ', 'Algeria', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(63, 'EC', 'Ecuador', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(64, 'EE', 'Estonia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(65, 'EG', 'Egypt', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(66, 'EH', 'Western Sahara', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(67, 'ER', 'Eritrea', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(68, 'ES', 'Spain', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(69, 'ET', 'Ethiopia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(70, 'FI', 'Finland', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(71, 'FJ', 'Fiji', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(72, 'FK', 'Falkland Islands', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(73, 'FM', 'Micronesia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(74, 'FO', 'Faroe Islands', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(75, 'FR', 'France', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(76, 'GA', 'Gabon', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(77, 'GB', 'United Kingdom', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(78, 'GD', 'Grenada', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(79, 'GE', 'Georgia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(80, 'GF', 'French Guiana', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(81, 'GG', 'Guernsey', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(82, 'GH', 'Ghana', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(83, 'GI', 'Gibraltar', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(84, 'GL', 'Greenland', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(85, 'GM', 'Gambia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(86, 'GN', 'Guinea', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(87, 'GP', 'Guadeloupe', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(88, 'GQ', 'Equatorial Guinea', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(89, 'GR', 'Greece', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(90, 'GS', 'South Georgia & the South Sandwich Islands', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(91, 'GT', 'Guatemala', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(92, 'GU', 'Guam', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(93, 'GW', 'Guinea-Bissau', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(94, 'GY', 'Guyana', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(95, 'HK', 'Hong Kong', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(96, 'HM', 'Heard Island & McDonald Islands', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(97, 'HN', 'Honduras', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(98, 'HR', 'Croatia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(99, 'HT', 'Haiti', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(100, 'HU', 'Hungary', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(101, 'ID', 'Indonesia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(102, 'IE', 'Ireland', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(103, 'IL', 'Israel', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(104, 'IM', 'Isle of Man', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(105, 'IN', 'India', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(106, 'IO', 'British Indian Ocean Territory', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(107, 'IQ', 'Iraq', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(108, 'IR', 'Iran', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(109, 'IS', 'Iceland', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(110, 'IT', 'Italy', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(111, 'JE', 'Jersey', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(112, 'JM', 'Jamaica', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(113, 'JO', 'Jordan', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(114, 'JP', 'Japan', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(115, 'KE', 'Kenya', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(116, 'KG', 'Kyrgyzstan', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(117, 'KH', 'Cambodia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(118, 'KI', 'Kiribati', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(119, 'KM', 'Comoros', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(120, 'KN', 'St Kitts & Nevis', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(121, 'KP', 'North Korea', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(122, 'KR', 'South Korea', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(123, 'KW', 'Kuwait', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(124, 'KY', 'Cayman Islands', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(125, 'KZ', 'Kazakhstan', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(126, 'LA', 'Laos', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(127, 'LB', 'Lebanon', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(128, 'LC', 'St Lucia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(129, 'LI', 'Liechtenstein', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(130, 'LK', 'Sri Lanka', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(131, 'LR', 'Liberia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(132, 'LS', 'Lesotho', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(133, 'LT', 'Lithuania', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(134, 'LU', 'Luxembourg', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(135, 'LV', 'Latvia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(136, 'LY', 'Libya', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(137, 'MA', 'Morocco', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(138, 'MC', 'Monaco', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(139, 'MD', 'Moldova', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(140, 'ME', 'Montenegro', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(141, 'MF', 'St Martin (French)', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(142, 'MG', 'Madagascar', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(143, 'MH', 'Marshall Islands', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(144, 'MK', 'North Macedonia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(145, 'ML', 'Mali', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(146, 'MM', 'Myanmar (Burma)', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(147, 'MN', 'Mongolia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(148, 'MO', 'Macau', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(149, 'MP', 'Northern Mariana Islands', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(150, 'MQ', 'Martinique', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(151, 'MR', 'Mauritania', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(152, 'MS', 'Montserrat', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(153, 'MT', 'Malta', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(154, 'MU', 'Mauritius', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(155, 'MV', 'Maldives', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(156, 'MW', 'Malawi', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(157, 'MX', 'Mexico', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(158, 'MY', 'Malaysia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(159, 'MZ', 'Mozambique', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(160, 'NA', 'Namibia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(161, 'NC', 'New Caledonia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(162, 'NE', 'Niger', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(163, 'NF', 'Norfolk Island', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(164, 'NG', 'Nigeria', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(165, 'NI', 'Nicaragua', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(166, 'NL', 'Netherlands', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(167, 'NO', 'Norway', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(168, 'NP', 'Nepal', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(169, 'NR', 'Nauru', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(170, 'NU', 'Niue', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(171, 'NZ', 'New Zealand', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(172, 'OM', 'Oman', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(173, 'PA', 'Panama', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(174, 'PE', 'Peru', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(175, 'PF', 'French Polynesia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(176, 'PG', 'Papua New Guinea', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(177, 'PH', 'Philippines', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(178, 'PK', 'Pakistan', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(179, 'PL', 'Poland', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(180, 'PM', 'St Pierre & Miquelon', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(181, 'PN', 'Pitcairn', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(182, 'PR', 'Puerto Rico', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(183, 'PS', 'Palestine', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(184, 'PT', 'Portugal', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(185, 'PW', 'Palau', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(186, 'PY', 'Paraguay', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(187, 'QA', 'Qatar', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(188, 'RE', 'Réunion', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(189, 'RO', 'Romania', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(190, 'RS', 'Serbia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(191, 'RU', 'Russia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(192, 'RW', 'Rwanda', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(193, 'SA', 'Saudi Arabia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(194, 'SB', 'Solomon Islands', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(195, 'SC', 'Seychelles', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(196, 'SD', 'Sudan', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(197, 'SE', 'Sweden', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(198, 'SG', 'Singapore', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(199, 'SH', 'St Helena', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(200, 'SI', 'Slovenia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(201, 'SJ', 'Svalbard & Jan Mayen', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(202, 'SK', 'Slovakia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(203, 'SL', 'Sierra Leone', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(204, 'SM', 'San Marino', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(205, 'SN', 'Senegal', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(206, 'SO', 'Somalia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(207, 'SR', 'Suriname', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(208, 'SS', 'South Sudan', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(209, 'ST', 'Sao Tome & Principe', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(210, 'SV', 'El Salvador', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(211, 'SX', 'St Maarten (Dutch)', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(212, 'SY', 'Syria', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(213, 'SZ', 'Eswatini (Swaziland)', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(214, 'TC', 'Turks & Caicos Is', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(215, 'TD', 'Chad', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(216, 'TF', 'French S. Terr.', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(217, 'TG', 'Togo', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(218, 'TH', 'Thailand', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(219, 'TJ', 'Tajikistan', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(220, 'TK', 'Tokelau', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(221, 'TL', 'East Timor', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(222, 'TM', 'Turkmenistan', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(223, 'TN', 'Tunisia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(224, 'TO', 'Tonga', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(225, 'TR', 'Turkey', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(226, 'TT', 'Trinidad & Tobago', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(227, 'TV', 'Tuvalu', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(228, 'TW', 'Taiwan', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(229, 'TZ', 'Tanzania', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(230, 'UA', 'Ukraine', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(231, 'UG', 'Uganda', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(232, 'UM', 'US minor outlying islands', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(233, 'US', 'United States', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(234, 'UY', 'Uruguay', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(235, 'UZ', 'Uzbekistan', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(236, 'VA', 'Vatican City', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(237, 'VC', 'St Vincent', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(238, 'VE', 'Venezuela', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(239, 'VG', 'Virgin Islands (UK)', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(240, 'VI', 'Virgin Islands (US)', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(241, 'VN', 'Vietnam', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(242, 'VU', 'Vanuatu', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(243, 'WF', 'Wallis & Futuna', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(244, 'WS', 'Samoa (western)', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(245, 'YE', 'Yemen', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(246, 'YT', 'Mayotte', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(247, 'ZA', 'South Africa', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(248, 'ZM', 'Zambia', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(249, 'ZW', 'Zimbabwe', NULL, '2026-10-03 04:08:40', '2026-10-03 04:08:40'),
(256, 'DD', 'DD', NULL, '2026-10-05 08:43:05', '2026-10-05 08:43:05');

-- --------------------------------------------------------

--
-- Table structure for table `daily_logs`
--

CREATE TABLE `daily_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `logged_at` datetime NOT NULL,
  `metric` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` double NOT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
PARTITION BY RANGE (to_days(`logged_at`))
(
PARTITION p2025_01 VALUES LESS THAN (739648) ENGINE=InnoDB,
PARTITION p2025_02 VALUES LESS THAN (739676) ENGINE=InnoDB,
PARTITION p2025_03 VALUES LESS THAN (739707) ENGINE=InnoDB,
PARTITION p2026_12 VALUES LESS THAN (740316) ENGINE=InnoDB,
PARTITION pmax VALUES LESS THAN MAXVALUE ENGINE=InnoDB
);

-- --------------------------------------------------------

--
-- Table structure for table `daily_logs_summary`
--

CREATE TABLE `daily_logs_summary` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `metric` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bucket` date NOT NULL,
  `samples` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total` double NOT NULL DEFAULT 0,
  `average` double NOT NULL DEFAULT 0,
  `min_value` double DEFAULT NULL,
  `max_value` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `floor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `floor`, `status`, `manager_id`, `created_at`, `updated_at`) VALUES
(1, 'Human Resources', 'Manages recruitment, employee relations, benefits, and company culture.', '2nd Floor', 1, NULL, '2026-10-03 04:14:30', '2026-10-03 04:14:30'),
(2, 'Engineering', 'Builds and maintains the product, platform, and infrastructure.', '3rd Floor', 1, NULL, '2026-10-03 04:27:34', '2026-10-03 04:27:34'),
(3, 'Sales', 'Drives revenue through customer acquisition, retention, and partnerships.', '1st Floor', 1, NULL, '2026-10-03 05:37:24', '2026-10-03 05:37:24'),
(4, 'Marketing', 'Owns brand, campaigns, content, and demand generation.', '1st Floor', 1, NULL, '2026-10-03 05:37:24', '2026-10-03 05:37:24'),
(5, 'Finance', 'Handles accounting, payroll, budgeting, and financial reporting.', '4th Floor', 1, NULL, '2026-10-03 05:37:25', '2026-10-03 05:37:25'),
(6, 'Customer Support', 'Provides 24/7 support to customers across all channels.', '2nd Floor', 1, NULL, '2026-10-03 05:37:25', '2026-10-03 05:37:25');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `emp_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hired_date` date DEFAULT NULL,
  `confirmation_date` date DEFAULT NULL,
  `termination_date` date DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `state_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','on_leave','suspended','resigned','terminated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `job_profile_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `emp_code`, `designation`, `salary`, `hired_date`, `confirmation_date`, `termination_date`, `department_id`, `country_id`, `city_id`, `state_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `job_profile_id`) VALUES
(2, 2, NULL, NULL, NULL, '2018-11-28', NULL, NULL, 4, 80, 25, 12, '2026-10-10 11:42:49', '2026-10-10 11:42:49', NULL, 'terminated', 47);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_profiles`
--

CREATE TABLE `job_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employment_type` enum('full_time','part_time','contract','intern','temporary','freelance') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `calculated_years` int(11) DEFAULT NULL,
  `is_promoted` tinyint(1) NOT NULL DEFAULT 0,
  `last_promotion_date` date DEFAULT NULL,
  `basic_salary` decimal(12,2) DEFAULT NULL,
  `allowance` decimal(12,2) DEFAULT NULL,
  `currency` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `national_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_profiles`
--

INSERT INTO `job_profiles` (`id`, `employment_type`, `designation`, `department_id`, `calculated_years`, `is_promoted`, `last_promotion_date`, `basic_salary`, `allowance`, `currency`, `bank_account`, `national_id`, `passport_number`, `nationality`, `marital_status`, `phone`, `emergency_contact_name`, `emergency_contact_phone`, `address`, `postal_code`, `user_id`, `branch_id`, `notes`, `meta`, `created_at`, `updated_at`, `deleted_at`) VALUES
(41, 'part_time', 'Operations Manager', 6, 20, 0, NULL, '6934.30', '946.05', 'INR', '541437493', 'UZ17537086', 'GZ8520743', 'Marshall Islands', 'divorced', '1-435-467-0857', 'Norbert Pfannerstill I', '(484) 414-5582', '733 Jaron Village Apt. 509', '82517-8636', 3, 1, NULL, '{\"skills\":[\"PHP\",\"Docker\",\"Laravel\",\"React\"],\"remote\":false,\"referred\":false}', '2026-10-10 11:42:40', '2026-10-10 11:42:40', NULL),
(42, 'full_time', 'Data Analyst', 2, 3, 1, NULL, '17052.34', '1902.79', 'EUR', '77848854490', 'TY59722749', 'MF1865974', 'Germany', 'divorced', '(774) 821-6803', 'Nathanial Pagac', '(636) 487-5395', '7513 Jeramie Ferry Suite 992', '28827-9166', 3, 35, 'Tempore non ab exercitationem molestiae.', '{\"skills\":[\"Postgres\",\"MySQL\",\"AWS\",\"Laravel\"],\"remote\":false,\"referred\":false}', '2026-10-10 11:42:40', '2026-10-10 11:42:40', NULL),
(43, 'intern', 'Accountant', 5, 23, 1, NULL, '8443.37', '1807.72', 'INR', '4775746594259', 'LG98363629', 'RQ6744858', 'South Africa', 'divorced', '+1-518-354-8377', 'Adrien Braun', '+16603630824', '508 Heath Ramp', '12325-4654', 3, 39, NULL, '{\"skills\":[\"Docker\",\"Postgres\",\"PHP\"],\"remote\":false,\"referred\":false}', '2026-10-10 11:42:40', '2026-10-10 11:42:40', NULL),
(44, 'contract', 'DevOps Engineer', 2, 25, 0, '2025-05-27', '17394.08', '172.73', 'BDT', '99848512464', 'BN65332656', 'UK0896106', 'Guadeloupe', 'divorced', '1-484-623-1539', 'Pauline Bosco', '+1.229.933.8043', '9340 Dorthy Camp Suite 811', '33482', 1, 22, 'Et ut dignissimos quas ea.', '{\"skills\":[\"Laravel\",\"React\"],\"remote\":false,\"referred\":false}', '2026-10-10 11:42:45', '2026-10-10 11:42:45', NULL),
(45, 'contract', 'Operations Manager', 6, 11, 0, '2022-01-10', '5650.98', '1055.04', 'EUR', '5663871353951', 'AV98441510', 'VE9401366', 'Armenia', 'single', '+1.239.951.9717', 'Francesca Hagenes Sr.', '858.268.0786', '617 Rippin Falls', '12323-9613', 2, 11, NULL, '{\"skills\":[\"Docker\",\"AWS\",\"Laravel\",\"React\"],\"remote\":true,\"referred\":false}', '2026-10-10 11:42:45', '2026-10-10 11:42:45', NULL),
(46, 'part_time', 'HR Specialist', 2, 0, 0, NULL, '4986.23', '2386.98', 'EUR', '835913895', 'TB76855439', 'JR7561888', 'Canada', 'single', '585-209-7149', 'Mrs. Emie Goodwin', '+1-657-889-3325', '6550 Mayert Lights Suite 177', '36612-8247', 2, 7, 'Maiores animi alias natus sit.', '{\"skills\":[\"Laravel\",\"Vue\",\"MySQL\",\"AWS\",\"Postgres\"],\"remote\":false,\"referred\":false}', '2026-10-10 11:42:45', '2026-10-10 11:42:45', NULL),
(47, 'part_time', 'HR Specialist', 2, 7, 1, NULL, '9760.48', '2812.34', 'BDT', '3046945742', 'TG59567083', 'BV5713391', 'Lesotho', 'divorced', '+1-484-974-8899', 'Dr. Nova Gleason', '(380) 942-7220', '58623 Ulises Land Apt. 010', '50802-6027', 3, 2, 'Dolores qui voluptatem vel sit mollitia et itaque.', '{\"skills\":[\"Vue\",\"Docker\",\"PHP\",\"AWS\"],\"remote\":false,\"referred\":false}', '2026-10-10 11:42:48', '2026-10-10 11:42:48', NULL),
(48, 'contract', 'Senior Software Engineer', 3, 11, 1, NULL, '22763.63', '2698.79', 'GBP', '86378373015', 'HH84133041', 'HJ5181826', 'Seychelles', 'single', '+12127999373', 'Constantin Gleason', '678.866.8950', '2230 Ena Plain Apt. 789', '39658', 3, 34, NULL, '{\"skills\":[\"Vue\",\"AWS\",\"Laravel\",\"Docker\"],\"remote\":true,\"referred\":false}', '2026-10-10 11:42:48', '2026-10-10 11:42:48', NULL),
(49, 'contract', 'Sales Executive', 6, 11, 0, NULL, '22489.94', '1057.66', 'BDT', '5197557', 'DE78898045', 'WB4597142', 'United States of America', 'widowed', '1-347-509-4132', 'Lola Kris', '1-623-459-2824', '87841 Heaney Brook Suite 760', '00089', 1, 29, 'Pariatur soluta et dignissimos aliquid quo.', '{\"skills\":[\"Laravel\",\"Postgres\"],\"remote\":false,\"referred\":false}', '2026-10-10 11:42:48', '2026-10-10 11:42:48', NULL),
(50, 'part_time', 'Software Engineer', 2, 0, 0, NULL, '12453.37', '2936.05', 'GBP', '8418422693127', 'LZ10575636', 'IS0468745', 'Lithuania', 'married', '941-695-1916', 'Annalise Harvey', '347.696.1637', '359 Cleora Garden', '74069-9108', 3, 12, NULL, '{\"skills\":[\"PHP\",\"Vue\",\"MySQL\",\"AWS\"],\"remote\":false,\"referred\":false}', '2026-10-10 11:42:48', '2026-10-10 11:42:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2021_10_10_165340_create_countries_table', 1),
(6, '2021_10_10_165410_create_states_table', 1),
(7, '2021_10_10_165414_create_cities_table', 1),
(8, '2021_10_10_165418_create_departments_table', 1),
(9, '2021_10_10_165421_create_employees_table', 1),
(10, '2021_10_14_025753_create_permission_tables', 1),
(11, '2026_09_25_170806_create_profiles_table', 1),
(12, '2026_09_26_214424_add_relations_to_users_table', 1),
(13, '2026_09_26_222043_remove_an_column_from_employees_table', 1),
(14, '2026_09_26_222255_add_new_column_to_profiles_table', 1),
(15, '2026_09_26_222532_remove_columns_from_users_table', 1),
(16, '2026_09_27_085311_create_branch_table', 1),
(17, '2026_09_27_092621_add_new_columns_to_employee_table', 1),
(18, '2026_09_27_093345_create_job_profiles_table', 1),
(19, '2026_09_27_094212_create_project_infos_table', 1),
(20, '2026_09_27_094600_create_clients_infos_table', 1),
(21, '2026_09_27_101154_add_job_profile_fk_to_employees_table', 1),
(22, '2026_09_27_160648_add_client_id_fk_to_projects_info_table', 1),
(23, '2026_10_04_184648_add_user_id_to_employees_table', 2),
(24, '2026_10_09_071926_create_daily_logs_table', 3),
(25, '2026_10_09_072456_create_daily_logs_summary_table', 4),
(26, '2026_10_09_144755_update_employee_location_columns_nullable', 5),
(27, '2026_10_11_193713_create_registered_social_services', 6),
(28, '2026_10_11_200328_create_social_accounts_table', 6),
(29, '2026_10_11_200430_make_users_password_nullable', 7),
(30, '2026_10_13_173404_create_notifications_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 7),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 8);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `audience` enum('user','role','system','branch') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `audience_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_label` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `is_dismissed` tinyint(1) NOT NULL DEFAULT 0,
  `dismissed_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `priority` enum('low','normal','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `group_key` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_count` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `audience`, `audience_value`, `type`, `category`, `title`, `message`, `icon`, `action_url`, `action_label`, `related_type`, `related_id`, `is_read`, `read_at`, `is_dismissed`, `dismissed_at`, `deleted_at`, `priority`, `group_key`, `group_count`, `data`, `expires_at`, `created_at`, `updated_at`) VALUES
(4, 7, 'user', NULL, 'user.created', 'success', 'Welcome to the System!', 'Hello Jk, your account has been created successfully.', 'heroicon-o-user-plus', 'http://localhost:8000/admin/users/7', 'View Your Profile', NULL, NULL, 0, NULL, 0, NULL, NULL, 'normal', NULL, 1, '{\"user_id\":7,\"username\":\"jk\",\"email\":\"jk12@gmail.com\",\"roles\":[\"3\"],\"created_by\":1}', NULL, '2026-10-14 16:53:59', '2026-10-14 16:53:59'),
(5, 1, 'user', NULL, 'user.created', 'success', 'New User Created', 'Jk  (jk12@gmail.com) was created.', 'heroicon-o-user-plus', 'http://localhost:8000/admin/users/7', 'View User', 'App\\Models\\User', 7, 1, '2026-10-14 18:40:06', 0, '2026-10-14 17:05:26', NULL, 'normal', NULL, 1, '{\"user_id\":7,\"username\":\"jk\",\"email\":\"jk12@gmail.com\",\"roles\":[\"3\"],\"created_by\":1}', NULL, '2026-10-14 16:53:59', '2026-10-14 18:40:06'),
(6, 8, 'user', NULL, 'user.created', 'success', 'Welcome to the System!', 'Hello Shayla, your account has been created successfully.', 'heroicon-o-user-plus', 'http://localhost:8000/admin/users/8', 'View Your Profile', NULL, NULL, 0, NULL, 0, NULL, NULL, 'normal', NULL, 1, '{\"user_id\":8,\"username\":\"shayla\",\"email\":\"shayla@gmail.com\",\"roles\":[\"User\"],\"created_by\":1}', NULL, '2026-10-14 19:07:23', '2026-10-14 19:07:23'),
(7, 1, 'user', NULL, 'user.created', 'success', 'New User Created', 'Shayla  (shayla@gmail.com) was created.', 'heroicon-o-user-plus', 'http://localhost:8000/admin/users/8', 'View User', 'App\\Models\\User', 8, 0, NULL, 0, NULL, NULL, 'normal', NULL, 1, '{\"user_id\":8,\"username\":\"shayla\",\"email\":\"shayla@gmail.com\",\"roles\":[\"User\"],\"created_by\":1}', NULL, '2026-10-14 19:07:33', '2026-10-14 19:07:33'),
(8, 1, 'user', NULL, 'user.updated', 'info', 'User Updated', 'user (user@gmail.com) was updated.', 'heroicon-o-pencil-square', 'http://localhost:8000/admin/users/2', 'View User', 'App\\Models\\User', 2, 0, NULL, 0, NULL, NULL, 'normal', NULL, 1, '{\"user_id\":2,\"updated_by\":1}', NULL, '2026-10-16 08:18:18', '2026-10-16 08:18:18');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'permission-list', 'web', '2026-10-03 04:06:02', '2026-10-03 04:06:02'),
(2, 'permission-create', 'web', '2026-10-03 04:06:02', '2026-10-03 04:06:02'),
(3, 'permission-edit', 'web', '2026-10-03 04:06:02', '2026-10-03 04:06:02'),
(4, 'permission-delete', 'web', '2026-10-03 04:06:02', '2026-10-03 04:06:02'),
(5, 'role-list', 'web', '2026-10-03 04:06:02', '2026-10-03 04:06:02'),
(6, 'role-create', 'web', '2026-10-03 04:06:02', '2026-10-03 04:06:02'),
(7, 'role-edit', 'web', '2026-10-03 04:06:02', '2026-10-03 04:06:02'),
(8, 'role-delete', 'web', '2026-10-03 04:06:02', '2026-10-03 04:06:02'),
(9, 'user-list', 'web', '2026-10-03 04:06:03', '2026-10-03 04:06:03'),
(10, 'user-create', 'web', '2026-10-03 04:06:03', '2026-10-03 04:06:03'),
(11, 'user-edit', 'web', '2026-10-03 04:06:03', '2026-10-03 04:06:03'),
(12, 'user-delete', 'web', '2026-10-03 04:06:03', '2026-10-03 04:06:03'),
(13, 'city-list', 'web', '2026-10-03 04:06:03', '2026-10-03 04:06:03'),
(14, 'city-create', 'web', '2026-10-03 04:06:03', '2026-10-03 04:06:03'),
(15, 'city-edit', 'web', '2026-10-03 04:06:03', '2026-10-03 04:06:03'),
(16, 'city-delete', 'web', '2026-10-03 04:06:03', '2026-10-03 04:06:03'),
(17, 'country-list', 'web', '2026-10-03 04:06:03', '2026-10-03 04:06:03'),
(18, 'country-create', 'web', '2026-10-03 04:06:03', '2026-10-03 04:06:03'),
(19, 'country-edit', 'web', '2026-10-03 04:06:03', '2026-10-03 04:06:03'),
(20, 'country-delete', 'web', '2026-10-03 04:06:04', '2026-10-03 04:06:04'),
(21, 'state-list', 'web', '2026-10-03 04:06:04', '2026-10-03 04:06:04'),
(22, 'state-create', 'web', '2026-10-03 04:06:04', '2026-10-03 04:06:04'),
(23, 'state-edit', 'web', '2026-10-03 04:06:04', '2026-10-03 04:06:04'),
(24, 'state-delete', 'web', '2026-10-03 04:06:04', '2026-10-03 04:06:04'),
(25, 'department-list', 'web', '2026-10-03 04:06:04', '2026-10-03 04:06:04'),
(26, 'department-create', 'web', '2026-10-03 04:06:04', '2026-10-03 04:06:04'),
(27, 'department-edit', 'web', '2026-10-03 04:06:04', '2026-10-03 04:06:04'),
(28, 'department-delete', 'web', '2026-10-03 04:06:04', '2026-10-03 04:06:04'),
(29, 'employee-list', 'web', '2026-10-03 04:06:05', '2026-10-03 04:06:05'),
(30, 'employee-create', 'web', '2026-10-03 04:06:05', '2026-10-03 04:06:05'),
(31, 'employee-edit', 'web', '2026-10-03 04:06:05', '2026-10-03 04:06:05'),
(32, 'employee-delete', 'web', '2026-10-03 04:06:05', '2026-10-03 04:06:05'),
(33, 'backup-list', 'web', '2026-10-03 04:06:05', '2026-10-03 04:06:05'),
(34, 'backup-create', 'web', '2026-10-03 04:06:05', '2026-10-03 04:06:05'),
(35, 'backup-download', 'web', '2026-10-03 04:06:05', '2026-10-03 04:06:05'),
(36, 'backup-delete', 'web', '2026-10-03 04:06:05', '2026-10-03 04:06:05');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `state_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `phone`, `avatar`, `birthdate`, `gender`, `bio`, `address`, `zip_code`, `country_id`, `state_id`, `city_id`, `website`, `linkedin`, `twitter`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin', 'ad', 'User', '01872356097', 'avatars/1/0D4mkYlYk7GYvGL3uTsAIXHl34JdzjvBtUovfLPb.jpg', '1996-10-03', 'male', 'zcxczxc', 'street address', '456465', 233, NULL, NULL, 'http://localhost:8000/admin/profile/index', 'http://localhost:8000/admin/profile/index', 'http://localhost:8000/admin/profile/index', '2026-10-03 04:06:17', '2026-10-16 12:51:12'),
(2, 2, 'Us', NULL, 'User22', '01676791221', NULL, '2026-10-13', 'male', NULL, NULL, NULL, 62, NULL, NULL, NULL, NULL, NULL, '2026-10-03 06:18:40', '2026-10-16 17:13:32'),
(3, 3, 'Jaber', NULL, 'Khan', '01872356097', NULL, '2022-06-07', 'male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-04 12:35:23', '2026-10-16 17:14:31'),
(7, 7, 'Jk', NULL, NULL, NULL, NULL, '2019-07-17', 'male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-14 16:53:59', '2026-10-16 14:26:01'),
(8, 8, 'Shayla', NULL, 'Salma', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-14 19:07:05', '2026-10-15 14:20:39');

-- --------------------------------------------------------

--
-- Table structure for table `project_infos`
--

CREATE TABLE `project_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assignee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `project_date` date DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `status` enum('active','inactive','planning','in_progress','on_hold','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `progress` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `budget` decimal(14,2) DEFAULT NULL,
  `actual_cost` decimal(14,2) DEFAULT NULL,
  `currency` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registered_social_services`
--

CREATE TABLE `registered_social_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect_uri` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scopes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`scopes`)),
  `extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra`)),
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2026-10-03 04:06:05', '2026-10-03 04:06:05'),
(2, 'Editor', 'web', '2026-10-03 04:06:12', '2026-10-03 04:06:12'),
(3, 'User', 'web', '2026-10-03 04:06:12', '2026-10-03 04:06:12'),
(4, 'Employee', 'web', '2026-10-16 14:36:08', '2026-10-16 14:36:08');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(9, 2),
(9, 3),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(13, 2),
(13, 3),
(13, 4),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(17, 2),
(17, 3),
(17, 4),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(21, 2),
(21, 3),
(21, 4),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(25, 2),
(25, 3),
(25, 4),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(29, 4),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1);

-- --------------------------------------------------------

--
-- Table structure for table `social_accounts`
--

CREATE TABLE `social_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_user_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refresh_token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_expires_at` timestamp NULL DEFAULT NULL,
  `raw` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `country_id`, `name`, `description`, `state_code`, `created_at`, `updated_at`) VALUES
(1, 231, 'Oklahoma', 'Similique eos aperiam fuga aliquam repellat voluptatem quia numquam optio omnis velit et exercitationem.', 'OKL', '2026-10-03 04:13:37', '2026-10-03 04:13:37'),
(2, 211, 'District of Columbia', NULL, 'DIS', '2026-10-03 04:13:37', '2026-10-03 04:13:37'),
(3, 93, 'Illinois', 'Ullam sunt nihil cumque ut quis aperiam laboriosam quaerat unde aliquid provident.', 'ILL', '2026-10-03 04:13:37', '2026-10-03 04:13:37'),
(4, 82, 'Iowa', 'Aut libero enim molestiae harum est qui nostrum.', 'IOW', '2026-10-03 04:13:37', '2026-10-03 04:13:37'),
(5, 106, 'Colorado', NULL, 'COL', '2026-10-03 04:13:38', '2026-10-03 04:13:38'),
(6, 65, 'Georgia', NULL, 'GEO', '2026-10-03 04:13:38', '2026-10-03 04:13:38'),
(7, 54, 'California', NULL, 'CAL', '2026-10-03 04:13:38', '2026-10-03 04:13:38'),
(8, 107, 'Nevada', 'Veniam aut odit est impedit non quasi ab vitae odio sunt blanditiis vel.', 'NEV', '2026-10-03 04:13:39', '2026-10-03 04:13:39'),
(9, 38, 'South Dakota', 'Nobis unde ullam quod sit libero cumque eveniet at blanditiis ab.', 'SOU', '2026-10-03 04:13:39', '2026-10-03 04:13:39'),
(10, 226, 'Wisconsin', 'Qui quis sed sed quis ut iste maxime mollitia perspiciatis quas.', 'WIS', '2026-10-03 04:13:39', '2026-10-03 04:13:39'),
(11, 73, 'Connecticut', 'Debitis sint consequatur id cumque magni consequuntur ipsum.', 'CON', '2026-10-03 04:13:39', '2026-10-03 04:13:39'),
(12, 80, 'Michigan', 'Qui corporis corrupti doloribus animi eum doloribus ea maiores placeat pariatur eos.', 'MIC', '2026-10-03 04:13:39', '2026-10-03 04:13:39'),
(13, 45, 'New Hampshire', 'Molestiae est a aut quo aut sequi magni aut id tenetur quisquam sed.', 'NEW', '2026-10-03 04:13:39', '2026-10-03 04:13:39'),
(14, 131, 'Arizona', 'Praesentium omnis mollitia est facilis rerum voluptatem aliquam consequuntur.', 'ARI', '2026-10-03 04:13:40', '2026-10-03 04:13:40'),
(15, 110, 'Maine', 'Tempore quos et ullam a ut ut repellendus eum id et quos.', 'MAI', '2026-10-03 04:13:40', '2026-10-03 04:13:40'),
(16, 84, 'Mississippi', 'Repellendus placeat dolor rerum rerum quo ut.', 'MIS', '2026-10-03 04:13:40', '2026-10-03 04:13:40'),
(17, 136, 'Rhode Island', 'Facilis omnis molestiae voluptates sed omnis sunt id aut molestias ea dignissimos nihil eos quo.', 'RHO', '2026-10-03 04:13:40', '2026-10-03 04:13:40'),
(18, 228, 'Vermont', NULL, 'VER', '2026-10-03 04:13:40', '2026-10-03 04:13:40'),
(19, 125, 'Minnesota', 'Accusamus voluptates voluptas laborum dolore minus ipsum et eum odio eligendi maiores rerum.', 'MIN', '2026-10-03 04:13:40', '2026-10-03 04:13:40'),
(20, 210, 'West Virginia', 'Ea harum tempora consequatur quod veniam ut cumque doloremque maiores.', 'WES', '2026-10-03 04:13:41', '2026-10-03 04:13:41'),
(21, 9, 'Oregon', 'Nobis sunt nihil sit magnam molestiae dignissimos omnis itaque.', 'ORE', '2026-10-03 04:14:07', '2026-10-03 04:14:07'),
(22, 31, 'District of Columbia', NULL, 'DIS', '2026-10-03 04:14:08', '2026-10-03 04:14:08'),
(23, 17, 'North Carolina', 'Magni ut deleniti cupiditate voluptas minus ratione blanditiis.', 'NOR', '2026-10-03 04:14:08', '2026-10-03 04:14:08'),
(24, 237, 'Arizona', 'Rerum non quam et architecto non consequatur id quos assumenda ut.', 'ARI', '2026-10-03 04:14:08', '2026-10-03 04:14:08'),
(25, 14, 'Colorado', 'Rerum quas est reprehenderit non consequuntur fugiat saepe maxime et eveniet.', 'COL', '2026-10-03 04:14:08', '2026-10-03 04:14:08'),
(26, 103, 'New Jersey', 'Magnam ut nihil sed voluptatem et amet exercitationem.', 'NEW', '2026-10-03 04:14:08', '2026-10-03 04:14:08'),
(27, 228, 'Delaware', 'Ex iste eligendi voluptatem nostrum et molestiae.', 'DEL', '2026-10-03 04:14:08', '2026-10-03 04:14:08'),
(28, 158, 'New York', 'Similique quibusdam et iusto vel et earum qui illo doloremque ad natus suscipit.', 'NEW', '2026-10-03 04:14:08', '2026-10-03 04:14:08'),
(29, 125, 'Louisiana', 'Voluptas unde unde corrupti sed blanditiis et id et suscipit laboriosam voluptatem.', 'LOU', '2026-10-03 04:14:09', '2026-10-03 04:14:09'),
(30, 119, 'Missouri', 'Sapiente sequi et qui sapiente omnis omnis.', 'MIS', '2026-10-03 04:14:09', '2026-10-03 04:14:09'),
(31, 235, 'Oklahoma', 'Earum doloremque ut voluptatem ut voluptatem atque.', 'OKL', '2026-10-03 04:14:09', '2026-10-03 04:14:09'),
(32, 37, 'Maine', 'A quod iusto quod in praesentium neque vitae animi magnam.', 'MAI', '2026-10-03 04:14:09', '2026-10-03 04:14:09'),
(33, 215, 'Mississippi', NULL, 'MIS', '2026-10-03 04:14:09', '2026-10-03 04:14:09'),
(34, 160, 'Georgia', NULL, 'GEO', '2026-10-03 04:14:09', '2026-10-03 04:14:09'),
(35, 33, 'Rhode Island', 'Recusandae nemo ullam velit esse perspiciatis molestiae modi sequi repellat suscipit.', 'RHO', '2026-10-03 04:14:09', '2026-10-03 04:14:09'),
(36, 16, 'Illinois', NULL, 'ILL', '2026-10-03 04:14:09', '2026-10-03 04:14:09'),
(37, 46, 'North Dakota', 'Veniam velit ut cumque dignissimos doloribus asperiores commodi tempora minima minus dolores.', 'NOR', '2026-10-03 04:14:09', '2026-10-03 04:14:09'),
(38, 240, 'Hawaii', 'Et vero sequi nemo non accusantium placeat ad voluptatem deleniti illum recusandae eos.', 'HAW', '2026-10-03 04:14:09', '2026-10-03 04:14:09'),
(39, 192, 'Maryland', 'Eaque ea qui consectetur nesciunt eligendi occaecati laborum mollitia dolor laudantium quod eligendi nihil.', 'MAR', '2026-10-03 04:14:10', '2026-10-03 04:14:10'),
(40, 71, 'Nevada', 'Quo aut sequi minima tenetur at error nisi corporis et.', 'NEV', '2026-10-03 04:14:10', '2026-10-03 04:14:10'),
(41, 161, 'Ohio', NULL, 'OHI', '2026-10-03 04:14:10', '2026-10-03 04:14:10'),
(42, 130, 'Connecticut', 'Voluptas id velit autem aut sed ad eos aut aut quos.', 'CON', '2026-10-03 04:14:10', '2026-10-03 04:14:10'),
(43, 218, 'Idaho', NULL, 'IDA', '2026-10-03 04:14:10', '2026-10-03 04:14:10'),
(44, 235, 'Texas', 'Odit saepe neque vitae quae quae qui nam rerum non est et magni.', 'TEX', '2026-10-03 04:14:10', '2026-10-03 04:14:10'),
(45, 237, 'Alaska', NULL, 'ALA', '2026-10-03 04:14:10', '2026-10-03 04:14:10'),
(46, 94, 'Indiana', 'Doloremque quidem quae maxime eveniet dolorum et non provident est.', 'IND', '2026-10-03 04:14:10', '2026-10-03 04:14:10'),
(47, 107, 'Utah', 'Voluptatem dolores at nam id maxime temporibus cupiditate et omnis.', 'UTA', '2026-10-03 04:14:10', '2026-10-03 04:14:10'),
(48, 114, 'Vermont', 'Porro rem qui non iusto asperiores minus.', 'VER', '2026-10-03 04:14:11', '2026-10-03 04:14:11'),
(49, 237, 'West Virginia', 'Quod molestias id non ut facilis provident blanditiis optio aliquid dolores ex et.', 'WES', '2026-10-03 04:14:11', '2026-10-03 04:14:11'),
(50, 211, 'Kentucky', 'Iste dolor et in nemo rem quia et sit voluptatem.', 'KEN', '2026-10-03 04:14:11', '2026-10-03 04:14:11'),
(51, 44, 'Nebraska', 'Voluptates at esse tempore nostrum repudiandae architecto quos laborum quae ea id enim.', 'NEB', '2026-10-03 04:14:11', '2026-10-03 04:14:11'),
(52, 44, 'Washington', 'Eligendi hic omnis magnam sunt est cupiditate qui voluptas ab.', 'WAS', '2026-10-03 04:14:11', '2026-10-03 04:14:11'),
(53, 20, 'Virginia', 'Quidem sint harum eius cupiditate ex quaerat atque molestiae nihil ad consequatur.', 'VIR', '2026-10-03 04:14:11', '2026-10-03 04:14:11'),
(54, 216, 'New Hampshire', NULL, 'NEW', '2026-10-03 04:14:11', '2026-10-03 04:14:11'),
(55, 109, 'South Dakota', 'Debitis enim enim quam repellat commodi autem eligendi accusantium.', 'SOU', '2026-10-03 04:14:12', '2026-10-03 04:14:12'),
(56, 112, 'Alabama', 'Qui odit ullam cumque quae neque iusto consectetur amet adipisci aut mollitia quaerat.', 'ALA', '2026-10-03 04:14:12', '2026-10-03 04:14:12'),
(57, 91, 'Massachusetts', 'Et fugiat sint voluptas ut dolores nam ducimus amet dignissimos.', 'MAS', '2026-10-03 04:14:12', '2026-10-03 04:14:12'),
(58, 153, 'Michigan', NULL, 'MIC', '2026-10-03 04:14:12', '2026-10-03 04:14:12'),
(59, 72, 'Wisconsin', 'Eius quas eligendi voluptatum amet excepturi incidunt recusandae natus ad expedita dolor.', 'WIS', '2026-10-03 04:14:12', '2026-10-03 04:14:12'),
(60, 84, 'Wyoming', 'Explicabo eligendi tempora expedita incidunt ut velit aperiam ducimus maiores debitis doloribus ea.', 'WYO', '2026-10-03 04:14:13', '2026-10-03 04:14:13'),
(61, 96, 'Kansas', 'Labore eum ex perferendis voluptatem dolor aut culpa non perferendis voluptatem saepe.', 'KAN', '2026-10-03 04:14:13', '2026-10-03 04:14:13'),
(62, 189, 'Arkansas', 'Aperiam sunt est nemo minus cupiditate officia aut corporis dolores et.', 'ARK', '2026-10-03 04:14:13', '2026-10-03 04:14:13'),
(63, 230, 'Florida', 'Vel quas quos alias fugiat autem qui ab odio quaerat qui et quam.', 'FLO', '2026-10-03 04:14:13', '2026-10-03 04:14:13'),
(64, 18, 'Pennsylvania', 'Necessitatibus laudantium repellat assumenda voluptate similique voluptas numquam.', 'PEN', '2026-10-03 04:14:13', '2026-10-03 04:14:13'),
(65, 113, 'Tennessee', 'Mollitia dolor ad sunt qui fugiat est soluta.', 'TEN', '2026-10-03 04:14:13', '2026-10-03 04:14:13'),
(66, 125, 'Iowa', NULL, 'IOW', '2026-10-03 04:14:13', '2026-10-03 04:14:13'),
(67, 59, 'New Mexico', 'Saepe et laudantium aut nesciunt ea beatae et expedita quis ipsum.', 'NEW', '2026-10-03 04:14:14', '2026-10-03 04:14:14'),
(68, 213, 'South Carolina', NULL, 'SOU', '2026-10-03 04:14:15', '2026-10-03 04:14:15'),
(69, 199, 'California', NULL, 'CAL', '2026-10-03 04:14:15', '2026-10-03 04:14:15'),
(70, 114, 'Minnesota', 'Iusto non occaecati cum tempore dolorem rerum totam saepe vel voluptas reiciendis aut omnis.', 'MIN', '2026-10-03 04:14:15', '2026-10-03 04:14:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dept_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `email_verified_at`, `last_login_at`, `last_login_ip`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `role_id`, `profile_id`, `dept_id`, `created_by`, `updated_by`, `status`) VALUES
(1, 'admin', 'admin@gmail.com', '2026-10-03 04:06:15', NULL, NULL, '$2y$10$3cca8ng4CM.DytvpuMUwQO830rZ9kUuKLkhUZ3.hk7qw3nUqWZUxm', '2nQAVoJ9nnJjHmoVrgABVLUB9CVLodX43B19q1dl6FQhXw3R62fWVm0NkukF', '2026-10-03 04:06:15', '2026-10-03 04:06:15', NULL, 1, NULL, NULL, NULL, NULL, 1),
(2, 'user', 'user@gmail.com', '2026-10-03 06:18:39', NULL, NULL, '$2y$10$B7wljKkIXz2..9brQ.2hF.Z.5R2ZiAXMB6gRF71jW/5EpPjtA3hSW', '6IRC9aWn05', '2026-10-03 06:18:39', '2026-10-16 17:26:18', NULL, 2, NULL, NULL, NULL, NULL, 1),
(3, 'JaberKh16', 'jk@gmail.com', NULL, NULL, NULL, '$2y$10$4CMbHt1FF9DrcPVPiXonfOlCgavNY1dSxdA1iglYv5JDaD4IRc6.2', NULL, '2026-10-04 12:35:23', '2026-10-18 17:03:11', NULL, 3, NULL, NULL, NULL, NULL, 1),
(7, 'jk', 'jk12@gmail.com', '2026-10-14 18:00:00', NULL, NULL, '$2y$10$TABCU3EEbNNrCUg0gyGfGumnTap7LCFnoBPmlNhYE/k19o073/FkW', NULL, '2026-10-14 16:53:59', '2026-10-16 02:14:42', NULL, 2, NULL, NULL, NULL, NULL, 0),
(8, 'shayla', 'shayla2@gmail.com', NULL, NULL, NULL, '$2y$10$OgquP/bdO72e.Y4pJbgjM.opiFaKfH3Cn9gPIGz4QPRCMCCCnNuTm', NULL, '2026-10-14 19:07:03', '2026-10-16 13:26:56', NULL, NULL, NULL, NULL, NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branch_code_unique` (`code`),
  ADD KEY `branch_country_id_foreign` (`country_id`),
  ADD KEY `branch_state_id_foreign` (`state_id`),
  ADD KEY `branch_city_id_foreign` (`city_id`),
  ADD KEY `branch_status_index` (`status`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cities_state_id_foreign` (`state_id`);

--
-- Indexes for table `clients_infos`
--
ALTER TABLE `clients_infos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_infos_code_unique` (`code`),
  ADD KEY `clients_infos_branch_id_foreign` (`branch_id`),
  ADD KEY `clients_infos_created_by_foreign` (`created_by`),
  ADD KEY `clients_infos_updated_by_foreign` (`updated_by`),
  ADD KEY `clients_infos_status_index` (`status`),
  ADD KEY `clients_infos_email_index` (`email`),
  ADD KEY `clients_infos_company_name_index` (`company_name`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `countries_country_code_unique` (`country_code`),
  ADD UNIQUE KEY `countries_name_unique` (`name`),
  ADD KEY `countries_name_index` (`name`);

--
-- Indexes for table `daily_logs`
--
ALTER TABLE `daily_logs`
  ADD PRIMARY KEY (`id`,`logged_at`),
  ADD KEY `idx_user_metric_time` (`user_id`,`metric`,`logged_at`);

--
-- Indexes for table `daily_logs_summary`
--
ALTER TABLE `daily_logs_summary`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `daily_logs_summary_user_id_metric_bucket_unique` (`user_id`,`metric`,`bucket`),
  ADD KEY `daily_logs_summary_user_id_bucket_index` (`user_id`,`bucket`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_name_unique` (`name`),
  ADD UNIQUE KEY `departments_manager_id_unique` (`manager_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employees_department_id_foreign` (`department_id`),
  ADD KEY `employees_job_profile_id_foreign` (`job_profile_id`),
  ADD KEY `employees_user_id_foreign` (`user_id`),
  ADD KEY `employees_country_id_foreign` (`country_id`),
  ADD KEY `employees_state_id_foreign` (`state_id`),
  ADD KEY `employees_city_id_foreign` (`city_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `job_profiles`
--
ALTER TABLE `job_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_profiles_user_id_foreign` (`user_id`),
  ADD KEY `job_profiles_branch_id_foreign` (`branch_id`),
  ADD KEY `job_profiles_designation_index` (`designation`),
  ADD KEY `job_profiles_department_id_index` (`department_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_unread` (`user_id`,`is_read`,`created_at`),
  ADD KEY `idx_user_active` (`user_id`,`is_dismissed`,`created_at`),
  ADD KEY `idx_audience` (`audience`,`audience_value`),
  ADD KEY `idx_type` (`type`,`created_at`),
  ADD KEY `idx_group` (`group_key`),
  ADD KEY `idx_expiry` (`expires_at`),
  ADD KEY `idx_related` (`related_type`,`related_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profiles_user_id_unique` (`user_id`),
  ADD KEY `profiles_country_id_foreign` (`country_id`),
  ADD KEY `profiles_state_id_foreign` (`state_id`),
  ADD KEY `profiles_city_id_foreign` (`city_id`);

--
-- Indexes for table `project_infos`
--
ALTER TABLE `project_infos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_infos_code_unique` (`code`),
  ADD KEY `project_infos_manager_id_foreign` (`manager_id`),
  ADD KEY `project_infos_branch_id_foreign` (`branch_id`),
  ADD KEY `project_infos_department_id_foreign` (`department_id`),
  ADD KEY `project_infos_created_by_foreign` (`created_by`),
  ADD KEY `project_infos_updated_by_foreign` (`updated_by`),
  ADD KEY `project_infos_status_index` (`status`),
  ADD KEY `project_infos_priority_index` (`priority`),
  ADD KEY `project_infos_start_date_index` (`start_date`),
  ADD KEY `project_infos_end_date_index` (`end_date`),
  ADD KEY `project_infos_client_id_foreign` (`client_id`);

--
-- Indexes for table `registered_social_services`
--
ALTER TABLE `registered_social_services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registered_social_services_provider_unique` (`provider`),
  ADD KEY `registered_social_services_created_by_foreign` (`created_by`),
  ADD KEY `registered_social_services_updated_by_foreign` (`updated_by`),
  ADD KEY `registered_social_services_is_enabled_sort_order_index` (`is_enabled`,`sort_order`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `social_accounts_provider_provider_user_id_unique` (`provider`,`provider_user_id`),
  ADD UNIQUE KEY `social_accounts_user_id_provider_unique` (`user_id`,`provider`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `states_country_id_name_unique` (`country_id`,`name`),
  ADD KEY `states_state_code_index` (`state_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_profile_id_foreign` (`profile_id`),
  ADD KEY `users_dept_id_foreign` (`dept_id`),
  ADD KEY `users_created_by_foreign` (`created_by`),
  ADD KEY `users_updated_by_foreign` (`updated_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `clients_infos`
--
ALTER TABLE `clients_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=257;

--
-- AUTO_INCREMENT for table `daily_logs`
--
ALTER TABLE `daily_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_logs_summary`
--
ALTER TABLE `daily_logs_summary`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_profiles`
--
ALTER TABLE `job_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `project_infos`
--
ALTER TABLE `project_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registered_social_services`
--
ALTER TABLE `registered_social_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `social_accounts`
--
ALTER TABLE `social_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branch`
--
ALTER TABLE `branch`
  ADD CONSTRAINT `branch_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `branch_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `branch_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`);

--
-- Constraints for table `clients_infos`
--
ALTER TABLE `clients_infos`
  ADD CONSTRAINT `clients_infos_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `clients_infos_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `clients_infos_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `employees_job_profile_id_foreign` FOREIGN KEY (`job_profile_id`) REFERENCES `job_profiles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_profiles`
--
ALTER TABLE `job_profiles`
  ADD CONSTRAINT `job_profiles_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `job_profiles_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `job_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `profiles_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `profiles_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_infos`
--
ALTER TABLE `project_infos`
  ADD CONSTRAINT `project_infos_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `project_infos_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients_infos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `project_infos_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `project_infos_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `project_infos_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `project_infos_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `registered_social_services`
--
ALTER TABLE `registered_social_services`
  ADD CONSTRAINT `registered_social_services_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `registered_social_services_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD CONSTRAINT `social_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `states`
--
ALTER TABLE `states`
  ADD CONSTRAINT `states_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_dept_id_foreign` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_profile_id_foreign` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
