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
        $sql="select `id` FROM `afiliados` WHERE  `reconectPending`='1' ";  //pdte comprobar si debemos agregar shutoffpending=1
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
                    //$clientAreaCode=areaCode($ip);
                    $data=areaCode($ip);
                    $clientAreaCode=$data['areaCode'];
                    echo "cliente $nombre q tien ip:$ip tiene areaCode: $clientAreaCode"; 
                    print "\n Ip que vamos a remover:".$ip."\n";         
                    $str.=$ip;
                    if (next($idarray)==true) $str .= ","; 
                    if( ($clientAreaCode==$area1Cod) && $passArea1 ){
                        removeIp($mkobjArea1->remove_ip($ip,'morosos'),$ip,$today,$hourMin,$mysqli,$id);
                    }
                    
                    if( ($clientAreaCode==$area2Cod) && $passArea2 ){
                        removeIp($mkobjArea2->remove_ip($ip,'morosos'),$ip,$today,$hourMin,$mysqli,$id);
                    }
                    if( ($clientAreaCode==$area3Cod) && $passArea3 ){
                        removeIp($mkobjArea3->remove_ip($ip,'morosos'),$ip,$today,$hourMin,$mysqli,$id);
                    }
                    if( ($clientAreaCode==$area4Cod) && $passArea4 ){
                        removeIp($mkobjArea4->remove_ip($ip,'morosos'),$ip,$today,$hourMin,$mysqli,$id);
                    }

                    if( ($clientAreaCode==$area5Cod) && $passArea5 ){
                        removeIp($mkobjArea5->remove_ip($ip,'morosos'),$ip,$today,$hourMin,$mysqli,$id);
                    }

                    if( ($clientAreaCode==$area6Cod) && $passArea6 ){
                        removeIp($mkobjArea6->remove_ip($ip,'morosos'),$ip,$today,$hourMin,$mysqli,$id);
                    }

                    if( ($clientAreaCode==$area7Cod) && $passArea7 ){
                        removeIp($mkobjArea7->remove_ip($ip,'morosos'),$ip,$today,$hourMin,$mysqli,$id);
                    }

                    if( ($clientAreaCode==$area8Cod) && $passArea8 ){
                        removeIp($mkobjArea8->remove_ip($ip,'morosos'),$ip,$today,$hourMin,$mysqli,$id);
                    }

                    if( ($clientAreaCode==$area9Cod) && $passArea9 ){
                        removeIp($mkobjArea9->remove_ip($ip,'morosos'),$ip,$today,$hourMin,$mysqli,$id);
                    }

                    if( ($clientAreaCode==$area10Cod) && $passArea10 ){
                        removeIp($mkobjArea10->remove_ip($ip,'morosos'),$ip,$today,$hourMin,$mysqli,$id);
                    }

                    if( ($clientAreaCode==$area11Cod) && $passArea11 ){
                        removeIp($mkobjArea11->remove_ip($ip,'morosos'),$ip,$today,$hourMin,$mysqli,$id);
                    }

                    if( ($clientAreaCode==$area12Cod) && $passArea12 ){
                        removeIp($mkobjArea12->remove_ip($ip,'morosos'),$ip,$today,$hourMin,$mysqli,$id);
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

function removeIp($remove,$ip,$today,$hourMin,$mysqli,$id){
    if($remove==1){
       echo "$today-$hourMin: Ip: $ip removida con éxito\n";
       $sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`suspender`='0', `afiliados`.`shutoffpending`='0', `afiliados`.`reconectPending`='0'  WHERE `afiliados`.`id`='$id'";
		if($result2 = $mysqli->query($sqlUpd)){					
		}
		else{
			$txt.= "-Error al actualizar cliente Mysql `shutoffpending`=1";	
		}
    }
    if($remove==2){
        echo "$today-$hourMin: Dirección Ip $ip o Lista  de morosos no existe! . !\n";
    }     
} 
