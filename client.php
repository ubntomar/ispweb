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
	<title>Wisdev-Administrador ISP</title>
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">    
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet">
	<link rel="stylesheet" href="bower_components/DataTables/media/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="bower_components/alertify/css/alertify.min.css" />

	<link rel="stylesheet" href="bower_components/alertify/css/themes/default.min.css" />
	<link rel="stylesheet" href="css/fontello.css">
	<link rel="stylesheet" href="css/estilos.css">
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
			<div class="barra-lateral col-12 col-sm-auto">				
				<nav class="menu d-flex d-sm-block justify-content-center flex-wrap">
					<a href="tick.php"><i class="icon-pinboard"></i><span>Tickets</span></a>
					<a href="fact.php"><i class="icon-docs-1"></i><span>Facturas</span></a>
					<a href="client.php"><i class="icon-users"></i><span>Clientes</span></a>
					<a href="mktik.php"><i class="icon-network"></i><span>Mktik</span></a>
					<a href="egr.php"><i class="icon-money"></i><span>Egresos</span></a>
					<a href="login/logout.php"><i class="icon-logout"></i><span>Salir</span></a>
				</nav>
			</div>

			<main class="main col">
				<div class="row">
					<div class="columna col-11">						
						<div class=" nuevo_contenido">
							<h3 class="titulo">Configuración de Clientes</h3>
							<!-- inicio de contenido de pagina -->
							<div class="card text-center">
	  							<div class="card-header">
	    							<ul class="nav nav-tabs card-header-tabs">
	     								<li class="nav-item">
	       									 <a class="nav-link active" id="new" href="#">Afiliar Nuevo Cliente </a>
	      								</li>
    							  		<li class="nav-item">
	        								<a class="nav-link " id="cliente_activo" href="#">Clientes  </a>
	      								</li>
	      								<li class="nav-item">
	        								<a class="nav-link " id="mor" href="#">Notificaciones vía SMS</a>
	      								</li>
												<li class="nav-item">
	        								<a class="nav-link " id="sms_masivos" href="#">SMS Masivos</a>
	      								</li>
	      								<li class="nav-item">
	        								<a class="nav-link disabled" id="edi" href="#">Editar </a>
	      								</li>
	    						   	</ul>
	 				 			</div>
	  							<div class="card-block">
	  								
	  								<div id="content-1" class="px-3 py-3 text-left">
	  									<p class="card-text ">#. Nombres.</p>
		    							<div class="my-3">
		    							   <input class="form-control" type="text" value="" id="name" >
		    							</div>
		    							<p class="card-text ">#. Apellidos.</p>
		    							<div class="my-3">
		    							   <input class="form-control" type="text" value="" id="last-name" >
		    							</div>
		    							<p class="card-text ">#. Cedula.</p>
		    							<div class="my-3">
		    							   <input class="form-control" type="text" value="" id="cedula" >
		    							</div>	
		    							<p class="card-text ">#. Dirección.</p>
		    							<div class="my-3">
		    							   <input class="form-control" type="text" value="" id="address" >
		    							</div>
		    							<p class="card-text ">#. Ciudad.</p>
		    							<div class="my-3">
		    							   <input class="form-control" type="text" value="" id="ciudad" >
		    							</div>
		    							<p class="card-text ">#. Departamento.</p>
		    							<div class="my-3">
		    							   <input class="form-control" type="text" value="" id="departamento" >
		    							</div>
		    							<p class="card-text ">#. Telefono.</p>
		    							<div class="my-3">
		    							   <input class="form-control" type="text" value="" id="phone" >
		    							</div>
		    							<p class="card-text ">#. Email.</p>
		    							<div class="my-3">
		    							   <input class="form-control" type="email" value="" id="email" >
		    							</div>
		    							<p class="card-text mt-3 ">#. Corte de facturación.</p>	 
		    							<div class="my-3">
		    								<select class="custom-select " id="corte">
											  <option value="1" selected>Corte 1.</option>
											  <option value="15">Corte 15.</option>								  
											</select>
		    							</div>		
		    								
		    							<p class="card-text mt-3 ">#. Seleccione Tipo de Plan.</p>	 
		    							<div class="my-3">
		    								<select class="custom-select " id="plan">
											  <option value="Residencial" selected>Residencial.</option>
											  <option value="Comercial">Comercial.</option>								  
											</select>
		    							</div>
		    							<p class="card-text mt-3 ">#. Velocidad de Plan.</p>	 
		    							<div class="my-3">
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
		    							<div class="my-3">
		    							   	<input class="form-control" type="number" value="" id="valor-plan" >
		    							</div>	
		    							<p class="card-text ">#. Dirección ip.</p>

		    							<div class="my-3">
		    							   	<input class="form-control" type="text" value="" id="ip-address" >
		    							</div>		
		    							<p class="card-text mt-3 ">#. Generar Factura de inmediato ?. (Si necesito que el pago del primer mes quede aparte de lo que van a pagar de afiliación le doy sí. Entonces voy a Registrar Pago y ahí ya le aparece para que genere el primer pago de una vés.)</p>	 
		    							<div class="my-3">
		    								<select class="custom-select " id="generar-factura">		    								  
											  <option value="1" >Si.</option>
											  <option value="0" selected >No.</option>											  
											</select>	
		    							</div>
		    							<p class="card-text mt-3 ">#. Paga el primer mes de servicio con lo que está pagando de afiliación? . Si no sabe , dejarlo en No.</p>	 
		    							<div class="my-3">
		    								<select class="custom-select " id="standby">		    								  
											  <option value="1" >Si.</option>
											  <option value="0" selected >No.</option>											  
											</select>	
		    							</div>
		    							<p class="card-text ">#. Valor de Afiliación a servicio de Internet. 150.000 dentro de Guamal ,180.000 rural simple ,220.000 rural complicada.</p>
		    							<div class="my-3">
		    							   	<input class="form-control" type="number" value="" id="valorAfiliacion" >
		    							</div>   	
		    							<a  class="btn btn-primary" id="save">Guardar</a>  	

	  								</div>
	  								<div id="content-2" class="px-3 py-3 text-left">
	  									<h4 class="card-title mt-4">Lista de Clientes <strong>Activos</strong> </h4>	
	  									<table id="egrList" class="display compact table text-dark table-bordered table-responsive  table-hover ">
											<thead  class="bg-primary">
											<tr><td>cod</td>
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
										<h1>Recaudo estimado Agosto : <?php echo " $ $contPago"; ?> </h1>
										<hr>
										<hr>
										<h4 class="card-title mt-4">Lista de Clientes en <strong> SUSPENSIÓN</strong> </h4>	
	  									<table id="clientes-inactivos" class="display compact table text-dark table-bordered table-responsive  table-hover ">
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
										<hr>
										<hr>
										<h4 class="card-title mt-4">Lista de Clientes de este mes  <strong>Corte 1 retrasados </strong> de pago  </h4>	
	  									<table id="clientes-retrasados" class="display compact table text-dark table-bordered table-responsive  table-hover ">
											<thead  class="bg-danger text-light">
											<tr><td>cod</td>
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
	  									<table id="clientes-retrasadosc15" class="display compact table text-dark table-bordered table-responsive  table-hover ">
											<thead  class="bg-info text-dark">
											<tr><td>cod</td>
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
	  								</div>

	  								<div id="content-3" class="px-3 py-3 text-left">
	  									<h4 class="card-title mt-4">Morosos</h4>	
	  									<table id="morList" class="display compact table text-dark table-bordered table-responsive  table-hover ">
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
	  								</div>

	  							</div>
							</div>
							<!-- fin de contenido de pagina -->	
						</div>
					</div>

					<div class="columna col-12">
						<div class="widget estadisticas">
							<h3 class="titulo">Estadisticas</h3>
							<div class="contenedor d-flex flex-wrap">
								<div class="caja">
									<h3>15,236</h3>
									<p>Visitas</p>
								</div>
								<div class="caja">
									<h3>1,831</h3>
									<p>Registros</p>
								</div>
								<div class="caja">
									<h3>$160,548</h3>
									<p>Ingresos</p>
								</div>
							</div>
						</div>

						<div class="widget comentarios">
							<h3 class="titulo">Comentarios</h3>
							<div class="contenedor">
								<div class="comentario d-flex flex-wrap">
									<div class="foto">
										<a href="#">
											<img src="img/persona1.jpg" width="100" alt="">
										</a>
									</div>
									<div class="texto">
										<a href="#">Jhon Doe</a>
										<p>en <a href="#">Mi primer entrada</a></p>
										<p class="texto-comentario">
											Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis natus ex inventore provident modi id distinctio non minus, magni quia officiis, vel debitis doloremque ratione, consequuntur omnis hic voluptatem asperiores?
										</p>
									</div>
									<div class="botones d-flex justify-content-start flex-wrap w-100">
										<button class="aprobar"><i class="icono icon-ok"></i>Aprobar</button>
										<button class="eliminar"><i class="icono icon-cancel"></i>Eliminar</button>
										<button class="bloquear"><i class="icono icon-flag"></i>Bloquear Usuario</button>
									</div>
								</div>	

								<div class="comentario d-flex flex-wrap">
									<div class="foto">
										<a href="#">
											<img src="img/persona2.jpg" width="100" alt="">
										</a>
									</div>
									<div class="texto">
										<a href="#">Jhon Doe</a>
										<p>en <a href="#">Mi primer entrada</a></p>
										<p class="texto-comentario">
											Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis natus ex inventore provident modi id distinctio non minus, magni quia officiis, vel debitis doloremque ratione, consequuntur omnis hic voluptatem asperiores?
										</p>
									</div>
									<div class="botones d-flex justify-content-start flex-wrap w-100">
										<button class="aprobar"><i class="icono icon-ok"></i>Aprobar</button>
										<button class="eliminar"><i class="icono icon-cancel"></i>Eliminar</button>
										<button class="bloquear"><i class="icono icon-flag"></i>Bloquear Usuario</button>
									</div>
								</div>

								<div class="comentario d-flex flex-wrap">
									<div class="foto">
										<a href="#">
											<img src="img/persona3.jpg" width="100" alt="">
										</a>
									</div>
									<div class="texto">
										<a href="#">Jhon Doe</a>
										<p>en <a href="#">Mi primer entrada</a></p>
										<p class="texto-comentario">
											Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis natus ex inventore provident modi id distinctio non minus, magni quia officiis, vel debitis doloremque ratione, consequuntur omnis hic voluptatem asperiores?
										</p>
									</div>
									<div class="botones d-flex justify-content-start flex-wrap w-100">
										<button class="aprobar"><i class="icono icon-ok"></i>Aprobar</button>
										<button class="eliminar"><i class="icono icon-cancel"></i>Eliminar</button>
										<button class="bloquear"><i class="icono icon-flag"></i>Bloquear Usuario</button>
									</div>
								</div>				
							</div>
						</div>
					</div>
				</div>
			</main>
		</div>
	</div>

	<div class="container-fluid 	">
		<div class="row">
			<div class="col text-light bg-dark py-2 d-flex justify-content-center footer-text">								
						<p>Copyright ©2014-2017 Wisdev-Administrador ISP - <small>All Rights Reserved.</small></p>	
									
			</div>
		</div>
		<div class="row">
			<div class="col text-light bg-dark  d-flex justify-content-center">								
						
						<p><i class="icon-facebook-official"></i><i class="icon-twitter-squared"></i></p>				
			</div>
		</div>

	</div>
	<script src="bower_components/jquery/dist/jquery.min.js"></script>
	<script src="bower_components/DataTables/media/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/Popper/popper.min.js" ></script>  
    <script src="bower_components/bootstrap/dist/js/bootstrap.js"></script> 
	<script src="bower_components/alertify/js/alertify.min.js"></script>
	<script src="bower_components/AutoFormatCurrency/simple.money.format.js"></script>
	<script type="text/javascript">
		<?php
		if($_GET['opc']) {
			echo"
			$('#new').removeClass(\"active\");
			$('#content-1').hide();\n
			$('#mor').removeClass(\"active\");
			$('#content-3').hide();\n
			$('#cliente_activo').addClass(\"active\");
			$('#content-2').show();\n								
			var table2 =$('#egrList').DataTable();
			table2.order( [ 0, 'desc' ] );
			table2.draw();
			var table2a =$('#clientes-inactivos').DataTable();			
			var table2ax =$('#clientes-retrasados').DataTable();	
			var table2ax15 =$('#clientes-retrasadosc15').DataTable();
			table2a.draw();
			table2ax.draw();
			table2ax15.draw();\n
			\n
			";
			
		} 
		else{
			echo"
			$('#cliente_activo').removeClass(\"active\");
			$('#content-2').hide();
			$('#mor').removeClass(\"active\");
			$('#content-3').hide();
			$('#new').addClass(\"active\");
			$('#content-1').show();\n
			\n";
			
		}
		echo "\n";
		?>
		$('#cliente_activo').click(function(){
				$('#new').removeClass("active");
				$('#content-1').hide();
				$('#mor').removeClass("active");
				$('#content-3').hide();
				$('#cliente_activo').addClass("active");
				$('#content-2').show();								
				var table2 =$('#egrList').DataTable();
				table2.order( [ 0, 'desc' ] );
				table2.draw();
				var table2a =$('#clientes-inactivos').DataTable();	
				table2a.draw();
				var table2ax =$('#clientes-retrasados').DataTable();	
				table2ax.draw();
				var table2ax15 =$('#clientes-retrasadosc15').DataTable();	
				table2ax15.draw();						
				});			
		$('#new').click(function(){
				$('#cliente_activo').removeClass("active");
				$('#content-2').hide();
				$('#mor').removeClass("active");
				$('#content-3').hide();
				$('#new').addClass("active");
				$('#content-1').show();
				});		
		$('#mor').click(function(){				
				$('#cliente_activo').removeClass("active");
				$('#content-2').hide();
				$('#new').removeClass("active");
				$('#content-1').hide();
				$('#mor').addClass("active");
				$('#content-3').show();
				var table3 =$('#morList').DataTable();
				table3.order( [ 4, 'desc' ] );
				table3.draw();
				
				});	
		$('.updateTel').click(function(){// para clientes morosos
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
		$('.updateTelAtrasado').click(function(){//corte 1
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
		$('.updateTelAtrasadoc15').click(function(){
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
		$('.smsclientMoroso').click(function(){//clientes morosos
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
		$('.smsclientAtrasado').click(function(){//corte 1 
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
		$('.smsclientAtrasadoc15').click(function(){
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
		$('.suspender').click(function(){
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
									            	
									            	$('#new').removeClass("active");
													$('#content-1').hide();
													$('#mor').removeClass("active");
													$('#content-3').hide();
													$('#cliente_activo').addClass("active");
													$('#content-2').show();
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

		function validEmail(email) {
         	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    		return re.test(email);
        };  

	</script>

</body>
</html>