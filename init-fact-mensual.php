<?php
echo"arranca okg";
include("login/db.php");
include("Client.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota'); 
$clientObj=new Client($server, $db_user, $db_pwd, $db_name);

$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
$monthn = date("n");//****************** IMPORTANTE****Y REVISAR LOS STAND BY*****************************************----------
$periodo=$mes[1];// hoy 03   de Enero de 2022 aquí pongo el mes al que le voy a crear la tanda de facturas a todos los afiliados.  AND `suspender`!=1
$cont=0;																
$sql = "SELECT * FROM `afiliados` WHERE `mesenmora` != -1 AND `activo`=1  AND `eliminar`!=1  ORDER BY `id` ASC "; 
if ($result = $mysqli->query($sql)) {
	while ($row = $result->fetch_assoc()) {
		$standby=$row["standby"];
		$cont++;	
		if(($standby-1)<0){ //si vale 1 o 2 p.e no genera factura 
			/// 
			$idafiliado=$row["id"];
			$valorf=$row["pago"];
			$wallet=$row["wallet-money"];
			$valorp=0;
			$cerrado=0;
			$newWalletMoney=0;
			$fechaPago=$today; 
			$fechaCierre='null'; 
			$notas="";
			//El saldo ahora depende de lo que valga "wallet-money"
			if($wallet>0){
				if($wallet<$valorf){
					$valorp=$wallet;
					$saldo=$valorf-$wallet;
					$newWalletMoney=0;
					$notas="Se usan $wallet pesos de la billetera para abonar a la factura y queda un saldo de deuda inicial de $saldo pesos";
					$clientObj->updateClient($idafiliado,"wallet-money",$newWalletMoney);
					print "\nOJO ".$notas."\n";
				}else{
					$valorp=$valorf;
					$saldo=0;
					$cerrado=1;
					$notas="Se usa dinero de la bitterera y se paga esta factura y queda saldo a favor:$saldo para el mes siguiente"; 
					$fechaCierre=$today;
					//update TABLE afiliados set new value to wallet-money
					$newWalletMoney=$wallet-$valorf;
					$clientObj->updateClient($idafiliado,"wallet-money",$newWalletMoney);
					print "\nOJO clientObj->updateClient($idafiliado,'wallet-money',$newWalletMoney)\n"; 

				}
			}else{
				$saldo=$valorf;
			}
            $fc=$fechaCierre=='null'?$fechaCierre:"'$fechaCierre'";
			$sql1 = "INSERT INTO `redesagi_facturacion`.`factura` (`id-factura`, `id-afiliado`, `fecha-pago`, `iva`, `notas`, `descuento`, `valorf`, `valorp`, `saldo`, `cerrado`, `fecha-cierre`, `vencidos`, `periodo`) VALUES (NULL,'$idafiliado', '$fechaPago', '19', '$notas', '0', '$valorf', '$valorp', '$saldo', '$cerrado', $fc, '-10', '$periodo');";
			echo "<br>".$sql1."<br>";
			// $mysqli->query($sql1);        
		}else{
			$standby-=1;
			$clientObj->updateClient($idafiliado,"standby",$standby);
		}

		}
    	//$result->free();  //OJO UBICAR BIEN ESTE LINEA
	}
 echo "termina en :$cont";  

 ?> 