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
        $userByUsername = $app->dataAccessService->getUserByName($data->username);
        $userByEmail = $app->dataAccessService->getUserByEmail($data->email);
        $byEmailUsername = strtolower($userByEmail["user_name"]);
        $byUsernameUsername = strtolower($userByUsername["user_name"]);
        $reqUsername = strtolower($data->username);

        $reqEmail = strtolower($data->email);
        $byEmailEmail = strtolower($userByEmail["user_email"]);
        $byUsernameEmail = strtolower($userByUsername["user_email"]);

        if ($reqUsername !== $byEmailUsername) {
            // requested username is different from the username retrieved by looking up by requested email
            if (empty($byEmailUsername)) {
                // no username was retrieved by requested email
                if ($reqEmail !== $byUsernameEmail) {
                    // no user found by this email using the same username
                    if ($reqUsername !== $byUsernameUsername) {
                        // no user found by this username either
                        // username not taken, email not taken
                        // create user
                        $response = array("success" => true, "data" => "Thank you for registering.");
                    } else {
                        // username in use by another email address
                        $response = array("success" => false, "data" => "Username is already in use by another account.");
                    }
                } else {
                    // requested email is in use by a differently named account
                    $response = array("success" => false, "data" => "Email is already in use by another account.");
                }
            } else {
                // requested username is different from the username retrieved by looking up by requested email
                // an username was found that was using the requested email
                $response = array("success" => false, "data" => "Email is already in use by another account.");
            }
        } else {
            // requested username is the same as the one found for requested email address
            if($userByEmail["user_role_id"] !== 6) {
                // not unregistered
                $response = array("success" => false, "data" => "Username taken by a registered user.");
            } else {
                // unregistered
                $response = array("success" => true, "data" => "An account has been created for your username.");
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
                $token = $this->decodeToken($jwt);
            } catch (Exception $e) {
                error_log($e);
            }
        }
        return $token;
    }

    public function decodeToken($token) {
        return JWT::decode($token, SECRET, array('HS512'));
    }
}
