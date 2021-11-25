<?php 
class Client 
{
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
    public function createClient($name, $lastName, $cedula, $address, $ciudad, $departamento, $email, $phone, $valorPlan,$corte, $nextPay,$billDeliveryNumber, $velocidadPlan, $plan, $today, $source="ispdev", $activo="1", $ipAddress, $standby, $AfiliacionItemValue,  $usuario, $idClientArea, $empresa){
        //print "\n =afiliar_cliente($name, $lastName, $cedula, $address, $ciudad, $departamento, $email, $phone, $valorPlan,$corte, $nextPay,$billDeliveryNumber, $velocidadPlan, $plan, $today, $source, $activo, $ipAddress, $standby, $AfiliacionItemValue,  $usuario, $idClientArea, $empresa)  \n";
        $sql="INSERT INTO `redesagi_facturacion`.`afiliados` (`id`, `cliente`, `apellido`, `cedula`, `direccion`, `ciudad`, `departamento`, `mail`, `telefono`, `pago`, `corte`, `mesenmora`,  `orden_reparto`, `velocidad-plan`, `tipo-cliente`, `registration-date`, `source`, `activo`, `ip`, `standby`, `valorAfiliacion`,`stdbymcount`,`cajero`,`id_client_area`,`id-empresa`) VALUES
														 (NULL, '$name', '$lastName', '$cedula', '$address', '$ciudad', '$departamento', '$email', '$phone', '$valorPlan','$corte', '$nextPay',  '999', '$velocidadPlan', '$plan', '$today', 'ispdev', '1', '$ipAddress', '$standby', '$AfiliacionItemValue', '$standby', '$usuario', '$idClientArea', '$empresa');";
        if ($this->mysqli->query($sql) === TRUE) {
                $last_id = $this->mysqli->insert_id;
                $idafiliado=$last_id;
            }
        return $idafiliado;
    }
    public function getClient(){

    }
    public function getClientItem($idClient,$item){
        $sql="SELECT `$item` FROM `redesagi_facturacion`.`afiliados` WHERE `id` = '$idClient' ";
        if($result=$this->mysqli->query($sql)){
            $row=$result->fetch_assoc();
            $value=$row[$item];
        }
        return $value;
    }
    public function updateClient($clienteId,$param,$value,$operator="="){
        // print "\nupdateClient $clienteId,$param,$value \n";
        $sql="UPDATE `redesagi_facturacion`.`afiliados` set `$param` $operator '$value' WHERE `id`='$clienteId' ";
        // print $sql;
        if ($this->mysqli->query($sql) === TRUE){
            $response= true;
        }else{
            $response= false;
        }
        return $response;
        }
    public function deleteClient(){

    }
}

// $clientObject=new Client("localhost", "mikrotik", "Agwist1.", "redesagi_facturacion");
// $clienteId=25;//$data["id"]; 
// //Update afiliados table 
// $param="wallet-money";
// $value="1202";
// $res=$clientObject->updateClient($clienteId,$param,$value,$operator="=");
// if($res){
//     echo json_encode("success");
// }else{
//     echo json_encode("fail $clienteId  -- $value "); 
// }



?>