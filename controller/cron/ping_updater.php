<?php 
require '/var/www/ispexperts/PingTime.php';
require '/var/www/ispexperts/Mkt.php';
require '/var/www/ispexperts/CheckDevice.php';
include("/var/www/ispexperts/login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error; 
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$sqlSearch="SELECT * FROM `afiliados` WHERE  `eliminar`=0 AND `activo`=1 AND `id`='83' ";
if ($result = $mysqli->query($sqlSearch)) {
    while($row = $result->fetch_assoc()) {
        $signal=0;
        $id=$row["id"];
        $ipAddress=$row['ip'];
        $currentPingDateInAfiliadosTable=$row['pingDate'];
        $device=new CheckDevice();
        if($device->ping($ipAddress)){
            $timeObj=new PingTime($ipAddress); 
            if( $responseTime=$timeObj->time() && $responseTime>0){
                $sqlupdate="UPDATE `redesagi_facturacion`.`afiliados` SET `ping` = '$responseTime' , `pingDate` = '$today'  WHERE (`id` = '$id');";
            }elseif ($currentPingDateInAfiliadosTable) {
                $sqlupdate="UPDATE `redesagi_facturacion`.`afiliados` SET `ping` = ''   WHERE (`id` = '$id')";
                print "\nDont touch pingDate";
            }else{
                $sqlupdate="UPDATE `redesagi_facturacion`.`afiliados` SET `ping` = '' , `pingDate` = null  WHERE (`id` = '$id')";
                print "\npingDate and ping to NULL";
            }
            print "\n $sqlupdate \n";
            $mysqli->query($sqlupdate);
        }
    }
}
echo "Script ping updater ejecutado: $today::$hourMin\n";
?>