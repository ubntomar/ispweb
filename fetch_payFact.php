<?php
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: login/index.php');
		exit;
		}
else    {
		$user=$_SESSION['username'];
		}
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$usuario=$_SESSION['username'];

$debug=0;
if(($_POST['vap']>=0)||($debug==1)) {//paga todo	//paga todo   //paga todo   //paga todo   //paga todo  //paga todo			
		if($debug!=1){													
			$idc = mysqli_real_escape_string($mysqli, $_REQUEST['idc']);
			$vap = mysqli_real_escape_string($mysqli, $_REQUEST['vap']);
			$vaa = mysqli_real_escape_string($mysqli, $_REQUEST['vaa']);
			$vre = mysqli_real_escape_string($mysqli, $_REQUEST['vre']);
			$cam = mysqli_real_escape_string($mysqli, $_REQUEST['cam']);
			}
		else{
			$idc=11;
			$vap=90000;
			$vaa=0;
			}	
		$sqlins="INSERT INTO `redesagi_facturacion`.`transacciones` (`idtransaccion`, `valor-recibido`, `valor-a-pagar`, `cambio`, `id-cliente`, `fecha`, `aprobado`, `hora`, `cajero`) VALUES (NULL, '$vre', '$vap', '$cam', '$idc', '$today', '', '$hourMin', '$usuario')";
		if ($mysqli->query($sqlins) === TRUE) {
		    $last_id_tra = $mysqli->insert_id;		    
		} 
		else {
		    echo "Error: " . $sqlins . "<br>" . $conn->error;
		}
		$sql = "SELECT * FROM `factura`  WHERE `factura`.`id-afiliado`='$idc' AND `factura`.`cerrado`='0'  ORDER BY `factura`.`id-factura` ASC";
		    $vtotal=0;
		    $row_cnt=0;		   
		    if ($result = $mysqli->query($sql)) {
		    	$row_cnt = $result->num_rows;
		    	$cnt=0;
				while ($rowf = $result->fetch_assoc()) {
					$cnt+=1;	
					$idFactura=$rowf["id-factura"];
					$periodo=$rowf["periodo"];
					$saldo=$rowf["saldo"];
					$vtotal+=$saldo;
					if($vap!=0)
						{//Insert Recaudo x todas factura
						$sqlin="INSERT INTO `redesagi_facturacion`.`recaudo` (`idrecaudo`, `idfactura`, `fecha-hoy`, `hora`, `notas`, `valorp`, `abonar`, `vendedor`) VALUES (NULL, '$idFactura', '$today', '$hourMin', 'nota', '$saldo', '0', '$usuario');";
						//Update todas factura
						$sqlup="UPDATE `redesagi_facturacion`.`factura` SET `saldo` = '0', `cerrado` = '1', `fecha-pago` = '$today', `fecha-cierre` = '$today', `vencidos` = '0' WHERE `factura`.`id-factura` = $idFactura ";			
						if ($mysqli->query($sqlin) === TRUE) {
							    if ($mysqli->query($sqlup) === TRUE){							    		
							    		if ($cnt==$row_cnt) 
							    			echo "Pago realizado con exito!/".$last_id_tra;							    		
							    		else
							    			echo "";
							    	} else{
							    		echo "Error Recaudo:".$sqlin."<br>".$mysqli->error;	
							    		}						    
								} 	
								else {
							    	echo "Error Factura: ".$sqlup."<br>".$mysqli->error;
								}	
						$vap-=$saldo;	
						}	
				}
					
			    	$result->free();
			} 		
 		}	
if(($_POST['vap']<0)||($debug==2)){//abonar //abonar //abonar //abonar //abonar //abonar //abonar //abonar //abonar //abonar 
	if($debug!=2)
		{
		$idc = mysqli_real_escape_string($mysqli, $_REQUEST['idc']);
		$vaa = mysqli_real_escape_string($mysqli, $_REQUEST['vaa']);
		$vre = mysqli_real_escape_string($mysqli, $_REQUEST['vre']);
		$cam = mysqli_real_escape_string($mysqli, $_REQUEST['cam']);
	}
	if($debug==2){
		$idc=11;
		$vaa=60000;		
	}	
	$sqlins="INSERT INTO `redesagi_facturacion`.`transacciones` (`idtransaccion`, `valor-recibido`, `valor-a-pagar`, `cambio`, `id-cliente`, `fecha`, `aprobado`, `hora`, `cajero`) VALUES (NULL, '$vre', '$vaa', '$cam', '$idc', '$today', '', '$hourMin', '$usuario')";
		
	if ($mysqli->query($sqlins) === TRUE) {
	    $last_id_tra = $mysqli->insert_id;		    
	} 
	else {
	    echo "Error: " . $sqlins . "<br>" . $conn->error;
	}
		
	$sql = "SELECT * FROM `factura`  WHERE `factura`.`id-afiliado`='$idc' AND `factura`.`cerrado`='0'  ORDER BY `factura`.`id-factura` ASC ";	
    $vtotal=0;		   
    $cont=0;
    $row_cnt=0;
    if ($result = $mysqli->query($sql)) {
    	$row_cnt = $result->num_rows;
    	$x=0;
    	$cnt=0;
		while ($rowf = $result->fetch_assoc()) 
		{	
			$cnt+=1;
			$cont+=1;
			$idFactura=$rowf["id-factura"];
			$periodo=$rowf["periodo"];
			$saldo=$rowf["saldo"];//abono/deuda.
			$vtotal+=$saldo;
			$cerrarAbono=0;
			$paga1fact=-1;
			$fechaCierreFact="0000/00/00";
			$cierreFact=0;
			$text="";
			if(($vaa==$saldo)&&($vaa!=0))
				$paga1fact=1;
			if(($vaa!=$saldo)&&($vaa!=0)) 
				$paga1fact=0;
			if($vaa>$saldo){
				$valorAbonar=$saldo;
				$newSaldoTotal1fact=0;
				$fechaCierreFact=$today;	
				$cierreFact=1;
				$text="Completo";
				}
			if(($vaa<$saldo)&&($vaa>0)){
				$valorAbonar=$vaa;
				$newSaldoTotal1fact=$saldo-$vaa;
				$cerrarAbono=1;
				$text="Parcial";
				}

			if($paga1fact==0){//pago parcial				
				$sqlin="INSERT INTO `redesagi_facturacion`.`recaudo` (`idrecaudo`, `idfactura`, `fecha-hoy`,`hora`, `notas`, `valorp`, `abonar`, `vendedor`) VALUES (NULL, '$idFactura', '$today','$hourMin', 'nota', '0', '$valorAbonar', '$usuario');";		
				$sqlup="UPDATE `redesagi_facturacion`.`factura` SET `saldo` = '$newSaldoTotal1fact', `cerrado` = '$cierreFact', `fecha-pago` = '$today', `fecha-cierre` = '$fechaCierreFact'  WHERE `factura`.`id-factura` = $idFactura ";	
				if ($mysqli->query($sqlin) === TRUE) {
					    if ($mysqli->query($sqlup) === TRUE){
					    		if ($cnt==$row_cnt) 
							    	echo "Pago realizado con exito!/".$last_id_tra;							    		
					    		else
					    			echo "";
					    	} else{
					    		echo "Error Recaudo:".$sqlin."<br>".$mysqli->error;	
					    		}						    
				} 	
				else {
			    	echo "Error Factura: ".$sqlup."<br>".$mysqli->error;
				}
				if($cerrarAbono==1)
					$vaa=0;
				else{
					$vaa-=$saldo;
					}
				
				}
			if($paga1fact==1){//pago 1 factura
				$vaa-=$saldo;
				$sqlin="INSERT INTO `redesagi_facturacion`.`recaudo` (`idrecaudo`, `idfactura`, `fecha-hoy`,`hora`, `notas`, `valorp`, `abonar`, `vendedor`) VALUES (NULL, '$idFactura', '$today','$hourMin', 'nota', '$saldo', '0', '$usuario');";
				$sqlup="UPDATE `redesagi_facturacion`.`factura` SET `saldo` = '0', `cerrado` = '1', `fecha-pago` = '$today', `fecha-cierre` = '$today', `vencidos` = '0' WHERE `factura`.`id-factura` = $idFactura ";
				if ($mysqli->query($sqlin) === TRUE) {
					    if ($mysqli->query($sqlup) === TRUE){
					    		if ($cnt==$row_cnt) 
					    			echo "Pago realizado con exito!/".$last_id_tra;						    		
					    		else
					    			echo "";
					    } else{
					    		echo "Error Recaudo:".$sqlin."<br>".$mysqli->error;	
					      }						    
				} 	
				else {
			    	echo "Error Factura: ".$sqlup."<br>".$mysqli->error;
				}
				
				}						
		}
	    	$result->free();
	} 
	else{
		echo "Error <br>".$mysqli->error;	
	}	 
}
 ?>