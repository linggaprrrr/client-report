-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2022 at 05:53 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rms`
--

-- --------------------------------------------------------

--
-- Table structure for table `assign_reports`
--

CREATE TABLE `assign_reports` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `file` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'incomplete',
  `units` int(11) NOT NULL,
  `retails` float NOT NULL,
  `originals` float NOT NULL,
  `costs` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `assign_report_box`
--

CREATE TABLE `assign_report_box` (
  `id` int(11) NOT NULL,
  `box_name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` varchar(59) NOT NULL,
  `report_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `assign_report_details`
--

CREATE TABLE `assign_report_details` (
  `id` int(11) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `item_description` varchar(255) DEFAULT NULL,
  `cond` varchar(255) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `retail` float DEFAULT NULL,
  `original` float DEFAULT NULL,
  `cost` float DEFAULT NULL,
  `vendor` int(11) DEFAULT NULL,
  `box_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `investment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `investment_id`) VALUES
(3, 'WOMEN\'S APPAREL (NEW WITH TAGS)', 3),
(4, 'WOMEN\'S APPAREL (NEW WITH TAGS)', 4),
(5, 'WOMEN\'S APPAREL (NEW WITH TAGS)', 5),
(6, 'WOMEN\'S APPAREL (NEW WITH TAGS)', 6);

-- --------------------------------------------------------

--
-- Table structure for table `chart_pl`
--

CREATE TABLE `chart_pl` (
  `id` int(11) NOT NULL,
  `chart` varchar(255) NOT NULL,
  `jan` float NOT NULL,
  `feb` float NOT NULL,
  `mar` float NOT NULL,
  `apr` float NOT NULL,
  `may` float NOT NULL,
  `jun` float NOT NULL,
  `jul` float NOT NULL,
  `aug` float NOT NULL,
  `sep` float NOT NULL,
  `oct` float NOT NULL,
  `nov` float NOT NULL,
  `dec` float NOT NULL,
  `avg` float DEFAULT NULL,
  `type` varchar(55) NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chart_pl`
--

INSERT INTO `chart_pl` (`id`, `chart`, `jan`, `feb`, `mar`, `apr`, `may`, `jun`, `jul`, `aug`, `sep`, `oct`, `nov`, `dec`, `avg`, `type`, `client_id`) VALUES
(1, 'Sold', 0, 0, 0, 0, 0, 92, 328, 397, 551, 404, 461, 0, NULL, 'num', 10),
(2, 'YTD Average', 0, 0, 0, 0, 0, 92, 210, 272, 342, 354, 372, 0, NULL, 'num', 10),
(3, 'Returned', 0, 0, 0, 0, 0, 13, 89, 119, 85, 96, 104, 0, NULL, 'num', 10),
(4, 'Return Rate', 0, 0, 0, 0, 0, 14, 27, 30, 15, 24, 23, 0, NULL, 'percentage', 10),
(5, 'Gross Sales', 0, 0, 0, 0, 0, 2951, 8773, 9573, 10242, 10702, 11054, 0, NULL, 'currency', 10),
(6, 'Average per Unit', 0, 0, 0, 0, 0, 32.08, 26.75, 24.11, 18.59, 26.49, 23.98, 0, NULL, 'currency', 10),
(7, 'COGS', 0, 0, 0, 0, 0, 1040, 3319, 1853, 2707, 3511, 4915, 0, NULL, 'currency', 10),
(8, 'Average per Unit', 0, 0, 0, 0, 0, 11.31, 10.12, 4.67, 4.91, 8.69, 10.66, 0, NULL, 'currency', 10),
(9, 'Gross Profit', 0, 0, 0, 0, 0, 1911, 5454, 7720, 7535, 7190, 6139, 0, NULL, 'currency', 10),
(10, 'Average per Unit', 0, 0, 0, 0, 0, 20.77, 16.63, 19.45, 13.67, 17.8, 13.32, 0, NULL, 'currency', 10),
(11, 'Gross Profit Margin', 0, 0, 0, 0, 0, 65, 62, 81, 74, 67, 56, 0, NULL, 'percentage', 10),
(12, 'YTD Average', 0, 0, 0, 0, 0, 65, 63, 69, 70, 70, 67, 0, NULL, 'percentage', 10),
(13, 'Fees and Subtractions', 0, 0, 0, 0, 0, 1522, 3591, 5822, 4274, 4270, 5878, 0, NULL, 'currency', 10),
(14, 'Average per Unit', 0, 0, 0, 0, 0, 16.55, 10.95, 14.66, 7.76, 10.57, 12.75, 0, NULL, 'currency', 10),
(15, 'Net Profit', 0, 0, 0, 0, 0, 389, 1863, 1898, 3260, 2921, 260, 0, NULL, 'currency', 10),
(16, 'Average per Unit', 0, 0, 0, 0, 0, 4.22, 5.68, 4.78, 5.92, 7.23, 0.56, 0, NULL, 'currency', 10),
(17, 'Net Profit Margin', 0, 0, 0, 0, 0, 13, 21, 20, 32, 27, 2, 0, NULL, 'percentage', 10),
(18, 'YTD Average', 0, 0, 0, 0, 0, 13, 17, 18, 22, 23, 19, 0, NULL, 'percentage', 10);

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` int(11) NOT NULL,
  `cost` float NOT NULL,
  `date` date NOT NULL,
  `client_id` int(11) NOT NULL,
  `status` varchar(55) NOT NULL DEFAULT 'complete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`id`, `cost`, `date`, `client_id`, `status`) VALUES
(3, 3000, '2021-12-21', 9, 'complete'),
(4, 4850, '2021-12-21', 9, 'complete'),
(5, 1000, '2022-01-04', 9, 'assign'),
(6, 4850, '2021-12-21', 9, 'complete');

-- --------------------------------------------------------

--
-- Table structure for table `log_files`
--

CREATE TABLE `log_files` (
  `id` int(11) NOT NULL,
  `date` timestamp NULL DEFAULT current_timestamp(),
  `file` varchar(255) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `investment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `log_files`
--

INSERT INTO `log_files` (`id`, `date`, `file`, `client_id`, `investment_id`) VALUES
(10, '2022-01-06 10:34:31', '1641465271Imad Graph.xlsx', 4, NULL),
(11, '2022-01-06 10:48:33', '1641466113Imad Graph.xlsx', 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'news',
  `title` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `type`, `title`, `message`, `date`) VALUES
(1, 'news', 'HAPPY NEW YEAHHHwww', 'Happy New Year 2023', '2021-12-29 19:04:37');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `sku` varchar(25) DEFAULT NULL,
  `item_description` text DEFAULT NULL,
  `cond` varchar(10) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `retail_value` double DEFAULT NULL,
  `original_value` double DEFAULT NULL,
  `cost` double DEFAULT NULL,
  `vendor` varchar(55) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `investment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `sku`, `item_description`, `cond`, `qty`, `retail_value`, `original_value`, `cost`, `vendor`, `client_id`, `investment_id`) VALUES
(2293, '193623315443', 'Calvin Klein Women\'s Three Quarter Cowl Neck Sheath, Black, 2 Petite', 'New', 1, 134, 134, 33.5, 'CALVIN KLEIN/G-III APPAREL GROUP', 9, 3),
(2294, '193623321086', 'Calvin Klein Women\'s Sleeveless Open V-Neck Sheath Dress, Red, 12', 'New', 1, 134, 134, 33.5, 'CALVIN KLEIN/G-III APPAREL GROUP', 9, 3),
(2295, '193623321253', 'Calvin Klein Women\'s Sleeveless V-Neck Sheath Dress, Camel Multi, 10', 'New', 1, 119, 119, 29.75, 'CALVIN KLEIN/G-III APPAREL GROUP', 9, 3),
(2296, '193623321949', 'Calvin Klein Womens Double Tier Piped Midi Dress Red 10', 'New', 1, 89.98, 89.98, 22.5, 'CALVIN KLEIN', 9, 3),
(2297, '193623435646', 'Calvin Klein Womens Jumpsuit V-Neck Texture-Stripe Black 16', 'New', 1, 199, 199, 49.75, 'CALVIN KLEIN', 9, 3),
(2298, '193623436551', 'Calvin Klein Womens Purple Gathered 3/4 Sleeve Off Shoulder Knee Length Sheath Cocktail Dress Size 10', 'New', 1, 149, 149, 37.25, 'CALVIN KLEIN', 9, 3),
(2299, '193623454722', 'Calvin Klein Women\'s Size Velvet Sheath with Embellished Long Sleeve, Sapphire, 14 Plus', 'New', 1, 139, 139, 34.75, 'CALVIN KLEIN', 9, 3),
(2300, '193623477653', 'Calvin Klein Women\'s Essential Sleeveless Sheath, Navy Faux Suede, 8', 'New', 1, 129, 129, 32.25, 'CALVIN KLEIN', 9, 3),
(2301, '193623490904', 'Calvin Klein Womens Black Bell Sleeve Open Cardigan Top Size XL', 'New', 1, 49.98, 49.98, 12.5, 'CALVIN KLEIN', 9, 3),
(2302, '193623503376', 'Calvin Klein Womens Black Sheer Zippered Long Sleeve Off Shoulder Cocktail Jumpsuit Size 4', 'New', 1, 139, 139, 34.75, 'CALVIN KLEIN', 9, 3),
(2303, '193623852962', 'Calvin Klein Womens Navy Sheer Zippered Short Sleeve Jewel Neck Short Fit + Flare Party Dress Size 8', 'New', 1, 89.98, 89.98, 22.5, 'CALVIN KLEIN/G-III APPAREL GROUP', 9, 3),
(2304, '193623853549', 'Calvin Klein Womens Green Belted Zippered 3/4 Sleeve Jewel Neck Below The Knee Sheath Formal Dress Size 2', 'New', 2, 89.98, 179.96, 44.99, 'CALVIN KLEIN', 9, 3),
(2305, '194414959983', 'Calvin Klein Women\'s Sheath Sleeveless Dress with Pearl Neck, Black, 10 Petite', 'New', 1, 89.98, 89.98, 22.5, 'CALVIN KLEIN', 9, 3),
(2306, '194414960064', 'Calvin Klein Women\'s Sleeveless Scuba Crepe Sheath Dress, Red Multi 2, 8 Petite', 'New', 1, 119, 119, 29.75, 'CALVIN KLEIN', 9, 3),
(2307, '194414960071', 'Calvin Klein Women\'s Sleeveless Scuba Crepe Sheath Dress, Red Multi 2, 6 Petite', 'New', 1, 119, 119, 29.75, 'CALVIN KLEIN/G-III APPAREL GROUP', 9, 3),
(2308, '194414964352', 'Calvin Klein Women\'s Cold Shoulder Sheath with Illusion Neckline, Black/Black, 12', 'New', 1, 139, 139, 34.75, 'CALVIN KLEIN/G-III APPAREL GROUP', 9, 3),
(2309, '194414967827', 'Calvin Klein Women\'s Sleeveless Sheath with Asymmetrical Neckline Dress, Black 2, 6', 'New', 1, 89.98, 89.98, 22.5, 'CALVIN KLEIN', 9, 3),
(2310, '194414970315', 'Calvin Klein Women\'s Size Sleeveless Mock Neck Lace Midi with Illusion Detail, Black, 20 Plus', 'New', 1, 537, 537, 134.25, 'CALVIN KLEIN/G-III APPAREL GROUP', 9, 3),
(2311, '194414971077', 'Calvin Klein Women\'s Sleeveless Floral Embroidered Fit and Flare Dress, Ultramarine/Black, 14', 'New', 1, 149, 149, 37.25, 'CALVIN KLEIN', 9, 3),
(2312, '192351591143', 'Calvin Klein Women\'s Square Neck Sheath with Embellished Split Sleeve, Black, 8', 'New', 1, 139, 139, 34.75, 'CALVIN KLEIN/G-III APPAREL GROUP', 9, 3),
(2313, '191797086077', 'Calvin Klein Women\'s Sleeveless Midi Sheath with Ruffle Hem Dress, Black, 2', 'New', 1, 89.98, 89.98, 22.5, 'CALVIN KLEIN', 9, 3),
(2314, '193623444952', 'Calvin Klein Women\'s Three Quarter Sweetheart Off-The-Shoulder Cocktail Dress, Black, 8', 'New', 1, 149, 149, 37.25, 'CALVIN KLEIN/G-III APPAREL GROUP', 9, 3),
(2315, '193623444112', 'Calvin Klein Women\'s One Shoulder Gown with Shirred Bodice, Black/Copper, 2', 'New', 1, 219, 219, 54.75, 'CALVIN KLEIN', 9, 3),
(2316, '5257472495', 'Brinley Co Women\'s LANET Ballet Flat, Pink, 11 Regular US', 'New', 1, 45, 45, 13.64, 'BRINLEY CO', 9, 4),
(2317, '17114286008', 'SOUL Naturalizer Women\'s Stellar Flat Sandal, Black, 8 Wide', 'New', 1, 80, 80, 24.24, 'NATUALIZER SOUL/CALERES INC', 9, 4),
(2318, '17115795660', 'LifeStride Women\'s Deja Vu Ballet Flat, Black, 8.5 M US', 'New', 1, 60, 60, 18.18, 'LIFESTRIDE/CALERES INC', 9, 4),
(2319, '17116991993', 'Dr. Scholl\'s No Bad Vibes White 7 M', 'New', 1, 75, 75, 22.73, 'DR. SCHOLLS/CALERES INC', 9, 4),
(2320, '52574557824', 'Brinley Co Comfort Womens Espadrille Ankle Strap Wedge Grey, 7 Regular US', 'New', 1, 69, 69, 20.91, 'KNS INTERNATIONAL', 9, 4),
(2321, '52574612530', 'Journee Collection Solay Black 8.5', 'New', 1, 49, 49, 14.85, 'KNS INTERNATIONAL', 9, 4),
(2322, '52574762150', 'Journee Collection Womens Aubrinn Sandal Pink, 8.5 Womens US', 'New', 1, 64.99, 64.99, 19.69, 'KNS INTERNATIONAL', 9, 4),
(2323, '52574769999', 'Journee Collection Womens Jenice Wedge Sandal Black, 11 Womens US', 'New', 1, 79.99, 79.99, 24.24, 'KNS INTERNATIONAL', 9, 4),
(2324, '190748604384', 'RIALTO Shoes Sunnyside II Women\'s Flat, RED/Smooth, 8 M', 'New', 1, 49, 49, 14.85, 'RIALTO/CONNORS FOOTWEAR', 9, 4),
(2325, '191045700670', 'Sugar Women\'s Evermore Comfortable Slip On Espadrille Flats Fashion Sneaker Shoe with Cute Designs 7.5 Beach Stripe', 'New', 1, 44.99, 44.99, 13.63, 'RAMPAGE/ES ORIGINALS', 9, 4),
(2326, '191045834887', 'Sugar Women\'s Noelle Low Two Piece Block Heel Dress Shoe Ladies Ankle Strap Pump Sandal Baby Blue 8', 'New', 1, 50, 50, 15.15, 'RAMPAGE/ES ORIGINALS', 9, 4),
(2327, '193553731436', 'Sorel Women\'s Kinetic Lite Lace Sneakers, Black, 5 Medium US', 'New', 1, 120, 120, 36.36, 'SOREL/COLUMBIA BRANDS USA, LLC', 9, 4),
(2328, '193569379912', 'Gentle Souls by Kenneth Cole Women\'s Lavern Easy Strap Platform Sandal, Ice, 8', 'New', 1, 189, 189, 57.27, 'GENTLE SOULS/KENNETH COLE PRODUCTNS', 9, 4),
(2329, '885660622256', 'Easy Street womens Waive pumps shoes, Black, 7.5 Wide US', 'New', 1, 55, 55, 16.67, 'EASY STREET SALES CORP', 9, 4),
(2330, '885660700435', 'Easy Street Womens Waive Square Toe Kitten Pumps - Black - Size 8 B', 'New', 1, 50, 50, 15.15, 'EASY STREET SALES CORP', 9, 4),
(2331, '885660700442', 'Easy Street Womens Waive Closed Toe Classic Pumps, Black Patent, Size 8.5', 'New', 1, 50, 50, 15.15, 'EASY STREET SALES CORP', 9, 4),
(2332, '889885358938', 'Easy Street Women\'s Proper Dress Pump, Black, 7.5 W US', 'New', 1, 55, 55, 16.67, 'EASY STREET SALES CORP', 9, 4),
(2333, '889885359102', 'Easy Street Women\'s Proper Dress Pump, Black, 11 2W US', 'New', 1, 55, 55, 16.67, 'EASY STREET SALES CORP', 9, 4),
(2334, '889885748982', 'Easy Street Women\'s Tarrah Wedge Sandal, Gold Glitter, 10 W US', 'New', 1, 60, 60, 18.18, 'EASY STREET SALES CORP', 9, 4),
(2335, '889885797829', 'Bella Vita Women\'s Scarlett Pump, Black LEA, 8.5 N US', 'New', 1, 100, 100, 30.3, 'BELLA VITA', 9, 4),
(2336, '17118004097', 'Life Stride Ally Women\'s Boot 8 B(M) US Black-Suede', 'New', 1, 75, 75, 22.73, 'LIFESTRIDE/CALERES INC', 9, 4),
(2337, '17118005100', 'Life Stride Ally Women\'s Boot 9 B(M) US Mushroom-Suede', 'New', 1, 75, 75, 22.73, 'LIFESTRIDE/CALERES INC', 9, 4),
(2338, '17121662536', 'Franco Sarto Women\'s Tribute Knee High Boot, Mulberry, 9.5', 'New', 1, 149, 149, 45.15, 'FRANCO SARTO/CALERES INC', 9, 4),
(2339, '52574666359', 'Journee Collection Women\'s Pumps, Grey, 7.5', 'New', 1, 65, 65, 19.7, 'KNS INTERNATIONAL', 9, 4),
(2340, '191154784905', 'Sanctuary Social Fatigue Camo Pr Recycled Fabric 8 M', 'New', 1, 99, 99, 30, 'SANCTUARY/HIGHLINE UNITED LLC', 9, 4),
(2341, '716142102921', 'NINA Rhiyana Black Luster Satin 9.5', 'New', 1, 79, 79, 23.94, 'NINA FOOTWEAR', 9, 4),
(2342, '736705464693', 'LifeStride womens Giada Ankle Boot, Black, 9.5 US', 'New', 1, 60, 60, 18.18, 'LIFESTRIDE/CALERES INC', 9, 4),
(2343, '885660473117', 'Easy Street Women\'s Passion Dress Pump,Black,8.5 M US', 'New', 1, 55, 55, 16.67, 'EASY STREET SALES CORP', 9, 4),
(2344, '889309535662', 'Clarks Women\'s Hollis Star Ankle Boot, Black Suede Combi, 8', 'New', 1, 135, 135, 40.91, 'CLARKS OF ENGLAND', 9, 4),
(2345, '889885358969', 'Easy Street Women\'s Proper Dress Pump, Black, 9 W US', 'New', 1, 55, 55, 16.67, 'EASY STREET SALES CORP', 9, 4),
(2346, '889885800253', 'Bella Vita Women\'s SCARLETTII Pump, Nude PAT, 8.5 M US', 'New', 1, 80, 80, 24.24, 'BELLA VITA', 9, 4),
(2347, '191045834696', 'Sugar Women\'s Noelle Low Two Piece Block Heel Dress Shoe Ladies Ankle Strap Pump Sandal Nude 8.5', 'New', 1, 50, 50, 15.15, 'RAMPAGE/ES ORIGINALS', 9, 4),
(2348, '191045700663', 'Sugar Women\'s Evermore Comfortable Slip On Espadrille Flats Fashion Sneaker Shoe with Cute Designs 7 Beach Stripe', 'New', 1, 44.99, 44.99, 13.63, 'RAMPAGE/ES ORIGINALS', 9, 4),
(2349, '191045662329', 'Sugar Womens Gabber Fabric Low Top Lace Up Fashion Sneakers Black', 'New', 1, 60, 60, 18.18, 'RAMPAGE/ES ORIGINALS', 9, 4),
(2350, '636193937818', 'Style & Co. Womens Chicklet Open Toe Casual Platform Sandals, White, Size 11.0', 'New', 1, 49.5, 49.5, 15, 'STYLE & CO-MMG', 9, 4),
(2351, '191837109766', 'Xscape Womens Floral Lace Sleeveless Evening Dress Green 8', 'New', 1, 289, 289, 72.25, 'Xscape', 9, 5),
(2352, '193596777705', 'Sequin Hearts Dress Large Junior Floral Puff-Sleeve Sheath Blue L', 'New', 1, 36.99, 36.99, 9.25, 'YOU BABES/MY MICHELLE/KELLWOOD', 9, 5),
(2353, '194592912169', 'Jessica Howard Womens Navy Tie Waist Bell Sleeve Surplice Neckline Evening Wide Leg Jumpsuit L', 'New', 1, 99, 99, 24.75, 'JESSICA HOWARD/G-III APPAREL GROUP', 9, 5),
(2354, '194592927927', 'Jessica Howard Womens Plus Sequined Midi Sheath Dress Black 18W', 'New', 1, 109, 109, 27.25, 'JESSICA HOWARD/G-III LEATHER FAS', 9, 5),
(2355, '195606009387', 'Ultra Flirt Womens Coral Tie Dye Long Sleeve Crew Neck Short Shift Dress Size XS Pink', 'New', 1, 32.99, 32.99, 8.25, 'ULTRAFLIRT BY IKEDDI/IKEDDI ENTERPR', 9, 5),
(2356, '195606015692', 'Ultra Flirt Womens Green Mock Neck Tie Dye Mini Shift Dress Juniors Size: S', 'New', 1, 28.99, 28.99, 7.25, 'ULTRAFLIRT BY IKEDDI/IKEDDI ENTERPR', 9, 5),
(2357, '635273792187', 'Tahari ASL Women\'s One Shoulder Long Pleated Sleeve Jumpsuit, Black, 8', 'New', 1, 188, 188, 47, 'ARTHUR S LEVINE/PACIFIC ALLIANCE', 9, 5),
(2358, '661414564870', 'Teeze Me Womens Juniors Off-The-Shoulder A-Line Mini Dress Black 9/10', 'New', 1, 59, 59, 14.75, 'TEEZE ME/CHOON INC (227/256)', 9, 5),
(2359, '661414673541', 'Teeze Me Womens Juniors Satin Mini Fit & Flare Dress Red 9', 'New', 1, 69, 69, 17.25, 'TEEZE ME/CHOON INC (227/256)', 9, 5),
(2360, '661414673558', 'Teeze Me Womens Red Spaghetti Strap Short Fit + Flare Party Dress Juniors 11', 'New', 2, 69, 138, 34.5, 'Teeze Me', 9, 5),
(2361, '661414675200', 'Teeze Me Womens White Floral Sleeveless Halter Short Fit + Flare Dress Size 3', 'New', 1, 46.99, 46.99, 11.75, 'TEEZE ME/CHOON INC (226/256)', 9, 5),
(2362, '661414675217', 'Teeze Me Womens Ivory Floral Sleeveless Halter Short Fit + Flare Dress Size 5', 'New', 2, 46.99, 93.98, 23.5, 'Teeze Me', 9, 5),
(2363, '689886187465', 'Jessica Howard Womens Ribbed Three Quarter Sleeves Cardigan Sweater Navy L', 'New', 2, 24.98, 49.96, 12.49, 'JESSICA HOWARD/G-III APPAREL GROUP', 9, 5),
(2364, '689886187489', 'Jessica Howard Womens Ribbed Three Quarter Sleeves Cardigan Sweater Navy S', 'New', 1, 24.98, 24.98, 6.25, 'JESSICA HOWARD/G-III APPAREL GROUP', 9, 5),
(2365, '707762273061', 'Nightway Womens Gold Patterned Spaghetti Strap Sweetheart Neckline Full-Length Formal Fit + Flare Dress 6', 'New', 1, 169, 169, 42.25, 'NIGHT WAY/R & M RICHARDS INC', 9, 5),
(2366, '707762279377', 'Nightway Womens Navy Spaghetti Strap Full-Length Sheath Formal Dress Size: 10', 'New', 1, 149, 149, 37.25, 'NIGHT WAY/R & M RICHARDS INC', 9, 5),
(2367, '708008555156', 'City Studio Womens Navy Sleeveless Short Fit + Flare Party Dress Juniors 3', 'New', 1, 44.99, 44.99, 11.25, 'CITY TRIANGLES-JODI KRISTOPHER', 9, 5),
(2368, '708008555194', 'City Studio Womens Navy Printed Sleeveless Halter Short Fit + Flare Party Dress Size 11', 'New', 1, 44.99, 44.99, 11.25, 'CITY TRIANGLES-JODI KRISTOPHER', 9, 5),
(2369, '708008630488', 'City Studio Womens Maroon Floral Spaghetti Strap V Neck Short Sheath Evening Dress Size 17', 'New', 1, 59, 59, 14.75, 'CITY TRIANGLES-JODI KRISTOPHER', 9, 5),
(2370, '708008634646', 'City Studio Womens White Floral Sleeveless V Neck Mini Fit + Flare Dress Size 1', 'New', 1, 52.99, 52.99, 13.25, 'CITY TRIANGLES-JODI KRISTOPHER', 9, 5),
(2371, '708008672464', 'City Studio Womens Floral Short Mini Dress Black 11', 'New', 1, 47.99, 47.99, 12, 'CITY TRIANGLES-JODI KRISTOPHER', 9, 5),
(2372, '710816334380', 'BCX Womens White Solid Spaghetti Strap Square Neck Short Body Con Evening Dress Size 3', 'New', 1, 59, 59, 14.75, 'BCX/BYER CALIFORNIA', 9, 5),
(2373, '747941622440', 'Speechless Womens Juniors Floral Criss-Cross Scuba Dress Navy 1', 'New', 1, 47.99, 47.99, 12, 'SPEECHLESS/SWAT FAME INC', 9, 5),
(2374, '747941622501', 'Speechless Womens Juniors Floral Criss-Cross Scuba Dress Navy 5', 'New', 1, 47.99, 47.99, 12, 'SPEECHLESS/SWAT FAME INC', 9, 5),
(2375, '747941622525', 'Speechless Womens Juniors Floral Criss-Cross Scuba Dress Navy 9', 'New', 1, 47.99, 47.99, 12, 'SPEECHLESS/SWAT FAME INC', 9, 5),
(2376, '747941892553', 'Speechless Womens Juniors Lace Top Mini Fit & Flare Dress Navy 3', 'New', 1, 43.99, 43.99, 11, 'SPEECHLESS/SWAT FAME INC', 9, 5),
(2377, '758116572422', 'Derek Heart Smocked Peasant Top - Juniors', 'New', 1, 22.99, 22.99, 5.75, 'PLANET GOLD CLOTHING/GOLDEN TOUCH', 9, 5),
(2378, '828659599597', 'Jessica Howard Womens Plus Scalloped Open Front Shrug Sweater Navy 1X', 'New', 1, 50, 50, 12.5, 'JESSICA HOWARD/G-III LEATHER FAS', 9, 5),
(2379, '828659687706', 'Jessica Howard Womens Purple 3/4 Sleeve Off Shoulder Fit + Flare Formal Dress Size 16W', 'New', 1, 119, 119, 29.75, 'JESSICA HOWARD/G-III LEATHER FAS', 9, 5),
(2380, '882191505713', 'R & M Richards Womens Halter Metallic Evening Dress Gold 12', 'New', 1, 99, 99, 24.75, 'R & M RICHARDS', 9, 5),
(2381, '887840361740', 'Emerald Sundae Honey and Rosie Womens Juniors Lace Back Mini Bodycon Dress Blue S', 'New', 1, 59, 59, 14.75, 'EMERALD SUNDAE/WILD HORSES APPAREL', 9, 5),
(2382, '888815848952', 'Connected Apparel Womens Plus Lace Sequined Evening Dress Purple 14W', 'New', 1, 109, 109, 27.25, 'CONNECTED APPAREL COMPANY LLC', 9, 5),
(2383, '888815857640', 'Connected Apparel Womens Green Ruched Floral Elbow Cowl Neck Above The Knee Wear to Work Sheath Dress 10', 'New', 1, 69, 69, 17.25, 'CONNECTED APPAREL COMPANY LLC', 9, 5),
(2384, '888815932675', 'Connected Apparel Womens Plus Cape Metallic Jumpsuit Black 14W', 'New', 1, 99, 99, 24.75, 'CONNECTED APPAREL COMPANY LLC', 9, 5),
(2385, '9349585195895', 'Bardot Womens Pink Sheer Ruffled Floral Spaghetti Strap V Neck Below The Knee Hi-Lo Dress Size S', 'New', 1, 109, 109, 27.25, 'BARDOT/BAROL PTY LTD', 9, 5),
(2386, '794795093025', 'S.L. Fashions Women\'s Plus Size Long Satin Party Dress 3/4 Sleeve and Sleeveless, Black Silver, 14W', 'New', 1, 139, 139, 34.75, 'SALLY LOU FASHIONS/S L FASHIONS', 9, 5),
(2387, '5257472495', 'Brinley Co Women\'s LANET Ballet Flat, Pink, 11 Regular US', 'New', 1, 45, 45, 13.64, 'BRINLEY CO', 9, 6),
(2388, '17114286008', 'SOUL Naturalizer Women\'s Stellar Flat Sandal, Black, 8 Wide', 'New', 1, 80, 80, 24.24, 'NATUALIZER SOUL/CALERES INC', 9, 6),
(2389, '17115795660', 'LifeStride Women\'s Deja Vu Ballet Flat, Black, 8.5 M US', 'New', 1, 60, 60, 18.18, 'LIFESTRIDE/CALERES INC', 9, 6),
(2390, '17116991993', 'Dr. Scholl\'s No Bad Vibes White 7 M', 'New', 1, 75, 75, 22.73, 'DR. SCHOLLS/CALERES INC', 9, 6),
(2391, '52574557824', 'Brinley Co Comfort Womens Espadrille Ankle Strap Wedge Grey, 7 Regular US', 'New', 1, 69, 69, 20.91, 'KNS INTERNATIONAL', 9, 6),
(2392, '52574612530', 'Journee Collection Solay Black 8.5', 'New', 1, 49, 49, 14.85, 'KNS INTERNATIONAL', 9, 6),
(2393, '52574762150', 'Journee Collection Womens Aubrinn Sandal Pink, 8.5 Womens US', 'New', 1, 64.99, 64.99, 19.69, 'KNS INTERNATIONAL', 9, 6),
(2394, '52574769999', 'Journee Collection Womens Jenice Wedge Sandal Black, 11 Womens US', 'New', 1, 79.99, 79.99, 24.24, 'KNS INTERNATIONAL', 9, 6),
(2395, '190748604384', 'RIALTO Shoes Sunnyside II Women\'s Flat, RED/Smooth, 8 M', 'New', 1, 49, 49, 14.85, 'RIALTO/CONNORS FOOTWEAR', 9, 6),
(2396, '191045700670', 'Sugar Women\'s Evermore Comfortable Slip On Espadrille Flats Fashion Sneaker Shoe with Cute Designs 7.5 Beach Stripe', 'New', 1, 44.99, 44.99, 13.63, 'RAMPAGE/ES ORIGINALS', 9, 6),
(2397, '191045834887', 'Sugar Women\'s Noelle Low Two Piece Block Heel Dress Shoe Ladies Ankle Strap Pump Sandal Baby Blue 8', 'New', 1, 50, 50, 15.15, 'RAMPAGE/ES ORIGINALS', 9, 6),
(2398, '193553731436', 'Sorel Women\'s Kinetic Lite Lace Sneakers, Black, 5 Medium US', 'New', 1, 120, 120, 36.36, 'SOREL/COLUMBIA BRANDS USA, LLC', 9, 6),
(2399, '193569379912', 'Gentle Souls by Kenneth Cole Women\'s Lavern Easy Strap Platform Sandal, Ice, 8', 'New', 1, 189, 189, 57.27, 'GENTLE SOULS/KENNETH COLE PRODUCTNS', 9, 6),
(2400, '885660622256', 'Easy Street womens Waive pumps shoes, Black, 7.5 Wide US', 'New', 1, 55, 55, 16.67, 'EASY STREET SALES CORP', 9, 6),
(2401, '885660700435', 'Easy Street Womens Waive Square Toe Kitten Pumps - Black - Size 8 B', 'New', 1, 50, 50, 15.15, 'EASY STREET SALES CORP', 9, 6),
(2402, '885660700442', 'Easy Street Womens Waive Closed Toe Classic Pumps, Black Patent, Size 8.5', 'New', 1, 50, 50, 15.15, 'EASY STREET SALES CORP', 9, 6),
(2403, '889885358938', 'Easy Street Women\'s Proper Dress Pump, Black, 7.5 W US', 'New', 1, 55, 55, 16.67, 'EASY STREET SALES CORP', 9, 6),
(2404, '889885359102', 'Easy Street Women\'s Proper Dress Pump, Black, 11 2W US', 'New', 1, 55, 55, 16.67, 'EASY STREET SALES CORP', 9, 6),
(2405, '889885748982', 'Easy Street Women\'s Tarrah Wedge Sandal, Gold Glitter, 10 W US', 'New', 1, 60, 60, 18.18, 'EASY STREET SALES CORP', 9, 6),
(2406, '889885797829', 'Bella Vita Women\'s Scarlett Pump, Black LEA, 8.5 N US', 'New', 1, 100, 100, 30.3, 'BELLA VITA', 9, 6),
(2407, '17118004097', 'Life Stride Ally Women\'s Boot 8 B(M) US Black-Suede', 'New', 1, 75, 75, 22.73, 'LIFESTRIDE/CALERES INC', 9, 6),
(2408, '17118005100', 'Life Stride Ally Women\'s Boot 9 B(M) US Mushroom-Suede', 'New', 1, 75, 75, 22.73, 'LIFESTRIDE/CALERES INC', 9, 6),
(2409, '17121662536', 'Franco Sarto Women\'s Tribute Knee High Boot, Mulberry, 9.5', 'New', 1, 149, 149, 45.15, 'FRANCO SARTO/CALERES INC', 9, 6),
(2410, '52574666359', 'Journee Collection Women\'s Pumps, Grey, 7.5', 'New', 1, 65, 65, 19.7, 'KNS INTERNATIONAL', 9, 6),
(2411, '191154784905', 'Sanctuary Social Fatigue Camo Pr Recycled Fabric 8 M', 'New', 1, 99, 99, 30, 'SANCTUARY/HIGHLINE UNITED LLC', 9, 6),
(2412, '716142102921', 'NINA Rhiyana Black Luster Satin 9.5', 'New', 1, 79, 79, 23.94, 'NINA FOOTWEAR', 9, 6),
(2413, '736705464693', 'LifeStride womens Giada Ankle Boot, Black, 9.5 US', 'New', 1, 60, 60, 18.18, 'LIFESTRIDE/CALERES INC', 9, 6),
(2414, '885660473117', 'Easy Street Women\'s Passion Dress Pump,Black,8.5 M US', 'New', 1, 55, 55, 16.67, 'EASY STREET SALES CORP', 9, 6),
(2415, '889309535662', 'Clarks Women\'s Hollis Star Ankle Boot, Black Suede Combi, 8', 'New', 1, 135, 135, 40.91, 'CLARKS OF ENGLAND', 9, 6),
(2416, '889885358969', 'Easy Street Women\'s Proper Dress Pump, Black, 9 W US', 'New', 1, 55, 55, 16.67, 'EASY STREET SALES CORP', 9, 6),
(2417, '889885800253', 'Bella Vita Women\'s SCARLETTII Pump, Nude PAT, 8.5 M US', 'New', 1, 80, 80, 24.24, 'BELLA VITA', 9, 6),
(2418, '191045834696', 'Sugar Women\'s Noelle Low Two Piece Block Heel Dress Shoe Ladies Ankle Strap Pump Sandal Nude 8.5', 'New', 1, 50, 50, 15.15, 'RAMPAGE/ES ORIGINALS', 9, 6),
(2419, '191045700663', 'Sugar Women\'s Evermore Comfortable Slip On Espadrille Flats Fashion Sneaker Shoe with Cute Designs 7 Beach Stripe', 'New', 1, 44.99, 44.99, 13.63, 'RAMPAGE/ES ORIGINALS', 9, 6),
(2420, '191045662329', 'Sugar Womens Gabber Fabric Low Top Lace Up Fashion Sneakers Black', 'New', 1, 60, 60, 18.18, 'RAMPAGE/ES ORIGINALS', 9, 6),
(2421, '636193937818', 'Style & Co. Womens Chicklet Open Toe Casual Platform Sandals, White, Size 11.0', 'New', 1, 49.5, 49.5, 15, 'STYLE & CO-MMG', 9, 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `role` enum('client','superadmin') NOT NULL DEFAULT 'client',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `company`, `email`, `address`, `username`, `password`, `photo`, `role`, `created_at`, `updated_at`) VALUES
(3, 'LINGGA PANGESTU  Rac', 'Smart Wholesale LLC', 'lingga@buysmartwholesale.com', 'GG.RD.JIBJA, RT/RW 005/002, Kel/Desa CICAHEUM, Kecamatan KIARACONDONG', 'admin', '$2y$10$fA0w1YCmfdX0WaOyA60ORuYU2tONCwOrQRD0wZElC/uUz07AmxYFu', '1640641198taekwondo.png', 'superadmin', NULL, NULL),
(4, 'Andrew Bath', 'Smart Wholesale LLC55', 'test@buysmartwholesale.com', 'teeee', 'test', '$2y$10$oOgpLl5tk3bRAotwegJs3ex/piYCrydso0ovfweS/erItjD7FokoO', '1640768881Daco_4363443.png', 'client', NULL, NULL),
(7, 'Lingga Pangestyu', 'test2', NULL, 'test2', 'test2', '$2y$10$YmbwDDXVpBA.j34Knyg25uPFNGDj4t00fjLQH2tPiXxcdcnBp4y/2', NULL, 'client', NULL, NULL),
(9, 'Stevan VCain', 'A55 Store', NULL, '5', '5', '$2y$10$v/FPniaWaAgZuUxB7sTU5.ZvWeG1JEPBxGtu4T2TLeE7Irr4bXmbO', NULL, 'client', NULL, NULL),
(10, 'John Cena', '6', NULL, '6', '6', '$2y$10$BC9aQB/nU3o/5N1Pf3hUNuxv/y.VtjcGEu9e0cyK7DG9styAulGWy', NULL, 'client', NULL, NULL),
(11, 'Michael Carrick', 'temp', NULL, 'temp', 'temp', '$2y$10$znhyR4f4flkhfHNVbndffePxE/r3sr/2Obc.uTF5ilMqG7PQs6CKC', NULL, 'client', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assign_reports`
--
ALTER TABLE `assign_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assign_report_box`
--
ALTER TABLE `assign_report_box`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assign_report_details`
--
ALTER TABLE `assign_report_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chart_pl`
--
ALTER TABLE `chart_pl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_files`
--
ALTER TABLE `log_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assign_reports`
--
ALTER TABLE `assign_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `assign_report_box`
--
ALTER TABLE `assign_report_box`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assign_report_details`
--
ALTER TABLE `assign_report_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `chart_pl`
--
ALTER TABLE `chart_pl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `log_files`
--
ALTER TABLE `log_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2422;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
