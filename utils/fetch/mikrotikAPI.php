<?php
require("../../login/db.php");
require("../../Mkt.php");
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

if($_SERVER['REQUEST_METHOD']==='GET'){
    switch($_GET["option"]){
        case "repeaterSignal":{
            $ipAddress=$mysqli -> real_escape_string($_GET["ipAddress"]);
            $mktObject=new Mkt($ipRouter=$ipAddress, $user=$rb_default_repeater_user, $pass=$rb_default_repeater_password);
            $signal=$mktObject->checkSignal();
            $response= '{"signal": "'.$signal.'"}';
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