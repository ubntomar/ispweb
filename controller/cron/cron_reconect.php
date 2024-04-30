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
$file = '/var/www/ispexperts/controller/cron/logs.txt';
$current = file_get_contents($file);
$current.="\nFilename:".basename(__FILE__, '.php')."  Date: $today $hourMin\n";
file_put_contents($file, $current);
print "\n".basename(__FILE__, '.php')." --archivo creado $today $hourMin!\n";
$groupArray=[];
$mkobj=[];
$sql="SELECT * FROM `vpn_targets` WHERE  `active`= 1 AND `eliminar`= 0 ";
if($rs=$mysqli->query($sql)){
    while($row=$rs->fetch_assoc()){
        $serverIp=$row["server-ip"];
        $serverName=$row["server-name"];
        $username=$row["username"];
        $password=$row["password"];
        $groupId=$row["id-repeater-subnet-group"];
        print "\n Starting width $serverIp ...\n";
        $mkobj[$groupId]=new Mkt($serverIp,$username,$password);
        if($mkobj[$groupId]->success){
            $groupArray+=array("$groupId"=>true);
            print "$serverIp $serverName $groupId grupo connwxion valido \n";
        }else {
            $groupArray+=array("$groupId"=>false);
            print "$serverIp $serverName $groupId $groupId grupo connwxion invalido! \n";
            //print "\n error:{$mkobj[$groupId]->error} \n";   
        }
    }
    $rs->free();
}
print "\n\n\n***********************************************************************************\n\n\n";
$sql="select * FROM `afiliados` WHERE  `reconectPending`='1' AND `eliminar`=0 AND `id-repeater-subnets-group` != 0"; 
if($rt=$mysqli->query($sql)){
    if($rt->num_rows){
        while($row=$rt->fetch_assoc()){
            $id=$row['id'];
            $ip=$row["ip"];
            $idGroup=$row["id-repeater-subnets-group"];
            print "\n{$row['cliente']} $id  idgrupo: $idGroup valor de groupArray {$groupArray[$idGroup]}\n";
            if( $groupArray[$idGroup] ){
                print "\n\n\n Remover ip $ip {$row['cliente']}";
                removeIpFromMorososList($mkobj[$idGroup]->remove_ip($ip,'morosos'),$ip,$today,$hourMin,$mysqli,$id);
            }else{
                $sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`reconected-date`=NULL  WHERE `afiliados`.`id`='$id'";
                //print "\n $sqlUpd \n";
                if(!$result2 = $mysqli->query($sqlUpd)){					
                    print"-Error al actualizar cliente Mysql"; 	
                }else{
                    print "\t `reconected-date`=NULL";
                }
            
            }
        }
    }
    else{
        print "\n\n\n\n $today : $hourMin * No hay clientes para reconectar en  este momento\n";
    }
$rt->free();    
}   
    
function removeIpFromMorososList($remove,$ip,$today,$hourMin,$mysqli,$id){
    if($remove==1){
       print "\n\n\n$today-$hourMin: Ip: $ip removida con éxito\n\n\n\n";
       $sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`suspender`='0',`afiliados`.`shutoff_order`='pending', `afiliados`.`shutoffpending`='0', `afiliados`.`reconectPending`='0', `afiliados`.`reconected-date`='$today', `afiliados`.`suspenderFecha`= NULL  WHERE `afiliados`.`id`='$id'";
    }
    if($remove==2){
        print "$today-$hourMin: Dirección Ip $ip o Lista  de morosos no existe! . !\n";
        $sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`suspender`='0', `afiliados`.`shutoffpending`='0', `afiliados`.`reconectPending`='0', `afiliados`.`suspenderFecha`= NULL  WHERE `afiliados`.`id`='$id'";
    }
    if(!$result2 = $mysqli->query($sqlUpd)){					
        print"-Error al actualizar cliente Mysql `shutoffpending`=1"; 	
    }
} 

