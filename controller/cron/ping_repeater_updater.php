<?php
require("../../login/db.php");
require("../../Mkt.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");
//validate vpn is up for ip-segment target
//ping to vpn ip client

//ping time, pind date to items_repeater_subnet_group->ip-segment

//ip-segment ip route exist in remote router board?  `remote-route`

$sql="SELECT * FROM `redesagi_facturacion`.`items_repeater_subnet_group` WHERE 1 ";
if($result=$mysqli->query($sql)){
    while($row=$result->fetch_assoc()){
        
    }
}


$mktObject=new Mkt($ipRouter="192.168.65.1", $user="agingenieria", $pass="agwist2017");
print $mktObject->ipRoute($dstAddresses="192.168.125.0/24")==3?"Dst-nat Exist!":"Dst-nat dont Exist";





?>