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

    public function getFilms() {
        $query = ("select film_id, film_title, film_release_date, film_director_id, film_genre, film_description, director_id, director_name FROM films JOIN directors ON films.film_director_id = directors.director_id order by film_release_date;");
        $query = $this->pdo->prepare($query);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilmData($filmId) {
        $query = $this->pdo->prepare("select film_id, film_title, film_release_date, film_director_id, film_genre, film_description, director_id, director_name FROM films JOIN directors ON films.film_director_id = directors.director_id WHERE film_id = ?;");
        $query->bindParam(1, $filmId);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

}