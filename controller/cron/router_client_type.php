<?php

require("../../PingTime.php");
require("../../Client.php");
require("../../login/db.php");
require("../../Mkt.php");
date_default_timezone_set('America/Bogota');
$date = new DateTime('NOW');
$date->format('Y-m-d');
$hoy=$date->format('Y-m-d');
$date->format('Y/m/d');
$today=$date->format('Y/m/d');
$date->modify('-3 day');
$yesterday=$date->format('Y/m/d');
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    }	
mysqli_set_charset($mysqli,"utf8"); 

// $MikrotikCredentials[]=["user"=>"ubnt","password"=>"ubnt"];
$MikrotikCredentials[]=["user"=>"admin","password"=>"agwist2017"];
$MikrotikCredentials[]=["user"=>"admin","password"=>""];
// $MikrotikCredentials[]=["user"=>"ubnt","password"=>"ubnt1234"];

$clientObject=new Client($server, $db_user, $db_pwd, $db_name);


$sql="SELECT `id`,`ip` FROM `redesagi_facturacion`.`afiliados` WHERE `activo`='1' AND `eliminar`='0' ORDER BY `id` DESC ";
if($result=$mysqli->query($sql)){
    while($row=$result->fetch_assoc()){
        print "\n {$row["id"]} {$row["ip"]} \t";
        $id=$row["id"];
        $ipValue=$row["ip"];
        if (filter_var($ipValue, FILTER_VALIDATE_IP)) {
            $pingObj=new PingTime($ipValue);
            if($time=$pingObj->time(1)){
                print "$ipValue $time ms \t";
                $ipSegment=explode(".",$ipValue)[2];
                print "IP SEGMENT $ipSegment";
                $sql2="SELECT * FROM `redesagi_facturacion`.`items_repeater_subnet_group` WHERE `ip-segment`='$ipSegment' AND (`id-repeater-subnets-group`!='1' AND `id-repeater-subnets-group`!='2') ";
                if($rsp=$mysqli->query($sql2)){
                    if($rsp->num_rows){
                        print "\nEl usuario $id $ipValue es parte de un repeater\n";
                        print $clientObject->updateClient($id,"ssh-login-type","router",$operator="=");
                    } 
                }
            }else{
                //$clientObject->updateClient($id,"ping",null,$operator="=");
            }

        }
    }
}






?>