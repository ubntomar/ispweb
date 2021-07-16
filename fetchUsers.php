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
include("login/db.php");
require 'Mkt.php';
require 'vpnConfig.php';
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

// $searchString="herna";
// $searchOption="null"; 
$searchString=$mysqli -> real_escape_string($_GET["searchString"]);
$searchOption=$mysqli -> real_escape_string($_GET["searchOption"]);

if($mkobj=new Mkt($serverIpAddressArea1,$vpnUser,$vpnPassword)){
    $exclusivosList=$mkobj->list_all();        
}

if($_SERVER['REQUEST_METHOD']==='POST') {
    $idRow = $_POST['idRow'];
    $ipRow = $_POST['ipRow'];
    $sql_update = "UPDATE `redesagi_facturacion`.`afiliados` SET `ip` = '$ipRow' WHERE (`id` = '$idRow');";
    $result = $mysqli->query($sql_update) or die('error');
    if($result){
        $retObj=(object)["id"=>"$idRow","ip"=>"$ipRow","status"=>"success"];
        echo json_encode($retObj); 
    }
 }

if ($searchOption=="Todos"){
    if($searchString!="") $queryPart="AND ( (`cliente` LIKE '%$searchString%') OR (`apellido` LIKE '%$searchString%') OR (`ip` LIKE '%$searchString%') ) ";
    else $queryPart="LIMIT 3";
    $sqlSearch="SELECT `id`,`cliente`,`apellido`,`ip`,`ping`,`pingDate`,`suspender` FROM `redesagi_facturacion`.`afiliados` WHERE  `eliminar`=0 AND `activo`=1 $queryPart  "; 
    if ($result = $mysqli->query($sqlSearch)) {
        $num=$result->num_rows;
        $counter=0;
        while($row = $result->fetch_assoc()) {
            $counter+=1;
            $id=$row['id'];
            $name=strtoupper($row["cliente"]." ".$row['apellido']);
            $ipAddress=$row["ip"];
            ($row["pingDate"]==$today) ? $pingStatus='up':$pingStatus='down';
            foreach ($exclusivosList as $value) {
                if($value['ip']==$ipAddress)	$pingStatus='Pending';
            } 
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
            $ping[] = array("ipText"=>"","ipIconSpin"=>false,"validIp"=>"true","counter"=>"$counter","id"=>"$id", "name"=>"$name", "ipAddress"=>"$ipAddress", "pingStatus"=>"$pingStatus", "responseTime"=>"$responseTime","suspender"=>"$suspender","elapsedTime"=>$elapsedTime);
        }
        $ping[]=array("numResult"=>"$num");
    }
    echo json_encode($ping);
}

if($searchOption=="Cortado"){
    $sqlSearch="SELECT `id`,`cliente`,`apellido`,`ip`,`ping`,`pingDate`,`suspender` FROM `redesagi_facturacion`.`afiliados` WHERE  `eliminar`=0 AND `activo`=1  AND `suspender`=1 "; 
    if ($result = $mysqli->query($sqlSearch)) {
        $num=$result->num_rows;
        $counter=0;
        while($row = $result->fetch_assoc()) {
            $counter+=1;
            $id=$row['id'];
            $name=strtoupper($row["cliente"]." ".$row['apellido']);
            $ipAddress=$row["ip"];
            ($row["pingDate"]==$today) ? $pingStatus='up':$pingStatus='down'; 
            foreach ($exclusivosList as $value) {
                if($value['ip']==$ipAddress)	$pingStatus='Pending';
            }
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
            $ping[] = array("ipText"=>"","ipIconSpin"=>false,"validIp"=>"true","counter"=>"$counter","id"=>"$id", "name"=>"$name", "ipAddress"=>"$ipAddress", "pingStatus"=>"$pingStatus", "responseTime"=>"$responseTime","suspender"=>"$suspender","elapsedTime"=>$elapsedTime);
        }
        $ping[]=array("numResult"=>"$num");
    }
echo json_encode($ping);   
}

if($searchOption=="Ping OK"){
    $sqlSearch="SELECT `id`,`cliente`,`apellido`,`ip`,`ping`,`pingDate`,`suspender` FROM `redesagi_facturacion`.`afiliados` WHERE  `eliminar`=0 AND `activo`=1  AND `ping`!='NULL' LIMIT 1"; 
    if ($result = $mysqli->query($sqlSearch)) {
        $num=$result->num_rows;
        $counter=0;
        while($row = $result->fetch_assoc()) {
            $counter+=1;
            $id=$row['id'];
            $name=strtoupper($row["cliente"]." ".$row['apellido']);
            $ipAddress=$row["ip"];
            ($row["pingDate"]==$today) ? $pingStatus='up':$pingStatus='down'; 
            foreach ($exclusivosList as $value) {
                if($value['ip']==$ipAddress)	$pingStatus='Pending';
            }
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
            $ping[] = array("ipText"=>"","ipIconSpin"=>false,"validIp"=>"true","counter"=>"$counter","id"=>"$id", "name"=>"$name", "ipAddress"=>"$ipAddress", "pingStatus"=>"$pingStatus", "responseTime"=>"$responseTime","suspender"=>"$suspender","elapsedTime"=>$elapsedTime);
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
            ($row["pingDate"]==$today) ? $pingStatus='up':$pingStatus='down'; 
            foreach ($exclusivosList as $value) {
                if($value['ip']==$ipAddress)	$pingStatus='Pending';
            }
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
            $ping[] = array("ipText"=>"","ipIconSpin"=>false,"validIp"=>"true","counter"=>"$counter","id"=>"$id", "name"=>"$name", "ipAddress"=>"$ipAddress", "pingStatus"=>"$pingStatus", "responseTime"=>"$responseTime","suspender"=>"$suspender","elapsedTime"=>$elapsedTime);
        }
        $ping[]=array("numResult"=>"$num");
    }
echo json_encode($ping);   
}
?>