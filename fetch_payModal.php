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
if($_POST['rowid']) {
		$id = $_POST['rowid'];
		$cedula = $_POST['cedula']; 
		$telefono = $_POST['telefono'];  
		include("login/db.php");
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
			$varHtml="<table class=\"table table-striped\">
						<tr>
					  		<td><small>Cod. Cliente:</small></td><td><small>017000</small><small  id=\"id-client\">".$id."</small></td>
						</tr>
						<tr>
					  		<td>Cliente:</td><td>".$row["cliente"]."  ".$row["apellido"]."</td>
						</tr>
						<tr>
			          		<td>Direccion:</td><td>".$row["direccion"]."</td>
			          	</tr>
			          	<tr>";
			$sql = "SELECT * FROM `factura`  WHERE `factura`.`id-afiliado`='$id' AND `factura`.`fecha-pago`!= '0000-00-00'   ORDER BY `factura`.`id-factura` DESC";
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
				}
			else{
				echo "Error:".$mysqli->error;
				$idFactura=0;
				
				}				
			$result->free();
			$sql = "SELECT * FROM `recaudo` WHERE `idfactura` = $idFactura ORDER BY `idrecaudo` DESC ";
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
						
						}
					else{
						echo "Error:".$mysqli->error;	
						}	
				}
			if($fl==0){
				$varHtml.="<td class=\"small\">Pago Anterior:</td><td>Ninguno. Afiliación:$registration Corte:$corte</td>"; 	
			}
			$result->free();         	
			          	
			$varHtml.="  
						</tr>
						
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
			          		<td>Valor Plan:</td><td>$".number_format($row["pago"])."</td>
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
			          		<td>Total:</td><td id=\"valor-pago\" >$".number_format($vtotal)."</td>
			          	</tr>
			          	<tr id=\"tr-chkb-abonar\">
			          		<td>Abonar:</td><td><input class=\"form-check-input\" id=\"checkbox-abonar\" type=\"checkbox\" value=\"1\"></td>
			          	</tr>
			          	<tr id=\"tr-valor-abonar\">
			          		<td>Valor:</td><td><input class=\"form-control\" id=\"valor-abonar\"  value=\"\" placeholder=\"Cuanto abona?\"><span class=\"bg-info text-white px-1 ml-1\">$<small class=\" money\"></small></span></td>
			          	</tr>
			          	
			          </table>";
			         
			echo $varHtml;
			}
 		
 		}

 ?>