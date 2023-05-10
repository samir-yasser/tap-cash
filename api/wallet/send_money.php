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
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

if ($auth->checkToken()) {
    if ($_SERVER["REQUEST_METHOD"] != "POST") :

        $returnData = msg(0, 400, 'Request Must Be POST');
        
    elseif (
        !isset($data->money)
        || !isset($data->category_id)
        || empty(trim($data->money))
        || empty(trim($data->category_id))
    ) :
            
        $fields = ['fields' => ['money', 'category_id']];
        $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);
        
    else:
        $check_user = "SELECT * FROM `users` WHERE `id` = :user";
        $check_stmt = $con->prepare($check_user);
        $check_stmt->bindValue(':user', $auth->getUser()['user']['id'], PDO::PARAM_STR);
        $check_stmt->execute();
        
        // CHECK IF USER EXISTS
        if ($check_stmt->rowCount()):
            if ($auth->getUser()['user']['user_type'] == 'c'):
                $check_cat = "SELECT * FROM user_cats WHERE user_id = :user AND cat_id = :cat";
                $cat_stmt = $con->prepare($check_cat);
                $cat_stmt->bindValue(':user', $auth->getUser()['user']['id']);
                $cat_stmt->bindValue(':cat', $data->category_id);
                $cat_stmt->execute();
                
                if ($cat_stmt->rowCount() == 0 || ($cat_stmt->rowCount() == 1 && $cat_stmt->fetch(PDO::FETCH_ASSOC)['allow'] == 0)) :
                    $returnData = msg(0, 422, 'This category is forbidden for you');

                elseif ($auth->getUser()['user']['money'] < $data->money) :
                    $returnData = msg(0, 422, 'you don\'t have enough money');

                else:

                    // INSERT TRANSACTION FOR SENDER
                    $transaction = "INSERT INTO `transactions` (user_id, `money`, proccess, cat_id) VALUES (:user, :money, :proccess, :cat)";
                    $transaction_stmt = $con->prepare($transaction);
                    $transaction_stmt->bindValue(':user', $auth->getUser()['user']['id'], PDO::PARAM_STR);
                    $transaction_stmt->bindValue(':money', $data->money, PDO::PARAM_STR);
                    $transaction_stmt->bindValue(':proccess', '-');
                    $transaction_stmt->bindValue(':cat', $data->category_id, PDO::PARAM_INT);
                    $transaction_stmt->execute();

                    // UPDATE MONEY OF SENDER
                    $money = $auth->getUser()['user']['money'] - $data->money;
                    $update_user = "UPDATE `users` SET `money` = $money WHERE id = :id";
                    $update_user_stmt = $con->prepare($update_user);
                    $update_user_stmt->bindValue(':id', $auth->getUser()['user']['id'], PDO::PARAM_INT);
                    $update_user_stmt->execute();
                    
                    $returnData = msg(1, 200, 'You have successfully send money.');

                endif;

            else:
                if ($auth->getUser()['user']['money'] < $data->money) :
                    $returnData = msg(0, 422, 'you don\'t have enough money');

                else:

                    if ($data->user_id) {
                        // INSERT TRANSACTION FOR SENDER
                        $transaction = "INSERT INTO `transactions` (user_id, `money`, proccess, cat_id) VALUES (:user, :money, :proccess, null)";
                        $transaction_stmt = $con->prepare($transaction);
                        $transaction_stmt->bindValue(':user', $auth->getUser()['user']['id'], PDO::PARAM_STR);
                        $transaction_stmt->bindValue(':money', $data->money, PDO::PARAM_STR);
                        $transaction_stmt->bindValue(':proccess', '-');
                        $transaction_stmt->execute();

                        // INSERT TRANSACTION FOR RECEIVER
                        $transaction = "INSERT INTO `transactions` (user_id, `money`, proccess, cat_id) VALUES (:user, :money, :proccess, null)";
                        $transaction_stmt = $con->prepare($transaction);
                        $transaction_stmt->bindValue(':user', $data->user_id, PDO::PARAM_INT);
                        $transaction_stmt->bindValue(':money', $data->money, PDO::PARAM_STR);
                        $transaction_stmt->bindValue(':proccess', '+');
                        $transaction_stmt->execute();

                        // UPDATE MONEY OF SENDER
                        $money = $auth->getUser()['user']['money'] - $data->money;
                        $update_user = "UPDATE `users` SET `money` = $money WHERE id = :id";
                        $update_user_stmt = $con->prepare($update_user);
                        $update_user_stmt->bindValue(':id', $auth->getUser()['user']['id'], PDO::PARAM_INT);
                        $update_user_stmt->execute();

                        // CHECK USER_ID EXISTS
                        $check_user = "SELECT * FROM `users` WHERE `id` = :user";
                        $check_stmt = $con->prepare($check_user);
                        $check_stmt->bindValue(':user', $data->user_id, PDO::PARAM_INT);
                        $check_stmt->execute();

                        // UPDATE MONEY OF RECEIVER
                        $money = $check_stmt->fetch(PDO::FETCH_ASSOC)['money'] + $data->money;
                        $update_user = "UPDATE `users` SET `money` = $money WHERE id = :id";
                        $update_user_stmt = $con->prepare($update_user);
                        $update_user_stmt->bindValue(':id', $data->user_id, PDO::PARAM_INT);
                        $update_user_stmt->execute();
                    }
                    else {
                        // INSERT TRANSACTION FOR SENDER
                        $transaction = "INSERT INTO `transactions` (user_id, `money`, proccess, cat_id) VALUES (:user, :money, :proccess, :cat)";
                        $transaction_stmt = $con->prepare($transaction);
                        $transaction_stmt->bindValue(':user', $auth->getUser()['user']['id'], PDO::PARAM_STR);
                        $transaction_stmt->bindValue(':money', $data->money, PDO::PARAM_STR);
                        $transaction_stmt->bindValue(':proccess', '-');
                        $transaction_stmt->bindValue(':cat', $data->category_id, PDO::PARAM_INT);
                        $transaction_stmt->execute();

                        // UPDATE MONEY OF SENDER
                        $money = $auth->getUser()['user']['money'] - $data->money;
                        $update_user = "UPDATE `users` SET `money` = $money WHERE id = :id";
                        $update_user_stmt = $con->prepare($update_user);
                        $update_user_stmt->bindValue(':id', $auth->getUser()['user']['id'], PDO::PARAM_INT);
                        $update_user_stmt->execute();
                    }
                    
                    $returnData = msg(1, 200, 'You have successfully purchases.');

                endif;

            endif;

        else:
            $returnData = msg(0, 422, 'this user doesn\'t exist!', $fields);
        endif;
    endif;
}
else {
    $returnData = msg(0, 400, 'you need token to access');
}
echo json_encode($returnData);