<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login/index.php');
    //exit;
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

if ($_POST['datos']||1) {
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
        elseif ($cont==2){
            $telefono .= "57".$db_field['telefono'];
        }
        else{
            $telefono .= ",57".$db_field['telefono'];
        }
            
        $sqlinsert="insert into redesagi_facturacion.sent_messages (id,tipo,fecha,hora,status,user,personalized_content,id_client) values (null,5,'$today','$hourMin','ok','$user','$msj',$id)";
        mysqli_query($mysqli,$sqlinsert) or die('error');         

    }
    echo "mensaje:".$msj;
    echo "telefonos:".$telefono; //
    $curl = curl_init();
    $query = http_build_query(array(
    'key' => $smsKey,
    'client' => '1856',
    'phone' => $telefono,
    'sms' => $msj,
    'country-code' => 'CO'
    ));
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://www.onurix.com/api/v1/send-sms",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    /*CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,*/
    CURLOPT_POST  => 1,
    CURLOPT_POSTFIELDS => $query,
    CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded"
    ),
    ));
    echo "Enviando..."; 
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response."\n";
        $arrayDecoded=json_decode($response, true);
        echo ($arrayDecoded['status']==1)? "Mensaje enviado!":"Error en envio de mensaje";
    } 

}
 