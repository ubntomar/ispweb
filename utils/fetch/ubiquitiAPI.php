<?php
require("../../login/db.php");
require("../../controller/brand/Ubiquiti.php");
require("../../controller/src/Repeater.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$response=null;

if($_SERVER['REQUEST_METHOD']==='GET' ){
    // $option="repeaterSignal";
    switch($_GET["option"]){//
        case "repeaterSignal":{
            $ipAddress=$mysqli -> real_escape_string($_GET["ipAddress"]);
            if(explode(".",$ipAddress)[3]!=1){
                $repeaterObject=new Repeater($server, $db_user, $db_pwd, $db_name); 
                if($result=$repeaterObject->getRepeaterItem($table='vpn_targets',$item="server-ip",$value=$ipAddress,$target="cpe-ubiquiti")){
                    $ubiquitiObject=new Ubiquiti($ipRouter=$result, $user=$ubiquiti_default_repeater_user, $pass=$ubiquiti_default_repeater_password);
                    $signal=intval($ubiquitiObject->getUbiquitiSignal());  
                    $response= '{"signal": "'.$signal.'"}';
                }
            }
            
            break;
        }
    }
}
if($_SERVER['REQUEST_METHOD']==='POST' ){ 
    switch($_POST["option"]){
        case "???":{
            // $newIpSegment=$mysqli -> real_escape_string($_POST["newIpSegment"]);
            
            break; 
        }
    }
}
if(!$response)$response= '{"status": "fail"}';
echo $response;

?>