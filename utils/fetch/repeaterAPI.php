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
$awsVpninterfaceNameMain="ppp0";
$awsVpninterfaceNameSecondary="ppp1";
$awsVpninterfaceNameTertiary="ppp2";

if($_SERVER['REQUEST_METHOD']==='GET'){
    switch($_GET["option"]){
        case "subnet":{
            $sql="INSERT  INTO `redesagi_facturacion`.`repeater_subnets_group` (`descripcion`,`date`) VALUES ('','$today')";
            if($mysqli->query($sql)=== true){
                $lastId=$mysqli->insert_id;
                $response= '{"id": "'.$lastId.'"}';
            }
            break;
        }
        case "ipSegment":{
            $sql="SELECT MAX(`ip-segment`) AS mx FROM `redesagi_facturacion`.`items_repeater_subnet_group` WHERE `ip-segment`!='200' ";
            if($result=$mysqli->query($sql)){
                $array=$result->fetch_assoc()["mx"];
                $lastId=$array==199?201:$array+1;
                $response= '{"ipSegment": "'.$lastId.'"}';
            }
            break;
        }
    
    }
}
if($_SERVER['REQUEST_METHOD']==='POST' ){ 
    switch($_POST["option"]){
        case "itemRepeater":{
            $idRepeaterSubnetsGroup=$mysqli -> real_escape_string($_POST["idRepeaterSubnetsGroup"]); 
            $newIpSegment=$mysqli -> real_escape_string($_POST["newIpSegment"]);
            $repeaterName=$mysqli -> real_escape_string($_POST["repeaterName"]); 
            $idAwsVpnClient= $mysqli -> real_escape_string($_POST["picked"]);
            $sql="INSERT INTO `redesagi_facturacion`.`items_repeater_subnet_group` (`id`, `id-repeater-subnets-group`, `ip-segment`, `descripcion`, `ubicacion`, `active`, `id-aws-vpn-client`, `aws-vpn-interface-name-main`, `aws-vpn-interface-name-secondary`, `aws-vpn-interface-name-tertiary`) VALUES (NULL, '$idRepeaterSubnetsGroup', '$newIpSegment', '$repeaterName', '', '1', '$idAwsVpnClient', 'ppp0', 'ppp1', 'ppp2') ";
            if($mysqli->query($sql)=== true){
                $lastId=$mysqli->insert_id;
                $response= '{"id": "'.$lastId.'"}';
            }
            break; 
        }
        case "vpnTargets":{
            $repeaterName=$mysqli -> real_escape_string($_POST["repeaterName"]);
            $ipServer=$mysqli -> real_escape_string($_POST["ipServer"]);
            $idRepeaterSubnetsGroup=$mysqli -> real_escape_string($_POST["idRepeaterSubnetsGroup"]);
            $idEmpresa=$mysqli -> real_escape_string($_POST["idEmpresa"]);
            $usernameVpnTarget=$awsVpnDefaultUser; 
            $passwordVpnTarget=$awsVpnDefaultPassword;
            $active=1;
            $idGruposEmpresa=$defaultIdGruposEmpresa;
            $loadScript=1;
            $fecha=$today; 
            $sql="INSERT INTO `vpn_targets` (`id`, `fecha`, `server-name`, `server-ip`, `username`, `password`, `id-repeater-subnet-group`, `active`, `id-empresa`, `id-grupos-empresa`, `load-script`) VALUES (NULL, '$fecha', '$repeaterName', '$ipServer', '$usernameVpnTarget', '$passwordVpnTarget', '$idRepeaterSubnetsGroup', '$active', '$idEmpresa', '$idGruposEmpresa', '$loadScript')";
            if($mysqli->query($sql)=== true){
                $lastId=$mysqli->insert_id;
                $response= '{"id": "'.$lastId.'"}';
            }
            break; 
        }
        case "staticRoutes":{
            $newIpSegment=$mysqli -> real_escape_string($_POST["newIpSegment"]);
            $repeaterName=$mysqli -> real_escape_string($_POST["repeaterName"]);
            $idItemsRepeaterSubnetGroup=$mysqli -> real_escape_string($_POST["idItemsRepeaterSubnetGroup"]);
            $sql="INSERT INTO `static_routes` (`id`, `ip-segment`, `descripcion`, `id-items-repeater-subnet-group`) VALUES (NULL, '$newIpSegment', '$repeaterName', '$idItemsRepeaterSubnetGroup')";
            if($mysqli->query($sql)=== true){
                $lastId=$mysqli->insert_id;
                $response= '{"id": "'.$lastId.'"}';
            }
            break; 
        }
        case "staticRouteSteps":{
            $step=$mysqli -> real_escape_string($_POST["step"]);
            $idStaticRoutes=$mysqli -> real_escape_string($_POST["idStaticRoutes"]);
            $localServerIp=$mysqli -> real_escape_string($_POST["localServerIp"]);
            $dstAddress=$mysqli -> real_escape_string($_POST["dstAddress"]);
            $gateway=$mysqli -> real_escape_string($_POST["gateway"]);
            $idAwsVpnClient=$mysqli -> real_escape_string($_POST["idAwsVpnClient"]);
            $sql="INSERT INTO `static_route_steps` (`id`, `step`, `id-static-route`, `local-server-ip`, `dst-ip-address`, `gateway`, `id-aws-vpn-client`) VALUES (NULL, '$step', '$idStaticRoutes', '$localServerIp', '$dstAddress', '$gateway', '$idAwsVpnClient')";
            if($mysqli->query($sql)=== true){
                $lastId=$mysqli->insert_id;
                $response= '{"id": "'.$lastId.'"}';
            }
            break; 
        }
    }
}
if(!$response)$response= '{"id": "0"}';
echo $response;

?>