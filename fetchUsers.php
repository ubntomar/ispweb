<?php 
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: login/index.php');
		exit;
		}
else    {
		$user=$_SESSION['username'];
		}
header('Content-Type: application/json');
require 'vendor/autoload.php';
require 'dateHuman.php';
require("login/db.php");
require 'Mkt.php';
require 'vpnConfig.php';
require("VpnUtils.php");
require("PingTime.php");
require("controller/brand/Ubiquiti.php");
//
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$ping = array();
$searchString=$mysqli -> real_escape_string($_GET["searchString"]);
$searchOption=$mysqli -> real_escape_string($_GET["searchOption"]);
if($_SERVER['REQUEST_METHOD']==='POST') {  //    Update Ip and others  Block
    $idRow = $_POST['idRow'];
    $ipRow = $_POST['ipRow'];
    $sql_update = "UPDATE `redesagi_facturacion`.`afiliados` SET `ip` = '$ipRow' WHERE (`id` = '$idRow');";
    $result = $mysqli->query($sql_update) or die('error');
    if($result){
        $vpnObject=new VpnUtils($server, $db_user, $db_pwd, $db_name);
        $idGroup=$vpnObject->updateGroupId($idRow,$ipRow);
        $serverIp=serverIP($server, $db_user, $db_pwd, $db_name,$idRow,$ipRow);
        $id=$idRow;
        $ip=$ipRow;
        $pingObject=new PingTime($serverIp);
        if($pingObject->time(1)){
            $response=addToNatRule($serverIp,$rb_default_dstnat_port,$rb_default_user,$rb_default_password,$vpnUser,$vpnPassword,$id,$ip,$router_default_wanIp_cpe_mktik,false); 
            $dstnatResponse=($response["result"]=="1" || $response["result"]=="3") ? "Actived-Mikrotik":"Inactive";
            $dstnatTarget=$response["dstnatTarget"];
            $arp=getArp($serverIp,$rb_default_dstnat_port,$rb_default_user,$rb_default_password,$vpnUser,$vpnPassword,$ip);
            $signal=getSignal($serverIp,$rb_default_dstnat_port,$rb_default_user,$rb_default_password,$ubiquiti_default_user,$ubiquiti_default_password,$vpnUser,$vpnPassword,$ip);
            $queue=Queue($serverIp,$rb_default_dstnat_port,$rb_default_user,$rb_default_password,$vpnUser,$vpnPassword,$ip);
        }else{
            $dstnatResponse="Comunication Error";
            $dstnatTarget="Comunication Error";
        }
        $response=checkPort($ip,$serverIp,$id,$rb_default_dstnat_port);
        $portStatus=$response["portStatus"];
        $port=$response["port"];
        $retObj=["signal"=>"$signal","queue"=>"$queue","arp"=>$arp,"arpTarget"=>"$dstnatTarget","portStatus"=>"$portStatus","id"=>"$idRow","ip"=>"$ipRow","status"=>"success","idGroup"=>"$idGroup","dstnatResponse"=>"$dstnatResponse","port"=>"$port","dstnatTarget"=>"$dstnatTarget","ipAddress"=>"$ip"]; 
        echo json_encode($retObj); 
    }
}

if ($searchOption=="Todos"){ 
    if(filter_var($searchString, FILTER_VALIDATE_IP)){
        $queryPart="AND `ip` LIKE '%$searchString%' ";
    }elseif($searchString!="" && !filter_var($searchString, FILTER_VALIDATE_IP) ){
        $name=explode(" ",$searchString)[0];
        $ln=explode(" ",$searchString)[1];
        $lastName=$ln!=null?$ln:"";
        $queryPart="AND ( (`cliente` LIKE '%$name%') OR (`apellido` LIKE '%$lastName%'))  ORDER BY `id` DESC LIMIT 1"; 
        echo $queryPart;
    }
    //$queryPart.=" ORDER BY `id` DESC LIMIT 1";
    
    $sqlSearch="SELECT * FROM `redesagi_facturacion`.`afiliados` WHERE  `eliminar`=0 AND `activo`=1 $queryPart  "; 
    // echo $sqlSearch;
    if ($result = $mysqli->query($sqlSearch)) {
        $num=$result->num_rows;
        $counter=0;
        while($row = $result->fetch_assoc()) {
            $counter+=1;
            $id=$row['id'];
            $name=strtoupper($row["cliente"]." ".$row['apellido']);
            $ipAddress=$row["ip"];
            $direccion=$row["direccion"];
            $serverIp=serverIP($server, $db_user, $db_pwd, $db_name,$id,$ipAddress);
            $response=checkPort($ipAddress,$serverIp,$id,$rb_default_dstnat_port);
            $portStatus=$response["portStatus"];
            $port=$response["port"];
            //$id=$idRow; 
            $ip=$ipAddress; 
            $pingObject=new PingTime($serverIp);
            if($pingObject->time(1)){
                $response=addToNatRule($serverIp,$rb_default_dstnat_port,$rb_default_user,$rb_default_password,$vpnUser,$vpnPassword,$id,$ip,$router_default_wanIp_cpe_mktik,false); 
                $dstnatResponse=($response["result"]=="1" || $response["result"]=="3") ? "Actived-Mikrotik":"Inactive";
                $dstnatTarget=$response["dstnatTarget"];
                $arp=getArp($serverIp,$rb_default_dstnat_port,$rb_default_user,$rb_default_password,$vpnUser,$vpnPassword,$ip);
                $signal=getSignal($serverIp,$rb_default_dstnat_port,$rb_default_user,$rb_default_password,$ubiquiti_default_user,$ubiquiti_default_password,$vpnUser,$vpnPassword,$ip);
                $queue=Queue($serverIp,$rb_default_dstnat_port,$rb_default_user,$rb_default_password,$vpnUser,$vpnPassword,$ip);
            }else{
                $dstnatResponse="Comunication Error";
                $dstnatTarget="Comunication Error";
            }
            ($row["pingDate"]==$today) ? $pingStatus='up':$pingStatus='down';
            $responseTime=$row["ping"];
            ($row["suspender"])? $suspender="cortado":$suspender="";
            $pingDate=$row["pingDate"];   
            if($pingDate){
                if($elapsedTime=get_date_diff( $pingDate, $today, 2 ));
                else $elapsedTime="Hoy";             
            }
            else{
                $elapsedTime=""; 
            }
            $ping[] = array("signal"=>"$signal","queue"=>"$queue","arp"=>$arp,"arpTarget"=>"$dstnatTarget","dstnatTarget"=>"$dstnatTarget","dstnatResponse"=>$dstnatResponse,"port"=>"$port","portStatus"=>"$portStatus","serverIp"=>"$serverIp","ipText"=>"","ipIconSpin"=>false,"validIp"=>"true","counter"=>"$counter","id"=>"$id", "name"=>"$name", "direccion"=>"$direccion", "ipAddress"=>"$ipAddress", "pingStatus"=>"$pingStatus", "responseTime"=>"$responseTime","suspender"=>"$suspender","elapsedTime"=>$elapsedTime);
        }
        $ping[]=array("numResult"=>"$num");
    }
    echo json_encode($ping); 
}

if($searchOption=="Cortado"){
    $sqlSearch="SELECT * FROM `redesagi_facturacion`.`afiliados` WHERE  `eliminar`=0 AND `activo`=1  AND `suspender`=1 "; 
    if ($result = $mysqli->query($sqlSearch)) { 
        $num=$result->num_rows;
        $counter=0;
        while($row = $result->fetch_assoc()) {
            $counter+=1;
            $id=$row['id'];
            $name=strtoupper($row["cliente"]." ".$row['apellido']);
            $ipAddress=$row["ip"];
            $direccion=$row["direccion"];
            $serverIp=serverIP($server, $db_user, $db_pwd, $db_name,$id,$ipAddress);
            ($row["pingDate"]==$today) ? $pingStatus='up':$pingStatus='down'; 
            
            $responseTime=$row["ping"];
            ($row["suspender"])? $suspender="cortado":$suspender="";
            $pingDate=$row["pingDate"];   
            if($pingDate){
                if($elapsedTime=get_date_diff( $pingDate, $today, 2 ));
                else $elapsedTime="Hoy";             
            }
            else{
                $elapsedTime="";
            }
            $ping[] = array("serverIp"=>"$serverIp","ipText"=>"","ipIconSpin"=>false,"validIp"=>"true","counter"=>"$counter","id"=>"$id", "name"=>"$name", "direccion"=>"$direccion", "ipAddress"=>"$ipAddress", "pingStatus"=>"$pingStatus", "responseTime"=>"$responseTime","suspender"=>"$suspender","elapsedTime"=>$elapsedTime);
        }
        $ping[]=array("numResult"=>"$num");
    }
echo json_encode($ping);   
}

if($searchOption=="Ping OK"){
    $sqlSearch="SELECT * FROM `redesagi_facturacion`.`afiliados` WHERE  `eliminar`=0 AND `activo`=1  AND `ping`!='NULL' LIMIT 1"; 
    if ($result = $mysqli->query($sqlSearch)) {
        $num=$result->num_rows;
        $counter=0;
        while($row = $result->fetch_assoc()) {
            $counter+=1;
            $id=$row['id'];
            $name=strtoupper($row["cliente"]." ".$row['apellido']);
            $ipAddress=$row["ip"];
            $direccion=$row["direccion"];
            $serverIp=serverIP($server, $db_user, $db_pwd, $db_name,$id,$ipAddress);
            ($row["pingDate"]==$today) ? $pingStatus='up':$pingStatus='down'; 
            
            $responseTime=$row["ping"];
            ($row["suspender"])? $suspender="cortado":$suspender="";
            $pingDate=$row["pingDate"];   
            if($pingDate){
                if($elapsedTime=get_date_diff( $pingDate, $today, 2 ));
                else $elapsedTime="Hoy";             
            }
            else{
                $elapsedTime="";
            }
            $ping[] = array("serverIp"=>"$serverIp","ipText"=>"","ipIconSpin"=>false,"validIp"=>"true","counter"=>"$counter","id"=>"$id", "name"=>"$name", "direccion"=>"$direccion", "ipAddress"=>"$ipAddress", "pingStatus"=>"$pingStatus", "responseTime"=>"$responseTime","suspender"=>"$suspender","elapsedTime"=>$elapsedTime);
        }
        $ping[]=array("numResult"=>"$num");
    }
echo json_encode($ping);   
}
 
if($searchOption=="Ping Down"){
    //SELECT * FROM `redesagi_facturacion`.`afiliados` WHERE `eliminar`=0 AND `activo`=1 AND ( (`pingDate` < DATE_SUB(NOW(), INTERVAL 5 DAY)) OR (`ping` is NULL) ) ORDER BY `afiliados`.`pingDate` DESC
    $sqlSearch="SELECT * FROM `redesagi_facturacion`.`afiliados` WHERE `eliminar`=0 AND `activo`=1 AND ( (`pingDate` < DATE_SUB(NOW(), INTERVAL 5 DAY)) OR (`ping` is NULL) ) ORDER BY `afiliados`.`pingDate` DESC LIMIT 1 "; 
    if ($result = $mysqli->query($sqlSearch)) { 
        $num=$result->num_rows;
        $counter=0;
        while($row = $result->fetch_assoc()) {
            $counter+=1;
            $id=$row['id'];
            $name=strtoupper($row["cliente"]." ".$row['apellido']);
            $ipAddress=$row["ip"];
            $direccion=$row["direccion"];
            $serverIp=serverIP($server, $db_user, $db_pwd, $db_name,$id,$ipAddress);
            ($row["pingDate"]==$today) ? $pingStatus='up':$pingStatus='down'; 
            
            $responseTime=$row["ping"];
            ($row["suspender"])? $suspender="cortado":$suspender="";
            $pingDate=$row["pingDate"];   
            if($pingDate){
                if($elapsedTime=get_date_diff( $pingDate, $today, 2 ));
                else $elapsedTime="Hoy";             
            }
            else{
                $elapsedTime="";
            }
            $ping[] = array("serverIp"=>"$serverIp","ipText"=>"","ipIconSpin"=>false,"validIp"=>"true","counter"=>"$counter","id"=>"$id", "name"=>"$name", "direccion"=>"$direccion", "ipAddress"=>"$ipAddress", "pingStatus"=>"$pingStatus", "responseTime"=>"$responseTime","suspender"=>"$suspender","elapsedTime"=>$elapsedTime);
        }
        $ping[]=array("numResult"=>"$num");
    }
echo json_encode($ping);   
}


function serverIP($server, $db_user, $db_pwd, $db_name,$id,$ipAddress){
    $res="0.0.0.0";
    if($ipAddress!="0.0.0.0"){
        $vpnObject2=new VpnUtils($server, $db_user, $db_pwd, $db_name);  
        $idGroup=$vpnObject2->updateGroupId($id,$ipAddress); 
        $res= $vpnObject2->getServerIp($idGroup); 
    }
    return $res;
}
function checkPort($ip,$serverIp,$id,$rb_default_dstnat_port){ 
    $serverLasByte=explode(".",$serverIp)[3];
    if($serverLasByte=="1"){
        $portToCheck=$rb_default_dstnat_port;
    }
    else {
        $portToCheck=($id<1000)?"8".$id:$id+2000;//8524 ó 3050 
    } 
    if(filter_var($ip, FILTER_VALIDATE_IP)){
    $host = $ip;
    $ports = array($portToCheck);
    foreach ($ports as $port)
        {
            $connection = @fsockopen($host, $port, $errno, $errstr, 2);  
            if (is_resource($connection))
            {
                $result="Open";
                fclose($connection);
            }
            else
            {
                $result="Closed";
            }
        }
        return  array("port"=>"$portToCheck","portStatus"=>"$result"); 
   } 
   else{
       return "$ip Invalid";
   }
}


function addToNatRule($serverIp,$rb_default_dstnat_port,$rb_default_user,$rb_default_password,$vpnUser,$vpnPassword,$idRow,$ipRow,$router_default_wanIp_cpe_mktik,$check){
    $serverLasByte=explode(".",$serverIp)[3];
    if($serverLasByte=="1"){
        $port=$rb_default_dstnat_port;
        $user=$rb_default_user;
        $password=$rb_default_password;    
    }
    else {
        $user=$vpnUser;
        $password=$vpnPassword;
        $port=($idRow<1000)?"8".$idRow:$idRow+2000;//8524 ó 3050
    }
    $comment="Rule to can access to client $idRow  from ip $ipRow of server $serverIp  created from  fetchUser line 48";
    $targetIp=($serverLasByte=="1")?$ipRow:$serverIp;//example 21.1 17.163 ?
    $dstnatTarget=($serverLasByte=="1")?"Cpe":"Repeater";//example 21.1 17.163 ?
    $toAddressesParam=($serverLasByte=="1")? $router_default_wanIp_cpe_mktik:$ipRow;
    $result=2;//fail
    try{
        //print "($mkobj=new Mkt($targetIp,$user,$password)){";
        if($mkobj=new Mkt($targetIp,$user,$password)){
            if($mkobj->success){
                $result= $mkobj->addNat($port,$comment,$toAddressesParam,$check); 
            }
        }
    }catch (Exception $e) {
        // echo 'Caught exception: ',  $e->getMessage(), "\n"; 
    }
    return array("result"=>"$result","port"=>"$port","dstnatTarget"=>"$dstnatTarget");
}
function Queue($serverIp,$rb_default_dstnat_port,$rb_default_user,$rb_default_password,$vpnUser,$vpnPassword,$ipRow){
    $serverLasByte=explode(".",$serverIp)[3];
    if($serverLasByte=="1"){
        $user=$rb_default_user;
        $password=$rb_default_password;    
    }
    else {
        $user=$vpnUser;
        $password=$vpnPassword;
    }
    $targetIp=($serverLasByte=="1")?$ipRow:$serverIp;//example 21.1 17.163 ?
    try{
        if($mkobj=new Mkt($targetIp,$user,$password)){
            if($mkobj->success){
                $result= $mkobj->checkQueue($ipRow)=="3"?"Success":"Fail"; 
            }
        }
    }catch (Exception $e) {
        // echo 'Caught exception: ',  $e->getMessage(), "\n"; 
    }
    return $result;
}
function getArp($serverIp,$rb_default_dstnat_port,$rb_default_user,$rb_default_password,$vpnUser,$vpnPassword,$ip){
    $serverLastByte=explode(".",$serverIp)[3];
    if($serverLastByte=="1"){
        $user=$rb_default_user;
        $password=$rb_default_password;    
    }
    else {
        $user=$vpnUser;
        $password=$vpnPassword;
    }
    $targetIp=($serverLastByte=="1")?$ip:$serverIp;//example 21.1 17.163 ?
    try{
        if($mkobj=new Mkt($targetIp,$user,$password)){
            if($mkobj->success){
                $result= $mkobj->arp(); 
            }
        }
    }catch (Exception $e) {
        // echo 'Caught exception: ',  $e->getMessage(), "\n"; 
    }
    return $result;
}
function getSignal($serverIp,$rb_default_dstnat_port,$rb_default_user,$rb_default_password,$ubiquiti_default_user,$ubiquiti_default_password,$vpnUser,$vpnPassword,$ip){
    $serverLastByte=explode(".",$serverIp)[3];
    if($serverLastByte=="1"){
        $user=$rb_default_user;
        $password=$rb_default_password; 
        try{
            if($mkobj=new Mkt($ip,$user,$password)){
                if($mkobj->success){
                    $result= $mkobj->checkSignal(); 
                }
            }
        }catch (Exception $e) {
            // echo 'Caught exception: ',  $e->getMessage(), "\n"; 
        }   
    }
    if(!$result){
        $port=22;
        $connection = @fsockopen($ip, $port,$errno, $errstr, 10);//last parameter is timeout
        if (is_resource($connection)){
            //print "si da ssh $ip";
            try {
                //code...
                $obj=new Ubiquiti($ip,$ubiquiti_default_user,$ubiquiti_default_password);
                $result=0;
                if($obj->status){   
                    $result=$obj->getUbiquitiSignal();
                     //print "id $id ---$time {$row["user"]} {$row["password"]} $ipValue: ok SIGNAL: $signal \n";
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }else{
            //echo "no da ssh";
        }
    }
    return $result; 
}








?>