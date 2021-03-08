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
        else
            $telefono .= ",57".$db_field['telefono'];
            
        $sqlinsert="insert into redesagi_facturacion.sent_messages (id,tipo,fecha,hora,status,user,personalized_content,id_client) values (null,5,'$today','$hourMin','ok','$user','$msj',$id)";
        mysqli_query($mysqli,$sqlinsert) or die('error');         

    }
    // $url = 'https://api.hablame.co/sms/envio/';
    // $data = array(
    //     'cliente' => 10015263, //Numero de cliente
    //     'api' => '0CGog61aGGgTn0dCFzgwjqPtlARGCp', //Clave API suministrada
    //     'numero' => $telefono, //numero o numeros telefonicos a enviar el SMS (separados por una coma ,)
    //     'sms' => $msj, //Mensaje de texto a enviar
    //     'fecha' => '', //(campo opcional) Fecha de envio, si se envia vacio se envia inmediatamente (Ejemplo: 2017-12-31 23:59:59)
    //     'referencia' => 'Referenca Envio Hablame', //(campo opcional) Numero de referencio ó nombre de campaña
    // );

    // $options = array(
    //     'http' => array(
    //         'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
    //         'method'  => 'POST',
    //         'content' => http_build_query($data)
    //     )
    // );
    // $context  = stream_context_create($options);
    // $result = json_decode((file_get_contents($url, false, $context)), true);

    // if ($result["resultado"] === 0) {
    //     echo 'Se ha enviado el SMS exitosamente';
    // } else {
    //     echo json_encode($result);
    // }
    $curl = curl_init();
    $query = http_build_query(array(
    'key' => '7569901a3b138f406d2c7acc4704838c7047dbb5600511a41029d',
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
 