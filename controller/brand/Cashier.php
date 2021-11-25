<?php
class Cashier {
    private $mysqli;
    public function __construct($server, $db_user, $db_pwd, $db_name){
        $response= true;
        $this->mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
		if ($this->mysqli->connect_errno) {
	    	echo "Failed to connect to MySQL: ";
            $response= false;
			}	
		mysqli_set_charset($this->mysqli,"utf8");
		date_default_timezone_set('America/Bogota'); 
        return $response;
    }
    public function getCashierItem($idUser,$item){
        $sql="SELECT `$item` FROM `redesagi_facturacion`.`empresa` WHERE `id` = '$idUser' ";
        if($result=$this->mysqli->query($sql)){
            $row=$result->fetch_assoc();
            $value=$row[$item];
        }
        return $value;
    }



}
?>