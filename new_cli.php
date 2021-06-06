<?php
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: login/index.php');
		exit;
		}
else    {
		$user=$_SESSION['username'];
		$administrador=$user;
		$role = $_SESSION["role"];
		$empresa = $_SESSION['empresa'];
		}
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$usuario=$_SESSION['username'];
$blankDate="0000/00/00";
$debug=false;
if($_POST['valorPlan']||$debug){
	$valorPlan= mysqli_real_escape_string($mysqli, $_REQUEST['valorPlan']);
	$name= mysqli_real_escape_string($mysqli, $_REQUEST['name']);
	$lastName= mysqli_real_escape_string($mysqli, $_REQUEST['lastName']);
	$cedula= mysqli_real_escape_string($mysqli, $_REQUEST['cedula']);
	$address= mysqli_real_escape_string($mysqli, $_REQUEST['address']);
	$city= mysqli_real_escape_string($mysqli, $_REQUEST['ciudad']);
	$pieces = explode("-", $city);
	$idClientArea=$pieces[0];
	$ciudad=$pieces[1];
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
	$sql="INSERT INTO `redesagi_facturacion`.`afiliados` (`id`, `cliente`, `apellido`, `cedula`, `direccion`, `ciudad`, `departamento`, `mail`, `telefono`, `pago`, `corte`, `mesenmora`,  `orden_reparto`, `velocidad-plan`, `tipo-cliente`, `registration-date`, `source`, `activo`, `ip`, `standby`, `valorAfiliacion`,`stdbymcount`,`cajero`,`id_client_area`,`id-empresa`) VALUES
														 (NULL, '$name', '$lastName', '$cedula', '$address', '$ciudad', '$departamento', '$email', '$phone', '$valorPlan','$corte', '$nextPay',  '999', '$velocidadPlan', '$plan', '$today', 'ispdev', '1', '$ipAddress', '$standby', '$AfiliacionItemValue', '$standby', '$usuario', '$idClientArea', '$empresa');";
	if ($mysqli->query($sql) === TRUE) {
			$last_id = $mysqli->insert_id;
			$idafiliado=$last_id;

			$sql="INSERT INTO `redesagi_facturacion`.`ticket` (`id-cliente`,`backup-telefono`,`backup-email`,`backup-ipaddress`,`backup-antena`,`backup-velocidad`,`backup-precio-plan`,`backup-router`,`backup-acceso-remoto`,`backup-tipo-instalacion`,`backup-direccion`,`backup-ciudad`,`telefono-contacto`,`solicitud-cliente`,`sugerencia-solucion`,`fecha-creacion-ticket`,`hora-sugerida`,`hora`,`administrador`,`solucion`,`recomendaciones`,`status`,`precio-soporte`) VALUES ('$idafiliado','$backup_telefono','$backup_email','$backup_ipaddress','$backup_antena','$backup_velocidad','$backup_precio_plan','$backup_router','$backup_acceso_remoto','$backup_tipo_instalacion','$backup_direccion','$backup_ciudad','$telefono_contacto','Solicitud instalaciòn servicio de Internet Banda Ancha','$sugerencia_solucion','$today','$hora_sugerida','$hora','$administrador','$solucion','$recomendacion','ABIERTO','$precio_soporte')";
			if(!$mysqli->query($sql)){
				$msj= 'error inserting tickets!';
			}
			$idTicket=$mysqli->insert_id;
			$cliente=$name." ".$lastName;
			$solicitud_cliente="Solicitud instalaciòn servicio de Internet Banda Ancha";
			$tecnico="ASIGNACION PENDIENTE";
			$solucion="";
			sendEmail($idTicket,$cliente,$address,$phone,$today,$solicitud_cliente,$solucion,$recomendaciones,$tecnico,$email,$email_user,$email_password);
			$sqlping = "INSERT INTO `redesagi_facturacion`.`liveinfo` (`id`, `id-cliente`, `fecha`, `descripcion`) VALUES (NULL,'$last_id','$today', 'Batch of invoices')  ";
			if($mysqli->query($sqlping)==true)
				echo "";
			else echo "Error-liveinfo";				
			if($mergeItems==1){//First internet service bill is already payed and the other one  Afiliación bill is already payed. b   valorAdicionalServicio    leftDays = days - daySelected;  new_cli  AfiliacionItemValue  $("#AfiliacionItemValue").val(0);   si Valor adicional de Servicio==0 && Standar service flag==0 ==>Total Prorrateo definevalor de servicio
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
					$sql1 = "INSERT INTO `redesagi_facturacion`.`factura` (`id-factura`, `id-afiliado`, `fecha-pago`, `iva`, `notas`, `descuento`, `valorf`, `valorp`, `saldo`, `cerrado`, `fecha-cierre`, `vencidos`, `periodo`) VALUES 
																		  (NULL,'$idafiliado', '$fechaPago', '$iva', '$notas', '$descuento', '$valorf', '$valorp', '$saldo', '$cerrado', '$fechaCierre', '$vencidos', '$periodo');";
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
					$sqlins="INSERT INTO `redesagi_facturacion`.`transacciones` (`idtransaccion`, `valor-recibido`, `valor-a-pagar`, `cambio`, `id-cliente`, `fecha`,  `hora`, `cajero`, `descripcion`) VALUES 
																				(NULL, '$valorr', '$valorap', '$cambio', '$id_cliente', '$today',  '$hora', '$cajero', '$descripcion' )";
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
					$sqlins="INSERT INTO `redesagi_facturacion`.`transacciones` (`idtransaccion`, `valor-recibido`, `valor-a-pagar`, `cambio`, `id-cliente`, `fecha`,  `hora`, `cajero`, `descripcion`) VALUES 
																					(NULL, '$valorr', '$valorap', '$cambio', '$id_cliente', '$today', '$hora', '$cajero', '$descripcion' )";
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
				$sqlins="INSERT INTO `redesagi_facturacion`.`transacciones` (`idtransaccion`, `valor-recibido`, `valor-a-pagar`, `cambio`, `id-cliente`, `fecha`,  `hora`, `cajero`, `descripcion`) VALUES 
																				(NULL, '$valorr', '$valorap', '$cambio', '$id_cliente', '$today', '$hora', '$cajero', '$descripcion' )";
				if($mysqli->query($sqlins)==true)
					echo "";
				else echo "Error-Transacción Issues";		
				
			}
			if($mergeItems==0){//First internet service bill is NOT !!! payed and the other one  Afiliación bill is already payed.
				//$idafiliado=$mysqli->insert_id;
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
				$sqlins="INSERT INTO `redesagi_facturacion`.`transacciones` (`idtransaccion`, `valor-recibido`, `valor-a-pagar`, `cambio`, `id-cliente`, `fecha`,  `hora`, `cajero`, `descripcion`) VALUES 
																				(NULL, '$valorr', '$valorap', '$cambio', '$id_cliente', '$today', '$hora', '$cajero', '$descripcion' )";
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
function sendEmail($idTicket,$cliente,$direccio,$telefon,$fecha_creacion_ticket,$solicitud_cliente,$solucio,$recomendacione,$tecnic,$emai,$email_user,$email_password){
	$numeroDeCaso="2541".$idTicket;
	$titular=strtoupper($cliente);
	$direccion=strtoupper($direccio);
	$telefono=$telefon;
	$fechaApertura=$fecha_creacion_ticket;
	$status="CERRADO";
	$solicitud=ucfirst($solicitud_cliente);
	$solucion=ucfirst($solucio);
	$recomendaciones=ucfirst($recomendacione);
	$tecnico=strtoupper($tecnic);
	$email=$emai;
	$today = date("d-m-Y");
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->SMTPDebug = 0;
	$mail->Host = 'smtp.flockmail.com';
	$mail->Port = 587;
	$mail->SMTPAuth = true;
	$mail->Username = $email_user;
	$mail->Password = $email_password;
	$mail->isHTML(true);
	$mail->setFrom('cliente@ispexperts.com', 'Ticket Internet -INSTALACION'); 
	$mail->addReplyTo('cliente@ispexperts.com', 'Dpto de Soporte');
	$mail->addAddress($email, 'Cliente Residencial');
	$mail->Subject = 'AG INGENIERIA- Solicitud #'.$numeroDeCaso;
	$style_table="
			style='
				border-radius:3px;
				padding:5px;
				'
			";
	$style_thead="
			style='
				color:#fff;
				background-color:#111d5e;
				padding:10px;
				text-align-center;
				border:0px;
				'
			";
	$style_th="
			style='
				padding:10px;
				'
			";
	$style_title="
			style='
			background-color:#0275d8;
			padding:6px;
			margin:3px;
			color:#fff;
			'
			";  
	$style_tbody="
			style='
			background-color:#fff;
			color:#000;
			'
			";              
	$style_tfoot="
			style='
			margin-top:20px;
			'
			";              
	$style_td="
			style='
			padding:10px;
			'
			";              
	$style_div="
			style='
			padding:10px;
			'
			";              
	$style_div_thead_td="
			style='
			padding:10px;
			text-align:left;
			'
			";              
	$style_tr="
			style='
			padding:10px;
			'
			";              
	$style_tr_tfoot="
			style='
			padding:10px;
			border:0px;
			'
			";  
	$mailContent = "
	<table border='1' $style_table>
		<thead $style_thead>
			<th  colspan='2' $style_th>
				<h3>Bienvenido a AG INGENIERIA WIST, Gracias por elegirnos como tu proveedor de servicio de Internet Banda Ancha</h3>
				<p>Ahora el siguiente paso es ponernos en contacto contigo para definir dìa exacto y hora exacta de instalaciòn, te estaremos informando!!</p>
				<div $style_div_thead_td>
					<p><strong>Titular: </strong>$titular</p>
					<p><strong>Direcciòn: </strong>$direccion</p>
					<p><strong>Telèfono: </strong>$telefono</p>
					<p><strong>Fecha apertura de caso: </strong>$fechaApertura</p>
					<p><span>ESTADO DE TICKET: </span><strong>ABIERTO-INSTALACION PENDIENTE</strong></p>
				</div>    
			</th> 
		</thead>
		<tbody $style_tbody>
			<tr $style_tr>
				<td $style_td>
					<strong $style_title>SOLICITUD</strong>
					<div $style_div>$solicitud</div>
				</td>
				<td $style_td>
					<strong $style_title>SOLUCION</strong>
					<div $style_div>$solucion</div>
				</td>
			</tr>
			<tr $style_tr>
				<td $style_td>
					<strong $style_title>RECOMENDACIONES</strong>
					<div $style_div>$recomendaciones</div>
				</td>
				<td $style_td>
					<strong $style_title>TECNICO</strong>
					<div $style_div>$tecnico</div>
				</td>
			</tr>
			
		</tbody>
		<tfoot>
			<tr $style_tr_tfoot>
				<td $style_td colspan='2'>
					<div $style_div>
						<p>
							Dirección de Servicio al Cliente Bogotá D.C,Colombia
						</p>
						<p>
							Tel.3134308121-3147654655
						</p>
						<p>
							<a href='#'>E-mail:cliente@ispexperts.com</a>
						</p>
					</div>
				</td>
			</tr>
		</tfoot>
	</table>";
	$mail->Body = $mailContent;
	try {
		$mail->send();
		return true;
	} catch (Exception $e) {
		//echo "Mailer Error: " . $mail->ErrorInfo;
		return false;
	}


}	
 ?>