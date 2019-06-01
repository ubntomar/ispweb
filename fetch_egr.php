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
if($_POST['egr']){
	$egr= mysqli_real_escape_string($mysqli, $_REQUEST['egr']);
	$det= mysqli_real_escape_string($mysqli, $_REQUEST['det']);
	$val= mysqli_real_escape_string($mysqli, $_REQUEST['val']);
	$egrtxt= mysqli_real_escape_string($mysqli, $_REQUEST['egrtxt']);	
	$sql="INSERT INTO `redesagi_facturacion`.`egresos` (`id-egreso`, `concepto`, `id-concepto`,`detalle`, `valor`, `fecha`, `hora`, `cajero`, `tipo`) VALUES (NULL, '$egrtxt', '$egr','$det', '$val', '$today', '$hourMin', '$usuario', '0')";								
	if ($mysqli->query($sql) === TRUE) {
		    echo "Egreso agregado con exito!!";	    
		} 
		else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}
			
}	

 ?>
