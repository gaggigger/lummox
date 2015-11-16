<?php

class ApiService {

	public function json($status, $data) {
        $app = \Slim\Slim::getInstance();
        $app->response->headers->set('Content-Type', 'application/json');
        $app->halt($status, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function formatDatetime($date) {
        $date = strtotime($date);
        $date = date('d.m.Y', $date);
        return $date;
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

    public function validateRegistration($app, $data) {
        $response = null;

        if (isset($data->username) && isset($data->password) && isset($data->email)) {
            $user = $app->dataAccessService->getUserByEmail($data->email);

            if (!empty ($user)) {
                $response = array("success" => false, "data" => "User exists by that email");
            } else {
                $user = $app->dataAccessService->getUserByName($data->username);
                if (!empty($user)) {
                    if ($user["user_role_id"] === 6) {
                        error_log("Hello");
                        $response = array("success" => true, "data" => "Creating an account for existing username");

                    } else if ($user["user_role_id"] !== 6 ) {
                        $response = array("success" => false, "data" => "User exists by that name");
                    }
                } else {
                    $response = array("success" => true, "data" => "Creating a brand new account");
                }
            }
        }
        return $response;
    }

    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function generateTokenData($user) {
        $tokenId = base64_encode(mcrypt_create_iv(32));
        $issuedAt = time();
        $serverName = SERVER_NAME;
        $data = [
            "iat" => $issuedAt,
            "jti" => $tokenId,
            "iss" => $serverName,
            "data" => [
                "userId" => $user->userId,
                "username" => $user->username
            ]
        ];
        return $data;
    }

    public function createToken($data) {
        $jwt = JWT::encode($data, SECRET, "HS512");
        return $jwt;
    }

}
