<?php
$response=exec("curl http://192.168.30.254/50");

$response=str_replace("'","\"","{".$response."}");


$jArr = json_decode($response,true);
var_dump($jArr);


$rele= $jArr["data"]["rele"];
$batteryVolts=$jArr["data"]["sensor1"];
$acDcVolt=$jArr["data"]["sensor2"];

?>

