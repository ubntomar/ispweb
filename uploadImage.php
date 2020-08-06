<?php
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true){
	header('Location: login/index.php');
	exit;
	}
else{
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
if($_FILES['file1']['name']){
    $filename = $_FILES['file1']['name'];
    $source="file1";
}
else{
    $filename = $_FILES['file2']['name'];
    $source="file2";
} 
$valid_extensions = array("jpg","jpeg","png");
$extension = pathinfo($filename, PATHINFO_EXTENSION);
if(in_array(strtolower($extension),$valid_extensions) ) {
    if(move_uploaded_file($_FILES[$source]['tmp_name'], "img/".$filename)){
		$stat="success";
	}else{
        $stat="error";
		$msj= "error saving the file:";
	}
}else{
    $stat="error";
	$msj= "Tipo de archivo invalido";
}
$respon=["stat"=>"$stat","msj"=>"$msj","source"=>"$source"];
echo json_encode($respon);
?>