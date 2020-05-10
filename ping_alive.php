<?php
require 'ping.php'; 
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$sqlSearch="SELECT * FROM `afiliados` WHERE `suspender`=0 AND `eliminar`=0 AND `activo`=1 ";
if ($result = $mysqli->query($sqlSearch)) {
    while($row = $result->fetch_assoc()) {
        $text="";
        $id=$row["id"];
        $nombre=$row["cliente"];
        $apellido=$row["apellido"];
        $ip=$row["ip"];
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $text="$ip";
            if ((new CheckDevice())->ping($ip)){
                $text.= "  :Ping O.K";
                $sqlUpd="UPDATE `redesagi_facturacion`.`liveinfo` SET `liveinfo`.`ping`='1',`liveinfo`.`hora`='$hourMin' WHERE `liveinfo`.`id`='$id'  ";
                echo $sqlUpd;
                if($result2 = $mysqli->query($sqlUpd)){						
                    echo "Reactivación con éxito!!";	
                }
                else{
                    echo "Error al actualizar cliente(Udpate reactivar.)";	
                }	
            }
            else{
                $text.= "  :Dead";
                $sqlUpd="UPDATE `redesagi_facturacion`.`liveinfo` SET `liveinfo`.`ping`='0',`liveinfo`.`hora`='$hourMin' WHERE `liveinfo`.`id`='$id'  ";
                echo $sqlUpd;
                if($result2 = $mysqli->query($sqlUpd)){						
                    echo "Actualizacion con éxito!!";	
                }
                else{
                    echo "Error al actualizar ";	 
                }

            } 
        } else {
            $text="Invalid";
        }
        print "\n id:$id nombre:$nombre apellido:$apellido ip:$text \n";
    }   
}
else{
    echo "Error al consultar valor plan del cliente";
}		
			

?>