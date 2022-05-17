<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
		header('Location: ../../login/index.php');
		exit;
	} else {
	$user = $_SESSION['username'];
	$idCajero = $_SESSION['idCajero'];
} 
include("../../login/db.php"); 
require '../../Mkt.php';
require '../../vpnConfig.php'; 
require '../../VpnUtils.php';
require '../Wallet.php'; 
require '../sms/Sms.php';  
require("../brand/Company.php");
require("../../Email.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");
$convertdate = date("d-m-Y", strtotime($today));
$hourMin = date('H:i');
$usuario = $_SESSION['username'];
$response="";
$descuento=0;//Mysql strict mode dont accept ''     // vap $vap  vaa $vaa vad $vad vpl $vpl
$idtransaccion=0;
$debug = 0;
									// idc: idcRow, 
									// vap: vapRow,
									// vaa: vaaRow,
									// vre: vreRow,
									// cam: cambioRow,
									// vad: vadRow,
									// vpl: vplanRow,
									// rec: rec?reconect? 
									// valorWallet:valorWallet

if($_POST["rec"]){
	$rec = mysqli_real_escape_string($mysqli, $_REQUEST['rec']);
	if($rec){
		$idc = mysqli_real_escape_string($mysqli, $_REQUEST['idc']);
		$sql_client_id = "select * from redesagi_facturacion.afiliados where `id`=$idc ";
		$result = mysqli_query($mysqli, $sql_client_id) or die('error encontrando el cliente');
		$db_field = mysqli_fetch_assoc($result);
		$ip=$db_field['ip'];
		//$data=areaCode($ip);
		$nombre=$db_field['cliente'];
		$vpnObject2=new VpnUtils($server, $db_user, $db_pwd, $db_name);  
        $idGroup=$vpnObject2->updateGroupId($idc,$ip); 
        $serverIp=$vpnObject2->getServerIp($idGroup); 
		$mkobj=new Mkt($serverIp,$vpnUser,$vpnPassword);
		if($mkobj->success){
			//echo "Conectado a la Rboard cod Server-target-> {{$data['server']}}";
			removeIp($mkobj->remove_ip($ip,'morosos'),$idc,$mysqli,$ip,$today,$hourMin);       
		}else {
			$txt= "-$today-$hourMin  No fue posible conectar a la Rboard 1, reconectar pendiente";
			$sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`suspender`='0' , `afiliados`.`shutoffpending`='0', `afiliados`.`reconectPending`='1'  WHERE `afiliados`.`id`= '$idc' ";
			if($result2 = $mysqli->query($sqlUpd)){					
			}else{
				$txt.= "-Error al actualizar cliente Mysql `shutoffpending`=1\n";	
			}
			file_put_contents('cut.log', $txt.PHP_EOL , FILE_APPEND );
		}
		
		
	} 
}
if (($_POST['vap'] >= 0) || ($debug == 1)) { //paga todo	//paga todo   //paga todo   //paga todo   //paga todo  //paga todo	 		
	if ($debug != 1) {
		$idc = mysqli_real_escape_string($mysqli, $_REQUEST['idc']);
		$vap = mysqli_real_escape_string($mysqli, $_REQUEST['vap']);
		$vaa = mysqli_real_escape_string($mysqli, $_REQUEST['vaa']);
		$vre = mysqli_real_escape_string($mysqli, $_REQUEST['vre']);
		$cam = mysqli_real_escape_string($mysqli, $_REQUEST['cam']);
		$vpl = mysqli_real_escape_string($mysqli, $_REQUEST['vpl']);
	} else {
		$idc = 11;
		$vap = 90000;
		$vaa = 0;
	}
	$sqlins = "INSERT INTO `redesagi_facturacion`.`transacciones` (`idtransaccion`, `valor-recibido`, `valor-a-pagar`, `cambio`, `id-cliente`, `fecha`, `aprobado`, `hora`, `cajero`) VALUES (NULL, '$vre', '$vap', '$cam', '$idc', '$today', '', '$hourMin', '$usuario')";
	if ($mysqli->query($sqlins) === TRUE) {
		$last_id_tra = $mysqli->insert_id;
	} else {
		$response= "Error: " . $sqlins . "<br>" . $conn->error;
	}
	$sql = "SELECT * FROM `factura`  WHERE `factura`.`id-afiliado`='$idc' AND `factura`.`cerrado`='0'  ORDER BY `factura`.`id-factura` ASC";
	$vtotal = 0;
	$row_cnt = 0;
	if ($result = $mysqli->query($sql)) {
		$row_cnt = $result->num_rows;
		$cnt = 0;
		while ($rowf = $result->fetch_assoc()) {
			$cnt += 1;
			$idFactura = $rowf["id-factura"];
			$periodo = $rowf["periodo"];
			$saldo = $rowf["saldo"];
			$vtotal += $saldo;
			if ($vap != 0) { //Insert Recaudo x todas factura
					$sqlin = "INSERT INTO `redesagi_facturacion`.`recaudo` (`idrecaudo`, `idfactura`, `fecha-hoy`, `hora`, `notas`, `valorp`, `abonar`, `vendedor`) VALUES (NULL, '$idFactura', '$today', '$hourMin', 'nota', '$saldo', '0', '$usuario');";
					//Update todas factura
					$sqlup = "UPDATE `redesagi_facturacion`.`factura` SET `saldo` = '0', `cerrado` = '1', `fecha-pago` = '$today', `fecha-cierre` = '$today', `vencidos` = '0', `valorp` = '$vpl', `descuento` = '0' WHERE `factura`.`id-factura` = $idFactura ";
					if ($mysqli->query($sqlin) === TRUE) {
						if ($mysqli->query($sqlup) === TRUE) {
							if ($cnt == $row_cnt)
							$response= "Pago realizado con exito!/" . $last_id_tra;
							else
							$response= "error en: $sqlup";
						} else {
							$response= "Error Recaudo:" . $sqlup . "<br>" . $mysqli->error;
						}
					} else {
						$response= "Error Factura: " . $sqlin . "<br>" . $mysqli->error;
					}
					$vap -= $saldo;
				}
		}

		$result->free();
	}
}
if (($_POST['vap'] < 0) || ($debug == 2)) { //abonar //abonar //abonar //abonar //abonar //abonar //abonar //abonar //abonar //abonar 
	if ($debug != 2) {
			$idc = mysqli_real_escape_string($mysqli, $_REQUEST['idc']);
			$vna = mysqli_real_escape_string($mysqli, $_REQUEST['vaa']);//Vna es valor a abonar antes de descontar
			$vre = mysqli_real_escape_string($mysqli, $_REQUEST['vre']);
			$cam = mysqli_real_escape_string($mysqli, $_REQUEST['cam']);
			$vad = mysqli_real_escape_string($mysqli, $_REQUEST['vad']);
			$vpl = mysqli_real_escape_string($mysqli, $_REQUEST['vpl']);
			if($vna=="") $vna=0;
		}

	if ($debug == 2) {
		$idc = 11;
		$vaa = 60000;
	}
	$descParcial=-1;
	if($vad!=0){
		$descontar=$vad;
		$numFactsAffectedTmp=$descontar/$vpl;
		//echo "descontar:$descontar y numFactsAffectedTmp: $numFactsAffectedTmp";
		$residuo=$descontar%$vpl;
		$numFactsAffected=0;
		$descCompletas=0;
		$descIncompletas=0;
		if($numFactsAffectedTmp<1){
			$numFactsAffected=1;
			$descIncompletas=1;
		}
		if($residuo==0){
			$numFactsAffected=$descontar/$vpl;
			$descCompletas=$numFactsAffected;
		}
		if($residuo>0){
			$numFactsAffected=intval($numFactsAffectedTmp)+1;
			$descCompletas=intval($numFactsAffectedTmp);
			$descIncompletas=1;
		}
		$descParcial=$descCompletas+$descIncompletas;
		//echo " \n completas:$descCompletas  incompletas: $descIncompletas y descuento parcial de facturas: $descParcial  (la suma de las dos anteriores!) \n";
		$vaa=$vna+$vad;
		$dctoTotal=$vad;
	}
	else{
		$vaa=$vna;
	}

	//echo "estoy en la 110: \n";
	$sqlins = "INSERT INTO `redesagi_facturacion`.`transacciones` (`idtransaccion`, `valor-recibido`, `valor-a-pagar`, `cambio`, `id-cliente`, `fecha`, `aprobado`, `hora`, `cajero`, `descontar`) VALUES (NULL, '$vre', '$vaa', '$cam', '$idc', '$today', '', '$hourMin', '$usuario', '$vad')";
	//echo "\n Transaccion: $sqlins \n";
	if ($mysqli->query($sqlins) === TRUE) {
		$last_id_tra = $mysqli->insert_id;
	} else {
		$response= "Error: " . $sqlins . "<br>" . $conn->error;
	}

	$sql = "SELECT * FROM `factura`  WHERE `factura`.`id-afiliado`='$idc' AND `factura`.`cerrado`='0'  ORDER BY `factura`.`id-factura` ASC ";
	$vtotal = 0;
	$cont = 0;
	$row_cnt = 0;
	if ($result = $mysqli->query($sql)) {
		$row_cnt = $result->num_rows;
		$x = 0;
		$cnt = 0;
		while ($rowf = $result->fetch_assoc()) {
				$cnt += 1;
				//echo "\n $cnt:**********************INICIO******************************************\n";
				$cont += 1;
				$idFactura = $rowf["id-factura"];
				$periodo = $rowf["periodo"];
				$saldo = $rowf["saldo"]; //abono/deuda.
				$vtotal += $saldo;
				$cerrarAbono = 0;
				$paga1fact = -1;
				$fechaCierreFact = NULL;
				$cierreFact = 0;
				$text = "";
				if (($vaa == $saldo) && ($vaa != 0))
					$paga1fact = 1;
				if (($vaa != $saldo) && ($vaa != 0))
					$paga1fact = 0;
				if ($vaa > $saldo) {
					$valorAbonar = $saldo;
					$newSaldoTotal1fact = 0;
					$fechaCierreFact = $today;
					$cierreFact = 1;
					$text = "Completo";
				}
				if (($vaa < $saldo) && ($vaa > 0)) {
					$valorAbonar = $vaa;
					$newSaldoTotal1fact = $saldo - $vaa;
					$cerrarAbono = 1;
					$text = "Parcial";
				}

				if ($paga1fact == 0) { //pago parcial
					//echo "\n**************PAGO PARCIAL*****************\n";	
					
					if($descParcial!=-1){
						if($descParcial==1 && $cnt==1 ){
							$descuento=$vad;
						}
						if($descParcial==1){
							$descuento=$dctoTotal;
							//echo "\n decuento parcal es igual a 1 y descuento vale :$descuento ";
						}
						if($descParcial>1){
							$descuento=$saldo;
							$dctoTotal-=$descuento;
							$descParcial-=1;
							//echo "\n descParcial>1 y descuento vale:$saldo ";  
						}
					}
		
					//echo "\n vad vale:$vad y la variable descuento vale:$descuento \n";

					$sqlin = "INSERT INTO `redesagi_facturacion`.`recaudo` (`idrecaudo`, `idfactura`, `fecha-hoy`,`hora`, `notas`, `valorp`, `abonar`, `vendedor`) VALUES (NULL, '$idFactura', '$today','$hourMin', 'nota', '0', '$valorAbonar', '$usuario');";
					$fc=$fechaCierreFact==NULL?'NULL':"'".$fechaCierreFact."'"; 
					$sqlup = "UPDATE `redesagi_facturacion`.`factura` SET `saldo` = '$newSaldoTotal1fact', `cerrado` = '$cierreFact', `fecha-pago` = '$today', `fecha-cierre`=$fc, `descuento` = '$descuento', `idtransaccion` = $last_id_tra  WHERE `factura`.`id-factura` = $idFactura ";
					//echo "\n : $sqlup";
					if ($mysqli->query($sqlin) === TRUE) {
						if ($mysqli->query($sqlup) === TRUE) {
							if ($cnt == $row_cnt)
							$response= "Pago realizado con exito!/" . $last_id_tra;
							else
								$response= "linea 248";
						} else {
							$response= "Error Recaudo:" . $sqlup . "<br>" . $mysqli->error;
						}
					} else {
						$response= "Error Factura: " . $sqlin . "<br>" . $mysqli->error;
					}
					if ($cerrarAbono == 1)
						$vaa = 0;
					else {
						$vaa -= $saldo;
					}
				}
				if ($paga1fact == 1) { //pago 1 factura
					//echo "\n**************PAGO 1 FACTURA*****************\n";						
					if($vad!=0) 
						$descontar=$saldo;
					else
						$descontar=0;		
					$vaa -= $saldo;
					$sqlin = "INSERT INTO `redesagi_facturacion`.`recaudo` (`idrecaudo`, `idfactura`, `fecha-hoy`,`hora`, `notas`, `valorp`, `abonar`, `vendedor`) VALUES (NULL, '$idFactura', '$today','$hourMin', 'nota', '$saldo', '0', '$usuario');";
					//echo "\n sqlin:$sqlin";
					if ($mysqli->query($sqlin) === TRUE) {
						$sqlup = "UPDATE `redesagi_facturacion`.`factura` SET `saldo` = '0', `cerrado` = '1', `fecha-pago` = '$today', `fecha-cierre` = '$today', `vencidos` = '0', `descuento` = '$saldo', `idtransaccion` = '$last_id_tra' WHERE `factura`.`id-factura` = $idFactura ";
						//echo "\n 199:$sqlup";
						if ($mysqli->query($sqlup) === TRUE) {
							if ($cnt == $row_cnt)
							$response="Pago realizado con exito!/" . $last_id_tra;
							else
								echo "";
						} else {
							$response="Error Recaudo:" . $sqlup . "<br>" . $mysqli->error;
						}
					} else {
						$response="Error Factura: " . $sqlin . "<br>" . $mysqli->error;
					}
				}
				//echo "\n *************************FIN***************************************\n";	 
			}
		$result->free();
	} else {
		echo "Error <br>" . $mysqli->error;
	}
}
if($_POST["valorWallet"]){
	$valorWallet = mysqli_real_escape_string($mysqli, $_REQUEST['valorWallet']);
	if($valorWallet){
		$idClient=mysqli_real_escape_string($mysqli, $_REQUEST['idc']);
		$wallet= new Wallet($server, $db_user, $db_pwd, $db_name);
		//create wallet action="add"
		$wallet->createWallet($idClient, $action="add", $value=$valorWallet, $date=$today, $hour=$hourMin, $idCajero, $source="registerPay.php", $comment="payFact.php");
		//update client
		$WalletMoneyTosave=($wallet->getClientItem($idClient,"wallet-money"))+$valorWallet;//wallet-id,wallet-money. 
		$wallet->updateClient($idClient, $param="wallet-money",$value=$WalletMoneyTosave);//primero debo saber cuanto hay disponible y luego sumar.
	}
}
/////SMS && EMAIL
$endPoint=$mailEndPoint;
$key=$smsKey;
$prefix=$prefixCode;//"+57"; 
$idClient=mysqli_real_escape_string($mysqli, $_REQUEST['idc']);
$walletObject=new Wallet($server, $db_user, $db_pwd, $db_name);
$companyObj=new Company($server, $db_user, $db_pwd, $db_name);
$smsObj=new Sms($server, $db_user, $db_pwd, $db_name);
$emailObj=new Email($endPoint);
//Update email from pyment modal
$emailInput=mysqli_real_escape_string($mysqli, $_REQUEST['emailInput']);
if(($emailObj->emailValidate($emailInput))){
	$walletObject->updateClient($idClient,$param="mail",$value=$emailInput,$operator="=");
}
// $email="ag.ingenieria.wist@gmail.com";
$fullName=$walletObject->getClientItem($idClient,$item="cliente")."  ".$walletObject->getClientItem($idClient,$item="apellido");
$companyName=$companyObj->getCompanyItem($idCompany=1,$item="nombre");
$companyAddress=$companyObj->getCompanyItem($idCompany=1,$item="direccion");
$message="Gracias por tu pago!. Sigue disfrutando del servicio. $companyName $companyAddress";
$telefono=$walletObject->getClientItem($idClient,$item="telefono");
$data[] =["idClient"=>$idClient,"phone"=>$telefono]; 
$sms= $smsObj->sendSms($data,$message,$key)["status"]; 
$email=$walletObject->getClientItem($idClient,$item="mail");
$emailRespone="el email NO es valido";
$responseEmail="";
if(($emailObj->emailValidate($email)) && $fullName){
	$emailRespone="el email si es valido!";
    if($responseEmail=$emailObj->emailAfterPayment($emailArray=[
        "fullName"=> $fullName,
        "template"=>$tokenToPaymentDone,
        "idClient"=>$idClient,
        "email"=>$email
        ])){ 
			$emailRespone=$responseEmail;   
		}
}
///////END/////// 
echo "res:".$response;//."response email:$responseEmail";//."response email:$responseEmail"  

//
function removeIp($remove,$idc,$mysqli,$ip,$today,$hourMin){      
    if($remove==1){
		//echo "Ip $ip removida con éxito de morosos $idc\n";
		$txt="$today-$hourMin Ip $ip removida con éxito $idc";
		$sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`suspender`='0', `afiliados`.`shutoffpending`='0', `afiliados`.`reconectPending`='0'   WHERE `afiliados`.`id`='$idc'";
		if($result2 = $mysqli->query($sqlUpd)){					
		}
		else{
			$txt.= "-Error al actualizar cliente Mysql `shutoffpending`=0";	
		}
		file_put_contents('cut.log', $txt.PHP_EOL , FILE_APPEND );
    }
    if($remove==2){
		//echo "Dirección Ip o Lista 'xxxxxx' no existe!$ip .. !\n";
		$txt="$today-$hourMin Dirección Ip o Lista 'morosos' no existe!$ip ..  $idc";
		$sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`suspender`='0', `afiliados`.`shutoffpending`='0', `afiliados`.`reconectPending`='1'  WHERE `afiliados`.`id`='$idc'";
		if($result2 = $mysqli->query($sqlUpd)){					
		}
		else{
			$txt.= "-Error al actualizar cliente Mysql `shutoffpending`=1";	
		}
		file_put_contents('cut.log', $txt.PHP_EOL , FILE_APPEND );
    }     
}




?>