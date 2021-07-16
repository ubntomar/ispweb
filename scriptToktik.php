<?php
include("login/db.php");
require 'Mkt.php'; 
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    print "Failed to connect to MySQL: " . $mysqli->connect_error;
}
mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");
$convertdate = date("d-m-Y", strtotime($today));
$hourMin = date('H:i');
$user="aws";
$idEmpresa=1;//AG INGENIERIA GUAMAL-CASTILLA
$groupArray=[];
$mkobj=[];
$backupCode="
# Start Variables Definition
:local routerName [/system identity get name];
:local dateNow [/system clock get date];
:local timeNow [/system clock get time];
:local sendTo \"ispexperts.backup@gmail.com\";
:local subject \"\F0\9F\93\A6 BACKUP: [ \$routerName ] [ \$dateNow ]  \";
:local body \"Backup file attached in this Email\nDate: \$dateNow and Time \$timeNow \";
# End Variables Definition
# Start Main Script
# & Make Backup and Send Email
export file=\$routerName
:delay 3s 
:local fileToUpload \"\$routerName.rsc\";
/tool e-mail send to=\$sendTo body=\$body subject=\$subject file=\$fileToUpload
# End Main Script
:log info \"Daily backup script completed\"
";
$sql="SELECT * FROM `vpn_targets` WHERE  `active`= 1 AND `id-empresa`= $idEmpresa ";//AND `id`= 4341
if($rs=$mysqli->query($sql)){
    while($row=$rs->fetch_assoc()){
        print "\n***************************START*****************************************\n";
        $serverIp=$row["server-ip"];
        $serverName=$row["server-name"];
        $username=$row["username"];
        $password=$row["password"];
        $groupId=$row["id-repeater-subnet-group"];
        print "\n Starting width $serverIp ...\n";
        $mkobj[$groupId]=new Mkt($serverIp,$username,$password);
        if($mkobj[$groupId]->success){
            $groupArray+=array("$groupId"=>"true");
            print "$serverIp $serverName $groupId grupo connwxion valido \n";
            try {
                $mkobj[$groupId]->addScheduler($backupCode);
                print "\nSuccess Backup Script\n";
            } catch (\Throwable $th) {
                print "\nError al enviar script de backups. $serverIp $serverName $groupId\n";
            }
            try {
                $mkobj[$groupId]->addEmail();
                print "\nSuccess creating email params\n";
            } catch (\Throwable $th) {
                print "\nError al enviar Email. $serverIp $serverName $groupId\n";
            }
        }else {
            $groupArray+=array("$groupId"=>"false");
            print "$serverIp $serverName $groupId $groupId grupo connwxion invalido! \n";
            //print "\n error:{$mkobj[$groupId]->error} \n";   
        }
        print "\n****************************END****************************************\n";
    }
    $rs->free();
}
 
    

?>