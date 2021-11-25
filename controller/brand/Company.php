<?php
class Company {
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
    public function getCompanyItem($idCompany,$item){
        $value=false;
        $sql="SELECT `$item` FROM `redesagi_facturacion`.`empresa` WHERE `id` = '$idCompany' ";
        //print "\n $sql \n";
        if($result=$this->mysqli->query($sql)){
            $row=$result->fetch_assoc();
            $value=$row[$item];
        }
        return $value;
    }
}

// $companyObj= new Company("localhost", "mikrotik", "Agwist1.", "redesagi_facturacion");
// $companyName=$companyObj->getCompanyItem($idCompany="1",$item="nombre");
// print $companyName;

?>




