<?php
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: login/index.php');
		exit;
		}
else    {
		$user=$_SESSION['username'];
		}
require("../../login/db.php"); 
require("../../Client.php");
require("../../Bill.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i'); 


if( $_SERVER['REQUEST_METHOD']==='POST' ){
    switch ($mysqli -> real_escape_string($_POST["option"])) {
        case 'updateClient':{
            $clientObj=new Client($server, $db_user, $db_pwd, $db_name);
            $id=$_POST["id"];
            if($speedValue=$mysqli -> real_escape_string($_POST["speed"])){
                $param="velocidad-plan";
                if($clientObj->updateClient($id,$param,$speedValue,$operator="=")){
                    $response[]=["speed"=>"updated"];
                }else{
                    $response[]=["speed"=>"fail"];
                }
            }else{
                $response[]=["speed"=>""];
            }
            if($pagoValue=$mysqli -> real_escape_string($_POST["planPrice"])){
                $param="pago";
                if($clientObj->updateClient($id,$param,$pagoValue,$operator="=")){
                    $response[]=["pago"=>"updated"];
                }else{
                    $response[]=["pago"=>"fail"];
                }
            }else{
                $response[]=["pago"=>""];
                
            }
            break;
        }
        case 'createBill':{
            $idClient=$mysqli -> real_escape_string($_POST["id"]);
            $item=$mysqli -> real_escape_string($_POST["item"]);
            $valor=$mysqli -> real_escape_string($_POST["valor"]);
            $saldo=$mysqli -> real_escape_string($_POST["saldo"]);
            $nota=$mysqli -> real_escape_string($_POST["nota"]);
            $billObj=new Bill($server, $db_user, $db_pwd, $db_name);
            $response=$billObj->createBill($idClient,$periodo=$item,$notas=$nota,$valorf=$valor,$valorp="0",$saldo,$cerrado="0",$fechaPago='',$iva="19",$descuento="0",$fechaCierre='',$vencidos='0');
            break;
        } 
        case 'updateBill':{
            $idBill=$mysqli -> real_escape_string($_POST["id"]);
            $item=$mysqli -> real_escape_string($_POST["item"]);
            $valor=$mysqli -> real_escape_string($_POST["valor"]);
            $saldo=$mysqli -> real_escape_string($_POST["saldo"]);
            $nota=$mysqli -> real_escape_string($_POST["nota"]);
            $billObj=new Bill($server, $db_user, $db_pwd, $db_name);
            $response=$billObj->updateBill($idBill,$itemFact="periodo",$value=$item);
            $response=$billObj->updateBill($idBill,$item="valorf",$value=$valor);
            $response=$billObj->updateBill($idBill,$item="saldo",$value=$saldo);
            $response=$billObj->updateBill($idBill,$item="notas",$value=$nota);
            break;
        } 
        case 'deleteBill':{
            $idBill=$mysqli -> real_escape_string($_POST["id"]);
            $billObj=new Bill($server, $db_user, $db_pwd, $db_name);
            $response=$billObj->deleteBill($idBill);
            break;
        } 
        default:{
            break;
        }
    }
}
if( $_SERVER['REQUEST_METHOD']==='GET' ){
    switch ($mysqli -> real_escape_string($_GET["option"])) {
        case 'getBillList':{
            $idClient=$mysqli -> real_escape_string($_GET["idClient"]);
            $billObj=new Bill($server, $db_user, $db_pwd, $db_name);
            $aditionalCondition=" `factura`.`cerrado`='0' ";
            $response=$billObj->getBill($idClient,$aditionalCondition);
            break;
        } 
        default:{
            break;
        }
    }
}

echo json_encode($response);


?>