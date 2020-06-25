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
$y=1;
//$ipAddress="192.168.21.110";
$device= new PingTime($ipAddress);
$time= array("time"=>$device->time());
echo json_encode($time);
?>