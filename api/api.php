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
    $response = array("success" => true, "data" => $reviews);
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

$app->post('/review/publish', function() use ($app) {
    $review = json_decode($app->request()->getBody());

    $review->reviewStatusId = 1;

    $reviewAuthorResult = $app->dataAccessService->getUserIdByUsername($review->reviewAuthor);
    $reviewAuthorId = $reviewAuthorResult["user_id"];
    if (!isset($reviewAuthorId)) {
        $app->dataAccessService->createUnregisteredUser($review->reviewAuthor);
        $reviewAuthorResult = $app->dataAccessService->getUserIdByUsername($review->reviewAuthor);
        $reviewAuthorId = $reviewAuthorResult["user_id"];
    }

    if ($app->dataAccessService->isReviewedByUser($review->reviewFilmId, $reviewAuthorId)) {
        error_log("a user has posted a review for a film that is already reviewed by same user. rejecting");
        $response = array("success" => false, "data" => "Review already exists by this user for this film!");
        $app->apiService->json(500, $response);
    }

    $getUserRoleResult = $app->dataAccessService->getUserRole($review->reviewAuthor);
    $userRole = $getUserRoleResult["role_name"];
    error_log("=== fo realz mang, user role " . $userRole);

    if ($userRole === "Unregistered") {
        $app->dataAccessService->createReviewPending($review, $reviewAuthorId);
    } else if ($userRole === "Admin" || $userRole === "Moderator" || $userRole === "User") {
        error_log("User is registered and stuff, autopublishing");
        $app->dataAccessService->createReviewPublished($review, $reviewAuthorId);
        $app->dataAccessService->updateFilmAverageScore($review->reviewFilmId, $app->apiService->calculateNewAverage($app, $review->reviewFilmId));
    }

    $response = array("success" => true, "data" => "Review published successfully!");
    $app->apiService->json(200, $response);
});