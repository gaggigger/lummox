<?php

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

    $review->reviewStatusId = 1;

    $reviewAuthorResult = $app->dataAccessService->getUserByUsername($review->reviewAuthor);
    $reviewAuthorId = $reviewAuthorResult["user_id"];

    if (!isset($reviewAuthorId)) {
        $app->dataAccessService->createUnregisteredUser($review->reviewAuthor, $review->email);
        $reviewAuthorResult = $app->dataAccessService->getUserByUsername($review->reviewAuthor);
        $reviewAuthorId = $reviewAuthorResult["user_id"];
    } else {
        if($review->email !== $reviewAuthorResult["user_email"]) {
            $response = array("success" => false, "data" => "A user by this email already exists.");
            $app->apiService->json(401, $response);
        }
    }

    if ($app->dataAccessService->isReviewedByUser($review->reviewFilmId, $reviewAuthorId)) {
        error_log("a user has posted a review for a film that is already reviewed by same user. rejecting");
        $response = array("success" => false, "data" => "Review already exists by this user for this film!");
        $app->apiService->json(500, $response);
    }

    $getUserRoleResult = $app->dataAccessService->getUserRole($review->reviewAuthor);
    $userRole = $getUserRoleResult["role_name"];

    if ($userRole === "Unregistered") {
        $app->dataAccessService->createReviewPending($review, $reviewAuthorId);
        $response = array("success" => true, "data" => "Review published successfully!", "autopublished" => false);
    } else if ($userRole === "Admin" || $userRole === "Moderator" || $userRole === "User") {
        error_log("User is registered and stuff, autopublishing");
        $app->dataAccessService->createReviewPublished($review, $reviewAuthorId);
        $app->dataAccessService->updateFilmAverageScore($review->reviewFilmId, $app->apiService->calculateNewAverage($app, $review->reviewFilmId));
        $response = array("success" => true, "data" => "Review published successfully!", "autopublished" => true);
    }

    $app->apiService->json(200, $response);
});

$app->post('/users/registration', function() use ($app) {

    $data = json_decode($app->request()->getBody());
    $data->password = $app->apiService->hashPassword($data->password);
    $response = $app->apiService->validateRegistration($app, $data);

    if ($response["success"] === true) {
        if($response["data"] === "Creating an account for existing username") {
            // user role id is 4 by default, which is 'unverified'
            $app->dataAccessService->updateUserRole($data->username);
        } else if($response["data"] === "Creating a brand new account") {
            $app->dataAccessService->createNewUser($data);
        }
        $app->apiService->json(200, $response);

    } else {
        $app->apiService->json(500, $response);
    }
});