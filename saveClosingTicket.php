<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login/index.php');
    exit;
} else {
    $user = $_SESSION['name'];
    $role = $_SESSION["role"];
    $id_tecnico = $_SESSION["id"];
}
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';
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
$sql2="UPDATE `redesagi_facturacion`.`ticket` SET `telefono-contacto`='$telefono_contacto',`solucion`='$solucion',`recomendaciones`='$recomendaciones',`status`='$status',`precio-soporte`='$precio_soporte',`precio-soporte-descripcion`='$precio_soporte_descripcion',`tecnico`='$tecnico',`id-tecnico`='$id_tecnico',`tipo-soporte`='$tipo_soporte',`evidencia-fotografica1`='$evidencia_fotografica1',`evidencia-fotografica2`='$evidencia_fotografica2',`fecha-cierre-ticket`='$fecha_cierre_ticket' WHERE `ticket`.`id`='$idTicket' ";
if(!$mysqli->query($sql2)){
    $msj= 'error updating tickets!';
}
$msjToBack="error saving ticket";
if($msj=="updated"){
    if(sendEmail($idTicket,$cliente,$direccion,$telefono,$fecha_creacion_ticket,$solicitud_cliente,$solucion,$recomendaciones,$tecnico,$email,$email_user,$email_password)){
        $msjToBack="updatedEmailOk";
        echo json_encode($msjToBack);
    }else{
        $msjToBack="updatedEmailNo";
        echo json_encode($msjToBack);
    }
}else{
    echo json_encode($msjToBack);
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
    $mail->setFrom('cliente@ispexperts.com', 'Ticket Internet -SOPORTE TECNICO'); 
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
                <h3>Fecha de cierre de Caso #$numeroDeCaso: $today</h3>
                <div $style_div_thead_td>
                    <p><strong>Titular: </strong>$titular</p>
                    <p><strong>Direcciòn: </strong>$direccion</p>
                    <p><strong>Telèfono: </strong>$telefono</p>
                    <p><strong>Fecha apertura de caso: </strong>$fechaApertura</p>
                    <p><span>ESTADO DEL CASO: </span><strong>CERRADO</strong></p>
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
                            <a href='#'>E-mail:servicioalcliente@ispexperts.com</a>
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