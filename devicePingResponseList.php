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
include("PingTime.php");
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
$mainServerIp=mysqli_real_escape_string($mysqli, $_REQUEST['mainServerIp']);
$from=mysqli_real_escape_string($mysqli, $_REQUEST['from']);
$to  =mysqli_real_escape_string($mysqli, $_REQUEST['to']);
$byteToChange=mysqli_real_escape_string($mysqli,$_REQUEST['byteToChange']);
$rowNumbers=mysqli_real_escape_string($mysqli,$_REQUEST['rowNumbers']);
$ipParts=explode(".",$from);
$valueBytetoChange=$ipParts[$byteToChange];
$ipList =array();
if(($rowNumbers+$valueBytetoChange)>254)$rowNumbers=254-$valueBytetoChange;
$device= new PingTime($mainServerIp);        
if($device->time()){
    for ($i=$valueBytetoChange; $i <($valueBytetoChange+$rowNumbers) ; $i++) {
        $ipParts[$byteToChange]=$i; 
        $dotSeparated=implode(".",$ipParts);
        $sql="select `id` FROM `afiliados` WHERE  `eliminar`=0 AND `activo`=1  AND `suspender`=0 and `ip` like '$dotSeparated' ";
        $rt=$mysqli->query($sql);        
        $device2= new PingTime($dotSeparated);
        if(!$device->time() || !$rt->num_rows){
            array_push($ipList,$dotSeparated);  
        }     
        
    }
    echo json_encode($ipList);
}
else{
    array_push($ipList,"No server: response!");            
}
?>