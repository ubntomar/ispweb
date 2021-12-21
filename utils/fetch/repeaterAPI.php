<?php
require("../../login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');

if($_SERVER['REQUEST_METHOD']==='GET'){
    switch($_GET["option"]){
        case "subnet":{
            //$sql="SELECT MAX(id) AS mx FROM `redesagi_facturacion`.`repeater_subnets_group`";
            $sql="INSERT  INTO `redesagi_facturacion`.`repeater_subnets_group` (`descripcion`,`date`) VALUES ('','$today')";
            if($mysqli->query($sql)=== true){
                $lastId=$mysqli->insert_id;
                $response= '{"id": '.$lastId.'}';
            }
            break;
        }
        case "ipSegment":{
            $sql="SELECT MAX(`ip-segment`) AS mx FROM `redesagi_facturacion`.`items_repeater_subnet_group` WHERE `ip-segment`!='200' ";
            if($result=$mysqli->query($sql)){
                $array=$result->fetch_assoc()["mx"];
                $lastId=$array==199?201:$array+1;
                $response= '{"ipSegment": '.$lastId.'}';
            }
            break;
        }
    
    }
}
if($_SERVER['REQUEST_METHOD']==='POST'){
    switch($_POST["parametro"]){
        case "10":{
            $response= '{"id": "99"}';
            break;
        }
        
    
    }
}
if(!$response)$response= '{"id": "0"}';
// sleep(1);
echo $response;
 
// INSERT INTO `items_repeater_subnet_group` 
// (`id`, `id-repeater-subnets-group`, `ip-segment`, `descripcion`, `ubicacion`, `active`, `id-aws-vpn-client`, `aws-vpn-interface-name-main`, `aws-vpn-interface-name-secondary`, `aws-vpn-interface-name-tertiary`) 
// VALUES 
//      (NULL, '928', '999', 'Repetidor de prueba', 'Guamal', '1', '1', 'ppp0', 'ppp1', 'ppp2');



?>