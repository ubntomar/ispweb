<?php
// session_start();
// if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
//     header('Location: ../../login/index.php');
//     exit;
// } else {
//     $user = $_SESSION['name'];
//     $role = $_SESSION["role"];
//     $id_tecnico = $_SESSION["id"];
// }
require("../../login/db.php");
require("../../Client.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
$clientObject=new Client($server, $db_user, $db_pwd, $db_name);
mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");
$convertdate = date("d-m-Y", strtotime($today));
$hourMin = date('H:i');
$debug = 0;
$msj="saved";
$ticketData = mysqli_real_escape_string($mysqli, $_REQUEST['ticketData']);
$data=json_decode(stripslashes($ticketData),true);
$clienteId=25;//$data["id"]; 
//Update afiliados table 
$param="wallet-money";
$value=$data["wallet"];
$res=$clientObject->updateClient($clienteId,$param,$value);
if($res){
    echo json_encode("success");
}else{
    echo json_encode("fail $clienteId  -- $value "); 
}







?>