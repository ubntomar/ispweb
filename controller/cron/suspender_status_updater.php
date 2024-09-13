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
$sqlSearch="SELECT * FROM `afiliados` WHERE  `eliminar`=0 AND `activo`=1 AND `suspender`=1 ";
$file = 'logs.txt';
$current = file_get_contents($file);
$clientObject=new Client($server, $db_user, $db_pwd, $db_name);
if ($result = $mysqli->query($sqlSearch)) {
    while($row = $result->fetch_assoc()) {
        $listStatus=0;
        $id=$row["id"];
        $name=$row["cliente"];
        $lastName=$row["apellido"];
        $address=$row["direccion"];
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
                            $listStatus= $mkobj->verifyList("morosos",$ipAddress)?1:0;
                            if($listStatus){    
                                print "\nclient $id is already in list 'morosos'!\n";
                                $date=$today;
                            }else{
                                print "\nclient $id is not in list 'morosos'!\n";
                                //client must be inserted in 'morosos'!  /
                                $res=$mkobj->add_address($ip=$ipAddress,$listName="morosos",$idUser=$id,$name,$lastName,$direccion=$address,$fecha=$convertdate); 
                                if($res==1){
                                    print "\n \t\t\t\t\t$name $lastName Agregado a morosos con éxito \n";
                                    $current.="$id $name $lastName Agregado a morosos con éxito";
                                    $listStatus=1;
                                    $date=$today;
                                }else{
                                    print "\n \t\t\t\t\t$name $lastName NO FUE Agregado a morosos con éxito \n";
                                    $current.="$id $name $lastName NO FUE Agregado a morosos con éxito";
                                    $listStatus=0;
                                    $date=NULL;
                                }
                            }
                            $clientObject->updateClient($id,$param="suspender-list-status",$listStatus,$operator="=");
                            if($listStatus){
                                $clientObject->updateClient($id,$param="suspender-list-status-date",$date,$operator="=");
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
    }
}


$sqlupdateSuspenderList="SELECT * FROM `afiliados` WHERE  `eliminar`=0 AND `activo`=1 AND `suspender`=0 ";
if ($result = $mysqli->query($sqlupdateSuspenderList)) {
    while($row = $result->fetch_assoc()) {
        $id=$row["id"];
        $clientObject->updateClient($id,$param="suspender-list-status",0,$operator="=");
        $clientObject->updateClient($id, $param="suspender-list-status-date", $date=NULL, $operator="=");
        print "\nclient $id set not in list 'morosos' because suspender=0'!\n";
    }
}


file_put_contents($file, $current);

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