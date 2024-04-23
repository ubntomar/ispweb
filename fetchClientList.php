<?php
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: login/index.php');
		exit;
		}
else    {
		$user=$_SESSION['username'];
		$empresa = $_SESSION['empresa'];
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
$queryPart="";
$searchClientContent = mysqli_real_escape_string($mysqli, $_REQUEST['searchClientContent']);

$words = explode(" ", $searchClientContent);
$lastName  = $words[1];



$searchWords = explode(" ", $searchClientContent);
$searchQuery = "";

foreach ($searchWords as $word) {
    if (!empty($searchQuery)) {
        $searchQuery .= " OR ";
    }
    $word = "%$word%";
    $searchQuery .= "(`cliente` LIKE '$word') OR (`apellido` LIKE '$word')";
}

$searchQuery = "AND ( $searchQuery OR (`ip` LIKE '%$searchClientContent%') )";




$queryPart=$searchQuery;
$sqlSearch="SELECT * FROM `redesagi_facturacion`.`afiliados` WHERE `id-empresa`=$empresa  AND  `eliminar`=0 AND `activo`=1 $queryPart  limit 20"; 
if ($result = $mysqli->query($sqlSearch)) {
	$num=$result->num_rows;
	$counter=0;
	while($row = $result->fetch_assoc()) {
		$counter+=1;
		$id=$row['id'];
		$cliente=strtoupper($row["cliente"]);
		$apellido=strtoupper($row["apellido"]);
		$ip=$row["ip"];
		$mail=$row["mail"];
		$direccion=$row["direccion"];
		$ciudad=$row["ciudad"];
		$telefono=$row["telefono"];
		$apuntamiento=$row["apuntamiento"];
		$wallet=$row["wallet-money"];
		$cedula=$row["cedula"];
		$planPrice=$row["pago"];
		$speed=$row["velocidad-plan"];
		$list[]=["id"=>"$id","cliente"=>"$cliente","apellido"=>"$apellido","ip"=>"$ip","fecha"=>"$today","email"=>"$mail","direccion"=>"$direccion","ciudad"=>"$ciudad","telefono"=>"$telefono","apuntamiento"=>"$apuntamiento","wallet"=>"$wallet","cedula"=>"$cedula","planPrice"=>"$planPrice","speed"=>"$speed"];
	}
}
$jsonList=json_encode($list);
echo $jsonList;



 







?>