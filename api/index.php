<?php

require "vendor/autoload.php";

require "config.php";

spl_autoload_register(function($class) {
    require "classes/{$class}.php";
});

$app = new CustomSlim();

$app->dataAccessService = function() {
    return new DataAccessService(DB_HOST, DB_PORT, DB_NAME, DB_USERNAME, DB_PASSWORD);
};

$app->apiService = function() {
    return new ApiService();
};

require "api.php";
require "admin.php";

$app->run();