<?php 
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		// header('Location: login/index.php');
		// exit;
		}
else    {
		$user=$_SESSION['username'];
		}
header('Content-Type: application/json');
require 'vendor/autoload.php';
require 'dateHuman.php';

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
$ping = array();
$sqlSearch="SELECT `id`,`cliente`,`apellido`,`ip`,`ping`,`pingDate`,`suspender` FROM `redesagi_facturacion`.`afiliados` WHERE  `eliminar`=0 AND `activo`=1  limit 1 "; 
if ($result = $mysqli->query($sqlSearch)) {
    while($row = $result->fetch_assoc()) {
        $id=$row['id'];
        $name=strtoupper($row["cliente"]." ".$row['apellido']);
        $ipAddress=$row["ip"];
        ($row["ping"]) ? $pingStatus='up':$pingStatus='down';
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
        $ping[] = array("id"=>"$id", "name"=>"$name", "ipAddress"=>"$ipAddress", "pingStatus"=>"$pingStatus", "responseTime"=>"$responseTime","suspender"=>"$suspender","elapsedTime"=>$elapsedTime);
    }
}
echo json_encode($ping);
?>