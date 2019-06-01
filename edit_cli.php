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
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$usuario=$_SESSION['username'];

$debug=0;
if($_POST['id']) {	
			$id = mysqli_real_escape_string($mysqli, $_REQUEST['id']);
			$telefono = mysqli_real_escape_string($mysqli, $_REQUEST['telefono']);		
			$sqlTelefono="UPDATE `redesagi_facturacion`.`afiliados` SET `telefono` = '$telefono' WHERE `afiliados`.`id` = $id";					
			if ($result = $mysqli->query($sqlTelefono)) {
				echo "Teléfono actualizado con éxito!!!";
			}
			else{
				echo "Error al actualizar Teléfono";
			}
}
?>