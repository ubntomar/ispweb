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
        $idarray[]=363; 
    else{
        $sql="select `id` FROM `afiliados` WHERE  `eliminar`=0 AND `activo`=1  AND `suspender`=1 ";  //pdte comprobar si debemos agregar shutoffpending=1
        if($rt=$mysqli->query($sql)){
            if($rt->num_rows){
                while($row=$rt->fetch_assoc()){
                    $idarray[]=$row['id'];
                }
                $str="";     

                $mkobjArea1=new Mkt($serverIpAddressArea1,$vpnUser,$vpnPassword);
                if($mkobjArea1->success){
                    echo "\nConectado a la Rboard cod Server-target-> 21.1:$serverIpAddressArea1\n";
                    $passArea1=true;        
                }else {
                    echo "\n $today-$hourMin: No fue posible conectar a la Rboard 21.1:$serverIpAddressArea1 \n";      
                }
                $mkobjArea2=new Mkt($serverIpAddressArea2,$vpnUser,$vpnPassword);    
                if($mkobjArea2->success){
                    echo "\nConectado a la Rboard cod Server-target-> 30.1:$serverIpAddressArea2\n";
                    $passArea2=true;        
                }else {
                    echo "\n $today-$hourMin: No fue posible conectar a la Rboard 30.1:$serverIpAddressArea2 \n";        
                }
                $mkobjArea3=new Mkt($serverIpAddressArea3,$vpnUser,$vpnPassword);
                if($mkobjArea3->success){
                    echo "\nConectado a la Rboard cod Server-target-> (alcaravan):$serverIpAddressArea3\n\n";
                    $passArea3=true;        
                }else {
                    echo "\n $today-$hourMin: No fue posible conectar a la Rboard (alcaravan):$serverIpAddressArea3 \n\n";        
                }
                $mkobjArea4=new Mkt($serverIpAddressArea4,$vpnUser,$vpnPassword);
                if($mkobjArea4->success){
                    echo "\nConectado a la Rboard cod Server-target-> (fruteria):$serverIpAddressArea4\n\n";
                    $passArea4=true;        
                }else {
                    echo "\n $today-$hourMin: No fue posible conectar a la Rboard (fruteria):$serverIpAddressArea4 \n";        
                }
                $mkobjArea5=new Mkt($serverIpAddressArea5,$vpnUser,$vpnPassword);
                if($mkobjArea5->success){
                    echo "\nConectado a la Rboard cod Server-target-> (Santa Ana 1):$serverIpAddressArea5\n";
                    $passArea5=true;        
                }else {
                    echo "\n $today-$hourMin: No fue posible conectar a la Rboard (Santa Ana 1):$serverIpAddressArea5 \n";        
                }
                $mkobjArea6=new Mkt($serverIpAddressArea6,$vpnUser,$vpnPassword);
                if($mkobjArea6->success){
                    echo "\nConectado a la Rboard cod Server-target-> (Sapitos):$serverIpAddressArea6\n";
                    $passArea6=true;        
                }else {
                    echo "\n $today-$hourMin: No fue posible conectar a la Rboard (Sapitos):$serverIpAddressArea6 \n";        
                }
                $mkobjArea7=new Mkt($serverIpAddressArea7,$vpnUser,$vpnPassword);
                if($mkobjArea7->success){
                    echo "\nConectado a la Rboard cod Server-target-> (Santa Ana 2):$serverIpAddressArea7\n";
                    $passArea7=true;        
                }else {
                    echo "\n $today-$hourMin: No fue posible conectar a la Rboard (Santa Ana 2):$serverIpAddressArea7 \n";        
                }
                $mkobjArea8=new Mkt($serverIpAddressArea8,$vpnUser,$vpnPassword);
                if($mkobjArea8->success){
                    echo "\nConectado a la Rboard cod Server-target-> (Costeños):$serverIpAddressArea8\n";
                    $passArea8=true;        
                }else {
                    echo "\n $today-$hourMin: No fue posible conectar a la Rboard (Costeños):$serverIpAddressArea8 \n";        
                }
                $mkobjArea9=new Mkt($serverIpAddressArea9,$vpnUser,$vpnPassword);
                if($mkobjArea9->success){
                    echo "\nConectado a la Rboard cod Server-target-> (Cacayal 1):$serverIpAddressArea9\n";
                    $passArea9=true;        
                }else {
                    echo "\n $today-$hourMin: No fue posible conectar a la Rboard (Cacayal 1):$serverIpAddressArea9 \n";        
                }
                $mkobjArea10=new Mkt($serverIpAddressArea10,$vpnUser,$vpnPassword);
                if($mkobjArea10->success){
                    echo "\nConectado a la Rboard cod Server-target-> (Torres Castilla):$serverIpAddressArea10\n";
                    $passArea10=true;        
                }else {
                    echo "\n $today-$hourMin: No fue posible conectar a la Rboard (Torres Castilla):$serverIpAddressArea10 \n";        
                }
                $mkobjArea11=new Mkt($serverIpAddressArea11,$vpnUser,$vpnPassword);
                if($mkobjArea11->success){
                    echo "\nConectado a la Rboard cod Server-target-> (Deyanira):$serverIpAddressArea11\n";
                    $passArea11=true;        
                }else {
                    echo "\n $today-$hourMin: No fue posible conectar a la Rboard (Deyanira):$serverIpAddressArea11 \n";        
                }
                $mkobjArea12=new Mkt($serverIpAddressArea12,$vpnUser,$vpnPassword);
                if($mkobjArea12->success){
                    echo "\nConectado a la Rboard cod Server-target-> (Cacayal 2):$serverIpAddressArea12\n";
                    $passArea12=true;        
                }else {
                    echo "\n $today-$hourMin: No fue posible conectar a la Rboard (Cacayal 2):$serverIpAddressArea12 \n";        
                }
                echo "\n//****************************************//**********************************//******************************";
                foreach ($idarray as $id) {
                    $sql_client_id = "select * from redesagi_facturacion.afiliados where `id`=$id ";
                    $result = mysqli_query($mysqli, $sql_client_id) or die('error encontrando el cliente');
                    $db_field = mysqli_fetch_assoc($result);
                    $ip=$db_field['ip'];
                    $nombre=$db_field['cliente'];
                    // $clientAreaCode=$db_field["id_client_area"]; 
                    $clientAreaCode=areaCode($ip);
                    print "\n Ip que vamos a gregar:".$ip."\n";         
                    $str.=$ip;
                    if (next($idarray)==true) $str .= ","; 
                    if( ($clientAreaCode==$area1Cod) && $passArea1 ){
                        removeIp($mkobjArea1->remove_ip($ip,'permitidos'),$ip,$today,$hourMin);
                        addIP($mkobjArea1->add_address($ip,'morosos','idUserNumber:'.$id,$nombre),$id,$mysqli,$today,$ip,$hourMin);
                    }
                    
                    if( ($clientAreaCode==$area2Cod) && $passArea2 ){
                        removeIp($mkobjArea2->remove_ip($ip,'permitidos'),$ip,$today,$hourMin);
                        try {
                            addIP($mkobjArea2->add_address($ip,'morosos','idUserNumber:'.$id,$nombre),$id,$mysqli,$today,$ip,$hourMin);
                        }
                        catch (Exception $e){
                            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                        }
                    }
                    if( ($clientAreaCode==$area3Cod) && $passArea3 ){
                        removeIp($mkobjArea3->remove_ip($ip,'permitidos'),$ip,$today,$hourMin);
                        try {
                            addIP($mkobjArea3->add_address($ip,'morosos','idUserNumber:'.$id,$nombre),$id,$mysqli,$today,$ip,$hourMin);
                        }
                        catch (Exception $e){
                            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                        }
                    }
                    if( ($clientAreaCode==$area4Cod) && $passArea4 ){
                        removeIp($mkobjArea4->remove_ip($ip,'permitidos'),$ip,$today,$hourMin);
                        try {
                            addIP($mkobjArea4->add_address($ip,'morosos','idUserNumber:'.$id,$nombre),$id,$mysqli,$today,$ip,$hourMin);
                        }
                        catch (Exception $e){
                            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                        }
                    }

                    if( ($clientAreaCode==$area5Cod) && $passArea5 ){
                        removeIp($mkobjArea5->remove_ip($ip,'permitidos'),$ip,$today,$hourMin);
                        try {
                            addIP($mkobjArea5->add_address($ip,'morosos','idUserNumber:'.$id,$nombre),$id,$mysqli,$today,$ip,$hourMin);
                        }
                        catch (Exception $e){
                            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                        }
                    }

                    if( ($clientAreaCode==$area6Cod) && $passArea6 ){
                        removeIp($mkobjArea6->remove_ip($ip,'permitidos'),$ip,$today,$hourMin);
                        try {
                            addIP($mkobjArea6->add_address($ip,'morosos','idUserNumber:'.$id,$nombre),$id,$mysqli,$today,$ip,$hourMin);
                        }
                        catch (Exception $e){
                            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                        }
                    }

                    if( ($clientAreaCode==$area7Cod) && $passArea7 ){
                        removeIp($mkobjArea7->remove_ip($ip,'permitidos'),$ip,$today,$hourMin);
                        try {
                            addIP($mkobjArea7->add_address($ip,'morosos','idUserNumber:'.$id,$nombre),$id,$mysqli,$today,$ip,$hourMin);
                        }
                        catch (Exception $e){
                            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                        }
                    }

                    if( ($clientAreaCode==$area8Cod) && $passArea8 ){
                        removeIp($mkobjArea8->remove_ip($ip,'permitidos'),$ip,$today,$hourMin);
                        try {
                            addIP($mkobjArea8->add_address($ip,'morosos','idUserNumber:'.$id,$nombre),$id,$mysqli,$today,$ip,$hourMin);
                        }
                        catch (Exception $e){
                            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                        }
                    }

                    if( ($clientAreaCode==$area9Cod) && $passArea9 ){
                        removeIp($mkobjArea9->remove_ip($ip,'permitidos'),$ip,$today,$hourMin);
                        try {
                            addIP($mkobjArea9->add_address($ip,'morosos','idUserNumber:'.$id,$nombre),$id,$mysqli,$today,$ip,$hourMin);
                        }
                        catch (Exception $e){
                            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                        }
                    }

                    if( ($clientAreaCode==$area10Cod) && $passArea10 ){
                        removeIp($mkobjArea10->remove_ip($ip,'permitidos'),$ip,$today,$hourMin);
                        try {
                            addIP($mkobjArea10->add_address($ip,'morosos','idUserNumber:'.$id,$nombre),$id,$mysqli,$today,$ip,$hourMin);
                        }
                        catch (Exception $e){
                            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                        }
                    }

                    if( ($clientAreaCode==$area11Cod) && $passArea11 ){
                        removeIp($mkobjArea11->remove_ip($ip,'permitidos'),$ip,$today,$hourMin);
                        try {
                            addIP($mkobjArea11->add_address($ip,'morosos','idUserNumber:'.$id,$nombre),$id,$mysqli,$today,$ip,$hourMin);
                        }
                        catch (Exception $e){
                            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                        }
                    }

                    if( ($clientAreaCode==$area12Cod) && $passArea12 ){
                        removeIp($mkobjArea12->remove_ip($ip,'permitidos'),$ip,$today,$hourMin);
                        try {
                            addIP($mkobjArea12->add_address($ip,'morosos','idUserNumber:'.$id,$nombre),$id,$mysqli,$today,$ip,$hourMin);
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
function areaCode($ip){
    $byte3=explode(".",$ip)[2];
    if($byte3){
        if( $byte3=='16' || $byte3=='17' || $byte3=='20' || $byte3=='21' || $byte3=='26' || $byte3=='40' || $byte3=='50' ) return "4324";
        if( $byte3=='30' ) return "4325";
        if( $byte3=='85' ) return "4326";
        if( $byte3=='79' ) return "4327";
        if( $byte3=='68' ) return "4328";
        if( $byte3=='76' ) return "4329";
        if( $byte3=='9' ) return "4330";
        if( $byte3=='73' ) return "4331";
        if( $byte3=='10' ) return "4332";
        if( $byte3=='18' ) return "4333";
        if( $byte3=='71' ) return "4334";
        if( $byte3=='11' ) return "4335";
        if( $byte3=='65' ) return "4336";
        if( $byte3=='74' ) return "4337";   
    }
    return '';
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