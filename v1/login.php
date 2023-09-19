<?php 
ini_set("display_errors", 1);
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once("../config/database.php");
include_once("../classes/Users.php");

$db = new Database();
$connection = $db->connect();
$users = new Users($connection);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    
    $data = json_decode(file_get_contents("php://input"));

    

    if (!empty($data->email) && !empty($data->password)) {
      
        $users->email = $data->email;
       

        $login_data = $users->login();

        /*if(empty($data->email)){
            http_response_code(404); 
        echo json_encode(array("status" => 0, "message" => "email is missing"));
        }
        //if(empty($data->password)){
        http_response_code(400); 
        echo json_encode(array("status" => 0, "message" => "password is missing"));}
       */

        if (!empty($login_data)) {
            $name = $login_data['name'];
            $email = $login_data['email'];
            $password = $login_data['password']; 

            if (password_verify($data->password, $password)) {

                $iss = "localhost";
                $iat = time();
                $nbf = $iat + 10;
                $exp = $iat + 30;
                $aud = "mypersonal";
                $user_data = array(
                    "id" => $login_data['id'],
                    "name" => $login_data['name'],
                    "email" => $login_data['email']
                );

                $secret_key = "vendhu123";

                $payload_info = array(
                    "iss" => $iss,
                    "iat" => $iat,
                    "nbf" => $nbf,
                    "exp" => $exp,
                    "aud" => $aud,
                    "data" => $user_data
                );

                $jwt = JWT::encode($payload_info, $secret_key, 'HS256');
                http_response_code(200);
                echo json_encode(array("status" => 1,"jwt" => $jwt,"message" => "Successfully logged in"));
                exit(); 
            }
           
        }

        else{
            http_response_code(404); 
            echo json_encode(array("status" => 0, "message" => "logged failed"));
            exit(); 
        }
        
    } 
    else {
        http_response_code(400); 
        echo json_encode(array("status" => 0, "message" => "Missing email or password"));
        exit(); 
    }
    
}

?>
