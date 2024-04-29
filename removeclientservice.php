<?php
session_start();
$debug = false;
if(!$debug){
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header('Location: login/index.php');
        exit;
    } else {
        $user = $_SESSION['username'];
        $role = $_SESSION["role"];
    }
}
include("login/db.php");
require 'Mkt.php'; 
require 'vpnConfig.php';
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");
$convertdate = date("d-m-Y", strtotime($today));
$hourMin = date('H:i');
$pass=false;
if ($_POST['datos']||$debug) {
    if($debug)
        $idarray[]=363;//Hernando Monguívvvv 
    else
        $idarray  = explode(",", $_POST['datos']);  
    
    foreach ($idarray as $id) {
        //suspender=1
        //shutoffpending=1 
        $sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`suspender`='1' , `afiliados`.`shutoffpending`='1', `afiliados`.`reconectPending`='0', `afiliados`.`suspenderFecha`='$today'  WHERE `afiliados`.`id`='$id'";
        if($result2 = $mysqli->query($sqlUpd)){					
        }
        else{
            echo "\nError al actualizar cliente Mysql `shutoffpending`=1\n";	
        }
        $sql_client = "select * from redesagi_facturacion.afiliados where `id`=$id ";
        $result = mysqli_query($mysqli, $sql_client) or die('error encontrando el cliente');
        $db_field = mysqli_fetch_assoc($result);
        $ip=$db_field['ip'];
        if($db_field['shutoffpending'])
            print "Cliente $ip para suspender en el cron";
        $sqlinsert="insert into redesagi_facturacion.service_shut_off (id,tipo,fecha,hora,status,user,ip,id_client) values (null,5,'$today','$hourMin','ok','$user','$ip',$id)";
        mysqli_query($mysqli,$sqlinsert) or die('error ingresando a suspendidos tb');
    }
     
}
