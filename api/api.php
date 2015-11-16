<?php

use Zend\Http\PhpEnvironment\Request;

$app->get('/hello', function() use ($app) {
    $data = $app->dataAccessService->getHello('1');
    $response = array("success" => true, "data" => $data);
    $app->apiService->json(200, $response);
});

$app->get('/users', function() use ($app) {
    $users = $app->dataAccessService->getUsers();
    $response = array("success" => true, "data" => $users);
    $app->apiService->json(200, $response);
});

$app->post('/users/reviews', function() use ($app) {
    $userId = json_decode($app->request()->getBody());
    error_log("users/reviews user id: " . $userId);

    $reviews = $app->dataAccessService->getUserReviews($userId);

    $userName = $app->dataAccessService->getUsername($userId);
    $data = array("username" => $userName, "reviews" => $reviews);

    $response = array("success" => true, "data" => $data);
    $app->apiService->json(200, $response);
});

$app->get('/films', function() use ($app) {

    $films = $app->dataAccessService->getFilms();
    // debug filmdata
    $films = $app->apiService->formatFilmsDatetime($films);

    if (null !== $films && sizeof($films) > 0) {
        $response = array("success" => true, "data" => $films);
        $app->apiService->json(200, $response);
    } else {
        $response = array("success" => false, "data" => "No films found.");
        $app->apiService->json(500, $response);
    }
});

$app->post('/filmdata', function() use ($app) {

    $filmId = json_decode($app->request()->getBody());

    $filmData = $app->dataAccessService->getFilmData($filmId);
    $filmData = $app->apiService->formatFilmDataDatetime($filmData);

    $filmReviews = $app->dataAccessService->getFilmReviews($filmId);
    $filmReviews = $app->apiService->formatReviewsDatetime($filmReviews);
    $filmData["film_reviews"] = $filmReviews;

    if (null !== $filmData && sizeOf($filmData) > 0) {
        $response = array("success" => true, "data" => $filmData);
        $app->apiService->json(200, $response);
    } else {
        $response = array("success" => false, "data" => "Film not found.");
        $app->apiService->json(500, $response);
    }
});

$app->post('/review/get', function() use ($app) {
    $body = json_decode($app->request()->getBody());
    $reviewId = $body;
    $review = $app->dataAccessService->getReview($reviewId);
    $review["review_datetime"] = $app->apiService->formatDatetime($review["review_datetime"]);
    $response = array("success" => true, "data" => $review);
    $app->apiService->json(200, $response);
});

$app->get('/reviews', function() use ($app) {
    $reviews = $app->dataAccessService->getReviews();
    $reviews = $app->apiService->formatReviewsDatetime($reviews);
    $response = array("success" => true, "data" => $reviews);
    $app->apiService->json(200, $response);
});

$app->post('/review/publish', function() use ($app) {
    $review = json_decode($app->request()->getBody());

    // set review status to pending
    $review->reviewStatusId = 1;
    // lowercase email
    $review->email = strtolower($review->email);

    // look up user with the same username as review author
    // get their id
    $userByUsername = $app->dataAccessService->getUserByUsername($review->reviewAuthor);
    $reviewAuthorId = $userByUsername["user_id"];
    // look up email to see if it's in use
    $userByEmail = $app->dataAccessService->getUserByEmail($review->email);

    if (!empty($userByUsername)) {
        // user by username already exists
        // check if email matches
        if ($userByUsername["user_email"] === $review->email && $userByEmail["user_name"] === $review->reviewAuthor) {
            // same user
            // look up reviews by this user for this film
            if ($app->dataAccessService->isReviewedByUser($review->reviewFilmId, $reviewAuthorId)) {
                // user has already reviewed this film
                error_log("a user has posted a review for a film that is already reviewed by same user. rejecting");
                $response = array("success" => false, "data" => "Review already exists by this user for this film!");
            } else {
                // no review for this film by user
                // look up user role
                $getUserRoleResult = $app->dataAccessService->getUserRole($review->reviewAuthor);
                $userRole = $getUserRoleResult["role_name"];
                if ($userRole === "Unregistered") {
                    // user is unregistered, send review to queue
                    $app->dataAccessService->createReviewPending($review, $reviewAuthorId);
                    $response = array("success" => true, "data" => "Review published successfully!", "autopublished" => false);
                } else if ($userRole !== "Banned" && $userRole !== "Unverified") {
                    // user is registered and has privileges to publish review
                    $app->dataAccessService->createReviewPublished($review, $reviewAuthorId);
                    $app->dataAccessService->updateFilmAverageScore($review->reviewFilmId, $app->apiService->calculateNewAverage($app, $review->reviewFilmId));
                    $response = array("success" => true, "data" => "Review published successfully!", "autopublished" => true);
                }
            }
        } else {
            // same user, different email
            $response = array("success" => false, "data" => "This username is already taken.");
        }
    } else if (empty($userByEmail)){
        // completely new user
        $app->dataAccessService->createUnregisteredUser($review->reviewAuthor, $review->email);
        $reviewAuthor = $app->dataAccessService->getUserByUsername($review->reviewAuthor);
        $app->dataAccessService->createReviewPending($review, $reviewAuthor["user_id"]);
        $response = array("success" => true, "data" => "Review published successfully!", "autopublished" => false);
    } else {
        // email is already taken by another user
        $response = array("success" => false, "data" => "The email you entered is already taken by another user.");
    }

    $status = 200;
    // send response
    if ($response["success"] === false) {
        $status = 500;
    }
    $app->apiService->json($status, $response);
});

$app->post('/users/registration', function() use ($app) {

    $data = json_decode($app->request()->getBody());

    $data->password = $app->apiService->hashPassword($data->password);
    $data->email = strtolower($data->email);

    $response = $app->apiService->validateRegistration($app, $data);

    if ($response["success"]) {
        if ($response["data"] === "An account has been created for your username.") {
            $app->dataAccessService->updateUserRole($data->username);
        } else {
            $app->dataAccessService->createNewUser($data);
        }
        $app->apiService->json(200, $response);
    } else {
        $app->apiService->json(500, $response);
    }

});

$app->post('/users/authenticate', function() use ($app) {
    $data = json_decode($app->request()->getBody());
    $user = $app->dataAccessService->getUserByUsername($data->username);

    if ($app->apiService->verifyPassword($data, $user)) {
        // passwords match
        // generate token data and create token
        $token = $app->apiService->createToken(($app->apiService->generateTokenData($user)));
        // send 200 and token
        $response = array("success" => true, "token" => $token, "data" => "You are now logged in as " . $user["user_name"] . ".");
        $app->apiService->json(200, $response);
    } else {
        // wrong password
        $response = array("success" => false, "data" => "Authentication failed. Please make sure you entered the right password.");
        $app->apiService->json(401, $response);
    }
});

$app->get('/users/role', function() use ($app) {

    $request = new Request();

    if($request->isGet()) {
        $header = $request->getHeader('authorization');
        if ($header) {
            $token = $app->apiService->extractToken($header);
        }
    }

    if (null !== $token) {
        $tokenData = $token->data;
        $username = $tokenData->username;

        $userData = $app->dataAccessService->getUserRole($username);

        $response = array("success" => true, "data" => $userData);
        $app->apiService->json(200, $response);
    } else {
        $response = array("success" => false, "data" => "Invalid token");
        $app->apiService->json(401, $response);
    }
});