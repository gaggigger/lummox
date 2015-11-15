<?php

class ApiService {

	public function json($status, $data) {
        $app = \Slim\Slim::getInstance();
        $app->response->headers->set('Content-Type', 'application/json');
        $app->halt($status, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function formatReviewsDatetime($reviews) {
        foreach($reviews as $key => $review) {
            $date = strtotime($review["review_datetime"]);
            $date = date('d.m.Y', $date);
            $reviews[$key]["review_datetime"] = $date;
        }

        return $reviews;
    }

    public function formatFilmsDatetime($films) {
        foreach ($films as $key => $film) {
            $date = strtotime($film["film_release_date"]);
            $date = date('d.m.Y', $date);
            $films[$key]["film_release_date"] = $date;
        }

        return $films;
    }

    public function formatFilmDataDatetime($filmData) {
        $filmDatetime = $filmData["film_release_date"];
        $yearOfRelease = substr($filmDatetime, 0, 4);
        $filmData["film_release_year"] = $yearOfRelease;

        $date = strtotime($filmDatetime);
        $date = date('d.m.Y', $date);
        $filmData["film_release_date"] = $date;

        return $filmData;
    }

    public function calculateNewAverage($app, $filmId) {

        $reviews = $app->dataAccessService->getFilmReviews($filmId);

        $score = 0;

        foreach ($reviews as $rev) {
            $score = $score + $rev["review_score"];
        }

        $average = $score / sizeof($reviews);

        return $average;
    }
}
