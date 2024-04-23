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

if (file_exists("/var/www/ispexperts/Mkt.php")) {
    require("/var/www/ispexperts/Mkt.php");
} else {
    require("/home/omar/docker-work-area/go/ispweb/Mkt.php");
}


$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
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

$MikrotikCredentials[]=["user"=>"ubnt","password"=>"ubnt"];
$MikrotikCredentials[]=["user"=>"agingenieria","password"=>"agwist2017"];
$MikrotikCredentials[]=["user"=>"admin","password"=>"agwist2017"];
$MikrotikCredentials[]=["user"=>"ubnt","password"=>"ubnt1234"];
$MikrotikCredentials[]=["user"=>"ubnt","password"=>"-Agwist2017"];
$clientObject=new Client($server, $db_user, $db_pwd, $db_name);


$sql="SELECT `id`,`ip` FROM `redesagi_facturacion`.`afiliados` WHERE `activo`='1' AND `eliminar`='0'     ORDER BY `id` DESC";
if($result=$mysqli->query($sql)){
    while($row=$result->fetch_assoc()){
        print "\n Tryng ping: id:{$row["id"]} ip:{$row["ip"]} \n";
        $id=$row["id"];
        $ipValue=$row["ip"];
        if (filter_var($ipValue, FILTER_VALIDATE_IP)) {
            $pingObj=new PingTime($ipValue);
            if($time=$pingObj->time()){
                print "Response:$ipValue $time ms \n";
                $port=22;
                print "Opnening SSH... \n";
                $connection = @fsockopen($ipValue, $port,$errno, $errstr, 8);//last parameter is timeout 
                if (is_resource($connection)){
                    print $ipValue . ':' . $port . ' ' . '(' . getservbyport($port, 'tcp') . ') is open.' . "\n";
                    fclose($connection);
                    foreach ($MikrotikCredentials as $row) {
                        print"\n\t\tMkt($ipValue,$row[user],$row[password]);";
                        $obj=new Mkt($ipValue,$row["user"],$row["password"]); 
                        $signal=0;
                        if($obj->success){   
                            $signal=explode("@",$obj->checkSignal())[0];
                            print "Resume: id $id ---time: $time ms  user:{$row["user"]} pass:{$row["password"]} ip: $ipValue:  SIGNAL: $signal \n\n\n";
                            if(intval($signal)){
                                print $clientObject->updateClient($id,"signal-strenght",intval($signal),$operator="=");
                                print $clientObject->updateClient($id,"ssh-login-type","mikrotik",$operator="=");
                                break;
                            }
                        }
                    }
                }else{
                    print "$ipValue  is not responding to SSH. \n";
                }
            }else{
                $clientObject->updateClient($id,"ping",null,$operator="=");
            }

        }
    }
}






?>