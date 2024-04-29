<?php




if (file_exists("/var/www/ispexperts/PingTime.php")) {
    require("/var/www/ispexperts/PingTime.php");
} else {
    require("/home/omar/docker-work-area/go/ispweb/PingTime.php");
}

if (file_exists("/var/www/ispexperts/Client.php")) {
    require("/var/www/ispexperts/Client.php");
} else {
    require("/home/omar/docker-work-area/go/ispweb/Client.php");
}

if (file_exists("/var/www/ispexperts/login/db.php")) {
    require("/var/www/ispexperts/login/db.php");
} else {
    require("/home/omar/docker-work-area/go/ispweb/login/db.php");
    $server="localhost:3306";
}

if (file_exists("/var/www/ispexperts/controller/brand/Ubiquiti.php")) {
    require("/var/www/ispexperts/controller/brand/Ubiquiti.php");
} else {
    require("/home/omar/docker-work-area/go/ispweb/controller/brand/Ubiquiti.php");
}






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

$UbiquitiCredentials[]=["user"=>"ubnt","password"=>"-Agwist2017"];
$UbiquitiCredentials[]=["user"=>"ubnt","password"=>"Agwist1."];
// $UbiquitiCredentials[]=["user"=>"ubnt","password"=>"ubnt"];
$UbiquitiCredentials[]=["user"=>"ubnt","password"=>"ubnt1234"];
$UbiquitiCredentials[]=["user"=>"ubnt","password"=>"agwist2017"];

$clientObject=new Client($server, $db_user, $db_pwd, $db_name);

//$iid="AND (`id`='255' or `id`='258' or `id`='312' or `id`='802' or `id`='819' or `id`='883' or `id`='889' or `id`='905' or `id`='898' or `id`='908')";
//print $clientObject->updateClient($id,"signal-strenght",intval($signal),$operator="=");
$cont=0;
$sql="SELECT `id`,`ip` FROM `redesagi_facturacion`.`afiliados` WHERE `activo`='1' AND `eliminar`='0'   ORDER BY `id` ASC"; 
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
                    foreach ($UbiquitiCredentials as $row) {
                        $obj=new Ubiquiti($ipValue,$row["user"],$row["password"]);
                        $signal=0;
                        if($obj->status){   
                            $signal=$obj->getUbiquitiSignal();
                            print "id $id ---$time {$row["user"]} {$row["password"]} $ipValue: ok SIGNAL: $signal ";
                            if(intval($signal)*1!=0 && $signal!=null){
                                print $cont++."*";
                                print $clientObject->updateClient($id,"signal-strenght",intval($signal),$operator="=");
                                print $clientObject->updateClient($id,"ssh-login-type","ubiquiti",$operator="=");
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