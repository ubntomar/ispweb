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
$sqlSearch="SELECT * FROM `redesagi_facturacion`.`ticket` WHERE  `status` LIKE 'cerrado' ORDER BY `id` DESC  limit 40";  
if ($result = $mysqli->query($sqlSearch)) {
	$num=$result->num_rows;
	$counter=0;
	while($row = $result->fetch_assoc()) {
		$id_ticket=$row['id'];
		$id_cliente=$row['id-cliente'];
		$sql="SELECT * from  `redesagi_facturacion`.`afiliados` WHERE id= '$id_cliente'";
		$afiliado_result=$mysqli->query("$sql")->fetch_assoc();
		$cliente=$afiliado_result['cliente'];
		$apellido=$afiliado_result['apellido'];
		$telefono=$afiliado_result['telefono'];
		$direccion=$afiliado_result['direccion'];
		$ciudad=$afiliado_result['ciudad'];
		$departamento=$afiliado_result['departamento'];
		$email=$afiliado_result['mail'];
		$ip=$afiliado_result['ip'];
		$router=$afiliado_result['router'];
		$mac_address_router=$afiliado_result['mac-address-router'];//macRouter
		$mac_address_antena=$afiliado_result['mac-address-antena'];//macAntena
		$inyector_poe=$afiliado_result['inyector-poe'];//inyectorPoe
		$apuntamiento=$afiliado_result['apuntamiento'];
		$acceso_remoto=$afiliado_result['acceso-remoto'];//accesoRemoto
		$antena=$afiliado_result['antena'];//tipoAntena
		$tipo_instalacion=$afiliado_result['tipo-instalacion'];//tipoInstalacion
		$backup_telefono=$row['backup-telefono'];
		$backup_email=$row['backup-email']; 
		$backup_ipaddress=$row['backup-ipaddress'];
		$backup_antena=$row['backup-antena'];
		$backup_velocidad=$row['backup-velocidad'];
		$backup_precio_plan=$row['backup-precio-plan'];
		$backup_router=$row['backup-router'];
		$backup_acceso_remoto=$row['backup-acceso-remoto'];
		$backup_tipo_instalacion=$row['backup-tipo-instalacion'];
		$backup_direccion=$row['backup-direccion'];
		$backup_ciudad=$row['backup-ciudad'];
		$telefono_contacto=$row['telefono-contacto'];//telefonoContacto
		$solicitud_cliente=$row['solicitud-cliente'];//solicitudCliente
		$sugerencia_solucion=$row['sugerencia-solucion'];//sugerenciaSolucion
		$fecha_creacion_ticket=$row['fecha-creacion-ticket'];
		$fecha_sugerida=$row['fecha-sugerida'];//fechaSugerida
		$fecha_cierre_ticket=$row['fecha-cierre-ticket'];
		$hora_sugerida=$row['hora-sugerida'];//horaSugerida
		$hora=$row['hora'];//** */
		$administrador=$row['administrador'];//** */
		$solucion=$row['solucion'];/** */
		$recomendaciones=$row['recomendaciones'];/** */
		$status=strtoupper($row['status']);/** */
		$precio_soporte=$row['precio-soporte'];//precioSoporte /** */
		$precio_soporte_descripcion=$row['precio-soporte-descripcion'];//precioSoporteDescripcion /** */
		$tecnico=$row['tecnico'];
		$tipo_soporte=$row['tipo-soporte'];//tipoSoporte /** */
		$evidencia_fotografica1=$row['evidencia-fotografica1'];
		$evidencia_fotografica2=$row['evidencia-fotografica2'];
		$list []= array("id"=>"$id_ticket","idCliente"=>"$id_cliente","cliente"=>"$cliente","apellido"=>"$apellido","telefono"=>"$telefono","direccion"=>"$direccion","ciudad"=>"$ciudad","departamento"=>"$departamento","email"=>"$email","ip"=>"$ip","router"=>"$router","macRouter"=>"$mac_address_router","macAntena"=>"$mac_address_antena","inyectorPoe"=>"$inyector_poe","apuntamiento"=>"$apuntamiento","accesoRemoto"=>"$acceso_remoto","tipoAntena"=>"$antena","tipoInstalacion"=>"$tipo_instalacion","backupTelefono"=>"$backup_telefono","backupEmail"=>"$backup_email","bakupIpAdress"=>"$backup_ipaddress","backupAntena"=>"$backup_antena","backupVelocidad"=>"$backup_velocidad","backupPrecioPlan"=>"$backup_precio_plan","backupRouter"=>"$backup_router","backupAccesoRemoto"=>"$backup_acceso_remoto","backupTipoInstalacion"=>"$backup_tipo_instalacion","backupDireccion"=>"$backup_direccion","backupCiudad"=>"$backup_ciudad","telefonoContacto"=>"$telefono_contacto","solicitudCliente"=>"$solicitud_cliente","sugerenciaSolucion"=>"$sugerencia_solucion","fechaCreacionTicket"=>"$fecha_creacion_ticket","fechaSugerida"=>"$fecha_sugerida","horaSugerida"=>"$hora_sugerida","recomendaciones"=>"$recomendaciones","status"=>"$status","evidenciaFotografica1"=>"$evidencia_fotografica1","evidenciaFotografica2"=>"$evidencia_fotografica2","tecnico"=>"$tecnico","fechaCierreTicket"=>"$fecha_cierre_ticket","tipoSoporte"=>"$tipo_soporte","solucion"=>"$solucion");
	}
}
$jsonList=json_encode($list);
echo $jsonList;



 







?>