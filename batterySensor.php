<?php
$response=exec("curl http://192.168.30.254/50");
//$response=str_replace("'","\"","{".$response."}");
$jArr = json_decode($response,true);
var_dump($jArr);
$rele= $jArr["data"]["rele"];
$batteryVolts=$jArr["data"]["sensor1"];
$acDcVolt=$jArr["data"]["sensor2"]; 

if($rele=="1"){
    if(($batteryVolts<=12.6)){
        $messageToTelegram="Atencion! Respaldo activo con energia electrica a las baterias en el retiro. Voltaje actual de bateria: $batteryVolts.Voltaje de piloto AC:$acDcVolt  Revisar por favor!!";
        $stringFormatted = preg_replace('/\s+/', '\\t', $messageToTelegram);
        print "mensaje formateado: $stringFormatted";
        $curl="curl -X POST \"https://api.telegram.org/bot896101360:AAFy0Gs_8FET6EZBAEeTJ0qxf-GbLOWQ6Sg/sendMessage\" -d \"chat_id=-340818870&text=$messageToTelegram\"";
        exec($curl,$result);
        print "\nmessage has been sent  to Telegram!";

    }
}
if(($batteryVolts<=12) && ($batteryVolts>3)){ 
    $messageToTelegram="Alerta! Las baterias del retiro tienen muy poco voltaje:$batteryVolts y mirando el  voltaje de piloto AC muestra:$acDcVolt   Revisar por favor!!";
    $stringFormatted = preg_replace('/\s+/', '\\t', $messageToTelegram);
    print "mensaje formateado: $stringFormatted";
    $curl="curl -X POST \"https://api.telegram.org/bot896101360:AAFy0Gs_8FET6EZBAEeTJ0qxf-GbLOWQ6Sg/sendMessage\" -d \"chat_id=-340818870&text=$messageToTelegram\"";
    exec($curl,$result);
    print "\nmessage has been sent  to Telegram!";
}


?>
