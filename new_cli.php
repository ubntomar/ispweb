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
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$usuario=$_SESSION['username'];
if($_POST['valorPlan']){
	$valorPlan= mysqli_real_escape_string($mysqli, $_REQUEST['valorPlan']);
	$name= mysqli_real_escape_string($mysqli, $_REQUEST['name']);
	$lastName= mysqli_real_escape_string($mysqli, $_REQUEST['lastName']);
	$cedula= mysqli_real_escape_string($mysqli, $_REQUEST['cedula']);
	$address= mysqli_real_escape_string($mysqli, $_REQUEST['address']);
	$ciudad= mysqli_real_escape_string($mysqli, $_REQUEST['ciudad']);
	$departamento= mysqli_real_escape_string($mysqli, $_REQUEST['departamento']);
	$phone= mysqli_real_escape_string($mysqli, $_REQUEST['phone']);
	$email= mysqli_real_escape_string($mysqli, $_REQUEST['email']);
	$corte= mysqli_real_escape_string($mysqli, $_REQUEST['corte']);
	$plan= mysqli_real_escape_string($mysqli, $_REQUEST['plan']);
	$velocidadPlan= mysqli_real_escape_string($mysqli, $_REQUEST['velocidadPlan']);
	$generarFactura= mysqli_real_escape_string($mysqli, $_REQUEST['generarFactura']);
	$ipAddress= mysqli_real_escape_string($mysqli, $_REQUEST['ipAddress']);
	$standby= mysqli_real_escape_string($mysqli, $_REQUEST['standby']);
	$recibo= mysqli_real_escape_string($mysqli, $_REQUEST['recibo']);
	$valorAfiliacion= mysqli_real_escape_string($mysqli, $_REQUEST['valorAfiliacion']);
	$sql="INSERT INTO `redesagi_facturacion`.`afiliados` (`id`, `cliente`, `apellido`, `cedula`, `direccion`, `ciudad`, `departamento`, `mail`, `telefono`, `pago`, `ultimopago`, `pagoactual`, `corte`, `mesenmora`, `recibo_generado`, `orden_reparto`, `velocidad-plan`, `tipo-cliente`, `registration-date`, `source`, `activo`, `ip`, `standby`, `valorAfiliacion`) VALUES (NULL, '$name', '$lastName', '$cedula', '$address', '$ciudad', '$departamento', '$email', '$phone', '$valorPlan', '', '', '$corte', '$nextPay', '', '999', '$velocidadPlan', '$plan', '$today', 'ispdev', '1', '$ipAddress', '$standby', '$valorAfiliacion');";								
	if ($mysqli->query($sql) === TRUE) {
			$last_id = $mysqli->insert_id;
			if($generarFactura==1){
				$idafiliado=$mysqli->insert_id;
				$fechaPago='0000/00/00';
				$iva=19;
				$notas="SysdevNota";
				$descuento=0;
				$valorf=$valorPlan;
				$valorp=0;
				$saldo=$valorf=$valorPlan;
				$cerrado=0;
				$fechaCierre='0000/00/00';
				$vencidos=0;
				$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
				$monthn = date("n");
				$periodo=$mes[$monthn];
				$sql1 = "INSERT INTO `redesagi_facturacion`.`factura` (`id-factura`, `id-afiliado`, `fecha-pago`, `iva`, `notas`, `descuento`, `valorf`, `valorp`, `saldo`, `cerrado`, `fecha-cierre`, `vencidos`, `periodo`) VALUES (NULL,'$idafiliado', '$fechaPago', '$iva', '$notas', '$descuento', '$valorf', '$valorp', '$saldo', '$cerrado', '$fechaCierre', '$vencidos', '$periodo');";
				$mysqli->query($sql1);
			}
		    echo "$last_id:Usuario agregado con exito!!";

		} 
		else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}
			
}	

 ?>
