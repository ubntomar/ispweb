<?php

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
$pass=true;
$user="aws";
if($mkobj=new Mkt($serverIpAddressArea1,$vpnUser,$vpnPassword)){
	//echo "\nConectado a la Rboard cod Server-target-> 1:$serverIpAddressArea1\n";
	$pass=true;        
}

//echo json_encode($mkobj->list_all())."\n\n";
//$exclusivosList=$mkobj->list_all();
// foreach ($exclusivosList as $value) {
// 	if($value['list']=='Exclusivos')	echo " {$value['ip']}\n";
// }
//$listIp=json_encode($mkobj->list_all());
//echo $listIp;
//echo ..soy un fork!!
$DefaultJson="{}";
$fileJsonString= file_get_contents("ipAlive.json");
if($fileJsonString=="")$fileJsonString=$DefaultJson;
$filePhpObject=json_decode($fileJsonString,true);
array_push($filePhpObject,$mkobj->list_all());
$jsonData=json_encode($filePhpObject);
file_put_contents('ipAlive.json',$jsonData);


?>