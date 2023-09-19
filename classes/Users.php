<?php 

class Users{
    public $name;
    public $email;
    public $password;
    public $user_id;
    public $project_name;
    public $description;
    public $status;

    private $conn;
    private $table_name;  
    


    public function __construct($db){
        $this->conn = $db;
        $this->table_name = "jwt";
        
    }

    public function check_email(){

        $query = "SELECT * FROM jwt WHERE email = ?";

        $check_obj = $this->conn->prepare($query);

        $check_obj->bind_param("s",$this->email);

       if($check_obj->execute()){

           $data = $check_obj->get_result();

             return $data->fetch_assoc();
    }
    }

    public function create_user(){


       $user_query = "INSERT INTO ".$this->table_name." SET name = ?, email = ?, password = ?";

       $obj = $this->conn->prepare($user_query);

       $obj->bind_param("sss",$this->name,$this->email,$this->password);

       if($obj->execute()){
           return true;
       }
           return false;
    }

    public function login(){

        $query = "SELECT * FROM jwt WHERE email = ?";

        $check_obj = $this->conn->prepare($query);

        $check_obj->bind_param("s",$this->email);

       if($check_obj->execute()){

           $data = $check_obj->get_result();

             return $data->fetch_assoc();
    }
    }
}
?>