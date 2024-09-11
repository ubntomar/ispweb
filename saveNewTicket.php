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
$idTicket=$mysqli->insert_id;

if($msj=="saved"){
    if(sendEmail($idTicket,$cliente,$direccion,$telefono,$fecha_creacion_ticket,$solicitud_cliente,$solucion,$recomendacion,$email)){
        $msjToBack="savedEmailOk";
        echo json_encode($msjToBack);
    }else{
        $msjToBack="savedEmailNo";
        echo json_encode($msjToBack);
    }
}else{
    echo json_encode("Error: $msj");
}





function sendEmail($idTicket,$cliente,$direccio,$telefon,$fecha_creacion_ticket,$solicitud_cliente,$solucio,$recomendacione,$emai){
    $numeroDeCaso="2541".$idTicket;
    $titular=strtoupper($cliente);
    $direccion=strtoupper($direccio);
    $telefono=$telefon;
    $fechaApertura=$fecha_creacion_ticket;
    $status="CERRADO";
    $solicitud=ucfirst($solicitud_cliente);
    $solucion=ucfirst($solucio);
    $recomendaciones=ucfirst($recomendacione);
    $email=$emai;
    $today = date("d-m-Y");
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp.flockmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'cliente@ispexperts.com';
    $mail->Password = 'NFGsgQ4awD';
    $mail->isHTML(true);
    $mail->setFrom('cliente@ispexperts.com', 'Apertura Ticket Internet -SOPORTE TECNICO'); 
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
            background-color:#693a63;
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
                <h3>CLIENTE SOLICITA SOPORTE TECNICO</h3>
                <div $style_div_thead_td>
                    <p><strong>Titular: </strong>$titular</p>
                    <p><strong>Direcciòn: </strong>$direccion</p>
                    <p><strong>Telèfono: </strong>$telefono</p>
                    <p><strong>Fecha apertura de caso: </strong>$fechaApertura</p>
                    <p><span>ESTADO DEL CASO: </span><strong>ABIERTO</strong></p>
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
                    <div $style_div></div>
                </td>
            </tr>
            <tr $style_tr>
                <td $style_td>
                    <strong $style_title>RECOMENDACIONES</strong>
                    <div $style_div></div>
                </td>
                <td $style_td>
                    <strong $style_title>TECNICO</strong>
                    <div $style_div></div>
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