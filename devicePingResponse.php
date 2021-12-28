<?php 
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true){
	header('Location: login/index.php');	
	exit;
}else{
	$user=$_SESSION['username'];
}
include("PingTime.php");
include("login/db.php");
include("Client.php");
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
$ipAddress=mysqli_real_escape_string($mysqli, $_REQUEST['ip']);
$clienteId=mysqli_real_escape_string($mysqli, $_REQUEST['id']);
$mainServerIp=mysqli_real_escape_string($mysqli, $_REQUEST['mainServerIp']);
$clientObj=new Client($server, $db_user, $db_pwd, $db_name);
if($mainServerIp){
	$device= new PingTime($mainServerIp);
	if($device->time()){
		$device2= new PingTime($ipAddress);
		$time= array("time"=>$device2->time());  
		echo json_encode($time); 
	}
	else{
		$time= array("time"=>"-1");
		echo json_encode($time); 
	}
}
else{
	$device2= new PingTime($ipAddress);
	$time= array("time"=>$device2->time());
	if($clienteId){
		$param="ping";
		$value=$time["time"];
		$clientObj->updateClient($clienteId,$param,$value,$operator="=");
		$param="pingDate";
		$value=$today;
		$clientObj->updateClient($clienteId,$param,$value,$operator="=");
	} 
	echo json_encode($time); 
}

?>