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

if ($_POST['datos']) {
    $idarray = explode(",", $_POST['datos']);
    $msj = $_POST['message'];
    $telefono = "";
    $cont=0;
    foreach ($idarray as $id) {
        $cont += 1;
        $sql_client_telefono = "select * from redesagi_facturacion.afiliados where `id`=$id ";
        $result = mysqli_query($mysqli, $sql_client_telefono) or die('error');
        $db_field = mysqli_fetch_assoc($result);
        if ($cont==1) {
            $telefono .= "57".$db_field['telefono'].",";
        }
        else
            $telefono .= ",57".$db_field['telefono'];
            
        $sqlinsert="insert into redesagi_facturacion.sent_messages (id,tipo,fecha,hora,status,user,personalized_content,id_client) values (null,5,'$today','$hourMin','ok','$user','$msj',$id)";
        mysqli_query($mysqli,$sqlinsert) or die('error');         

    }
    $url = 'https://api.hablame.co/sms/envio/';
    $data = array(
        'cliente' => 10015263, //Numero de cliente
        'api' => '0CGog61aGGgTn0dCFzgwjqPtlARGCp', //Clave API suministrada
        'numero' => $telefono, //numero o numeros telefonicos a enviar el SMS (separados por una coma ,)
        'sms' => $msj, //Mensaje de texto a enviar
        'fecha' => '', //(campo opcional) Fecha de envio, si se envia vacio se envia inmediatamente (Ejemplo: 2017-12-31 23:59:59)
        'referencia' => 'Referenca Envio Hablame', //(campo opcional) Numero de referencio ó nombre de campaña
    );

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = json_decode((file_get_contents($url, false, $context)), true);

    if ($result["resultado"] === 0) {
        echo 'Se ha enviado el SMS exitosamente';
    } else {
        echo json_encode($result);
    }
}
