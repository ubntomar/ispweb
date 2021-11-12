<?php
class Transaction
{
    private $mysqli;

    public function __construct($server, $db_user, $db_pwd, $db_name){
        $this->mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
		if ($this->mysqli->connect_errno) {
	    	echo "Failed to connect to MySQL: ";
            return false;
			}	
		mysqli_set_charset($this->mysqli,"utf8");
		date_default_timezone_set('America/Bogota'); 
    }
    public function createTransaction(){
        
    }
    public function getTransaction(){

    }
    public function updateTransaction(){

    }
    public function deleteTransaction(){

    }
    
}

//$object=new VpnUtils($server, $db_user, $db_pwd, $db_name);
//$object->updateGroupId("808","192.168.30.150");
?>