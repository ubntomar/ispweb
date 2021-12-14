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
    $mainServerIp="192.168.21.1";
    $from="192.168.16.1";
    // $to  ="192.168.16.254";
    $byteToChange=3;
    $rowNumbers=1;
}
else{
    $mainServerIp=mysqli_real_escape_string($mysqli, $_REQUEST['mainServerIp']);
    $from=mysqli_real_escape_string($mysqli, $_REQUEST['from']);
    // $to  =mysqli_real_escape_string($mysqli, $_REQUEST['to']);
    $byteToChange=mysqli_real_escape_string($mysqli,$_REQUEST['byteToChange']);
    $rowNumbers=mysqli_real_escape_string($mysqli,$_REQUEST['rowNumbers']);
}
$ipParts=explode(".",$from);
$valueBytetoChange=$ipParts[$byteToChange];
$ipList =array();
if(($rowNumbers+$valueBytetoChange)>254)$rowNumbers=254-$valueBytetoChange;
$device= new PingTime($mainServerIp);  
if($device->time()){
    
    $counter=0;
    $cont=-1;
    while($counter<$rowNumbers  || ($valueBytetoChange+$counter>=254)  ) {
        $counter++;
        $cont++;
        $fileIpMatch=0;
        $ipParts[$byteToChange]=$cont+$valueBytetoChange; 
        $dotSeparated=implode(".",$ipParts);
        $sql="select `id` FROM `afiliados` WHERE  `eliminar`=0 AND `activo`=1  AND `suspender`=0 and `ip` like '$dotSeparated' ";
        $rt=$mysqli->query($sql);
        if(ipInFile($dotSeparated)){
            $pingResponse=true;
        }
        else{
            $device2= new PingTime($dotSeparated);
            $pingResponse = ($device2->time()) ?  true :  false ;
        }        
        
        if ($pingResponse) {
            $DefaultJson="{}";
            $fileJsonString= file_get_contents("ipAlive.json");
            if($fileJsonString=="")$fileJsonString=$DefaultJson;
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
        }else{
            $DefaultJson="{}";
            $fileJsonString= file_get_contents("ipAlive.json");
            if($fileJsonString=="")$fileJsonString=$DefaultJson;
            $filePhpObject=json_decode($fileJsonString,true);
            if($filePhpObject){
                foreach ($filePhpObject as  $value) {
                    if($value==$dotSeparated){
                        $fileIpMatch=1;
                    break;
                }
                else{
                }
            }
        }
        else{
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
    array_push($ipList,"No server: response!");            
}

function ipInFile($ip){
    $DefaultJson="{}";
    $fileJsonString= file_get_contents("ipAlive.json");
    if($fileJsonString=="")$fileJsonString=$DefaultJson;
    $filePhpObject=json_decode($fileJsonString,true);
    if($filePhpObject){
        foreach ($filePhpObject as  $value) {
            if($value==$ip){
                return true;
            }
            else{
                
            }
        }
    }
    else{
    }
    return false;     
}

?>