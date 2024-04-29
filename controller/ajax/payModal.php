<?php
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: ../../login/index.php');
		exit;
		}
else    {
		$user=$_SESSION['username'];
		}
include("../../login/db.php");
if($_POST['rowid']){
		$id =$_POST['rowid'];
		$cedula =$_POST['cedula']; 
		$telefono=$_POST['telefono'];   
		$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
		if ($mysqli->connect_errno) {
	    	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
			}	
		mysqli_set_charset($mysqli,"utf8");
		$today = date("Y-m-d");   
		$convertdate= date("d-m-Y" , strtotime($today));
		if($cedula!=0){
			$sqlCedula="UPDATE `redesagi_facturacion`.`afiliados` SET `cedula` = '$cedula' WHERE `afiliados`.`id` = $id;";
			if ($result = $mysqli->query($sqlCedula)) {
				echo "<p class=\"text-info\">Cedula Actualizada con exito!</p>";
			}
			else{
				echo "<p class=\"text-danger\" >Error, cedula No Actualizada!</p>";
			}
		}
		if($telefono!=0){
			$sqlTelefono="UPDATE `redesagi_facturacion`.`afiliados` SET `telefono` = '$telefono' WHERE `afiliados`.`id` = $id;";
			if ($result = $mysqli->query($sqlTelefono)) {
				echo "<p class=\"text-info\">Telefono Actualizado con exito!</p>";
			}
			else{
				echo "<p class=\"text-danger\" >Error, Telefono No Actualizado!</p>";
			}
		}
		$sql = "SELECT * FROM `afiliados` WHERE `id`=$id ";
		if ($result = $mysqli->query($sql)) {
			$row = $result->fetch_assoc();	
			if($row["mesenmora"]==0)
				$multiplicador=1;
			else
			$multiplicador=$row["mesenmora"];
			$registration=$row["registration-date"];
			$corte=$row["corte"];
			$email=$row["mail"];
			$date1 = new DateTime($today);
			$date2 = new DateTime($row["suspenderFecha"]);
			$days  = $date2->diff($date1)->format('%a');
			$style = "border-info text-info ";
			if($row["suspender"]==1){
				$statusText = "<div class=\"d-flex justify-content-center\" > <p class=\"border-top border-primary px-0 \"><small class=\"p-0 my-1 text-info\">Cortado-[ $days días ]</small> <small>Reconectar servicio <input class=\" ml-1 mt-1 \"  id=\"checkbox-reconectar\" type=\"checkbox\" checked value=\"1\"></small></p></div>";
			}
			$varHtml="<table class=\"table table-striped\">
						<tr>
					  		<td><small>Cod. Cliente:</small></td><td><small>017000</small><small  id=\"id-client\">".$id."</small></td>
						</tr>
						<tr>
					  		<td colspan='2' align='center'>Cliente: {$row['cliente']} {$row['apellido']} $statusText </td>
						</tr>
						<tr>
			          		<td>Direccion:</td><td>".$row["direccion"]."</td>
			          	</tr>
			          	<tr>";
			$sql = "SELECT * FROM `redesagi_facturacion`.`factura` WHERE `factura`.`id-afiliado`='$id' AND CAST(`factura`.`fecha-pago` AS CHAR(10)) != '0000-00-00' AND `factura`.`fecha-pago` IS NOT NULL AND (`valorf`!= `saldo`)  ORDER BY `factura`.`id-factura` DESC";
			//echo $sql;
			if($result = $mysqli->query($sql)){
				$rowf = $result->fetch_assoc();   
				$idFactura=$rowf["id-factura"];	
				$periodo=$rowf["periodo"];
				if($idFactura)
					$fl=1;
				else
					$fl=0;		
					//echo "flag =1 pero idfsctura es:$idFactura";
				$result->free();
			}else{

				echo "Error:".$mysqli->error;
				$idFactura=0;
				
			}				
			$sql = "SELECT * FROM `redesagi_facturacion`.`recaudo` WHERE `recaudo`.`idfactura` = '$idFactura' ORDER BY `idrecaudo` DESC ";
			//echo "<br>".$sql;
			if($fl==1){
					
					if($result = $mysqli->query($sql)){
						if($rowf = $result->fetch_assoc()){
							$pagoAnterior=$rowf["fecha-hoy"];
							$pagado=$rowf["valorp"];
							$abonado=$rowf["abonar"];
							$vendedor=$rowf["vendedor"];
							$formatted_date = date('d-m-Y', strtotime($pagoAnterior));
							$varHtml.="<td class=\"\">Pago Anterior:</td><td class=\"\"><span  class=\" border border-primary rounded  text-dark p-1  m-1 \">$formatted_date  </span> <span  class=\"btn btn-secondary btn-sm \" id=\"btn-paym\" ><i class=\"icon-down-open  \" id=\"icon-down-open\"></i><small>Más...</small></span>";
							
							$varHtml.="</td>";  	
						}
						else{
							$varHtml.="<td>Pago Anterior:</td><td>00/00/0000</td>"; 	
						}	 
						
					$result->free();         	
					}
					else{
						echo "Error:".$mysqli->error;	
						}	
				}
			if($fl==0){
				$varHtml.="<td class=\"small\">Pago Anterior:</td><td>Ninguno.<a href='../../factura_new_cli.php?rpp=1&idc=$id' target='_blank'> Afiliación:</a>$registration Corte:$corte</td>
				</tr>"; 	
			}
			else{
				$varHtml.="</tr><tr><td class=\"small\">Contrato de afiliación:</td><td><a href='../../factura_new_cli.php?rpp=1&idc=$id' target='_blank'> Afiliación:</a></td></tr>"; 	

			}
			          	
			$varHtml.="  
						
						
						<tr  >
							<td colspan=\"2\">
								<div id=\"payment-div\" class=\"d-flex flex-wrap py-1  justify-content-center\">
									<div id=\"payment\">
																	
									</div>
								</div>	
							</td>
						</tr>

			          	<tr>
			          		<td>Hoy:</td><td>".$convertdate."</td>
			          	</tr>
			          	<tr>
			          		<td>Plan:</td><td>".$row["velocidad-plan"]." Megas.</td>
			          	</tr>
			          	<tr>
			          		<td>Valor Plan:</td><td><strong id=\"valor-plan\" >$".number_format($row["pago"])."</strong></td>
			          	</tr>
			          	<tr>
			          		<td>Facturas:</td>
		          		<td>";
		    $sql = "SELECT * FROM `factura`  WHERE `factura`.`id-afiliado`='$id' AND `factura`.`cerrado`='0'  ORDER BY `factura`.`id-factura` DESC";
		    $vtotal=0;
		    $varHtml.="<select class=\"form-control form-control-sm \" id=\"sel1\">";
		    $cont=0;
		    if ($result = $mysqli->query($sql)) {
				while ($rowf = $result->fetch_assoc()) {	
					$cont+=1;
					$idFactura=$rowf["id-factura"];
					$periodo=$rowf["periodo"];
					$saldo=$rowf["saldo"];
					$vtotal+=$saldo;
					$varHtml.="<option  >Cod:".$idFactura."-".$periodo."-Valor:$".number_format($saldo)."</option>";	
					}
			    	$result->free();
				}  	
			if($cont==0)$varHtml.="<option>No hay pddtes.</option>";     		
			$varHtml.="</select>";		
			
			$varHtml.="</td>
			          	</tr>
			          	<tr>
			          		<td>Total:</td><td><div class=\"d-flex\" ><div class=\"border border-info rounded px-1\"><strong id=\"valor-pago\">$".number_format($vtotal)."</strong></div> <div id=\"nuevo-saldo\" class=\"ml-1 border border-info rounded pb-1 px-2\"><small>   Nuevo saldo:</small><strong class=\"ml-1\" >$</strong><strong  id=\"valor-nuevo-saldo\">$vtotal</strong></div></div></td>
			          	</tr>
			          	<tr  id=\"tr-chkb-abonar\">
			          		<td >Abonar<input class=\" ml-1 mt-1 \"  id=\"checkbox-abonar\" type=\"checkbox\" value=\"1\"> </td><td> Descontar<input class=\" ml-1 mt-1 \" id=\"checkbox-descontar\" type=\"checkbox\"  value=\"1\"> </td>
			          	</tr>
			          	<tr id=\"tr-valor-abonar\">
			          		<td>Valor abonar:</td><td><input class=\"form-control\" id=\"valor-abonar\"  value=\"\" placeholder=\"Cuanto abona?\"><span class=\"bg-info text-white px-1 ml-1\">$<small class=\" money-abonar\"></small></span></td>
						  </tr>
						<tr id=\"tr-valor-descontar\">
			          		<td>Valor descontar:</td><td><input class=\"form-control\" id=\"valor-descontar\"  value=\"\" placeholder=\"Cuanto descuenta?\"><span class=\"bg-info text-white px-1 ml-1\">$<small class=\" money-descontar\"></small></span></td>
			          	</tr>
			          </table>
					  <div>
					  	<p>-</p>
					  	<p class=\"text-info font-weight-bold font-italic\">Nuevo</p>
					  </div>
					  <div class=\"border border-info rounded\">
							<div class=\" font-weight-light  p-3\">
								<div>
								Desea agregar dinero a billetera?<input class=\"form-control form-control-sm\" id=\"valor-wallet\" type=\"number\" min=\"0\" step=\"1\"  value=\"\" placeholder=\"Cuanto pagan adelantado?\">
								</div> 
								<div class=\"mt-3 text-secondary\">
									<small>**Cada cliente tiene su billetera personal, mientras tengo saldo se va a usar para pagar la factura cada mes!</small>
								</div>
								<div class=\"text-secondary\"> 
									<small>**La billetera se puede recargar directamente aquí ingresando el valor de pago por adelantado o en la opcion Biletera  del menú principal</small>
								</div>
							</div>
					  </div> 
					<div>
						<p>-</p>
						<p class=\"text-info font-weight-bold font-italic\">Actualizar email:
						<input class=\"form-control\" id=\"emailInput\" value=\"$email\" placeholder=\"Ingrese email por favor\" >
						</p>
					</div>
					 
					  ";
			         
			echo $varHtml;
			}
 		
 		}

 ?>