<?php 

//ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");


include_once("../config/database.php");
include_once("../classes/Users.php");

$db = new Database();
$connection = $db->connect();
$users = new Users($connection);

if($_SERVER['REQUEST_METHOD']==="POST"){
    
    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->name) && !empty($data->email) && !empty($data->password)){

      $users->name = $data->name;
      $users->email = $data->email;

      $users->password = password_hash($data->password, PASSWORD_DEFAULT);

      $email = $users->check_email();

      if(!empty($email)){
        
        http_response_code(500);
        echo json_encode(array("status" =>0,"message" => "users already exists"));

      }else{

      if($users->create_user()){

        http_response_code(200);
        echo json_encode(array("status" =>1,"message" => "user is created"));
      }
    
      else{
        http_response_code(500);
        echo json_encode(array("status" =>0,"message" => "user is not created"));
      }}
    }
       else{
            http_response_code(404);
            echo json_encode(array("status" =>0,"message" => "all datas needed"));
       }
    }
    else{
        http_response_code(503);
        echo json_encode(array("status" =>0,"message" => "Access denied"));
    }
  
?>