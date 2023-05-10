<?php 

$host = 'sql207.byethost10.com';
$dbname = 'b10_34170493_smartwallet';
$user = 'b10_34170493';
$pass = 'SwSnc*28b2C3R6VBjw3l';

$con = new PDO("mysql:host=$host; dbname=$dbname", $user, $pass);

function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}