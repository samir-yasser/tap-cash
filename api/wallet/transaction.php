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

$returnData = [];

if ($auth->checkToken()) {
    if ($_SERVER["REQUEST_METHOD"] != "GET") :

        $returnData = msg(0, 400, 'Request Must Be GET');
        echo json_encode($returnData);
        
    else:
        $transaction = "SELECT money, proccess, cat_id FROM `transactions` WHERE `user_id` = :user";
        $transaction_stmt = $con->prepare($transaction);
        $transaction_stmt->bindValue(':user', $auth->getUser()['user']['id'], PDO::PARAM_STR);
        $transaction_stmt->execute();
        
        $fetch_transaction = $transaction_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'status' => 200,
            'data' => $fetch_transaction,
        ]);
    endif;
}
else {
    $returnData = msg(0, 400, 'you need token to access');
    echo json_encode($returnData);
}