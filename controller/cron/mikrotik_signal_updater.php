<?php

require("/var/www/ispexperts/PingTime.php");
require("/var/www/ispexperts/Client.php");
require("/var/www/ispexperts/login/db.php");
require("/var/www/ispexperts/Mkt.php");
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
// $MikrotikCredentials[]=["user"=>"ubnt","password"=>"ubnt1234"];

$clientObject=new Client($server, $db_user, $db_pwd, $db_name);


$sql="SELECT `id`,`ip` FROM `redesagi_facturacion`.`afiliados` WHERE `activo`='1' AND `eliminar`='0' AND `ssh-login-type`='mikrotik' AND `signal-strenght`='0'  ORDER BY `id` ";
if($result=$mysqli->query($sql)){
    while($row=$result->fetch_assoc()){
        print "\n {$row["id"]} {$row["ip"]} \t";
        $id=$row["id"];
        $ipValue=$row["ip"];
        if (filter_var($ipValue, FILTER_VALIDATE_IP)) {
            $pingObj=new PingTime($ipValue);
            if($time=$pingObj->time()){
                print "$ipValue $time ms \t";
                $port=22;
                $connection = @fsockopen($ipValue, $port,$errno, $errstr, 8);//last parameter is timeout 
                if (is_resource($connection)){
                    echo '<h2>' . $ipValue . ':' . $port . ' ' . '(' . getservbyport($port, 'tcp') . ') is open.</h2>' . "\n";
                    fclose($connection);
                    foreach ($MikrotikCredentials as $row) {
                        $obj=new Mkt($ipValue,$row["user"],$row["password"]); 
                        $signal=0;
                        if($obj->success){   
                            $signal=explode("@",$obj->checkSignal())[0];
                            print "id $id ---$time {$row["user"]} {$row["password"]} $ipValue: ok SIGNAL: $signal \n";
                            if(intval($signal)){
                                print $cont++."*";
                                print $clientObject->updateClient($id,"signal-strenght",intval($signal),$operator="=");
                                print $clientObject->updateClient($id,"ssh-login-type","mikrotik",$operator="=");
                                break;
                            }
                        }
                    }
                }else{
                    echo '<h2>' . $ipValue . ' is not responding.</h2>' . "\n";
                }
            }else{
                $clientObject->updateClient($id,"ping",null,$operator="=");
            }

        }
    }
}






?>