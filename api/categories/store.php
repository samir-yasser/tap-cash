<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../connect.php');

// DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$auth = new Auth($con, $allHeaders);
$returnData = [];

if ($auth->checkToken()) {
    if ($_SERVER["REQUEST_METHOD"] != "POST") :

        $returnData = msg(0, 400, 'Request Must Be POST');
    
    elseif (
        !isset($data->cat_name)
    ) :
    
        $fields = ['fields' => ['cat_name']];
        $returnData = msg(0, 422, 'Please Fill in Required Field!', $fields);
    
    // IF THERE ARE NO EMPTY FIELDS THEN-
    else :
    
        $name = trim($data->cat_name);
    
        if (strlen($name) < 3) :
            $returnData = msg(0, 422, 'Your category name must be at least 3 characters long!');
    
        else :
            try {
                $insert_query = "INSERT INTO `categories`(`name`) VALUES (:name)";

                $insert_stmt = $con->prepare($insert_query);

                // DATA BINDING
                $insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($name)), PDO::PARAM_STR);

                $insert_stmt->execute();

                $returnData = msg(1, 201, 'You have successfully category created.');
            } catch (PDOException $e) {
                $returnData = msg(0, 500, $e->getMessage());
            }
        endif;
    endif;
}

echo json_encode($returnData);