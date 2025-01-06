<?php 
require '/var/www/ispexperts/PingTime.php';
require '/var/www/ispexperts/Mkt.php';
require '/var/www/ispexperts/CheckDevice.php';
require("/var/www/ispexperts/VpnUtils.php");
include("/var/www/ispexperts/login/db.php");
include("/var/www/ispexperts/Client.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$sqlSearch="SELECT * FROM `afiliados` WHERE  `eliminar`=0 AND `activo`=1  ";
$file = '/var/www/ispexperts/controller/cron/logs_reconect_updated.txt';
$content .= "\nReconect Pending Updater $convertdate $hourMin  \n";
$clientObject=new Client($server, $db_user, $db_pwd, $db_name);
if ($result = $mysqli->query($sqlSearch)) {
    while($row = $result->fetch_assoc()) {
        $listStatus=0;
        $id=$row["id"];
        $name=$row["cliente"];
        $lastName=$row["apellido"];
        $address=$row["direccion"];
        $ipAddress=$row['ip'];
        $sqlSaldo="SELECT * FROM `factura` WHERE `cerrado`=0 AND `id-afiliado`=$id";
        if ($resultSaldo = $mysqli->query($sqlSaldo)) {
            if(!$rowSaldo = $resultSaldo->fetch_assoc()) {
                $serverIp=serverIP($server, $db_user, $db_pwd, $db_name,$id,$ipAddress);
                $device=new CheckDevice();
                if($device->ping($serverIp)){
                    $timeResponse=new PingTime($serverIp); 
                    if($time=$timeResponse->time()){
                        try{
                            if($mkobj=new Mkt($serverIp,$rb_server_default_user,$rb_default_password)){
                                if($mkobj->success){
                                    $listStatus= $mkobj->verifyList("morosos",$ipAddress)?1:0;
                                    if($listStatus){    
                                        print "\nclient $id  $name  $lastName ip address $ipAddress server:$serverIp is  in list 'morosos' , saldo 0 .. vamos a generar reconnect=1\n";
                                        $content="\n$today  $hourMin  client $id  $name  $lastName ip address $ipAddress server:$serverIp is  in list 'morosos' , saldo 0 .. vamos a generar reconnect=1";
                                        $clientObject->updateClient($id,$param="reconectPending",1,$operator="=");
                                        $clientObject->updateClient($id,$param="shutoffpending",0,$operator="=");
                                        $clientObject->updateClient($id,$param="suspender",0,$operator="="); 
                                    }
                                    
                                }else {
                                    print "\t Fail connecting for $id";
                                }
                            }
                            
                        }catch (Exception $e) {
                            echo 'Caught exception: ',  $e->getMessage(), "\n"; 
                        } 
                    }
                }
                $clientObject->updateClient($id,$param="reconectPending",1,$operator="=");
            }
        }
        
    }
}




$content .= PHP_EOL;
file_put_contents($file, $content, FILE_APPEND | LOCK_EX); 

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