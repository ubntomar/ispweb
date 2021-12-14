<?php 
require 'PingTime.php';
require 'Mkt.php';
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
if ($result = $mysqli->query($sqlSearch)) {
    while($row = $result->fetch_assoc()) {
        $signal=0;
        $id=$row["id"];
        $ipAddress=$row['ip'];
        $device=new CheckDevice();
        if($device->ping($ipAddress)){
            $timeResponse=new PingTime($ipAddress); 
            if($time=$timeResponse->time()){
                try{
                    if($mkobj=new Mkt($ipAddress,$user=$rb_default_user,$password=$rb_default_password)){
                        if($mkobj->success){
                            $signal= $mkobj->checkSignal(); 
                        }
                    }
                }catch (Exception $e) {
                    // echo 'Caught exception: ',  $e->getMessage(), "\n"; 
                } 
                $sqlupdate="UPDATE `redesagi_facturacion`.`afiliados` SET `ping` = '$time' , `pingDate` = '$today' , `signal-strenght`='$signal' WHERE (`id` = '$id');";
                print "\n $sqlupdate \n ";
                $updateresult=$mysqli->query($sqlupdate);
                //print "UPDATE `redesagi_facturacion`.`afiliados` SET `ping` = '$time' WHERE (`id` = '$id')";
            }
        }
    }
}
echo "Script ping updater ejecutado: $today::$hourMin\n";
?>