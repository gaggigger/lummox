<?php

class ApiService {

	public function json($status, $data) {
        $app = \Slim\Slim::getInstance();
        $app->response->headers->set('Content-Type', 'application/json');
        $app->halt($status, json_encode($data, JSON_PRETTY_PRINT));
    }


}
