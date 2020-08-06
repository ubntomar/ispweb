<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    // header('Location: login/index.php');
    // exit;
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
$msj="saved";
$ticketData = mysqli_real_escape_string($mysqli, $_REQUEST['ticketData']);
$data=json_decode(stripslashes($ticketData),true);
$clienteId=$data["id"]; 
//Backup afiliados fields
$sql="SELECT * FROM `redesagi_facturacion`.`afiliados` WHERE `id` = '$clienteId' ";
if ($result = $mysqli->query($sql)) {
    while($row = $result->fetch_assoc()) {
        $backup_telefono=$row["telefono"];
        $backup_email=$row["mail"];
        $backup_ipaddress=$row["ip"];
        $backup_antena=$row["antena"];
        $backup_velocidad=$row["velocidad-plan"];
        $backup_precio_plan=$row["pago"];
        $backup_router=$row["router"];
        $backup_acceso_remoto=$row["acceso-remoto"];
        $backup_tipo_instalacion=$row["tipo-instalacion"];
        $backup_direccion=$row["direccion"];
        $backup_ciudad=$row["ciudad"];
    }
}
//Update afiliados table 
$cliente=$data["cliente"];
$apellido=$data["apellido"];
$telefono=$data["telefono"];
$direccion=$data["direccion"];
$ciudad=$data["ciudad"];
$email=$data["email"];
$ip=$data["ip"];
$sql="UPDATE `redesagi_facturacion`.`afiliados` SET `cliente` = '$cliente', `apellido` = '$apellido', `telefono` = '$telefono', `direccion` = '$direccion', `ciudad` = '$ciudad', `mail` = '$email', `ip` = '$ip'  WHERE `afiliados`.`id` = '$clienteId'";
if(!$mysqli->query($sql)){
    $msj= 'error updating afiliados!';
}
//Info to inser new ticket
$telefono_contacto=$data["telefonoContacto"];
$solicitud_cliente=$data["solicitudCliente"];
$sugerencia_solucion=$data["sugerenciaSolucion"];
$fecha_creacion_ticket=$today;
$fecha_sugerida=$data["fechaSugerida"];
$hora_sugerida=$data["horaSugerida"];
$hora=$hourMin;
$administrador=$user;
$solucion= "";
$recomendacion = "";
$status="ABIERTO";   
$precio_soporte=$data["precioSoporte"];
$sql="INSERT INTO `redesagi_facturacion`.`ticket` (`id-cliente`,`backup-telefono`,`backup-email`,`backup-ipaddress`,`backup-antena`,`backup-velocidad`,`backup-precio-plan`,`backup-router`,`backup-acceso-remoto`,`backup-tipo-instalacion`,`backup-direccion`,`backup-ciudad`,`telefono-contacto`,`solicitud-cliente`,`sugerencia-solucion`,`fecha-creacion-ticket`,`fecha-sugerida`,`hora-sugerida`,`hora`,`administrador`,`solucion`,`recomendaciones`,`status`,`precio-soporte`) VALUES ('$clienteId','$backup_telefono','$backup_email','$backup_ipaddress','$backup_antena','$backup_velocidad','$backup_precio_plan','$backup_router','$backup_acceso_remoto','$backup_tipo_instalacion','$backup_direccion','$backup_ciudad','$telefono_contacto','$solicitud_cliente','$sugerencia_solucion','$fecha_creacion_ticket','$fecha_sugerida','$hora_sugerida','$hora','$administrador','$solucion','$recomendacion','$status','$precio_soporte')";
if(!$mysqli->query($sql)){
    $msj= 'error inserting tickets!';
}
echo json_encode($msj);
?>