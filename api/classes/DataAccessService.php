<?php

Class DataAccessService {

	private $pdo;
	
	function __construct($address, $port, $name, $user, $password) {
        $this->pdo = new PDO("mysql:host=$address;port=$port;dbname=$name;charset=utf8", $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getHello($helloId) {
        $query = $this->pdo->prepare("SELECT * FROM hello WHERE id=?");
        $query->bindParam(1, $helloId);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getReview($reviewId) {
        $query = $this->pdo->prepare("SELECT review_id, review_author, review_title, review_score, review_text, review_datetime, review_film_id, user_name, film_title FROM users JOIN reviews ON users.user_id = reviews.review_author JOIN films ON reviews.review_film_id = films.film_id WHERE review_id = ?");
        $query->bindParam(1, $reviewId);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getReviews() {
        $query = $this->pdo->prepare("SELECT review_id, review_author, review_title, review_score, review_text, review_datetime, review_film_id, film_title, user_name FROM users JOIN reviews ON users.user_id = reviews.review_author JOIN films ON reviews.review_film_id = films.film_id WHERE review_status_id = 2 ORDER BY review_datetime desc");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilms() {
        $query = "select film_id, film_title, film_release_date, film_director_id, film_genre, film_description, film_review_average, director_id, director_name FROM films JOIN directors ON films.film_director_id = directors.director_id order by film_release_date;";
        $query = $this->pdo->prepare($query);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilmReviews($filmId) {
        $query = $this->pdo->prepare("SELECT review_id, review_author, review_title, review_score, review_text, review_datetime, review_film_id, user_name, review_status_id FROM users JOIN reviews ON users.user_id = reviews.review_author JOIN review_status ON reviews.review_status_id = review_status.status_id WHERE review_film_id = ? && reviews.review_status_id = 2 ORDER BY review_datetime DESC");
        $query->bindParam(1, $filmId);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilmData($filmId) {
        $query = $this->pdo->prepare("select film_id, film_title, film_release_date, film_director_id, film_genre, film_description, film_review_average, director_id, director_name FROM films JOIN directors ON films.film_director_id = directors.director_id WHERE film_id = ?;");
        $query->bindParam(1, $filmId);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function createNewUser($data) {
        $unverifiedRoleId = 4;
        $query = $this->pdo->prepare("INSERT INTO users (user_role_id, user_name, user_email, password) VALUES (:role, :username, :email, :pw)");
        $query->bindParam(":role", $unverifiedRoleId);
        $query->bindParam(":username", $data->username);
        $query->bindParam(":email", $data->email);
        $query->bindParam(":pw", $data->password);
        $query->execute();
    }

    public function updateUserRole($username, $roleId = 4 /* unverified */) {
        $query = $this->pdo->prepare("UPDATE users SET user_role_id = ? WHERE user_name = ?");
        $query->bindParam(1, $roleId);
        $query->bindParam(2, $username);
        $query->execute();
    }

    public function getUsers() {
        $query = $this->pdo->prepare("SELECT user_id, user_role_id, user_name, role_name FROM roles JOIN users ON roles.role_id = users.user_role_id");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByName($username) {
        $query = $this->pdo->prepare("SELECT * FROM users WHERE user_name = ?");
        $query->bindParam(1, $username);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email) {
        $query = $this->pdo->prepare("SELECT * FROM users WHERE user_email = ?");
        $query->bindParam(1, $email);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserReviews($userId) {
        $query = $this->pdo->prepare("SELECT user_name, review_id, review_title, review_score, review_text, review_datetime, review_film_id, film_title FROM films JOIN reviews ON films.film_id = reviews.review_film_id JOIN users ON reviews.review_author = users.user_id WHERE user_id = ? && review_status_id = 2 ORDER BY review_datetime DESC");
        $query->bindParam(1, $userId);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUser($userId) {
        $query = $this->pdo->prepare("SELECT * FROM users JOIN roles ON users.user_role_id = roles.role_id WHERE user_id = ?");
        $query->bindParam(1, $userId);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByUsername($username) {
        $query = $this->pdo->prepare("SELECT * FROM users WHERE user_name = ?");
        $query->bindParam(1, $username);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getUsername($userId) {
        $query = $this->pdo->prepare("SELECT user_name FROM users WHERE user_id = ?");
        $query->bindParam(1, $userId);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserRole($username) {
        $query = $this->pdo->prepare("SELECT user_id, user_name, user_email, user_role_id, role_name FROM users JOIN roles ON users.user_role_id = roles.role_id WHERE user_name = ?");
        $query->bindParam(1, $username);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function isReviewedByUser($filmId, $userId) {
        $query = $this->pdo->prepare("SELECT * from reviews where review_author = ? && review_film_id = ?");
        $query->bindParam(1, $userId);
        $query->bindParam(2, $filmId);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (isset($result["review_id"])) {
            return true;
        } else if (!isset($result["review_id"])) {
            return false;
        }
    }

    public function createUnregisteredUser($username, $email) {
        $query = $this->pdo->prepare("INSERT INTO users (user_name, user_role_id, user_email) VALUES (:user_name, 6, :user_email)");
        $query->bindParam(":user_name", $username);
        $query->bindParam(":user_email",$email);
        $query->execute();
    }

    public function createReviewPending($review, $reviewAuthorId) {
        $query = $this->pdo->prepare("INSERT INTO reviews (review_author, review_title, review_score, review_text, review_film_id, review_status_id) VALUES(:review_author, :review_title, :review_score, :review_text, :review_film_id, 1) ");
        $query->bindParam(":review_author", $reviewAuthorId);
        $query->bindParam(":review_title", $review->reviewTitle);
        $query->bindParam(":review_score", $review->reviewScore);
        $query->bindParam(":review_text", $review->reviewText);
        $query->bindParam(":review_film_id", $review->reviewFilmId);
        $query->execute();
    }

    public function createReviewPublished($review, $reviewAuthorId) {
        $query = $this->pdo->prepare("INSERT INTO reviews (review_author, review_title, review_score, review_text, review_film_id, review_status_id)"
            . "VALUES(:review_author, :review_title, :review_score, :review_text, :review_film_id, 2) ");
        $query->bindParam(":review_author", $reviewAuthorId);
        $query->bindParam(":review_title", $review->reviewTitle);
        $query->bindParam(":review_score", $review->reviewScore);
        $query->bindParam(":review_text", $review->reviewText);
        $query->bindParam(":review_film_id", $review->reviewFilmId);
        $query->execute();
    }

    public function saveReview($review, $reviewAuthorId) {
        $query = $this->pdo->prepare("UPDATE reviews SET review_title = :title, review_score = :score, review_text = :text WHERE review_author = :author && review_film_id = :film ");
        $query->bindParam(":title", $review->reviewTitle);
        $query->bindParam(":score", $review->reviewScore);
        $query->bindParam(":text", $review->reviewText);
        $query->bindParam(":film", $review->reviewFilmId);
        $query->bindParam(":author", $reviewAuthorId);
        $query->execute();
    }

    public function updateReviewStatus($review, $statusId) {
        $query = $this->pdo->prepare("UPDATE reviews SET review_status_id = :status WHERE review_author = :author && review_film_id = :film ");
        $query->bindParam(":status", $statusId);
        $query->bindParam(":author", $review->review_author);
        $query->bindParam(":film", $review->review_film_id);
        $query->execute();
    }

    public function updateFilmAverageScore($filmId, $newAverage) {
        $query = $this->pdo->prepare("UPDATE films SET film_review_average = ? WHERE film_id = ?");
        $query->bindParam(1, $newAverage);
        $query->bindParam(2, $filmId);
        $query->execute();
    }

    public function getReviewsPending() {
        $query = $this->pdo->prepare("SELECT review_id, review_author, review_title, review_score, review_text, review_datetime, review_film_id, review_status_id, film_title, user_name FROM users JOIN reviews ON users.user_id = reviews.review_author JOIN films ON reviews.review_film_id = films.film_id WHERE review_status_id = 1 ORDER BY review_datetime desc");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

}