<?php 
class User 
{
    public $mysqli;
    public function __construct($server, $db_user, $db_pwd, $db_name){
        $response= true;
        $this->mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
		if ($this->mysqli->connect_errno) {
	    	echo "Failed to connect to MySQL: ";
            $response= false;//
			}	
		mysqli_set_charset($this->mysqli,"utf8");
		date_default_timezone_set('America/Bogota'); 
        return $response;
    }
    public function createUser(){
        $idUser="";
        return $idUser;
    }
    public function getUser(){

    }
    public function getUserItem($idUser,$item){
        $sql="SELECT `$item` FROM `redesagi_facturacion`.`users` WHERE `id` = '$idUser' ";
        if($result=$this->mysqli->query($sql)){
            $row=$result->fetch_assoc();
            $value=$row[$item];
        }
        return $value;
    }
    public function updateUser($UsereId,$param,$value,$operator="="){
        // print "\nupdateUser $UsereId,$param,$value \n";
        $sql="UPDATE `redesagi_facturacion`.`afiliados` set `$param` $operator '$value' WHERE `id`='$UsereId' ";
        // print $sql;
        if ($this->mysqli->query($sql) === TRUE){
            $response= true;
        }else{
            $response= false;
        }
        return $response;
        }
    public function deleteUser(){

    }
}

// $UsereId=25;//$data["id"]; 
// //Update afiliados table 
// $param="wallet-money";
// $value="1202";
// $res=$UserObject->updateUser($UsereId,$param,$value,$operator="=");
// if($res){
//     echo json_encode("success");
// }else{
//     echo json_encode("fail $UsereId  -- $value "); 
// }



?>