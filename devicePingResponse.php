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
$ipAddress=mysqli_real_escape_string($mysqli, $_REQUEST['ip']);
$mainServerIp=mysqli_real_escape_string($mysqli, $_REQUEST['mainServerIp']);
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
	echo json_encode($time); 
}
?>