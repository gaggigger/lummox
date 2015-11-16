-- --------------------------------------------------------
-- Verkkotietokone:              localhost
-- Palvelinversio:               5.6.21 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Versio:              9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for lummox
CREATE DATABASE IF NOT EXISTS `lummox` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_bin */;
USE `lummox`;


-- Dumping structure for taulu lummox.directors
CREATE TABLE IF NOT EXISTS `directors` (
  `director_id` int(11) NOT NULL AUTO_INCREMENT,
  `director_name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `director_bio` text COLLATE utf8_bin,
  `director_dob` date DEFAULT NULL,
  `director_origin` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `director_imgpath` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`director_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table lummox.directors: ~1 rows (suunnilleen)
/*!40000 ALTER TABLE `directors` DISABLE KEYS */;
INSERT INTO `directors` (`director_id`, `director_name`, `director_bio`, `director_dob`, `director_origin`, `director_imgpath`) VALUES
	(1, 'Guy Ritchie', 'This is bio', '1968-09-10', 'England', NULL);
/*!40000 ALTER TABLE `directors` ENABLE KEYS */;


-- Dumping structure for taulu lummox.films
CREATE TABLE IF NOT EXISTS `films` (
  `film_id` int(11) NOT NULL AUTO_INCREMENT,
  `film_title` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `film_release_date` date DEFAULT NULL,
  `film_director_id` int(11) DEFAULT NULL,
  `film_genre` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `film_description` text COLLATE utf8_bin,
  `film_review_average` int(3) DEFAULT NULL,
  PRIMARY KEY (`film_id`),
  KEY `film_director_id` (`film_director_id`),
  CONSTRAINT `films_ibfk_1` FOREIGN KEY (`film_director_id`) REFERENCES `directors` (`director_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table lummox.films: ~2 rows (suunnilleen)
/*!40000 ALTER TABLE `films` DISABLE KEYS */;
INSERT INTO `films` (`film_id`, `film_title`, `film_release_date`, `film_director_id`, `film_genre`, `film_description`, `film_review_average`) VALUES
	(1, 'Lock, Stock and Two Smoking Barrels', '1998-08-28', 1, 'Crime comedy', 'Vice health goth banjo twee normcore, yuccie microdosing dreamcatcher. Brunch artisan 90\'s, flannel kitsch before they sold out kombucha plaid sartorial. 8-bit pitchfork schlitz bushwick, flannel next level vice artisan roof party. Listicle twee blue bottle, slow-carb craft beer tilde before they sold out brooklyn squid locavore tote bag tattooed literally. Schlitz biodiesel knausgaard, venmo fingerstache kickstarter cold-pressed VHS ethical. Blue bottle celiac wolf, kickstarter green juice selfies fanny pack etsy. 90\'s polaroid ugh shoreditch twee salvia squid food truck brunch hashtag.', 87),
	(2, 'Snatch', '2000-08-23', 1, 'Crime comedy', 'Marfa gluten-free XOXO, cred forage cray fingerstache roof party stumptown. Polaroid iPhone selfies vegan. Keffiyeh put a bird on it vinyl, brunch offal butcher helvetica. Fixie retro freegan ugh. Synth church-key bitters, flexitarian post-ironic organic kogi polaroid mlkshk locavore asymmetrical hammock salvia skateboard. Schlitz disrupt slow-carb, actually twee cray +1. Kitsch kombucha celiac, authentic vegan hella chicharrones mumblecore tousled disrupt offal shoreditch.', 69);
/*!40000 ALTER TABLE `films` ENABLE KEYS */;


-- Dumping structure for taulu lummox.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `review_author` int(11) NOT NULL,
  `review_title` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `review_score` int(3) DEFAULT NULL,
  `review_text` text COLLATE utf8_bin,
  `review_datetime` datetime DEFAULT CURRENT_TIMESTAMP,
  `review_film_id` int(11) NOT NULL,
  `review_status_id` int(11) NOT NULL,
  PRIMARY KEY (`review_id`),
  KEY `review_author` (`review_author`),
  KEY `review_status_id` (`review_status_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`review_author`) REFERENCES `users` (`user_id`),
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`review_status_id`) REFERENCES `review_status` (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table lummox.reviews: ~6 rows (suunnilleen)
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` (`review_id`, `review_author`, `review_title`, `review_score`, `review_text`, `review_datetime`, `review_film_id`, `review_status_id`) VALUES
	(23, 1, 'This film blows my mind!', 98, 'Four loko pug next level, cliche keffiyeh green juice beard slow-carb brooklyn microdosing venmo readymade. Pork belly truffaut you probably haven\'t heard of them, mustache taxidermy four dollar toast celiac forage typewriter irony kitsch try-hard post-ironic. PBR&B narwhal offal tacos taxidermy selvage, pug small batch seitan viral pickled actually. Forage aesthetic pabst echo park, semiotics tattooed four dollar toast sartorial. Kombucha wolf irony, put a bird on it etsy pug pickled small batch farm-to-table yr thundercats. Kinfolk PBR&B chartreuse chambray, narwhal meggings pickled drinking vinegar semiotics hoodie ennui selvage godard. Disrupt celiac humblebrag banjo ethical.\n\nKeytar tousled everyday carry, YOLO hella food truck distillery XOXO. Vinyl letterpress paleo, umami cornhole vegan roof party. Fashion axe plaid bespoke, next level DIY raw denim helvetica mumblecore offal. Master cleanse kogi chartreuse jean shorts, iPhone wayfarers celiac tousled salvia gluten-free heirloom +1 kickstarter PBR&B. Vice chambray messenger bag kitsch, cronut pickled sustainable health goth scenester flexitarian celiac salvia deep v. Messenger bag sriracha PBR&B, sartorial cronut viral semiotics. Gentrify migas salvia 8-bit four loko.', '2015-11-17 00:53:00', 1, 2),
	(24, 1, 'I don\'t get this film at all', 28, 'PBR&B quinoa cold-pressed waistcoat whatever synth. Umami distillery master cleanse selvage, 8-bit forage meh. Fingerstache vegan etsy raw denim. Bicycle rights kinfolk lo-fi tousled keytar, mixtape kogi selvage put a bird on it beard pug messenger bag sustainable. Drinking vinegar pug you probably haven\'t heard of them flannel. Photo booth swag bicycle rights DIY, truffaut cliche chicharrones actually keffiyeh health goth trust fund blog meggings YOLO tumblr. Crucifix thundercats master cleanse franzen 8-bit williamsburg occupy freegan sustainable, aesthetic semiotics venmo fashion axe.\n\nActually artisan bespoke, cred jean shorts chartreuse listicle lomo butcher ugh humblebrag pinterest shabby chic. XOXO occupy chartreuse, pitchfork scenester jean shorts keytar YOLO meditation austin pour-over. Narwhal direct trade cornhole portland bespoke. Venmo tousled lo-fi, semiotics poutine artisan schlitz wolf street art cornhole put a bird on it readymade seitan chicharrones. Kickstarter chartreuse brunch, narwhal lumbersexual pour-over kitsch literally selvage. Beard wayfarers flexitarian meditation. Hella meggings portland, pinterest drinking vinegar small batch master cleanse before they sold out literally swag.', '2015-11-17 00:53:44', 2, 2),
	(25, 11, 'Shabby chic lo-fi photo booth', 76, 'Austin thundercats kickstarter, williamsburg sriracha yr cornhole pug microdosing craft beer. Flannel messenger bag post-ironic waistcoat. Ethical locavore direct trade, biodiesel VHS mixtape narwhal kale chips brunch health goth. Bitters schlitz migas kitsch cray. Selvage kickstarter pickled crucifix beard, sriracha cornhole microdosing wayfarers health goth mustache meditation leggings letterpress farm-to-table. Deep v neutra street art umami pabst blue bottle. Fanny pack VHS neutra vegan four dollar toast kogi.\n\nMustache sriracha jean shorts, VHS trust fund church-key intelligentsia keffiyeh vegan messenger bag heirloom pitchfork +1 meggings portland. Brunch fixie squid, brooklyn narwhal selvage lo-fi cornhole shoreditch. Paleo roof party tacos four loko taxidermy cray cred. Fanny pack XOXO chambray, PBR&B +1 ugh swag austin vegan before they sold out 8-bit cliche squid. Disrupt leggings bitters, yuccie selfies authentic mustache selvage. Four dollar toast shabby chic PBR&B hammock crucifix, tofu waistcoat swag 90\'s heirloom blog mustache iPhone wayfarers yuccie. 8-bit DIY wayfarers, four loko ennui green juice photo booth shabby chic bushwick trust fund single-origin coffee plaid slow-carb chia.', '2015-11-17 00:56:26', 1, 2),
	(26, 11, 'Four loko microdosing', 89, 'Pitchfork brunch cronut kale chips, banjo kickstarter gluten-free. Bitters artisan blue bottle roof party single-origin coffee before they sold out. Synth tacos meditation, sartorial ugh whatever chillwave fixie authentic occupy blog single-origin coffee gluten-free. Literally VHS you probably haven\'t heard of them deep v banjo freegan, hashtag lo-fi skateboard. Readymade vinyl sartorial meh iPhone. Viral banjo raw denim, forage YOLO meggings pop-up four loko. Lomo normcore gluten-free aesthetic, lo-fi messenger bag fanny pack offal stumptown kombucha mumblecore chicharrones.\n\nCronut jean shorts kogi, pitchfork tumblr ramps health goth humblebrag shoreditch gastropub intelligentsia. Williamsburg tumblr semiotics photo booth, swag craft beer tousled put a bird on it franzen heirloom hoodie raw denim sustainable tilde cold-pressed.\n\nSustainable messenger bag narwhal, ennui retro waistcoat cardigan cornhole craft beer chartreuse. Seitan organic PBR&B pinterest intelligentsia. Twee drinking vinegar cred flannel.', '2015-11-17 00:58:02', 2, 2),
	(27, 12, 'Poutine pop-up mlkshk williamsburg', 97, 'Shoreditch fanny pack godard yuccie semiotics disrupt green juice mustache +1. Synth kogi swag pork belly health goth, humblebrag whatever fanny pack offal thundercats organic butcher taxidermy. Crucifix photo booth master cleanse chicharrones, selvage try-hard ramps echo park scenester kitsch asymmetrical mlkshk cronut green juice. Bicycle rights messenger bag tofu single-origin coffee selfies distillery. Pitchfork ramps swag tumblr stumptown. Flexitarian scenester sartorial pitchfork dreamcatcher etsy. Stumptown vinyl fap 90\'s, pinterest schlitz brooklyn mlkshk hoodie direct trade chicharrones migas.', '2015-11-17 01:01:49', 2, 1),
	(28, 13, 'Cronut disrupt retro knausgaard', 90, 'Austin gentrify pickled banjo, kale chips +1 crucifix normcore master cleanse pabst blue bottle readymade echo park retro. Crucifix bushwick wolf blue bottle, gluten-free dreamcatcher schlitz irony single-origin coffee flannel bicycle rights. Bicycle rights deep v blog, four dollar toast bitters twee paleo salvia mumblecore meggings brunch pork belly cold-pressed roof party. Chia mixtape crucifix tilde cliche drinking vinegar pitchfork, hella selfies kogi vinyl. Whatever portland shoreditch, chillwave sustainable aesthetic synth craft beer keytar. Tote bag kale chips blue bottle mumblecore letterpress. Cornhole selfies pitchfork salvia drinking vinegar yuccie.\n\nBeard cold-pressed chambray sustainable, kombucha synth vinyl mustache gluten-free poutine kitsch church-key pug. Narwhal normcore tattooed whatever, blue bottle squid pop-up. Freegan poutine PBR&B, meditation messenger bag helvetica mlkshk raw denim semiotics williamsburg blue bottle truffaut. Schlitz roof party taxidermy asymmetrical. Brunch mixtape microdosing, offal synth keytar locavore. Twee wolf tattooed deep v slow-carb, taxidermy cardigan gastropub scenester neutra keytar chia YOLO +1. Umami everyday carry ennui pitchfork.', '2015-11-17 01:05:50', 2, 2);
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;


-- Dumping structure for taulu lummox.review_status
CREATE TABLE IF NOT EXISTS `review_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table lummox.review_status: ~3 rows (suunnilleen)
/*!40000 ALTER TABLE `review_status` DISABLE KEYS */;
INSERT INTO `review_status` (`status_id`, `status_name`) VALUES
	(1, 'Pending'),
	(2, 'Published'),
	(3, 'Deleted');
/*!40000 ALTER TABLE `review_status` ENABLE KEYS */;


-- Dumping structure for taulu lummox.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `role_desc` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table lummox.roles: ~6 rows (suunnilleen)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`role_id`, `role_name`, `role_desc`) VALUES
	(1, 'Admin', 'The head admin of the site'),
	(2, 'Moderator', 'The hall janitor'),
	(3, 'User', 'Registered user'),
	(4, 'Unverified', 'Unverified email'),
	(5, 'Banned', 'The dregs'),
	(6, 'Unregistered', 'Unregistered users');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;


-- Dumping structure for taulu lummox.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_role_id` int(11) NOT NULL,
  `user_name` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `user_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(1000) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_role_id` (`user_role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_role_id`) REFERENCES `roles` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table lummox.users: ~4 rows (suunnilleen)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`user_id`, `user_role_id`, `user_name`, `user_email`, `password`) VALUES
	(1, 1, 'MasterAdmin', 'masteradmin@lummox.fi', '$2y$10$TWn8e0kP8C.nY1kZk3dnR.zwlyNKbhZfBt6s/aGrSn7FG5dfMuKAi'),
	(11, 3, 'someUser1', 'someuser12@jees.com', '$2y$10$haXeVeY1uw3y1poOhagnJuCegOzB2TZe4ZS7akQfEJGsU092Cfbqu'),
	(12, 6, 'justsomeguy1', 'justsomeemail@yes.com', NULL),
	(13, 3, 'filmLover25', 'filmlover25@dmail.com', '$2y$10$HfY.69CFNwAZOIL3FU0D0OP48qpYkWdAGHG4ydtzaTzokoNuMsdt6'),
	(14, 6, 'testtest', 'testtest@', NULL),
	(15, 3, 'AnotherUser2', 'another@user2.com', '$2y$10$RLcR92qh8mdDdjyGT8nvv.Qf6oh6Pk1WHsmPH7Lu744fV5igJmIWu');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
