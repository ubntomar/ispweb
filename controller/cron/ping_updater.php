<?php 



if (file_exists("/var/www/ispexperts/PingTime.php")) {
    require("/var/www/ispexperts/PingTime.php");
} else {
    require("/home/omar/docker-work-area/go/ispweb/PingTime.php");
}

if (file_exists("/var/www/ispexperts/CheckDevice.php")) {
    require("/var/www/ispexperts/CheckDevice.php");
} else {
    require("/home/omar/docker-work-area/go/ispweb/CheckDevice.php");
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
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$responseTime=0;
$sqlSearch="SELECT * FROM `afiliados` WHERE  `eliminar`=0 AND `activo`=1  ORDER BY `id` DESC";
if ($result = $mysqli->query($sqlSearch)) {
    while($row = $result->fetch_assoc()) {
        $signal=0;
        $id=$row["id"];
        $ipAddress=$row['ip'];
        $currentPingDateInAfiliadosTable=$row['pingDate'];
        print"\n Ping id:$id  ip $ipAddress  last date for registered ping time :  $currentPingDateInAfiliadosTable";
        $device=new CheckDevice();
        if($device->ping($ipAddress)){
            $timeObj=new PingTime($ipAddress);
            if($responseTime=$timeObj->time()){
                if($responseTime>0){
                    print "\tPing time response NOW: $responseTime ms\n";
                    $sqlupdate="UPDATE `redesagi_facturacion`.`afiliados` SET `ping` = '$responseTime' , `pingDate` = '$today'  WHERE (`id` = '$id');";
                }elseif ($currentPingDateInAfiliadosTable) {
                    $sqlupdate="UPDATE `redesagi_facturacion`.`afiliados` SET `ping` = ''   WHERE (`id` = '$id')";
                    print "\nDont touch pingDate";
                    }else{
                        $sqlupdate="UPDATE `redesagi_facturacion`.`afiliados` SET `ping` = '' , `pingDate` = null  WHERE (`id` = '$id')";
                        print "\npingDate and ping to NULL";
                    }
                print " $sqlupdate \n\n\n";
                $mysqli->query($sqlupdate);

            } 
        }
    }
}
echo "Script ping updater ejecutado: $today::$hourMin\n";
?>