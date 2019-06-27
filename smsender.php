<?php
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: login/index.php');
		exit;
		}
else    {
        $user=$_SESSION['username'];
        $role=$_SESSION["role"];
		}
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
$debug=0;

require_once '/home/ubuntu/vendor/autoload.php'; 
use Twilio\Rest\Client; 
$sid    = "AC6dffc6d75f3fe13e5ab0cfe1f6180b57"; 
$token  = "b6bcf5d638adfc032d2ab7f4ed35baf3"; 
$twilio = new Client($sid, $token); 
if($_POST['datos']) {
    $idarray=explode(",",$_POST['datos']);
    $msj=$_POST['message'];
    foreach($idarray as $id){
        $cont+=1;
        $sql_client_telefono="select * from redesagi_facturacion.afiliados where `id`=$id ";
        $result = mysqli_query($mysqli, $sql_client_telefono) or die('error');
        $db_field = mysqli_fetch_assoc($result);        
        $telefono=$db_field['telefono'];
        // echo "$cont : $id : $user : $role : {$db_field['telefono']} : {$db_field['cliente']} : $message \n";
        try{		
            $message = $twilio->messages->create("+57".$telefono."", // to 
                array( 
                    "from" => "+18508055304",       
                    "body" => $msj 
                ) 
                );                
            $sqlinsert="insert into redesagi_facturacion.sent_messages (id,tipo,fecha,hora,status,user,personalized_content,id_client) values (null,5,'$today','$hourMin','ok','$user','$msj',$id)";
            mysqli_query($mysqli,$sqlinsert);
            echo "$cont-ok \n";
            } catch (Twilio\Exceptions\RestException $e) {
                $status = 'invalid';
                $sqlinsert="insert into redesagi_facturacion.sent_messages (id,tipo,fecha,hora,status,user,personalized_content) values (null,5,'$today','$hourMin','no','$user','$msj')";
                mysqli_query($mysqli,$sqlinsert);
                echo "$cont-no \n";                
            }
        //

    }
}
?>    