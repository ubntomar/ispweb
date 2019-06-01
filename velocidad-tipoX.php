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
echo"arranca";
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));

$sql = "SELECT * FROM `afiliados` ORDER BY `afiliados`.`id` ASC ";
if ($result = $mysqli->query($sql)) {
	while ($row = $result->fetch_assoc()) {	
		$idafiliado=$row["id"];
		$valorf=$row["pago"];
		$vencidos=$row["mesenmora"];
		$fechap=$row["ultimopago"];
		$periodo="Diciembre";
		
		if($valorf<=45000){
			$velocidadPlan="Plan 2Mbps Bajada / 1Mbps Subida ";
			}
		if(($valorf>45000)&&($valorf<=60000)){
			$velocidadPlan="Plan 3Mbps Bajada / 1.5Mbps Subida ";
			}
		if(($valorf>60000)&&($valorf<=75000)){
			$velocidadPlan="Plan 4Mbps Bajada / 2Mbps Subida ";
			}
		if(($valorf>75000)&&($valorf<=90000)){
			$velocidadPlan="Plan 5Mbps Bajada / 2.5Mbps Subida ";
			}
		if(($valorf>90000)&&($valorf<=105000)){
			$velocidadPlan="Plan 6Mbps Bajada / 3.0Mbps Subida ";
			}	
		
		if(($valorf>120000)&&($valorf<=150000)){
			$velocidadPlan="Plan 8Mbps Bajada / 3.5Mbps Subida ";
			}					
		$tipoCliente="Residencial";
		
		$sqlup="UPDATE `redesagi_facturacion`.`afiliados` SET `velocidad-plan` = '$velocidadPlan', `tipo-cliente` = '$tipoCliente' WHERE `afiliados`.`id` = $idafiliado;";	
		echo "<br>
				$sqlup
			  <br>";
		$mysqli->query($sqlup);	
		}
			
    	$result->free();
	}

 echo"termina";

 ?>