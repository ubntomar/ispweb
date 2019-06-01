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
		$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
		$monthn = date("n");
		$periodo=$mes[$monthn];
		if($vencidos==0){
			$multiplicador=1;
			
		}
		if($vencidos>0){
			$multiplicador=$vencidos+1;
			
		}
		$saldo=$valorf*$multiplicador;

		$sql1 = "INSERT INTO `redesagi_facturacion`.`factura` (`id-factura`, `id-afiliado`, `fecha-pago`, `iva`, `notas`, `descuento`, `valorf`, `valorp`, `saldo`, `cerrado`, `fecha-cierre`, `vencidos`, `periodo`) VALUES (NULL,'$idafiliado', '0000/00/00', '19', 'notas', '0', '$valorf', '0', '$saldo', '0', '0000/00/00', '$vencidos', '$periodo');";

		echo "<br>".$sql1."<br>";
		$mysqli->query($sql1);
		}
    	$result->free();
	}

 echo"termina";

 ?>