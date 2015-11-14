<?php

$app->get('/hello', function() use ($app) {
    $data = $app->dataAccessService->getHello('1');
    $response = array("success" => true, "data" => $data);
    $app->apiService->json(200, $response);
});

$app->get('/films', function() use ($app) {

    $films = $app->dataAccessService->getFilms();

    // debug filmdata

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

    if (null !== $filmData && sizeOf($filmData) > 0) {
        $response = array("success" => true, "data" => $filmData);
        $app->apiService->json(200, $response);
    } else {
        $response = array("success" => false, "data" => "Film not found.");
        $app->apiService->json(500, $response);
    }
});