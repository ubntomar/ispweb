<?php
require("../src/Repeater.php");
require("../../login/db.php"); 
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
if( $_SERVER['REQUEST_METHOD']==='POST' ){
    switch ($mysqli -> real_escape_string($_GET["option"])) {
        case 'getRepeaterList':{
            $repeaterObj=new Repeater($server, $db_user, $db_pwd, $db_name);
            $response=$repeaterObj->getServersList();
            break;
        } 
        
        default:{
            break;
        }
    }
}

echo json_encode($response);


?>