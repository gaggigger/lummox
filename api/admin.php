<?php

use Zend\Http\PhpEnvironment\Request;

$app->get('/admin/reviews/pending', function() use ($app) {
    $request = new Request();

    if($request->isGet()) {
        $header = $request->getHeader('authorization');
        if ($header) {
            $token = $app->apiService->extractToken($header);
        }
    }

    if (null !== $token) {
        $reviews = $app->dataAccessService->getReviewsPending();
        $response = array("success" => true, "data" => $reviews);
        $app->apiService->json(200, $response);
    } else {
        $response = array("success" => false, "data" => "Invalid token!");
        $app->apiService->json(401, $response);
    }
});

$app->post('/admin/review/reject', function() use ($app) {
    $request = new Request();
    if($request->isPost()) {
        $header = $request->getHeader('authorization');
        if ($header) {
            $token = $app->apiService->extractToken($header);
        }
    }

    $data = json_decode($app->request()->getBody());

    if (null !== $token) {
        // status 3 = deleted
        $app->dataAccessService->updateReviewStatus($data, 3);
        $response = array("success" => true, "data" => "Review marked as deleted.");
        $app->apiService->json(200, $response);
    } else {
        $response = array("success" => false, "data" => "Invalid token!");
        $app->apiService->json(401, $response);
    }
});

$app->post('/admin/review/accept', function() use ($app) {
    $request = new Request();
    if($request->isPost()) {
        $header = $request->getHeader('authorization');
        if ($header) {
            $token = $app->apiService->extractToken($header);
        }
    }

    $data = json_decode($app->request()->getBody());

    if (null !== $token) {
        // status 2 = published
        $app->dataAccessService->updateReviewStatus($data, 2);

        // new average
        $app->dataAccessService->updateFilmAverageScore($data->review_film_id, $app->apiService->calculateNewAverage($app, $data->review_film_id));

        $response = array("success" => true, "data" => "Review accepted and published!");
        $app->apiService->json(200, $response);
    } else {
        $response = array("success" => false, "data" => "Invalid token!");
        $app->apiService->json(401, $response);
    }
});

$app->post('/admin/user/verify', function() use ($app) {
    // user role id for user (verified)
    $verified = 3;

    $request = new Request();
    if($request->isPost()) {
        $header = $request->getHeader('authorization');
        if ($header) {
            $token = $app->apiService->extractToken($header);
        }
    }

    $data = json_decode($app->request()->getBody());

    if (null !== $token) {
        $app->dataAccessService->updateUserRole($data->user_name, $verified);
        $response = array("success" => true, "data" => "User verified successfully.");
        $app->apiService->json(200, $response);
    } else {
        $response = array("success" => false, "data" => "Invalid token!");
        $app->apiService->json(401, $response);
    }
});