<?php 
class Client 
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
        return true;
        
    }
    public function createClient($name, $lastName, $cedula, $address, $ciudad, $departamento, $email, $phone, $valorPlan,$corte, $nextPay,$billDeliveryNumber, $velocidadPlan, $plan, $today, $source="ispdev", $activo="1", $ipAddress, $standby, $AfiliacionItemValue,  $usuario, $idClientArea, $empresa){
        //print "\n =afiliar_cliente($name, $lastName, $cedula, $address, $ciudad, $departamento, $email, $phone, $valorPlan,$corte, $nextPay,$billDeliveryNumber, $velocidadPlan, $plan, $today, $source, $activo, $ipAddress, $standby, $AfiliacionItemValue,  $usuario, $idClientArea, $empresa)  \n";
        $sql="INSERT INTO `redesagi_facturacion`.`afiliados` (`id`, `cliente`, `apellido`, `cedula`, `direccion`, `ciudad`, `departamento`, `mail`, `telefono`, `pago`, `corte`, `mesenmora`,  `orden_reparto`, `velocidad-plan`, `tipo-cliente`, `registration-date`, `source`, `activo`, `ip`, `standby`, `valorAfiliacion`,`stdbymcount`,`cajero`,`id_client_area`,`id-empresa`) VALUES
														 (NULL, '$name', '$lastName', '$cedula', '$address', '$ciudad', '$departamento', '$email', '$phone', '$valorPlan','$corte', '$nextPay',  '999', '$velocidadPlan', '$plan', '$today', 'ispdev', '1', '$ipAddress', '$standby', '$AfiliacionItemValue', '$standby', '$usuario', '$idClientArea', '$empresa');";
	// if ($this->mysqli->query($sql) === TRUE) {
	// 		$last_id = $this->mysqli->insert_id;
	// 		$idafiliado=$last_id;
    //     }
        return $idafiliado=859;
    }
    public function getClient(){

    }
    public function updateClient(){

    }
    public function deleteClient(){

    }
}

?>