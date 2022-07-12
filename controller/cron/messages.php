<?php 
require("/var/www/ispexperts/login/db.php");
require("/var/www/ispexperts/Client.php");
require("/var/www/ispexperts/controller/sms/Sms.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$month = date('n');
$year=date("Y");
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i'); 
$day=date('d');
$dias_plazo=5;
$cont=0;
$data[]=null;
$message=null;
$smsObj=new Sms($server, $db_user, $db_pwd, $db_name);
$smsApiKey=$smsKey;
$jsonArray=json_decode($SmsCronjsonCte1);
foreach ($jsonArray as $key => $value) {
	foreach($value as $list){
		$dayFromJson= explode("/",$list->fecha)[0];
		$montFromJson= explode("/",$list->fecha)[1];
		if($dayFromJson==$day&&$montFromJson==$month){
			print "\n Eche no joda, voy a enviar mensajes por que hoy es: {$list->fecha} y el mensaje es: {$list->message} \n";
			$message=$list->message;
			$sql="SELECT * FROM `redesagi_facturacion`.`afiliados` where (  `corte` = '1' and `suspender` = '0' and  `activo` = '1' and `eliminar` = '0' ) ";
			if($result=$mysqli->query($sql)){
			    while($row=$result->fetch_assoc()){
			        $id=$row["id"];
			        $telefono=$row["telefono"];
			        $sqlSaldo="SELECT SUM(saldo)  AS saldo,valorf FROM `redesagi_facturacion`.`factura` WHERE `id-afiliado`='$id' AND `cerrado`='0' GROUP BY `valorf`";   
			        //echo $sqlSaldo;      
			        if ($resultsaldo = $mysqli->query($sqlSaldo)) {
			            $rowsaldo = $resultsaldo->fetch_assoc();
			            $saldo=isset($rowsaldo["saldo"])?$rowsaldo["saldo"]:0;  
			            $valor_factura=isset($rowsaldo["valorf"])?$rowsaldo["valorf"]:0;
						if (( ($saldo>0) && ($day> ( $row['corte'] +$dias_plazo) )) || ( ($day<=($row['corte']+$dias_plazo))&&($saldo>$valor_factura) )) {
							$cont++; 
							// print "\n\n\n ROW $cont $id {$row["cliente"]} {$row["apellido"]} SALDO $saldo VALOR FACTURA $valor_factura TELEFONO {$row["telefono"]} \n";
							$data[]=["idClient"=>"$id","phone"=>"$telefono"];
						}
						$resultsaldo->free();
					}
			    }
			    $result->free();
				// var_dump($data); 
				$sms= $smsObj->sendSms($data,$message,$smsApiKey)["status"];
				print "\n$sms";
			}
		break;
		}else{
			print "nop";
		}
	}
}
$jsonArray=json_decode($SmsCronjsonCte15);
foreach ($jsonArray as $key => $value) {
	foreach($value as $list){
		$dayFromJson= explode("/",$list->fecha)[0];
		$montFromJson= explode("/",$list->fecha)[1];
		if($dayFromJson==$day&&$montFromJson==$month){
			print "\n Eche no joda, voy a enviar mensajes por que hoy es: {$list->fecha} y el mensaje es: {$list->message} \n";
			$message=$list->message;
			$sql="SELECT * FROM `redesagi_facturacion`.`afiliados` where (  `corte` = '15' and `suspender` = '0' and  `activo` = '1' and `eliminar` = '0' ) ";
			if($result=$mysqli->query($sql)){
			    while($row=$result->fetch_assoc()){
			        $id=$row["id"];
			        $telefono=$row["telefono"];
			        $sqlSaldo="SELECT SUM(saldo)  AS saldo,valorf FROM `redesagi_facturacion`.`factura` WHERE `id-afiliado`='$id' AND `cerrado`='0' GROUP BY `valorf`";   
			        //echo $sqlSaldo;      
			        if ($resultsaldo = $mysqli->query($sqlSaldo)) {
			            $rowsaldo = $resultsaldo->fetch_assoc();
			            $saldo=isset($rowsaldo["saldo"])?$rowsaldo["saldo"]:0;  
			            $valor_factura=isset($rowsaldo["valorf"])?$rowsaldo["valorf"]:0;
						if (( ($saldo>0) && ($day> ( $row['corte'] +$dias_plazo) )) || ( ($day<=($row['corte']+$dias_plazo))&&($saldo>$valor_factura) )) {
							$cont++; 
							// print "\n\n\n ROW $cont $id {$row["cliente"]} {$row["apellido"]} SALDO $saldo VALOR FACTURA $valor_factura TELEFONO {$row["telefono"]} \n";
							$data[]=["idClient"=>"$id","phone"=>"$telefono"];
						}
						$resultsaldo->free();
					}
			    }
			    $result->free();
				// var_dump($data); 
				$sms= $smsObj->sendSms($data,$message,$smsApiKey)["status"];
				print "\n$sms";
			}
		break;
		}else{
			print "-no-";
		}

	}
	
}
//
//SMS to client 15-20 every month
$sql="SELECT * FROM redesagi_facturacion.afiliados where (  `corte` = '1' and `suspender` = '0' and  `activo` = '15' and `eliminar` = '0' ";

?>