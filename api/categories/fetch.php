<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../vendor/autoload.php');
require_once('../vendor/firebase/php-jwt/src/JWT.php');

require_once('../classes/Auth/AuthMiddleware.php');

require_once('../connect.php');

// DATA FORM REQUEST
// echo 'test';return;
$data = json_decode(file_get_contents("php://input"));
$allHeaders = getallheaders();
$auth = new Auth($con, $allHeaders);

if ($auth->checkToken()) {
    if ($_SERVER["REQUEST_METHOD"] != "GET") :

        $returnData = msg(0, 400, 'Request Must Be GET');
        echo json_encode($returnData);
    
    // IF REQUEST GET THEN-
    else :
        try {
            $query = "SELECT * FROM categories";

            $stmt = $con->prepare($query);

            $stmt->execute();

            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'success' => 1,
                'status' => 200,
                'data' => $categories
            ]);
        } catch (PDOException $e) {
            $returnData = msg(0, 500, $e->getMessage());
            echo json_encode($returnData);
        }
    endif;
}
else {
    $returnData = msg(0, 422, "you have to put token");
    echo json_encode($returnData);
}
