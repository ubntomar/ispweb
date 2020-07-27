<?php
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		// header('Location: login/index.php');
		// exit;
		}
else    {
		$user=$_SESSION['username'];
		}
header('Content-Type: application/json');
require 'dateHuman.php';
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$list []= array("id"=>"1","cliente"=>"first cliente","telefono"=>"3147654655","telefonoContacto"=>"3145265645","direccion"=>"cra 9 12 43","email"=>"omar_alberto_h@yahoo.es","ip"=>"192.168.1.1","marcaRouter"=>"Tenda","macRouter"=>"00:0F:0C:D4:F5:C0","macAntena"=>"00:0F:BD:02:05:AB","inyectorPoe"=>"Mikrotik","apuntamiento"=>"Calizas","accesoRemoto"=>"si","tipoAntena"=>"ubiquiti","tipoInstalacion"=>"x Antena","tipoSoporte"=>"daño de cable","solicitudCliente"=>"no tiene servicio actualmente","tecnico"=>"Juan Pablo","recibe"=>"alguien recibe");
$list[]= ["id"=>"2","cliente"=>"Second cliente","telefono"=>"3215450397","telefonoContacto"=>"3145265645","direccion"=>"cra 19 12 43","email"=>"omar_alberto_h@yahoo.es","ip"=>"192.168.1.2","marcaRouter"=>"Nexxt","macRouter"=>"00:0F:0C:D4:F5:C0","macAntena"=>"00:0F:BD:02:05:AB","inyectorPoe"=>"Mikrotik","apuntamiento"=>"Montecristo","accesoRemoto"=>"si","tipoAntena"=>"ubiquiti","tipoInstalacion"=>"x Antena","tipoSoporte"=>"daño de cable","solicitudCliente"=>"no tiene servicio actualmente","tecnico"=>"Juan Pablo","recibe"=>"alguien recibe"];
$jsonList=json_encode($list);
echo $jsonList;



?>