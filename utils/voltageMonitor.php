<?php
require("../login/db.php"); 
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
if( $_SERVER['REQUEST_METHOD']==='GET' ){
    switch ($mysqli -> real_escape_string($_GET["location"])) {
        case 'retiro':{
            $response=exec("curl http://192.168.30.254/50");
            //$response=str_replace("'","\"","{".$response."}"); 
            $jArr = json_decode($response,true);
            $rele= $jArr["data"]["rele"];
            $batteryVolts=$jArr["data"]["sensor1"]; 
            $acDcVolt=$jArr["data"]["sensor2"]; 
            $response=
            '{
                "dc": "'.$batteryVolts.'",
                "rele": "'.$rele.'",
                "ac": "'.$acDcVolt.'"
            }';
            break;
        } 
        
        default:{
            break;
        }
    } 
}
echo json_encode($response);
?>