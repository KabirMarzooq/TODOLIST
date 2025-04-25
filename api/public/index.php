<?php

require "../vendor/autoload.php";
require "../config/config.php";

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");
header("content-type: application/json; charset=UTF-8");

use Firebase\JWT\JWT;
use Firebase\JWT\key;

$users = new Controllers\Users();
echo $users->get();


echo "Hello world";