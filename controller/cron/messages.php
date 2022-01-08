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
//$data;
//SMS to client 1-7 every month
$sql="SELECT * FROM `redesagi_facturacion`.`afiliados` where (  `corte` = '1' and `suspender` = '0' and  `activo` = '1' and `eliminar` = '0' ) ";
// if($result=$mysqli->query($sql)){
//     while($row=$result->fetch_assoc()){
//         $id=$row["id"];
//         $telefono=$row["telefono"];
//         $sqlSaldo="SELECT SUM(saldo) saldo,valorf FROM `redesagi_facturacion`.`factura` where `id-afiliado`='$id' and `cerrado`='0' ;";   
//         //echo $sqlSaldo;      
//         if ($resultsaldo = $mysqli->query($sqlSaldo)) {
//             $rowsaldo = $resultsaldo->fetch_assoc();
//             $saldo=$rowsaldo["saldo"];  
//             $valor_factura=$rowsaldo["valorf"];
//             $resultsaldo->free();
//             }
//         if (( ($saldo>0) && ($day> ( $row['corte'] +$dias_plazo) )) || ( ($day<=($row['corte']+$dias_plazo))&&($saldo>$valor_factura) )) {
//             //$cont++; 
//             //print "\n ROW $cont $id {$row["cliente"]} {$row["apellido"]} SALDO $saldo VALOR FACTURA $valor_factura TELEFONO {$row["telefono"]} \n";
//             $data[]=["idClient"=>"$id","phone"=>"$telefono"];
//         }
//     }
//     $result->free();
// }







//SMS to client 15-20 every month
$sql="SELECT * FROM redesagi_facturacion.afiliados where (  `corte` = '1' and `suspender` = '0' and  `activo` = '15' and `eliminar` = '0' ";

?>