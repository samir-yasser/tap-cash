<?php

header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: POST");
// header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//echo 'test';
// return;

require_once('../connect.php');

require_once('../vendor/autoload.php');
require_once('../vendor/firebase/php-jwt/src/JWT.php');
require_once('../classes/Auth/jwtHandler.php');

$data = json_decode(file_get_contents("php://input"));
$returnData = [];

// IF REQUEST METHOD IS NOT EQUAL TO POST
if($_SERVER["REQUEST_METHOD"] != "POST"):
    $returnData = msg(0, 400, 'Request Must Be POST');

// CHECKING EMPTY FIELDS
elseif(!isset($data->phone) 
    || !isset($data->password)
    || empty(trim($data->phone))
    || empty(trim($data->password))
    ):

    $fields = ['fields' => ['phone','password']];
    $returnData = msg(0,422,'Please Fill in all Required Fields!',$fields);

// IF THERE ARE NO EMPTY FIELDS THEN
else:
    $phone = trim($data->phone);
    $password = trim($data->password);
    
    // IF PASSWORD IS LESS THAN 8 THE SHOW THE ERROR
    if(strlen($password) < 8):
        $returnData = msg(0,422,'Your password must be at least 8 characters long!');

    // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
    else:
        try{
            
            $fetch_user_by_phone = "SELECT * FROM `users` WHERE `phone`=:phone";
            $query_stmt = $con->prepare($fetch_user_by_phone);
            $query_stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
            $query_stmt->execute();

            // IF THE USER IS FOUNDED BY PHONE
            if($query_stmt->rowCount()):
                $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                $check_password = password_verify($password, $row['password']);

                // VERIFYING THE PASSWORD (IS CORRECT OR NOT?)
                // IF PASSWORD IS CORRECT THEN SEND THE LOGIN TOKEN
                if($check_password):

                    $jwt = new JwtHandler();
                    $token = $jwt->jwtEncodeData(
                        'http://localhost/smart_wallet/',
                        array("user_id"=> $row['id'])
                    );
                    
                    $returnData = [
                        'success' => 1,
                        'message' => 'You have successfully logged in.',
                        'token' => $token
                    ];

                // IF INVALID PASSWORD
                else:
                    $returnData = msg(0,422,'Invalid Password!');
                endif;

            // IF THE USER IS NOT FOUNDED BY PHONE THEN SHOW THE FOLLOWING ERROR
            else:
                $returnData = msg(0,422,'Invalid phone number!');
            endif;
        }
        catch(PDOException $e){
            $returnData = msg(0,500,$e->getMessage());
        }

    endif;

endif;

echo json_encode($returnData);