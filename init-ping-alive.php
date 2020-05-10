<?php

echo"arranca okg";
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
$monthn = date("n");//******************IMPORTANTE****Y REVISAR LOS STAND BY*****************************************----------
$periodo=$mes[3];// hoy 02  de Marzo de 2020 aquí pongo el mes al que le voy a crear la tanda de facturas a todos los afiliados.  AND `suspender`!=1
$cont=0;															
$sql = "SELECT * FROM `afiliados` WHERE   1 ORDER BY `id` ASC ";
if ($result = $mysqli->query($sql)) {
	while ($row = $result->fetch_assoc()) {			
		$idafiliado=$row["id"];
		$sql1 = "INSERT INTO `redesagi_facturacion`.`liveinfo` (`id`, `id-cliente`, `fecha`, `descripcion`) VALUES (NULL,'$idafiliado','$today', 'Batch of invoices')  ";
		//print "\n $sql1 \n";
		//$mysqli->query($sql1);
		
		}
    	$result->free();
	}

 echo "termina en :$cont"; 

 ?>