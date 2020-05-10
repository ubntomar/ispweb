<?php
$debug=false;
include("login/db.php");
require 'Mkt.php'; 
require 'vpnConfig.php';
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");
$convertdate = date("d-m-Y", strtotime($today));
$hourMin = date('H:i');
$pass=true;
$user="aws";
if (true) {   
    if($debug)
        $idarray[]=363;//Hernando Monguí  
    else{
        $sql="select `id` FROM `afiliados` WHERE  `shutoffpending`='1' and `suspender`='1' ";
        if($rt=$mysqli->query($sql)){
            if($rt->num_rows){
                while($row=$rt->fetch_assoc()){
                    $idarray[]=$row['id'];
                }
                $str="";     
                if($mkobj=new Mkt($serverIpAddressArea1,$vpnUser,$vpnPassword)){
                    echo "\nConectado a la Rboard cod Server-target-> 1:$serverIpAddressArea1\n";
                    $pass=true;        
                }
                else {
                    echo "$today-$hourMin: No fue posible conectar a la Rboard 1:$serverIpAddressArea1 \n";      
                }
                if($mkobj2=new Mkt($serverIpAddressArea2,$vpnUser,$vpnPassword)){
                    echo "\nConectado a la Rboard cod Server-target-> 2:$serverIpAddressArea2\n";
                    $pass=true;        
                }
                else {
                    echo "$today-$hourMin: No fue posible conectar a la Rboard 2:$serverIpAddressArea2 \n";        
                }
                foreach ($idarray as $id) {
                    $sql_client_telefono = "select * from redesagi_facturacion.afiliados where `id`=$id ";
                    $result = mysqli_query($mysqli, $sql_client_telefono) or die('error encontrando el cliente');
                    $db_field = mysqli_fetch_assoc($result);
                    $ip=$db_field['ip'];
                    $nombre=$db_field['cliente'];
                    $clientAreaCode=$db_field["id_client_area"]; 
                    print "\n Ip que vamos a gregar:".$ip."\n";         
                    $str.=$ip;
                    if (next($idarray)==true) $str .= ","; 
                    $sqlinsert="insert into redesagi_facturacion.service_shut_off (id,tipo,fecha,hora,status,user,ip,id_client) values (null,5,'$today','$hourMin','ok','$user','$ip',$id)";
                    mysqli_query($mysqli,$sqlinsert) or die('error ingresando a suspendidos tb');
                    if($clientAreaCode==$area1Cod){
                        removeIp($mkobj->remove_ip($ip,'permitidos'),$ip,$today,$hourMin);
                        addIP($mkobj->add_address($ip,'morosos','idUserNumber:'.$id,$nombre),$id,$mysqli,$today,$ip,$hourMin);
                    }
                    if($clientAreaCode==$area2Cod){
                        removeIp($mkobj2->remove_ip($ip,'permitidos'),$ip,$today,$hourMin);
                        try {
                            addIP($mkobj2->add_address($ip,'morosos','idUserNumber:'.$id,$nombre),$id,$mysqli,$today,$ip,$hourMin);
                        }
                        catch (Exception $e){
                            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                        }
                    }
                }
            }
            else{
                echo "\n $today : $hourMin * No hay clientes para cortar en  este momento\n";
                $pass=false;
            }
        }   
        
    }
     
}
function removeIp($remove,$ip,$today,$hourMin){
    if($remove==1){
       echo "$today-$hourMin: Ip: $ip removida con éxito\n";
    }
    if($remove==2){
        echo "$today-$hourMin: Dirección Ip $ip o Lista 'permitidos' no existe! ..procedemos a agregar a 'morosos' !\n";
    }     
} 

function addIp($response,$idClient,$mysqli,$today,$ip,$hourMin){
    if($response==1){
       echo "$today-$hourMin: Ip $ip agregada a suspendidos con éxito\n";
        $sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`suspender`='1' , `afiliados`.`shutoffpending`='0' , `afiliados`.`suspenderFecha`='$today'  WHERE `afiliados`.`id`='$idClient'";
        if($result2 = $mysqli->query($sqlUpd)){						
        }
        else{
            echo "\nError al actualizar cliente Mysql `shutoffpending`=0\n";	
        }	
    }
    elseif($response==2){
        echo "\n $today-$hourMin: Problemas al ingresar la Ip $ip a la Rboard\n";
        $sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`suspender`='1' , `afiliados`.`shutoffpending`='1'  WHERE `afiliados`.`id`='$idClient'";
        if($result2 = $mysqli->query($sqlUpd)){					
        }
        else{
            echo "\nError al actualizar cliente Mysql `shutoffpending`=1\n";	
        }
    }
    elseif($response==3){
        echo "\n $today-$hourMin: $idClient:Esa Ip $ip ya se encuentra en la lista de morosos!\n";
    }
}
