<?php
header("Location: /public/registerPay.php");
exit; 
?>
<?php 
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true){
	header('Location: ../login/index.php');
	exit;
}
else{
		$user=$_SESSION['username'];
}
if($_SESSION['role']=='tecnico'){
	header('Location: tick.php');
}
if($_SESSION['role']=='cajero'){
    header('Location: registerPay.php');
}
?>
<!DOCTYPE html>

<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>DevXm-Administrador ISP</title>
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">    
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet">
	<link rel="stylesheet" href="../bower_components/DataTables/media/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="../bower_components/alertify/css/alertify.min.css" />

	<link rel="stylesheet" href="../bower_components/alertify/css/themes/default.min.css" />
	<link rel="stylesheet" href="../css/fontello.css">
	<link rel="stylesheet" href="../css/estilos.css">
	<style>
		.caja {
			border-bottom: 1px solid #000;
		}
	</style>
</head>
<body>
	<?php 
		include("../login/db.php");
		$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
		if ($mysqli->connect_errno) {
	    	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
			}	
		mysqli_set_charset($mysqli,"utf8");
		$year=date("Y");
		$mons = array(1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre");
		$month = date('n');
		$month_name = $mons[$month];
		$today= date("j");
	?>	
	<div class="container-fluid px-0">		
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top   ">		
			<div class="container img-logo " >
				<img src="img/wisp.png">
				<!-- Nos sirve para agregar un logotipo al menu -->
				<a href="main.php" class="navbar-brand ">DevXm</a>
				
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
							<a href="registerPay.php" class="nav-link "><i class="icon-money"></i>Registrar Pago</a>
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
					<a href="../login/logout.php"><i class="icon-logout"></i><span>Salir</span></a>
				</nav>
			</div>

			<main class="main col">
				<div class="row">
					<div class="columna col-lg-7">
						
						<div class=" nuevo_contenido">
							<h3 class="titulo">Egresos</h3>
							<!-- inicio de contenido de pagina -->
							<div class="card text-center">
	  							<div class="card-header">
	    							<ul class="nav nav-tabs card-header-tabs">
	     								<li class="nav-item">
	       									 <a class="nav-link active" id="new" href="#">Nuevo </a>
	      								</li>
    							  		<li class="nav-item">
	        								<a class="nav-link " id="bus" href="#">Buscar </a>
	      								</li>
	      								<li class="nav-item">
	        								<a class="nav-link disabled" id="edi" href="#">Editar </a>
	      								</li>
	    						   	</ul>
	 				 			</div>
	  							<div class="card-block">
	  								
	  								<div id="content-1" class="px-3 py-3 text-left">
		    						<h4 class="card-title mt-4">Concepto</h4>	
		    							<p class="card-text ">1. Seleccione opcion.</p>	 
		    							<div class="my-3">
		    								<select class="custom-select " id="egr">
											  <option value="0" selected>Selecione tipo de egreso.</option>
											  <option value="1">Pago de servicio de  luz.</option>
											  <option value="2">Pago de servicio de  Agua.</option>
											  <option value="3">Pago de servicio de  Gas.</option>
											  <option value="4">Pago de servicio de  Internet.</option>
											  <option value="5">Pago de servicio de  Arrendamiento.</option>
											  <option value="6">Compra de dotación.</option>
											  <option value="7">Pago de gasolina o vale de transporte.</option>
											  <option value="8">Pago de impuestos.</option>
											  <option value="9">Pago de deuda.</option>
											  <option value="10">Pago de sueldos.</option>
											  <option value="11">Compra de materia prima.</option>
											  <option value="12">Pago a proveedores.</option>
											  <option value="13">Pago de honorarios a Consultores.</option>
											  <option value="14">Pago Administrativo.</option>
											  <option value="15">Pago servicio de Mensajeria.</option>
											  <option value="16">Otro.</option>
											</select>
		    							</div> 
		    							<p class="card-text ">2. Detalles.</p>	
		    							<div class="my-3">
		    								<div class="form-group">
											    <label for="textarea">Breve descripcion adicional del Egreso</label>
											    <textarea class="form-control"  id="det" rows="3"></textarea>
											 </div>
		    							</div> 
		    							 <p class="card-text ">3. Valor.</p>
		    							 <div class="my-3">
		    							   <input class="form-control" type="number" value="" id="val" >
		    							 </div>							
		    							<a  class="btn btn-primary" id="save">Guardar</a>  									
	  								</div>
	  								<div id="content-2" class="px-3 py-3 text-left">
	  									<h4 class="card-title mt-4">Lista de egresos HOY</h4>	
	  									<table id="egrList" class="display compact table text-dark table-bordered table-responsive  table-hover ">
											<thead  class="bg-primary">
											<tr>
												<td>Fecha</td>
												<td>Hora</td>
												<td>Valor</td>
												<td>Concepto</td>
												<td>Detalle</td>
												<td>Cajero</td>										
											</tr>
											</thead>
											
											<tbody >										
										<?php 					
											$sql = "SELECT * FROM `egresos` WHERE MONTH(fecha) = $month AND YEAR(fecha) = $year AND DAY(fecha) = $today ORDER BY `egresos`.`id-egreso` DESC  ";
											if ($result = $mysqli->query($sql)) {
												$su=0;
												while ($row = $result->fetch_assoc()) {
													$fecha=$row["fecha"];
													$hora=$row["hora"];
													$valor=$row["valor"];
													$su+=$valor;
													$concepto=$row["concepto"];
													$detalle=$row["detalle"];
													$cajero=$row["cajero"];
													echo "<tr class=\"text-center  \">";				
													echo "<td>".$fecha."</td>";
													echo "<td>".$hora."</td>";
													echo "<td>".$valor."</td>";
													echo "<td>".$concepto."</td>";	
													echo "<td>".$detalle."</td>";											
													echo "<td>".$cajero."</td>";
													echo "</tr>";		
													}
											    	$result->free();
												}
										?>		
											</tbody>
											<tfoot class="py-3 text-right">
												<tr>
													
													<td colspan="3">$<?php echo number_format($su,0);?></td>
													<td colspan="3"></td>
													
												</tr>
											</tfoot>
										</table>
										<h4 class="card-title mt-4">Lista de egresos Abril</h4>	
	  									<table id="egrListmonth" class="display compact table text-dark table-bordered table-responsive  table-hover ">
											<thead  class="bg-dark">
											<tr>
												<td>Fecha</td>
												<td>Hora</td>
												<td>Valor</td>
												<td>Concepto</td>
												<td>Detalle</td>
												<td>Cajero</td>										
											</tr>
											</thead>
											
											<tbody >										
										<?php 					
											$sql = "SELECT * FROM `egresos`WHERE MONTH(fecha) = $month AND YEAR(fecha) = $year ORDER BY `egresos`.`id-egreso` DESC  ";
											if ($result = $mysqli->query($sql)) {
												$sux=0;
												while ($row = $result->fetch_assoc()) {
													$fecha=$row["fecha"];
													$hora=$row["hora"];
													$valor=$row["valor"];
													$sux+=$valor;
													$concepto=$row["concepto"];
													$detalle=$row["detalle"];
													$cajero=$row["cajero"];
													echo "<tr class=\"text-center  \">";				
													echo "<td>".$fecha."</td>";
													echo "<td>".$hora."</td>";
													echo "<td>".$valor."</td>";
													echo "<td>".$concepto."</td>";	
													echo "<td>".$detalle."</td>";											
													echo "<td>".$cajero."</td>";
													echo "</tr>";		
													}
											    	$result->free();
												}
										?>		
											</tbody>
											<tfoot class="py-3 text-right">
												<tr>
													
													<td colspan="3"> $<?php echo number_format($sux,0);?> </td>
													<td colspan="3"></td>
													
												</tr>
											</tfoot>
										</table>	 
	  								</div>
	  							</div>
							</div>
							<!-- fin de contenido de pagina -->	
						</div>
					</div>

					<div class="columna col-lg-5">
						<div class="widget estadisticas">
							<h3 class="titulo">Egreso Mensual</h3>
							<div class="contenedor d-flex flex-wrap">
								<div class="caja">
									<h3>$<?php echo number_format($su,0) ?></h3>
									<p>Hoy</p>
								</div>
								<div class="caja">
									<h3>$<?php echo number_format($sux,0) ?></h3>
									<p>Total</p>
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
						<p>Copyright ©2014-2024 DevXm-Administrador ISP - <small>All Rights Reserved.</small></p>	
									
			</div>
		</div>
		<div class="row">
			<div class="col text-light bg-dark  d-flex justify-content-center">								
						
						<p><i class="icon-facebook-official"></i><i class="icon-twitter-squared"></i></p>				
			</div>
		</div>

	</div>

	
	<script src="../bower_components/jquery/dist/jquery.min.js"></script>
	<script src="../bower_components/DataTables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/Popper/popper.min.js" ></script>  
    <script src="../bower_components/bootstrap/dist/js/bootstrap.js"></script> 
	<script src="../bower_components/alertify/js/alertify.min.js"></script>
	<script src="../bower_components/AutoFormatCurrency/simple.money.format.js"></script>

	<script type="text/javascript">
		$('#content-2').hide();
		<?php 
		if(isset($_GET['search'])){

			$search= mysqli_real_escape_string($mysqli, $_REQUEST['search']);
			echo"
			$('#new').removeClass(\"active\");
			$('#content-1').hide();
			$('#bus').addClass(\"active\");
			$('#content-2').show();
			$('#egrList').DataTable();
			$('#egrListmonth').DataTable();
			";
		}
		 ?>
		
		$('#bus').click(function(){
				$('#new').removeClass("active");
				$('#content-1').hide();
				$('#bus').addClass("active");
				$('#content-2').show();
				$('#egrList').DataTable();
				$('#egrListmonth').DataTable();
				});	
		$('#new').click(function(){
				$('#bus').removeClass("active");
				$('#content-2').hide();
				$('#new').addClass("active");
				$('#content-1').show();
				});

		$('#save').click(function(){
				var egr=$( "#egr" ).val();
				if (egr==0){
					$( "#egr" ).addClass( "border border-danger" );					 
				}
				else {
					$( "#egr" ).removeClass( "border border-danger" );					 
					var det=$( "#det" ).val();
					if(det==""){
						$( "#det" ).addClass( "border border-danger" );						 
					}
					else{
						$( "#det" ).removeClass( "border border-danger" );						 
						var val=$( "#val" ).val();
						if($.isNumeric(val) && (val!="") ){
							$( "#val" ).removeClass( "border border-danger" );
							var egrtxt=$("#egr").find(":selected").text();
							console.log('egr='+egr+'detalle='+det+'valor='+val+'egreso='+egrtxt);
			  				$.ajax({
					            type : 'post',
					            url : '../fetch_egr.php', 
					            data: {egr:egr, det:det, val:val, egrtxt:egrtxt } ,
					            success : function(data){	       	
					            	alertify.success(data);
					            	$('#new').removeClass("active");
									$('#content-1').hide();
									$('#bus').addClass("active");
									$('#content-2').show();

									window.location.href = 'egr.php?search=1';
													            						            	
					            }
					        	});	
						}
						else{
							$( "#val" ).addClass( "border border-danger" );
							alertify.success("valor Incorrecto");
						}
					}
				}
					
				});	
	</script>

</body>
</html>