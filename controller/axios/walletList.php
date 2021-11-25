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
require ("../../dateHuman.php");
require ("../../login/db.php");
require ("../../Client.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$clientObject=new Client($server, $db_user, $db_pwd, $db_name);
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$queryPart="";
$sqlSearch="SELECT * FROM `redesagi_facturacion`.`wallet` WHERE  1 ORDER BY `id` DESC limit 20 "; 
if ($result = $mysqli->query($sqlSearch)) {
	while($row = $result->fetch_assoc()) {
		$id=$row['id']; 
		$idClient=$row['id-client'];  
		$cliente=$clientObject->getClientItem($idClient,$item="cliente");
		$apellido=$clientObject->getClientItem($idClient,$item="apellido");
		$recarga=$row["value"];
		$date=$row["date"];
		$cajero=$clientObject->getClientItem($idClient,$item="cajero");
		$nuevoSaldo=$clientObject->getClientItem($idClient,$item="wallet-money");
		$list[]=["id"=>"$id","cliente"=>"$cliente","apellido"=>"$apellido","recarga"=>"$recarga","date"=>"$date","cajero"=>"$cajero","nuevoSaldo"=>"$nuevoSaldo"];
	}
}
$jsonList=json_encode($list);
echo $jsonList; 

?>