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
$list = array("cliente"=>"Primer cliente");
$jsonList=json_encode($list);
echo $jsonList;











?>