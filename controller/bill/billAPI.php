<?php
require("../../login/db.php"); 
require("../../Client.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i'); 
$response='{"updated":"fail"}';
if( $_SERVER['REQUEST_METHOD']==='POST' ){
    switch ($mysqli -> real_escape_string($_POST["option"])) {
        case 'updateClient':{
            $clientObj=new Client($server, $db_user, $db_pwd, $db_name);
            $id=$_POST["id"];
            $param="pago";
            $value=$_POST["planPrice"];
            $speed=$_POST["speed"];
            $t="success $id $value";
            //if($clientObj->updateClient($id,$param,$value,$operator="="))
                $response="success";
            break;
        } 
        
        default:{
            break;
        }
    }
}

echo "hello world$response-".$_POST["option"]."-lol-speed:".$_POST["speed"]."planPrice".$_POST["planPrice"]."id--".$_POST["id"];


?>