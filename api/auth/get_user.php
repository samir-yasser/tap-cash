<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../vendor/autoload.php');
require_once('../vendor/firebase/php-jwt/src/JWT.php');

require_once('../classes/Auth/AuthMiddleware.php');

require_once('../connect.php');

$allHeaders = getallheaders();

$auth = new Auth($con, $allHeaders);

echo json_encode($auth->getUser());
// echo json_encode($auth->checkToken());