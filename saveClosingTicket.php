<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login/index.php');
    exit;
} else {
    $user = $_SESSION['username'];
    $role = $_SESSION["role"];
}
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");
$convertdate = date("d-m-Y", strtotime($today));
$hourMin = date('H:i');
$debug = 0;
$msj="updated";
$ticketData = mysqli_real_escape_string($mysqli, $_REQUEST['ticketData']);
$row=json_decode(stripslashes($ticketData),true);
$afiliado_result=json_decode(stripslashes($ticketData),true);
$idTicket=$row['id'];
$id_cliente=$row['idCliente'];
$cliente=$afiliado_result['cliente'];
$apellido=$afiliado_result['apellido'];
$telefono=$afiliado_result['telefono'];
$direccion=$afiliado_result['direccion'];
$ciudad=$afiliado_result['ciudad'];
$departamento=$afiliado_result['departamento'];
$email=$afiliado_result['email'];
$ip=$afiliado_result['ip'];
$router=$afiliado_result['router'];
$mac_address_router=$afiliado_result['macRouter'];
$mac_address_antena=$afiliado_result['macAntena'];
$inyector_poe=$afiliado_result['inyectorPoe']; 
$apuntamiento=$afiliado_result['apuntamiento'];
$acceso_remoto=$afiliado_result['accesoRemoto']; 
$tipo_antena=$afiliado_result['tipoAntena']; 
$tipo_instalacion=$afiliado_result['tipoInstalacion']; 
$backup_telefono=$row['backupTelefono'];
$backup_email=$row['backupEmail']; 
$backup_ipaddress=$row['bakupIpAdress'];
$backup_antena=$row['backupAntena'];
$backup_velocidad=$row['backupVelocidad'];
$backup_precio_plan=$row['backupPrecioPlan'];
$backup_router=$row['backupRouter'];
$backup_acceso_remoto=$row['backupAccesoRemoto'];
$backup_tipo_instalacion=$row['backupTipoInstalacion'];
$backup_direccion=$row['backupDireccion'];
$backup_ciudad=$row['backupCiudad'];
$telefono_contacto=$row['telefonoContacto']; 
$solicitud_cliente=$row['solicitudCliente']; 
$sugerencia_solucion=$row['sugerenciaSolucion']; 
$fecha_creacion_ticket=$row['fechaCreacionTicket'];
$fecha_sugerida=$row['fechaSugerida']; 
$hora_sugerida=$row['horaSugerida']; 
$hora=$row['hora']; 
$administrador=$row['administrador']; 
$solucion=$row['solucion']; 
$recomendaciones=$row['recomendaciones']; 
$status=strtoupper($row['status']);
$precio_soporte=$row['precioSoporte']; 
$precio_soporte_descripcion=$row['precioSoporteDescripcion']; 
$tecnico=$user;
$tipo_soporte=$row['tipoSoporte']; 
$evidencia_fotografica1=$row['evidenciaFotografica1'];
$evidencia_fotografica2=$row['evidenciaFotografica2'];
if($status=="CERRADO") $fecha_cierre_ticket=$today; 
$sql1="UPDATE `redesagi_facturacion`.`afiliados` SET `cliente` = '$cliente', `apellido` = '$apellido', `telefono` = '$telefono', `direccion` = '$direccion', `ciudad` = '$ciudad', `departamento` = '$departamento', `mail` = '$email', `ip` = '$ip', `router` = '$router', `mac-address-router` = '$mac_address_router', `mac-address-antena` = '$mac_address_antena', `inyector-poe` = '$inyector_poe', `apuntamiento` = '$apuntamiento', `acceso-remoto` = '$acceso_remoto', `antena` = '$tipo_antena', `tipo-instalacion` = '$tipo_instalacion'  WHERE `afiliados`.`id` = '$id_cliente'";
if(!$mysqli->query($sql1)){
    $msj= 'error updating afiliados!';
}
$sql2="UPDATE `redesagi_facturacion`.`ticket` SET `telefono-contacto`='$telefono_contacto',`solucion`='$solucion',`recomendaciones`='$recomendaciones',`status`='$status',`precio-soporte`='$precio_soporte',`precio-soporte-descripcion`='$precio_soporte_descripcion',`tecnico`='$tecnico',`tipo-soporte`='$tipo_soporte',`evidencia-fotografica1`='$evidencia_fotografica1',`evidencia-fotografica2`='$evidencia_fotografica2',`fecha-cierre-ticket`='$fecha_cierre_ticket' WHERE `ticket`.`id`='$idTicket' ";
if(!$mysqli->query($sql2)){
    $msj= 'error updating tickets!';
}
echo json_encode($msj);
?>