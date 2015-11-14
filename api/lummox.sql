CREATE TABLE IF NOT EXISTS directors (
director_id int(11) NOT NULL AUTO_INCREMENT,
director_name varchar(100) COLLATE utf8_bin DEFAULT NULL,
director_bio text COLLATE utf8_bin DEFAULT NULL,
director_dob date DEFAULT NULL,
director_origin varchar(50) COLLATE utf8_bin DEFAULT NULL,
director_imgpath varchar(255) COLLATE utf8_bin DEFAULT NULL,
PRIMARY KEY (director_id)
)ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS films (
film_id int(11) NOT NULL AUTO_INCREMENT,
film_title varchar(50) COLLATE utf8_bin DEFAULT NULL,
film_release_date date DEFAULT NULL,
film_director_id int(11) DEFAULT NULL,
film_genre varchar(50) COLLATE utf8_bin DEFAULT NULL,
film_description text COLLATE utf8_bin DEFAULT NULL,
PRIMARY KEY (film_id),FOREIGN KEY (film_director_id) REFERENCES directors(director_id)
)ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE roles (
role_id int(11) NOT NULL AUTO_INCREMENT,
role_name varchar(20) COLLATE utf8_bin DEFAULT NULL,
role_desc varchar(255) COLLATE utf8_bin DEFAULT NULL,
PRIMARY KEY (role_id)
)ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS users (
user_id int(11) NOT NULL AUTO_INCREMENT,
user_role_id int(11) NOT NULL,
user_name varchar(25) COLLATE utf8_bin DEFAULT NULL,
user_email varchar(100) COLLATE utf8_bin DEFAULT NULL,
password varchar(1000) COLLATE utf8_bin DEFAULT NULL,
PRIMARY KEY (user_id),
FOREIGN KEY (user_role_id) REFERENCES roles(role_id)
)ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS reviews (
review_id int(11) NOT NULL AUTO_INCREMENT,
review_author int(11) NOT NULL,
review_title varchar(30) COLLATE utf8_bin DEFAULT NULL,
review_score int(3) DEFAULT NULL,
review_text text COLLATE utf8_bin DEFAULT NULL,
review_datetime datetime DEFAULT NOW(),
PRIMARY KEY (review_id),
FOREIGN KEY (review_author) REFERENCES users (user_id)
)ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



INSERT INTO directors (director_id, director_name, director_bio, director_dob, director_origin) VALUES
(1, "Guy Ritchie", "This is bio", "1968-09-10", "England")
;

INSERT INTO films (film_id, film_title, film_release_date, film_director_id, film_genre, film_description) VALUES
(1, "Lock, Stock and Two Smoking Barrels", "1998-08-28", 1, "Crime comedy", "This describes the film"),
(2, "Snatch", "2000-08-23", 1, "Crime comedy", "This describes the film")
;

INSERT INTO roles (role_id, role_name, role_desc) VALUES
(1, "Admin", "The head admin of the site"),
(2, "Moderator", "The hall janitor"),
(3, "User", "Registered user"),
(4, "Unverified", "Unverified email"),
(5, "Banned", "The dregs")
;

INSERT INTO users (user_id, user_role_id, user_name, user_email, password) VALUES
(1, 1, "MasterAdmin", "jkatajamki@gmail.com", "adminpass"),
(2, 3, "User1", "some@email.com", "userpass")
;

INSERT INTO reviews (review_id, review_author, review_title, review_score, review_text) VALUES
(1, 2, "Awesome film", 99, "This is a review of the film")
;