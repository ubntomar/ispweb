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
$sql="SELECT * FROM `redesagi_facturacion`.`afiliados` WHERE  `shutoffpending`= 1 AND `suspender`=1   AND `eliminar`=0 AND `id-repeater-subnets-group` != 0"; 
if($rt=$mysqli->query($sql)){
    if($rt->num_rows){
        while($row=$rt->fetch_assoc()){
            $id=$row['id'];
            $ip=$row["ip"];
            $nombre=$row["cliente"];
            $apellido=$row["apellido"];
            $direccion=$row["direccion"];
            $fecha=$today;
            $idGroup=$row["id-repeater-subnets-group"];
            print "\n{$row['cliente']} $id  idgrupo: $idGroup valor de groupArray {$groupArray[$idGroup]}\n";
            if( $groupArray[$idGroup] ){
                print "\n\n\n Agregar ip a lista 'morosos' $ip {$row['cliente']}";
                try {
                    addIP($mkobj[$idGroup]->add_address($ip,'morosos','idUserNumber:'.$id,$nombre,$apellido,$direccion,$fecha),$id,$mysqli,$today,$ip,$hourMin,$user,$id);//add_address($ip,$listName,$idUser,$nombre="",$apellido="",$direccion="",$fecha="")
                }
                catch (Exception $e){
                    echo 'Excepción capturada: '.$e->getMessage()."\n";
                }
            }
        }
    }
    else{
        print "\n\n\n\n $today : $hourMin * No hay clientes para cortar en  este momento\n";
    }
$rt->free();    
}   
    
function addIp($response,$idClient,$mysqli,$today,$ip,$hourMin,$user,$id){
    if($response==1){
       print "$today-$hourMin: Ip $ip agregada a morosos con éxito\n";
        $sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`suspender`='1' , `afiliados`.`shutoffpending`='0' , `afiliados`.`suspenderFecha`='$today'  WHERE `afiliados`.`id`='$idClient'";
        if(!$result2 = $mysqli->query($sqlUpd)){						
            print "\nError al actualizar cliente Mysql `shutoffpending`=0\n";	
        }
        $sqlinsert="insert into redesagi_facturacion.service_shut_off (id,tipo,fecha,hora,status,user,ip,id_client) values (null,5,'$today','$hourMin','ok','$user','$ip',$id)";
        if(!$result2x = $mysqli->query($sqlinsert)){						
            print "\nError al actualizar registro de clientes cortados!\n";	
        }

    }
    elseif($response==2){
        print "\n $today-$hourMin: Problemas al ingresar la Ip $ip a la Rboard\n";
        $sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`suspender`='1' , `afiliados`.`shutoffpending`='1'  WHERE `afiliados`.`id`='$idClient'";
        if(!$result2 = $mysqli->query($sqlUpd)){					
            print "\nError al actualizar cliente Mysql `shutoffpending`=1\n";	
        }
    }
    elseif($response==3){
        print "\n $today-$hourMin: $idClient:Esa Ip $ip ya se encuentra en la lista de morosos!\n";
        $sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`suspender`='1' , `afiliados`.`shutoffpending`='0' , `afiliados`.`suspenderFecha`='$today'  WHERE `afiliados`.`id`='$idClient'";
        if($result2 = $mysqli->query($sqlUpd)){						
            print "\nError al actualizar cliente Mysql `shutoffpending`=0\n";	
        }
       	
    }
}
 
?>