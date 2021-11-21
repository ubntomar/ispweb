<?php
require("Client.php");

class Wallet extends Client{
    private $mysqli;

    public function __construct($server, $db_user, $db_pwd, $db_name){
        $this->mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
		if ($this->mysqli->connect_errno) {
	    	echo "Failed to connect to MySQL: ";
            return false;
			}	
		mysqli_set_charset($this->mysqli,"utf8");
		date_default_timezone_set('America/Bogota'); 
        return true;
        
    }
    public function createWallet(){
        $sql="";
	if ($this->mysqli->query($sql) === TRUE) {
			$last_id = $this->mysqli->insert_id;
			$idafiliado=$last_id;
        }
        return $idafiliado;
    }
    public function getWallet(){

    }
    public function updateWallet($idWallet,$param,$value){
        $sql="";
        if ($this->mysqli->query($sql) === TRUE)
        		return true;
                return false;
    }
    public function deleteWallet(){

    }
}


?>