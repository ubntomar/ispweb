<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../../login/index.php');
    exit;
} else {
    $user = $_SESSION['name'];
    $role = $_SESSION["role"];
    $id_tecnico = $_SESSION["id"];
    $idCajero = $_SESSION['idCajero'];
}
require("../../login/db.php");
require("../Wallet.php");
require("../sms/Sms.php");
require("../brand/Company.php");
require("../../Email.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$walletObject=new Wallet($server, $db_user, $db_pwd, $db_name);
$today = date("Y-m-d");
$convertdate = date("d-m-Y", strtotime($today));
$hourMin = date('H:i');
$debug = 0;
$msj="saved";
$ticketData = mysqli_real_escape_string($mysqli, $_REQUEST['ticketData']);
$data=json_decode(stripslashes($ticketData),true);
$clienteId=$data["id"]; 
$email=$data["email"]; 
$telefono=$data["telefono"]; 
//Update afiliados table 
$param="wallet-money";
$value=$data["wallet"];
$valueToCharge=$data["money"];
$res=$walletObject->updateClient($clienteId,$param,$value,$operator="="); 
$idWallet=$walletObject->createWallet($clienteId, $action="add", $value=$valueToCharge, $date=$today, $hour=$hourMin, $idCajero, $source="wallet->saveNewWallet", $comment="");

/////SMS && EMAIL
$companyObj=new Company($server, $db_user, $db_pwd, $db_name);
$smsObj=new Sms($server, $db_user, $db_pwd, $db_name);
$idClient=$clienteId;
$prefix=$prefixCode;//"+57";
$key=$smsKey;
$endPoint=$mailEndPoint;
// $email="ag.ingenieria.wist@gmail.com";
$fullName=$walletObject->getClientItem($idClient,$item="cliente")."  ".$walletObject->getClientItem($idClient,$item="apellido");
$companyName=$companyObj->getCompanyItem($idCompany=1,$item="nombre");
$companyAddress=$companyObj->getCompanyItem($idCompany=1,$item="direccion");
$message="Gracias por tu pago!. Sigue disfrutando del servicio. $companyName $companyAddress(Meta)";
$data[] =["idClient"=>$idClient,"phone"=>$telefono]; 
$sms= $smsObj->sendSms($data,$message,$key)["status"];//
$emailRespone="";
$emailObj=new Email($endPoint);
if(($emailObj->emailValidate($email)) && $fullName){
    $response=$emailObj->emailAfterPayment($emailArray=[
        "fullName"=> $fullName,
        "template"=>$tokenToPaymentDone,
        "idClient"=>$idClient,
        "email"=>$email
        ]);
    $emailRespone=$response;   
}
///////END/////// 

// $json='{"client":"'.$res.'","idWallet":"'.$idWallet.'"}';  
$json='{"client":"'.$res.'","idWallet":"'.$idWallet.'","sms":"'.$sms.'","email":"'.$emailRespone.'"}'; 
echo $json;








?>