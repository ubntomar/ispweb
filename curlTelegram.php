<?php
include("PingTime.php");
include("networkMap.php");
include("login/db.php");

$ipAddress="181.60.60.55";
$pingObject=new PingTime($ipAddress);
if(!$pingObject->time(15)){
    $messageToTelegram="Alerta!  Ip publica $ipAddress de la red 32 no responde, mensaje enviado desde Aws Server. Revisar por favor!!";
    $stringFormatted = preg_replace('/\s+/', '\\t', $messageToTelegram);
    print "mensaje formateado: $stringFormatted";
    $curl="curl -X POST \"$telegramApi\" -d \"chat_id=$telegramChatid&text=$messageToTelegram\"";
    exec($curl,$result);
    print "\nmessage has been sent  to Telegram!";
}else{
    print "\n$ipAddress responde con normalidad!";
}

$jsonDecoded=json_decode($json,true);
foreach ($jsonDecoded as $map){
   foreach ($map as $item){
        foreach ($item as $ruta){
            foreach ($ruta as $items){
                foreach ($items as $list){
                    foreach ($list as $li){
                        print $li["ip"]."\n";
                        $ipAddress=$li["ip"]; 
                        $name=$li["name"]; 
                        if(sendTelegram($ipAddress,$name,15,$telegramApi,$telegramChatid)=="break")break;
                    }
                }
            }
        }
    }
}

function sendTelegram($ipAddress,$name,$time=15,$telegramApi,$telegramChatid){
    $pingObject=new PingTime($ipAddress);
    if(!$pingObject->time($time)){
        $ipAddress=($ipAddress=="192.168.21.1")? "$ipAddress (Interface Ether2 de CCR1009-8G-1S1s+ Deshabilitada?)" : "$ipAddress" ;
        $messageToTelegram="Alerta!  Ip  $ipAddress : $name no responde, mensaje enviado desde Aws Server. Revisar por favor!!";
        $stringFormatted = preg_replace('/\s+/', '\\t', $messageToTelegram);
        print "mensaje formateado: $stringFormatted";
        $curl="curl -X POST \"$telegramApi\" -d \"chat_id=$telegramChatid&text=$messageToTelegram\"";
        exec($curl,$result);
        print "\nmessage has been sent  to Telegram!";
        return "break";
    }else{
        print "\n$ipAddress responde con normalidad!";
    } 
}


?>