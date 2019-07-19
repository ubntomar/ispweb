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
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>IspExperts-Administrador ISP</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css"> 
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css"> 
	<link rel="stylesheet" href="bower_components/alertify/css/alertify.min.css" />

	<link rel="stylesheet" href="bower_components/alertify/css/themes/default.min.css" />
	<link rel="stylesheet" href="css/fontello.css">
	<link rel="stylesheet" href="css/estilos.css">
	<link rel="stylesheet" href="css/dataTables.checkboxes.css">
	<style>
		.caja {
			border-bottom: 1px solid #000;
		}
	</style>
</head>
<body>
	<?php 
		include("login/db.php");
		$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
		if ($mysqli->connect_errno) {
	    	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
			}	
		mysqli_set_charset($mysqli,"utf8");
	?>	
	<div class="container-fluid px-0">		
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top   ">		
			<div class="container img-logo " >
				<img src="img/wisp.png">
				<!-- Nos sirve para agregar un logotipo al menu -->
				<a href="main.php" class="navbar-brand ">Wisdev</a>
				
				<!-- Nos permite usar el componente collapse para dispositivos moviles -->
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Menu de Navegacion">
					<span class="navbar-toggler-icon"></span>
				</button>
				
				<div class="collapse navbar-collapse " id="navbar">
					<ul class="navbar-nav  navclass ">
						<li class="nav-item ">
							<a href="#" class="nav-link"><i class="icon-money"></i>Cierre de caja <span class="sr-only">(Actual)</span></a>
						</li>
						<li class="nav-item">
							<a href="register-pay.php" class="nav-link "><i class="icon-money"></i>Registrar Pago</a>
						</li>
						<li class="nav-item  ">
							<a href="transacciones.php" class="nav-link  "><i class="icon-print "></i>Transacciones</a>
						</li>
						<li class="nav-item">
							<a href="#" class="nav-link"><i class="icon-mail"></i>Contacto</a>
						</li>
						<li class="nav-item">
							<a href="reclist.php" class="nav-link "><i class="icon-money"></i>Formato Recibo</a>
						</li>
					</ul>
					<div class="ml-auto">	
						<ul class="nav navbar-nav   ">
							<li class="nav-item ">
									<a class="nav-link disabled text-white " ><i class="icon-user"></i><?php echo "Hola ".$_SESSION['username']; ?></a>
							</li>       
						</ul>	
					</div>	
					
				</div>
			</div>
		</nav>

		<div class="row">
			<div class="barra-lateral col-12 col-lg-auto">				
				<nav class="menu d-flex d-lg-block justify-content-center flex-wrap">
					<a href="tick.php"><i class="icon-pinboard"></i><span>Tickets</span></a>
					<a href="fact.php"><i class="icon-docs-1"></i><span>Facturas</span></a>
					<a href="client.php"><i class="icon-users"></i><span>Clientes</span></a>
					<a href="mktik.php"><i class="icon-network"></i><span>Mktik</span></a>
					<a href="egr.php"><i class="icon-money"></i><span>Egresos</span></a>
					<a href="login/logout.php"><i class="icon-logout"></i><span>Salir</span></a>
				</nav>
			</div>

			<main class="main col border border-primary">
				<div class="row">
					<div class=" col-12">						
						<div class=" nuevo_contenido">
							<h3 class="titulo">Configuración de Clientes</h3>
							<!-- inicio de Bootstrap cards -->
							<div class="card text-center">
								<div class="card-header">
									<ul class="nav nav-tabs card-header-tabs">
										<li class="nav-item">
													<a class="nav-link active" id="new_client_registration" href="#">Afiliar Nuevo Cliente </a>
											</li>
											<li class="nav-item">
												<a class="nav-link " id="active_client_currently" href="#">Clientes  </a>
											</li>
											<li class="nav-item">
												<a class="nav-link " id="sms_notification" href="#">Notificaciones vía SMS</a>
											</li>
											<li class="nav-item">
												<a class="nav-link " id="massive_notifications" href="#">SMS Masivos</a>
											</li>
											<li class="nav-item">
												<a class="nav-link disabled" id="edi" href="#">Editar </a>
											</li>
										</ul>
									</div>
								<div class="card-body   border border-dark ">
									<div class="d-flex justify-content-center">
											<div id="new_client_registration_content" class=" px-3 py-3 text-left card col-sm-6 border border-warning">
													<div >
														<p class="card-text ">#. Nombres..</p>
														<div class=" my-3 ">
															<input class="form-control" type="text" value="" id="name" >
														</div>
														<p class="card-text ">#. Apellidos.</p>
														<div class=" my-3 ">
															<input class="form-control" type="text" value="" id="last-name" >
														</div>
														<p class="card-text ">#. Cedula.</p>
														<div class=" my-3 ">
															<input class="form-control" type="text" value="" id="cedula" >
														</div>	
														<p class="card-text ">#. Dirección.</p>
														<div class=" my-3 ">
															<input class="form-control" type="text" value="" id="address" >
														</div>
														<p class="card-text ">#. Ciudad.</p>
														<div class=" my-3">
															<input class="form-control" type="text" value="" id="ciudad" >
														</div>
														<p class="card-text ">#. Departamento.</p>
														<div class=" my-3">
															<input class="form-control" type="text" value="" id="departamento" >
														</div>
														<p class="card-text ">#. Telefono.</p>
														<div class=" my-3">
															<input class="form-control" type="text" value="" id="phone" >
														</div>
														<p class="card-text ">#. Email.</p>
														<div class=" my-3">
															<input class="form-control" type="email" value="" id="email" >
														</div>
														<p class="card-text mt-3 ">#. Corte de facturación.</p>	 
														<div class=" my-3">
															<select class="custom-select " id="corte">
																<option value="1" selected>Corte 1.</option>
																<option value="15">Corte 15.</option>								  
															</select>	
														</div>		
															
														<p class="card-text mt-3 ">#. Seleccione Tipo de Plan.</p>	 
														<div class=" my-3">
															<select class="custom-select " id="plan">
															<option value="Residencial" selected>Residencial.</option>
															<option value="Comercial">Comercial.</option>								  
														</select>
														</div>
														<p class="card-text mt-3 ">#. Velocidad de Plan.</p>	 
														<div class=" my-3">
															<select class="custom-select " id="velocidad-plan">
															<option value="1" >1 Mbps.</option>
															<option value="2" selected >2 Mbps.</option>
															<option value="3">3 Mbps.</option>
															<option value="4">4 Mbps.</option>
															<option value="5">5 Mbps.</option>
															<option value="6">6 Mbps.</option>
															<option value="7">7 Mbps.</option>					
															<option value="8">8 Mbps.</option>
															<option value="9">9 Mbps.</option>
															<option value="10">10 Mbps.</option>
															<option value="15">15 Mbps.</option>
															<option value="20">20 Mbps.</option>
														</select>	
														</div>  
														
														<p class="card-text ">#. Valor de Plan (Es la mensualidad que queda pagando el cliente Ojo sin  escribir puntos).</p>
														<div class=" my-3">
															<input class="form-control" type="number" value="" id="valor-plan" >
														</div>	
														<p class="card-text ">#. Dirección ip.</p>

														<div class=" my-3">
															<input class="form-control" type="text" value="" id="ip-address" >
														</div>		
														<p class="card-text mt-3 ">#. Generar Factura de inmediato ?. (Si necesito que el pago del primer mes quede aparte de lo que van a pagar de afiliación le doy sí. Entonces voy a Registrar Pago y ahí ya le aparece para que genere el primer pago de una vés.)</p>	 
														<div class=" my-3">
															<select class="custom-select " id="generar-factura">		    								  
																<option value="1" >Si.</option>
																<option value="0" selected >No.</option>											  
														</select>	
														</div>
														<p class="card-text mt-3 ">#. Paga el primer mes de servicio con lo que está pagando de afiliación? . Si no sabe , dejarlo en No.</p>	 
														<div class=" my-3">
															<select class="custom-select " id="standby">		    								  
																<option value="1" >Si.</option>
																<option value="0" selected >No.</option>											  
															</select>	
														</div>
														<p class="card-text ">#. Valor de Afiliación a servicio de Internet. 150.000 dentro de Guamal ,180.000 rural simple ,220.000 rural complicada.</p>
														<div class=" my-3">
																<input class="form-control" type="number" value="" id="valorAfiliacion" >
														</div>   	
														<div class="d-flex justify-content-center"><a  class="btn btn-primary col-sm-6" id="save">Guardar</a></div> 
													</div>												 
											</div>
									</div>		  								
										<div id="active_client_currently_content" class=" px-3 py-3 text-left    border border-danger">
												
											<div class="  border border-success" >
												<h4 class="card-title mt-4">Lista de Clientes <strong>Activos</strong> </h4>										
												<table id="table_active_client"  class="display datatable_table_active_client " cellspacing="0" width="100%">
														<thead  class="bg-primary">
														<tr>
															<td>cod</td>
															<td>Cliente</td>
															<td>Dirección</td>
															<td>Corte</td>
															<td>Pago</td>
															<td>Velocidad</td>
															<td>IP</td>															
															<td>Suspender</td>																								
														</tr>
														</thead>
														<tfoot class="py-3 text-right">
															<tr>
																
																<td colspan="3"></td>
																<td colspan="3"></td>
																
															</tr>
														</tfoot>
														<tbody >										
															<?php 
															$contPago=0;					
															$sql = "SELECT * FROM `afiliados` WHERE activo=1 AND suspender=0 ORDER BY `id` DESC ";
															if ($result = $mysqli->query($sql)) {
																while ($row = $result->fetch_assoc()) {
																	$cod=$row["id"];
																	$cliente=$row["cliente"]." ".$row["apellido"];
																	$direccion=$row["direccion"];
																	$corte=$row["corte"];
																	$pago=$row["pago"];
																	$contPago+=$pago;
																	$velocidad=$row["velocidad-plan"]." Megas";
																	$activo=$row["activo"];
																	$ip=$row["ip"];
																	echo "<tr class=\"text-center  \">";
																	echo "<td>".$cod."</td>";				
																	echo "<td>".$cliente."</td>";
																	echo "<td>".$direccion."</td>";
																	echo "<td>".$corte."</td>";
																	echo "<td>".$pago."</td>";	
																	echo "<td>".$velocidad."</td>";	
																	echo "<td>".$ip."</td>";										
																	
																	echo "<td><button type=\"button\" class=\"btn btn-primary suspender\" id=\"activo".$cod."\" ><i class=\"icon-scissors text-dark\"></i></button></td>";									
																	echo "</tr>";		
																	}
																		$result->free();
																}
														?>		
														</tbody>
												</table>												
											</div>
											<h1>Recaudo estimado Agosto : <?php echo " $ $contPago"; ?> </h1>
																
											<div class="  border border-success">
												<h4 class="card-title mt-4">Lista de Clientes en <strong> SUSPENSIÓN</strong> </h4>	
												<table id="table_no_active_client" class="display responsive " cellspacing="0" width="100%">
													<thead  class="bg-dark text-light">
														<tr><td>cod</td>
															<td>Cliente</td>
															<td>Dirección</td>
															<td>Corte</td>
															<td>Pago</td>
															<td>Velocidad</td>
															<td>IP</td>	
															
															<td>Reconectar</td>		
																								
														</tr>
													</thead>
													<tfoot class="py-3 text-right">
														<tr>
															
															<td colspan="3"></td>
															<td colspan="3"></td>
															
														</tr>
													</tfoot>
													<tbody >										
													<?php 					
														$sql = "SELECT * FROM `afiliados` WHERE suspender=1 ORDER BY `id` DESC ";
														if ($result = $mysqli->query($sql)) {
															while ($row = $result->fetch_assoc()) {
																$cod=$row["id"];
																$cliente=$row["cliente"]." ".$row["apellido"];
																$direccion=$row["direccion"];
																$corte=$row["corte"];
																$pago=$row["pago"];
																$velocidad=$row["velocidad-plan"]." Megas";
																$activo=$row["activo"];
																$ip=$row["ip"];
																echo "<tr class=\"text-center  \">";
																echo "<td>".$cod."</td>";				
																echo "<td>".$cliente."</td>";
																echo "<td>".$direccion."</td>";
																echo "<td>".$corte."</td>";
																echo "<td>".$pago."</td>";	
																echo "<td>".$velocidad."</td>";	
																echo "<td>".$ip."</td>";										
																
																echo "<td><button type=\"button\" class=\"btn btn-dark reconectar\" id=\"inactivo".$cod."\" ><i class=\"icon-plug text-success\"></i></button></td>";									
																echo "</tr>";		
																}
																	$result->free();
															}
													?>		
													</tbody>
												</table>				
											</div>				
																
											<hr>
										</div>
										<!-- inicio de bloque-->				
										<div id="sms_notification_content" class="px-3 py-3 text-left">
											<h4 class="card-title mt-4">Morosos</h4>	
											<table id="Table_morosos" class="display dataTable_Morosos cell-border" cellspacing="0" width="100%">
												<thead  class="bg-primary">
												<tr>
													<td>Id</td>
													<td>Cliente</td>
													<td>Dirección</td>
													<td>Corte</td>
													<td>Valor Plan</td>
													<td>Saldo</td>
													<td>Velocidad Plan</td>
													<td>Direccion IP</td>
													<td>Telefono</td>
													<td>SMS</td>										
												</tr>
												</thead>
												<tfoot class="py-3 text-right">
													<tr>
														
														<td colspan="3"></td>
														<td colspan="3"></td>
														
													</tr>
												</tfoot>
												<tbody >										
												<?php 					
													$sql = "SELECT id,cliente,apellido,telefono,direccion,corte,valorf,ip,SUM( saldo )as mysum,`velocidad-plan` FROM `afiliados` INNER JOIN factura ON `afiliados`.`id`=`factura`.`id-afiliado` WHERE factura.periodo !='' AND factura.cerrado=0 AND `afiliados`.`activo`=1 AND suspender !=1  GROUP BY`afiliados`.`id` ORDER BY mysum DESC ";
													if ($result = $mysqli->query($sql)) {
														while ($row = $result->fetch_assoc()) {
															$idCliente=$row["id"];
															$cliente=$row["cliente"]." ".$row["apellido"];
															$telefono=$row["telefono"];
															$direccion=$row["direccion"];
															$corte=$row["corte"];
															$pago=$row["valorf"];
															$saldo=$row["mysum"];
															$velocidad=$row["velocidad-plan"]." Megas";
															$ip=$row["ip"];
															$textTelefono=$telefono;
															if ($telefono==""){
																$textTelefono="<input placeholder=\" Telefono\" class=\"form-control my-1 telefono".$row["id"]." p-0  \" type=\"text\" value=\"\" >";
																}
															else{
																$textTelefono="<input placeholder=\" Telefono\" class=\"form-control my-1 telefono".$row["id"]." p-0   \" type=\"text\" value=\"$telefono\" id=\"-1\"  >";
															}
															if($saldo!=$pago){
																echo "<tr class=\"text-center small \">";
																echo "<td>".$idCliente."</td>";	
																echo "<td>".$cliente."</td>";				
																echo "<td>".$direccion."</td>";
																echo "<td>".$corte."</td>";
																echo "<td>".$pago."</td>";
																echo "<td>".$saldo."</td>";	
																echo "<td>".$velocidad."</td>";											
																echo "<td>".$ip."</td>";
																echo "<td>".$textTelefono."<button type=\"button\" class=\"btn btn-light updateTel\" id=\"$idCliente\" ><i class=\"icon-arrows-cw text-success\"></i></button></td>";
																echo "<td><button type=\"button\" class=\"btn btn-light smsclientMoroso\" id=\"$idCliente\" ><i class=\"icon-export text-success\"></i></button></td>";
																echo "</tr>";
															}	
																
															}
																$result->free();
														}
												?>		
												</tbody>
											</table>
											<hr>
											<h4 class="card-title mt-4">Lista de Clientes de este mes  <strong>Corte 1 retrasados </strong> de pago  </h4>	
	  									<table id="Table_clientes_cte1_retrasados" class="display responsive datatable_Table_clientes_cte1_retrasados cell-border" cellspacing="0" width="100%">
												<thead  class="bg-danger text-light">
												<tr>
													<td>cod</td>
													<td>Cliente</td>
													<td>Dirección</td>
													<td>Corte</td>
													<td>Pago</td>
													<td>Velocidad</td>
													<td>Teléfono</td>														
													<td>SMS</td>																						
												</tr>
												</thead>
												<tfoot class="py-3 text-right">
													<tr>
														
														<td colspan="3"></td>
														<td colspan="3"></td>
														
													</tr>
												</tfoot>
												<tbody >										
												<?php 					
													$sql = "SELECT * FROM `afiliados` WHERE `afiliados`.`suspender` =0   AND `afiliados`.`activo` =1 AND `afiliados`.`corte` =1 ORDER BY `id` DESC ";
													if ($result = $mysqli->query($sql)) 
														while ($row = $result->fetch_assoc()) {
															$cod=$row["id"];
															$sqlz="SELECT *, COUNT(`factura`.`cerrado`) as counts 
																FROM `redesagi_facturacion`.`afiliados`     
																INNER JOIN `factura`
																				ON `afiliados`.`id` = `factura`.`id-afiliado`
																WHERE `factura`.`id-afiliado`=$cod AND `factura`.`cerrado`=0 
																";	
															if ($resultz = $mysqli->query($sqlz)) {
																$rowz = $resultz->fetch_assoc(); 
																$counts=$rowz["counts"];
																//echo "<p>id:-$cod-:cerrado:".$rowz["cerrado"].":-$counts-</p>";
																if (($rowz["cerrado"]!="")&&($counts==1)){
																	$cliente=$rowz["cliente"]." ".$row["apellido"];
																	$direccion=$rowz["direccion"];
																	$corte=$rowz["corte"];
																	$pago=$rowz["pago"];
																	$velocidad=$rowz["velocidad-plan"]." Megas";
																	$activo=$rowz["activo"];
																	$telefono=$rowz["telefono"];
																	if ($telefono==""){
																	$textTelefono="<input placeholder=\" Telefono\" class=\"form-control my-1 telefonoAtrasado".$cod." p-0  \" type=\"text\" value=\"\" >";
																	}
																	else{
																		$textTelefono="<input placeholder=\" Telefono\" class=\"form-control my-1 telefonoAtrasado".$cod." p-0   \" type=\"text\" value=\"$telefono\" id=\"-1\"  >";
																	}
																	echo "<tr class=\"text-center  \">";
																	echo "<td>".$cod."</td>";				
																	echo "<td>".$cliente."</td>";
																	echo "<td>".$direccion."</td>";
																	echo "<td>".$corte."</td>";
																	echo "<td>".$pago."</td>";	
																	echo "<td>".$velocidad."</td>";	
																	echo "<td>".$textTelefono."<button type=\"button\" class=\"btn btn-light updateTelAtrasado\" id=\"$cod\" ><i class=\"icon-arrows-cw text-success\"></i></button></td>";
																	echo "<td><button type=\"button\" class=\"btn btn-light smsclientAtrasado\" id=\"$cod\" ><i class=\"icon-export text-success\"></i></button></td>";						
																	echo "</tr>";																
																}
																		
															}		
															$resultz->free();
																
														}
														$result->free();
												?>		
												</tbody>
											</table>
											<hr>
											<hr>
											<hr>
											<h4 class="card-title mt-4">Lista de Clientes de este mes  <strong>Corte 15 retrasados </strong> de pago  </h4>	
	  									<table id="Table_clientes_cte15_retrasados" class="display datatable_Table_clientes_cte15_retrasados cell-border" cellspacing="0" width="100%">
												<thead  class="bg-info text-dark">
												<tr>
													<td>cod</td>
													<td>Cliente</td>
													<td>Dirección</td>
													<td>Corte</td>
													<td>Pago</td>
													<td>Velocidad</td>
													<td>Teléfono</td>													
													<td>SMS</td>																					
												</tr>
												</thead>
												<tfoot class="py-3 text-right">
													<tr>
														
														<td colspan="3"></td>
														<td colspan="3"></td>
														
													</tr>
												</tfoot>
												<tbody >										
												<?php 					
												$sql = "SELECT * FROM `afiliados` WHERE `afiliados`.`suspender` =0   AND `afiliados`.`activo` =1 AND `afiliados`.`corte` =15 ORDER BY `id` DESC ";
												if ($result = $mysqli->query($sql)) 
													while ($row = $result->fetch_assoc()) {
														$cod=$row["id"];
														$sqlz="SELECT *, COUNT(`factura`.`cerrado`) as counts 
															FROM `redesagi_facturacion`.`afiliados`     
															INNER JOIN `factura`
																			ON `afiliados`.`id` = `factura`.`id-afiliado`
															WHERE `factura`.`id-afiliado`=$cod AND `factura`.`cerrado`=0 
															";	
														if ($resultz = $mysqli->query($sqlz)) {
															$rowz = $resultz->fetch_assoc(); 
															$counts=$rowz["counts"];
															//echo "<p>id:-$cod-:cerrado:".$rowz["cerrado"].":-$counts-</p>";
															if (($rowz["cerrado"]!="")&&($counts==1)){
																$cliente=$rowz["cliente"]." ".$row["apellido"];
																$direccion=$rowz["direccion"];
																$corte=$rowz["corte"];
																$pago=$rowz["pago"];
																$velocidad=$rowz["velocidad-plan"]." Megas";
																$activo=$rowz["activo"];
																$telefono=$rowz["telefono"];
																if ($telefono==""){
																$textTelefono="<input placeholder=\" Telefono\" class=\"form-control my-1 telefonoAtrasadoc15".$cod." p-0  \" type=\"text\" value=\"\" >";
																}
																else{
																	$textTelefono="<input placeholder=\" Telefono\" class=\"form-control my-1 telefonoAtrasadoc15".$cod." p-0   \" type=\"text\" value=\"$telefono\" id=\"-1\"  >";
																}
																echo "<tr class=\"text-center  \">";
																echo "<td>".$cod."</td>";				
																echo "<td>".$cliente."</td>";
																echo "<td>".$direccion."</td>";
																echo "<td>".$corte."</td>";
																echo "<td>".$pago."</td>";	
																echo "<td>".$velocidad."</td>";	
																echo "<td>".$textTelefono."<button type=\"button\" class=\"btn btn-light updateTelAtrasadoc15\" id=\"$cod\" ><i class=\"icon-arrows-cw text-success\"></i></button></td>";
																echo "<td><button type=\"button\" class=\"btn btn-light smsclientAtrasadoc15\" id=\"$cod\" ><i class=\"icon-export text-success\"></i></button></td>";						
																echo "</tr>";																
															}
																	
														}		
														$resultz->free();											    	
													}
													$result->free();
												?>		
												</tbody>
											</table>		  								
											<hr>	 
										</div>
										<div id="massive_notifications_content" class="col-12 border border-dark">										
													<!-- Inicio  de contenido de sms mensual... -->
													<div class="row">
														<div class=" border border-warning col-md-8">													
																<!-- <div class="col-12">
																	<h3>Criterio Personalizado de búsqueda para envia de SMS</h3>
																</div> -->
																<div class=" d-flex flex-column     col-12">
																		<div class="" id="criterios_content">
																			<div>																			
																				<h1 class="bg-dark text-white rounded p-1 my-1">Criterio Personalizado</h1>
																			</div>
																			<div class="text-justify">
																				<p class="p-2">En simples pasos a continuación selecciona los clientes a los que les vas a enviar los mensajes de texto y/o por email segú tu necesidad.</p>
																			</div>
																			<div class="row d-flex justify-content-center border border-secondary rounded">
																					<div class="   d-flex flex-wrap justify-content-sm-center justify-content-xl-around flex-column flex-sm-row p-1">	
																						
																							<div class="form-group px-1 border border-success rounded mx-1 my-3">
																								<label for="">Nombres </label>
																								<input type="text" class="form-control  " id="sms_masive_name" aria-describedby="" placeholder="Ingrese Nombres">
																								<small id="" class="form-text text-muted">Por defecto se busca para cualquier nombre.</small>
																							</div>
																							<div class="form-group px-1 border border-success rounded mx-1 my-3">
																								<label for="">Dirección </label>
																								<input type="text" class="form-control " id="sms_masive_address" aria-describedby="" placeholder="Ingrese Dirección">
																								<small id="" class="form-text text-muted">Por defecto se busca para cualquier dirección.</small>
																							</div>
																							<div class="form-group px-1 border border-success rounded mx-1 my-3">
																								<label for="">Ciudad </label>
																								<select class="form-control" id="sms_masive_city">
																								<option value="" selected>Cualquier ciudad</option>
																									<option value="Guamal">Guamal</option>
																									<option value="Castilla">Castilla</option>
																									<option value="San Martin"  >San Martin</option>
																									<option value="Cubarral" >Cubarral</option>
																									<option value="Acacias">Acacias</option>
																									<option value="Granada"  >Granada</option>
																									<option value="El Castillo" >El Castillo</option>
																									<option value="El Dorado">El Dorado</option>
																									<option value="Villavicencio"  >Villavicencio</option>
																																																			
																							</select>
																								<small id="" class="form-text text-muted">Por defecto se buscan clientes de cualquier ciudad.</small>
																							</div>
																							<div class="form-group px-1 border border-success rounded mx-1 my-3">
																								<label for="sel1">Fecha de Pago </label>
																								<select class="form-control" id="payment_date">
																									<option value="1">1 de cada mes</option>
																									<option value="15">15 de cada mes</option>
																									<option value="" selected >Cualquier fecha de pago</option>																									
																							</select>
																								<small id="" class="form-text text-muted">Por defecto se buscan clientes de cualquier fecha de pago.</small>
																							</div>
																							<div class="form-group px-1 border border-success rounded mx-1 my-3">
																								<label for="sel1">Estado de cuenta del cliente </label>
																								<select class="form-control" id="client_state">
																									<option value="1">Facturación al día</option>
																									<option value="-1">Facturación en mora</option>
																									<option value="0" selected >Cualquier estado de cuenta</option>																									
																							</select>
																								<small id="" class="form-text text-muted">Por defecto se buscan clientes de cualquier estado de cuenta.</small>
																							</div>
																							
																					</div>
																							<div>
																								<button type="text" class="btn btn-primary mb-2" id="sms_masivo_btn_buscar">
																								<span class=" spinner-border-sm" role="status" aria-hidden="true" id="spinner-buscar"></span>
  																							Buscar
																								</button>
																								
																							</div>
																			</div>
																		</div>
																		
																		<div class="border border-primary p-1 m-1 rounded" id="sms_masivo_container_buscar">
																			<h3>Resultado de criterio de busqueda personalizado</h3>
																			
																			
																		</div>
																		<div class="form-group px-1 border border-success rounded mx-1 my-3" id="message_box">
																								<label for="sel1">Escriba el mensaje que será enviado a los  clientes que ha seleccionado </label>
																								<textarea class="form-control" id="sms_text_content" rows="3" placeholder="Ingrese el mensaje a enviar"></textarea>
																								<small id="" class="form-text text-muted">Campo obligatorio.</small>
																		</div>
																		<div>
																			<button type="text" class="btn btn-primary mb-2" id="btn_enviar">
																				<span class=" spinner-border-sm" role="status" aria-hidden="true" id="spinner-enviar"></span>
																				Enviar
																			</button>
																		</div>		
																		
																		
																</div>
														</div>
															<div class=" border border-success col-md-4">
																<div class="col-12 px-0 ">
																	<h1 class="bg-dark text-white rounded py-1 px-0 my-1">Statistics</h1>
																</div>

																<div class=" d-flex flex-column  p-1 border border-danger col-12">
																		<div class="p-1">
																			<div class="card">
																					<div class="card-header">
																						Últimos sms enviados
																					</div>
																					<div class="card-body" id="div_sms_statistics">
																						<!-- <h5 class="card-title">Descripción</h5> -->
																						<!-- <p class="card-text text-justify ">{$row['smscontent']}</p> -->

																						
																						<!-- <a href="#" class="btn btn-primary">Editar</a> -->
																					</div>
																			</div>	
																		</div>
																		<!--  --> 
																		<?php 
																			$sql_msj_types="select * from redesagi_facturacion.smscontent where 1 ";
																			$result_sms = mysqli_query($mysqli, $sql_msj_types) or die('error');
																			while($row = $result_sms->fetch_assoc()){
																				
																				echo "
																				<div class=\"p-1\">
																					<div class=\"card\">
																						<div class=\"card-header\">
																							Mensaje Tipo {$row['idtipo']}
																						</div>
																						<div class=\"card-body\">
																							<h5 class=\"card-title\">Descripción</h5>
																							<p class=\"card-text text-justify \">{$row['smscontent']}</p>
																							<a href=\"#\" class=\"btn btn-primary\">Editar</a>
																						</div>
																					</div>	
																				</div>
																				" ;

																			}
																		?>
																		<!--  -->
																		
																</div>
															</div>
													</div>
													<!-- fin de contenido de sms mensual... -->
										</div>
								</div>
							</div>
								
						</div>
					</div>

					
				</div>
			</main>
		</div>
	</div>
  <div class="container-fluid border border-danger">
													
	</div>
	<script  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
	
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
  <script src="bower_components/Popper/popper.min.js" ></script>  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="bower_components/alertify/js/alertify.min.js"></script>
	<script src="bower_components/AutoFormatCurrency/simple.money.format.js"></script>
	<script src="js/dataTables.checkboxes.min.js"></script>
	
	<script type="text/javascript">
	
		<?php
		if($_GET['opc']) {
			echo"
			$('#new_client_registration').removeClass(\"active\");
			$('#new_client_registration_content').hide();\n
			$('#sms_notification').removeClass(\"active\");
			$('#sms_notification_content').hide();\n
			$('#massive_notifications').removeClass(\"active\");
			$('#massive_notifications_content').hide();
			$('#active_client_currently').addClass(\"active\");
			$('#active_client_currently_content').show();\n								
			var table2 =$('#table_active_client').DataTable();
			table2.order( [ 0, 'desc' ] );
			table2.draw();
			var table2a =$('#table_no_active_client').DataTable();			
			var table2ax =$('#Table_clientes_cte1_retrasados').DataTable();	
			var table2ax15 =$('#Table_clientes_cte15_retrasados').DataTable();
			table2a.draw();
			table2ax.draw();
			table2ax15.draw();\n
			\n
			";
			
		} 
		else{
			echo"
			$('#active_client_currently').removeClass(\"active\");
			$('#active_client_currently_content').hide();
			$('#sms_notification').removeClass(\"active\");
			$('#sms_notification_content').hide();
			$('#massive_notifications').removeClass(\"active\");
			$('#massive_notifications_content').hide();
			$('#new_client_registration').addClass(\"active\");
			$('#new_client_registration_content').show();\n
			\n";
			
		}
		echo "\n";
		?>
									
		$('#new_client_registration').click(function(){
					$('#active_client_currently').removeClass("active");
					$('#active_client_currently_content').hide();
					$('#sms_notification').removeClass("active");
					$('#sms_notification_content').hide();
					$('#massive_notifications').removeClass("active");
					$('#massive_notifications_content').hide();	
					$('#new_client_registration').addClass("active");
					$('#new_client_registration_content').show();
				});		
		$('#active_client_currently').click(function(){
					$('#new_client_registration').removeClass("active");
					$('#new_client_registration_content').hide();
					$('#sms_notification').removeClass("active");
					$('#sms_notification_content').hide();
					$('#massive_notifications').removeClass("active");
					$('#massive_notifications_content').hide();	
					$('#active_client_currently').addClass("active");
					$('#active_client_currently_content').show();	
					if ( ! $.fn.DataTable.isDataTable( '#table_active_client' ) ) {
						var table_active_client =$('#table_active_client').DataTable({
									"responsive": true,
									"paging":   true,
									"searching": true,								
									"info":     true
							}
						);
						table_active_client.order( [ 0, 'desc' ] );
						table_active_client.draw();						
						var table_no_active_client =$('#table_no_active_client').DataTable();	
						table_no_active_client.draw();
							
					}		
				});			
		$('#sms_notification').click(function(){				
					$('#active_client_currently').removeClass("active");
					$('#active_client_currently_content').hide();
					$('#new_client_registration').removeClass("active");
					$('#new_client_registration_content').hide();
					$('#massive_notifications').removeClass("active");
					$('#massive_notifications_content').hide();	
					$('#sms_notification').addClass("active");
					$('#sms_notification_content').show();
					if ( ! $.fn.DataTable.isDataTable( '#Table_morosos' ) ) {
						var tableMorosos =$('#Table_morosos').DataTable({
									"responsive": true,
									"paging":   true,
									"searching": true,								
									"info":     true
							});				
						tableMorosos.order( [ 4, 'desc' ] );
						tableMorosos.draw();
						var Table_clientes_cte1_retrasados =$('#Table_clientes_cte1_retrasados').DataTable({
									"responsive": true,
									"paging":   true,
									"searching": true,								
									"info":     true
							});	
						Table_clientes_cte1_retrasados.draw();
						var Table_clientes_cte15_retrasados =$('#Table_clientes_cte15_retrasados').DataTable({
									"responsive": true,
									"paging":   true,
									"searching": true,								
									"info":     true
							});	
						Table_clientes_cte15_retrasados.draw();						
						
					}
					});	
		$(".dataTable_Morosos").on('click','.updateTel', function () { 				
			console.log("clic en update telefono");
			var id=$(this).attr('id');
			console.log($(this).attr('id'));
			$(this).toggleClass( "btn-light" );
			var telefono=$( ".telefono"+id ).val(); 
			if($.isNumeric(telefono)&&(telefono.length==10)){
						if ( (telefono).match(/^\d+$/) ) {
						//alertify.success("Número Telefónico actualizado correctamente.");
							$.ajax({
										type : 'post',
										url : 'edit_cli.php', 
										data: {id:id,telefono:telefono } ,
										success : function(data){	       	
											alertify.success(data);            	
										}
									});	
					}
						else{
							alertify.error('telefono invalido, tiene puntos decimales!!');
						}
					}
					else{
							alertify.error('telefono invalido!!');
						}
			});
				
		$('#massive_notifications').click(function(){				
			$('#active_client_currently').removeClass("active");
			$('#active_client_currently_content').hide();
			$('#sms_notification').removeClass("active");
			$('#sms_notification_content').hide();
			$('#new_client_registration').removeClass("active");
			$('#new_client_registration_content').hide();
			$('#massive_notifications').addClass("active");
			$('#massive_notifications_content').show();	
			$('#sms_masivo_container_buscar').hide();
			$('#message_box').hide();
			$('#btn_enviar').hide();
			$.ajax({
				url: "sms_statistics.php", 
				success: function(result){
					$("#div_sms_statistics").html(result);
					if ( ! $.fn.DataTable.isDataTable( '#table_statistics' ) ) {
						var statistics =$('#table_statistics').DataTable({
											"responsive": true,
											"paging":   true,
											"searching": false,								
											"info":     true
									});
						
					}
				}
			});
			
		});
		
		$(".datatable_Table_clientes_cte1_retrasados").on('click','.updateTelAtrasado', function () { 		
				var id=$(this).attr('id');
				console.log($(this).attr('id'));
				$(this).toggleClass( "btn-light" );
				var telefono=$( ".telefonoAtrasado"+id ).val(); 
				if($.isNumeric(telefono)&&(telefono.length==10)){
		        	if ( (telefono).match(/^\d+$/) ) {
   						//alertify.success("Número Telefónico actualizado correctamente.");
		        		$.ajax({
					            type : 'post',
					            url : 'edit_cli.php', 
					            data: {id:id,telefono:telefono } ,
					            success : function(data){	       	
					            	alertify.success(data);            	
				            	}
					        	});	
						}
		        	else{
		        		alertify.error('telefono invalido, tiene puntos decimales!!');
		        	}
		        }
		        else{
		        		alertify.error('telefono invalido!!');
		        	}
				});
		$(".datatable_Table_clientes_cte15_retrasados").on('click','.updateTelAtrasadoc15', function () { 			
				var id=$(this).attr('id');
				console.log($(this).attr('id'));
				$(this).toggleClass( "btn-light" );
				var telefono=$( ".telefonoAtrasadoc15"+id ).val(); 
				if($.isNumeric(telefono)&&(telefono.length==10)){
		        	if ( (telefono).match(/^\d+$/) ) {
   						//alertify.success("Número Telefónico actualizado correctamente.");
		        		$.ajax({
					            type : 'post',
					            url : 'edit_cli.php', 
					            data: {id:id,telefono:telefono } ,
					            success : function(data){	       	
					            	alertify.success(data);            	
				            	}
					        	});	
						}
		        	else{
		        		alertify.error('telefono invalido, tiene puntos decimales!!');
		        	}
		        }
		        else{
		        		alertify.error('telefono invalido!!');
		        	}
				});
		$(".dataTable_Morosos").on('click','.smsclientMoroso', function () { 													
				console.log($(this).attr('id'));
				var id=$(this).attr('id');
				alertify.confirm("Desea enviar mensaje de texto a cliente moroso?",
			    function(){				    
					var telefono=$( ".telefono"+id ).val();
					var smsText="Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio. www.ispexperts.com";
					
					$.post("smst.php",
					    {
								telefono: telefono,
								msj: smsText
							},
							function(data,status){
								var res = data.substring(0,2);
								console.log("status"+status);
							if (res=="ok"){
									alertify.success("Mensaje ha sido enviado con éxito a cliente moroso");
										
								}
										else if (res=="no"){
											alertify.error("Información enviada contiene errores a cliente moroso");
																						
										}
											else {
												alertify.alert("Fordibben from morosos scheme ");
																								
											} 			      
							});
					

					},
			    function(){
			    alertify.error('Cancel');
			    });
				});	
		$(".datatable_Table_clientes_cte1_retrasados").on('click','.smsclientAtrasado', function () { 		
				console.log("id:"+$(this).attr('id'));
				var id=$(this).attr('id');
				alertify.confirm("Desea enviar mensaje de texto a cliente atrasado cliente corte 1 ?",
			    function(){
				    			
					var telefono=$( ".telefonoAtrasado"+id ).val();
					var smsText="Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio. www.ispexperts.com";
					$.post("smst.php",
					    {
					      telefono: telefono,
					      msj: smsText
					    },
					    function(data,status){
					      var res = data.substring(0,2);
					      console.log("status"+status);
						  if (res=="ok"){
						  		alertify.success("Mensaje ha sido enviado con éxito a cliente corte 1");
						  		 
						  	}
					      		else if (res=="no"){
					      			alertify.error("Información enviada contiene errores a cliente corte 1");
					      								      			
					      		}
					      			else {
					      				alertify.alert("Fordibben from corte 1 scheme ");
					      									      				
					      			} 			      
					    });
					
			    },
			    function(){
			    alertify.error('Cancel');
			    });
				});	
		$(".datatable_Table_clientes_cte15_retrasados").on('click','.smsclientAtrasadoc15', function () { 						
				console.log("id:"+$(this).attr('id'));
				var id=$(this).attr('id');
				alertify.confirm("Desea enviar mensaje de texto a cliente Corte 15 atrasado?",
			    function(){
				    var telefono=$( ".telefonoAtrasadoc15"+id ).val();
					var smsText="Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio. www.ispexperts.com";
					$.post("smst.php",
					    {
					      telefono: telefono,
					      msj: smsText
					    },
					    function(data,status){
					      var res = data.substring(0,2);
					      console.log("status"+status);
						  if (res=="ok"){
						  		alertify.success("Mensaje ha sido enviado con éxito cliente corte 15");
						  		 
						  	}
					      		else if (res=="no"){
					      			alertify.error("Información enviada contiene errores cliente corte 15");
					      								      			
					      		}
					      			else {
					      				alertify.alert("Fordibben from corte 15 scheme ");
					      									      				
					      			} 			      
					    });
					 
					
			    },
			    function(){
			    alertify.error('Cancel');
			    });
				});
		$(".datatable_table_active_client").on('click','.suspender', function () { 																		
				console.log("id:"+$(this).attr('id'));
				var x=$(this).attr('id');
				var idCli = x.match(/\d+/); // 123456	
				var idc=idCli*1;
				console.log("Id del usauario a suspender:"+idc);		
				var source="suspender";
				alertify.prompt("Escriba razón de suspensión del servicio.Gracias", "",
					  function(evt, value ){
					  	var detalle=value;
					  	$("#"+x).removeClass("btn-primary");
					  	$("#"+x).addClass("disabled");
					  	$("#"+x+" > i").removeClass("text-dark");
					  	$("#"+x+" > i").addClass("text-danger");
					    $.ajax({
					            type : 'post',
					            url : 'upd_cli.php', 
					            data: { idClient:idc,detalle:detalle,source:source } ,
					            success : function(data){	       	
					            	alertify.success(data); 
					            	window.location.href = 'client.php?opc=reload';           	
				            	}
					        	});	
					  },
					  function(){
					    alertify.error('Cancel');
					  })
					  ;
				});											

		$("#table_no_active_client").on('click','.reconectar', function () { 																		
		console.log("id:"+$(this).attr('id'));
		var x=$(this).attr('id');
		var idCli = x.match(/\d+/); // 123456	
		var idc=idCli*1;
		console.log("Id del usauario a reconectar:"+idc);		
		var source="reconectar";
		alertify.prompt("Escriba razón de reconexión del servicio.Gracias", "",
				function(evt, value ){
					var detalle=value;
					$("#"+x).removeClass("btn-primary");
					$("#"+x).addClass("disabled");
					$("#"+x+" > i").removeClass("text-dark");
					$("#"+x+" > i").addClass("text-danger");
					$.ajax({
									type : 'post',
									url : 'upd_cli.php', 
									data: { idClient:idc,detalle:detalle,source:source } ,
									success : function(data){	       	
										alertify.success(data); 
										window.location.href = 'client.php?opc=reload';           	
									}
								});	
				},
				function(){
					alertify.error('Cancel');
				})
				;
		});	

		$('#save').click(function(){

					if ( validEmail( $("#email").val() ) ) {
						$('#mail').removeClass("border border-danger");
	                    var valorPlan=$( "#valor-plan" ).val();
						if($.isNumeric(valorPlan) && (valorPlan!="") ){
							$( "#valor-plan" ).removeClass( "border border-danger" );
							var recibo=0;
							var name=$("#name").val();
							var lastName=$("#last-name").val();
							var cedula=$("#cedula").val();
							var address=$("#address").val();
							var ciudad=$("#ciudad").val();
							var departamento=$("#departamento").val();
							var phone=$("#phone").val();
							var email=$("#email").val();
							var corte=$("#corte").val();
							var plan=$("#plan").val();
							var velocidadPlan=$("#velocidad-plan").val();
							var ipAddress=$("#ip-address").val();
							var generarFactura=$("#generar-factura").val();
							var valorAfiliacion=$("#valorAfiliacion").val();
							var standby=$("#standby").val();
							console.log('valor-plan='+valorPlan+' -plan='+plan+' -velocidadPlan='+velocidadPlan+' -Corte='+corte+' -Standby='+standby);
							alertify.confirm("Desea imprimir recibo?",
								function(){
								  		recibo=1;
								    	
						  				$.ajax({
								            type : 'post',
								            url : 'new_cli.php', 
								            data: {valorPlan:valorPlan,name:name,lastName:lastName,address:address,ciudad:ciudad,departamento:departamento,phone:phone,email:email,corte:corte,plan:plan,velocidadPlan:velocidadPlan,cedula:cedula,generarFactura:generarFactura,ipAddress:ipAddress,standby:standby,recibo:recibo,valorAfiliacion:valorAfiliacion } ,
								            success : function(data){
								            	var result=data.split(':');	 
								            	var idCl=result[0];
								            	var messag=result[1]; 
								            	alertify.success(messag);
								            	if(recibo==1){
									            	if(idCl!='Error'){
									            		window.location.href = 'recibo_new_cli.php?rpp=1&idc='+idCl;		
									            	}				            		
								            	}
								            	

								            }
								        });	
								    	
								},
								function(){
								    	recibo=1;
								    	
						  				$.ajax({
								            type : 'post',
								            url : 'new_cli.php', 
								            data: {valorPlan:valorPlan,name:name,lastName:lastName,address:address,ciudad:ciudad,departamento:departamento,phone:phone,email:email,corte:corte,plan:plan,velocidadPlan:velocidadPlan,cedula:cedula,generarFactura:generarFactura,ipAddress:ipAddress,standby:standby,recibo:recibo } ,
								            success : function(data){
								            	var result=data.split(':');	 
								            	var idCl=result[0];
								            	var messag=result[1]; 
								            	alertify.success(messag);
								            	if(recibo!=1){
									            	if(idCl!='Error'){
									            		window.location.href = 'recibos.php?rpp=1&idc='+idCl;		
									            	}				            		
								            	}
								            	else{					            		
									            	
									            	$('#new_client_registration').removeClass("active");
													$('#new_client_registration_content').hide();
													$('#sms_notification').removeClass("active");
													$('#sms_notification_content').hide();
													$('#active_client_currently').addClass("active");
													$('#active_client_currently_content').show();
								            	}

								            }
								        });	
								}
								).set('labels', {ok:'Sí', cancel:'No'});
						}
						else{
							$( "#valor-plan" ).addClass( "border border-danger" );
							alertify.error("valor Incorrecto");
						}
            		}
            		else{
            			$( "#email" ).addClass( "border border-danger" );
						alertify.error("Email Incorrecto");
            		}						 
				
		});	
		
		
		var ct=0;
		$('#sms_masivo_btn_buscar').click(function(){
			//$('#criterios_content').hide();
			$('#spinner-buscar').addClass('spinner-border');			
			$('#btn_enviar').show();
			$('#sms_masivo_container_buscar').show();	
			$('#message_box').show();	
			$('#spinner-enviar').removeClass('spinner-border');				
			var name=$("#sms_masive_name").val();	
			var address=$("#sms_masive_address").val();
			var ciudad=$("#sms_masive_city").val();		
			var corte=$("#payment_date").val();
			var criterioFacturacion=$("#client_state").val();
			
			ct+=1;
			console.log('Nombres='+name+' -direccion='+address+' -ciudad='+ciudad+' -Corte='+corte+'-cont='+ct);
			$.ajax({
				type : 'post',
				url : 'tableMasiveSms.php', 
				data: {name:name,address:address,ciudad:ciudad,corte:corte,criterioFacturacion:criterioFacturacion } ,
				success : function(data){	       	
					alertify.success("Información en la tabla ha sido actualizada"); 
					//console.log(data);
					$('#sms_masivo_container_buscar').html(data);
					var table = $('#table_client_to_sms').DataTable({
					"responsive": true,
					"paging":   true,
					"searching": true,								
					"info":     true	,
					'columnDefs': [
						{
								'targets': 0,
								'checkboxes': true
						}
					],
					'order': [[1, 'asc']]
					});   
					$('#spinner-buscar').removeClass('spinner-border');
					
				  $('#btn_enviar').click(function(){
						$('#spinner-enviar').addClass('spinner-border');
						var message=$('#sms_text_content').val();				
						var rows_selected = table.column(0).checkboxes.selected();
						$.each(rows_selected, function(index, rowId){
						console.log("Seleccionado:"+rowId+"-texto: "+message);
						table.column(0).checkboxes.deselect();						
      			});
						var iddata=rows_selected.join(",");
						if(iddata&&message){
							console.log("datos con formato:"+iddata);
							$.ajax({
								type: 'post' ,
								url:  'smsender.php',
								data: {datos:iddata,message:message} , 
								success: function(data){
									console.log('Respuesta:'+data);
									alertify.dismissAll();
									alertify.success('Solicitud ha procesada');
									$('#spinner-enviar').removeClass('spinner-border');	
								}
							});	
						}
						else{
							$('#spinner-enviar').removeClass('spinner-border');	
							alertify.dismissAll();
							if(!message)
								alertify.error('No has seleccionado mensaje para enviar!');		
						  if (!iddata) 
								alertify.error('No has seleccionado clientes!');

						} 
					});	    	
				}
			});	
			
		
		});	
		
		


		function validEmail(email) {
         	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    		return re.test(email);
        };  

	</script>

</body>
</html>