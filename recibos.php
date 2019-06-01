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
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Generador de Recibos</title>
<style type="text/css" id="formato">
@media print { 			 
			 @page {
      				margin-left: 10mm;
  					margin-right: 6mm;
					margin-top: 2mm;
					margin-bottom: 9mm;
					}
			 }
.col1f1 {
	width: 23mm;
	height: 10mm;
	
}
.col2f1 {
	width: 5mm;
	height: 10mm;
}
.col3f1 {
	width: 20mm;
	height: 10mm;
}
.col4f1 {
	width: 45mm;
	height: 10mm;
}
.col5f1 {
	width: 24mm;
	height: 10mm;
}
.col6f1 {
	width: 23mm;
	height: 10mm;
}
.col7f1 {
	width: 23mm;
	height: 10mm;
}
.col8f1 {
	width: 20mm;
	height: 10mm;
}
.t2col1f1 {
	width: 23mm;
	height: 9mm;
	
}
.t2col2f1 {
	width: 5mm;
	height: 9mm;
}
.t2col3f1 {
	width: 20mm;
	height: 9mm;
}
.t2col4f1 {
	width: 45mm;
	height: 9mm;
}
.t2col5f1 {
	width: 24mm;
	height: 9mm;
}
.t2col6f1 {
	width: 23mm;
	height: 9mm;
}
.t2col7f1 {
	width: 23mm;
	height: 9mm;
}
.t2col8f1 {
	width: 20mm;
	height: 9mm;
}


.col1f2 {
	width: 23mm;
	height: 5mm;
	
}
.col2f2 {
	width: 5mm;
	height: 5mm;
}
.col3f2 {
	width: 20mm;
	height: 5mm;
}
.col4f2 {
	width: 45mm;
	height: 5mm;
}
.col5f2 {
	width: 24mm;
	height: 5mm;
}
.col6f2 {
	width: 23mm;
	height: 5mm;
}
.col7f2 {
	width: 23mm;
	height: 5mm;
}
.col8f2 {
	width: 20mm;
	height: 5mm;
}


.col1f3 {
	width: 23mm;
	height: 3mm;
	
}
.col2f3 {
	width: 5mm;
	height: 3mm;
}
.col3f3 {
	width: 20mm;
	height: 3mm;
}
.col4f3 {
	width: 45mm;
	height: 3mm;
}
.col5f3 {
	width: 24mm;
	height: 3mm;
}
.col6f3 {
	width: 23mm;
	height: 3mm;
}
.col7f3 {
	width: 23mm;
	height: 3mm;
}
.col8f3 {
	width: 20mm;
	height: 3mm;
}

.col1f4 {
	width: 23mm;
	height: 3mm;
	
}
.col2f4 {
	width: 5mm;
	height: 3mm;
}
.col3f4 {
	width: 20mm;
	height: 3mm;
}
.col4f4 {
	width: 45mm;
	height: 3mm;
}
.col5f4 {
	width: 24mm;
	height: 3mm;
}
.col6f4 {
	width: 23mm;
	height: 3mm;
}
.col7f4 {
	width: 23mm;
	height: 3mm;
}
.col8f4 {
	width: 20mm;
	height: 3mm;
}

.col1f5 {
	width: 23mm;
	height: 3mm;
	
}
.col2f5 {
	width: 5mm;
	height: 3mm;
}
.col3f5 {
	width: 20mm;
	height: 3mm;
}
.col4f5 {
	width: 45mm;
	height: 3mm;
}
.col5f5 {
	width: 24mm;
	height: 3mm;
}
.col6f5 {
	width: 23mm;
	height: 3mm;
}
.col7f5 {
	width: 23mm;
	height: 3mm;
}
.col8f5 {
	width: 20mm;
	height: 3mm;
}

.col1f6 {
	width: 23mm;
	height: 8mm;
	
}
.col2f6 {
	width: 5mm;
	height: 8mm;
}
.col3f6 {
	width: 20mm;
	height: 8mm;
}
.col4f6 {
	width: 45mm;
	height: 8mm;
}
.col5f6 {
	width: 24mm;
	height: 8mm;
}
.col6f6 {
	width: 23mm;
	height: 8mm;
}
.col7f6 {
	width: 23mm;
	height: 8mm;
}
.col8f6 {
	width: 20mm;
	height: 8mm;
}

.col1f7 {
	width: 23mm;
	height: 5mm;
	
}
.col2f7 {
	width: 5mm;
	height: 5mm;
}
.col3f7 {
	width: 20mm;
	height: 5mm;
}
.col4f7 {
	width: 45mm;
	height: 5mm;
}
.col5f7 {
	width: 24mm;
	height: 5mm;
}
.col6f7 {
	width: 23mm;
	height: 5mm;
}
.col7f7 {
	width: 23mm;
	height: 5mm;
}
.col8f7 {
	width: 20mm;
	height: 5mm;
}
.col1f8 {
	width: 23mm;
	height: 10mm;
	
}
.col2f8 {
	width: 5mm;
	height: 10mm;
}
.col3f8 {
	width: 20mm;
	height: 10mm;
}
.col4f8 {
	width: 45mm;
	height: 10mm;
}
.col5f8 {
	width: 24mm;
	height: 10mm;
}
.col6f8 {
	width: 23mm;
	height: 10mm;
}
.col7f8 {
	width: 23mm;
	height: 10mm;
}
.col8f8 {
	width: 20mm;
	height: 10mm;
}

.col1f9 {
	width: 23mm;
	height: 3mm;
	
}
.col2f9 {
	width: 5mm;
	height: 3mm;
}
.col3f9 {
	width: 20mm;
	height: 3mm;
}
.col4f9 {
	width: 45mm;
	height: 3mm;
}
.col5f9 {
	width: 24mm;
	height: 3mm;
}
.col6f9 {
	width: 23mm;
	height: 3mm;
}
.col7f9 {
	width: 23mm;
	height: 3mm;
}
.col8f9 {
	width: 20mm;
	height: 3mm;
}
.espacio {
	
	height: 12mm;
}
</style></head>
<body>
<?php                /////////revisar linea asc 410 cuadrar eso del ultimo pago.
if($_GET['idc']) {
		$id =$_GET['idc'];
		
	}
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}
mysqli_set_charset($mysqli,"utf8");
$mons = array(1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre");
$date = getdate();
$month = date('n');// si estoy imprimiendo desde un mes anterior sumar 1 mes luego de finalizar quitarlo
$month_name = $mons[$month];
$month_name2 = $mons[$month+1];
$fecha_recibo=$date['year'].'/'.$date['mon'].'/'.$date['mday'];
$query = "SELECT * FROM afiliados WHERE id=".$id."";
$afilia= $mysqli->query($query);
$queryupdate="INSERT INTO  `redesagi_facturacion`.`expediente` (`id` ,`id_cliente` ,`printed_recibo`,`fecha`)VALUES (NULL,  '".$id."',  '".$month_name."', '".$fecha_recibo."')";
$mysqli->query($queryupdate);
$ultimo_recibo="update redesagi_facturacion.afiliados set recibo_generado='".$month_name."'  WHERE id='".$id."'";
$mysqli->query($ultimo_recibo);
$infoafiliado=$afilia->fetch_assoc();
date_default_timezone_set("America/Bogota");
$hoy=date("d/m/Y");
$cliente=$infoafiliado['cliente']."  ".$infoafiliado['apellido'];
$corte=$infoafiliado['corte'];
$direccion=$infoafiliado['direccion'];
$pago=$infoafiliado['pago'];
$sql = "SELECT * FROM `factura`  WHERE `factura`.`id-afiliado`='$id'   ORDER BY `factura`.`id-factura` DESC";//$ultimopago="";
$pagoAnterior="";
if($result = $mysqli->query($sql)){
	while ($rowf = $result->fetch_assoc()) {	
		$idFactura=$rowf["id-factura"];
		$sql = "SELECT * FROM `recaudo` WHERE `idfactura` = $idFactura ORDER BY `idrecaudo` DESC ";
		if($resultw = $mysqli->query($sql)){
			while($rowf1 = $resultw->fetch_assoc()){
				$pagoAnterior=$rowf1["fecha-hoy"];
				
				$sqlx="SELECT * FROM `transacciones` WHERE `id-cliente` = $id ORDER BY `idtransaccion` DESC ";
				if($resultz = $mysqli->query($sqlx)){
					if($rowf2 = $resultz->fetch_assoc()){
						$valorPagoF=number_format($rowf2["valor-a-pagar"],0);

					}
				}
				else{
					echo "Error:".$mysqli->error;
				}
				$ultimopago = date('d-m-Y', strtotime($pagoAnterior));
				if($pagoAnterior)
					break;
			}
			 
		}
		else{
			echo "Error:".$mysqli->error;	
		}	
		$resultw->free();
		if($pagoAnterior)
			break;
	}  
}
else{
	echo "Error:".$mysqli->error;
	$idFactura=0;
}				
$result->free();
	

$result->free();
//$ultimopago=$row['ultimopago'];

$sql = "SELECT * FROM `factura`  WHERE `factura`.`id-afiliado`='$id' AND `factura`.`cerrado`='0'  ORDER BY `factura`.`id-factura` DESC";
$vtotal=0;
$cont=0;
if ($result = $mysqli->query($sql)) {
	while ($rowf = $result->fetch_assoc()) {	
		$cont+=1;
		$idFactura=$rowf["id-factura"];
		$periodo=$rowf["periodo"];
		$saldo=$rowf["saldo"];
		$vtotal+=$saldo;
		}
    	$result->free();
	}

if ($vtotal<=$pago) {
	$mesenmora=0;
	$saldoenmora=0;
}
else{
	$temp=$vtotal-$pago;
	$saldoenmora=$temp;
	if($temp<=$pago){
		$mesenmora=1;

	}
	if($temp>$pago){
		$mesenmora= ceil($temp/$pago); // 10
		
	}
}
$totalfactura=$pago+$saldoenmora;
if($cont==0){
	$totalfactura=0;
}
$textstyle='';
if($mesenmora>=1){
      if($ultimopago!=''){
       	 	$aviso='AVISO DE SUSPENSION-ULTIMO PAGO RECIBIDO:'.$ultimopago;
        	$textstyle='text-align:left;font-size:13px;';
        }
      else  {
        	$aviso='AVISO DE SUSPENSION';
        	$textstyle='text-align:left;font-size:12px;';
        }    	
}
if($mesenmora==0)
        {  
        $aviso='Ultimo Pago :'.$ultimopago.'($'.$valorPagoF.')'; 
        $textstyle='text-align:left;font-size:12px;';
        }   
if($ultimopago=="0000-00-00")
	$ultimopago="";

$codigo=300+$id;
$year=date("Y"); 
$countDay=cal_days_in_month(CAL_GREGORIAN,$month,$year);
if($corte==1){
	$periodo="1 de $month_name a $countDay de $month_name de $year";
	$paguehasta="5 DE $month_name";
	$suspension="7 DE $month_name";
}
if($corte==15){
	$periodo="15 de $month_name a 14 de $month_name2 de $year";
	$paguehasta="20 DE $month_name";
	$suspension="22 DE $month_name";
}



?>

<table style="table-layout:fixed;font-size:11px;" width="200" border="0">
  <tr>
    <td class="col1f1"></td>
    <td class="col2f1"></td>
    <td class="col3f1"></td>
    <td class="col4f1"></td>
    <td class="col5f1"></td>
    <td class="col6f1"></td>
    <td class="col7f1"></td>
    <td class="col8f1"></td>
  </tr>
  <tr>
    <td class="col1f2"></td>
    <td class="col2f2"></td>
    <td class="col3f2"><?php echo"$hoy";?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;www.redesagingenieria.com </td>
    <td class="col4f2"></td>
    <td class="col5f2"></td>
    <td class="col6f2"></td>
    <td class="col7f2"></td>
    <td class="col8f2"></td>
  </tr>
  <tr>
    <td class="col1f3"></td>
    <td class="col2f3"></td>
    <td class="col3f3"><?php echo $codigo;?></td>
    <td class="col4f3"></td>
    <td class="col5f3"></td>
    <td colspan="3" style="text-align:left" class="col6f3"><?php echo $periodo;?></td>    
  </tr>
  <tr>
    <td class="col1f4"></td>
    <td class="col2f4"></td>
    <td  colspan="2"class="col3f4" style="font-size:10px;"><?php echo"$cliente";?></td>
   
    <td class="col5f4"></td>
    <td colspan="3" style="text-align:left; font-size:10px;text-align:left;" class="col6f4"><?php echo $paguehasta;?></td>    
  </tr>
  
  <tr>
    <td class="col1f5"></td>
    <td class="col2f5"></td>
    <td  colspan="2"class="col3f5" style="text-align:left;font-size:10px;"><?php echo $direccion;?></td>
   
    <td class="col5f5"></td>
    <td colspan="3" style=" font-size:10px;text-align:left;"  class="col6f5"><?php echo $suspension;?></td>    
  </tr>
  <tr valign="bottom">
    <td class="col1f6">101</td>
    <td class="col2f6"></td>
    <td  colspan="2" class="col3f6" style="font-size:10px;text-align:left;font-style:italic;">SERVICIO INTENET BANDA ANCHA</td>
    
    <td style="font-size:10px;text-align:left;" class="col5f6">$<?php echo number_format($pago,0);?></td>
    <td class="col6f6"></td>
    <td style="text-align:right;font-size:10px;" class="col7f6">$<?php echo number_format($pago,0);?></td>
    <td class="col8f6"></td>
  </tr>
  <tr valign="top">
    <td class="col1f7" style="text-align:left;font-size:10px;"  ></td>
    <td class="col2f7"></td>
    <td  colspan="2" class="col3f7" style="font-size:10px;text-align:left;font-style:bold;">FACTURAS VENCIDAS</td>
    
    <td style="text-align:left;font-size:10px;" class="col5f7">$<?php echo number_format($saldoenmora,0);?></td>
    <td class="col6f7"></td>
    <td style="text-align:right;font-size:10px;" class="col7f7">$<?php echo number_format($saldoenmora,0);?></td>
    <td class="col8f7"></td>
  </tr>
  <tr valign="bottom">
    <td colspan="2" style="font-size:36px;text-align:right;font-weight: bold;" class="col1f8"> <?php echo $mesenmora;?> </td>
    
    <td class="col3f8"></td>
    <td colspan="4" style="<?php echo $textstyle;?>" class="col4f8"><b><?php echo "$aviso";?></b></td>
    
    <td class="col8f8"></td>
  </tr>
  <tr>
    <td colspan="6" class="col1f9"></td>
    
    <td style="text-align:right;font-size:12px;font-weight: bold;" class="col7f9">$<?php echo number_format($totalfactura,0);?></td>
    <td class="col8f9"></td>
  </tr>
   
  
</table>


<table style="table-layout:fixed;font-size:11px;" width="200" border="0">
  <tr>
    <td class="t2col1f1"></td>
    <td class="t2col2f1"></td>
    <td class="t2col3f1"></td>
    <td class="t2col4f1"></td>
    <td class="t2col5f1"></td>
    <td class="t2col6f1"></td>
    <td class="t2col7f1"></td>
    <td class="t2col8f1"></td>
  </tr>
  
  <tr>
    <td class="col1f3"></td>
    <td class="col2f3"></td>
    <td class="col3f3"><?php echo $codigo;?></td>
    <td class="col4f3"></td>
    <td class="col5f3"></td>
    <td colspan="3" style="text-align:left" class="col6f3"><?php echo $periodo;?></td>    
  </tr>
  <tr>
    <td class="col1f4"></td>
    <td class="col2f4"></td>
    <td  colspan="2"class="col3f4" style="font-size:10px;"><?php echo"$cliente";?></td>
   
    <td class="col5f4"></td>
    <td colspan="3" style="text-align:left; font-size:10px;text-align:left;" class="col6f4"><?php echo $paguehasta;?></td>    
  </tr>
  
  <tr>
    <td class="col1f5"></td>
    <td class="col2f5"></td>
    <td  colspan="2"class="col3f5" style="text-align:left;font-size:10px;"><?php echo $direccion;?></td>
   
    <td class="col5f5"></td>
    <td colspan="3" style=" font-size:10px;text-align:left;"  class="col6f5"><?php echo $suspension;?></td>    
  </tr>
  <tr>
    <td class="col1f6">101</td>
    <td class="col2f6"></td>
    <td  colspan="2" class="col3f6" style="font-size:10px;text-align:left;font-style:italic">SERVICIO INTENET BANDA ANCHA</td>
    
    <td style="font-size:10px;text-align:left;" class="col5f6">$<?php echo number_format($pago,0);?></td>
    <td class="col6f6"></td>
    <td style="text-align:right;font-size:10px;" class="col7f6">$<?php echo number_format($pago,0);?></td>
    <td class="col8f6"></td>
  </tr>
  <tr>
    <td class="col1f7" style="text-align:left;font-size:10px;"  ></td>
    <td class="col2f7"></td>
    <td  colspan="2" class="col3f7" style="font-size:10px;text-align:left;">FACTURAS VENCIDAS</td>
    
    <td style="text-align:left;font-size:10px;" class="col5f7">$<?php echo number_format($saldoenmora,0);?></td>
    <td class="col6f7"></td>
    <td style="text-align:right;font-size:10px;" class="col7f7">$<?php echo number_format($saldoenmora,0);?></td>
    <td class="col8f7"></td>
  </tr>
  <tr>
    <td colspan="2" style="font-size:36px;text-align:right;font-weight: bold;" class="col1f8"><?php echo $mesenmora;?></td>
    
    <td class="col3f8"></td>
    <td colspan="4" style="text-align:left;font-size:12px;" class="col4f8"></td>
    
    <td class="col8f8"></td>
  </tr>
  <tr>
    <td colspan="6" class="col1f9"></td>
    
    <td style="text-align:right;font-size:12px;font-weight: bold;" class="col7f9">$<?php echo number_format($totalfactura,0);?></td>
    <td class="col8f9"></td>
  </tr>
  
  
</table>


</body>
</html>
