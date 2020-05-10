<?php 
require 'PingTime.php';
require 'CheckDevice.php';
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
$sqlSearch="SELECT * FROM `afiliados` WHERE  `eliminar`=0 AND `activo`=1 ";
if ($result = $mysqli->query($sqlSearch)&&false) {
    while($row = $result->fetch_assoc()) {
        $id=$row["id"];
        $ipAddress=$row['ip'];
        $device=new CheckDevice();
        if($device->ping($ipAddress)){
            $timeResponse=new PingTime($ipAddress); 
            if($time=$timeResponse->time()){
                $sqlupdate="UPDATE `redesagi_facturacion`.`afiliados` SET `ping` = '$time' WHERE (`id` = '$id');";
                $updateresult=$mysqli->query($sqlupdate);
                //print "UPDATE `redesagi_facturacion`.`afiliados` SET `ping` = '$time' WHERE (`id` = '$id')";
            }
        }
    }
}
echo "Script ping ejecutado: $today::$hourMin\n";
?>