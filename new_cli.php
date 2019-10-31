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
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$usuario=$_SESSION['username'];
$blankDate="0000/00/00";
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
	$corteMes= mysqli_real_escape_string($mysqli, $_REQUEST['corteMes']);
	$plan= mysqli_real_escape_string($mysqli, $_REQUEST['plan']);
	$velocidadPlan= mysqli_real_escape_string($mysqli, $_REQUEST['velocidadPlan']);
	$generarFactura= mysqli_real_escape_string($mysqli, $_REQUEST['generarFactura']);//It always will be set to 1
	$ipAddress= mysqli_real_escape_string($mysqli, $_REQUEST['ipAddress']);
	$mergeItems= mysqli_real_escape_string($mysqli, $_REQUEST['mergeItems']);
	$stb=0;
	$recibo= mysqli_real_escape_string($mysqli, $_REQUEST['recibo']);
	$valorAfiliacion= mysqli_real_escape_string($mysqli, $_REQUEST['valorAfiliacion']);
	$standby= mysqli_real_escape_string($mysqli, $_REQUEST['standby']);// (el motor mensual debe restarle 1 unidad) standby value depende de el día del mes en curso
	$standarServiceFlag= mysqli_real_escape_string($mysqli, $_REQUEST['standarServiceFlag']);
	$AfiliacionItemValue= mysqli_real_escape_string($mysqli, $_REQUEST['AfiliacionItemValue']);
	$valorProrrateo= mysqli_real_escape_string($mysqli, $_REQUEST['valorProrrateo']);
	$valorApagar= mysqli_real_escape_string($mysqli, $_REQUEST['valorApagar']);
	$iva= mysqli_real_escape_string($mysqli, $_REQUEST['iva']);
	$valorAdicionalServicio= mysqli_real_escape_string($mysqli, $_REQUEST['valorAdicionalServicio']);
	$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
	$monthn = date("n");
	$sql="INSERT INTO `redesagi_facturacion`.`afiliados` (`id`, `cliente`, `apellido`, `cedula`, `direccion`, `ciudad`, `departamento`, `mail`, `telefono`, `pago`, `ultimopago`, `pagoactual`, `corte`, `mesenmora`, `recibo_generado`, `orden_reparto`, `velocidad-plan`, `tipo-cliente`, `registration-date`, `source`, `activo`, `ip`, `standby`, `valorAfiliacion`,`stdbymcount`) VALUES (NULL, '$name', '$lastName', '$cedula', '$address', '$ciudad', '$departamento', '$email', '$phone', '$valorPlan', '', '', '$corte', '$nextPay', '', '999', '$velocidadPlan', '$plan', '$today', 'ispdev', '1', '$ipAddress', '$standby', '$AfiliacionItemValue', '$standby');";
	if ($mysqli->query($sql) === TRUE) {
			$last_id = $mysqli->insert_id;			
			if($mergeItems==1){//First internet service bill is already payed and the other one  Afiliación bill is already payed. b   valorAdicionalServicio    leftDays = days - daySelected;  new_cli  AfiliacionItemValue  $("#AfiliacionItemValue").val(0);   si Valor adicional de Servicio==0 && Standar service flag==0 ==>Total Prorrateo definevalor de servicio
				$idafiliado=$mysqli->insert_id;
				$fechaPago=$today;
				$descuento=0;				
				$fechaCierre='0000/00/00';
				$vencidos=0;
				if($standarServiceFlag==1){
					//bill #1 4500 saldo 0 Estandar					
					$periodo=$mes[$corteMes];
					$notas="Servcio-1er Mes";
					$valorf=$valorPlan;
					$valorp=$valorPlan;				
					$saldo=0;
					$cerrado=1;	
					$sql1 = "INSERT INTO `redesagi_facturacion`.`factura` (`id-factura`, `id-afiliado`, `fecha-pago`, `iva`, `notas`, `descuento`, `valorf`, `valorp`, `saldo`, `cerrado`, `fecha-cierre`, `vencidos`, `periodo`) VALUES (NULL,'$idafiliado', '$fechaPago', '$iva', '$notas', '$descuento', '$valorf', '$valorp', '$saldo', '$cerrado', '$fechaCierre', '$vencidos', '$periodo');";
					if($mysqli->query($sql1)==true)
						echo "";
					else echo "Error-Factura de Servicio Issues";	
				}
				if($valorProrrateo !="" && $valorProrrateo !=0 ){
					//bill #1 37589 saldo 0 Basado en prorrateo
					$periodo="Prorrateo-".$mes[$corteMes];
					$notas="Servcio-1er Mes";
					$valorf=$valorProrrateo;
					$valorp=$valorProrrateo;				
					$saldo=0;
					$cerrado=1;	
					$sql1 = "INSERT INTO `redesagi_facturacion`.`factura` (`id-factura`, `id-afiliado`, `fecha-pago`, `iva`, `notas`, `descuento`, `valorf`, `valorp`, `saldo`, `cerrado`, `fecha-cierre`, `vencidos`, `periodo`) VALUES (NULL,'$idafiliado', '$fechaPago', '$iva', '$notas', '$descuento', '$valorf', '$valorp', '$saldo', '$cerrado', '$fechaCierre', '$vencidos', '$periodo');";
					if($mysqli->query($sql1)==true)
						echo "";
					else echo "Error-Factura de Servicio Issues";	
				}

				//bill #2 180  saldo 0
				$notas="Afiliacion";
				$periodo="Afiliación";
				$valorf=$AfiliacionItemValue;
				$saldo=0;
				$cerrado=1;	
				$sql1 = "INSERT INTO `redesagi_facturacion`.`factura` (`id-factura`, `id-afiliado`, `fecha-pago`, `iva`, `notas`, `descuento`, `valorf`, `valorp`, `saldo`, `cerrado`, `fecha-cierre`, `vencidos`, `periodo`) VALUES (NULL,'$idafiliado', '$fechaPago', '$iva', '$notas', '$descuento', '$valorf', '$valorp', '$saldo', '$cerrado', '$fechaCierre', '$vencidos', '$periodo');";
				if($mysqli->query($sql1)==true)
					echo "";
				else echo "Error-Factura de Servicio Issues";
				if($standarServiceFlag==1){
					//Transaccion 1 -> Monthly service planStandar
					$valorr=$valorPlan;
					$valorap=$valorPlan;	
					$cambio=0;
					$id_cliente=$last_id;
					$fecha=$today;
					$aprobado="";
					$hora=$hourMin;
					$cajero=$usuario;
					$descripcion=" Servicio-1er mes";
					$sqlins="INSERT INTO `redesagi_facturacion`.`transacciones` (`idtransaccion`, `valor-recibido`, `valor-a-pagar`, `cambio`, `id-cliente`, `fecha`, `aprobado`, `hora`, `cajero`, `descripcion`) VALUES (NULL, '$valorr', '$valorap', '$cambio', '$id_cliente', '$today', '', '$hora', '$cajero', '$descripcion' )";
					if($mysqli->query($sqlins)==true)
						echo "";
					else echo "Error-Transacción Issues";
				}
				if($valorProrrateo !="" && $valorProrrateo !=0 ){
					//Transaccion 1 -> Monthly service prorrateo
					$valorr=$valorProrrateo;
					$valorap=$valorProrrateo;	
					$cambio=0;
					$id_cliente=$last_id;
					$fecha=$today;
					$aprobado="";
					$hora=$hourMin;
					$cajero=$usuario;
					$descripcion=" Servicio-1er mes";
					$sqlins="INSERT INTO `redesagi_facturacion`.`transacciones` (`idtransaccion`, `valor-recibido`, `valor-a-pagar`, `cambio`, `id-cliente`, `fecha`, `aprobado`, `hora`, `cajero`, `descripcion`) VALUES (NULL, '$valorr', '$valorap', '$cambio', '$id_cliente', '$today', '', '$hora', '$cajero', '$descripcion' )";
					if($mysqli->query($sqlins)==true)
						echo "";
					else echo "Error-Transacción Issues";
				}
				//Transaccion 2 -> Service afiliation
				$valorr=$AfiliacionItemValue;
				$valorap=$AfiliacionItemValue;	
				$cambio=0;
				$id_cliente=$last_id;
				$fecha=$today;
				$aprobado="";
				$hora=$hourMin;
				$cajero=$usuario;
				$descripcion=" Afiliación";	
				$sqlins="INSERT INTO `redesagi_facturacion`.`transacciones` (`idtransaccion`, `valor-recibido`, `valor-a-pagar`, `cambio`, `id-cliente`, `fecha`, `aprobado`, `hora`, `cajero`, `descripcion`) VALUES (NULL, '$valorr', '$valorap', '$cambio', '$id_cliente', '$today', '', '$hora', '$cajero', '$descripcion' )";
				if($mysqli->query($sqlins)==true)
					echo "";
				else echo "Error-Transacción Issues";		
				
			}
			if($mergeItems==0){//First internet service bill is NOT !!! payed and the other one  Afiliación bill is already payed.
				$idafiliado=$mysqli->insert_id;
				$fechaPago='0000/00/00';
				$iva=19;
				$notas="SysdevNota";
				$descuento=0;				
				$valorp=0;					
				$vencidos=0;					
				if($standarServiceFlag==1){
					//bill #1 4500 saldo 45000
					$periodo=$mes[$corteMes];
					$notas="Servicio-1er Mes";
					$valorf=$valorPlan;
					$saldo=$valorPlan;
					$cerrado=0;	
					$fechaCierre=$blankDate;
					$sql1 = "INSERT INTO `redesagi_facturacion`.`factura` (`id-factura`, `id-afiliado`, `fecha-pago`, `iva`, `notas`, `descuento`, `valorf`, `valorp`, `saldo`, `cerrado`, `fecha-cierre`, `vencidos`, `periodo`) VALUES (NULL,'$idafiliado', '$fechaPago', '$iva', '$notas', '$descuento', '$valorf', '$valorp', '$saldo', '$cerrado', '$fechaCierre', '$vencidos', '$periodo');";
					if($mysqli->query($sql1)==true)
						echo "";
					else echo "Error-Factura de Servicio Issues";	
				}
				if($valorAdicionalServicio !="" && $valorAdicionalServicio !=0  ){
					//bill #1 2589 saldo 2589
					$periodo="Prorrateo-".$mes[$corteMes];
					$notas="Servicio-Prorrateo 1er Mes";
					$valorf=$valorAdicionalServicio;
					$saldo=$valorAdicionalServicio;
					$cerrado=0;	
					$fechaCierre=$blankDate;
					$sql1 = "INSERT INTO `redesagi_facturacion`.`factura` (`id-factura`, `id-afiliado`, `fecha-pago`, `iva`, `notas`, `descuento`, `valorf`, `valorp`, `saldo`, `cerrado`, `fecha-cierre`, `vencidos`, `periodo`) VALUES (NULL,'$idafiliado', '$fechaPago', '$iva', '$notas', '$descuento', '$valorf', '$valorp', '$saldo', '$cerrado', '$fechaCierre', '$vencidos', '$periodo');";
					if($mysqli->query($sql1)==true)
						echo "";
					else echo "Error-Factura de Servicio Issues";	
				}
				//bill #2 180  saldo 0
				$notas="Afiliacion";
				$periodo="Afiliación";
				$valorf=$AfiliacionItemValue;
				$saldo=0;
				$cerrado=1;	
				$fechaCierre=$today;
				$sql1 = "INSERT INTO `redesagi_facturacion`.`factura` (`id-factura`, `id-afiliado`, `fecha-pago`, `iva`, `notas`, `descuento`, `valorf`, `valorp`, `saldo`, `cerrado`, `fecha-cierre`, `vencidos`, `periodo`) VALUES (NULL,'$idafiliado', '$fechaPago', '$iva', '$notas', '$descuento', '$valorf', '$valorp', '$saldo', '$cerrado', '$fechaCierre', '$vencidos', '$periodo');";
				if($mysqli->query($sql1)==true)
					echo "";
				else echo "Error-Factura de Servicio Issues";				
				//Transaccion 2 -> Service afiliation
				$valorr=$AfiliacionItemValue ;
				$valorap=$AfiliacionItemValue ;	
				$cambio=0;
				$id_cliente=$last_id;
				$fecha=$today;
				$aprobado="";
				$hora=$hourMin;
				$cajero=$usuario;
				$descripcion=" Afiliación-servicio pdte";	
				$sqlins="INSERT INTO `redesagi_facturacion`.`transacciones` (`idtransaccion`, `valor-recibido`, `valor-a-pagar`, `cambio`, `id-cliente`, `fecha`, `aprobado`, `hora`, `cajero`, `descripcion`) VALUES (NULL, '$valorr', '$valorap', '$cambio', '$id_cliente', '$today', '', '$hora', '$cajero', '$descripcion' )";
				if($mysqli->query($sqlins)==true)
					echo "";
				else echo "Error-Transacción Issues";
				
				
			}
		    echo "$last_id:Usuario agregado con exito!!";

		} 
		else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}
			
}	
 
 ?>
