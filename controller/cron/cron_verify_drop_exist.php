<?php
include("/var/www/ispexperts/login/db.php"); 
require '/var/www/ispexperts/Mkt.php'; 
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    print "Failed to connect to MySQL: " . $mysqli->connect_error;
}
mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");
$convertdate = date("d-m-Y", strtotime($today));
$hourMin = date('H:i');
$file = '/var/www/ispexperts/controller/cron/logs_drop_morosos.txt';
$content = "\n--- Ejecución: " . date("Y-m-d H:i:s") . " ---\n";
$user="aws";
$idEmpresa=1;//AG INGENIERIA GUAMAL-CASTILLA
$groupArray=[];
$mkobj=[];
$sql="SELECT * FROM `vpn_targets` WHERE  `active`= 1 AND `id-empresa`= $idEmpresa ";
if($rs=$mysqli->query($sql)){
    while($row=$rs->fetch_assoc()){
        $serverIp=$row["server-ip"];
        $serverName=$row["server-name"];
        $username=$row["username"];
        $password=$row["password"];
        $groupId=$row["id-repeater-subnet-group"];
        $mkobj[$groupId]=new Mkt($serverIp,$username,$password);
        if($mkobj[$groupId]->success){
            // To check and create the rule if it doesn't exist
            $result = $mkobj[$groupId]->checkOrCreateMorososRule(true);
            if (isset($result['error'])) {
                echo "Ocurrió un error: " . $result['error'];
            } else {
                if (isset($result['duplicatesRemoved'])) {
                    echo "Se eliminaron $serverIp $serverName " . $result['duplicatesRemoved'] . " reglas duplicadas. \n";
                }
                
                if ($result['exists']) {
                    echo "La regla ya existía $serverIp $serverName. \n";
                } elseif ($result['created']) {
                    echo "Se creó la regla con éxito $serverIp $serverName.\n";
                    $content .= "\n$today  $hourMin  server $serverIp $serverName $groupId  Se creó la regla drop con éxito ";
                } else {
                    echo "La regla no existe y no se pudo crear.\n";
                }
            }  
        }else {
            $groupArray+=array("$groupId"=>false);
            // print "$serverIp $serverName $groupId $groupId grupo connwxion invalido! \n";
        }
    }
    $rs->free();
}

$content .= PHP_EOL;
file_put_contents($file, $content, FILE_APPEND | LOCK_EX);  

 
?>