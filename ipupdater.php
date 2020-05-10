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

if ($_POST['id']) {
    $id = mysqli_real_escape_string($mysqli, $_REQUEST['id']);
    $ip = mysqli_real_escape_string($mysqli, $_REQUEST['ip']);
    $duplicatedIp="SELECT `id`,`ip`,`cliente`,`apellido` FROM `redesagi_facturacion`.`afiliados` WHERE `ip` = '$ip'  ";
    $resultip = mysqli_query($mysqli, $duplicatedIp) or die('error');
    $filas = mysqli_num_rows($resultip);
    if($filas >= 1){
        $arrayIP= array();
        while ($row = mysqli_fetch_array($resultip)) {
            if($row[0]==$id)$filas-=1;
            $arrayIP['Ip Invalida: Duplicada'][] = "<br>---------------<br>"; 
            $arrayIP['Ip Invalida: Duplicada'][] = $row[0]; 
            $arrayIP['Ip Invalida: Duplicada'][] = $row[1]; 
            $arrayIP['Ip Invalida: Duplicada'][] = $row[2];
            $arrayIP['Ip Invalida: Duplicada'][] = $row[3];  
        }
        $response= json_encode($arrayIP, JSON_PRETTY_PRINT);
    } 
    if($filas == 0){
        $sql_update = "UPDATE `redesagi_facturacion`.`afiliados` SET `ip` = '$ip' WHERE (`id` = '$id');";
        $result = mysqli_query($mysqli, $sql_update) or die('error');
        $response= "1";
    }
    echo $response;  
}
?>