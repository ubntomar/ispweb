<?php
//0 */6 * * * php /var/www/html/controller/cron/ping_repeater_updater.php     =>every 6 hours 
require("/var/www/ispexperts/login/db.php");
require("/var/www/ispexperts/Mkt.php");
require("/var/www/ispexperts/PingTime.php");
require("/var/www/ispexperts/controller/src/Repeater.php");
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
$mktObject=new Mkt($ipRouter="192.168.65.1", $user="agingenieria", $pass="agwist2017");
$repeaterObject=new Repeater($server, $db_user, $db_pwd, $db_name);
$sql="SELECT * FROM `redesagi_facturacion`.`items_repeater_subnet_group` WHERE 1 ";
if($result=$mysqli->query($sql)){
    while($row=$result->fetch_assoc()){
        $idItemsRepeater=$row["id"];
        $idAwsVpnClient=$row["id-aws-vpn-client"];
        $ipSegment=$row["ip-segment"];
        $idRepeaterSubnetsGroup=$row["id-repeater-subnets-group"];
        $sql="SELECT `ip` FROM `redesagi_facturacion`.`aws-vpn-client` WHERE `id`='$idAwsVpnClient' ";
        if($res=$mysqli->query($sql)){
            $vpnRow=$res->fetch_assoc();
            $vpnIp=$vpnRow["ip"];
            $sql="SELECT * FROM `redesagi_facturacion`.`vpn_targets` WHERE `id-repeater-subnet-group`=$idRepeaterSubnetsGroup ";
            if($re=$mysqli->query($sql)){
                $rw=$re->fetch_assoc();
                $fourByteIpServer=explode(".",$rw["server-ip"])[3];
                if($fourByteIpServer!=1){
                    $pingTimeObject=new PingTime($vpnIp);
                    if($time=$pingTimeObject->time()){
                        $repeaterObject->updateRepeater($tableName="items_repeater_subnet_group",$item="remote-route",$operator="=",$value="1",$idTarget="$idItemsRepeater");
                        $lanIpSegment="192.168.$ipSegment.1";
                        $pingTimeObject2=new PingTime($lanIpSegment);
                        if($pintToLanTime=$pingTimeObject2->time()){
                            print "\n $lanIpSegment :   $pintToLanTime ms \n";
                            $repeaterObject->updateRepeater($tableName="items_repeater_subnet_group",$item="ping",$operator="=",$value="$pintToLanTime",$idTarget="$idItemsRepeater");
                            $repeaterObject->updateRepeater($tableName="items_repeater_subnet_group",$item="ping-date",$operator="=",$value="$today",$idTarget="$idItemsRepeater");
                        }
                    }
                }
            }
            
        }
    }
    $result->free();
}



// print $mktObject->ipRoute($dstAddresses="192.168.125.0/24")==3?"Dst-nat Exist!":"Dst-nat dont Exist";





?>