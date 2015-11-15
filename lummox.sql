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
	(1, 'Lock, Stock and Two Smoking Barrels', '1998-08-28', 1, 'Crime comedy', 'Vice health goth banjo twee normcore, yuccie microdosing dreamcatcher. Brunch artisan 90\'s, flannel kitsch before they sold out kombucha plaid sartorial. 8-bit pitchfork schlitz bushwick, flannel next level vice artisan roof party. Listicle twee blue bottle, slow-carb craft beer tilde before they sold out brooklyn squid locavore tote bag tattooed literally. Schlitz biodiesel knausgaard, venmo fingerstache kickstarter cold-pressed VHS ethical. Blue bottle celiac wolf, kickstarter green juice selfies fanny pack etsy. 90\'s polaroid ugh shoreditch twee salvia squid food truck brunch hashtag.', 81),
	(2, 'Snatch', '2000-08-23', 1, 'Crime comedy', 'Marfa gluten-free XOXO, cred forage cray fingerstache roof party stumptown. Polaroid iPhone selfies vegan. Keffiyeh put a bird on it vinyl, brunch offal butcher helvetica. Fixie retro freegan ugh. Synth church-key bitters, flexitarian post-ironic organic kogi polaroid mlkshk locavore asymmetrical hammock salvia skateboard. Schlitz disrupt slow-carb, actually twee cray +1. Kitsch kombucha celiac, authentic vegan hella chicharrones mumblecore tousled disrupt offal shoreditch.', 74);
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table lummox.reviews: ~9 rows (suunnilleen)
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` (`review_id`, `review_author`, `review_title`, `review_score`, `review_text`, `review_datetime`, `review_film_id`, `review_status_id`) VALUES
	(1, 2, 'Awesome film', 99, 'Butcher mustache dreamcatcher, kombucha waistcoat sartorial swag art party. Chillwave franzen blog cardigan try-hard biodiesel. Health goth craft beer cold-pressed, master cleanse quinoa yr hella DIY celiac sustainable 3 wolf moon sriracha meggings. Brunch semiotics fap, actually chillwave health goth tousled schlitz post-ironic godard cardigan stumptown. ', '2015-11-15 17:30:22', 1, 2),
	(2, 1, 'I don\'t get this film', 25, 'Schlitz chia portland pabst. Asymmetrical truffaut PBR&B deep v. Neutra four dollar toast jean shorts, green juice celiac pork belly banjo pitchfork aesthetic ramps godard stumptown bicycle rights paleo plaid. Squid narwhal salvia thundercats fanny pack, DIY beard four dollar toast photo booth keffiyeh tofu irony lumbersexual.', '2015-11-15 17:30:22', 1, 2),
	(8, 8, 'Butcher ennui yuccie', 34, 'Butcher ennui yuccie, venmo hashtag vice selfies pinterest lomo irony raw denim. Banh mi blue bottle chia, wayfarers kinfolk brooklyn bitters pork belly pabst. Echo park kitsch pitchfork selfies, readymade forage umami neutra. Meditation venmo salvia, poutine viral kinfolk kale chips green juice forage. PBR&B next level blog poutine direct trade, polaroid kickstarter. Ennui cornhole gluten-free williamsburg sustainable poutine. Hammock try-hard pork belly, cred drinking vinegar vegan trust fund pabst seitan you probably haven\'t heard of them semiotics raw denim.\n\nLiterally hashtag seitan chicharrones bitters whatever, master cleanse iPhone banjo fap. Slow-carb jean shorts you probably haven\'t heard of them tofu kickstarter. Tote bag migas typewriter, locavore gastropub umami hashtag messenger bag farm-to-table trust fund sartorial health goth deep v next level chillwave. Wolf art party chambray poutine, williamsburg schlitz cliche tattooed bushwick pinterest letterpress cred. Listicle knausgaard marfa banjo jean shorts, pabst pork belly street art artisan. Slow-carb austin kinfolk PBR&B photo booth. Pitchfork polaroid humblebrag, literally roof party hoodie readymade irony 90\'s helvetica bicycle rights normcore master cleanse tattooed.', '2015-11-15 19:57:52', 1, 2),
	(9, 8, 'Squid roof party distillery celiac. Cray DIY lomo', 99, 'Squid roof party distillery celiac. Cray DIY lomo typewriter pork belly, semiotics intelligentsia quinoa synth paleo stumptown tattooed hashtag retro street art. IPhone street art meggings godard XOXO aesthetic. Raw denim ethical sriracha meh leggings. Pop-up polaroid 8-bit, beard umami literally four dollar toast fanny pack lomo cray lo-fi quinoa typewriter flexitarian. Wayfarers DIY keffiyeh, yuccie humblebrag literally hashtag. Lumbersexual fap artisan, flexitarian cornhole truffaut tousled dreamcatcher cronut meditation flannel.\n\nSeitan waistcoat bushwick truffaut, shabby chic cardigan fixie taxidermy ugh 90\'s polaroid keffiyeh health goth bitters trust fund. Authentic deep v squid church-key 8-bit, viral yuccie tousled gentrify freegan try-hard cray occupy. Put a bird on it chicharrones tattooed, truffaut chillwave synth sustainable kale chips vice butcher gentrify next level shoreditch cray meditation. Health goth echo park literally, crucifix chartreuse 90\'s authentic lumbersexual venmo chillwave church-key. Tattooed four dollar toast wolf banjo portland, ethical cold-pressed tilde. Raw denim taxidermy stumptown, squid actually trust fund YOLO sustainable butcher blog. Tattooed photo booth listicle fanny pack biodiesel, austin kogi pickled franzen.', '2015-11-15 19:58:30', 2, 2),
	(10, 9, 'Squid roof party distillery celiac. Cray DIY lomo', 100, 'Squid roof party distillery celiac. Cray DIY lomo typewriter pork belly, semiotics intelligentsia quinoa synth paleo stumptown tattooed hashtag retro street art. IPhone street art meggings godard XOXO aesthetic. Raw denim ethical sriracha meh leggings. Pop-up polaroid 8-bit, beard umami literally four dollar toast fanny pack lomo cray lo-fi quinoa typewriter flexitarian. Wayfarers DIY keffiyeh, yuccie humblebrag literally hashtag. Lumbersexual fap artisan, flexitarian cornhole truffaut tousled dreamcatcher cronut meditation flannel.\n\nSeitan waistcoat bushwick truffaut, shabby chic cardigan fixie taxidermy ugh 90\'s polaroid keffiyeh health goth bitters trust fund. Authentic deep v squid church-key 8-bit, viral yuccie tousled gentrify freegan try-hard cray occupy. Put a bird on it chicharrones tattooed, truffaut chillwave synth sustainable kale chips vice butcher gentrify next level shoreditch cray meditation. Health goth echo park literally, crucifix chartreuse 90\'s authentic lumbersexual venmo chillwave church-key. Tattooed four dollar toast wolf banjo portland, ethical cold-pressed tilde. Raw denim taxidermy stumptown, squid actually trust fund YOLO sustainable butcher blog. Tattooed photo booth listicle fanny pack biodiesel, austin kogi pickled franzen.', '2015-11-15 20:16:47', 1, 1),
	(11, 3, 'Seitan waistcoat bushwick truffaut, shabby chic ca', 100, 'Seitan waistcoat bushwick truffaut, shabby chic cardigan fixie taxidermy ugh 90\'s polaroid keffiyeh health goth bitters trust fund. Authentic deep v squid church-key 8-bit, viral yuccie tousled gentrify freegan try-hard cray occupy. Put a bird on it chicharrones tattooed, truffaut chillwave synth sustainable kale chips vice butcher gentrify next level shoreditch cray meditation. Health goth echo park literally, crucifix chartreuse 90\'s authentic lumbersexual venmo chillwave church-key. Tattooed four dollar toast wolf banjo portland, ethical cold-pressed tilde. Raw denim taxidermy stumptown, squid actually trust fund YOLO sustainable butcher blog. Tattooed photo booth listicle fanny pack biodiesel, austin kogi pickled franzen.', '2015-11-15 20:44:43', 1, 2),
	(20, 3, 'Poutine stumptown mustache lo-fi tumblr, heirloom', 10, 'Poutine stumptown mustache lo-fi tumblr, heirloom lomo tattooed. Williamsburg hammock cray, fap wolf keytar microdosing XOXO viral. Quinoa offal readymade, helvetica pug seitan swag farm-to-table. Hammock 90\'s venmo, four dollar toast listicle thundercats 8-bit pug drinking vinegar umami truffaut occupy kitsch. Disrupt typewriter organic, pug butcher 8-bit dreamcatcher bitters neutra gastropub. Celiac tousled VHS dreamcatcher flannel, pug farm-to-table artisan meh beard whatever four loko. Swag stumptown poutine, PBR&B shabby chic echo park XOXO actually.', '2015-11-15 21:09:47', 2, 2),
	(21, 1, 'Flexitarian fixie iPhone sriracha cronut. 90\'s tou', 90, 'Flexitarian fixie iPhone sriracha cronut. 90\'s tousled DIY, williamsburg etsy scenester lomo disrupt. Chillwave tumblr master cleanse, mumblecore fixie photo booth ugh next level stumptown keffiyeh tote bag listicle wayfarers typewriter lo-fi. Meggings schlitz 8-bit brunch organic, chambray tilde yr tousled four loko irony selvage aesthetic. Mumblecore crucifix gluten-free roof party, iPhone austin echo park. Slow-carb letterpress organic humblebrag. Polaroid normcore aesthetic, wayfarers seitan art party tacos hoodie chambray microdosing single-origin coffee.', '2015-11-15 21:11:17', 2, 2),
	(22, 2, 'Seitan waistcoat bushwick truffaut, shabby chic ca', 97, 'Seitan waistcoat bushwick truffaut, shabby chic cardigan fixie taxidermy ugh 90\'s polaroid keffiyeh health goth bitters trust fund. Authentic deep v squid church-key 8-bit, viral yuccie tousled gentrify freegan try-hard cray occupy. Put a bird on it chicharrones tattooed, truffaut chillwave synth sustainable kale chips vice butcher gentrify next level shoreditch cray meditation. Health goth echo park literally, crucifix chartreuse 90\'s authentic lumbersexual venmo chillwave church-key. Tattooed four dollar toast wolf banjo portland, ethical cold-pressed tilde. Raw denim taxidermy stumptown, squid actually trust fund YOLO sustainable butcher blog. Tattooed photo booth listicle fanny pack biodiesel, austin kogi pickled franzen.', '2015-11-15 21:12:05', 2, 2);
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Dumping data for table lummox.users: ~5 rows (suunnilleen)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`user_id`, `user_role_id`, `user_name`, `user_email`, `password`) VALUES
	(1, 1, 'MasterAdmin', 'jkatajamki@gmail.com', 'adminpass'),
	(2, 3, 'User1', 'some@email.com', 'userpass'),
	(3, 3, 'User2', 'bnlanla@sadf', 'whatever'),
	(8, 6, 'Butcher', NULL, NULL),
	(9, 6, 'Squid', NULL, NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
