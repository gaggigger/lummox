<?php

use Firebase\JWT\JWT;
use Zend\Http\PhpEnvironment\Request;
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

        // look up users with the same username or email
        $userByUsername = $app->dataAccessService->getUserByUsername($data->username);
        $userByEmail = $app->dataAccessService->getUserByEmail($data->email);

        // debug
        error_log("###");
        error_log($userByEmail["user_name"]);
        error_log($data->username);
        error_log($userByEmail["user_role_id"]);
        error_log("###");
        // end debug

        if (empty($userByEmail) && empty($userByUsername)) {
            // username not taken, email not taken
            // create user
            $response = array("success" => true, "data" => "Thank you for registering.", "emailTaken" => false, "usernameTaken" => false);
        } else if (empty($userByEmail) && !empty($userByEmail)) {
            // email taken. return 500
            $response = array("success" => false, "data" => "A user already exists by that email", "emailTaken" => true, "usernameTaken" => false);
        } else if (!empty($userByEmail) && empty($userByUsername)) {
            // email taken by another username
            // return 500
            $response = array("success" => false, "data" => "The email address you entered is in use by another username.", "emailTaken" => false, "usernameTaken" => true);
        } else if (!empty($userByEmail) && $userByEmail["user_name"] === $data->username) {
            // username taken by a user using the same email.
            // look up user role and see that it is unregistered, 6
            if ($userByEmail["user_role_id"] !== 6) {
                $response = array("success" => false, "data" => "Username taken by a registered user.", "emailTaken" => false, "usernameTaken" => true);
            } else {
                $response = array("success" => true, "data" => "An account has been created for your username.", "emailTaken" => false, "usernameTaken" => true);
            }
        } else if (!empty($userByUsername) && $userByUsername["user_email"] !== $data->email) {
            // username is taken by someone using a different email
            $response = array("success" => false, "data" => "Username taken by someone using a a different email. ", "emailTaken" => false, "usernameTaken" => true);
        } else {
            // problems
            $response = array("success" => false, "data" => "An error occurred. Please contact an administrator.");
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
                "userId" => $user["user_id"],
                "username" => $user["user_name"]
            ]
        ];
        return $data;
    }

    public function createToken($data) {
        $jwt = JWT::encode($data, SECRET, "HS512");
        return $jwt;
    }

    public function verifyPassword($requestUser, $dbUser) {
        if(!empty($requestUser->password)) {
            if (password_verify($requestUser->password, $dbUser["password"])) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function extractToken($header) {
        $token = null;
        list($jwt) = sscanf($header->toString(), 'Authorization: Bearer %s');
        if ($jwt) {
            try {
                $token = JWT::decode($jwt, SECRET, array('HS512'));
            } catch (Exception $e) {
                error_log($e);
            }
        }
        return $token;
    }
}
