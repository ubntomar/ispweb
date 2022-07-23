<?php
session_start();
$debug=false;
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
            if(!$debug){
                header('Location: login/index.php');
                exit;
        }
		}
else    {
		$user=$_SESSION['username'];
		}
require ("vpnConfig.php");    
include("PingTime.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($debug) {
    $mainServerIp="192.168.17.1";
    $from="192.168.17.150";
    // $to  ="192.168.16.254";
    $byteToChange=3;
    $ipsToDiscovery=1;
}
else{
    $mainServerIp=mysqli_real_escape_string($mysqli, $_REQUEST['mainServerIp']);
    $from=mysqli_real_escape_string($mysqli, $_REQUEST['from']);
    // $to  =mysqli_real_escape_string($mysqli, $_REQUEST['to']);
    $byteToChange=mysqli_real_escape_string($mysqli,$_REQUEST['byteToChange']);
    $ipsToDiscovery=mysqli_real_escape_string($mysqli,$_REQUEST['ipsToDiscovery']);
}
$ipParts=explode(".",$from);
$valueBytetoChange=$ipParts[$byteToChange];
$ipList =array();
if(($ipsToDiscovery+$valueBytetoChange)>254)$ipsToDiscovery=254-$valueBytetoChange;//ipsToDiscovery is adjusted to no exced 254 in the fourth byte into( x.x.x.x)
$device= new PingTime($mainServerIp);  
if($device->time()){
    
    $counter=0;
    $cont=-1;
    while($counter<$ipsToDiscovery  || ($valueBytetoChange+$counter>=254)  ) {
        $counter++;
        $cont++;
        $fileIpMatch=0;
        $ipParts[$byteToChange]=$cont+$valueBytetoChange; 
        $dotSeparated=implode(".",$ipParts);
        $sql="SELECT `id` FROM `redesagi_facturacion`.`afiliados` WHERE  `eliminar`='0' AND `activo`='1'   and `ip` like '$dotSeparated' ";
        $rt=$mysqli->query($sql);
        $sqlRepeaters="SELECT `id` FROM `redesagi_facturacion`.`vpn_targets` WHERE  `server-ip` like '$dotSeparated' ";
        $rtRepeaters=$mysqli->query($sqlRepeaters);

        if(ipInFile($dotSeparated)){            
            $pingResponse=true;
            $fileIpMatch=1;
        }
        else{
            $device2= new PingTime($dotSeparated);
            $pingResponse = ($device2->time()) ?  true :  false ;
        } 

        if($rt->num_rows) $pingResponse=true;      
        if($rtRepeaters->num_rows) $pingResponse=true;      

        if ($pingResponse) {
            $DefaultJson="{}";
            $fileJsonString= file_get_contents("ipAlive.json");
            if(!$fileJsonString)$fileJsonString=$DefaultJson;
            $filePhpObject=json_decode($fileJsonString,true);
            $oneTimesave=0;
            if($filePhpObject){
                foreach ($filePhpObject as  $value) {
                    if($value==$dotSeparated){
                        $oneTimesave=0;
                    break;
                    }else{
                        $oneTimesave=1;                        
                    }
                }
                if($oneTimesave){
                    array_push($filePhpObject,$dotSeparated);
                    $jsonData=json_encode($filePhpObject);
                    file_put_contents('ipAlive.json',$jsonData);  
                }
            }
            else{
                array_push($filePhpObject,$dotSeparated);
                $jsonData=json_encode($filePhpObject);
                file_put_contents('ipAlive.json',$jsonData); 
            }
        }
               
        if( !$pingResponse && !$rt->num_rows && !$fileIpMatch ){
            array_push($ipList,$dotSeparated); 
        }else{
            $counter-=1;
        } 
    }
    echo json_encode($ipList);
}
else{
    array_push($ipList,"Error=>vpn?server?"); 
    echo json_encode($ipList);             
}

function ipInFile($ip){
    $response=false;
    $DefaultJson="{}";
    $fileJsonString= file_get_contents("ipAlive.json");
    if(!$fileJsonString)$fileJsonString=$DefaultJson;
    $filePhpObject=json_decode($fileJsonString,true);
    if($filePhpObject){
        foreach ($filePhpObject as  $value) {
            if($value==$ip){
                $response= true;
            }
            
        }
    }
    return $response;     
}

?>