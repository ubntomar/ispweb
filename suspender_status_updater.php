<?php 
require 'PingTime.php';
require 'Mkt.php';
require 'CheckDevice.php';
require("VpnUtils.php");
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
$sqlSearch="SELECT * FROM `afiliados` WHERE  `eliminar`=0 AND `activo`=1 AND `suspender`=1  ";
if ($result = $mysqli->query($sqlSearch)) {
    while($row = $result->fetch_assoc()) {
        $listStatus=1;
        $id=$row["id"];
        $ipAddress=$row['ip'];
        print "ip address $ipAddress";
        $serverIp=serverIP($server, $db_user, $db_pwd, $db_name,$id,$ipAddress);
        print "\n server:$serverIp\n";
        $device=new CheckDevice();
        if($device->ping($serverIp)){
            $timeResponse=new PingTime($serverIp); 
            if($time=$timeResponse->time()){
                try{
                    if($mkobj=new Mkt($serverIp,$rb_server_default_user,$rb_default_password)){
                        if($mkobj->success){
                            $listStatus= $mkobj->verifyList("morosos",$ipAddress)==true?1:0;
                        }
                    }
                }catch (Exception $e) {
                    // echo 'Caught exception: ',  $e->getMessage(), "\n"; 
                } 
                $sqlupdate="UPDATE `redesagi_facturacion`.`afiliados` SET `suspender-list-status`='$listStatus' WHERE (`id` = '$id')";
                print "\n $sqlupdate \n ";
                $updateresult=$mysqli->query($sqlupdate);
            }
        }
    }
}
// echo "Script ping updater ejecutado: $today::$hourMin\n";



function serverIP($server, $db_user, $db_pwd, $db_name,$id,$ipAddress){
    $res="0.0.0.1";
    if($ipAddress!="0.0.0.0"){
        $vpnObject2=new VpnUtils($server, $db_user, $db_pwd, $db_name);  
        $idGroup=$vpnObject2->updateGroupId($id,$ipAddress); 
        $res= $vpnObject2->getServerIp($idGroup); 
    }
    return $res;
}

?>