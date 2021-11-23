<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../../login/index.php');
    exit;
} else {
    $user = $_SESSION['name'];
    $role = $_SESSION["role"];
    $id_tecnico = $_SESSION["id"];
}
require("../../login/db.php");
require("../Wallet.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
$walletObject=new Wallet($server, $db_user, $db_pwd, $db_name);
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
//Update afiliados table 
$param="wallet-money";
$value=$data["wallet"];
$valueToCharge=$data["money"];
$res=$walletObject->updateClient($clienteId,$param,$value,$operator="=");
$idWallet=$walletObject->createWallet($clienteId, $action="add", $value=$valueToCharge, $date=$today, $hour=$hourMin, $idCajero=$user, $source="wallet->saveNewWallet", $comment="");
$json='{"client":"'.$res.'","idWallet":"'.$idWallet.'"}';
echo $json;








?>