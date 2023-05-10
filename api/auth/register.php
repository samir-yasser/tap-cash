<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../connect.php');

// DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

if ($_SERVER["REQUEST_METHOD"] != "POST") :

    $returnData = msg(0, 400, 'Request Must Be POST');

elseif (
    !isset($data->name)
    || !isset($data->phone)
    || !isset($data->password)
    || !isset($data->child_account)
    || !isset($data->child_account->name)
    || !isset($data->child_account->phone)
    || !isset($data->child_account->password)
    || empty(trim($data->name))
    || empty(trim($data->phone))
    || empty(trim($data->password))
    || empty($data->child_account)
    || empty(trim($data->child_account->name))
    || empty(trim($data->child_account->phone))
    || empty(trim($data->child_account->password))
) :

    $fields = ['fields' => ['name', 'phone', 'password', 'child_account {name, phone, password}']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else :

    $name = trim($data->name);
    $phone = trim($data->phone);
    $password = trim($data->password);
    $child_account = $data->child_account;

    $child_name = $child_account->name;
    $child_phone = $child_account->phone;
    $child_pass = $child_account->password;

    if (strlen($password) < 8) :
        $returnData = msg(0, 422, 'Your parrent password must be at least 8 characters long!');

    elseif (strlen($name) < 3) :
        $returnData = msg(0, 422, 'Your parrent name must be at least 3 characters long!');

    elseif (strlen($child_name) < 3) :
        $returnData = msg(0, 422, 'Your child name must be at least 3 characters long!');

    elseif (strlen($child_pass) < 8) :
        $returnData = msg(0, 422, 'Your child password must be at least 8 characters long!');

    else :
        try {

            $check_phone = "SELECT `phone` FROM `users` WHERE `phone` = :phone";
            $check_phone_stmt = $con->prepare($check_phone);
            $check_phone_stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
            $check_phone_stmt->execute();
            
            
            if ($check_phone_stmt->rowCount()) :
                $returnData = msg(0, 422, 'Parrent Phone is already in use!');
                
            else :
                $p_query = "INSERT INTO `users`(`name`,`phone`,`password`, `user_type`) VALUES (:name,:phone,:password, 'p')";
                $p_stmt = $con->prepare($p_query);
                
                // DATA BINDING
                $p_stmt->bindValue(':name', htmlspecialchars(strip_tags($name)), PDO::PARAM_STR);
                $p_stmt->bindValue(':phone', htmlspecialchars(strip_tags($phone)), PDO::PARAM_STR);
                $p_stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
                
                $p_stmt->execute();
                // echo json_encode($con->lastInsertId());
                // return;
                $parrent = $con->lastInsertId();

                $c_query = "INSERT INTO `users`(`name`,`phone`,`password`, `user_id`, `user_type`) VALUES (:name,:phone,:password,:user, 'c')";
                $c_stmt = $con->prepare($c_query);

                // DATA BINDING
                $c_stmt->bindValue(':name', htmlspecialchars(strip_tags($child_name)), PDO::PARAM_STR);
                $c_stmt->bindValue(':phone', htmlspecialchars(strip_tags($child_phone)), PDO::PARAM_STR);
                $c_stmt->bindValue(':password', password_hash($child_pass, PASSWORD_DEFAULT), PDO::PARAM_STR);
                $c_stmt->bindValue(':user', $parrent, PDO::PARAM_INT);
                
                $c_stmt->execute();
                
                $child = $con->lastInsertId();
                
                foreach($child_account->categories as $cat) {
                    $cu_query = "INSERT INTO `user_cats`(`user_id`,`cat_id`,`allow`) VALUES (:user,:cat, 1)";
                    $cu_stmt = $con->prepare($cu_query);
                    
                    $cu_stmt->bindValue(':user', $child, PDO::PARAM_INT);
                    $cu_stmt->bindValue(':cat', $cat, PDO::PARAM_INT);

                    $cu_stmt->execute();
                }

                $returnData = msg(1, 200, 'You have successfully registered.');

            endif;
        } catch (PDOException $e) {
            $returnData = msg(0, 500, $e->getMessage());
        }
    endif;
endif;

echo json_encode($returnData);