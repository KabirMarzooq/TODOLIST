<?php

header('Access-Control-Allow-Origin: *'); //Adjust for production
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
require_once "../vendor/autoload.php";
require_once "../config/config.php";

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");
header("content-type: application/json; charset=UTF-8");

use Firebase\JWT\JWT;
use Firebase\JWT\key;

$jwtHandler = new JwtHandler (new jwt);

$paths = $_SERVER['REQUEST_URI'];

$method = $_SERVER['REQUEST_METHOD'];

$part = explode("/", $paths);




if($part[2] === "api" && $part[3] === "signup"){

    $userModel =new \Models\User($database);

    $userService = new \Services\User($userModel, $jwtHandler);

    $userController = new \Controllers\Users($userService);

    $userController->processRequest($method, $part[3]);

    exit();

}


http_response_code(500);


