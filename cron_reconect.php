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
$idEmpresa=1;//AG INGENIERIA GUAMAL-CASTILLA
$groupArray=[];
$mkobj=[];
$sql="SELECT * FROM `vpn_targets` WHERE 1 AND `ACTIVE`= 1 AND `id-empresa`= $idEmpresa ";
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
            $groupArray+=array("$groupId"=>"true");
            print "$serverIp $serverName $groupId grupo connwxion valido \n";
        }else {
            $groupArray+=array("$groupId"=>"false");
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
            }
        }
    }
    else{
        print "\n\n\n\n $today : $hourMin * No hay clientes para cortar en  este momento\n";
    }
$rt->free();    
}   
    

function removeIpFromMorososList($remove,$ip,$today,$hourMin,$mysqli,$id){
    if($remove==1){
       print "\n\n\n$today-$hourMin: Ip: $ip removida con éxito\n\n\n\n";
       $sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`suspender`='0', `afiliados`.`shutoffpending`='0', `afiliados`.`reconectPending`='0', `afiliados`.`suspenderFecha`= NULL  WHERE `afiliados`.`id`='$id'";
    }
    if($remove==2){
        print "$today-$hourMin: Dirección Ip $ip o Lista  de morosos no existe! . !\n";
        $sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`suspender`='0', `afiliados`.`shutoffpending`='0', `afiliados`.`reconectPending`='0', `afiliados`.`suspenderFecha`= NULL  WHERE `afiliados`.`id`='$id'";
    }
    if(!$result2 = $mysqli->query($sqlUpd)){					
        print"-Error al actualizar cliente Mysql `shutoffpending`=1"; 	
    }
} 

