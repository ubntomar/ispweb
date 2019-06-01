<?php

echo"arranca";
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
													//28 02 2017 agrago and activo  31 05 2018 OJO revisar los stand by antiguos para pasarlos a 0
$sql = "SELECT * FROM `afiliados` WHERE `mesenmora` != -1 AND `activo`=1 AND `suspender`!=1 AND `standby`!=1 ORDER BY `id` ASC ";
if ($result = $mysqli->query($sql)) {
	while ($row = $result->fetch_assoc()) {
		$makeFact=1;	
		$idafiliado=$row["id"];
		$valorf=$row["pago"];
		$vencidos=$row["mesenmora"];// se utiliza solo la primera ves q se cargan los datos de excel al programa---se puede omitir en el insert
		$fechap=$row["ultimopago"];
		$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
		$monthn = date("n");//******************IMPORTANTE****Y REVISAR LOS STAND BY*****************************************---------
		$periodo=$mes[5];// hoy 01 de Mayo  de 2019 aquí pongo el mes al que le voy a crear la tanda de facturas a todos los afiliados.
		if($vencidos==-1){//********************************************//*********************************-----
			$makeFact=0;
			
		}
		
		///31 enro de 2018 ultimo id factura =202  id afiliado =  202
		$saldo=$valorf;
		$sql1 = "INSERT INTO `redesagi_facturacion`.`factura` (`id-factura`, `id-afiliado`, `fecha-pago`, `iva`, `notas`, `descuento`, `valorf`, `valorp`, `saldo`, `cerrado`, `fecha-cierre`, `vencidos`, `periodo`) VALUES (NULL,'$idafiliado', '0000/00/00', '19', 'notas', '0', '$valorf', '0', '$saldo', '0', '0000/00/00', '-10', '$periodo');";
		echo "<br>".$sql1."<br>";
		///////////$mysqli->query($sql1);
		
		}
    	$result->free();
	}

 echo "termina";

 ?>