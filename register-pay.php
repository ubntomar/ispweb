<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
		header('Location: login/index.php');
		exit;
	} else {
	$user = $_SESSION['username'];
}
if($_SESSION['role']=='tecnico'){
	header('Location: tick.php');
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

</head>

<body>
	<?php
	include("login/db.php");
	$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}
	mysqli_set_charset($mysqli, "utf8");
	date_default_timezone_set('America/Bogota');
	$today = date("Y-m-d");
	?>
	<div class="container-fluid px-0">
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top   ">
			<div class="container img-logo ">
				<img src="img/wisp.png">
				<!-- Nos sirve para agregar un logotipo al menu -->
				<a href="main.php" class="navbar-brand">Wisdev</a>

				<!-- Nos permite usar el componente collapse para dispositivos moviles -->
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Menu de Navegacion">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse " id="navbar">
					<ul class="navbar-nav  navclass ">
						<li class="nav-item ">
							<a href="#" class="nav-link"><i class="icon-money"></i>Cierre de caja <span class="sr-only">(Actual)</span></a>
						</li>
						<li class="nav-item  ">
							<a href="register-pay.php " class="nav-link link-border "><i class="icon-money "></i>Registrar Pago</a>
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
					<div class=" ml-auto  ">
						<ul class="nav navbar-nav   ">
							<li class="nav-item ">
								<a class="nav-link disabled text-white "><i class="icon-user"></i><?php echo "Hola " . $_SESSION['username']; ?></a>
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
					<a href="fact.php"><i class="icon-doc-text"></i><span>Facturas</span></a>
					<a href="client.php"><i class="icon-users"></i><span>Clientes</span></a>
					<a href="mktik.php"><i class="icon-network"></i><span>Mktik</span></a>
					<a href="egr.php"><i class="icon-money"></i><span>Egresos</span></a>
					<a href="login/logout.php"><i class="icon-logout"></i><span>Salir</span></a>
				</nav>
			</div>

			<main class="main col">
				<div class="row">
					<div class="columna col-lg-9">
						<div class="nuevo_contenido pb-2 mb-2 caja border-primary">
							<h5 class="my-3 pb-2 caja border-primary">Registrar pago</h5>
							<!-- inicio de contenido de pagina -->
							<form style="display: hidden" action="printable.php" method="POST" id="form">
								<input type="hidden" id="idt" name="idt" value="0" />
								<input type="hidden" id="rpp" name="rpp" value="register-pay" />
							</form>
							<table id="clientList" class="display compact stripe cell-border" cellspacing="0" width="100%">
								<thead class="bg-primary">
									<tr>
										<td>Nombre Titular</td>
										<td>Dirección</td>
										<td>Antiguedad en meses</td>
										<td>Saldo</td>
										<td>Fecha de Ingreso</td>
										<td>Corte</td>
										<td>Cedula Titular</td>
										<td>Telefono</td>
										<td>Pay</td>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<td>Nombre Titular</td>
										<td>Dirección</td>
										<td>Antiguedad en meses</td>
										<td>Saldo</td>
										<td>Fecha de Ingreso</td>
										<td>Corte</td>
										<td>Cedula Titular</td>
										<td>Telefono</td>
										<td>Pay</td>
									</tr>
								</tfoot>
								<tbody>

									<?php
									$sql = "SELECT * FROM `afiliados` WHERE `afiliados`.`activo`=1 AND `afiliados`.`eliminar`!=1 ORDER BY `afiliados`.`id`  ASC ";
									if ($result = $mysqli->query($sql)) {
										while ($row = $result->fetch_assoc()) {
											$idCliente = $row["id"];
											$cedula = $row["cedula"];
											$telefono = $row["telefono"];
											$registration_date = $row["registration-date"];
											$corte = $row["corte"];
											$standby = $row["standby"];
											$ip=$row["ip"];
											$pingDate=$row["pingDate"];
											$pingCurrentStatus=($pingDate==$today) ? "Ping ok!" : "<small class=\"bg-danger text-white p-1\">Ping Error</small>";   
											$style_cell = "";
											$style2_cell = "";
											$ts1 = strtotime($registration_date);
											$ts2 = strtotime($today);

											$year1 = date('Y', $ts1);
											$year2 = date('Y', $ts2);

											$month1 = date('m', $ts1);
											$month2 = date('m', $ts2);

											$registration_day = date('d', $ts1);
											if ($registration_date != "0000-00-00") {

												$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
											} else
												$diff = "999";

											$sqlt = "SELECT * FROM `factura`  WHERE `factura`.`id-afiliado`='$idCliente' AND `factura`.`cerrado`='0'  ORDER BY `factura`.`id-factura` DESC";
											$vtotal = 0;
											$cont = 0;
											if ($resultt = $mysqli->query($sqlt)) {
												while ($rowft = $resultt->fetch_assoc()) {
													$cont += 1;
													$idFactura = $rowft["id-factura"];
													$periodo = $rowft["periodo"];
													$saldo = $rowft["saldo"];
													$vtotal += $saldo;
												}
												$resultt->free();
											}
											if ($vtotal > 0 && $diff == 0) {
												$style_cell = "class=\"text-warning bg-dark\" ";
											}
											if ($vtotal > 0 && $diff == 1 && $corte == 1 && $registration_day > 15) {
												$style_cell = "class=\"text-info bg-dark\" ";
											}

											if ($row["eliminar"] == 1) {
												$statusText = "Inactivo";
												$style = "border-dark text-secundary ";
											} else {
												$style = "border-primary text-success ";
												$statusText = "<p><small class=\"px-1 border $style rounded \">Activo</small></p>";
											}
											if ($row["suspender"] == 1) {
												$today = date("Y-m-d");
												$date1 = new DateTime($today);
												$date2 = new DateTime($row["suspenderFecha"]);
												$days  = $date2->diff($date1)->format('%a'); 
												$style = "border-info text-danger ";
												$statusText = "<p><small class=\"px-1 border $style rounded \">Cortado-[ $days días ]</small></p>";
											}
											$textCedula = $cedula;
											if ($cedula == 0) {
												$textCedula = "<input class=\"form-control form-control-sm cedula" . $row["id"] . " px-2\" type=\"text\" value=\"\" >";
											} else {
												$textCedula = "<input class=\"form-control form-control-sm cedula" . $row["id"] . " px-2\" type=\"text\" value=\"$cedula\" id=\"-1\" 	 >";
											}

											$textTelefono = $telefono;
											if ($telefono == "") {
												$textTelefono = "<input class=\"form-control form-control-sm telefono" . $row["id"] . " px-2 \" type=\"text\" value=\"\" >";
											} else {
												$textTelefono = "<input class=\"form-control form-control-sm telefono" . $row["id"] . " px-2 \" type=\"text\" value=\"$telefono\" id=\"-1\"  >";
											}

											$telefono = $row["telefono"]; 
											echo "<tr class=\"text-center  \">";
											echo "<td> {$row["cliente"]}  {$row["apellido"]} $statusText</td>";
											echo "<td><small>{$row["direccion"]} {$row["ciudad"]} -{$row['id']}</small></td>"; 
											echo "<td>$diff</td>";
											echo "<td><small 	$style_cell >$$vtotal</small><div><a href=\"#\" class=\"text-primary icon-client \" data-toggle=\"modal\" 	data-target=\"#payModal\" data-id=\"" . $row["id"] . "\"><i class=\"icon-money\"></i></a></div></td>";
											echo "<td><small>$registration_date</small><div class=\"border border-info rounded p-1 bg-white\"><p class=\"mb-0\"><small>ip:'$ip'</small></p><p class=\"mb-0\"><small>$pingCurrentStatus</small></p></div></td>";    
											echo "<td class=\" align-middle \"><small>C-" . $row["corte"] . "*$standby</small></td>";
											echo "<td class=\" align-middle \">$textCedula</td>"; 
											echo "<td class=\" align-middle \">$textTelefono</td>";          
											echo "<td class=\" align-middle \"><a href=\"#\" class=\"text-primary icon-client \" data-toggle=\"modal\" 	data-target=\"#payModal\" data-id=\"" . $row["id"] . "\"><i class=\"icon-money\"></i></a></td>";
											echo "</tr>";
										}
										$result->free(); 
									}
									?>
									<!-- Modal -->
									<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Detalles de Pago</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>

												<div class="modal-body">
													<div class="fetched-data"></div>

												</div>
												<div class="modal-footer">
													<button type="button" id="cancelbutton" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
													<button type="button" id="paybutton" class="btn btn-primary">Pagar</button>
												</div>
											</div>
										</div>
									</div>

								</tbody>
							</table>
							<!-- fin de contenido de pagina -->
						</div>

					</div>

					<div class="columna col-lg-3">
						<div class="widget estadisticas">
							<h3 class="titulo">Estadisticas</h3>
							<div class="contenedor d-flex flex-wrap">
								<div class="caja">
									<h3>1,236</h3>
									<p>Pagos</p>
								</div>
								<div class="caja">
									<h3>231</h3>
									<p>Clientes</p>
								</div>
								<div class="caja">
									<h3>$1.160.548</h3>
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
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

	<script type="text/javascript" src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
	<script src="bower_components/Popper/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="bower_components/alertify/js/alertify.min.js"></script>
	<script src="bower_components/AutoFormatCurrency/simple.money.format.js"></script>
	<script src="js/dataTables.checkboxes.min.js"></script>
	<script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>

	<script>
		$('#clientList').DataTable({
			"iDisplayLength": 15,
			"order": [
				[4, "desc"]
			],
			"responsive": true,
			"paging": true,
			"searching": true,
			"info": true,
			fixedHeader: {
				header: true,
				footer: true
			}
		});
		$('#payModal').on('show.bs.modal', function(e) {

			var rowid = $(e.relatedTarget).data('id');
			
			var cedula = 0;
			if ($.isNumeric($(".cedula" + rowid).val())) {
				if (($(".cedula" + rowid).val() + "").match(/^\d+$/)) {
					
					cedula = $(".cedula" + rowid).val();
				} else {
					alertify.error('Cedula invalido, tiene puntos decimales!!');
				}
			} else {
				if ($(".cedula" + rowid).val() == "")
					alertify.warning('Cedula  no sera actualizada!!');
				else
					alertify.error('Cedula valor invalido, no sera actualizada!!');
			}
			var telefono = 0;
			if ($.isNumeric($(".telefono" + rowid).val())) {
				if (($(".telefono" + rowid).val() + "").match(/^\d+$/)) {
					
					telefono = $(".telefono" + rowid).val();
				} else {
					alertify.error('telefono invalido, tiene puntos decimales!!');
				}
			} else {
				if ($(".telefono" + rowid).val() == "")
					alertify.warning('Telefono  no sera actualizado!!');
				else
					alertify.error('Telefono valor invalido, no sera actualizada!!');
			}
			$.ajax({ 
				type: 'post',
				url: 'fetch_payModal.php',
				data: {
					rowid: rowid,
					cedula: cedula,
					telefono: telefono
				},
				success: function(data) {
					$('.fetched-data').html(data);
					$("#tr-valor-abonar").hide();
					$("#tr-valor-descontar").hide();
					$("#nuevo-saldo").hide();
					$('#payment').hide();
					//start block pasted
					$('#cancelbutton').click(function() {
						$('#payModal').modal('hide');
						alertify.error('Operacion Cancelada').dismissOthers();
					});
					$("#checkbox-abonar").click(function() {
						$("#valor-abonar").val("");
						$('.money-abonar').html("");						
						if ($('#checkbox-abonar').is(":checked")) {
							$("#tr-valor-abonar").show();
							$("#nuevo-saldo").show();
							$( "#paybutton" ).prop( "disabled", true );
							if ($('#checkbox-descontar').is(":checked")) {
								if($("#valor-abonar").val().replace(/[^0-9\.]/g, '')!="" && $("#valor-descontar").val().replace(/[^0-9\.]/g, '')!=""){
									$( "#paybutton" ).prop( "disabled", false );
								}
								else{
									$( "#paybutton" ).prop( "disabled", true );
								}
							}
							else{
								$("#valor-nuevo-saldo").html($("#valor-pago").html().replace(/[^0-9\.]/g, '')).simpleMoneyFormat();
							}
							
						} else {
							$("#tr-valor-abonar").hide();
							$("#nuevo-saldo").hide();
							$( "#paybutton" ).prop( "disabled", false );
							if ($('#checkbox-descontar').is(":checked")){
								$("#nuevo-saldo").show();
								if($("#valor-descontar").val()=="" || !this.value ){
									$( "#paybutton" ).prop( "disabled", true );
								}
								else{
									let vp = $("#valor-pago").text();
									let intvp = vp.replace(/[^0-9]/gi, '');
									let va=$("#valor-descontar").val().replace(/[^0-9\.]/g, '');
									let vt=parseInt(intvp)-parseInt(va);
									$("#valor-nuevo-saldo").html(vt).simpleMoneyFormat();

								}
							}
						}
					});
					$("#checkbox-descontar").click(function() {
						$("#valor-descontar").val("");
						$('.money-descontar').html("");
						if ($('#checkbox-descontar').is(":checked")) {
							$("#tr-valor-descontar").show();
							$("#nuevo-saldo").show();
							$( "#paybutton" ).prop( "disabled", true );
							if ($('#checkbox-abonar').is(":checked")) {
								if($("#valor-abonar").val().replace(/[^0-9\.]/g, '')!="" && $("#valor-descontar").val().replace(/[^0-9\.]/g, '')!=""){
									$( "#paybutton" ).prop( "disabled", false );
								}
								else{
									$( "#paybutton" ).prop( "disabled", true );
								}
							}
							else{
								$("#valor-nuevo-saldo").html($("#valor-pago").html().replace(/[^0-9\.]/g, '')).simpleMoneyFormat();
							}
							
						} else {
							$("#tr-valor-descontar").hide();
							$("#nuevo-saldo").hide();
							$( "#paybutton" ).prop( "disabled", false );
							if ($('#checkbox-abonar').is(":checked")){
								$("#nuevo-saldo").show();
								if($("#valor-abonar").val()=="" || !this.value){
									$( "#paybutton" ).prop( "disabled", true );
								}
								else{
									let vp = $("#valor-pago").text();
									let intvp = vp.replace(/[^0-9]/gi, '');
									let va=$("#valor-abonar").val().replace(/[^0-9\.]/g, '');
									let vt=parseInt(intvp)-parseInt(va);
									$("#valor-nuevo-saldo").html(vt).simpleMoneyFormat();

								}
							} 
						}
					});
					$("#valor-abonar").keyup(function() {
						let value = this.value.replace(/[^0-9\.]/g, '');
						let abonar=$("#valor-abonar").val();
						$('.money-abonar').text($("#valor-abonar").val());
						$('.money-abonar').simpleMoneyFormat();
						var vp = $("#valor-pago").text();
						var intvp = vp.replace(/[^0-9]/gi, '');
						let descontar=0;
						let voidFlag=0;
						if ($('#checkbox-descontar').is(":checked")) {
							if( $("#valor-descontar").val().replace(/[^0-9]/gi,'')!="") {
								descontar=parseInt($("#valor-descontar").val());
							}
							else {
								descontar=0;
								$( "#paybutton" ).prop( "disabled", true );	
								voidFlag=1;
							}
						}
						if (  ( (parseInt(value)+parseInt(descontar)) > parseInt(intvp) )  || parseInt(abonar)==0   || isNaN(this.value) || !this.value  ) {
							$("#valor-abonar").val('');
							$("#valor-abonar").addClass('border border-danger');
							$( "#paybutton" ).prop( "disabled", true );
							$('.money-abonar').text('');
							$("#valor-nuevo-saldo").html(intvp).simpleMoneyFormat();
						}
						else{
							$("#valor-abonar").removeClass('border border-danger');
							if(voidFlag!=1)
								$( "#paybutton" ).prop( "disabled", false );
							let ns=intvp-value-descontar;
							$("#valor-nuevo-saldo").html(ns).simpleMoneyFormat();
												
						}
					});
					$("#valor-descontar").keyup(function() {
						let value = this.value.replace(/[^0-9\.]/g, '');
						let descontar=$("#valor-descontar").val();
						$('.money-descontar').text($("#valor-descontar").val());
						$('.money-descontar').simpleMoneyFormat();
						var vp = $("#valor-pago").text();
						var intvp = vp.replace(/[^0-9]/gi, '');
						let abonar=0;
						let voidFlag=0;
						if ($('#checkbox-abonar').is(":checked")) {
							if( $("#valor-abonar").val().replace(/[^0-9]/gi,'')!="") {
								abonar=parseInt($("#valor-abonar").val());
								
							}
							else {
								abonar=0;
								$( "#paybutton" ).prop( "disabled", true );
								
								voidFlag=1;
							}
						}
						
						if (  ( (parseInt(value)+parseInt(abonar))  > parseInt(intvp)) || parseInt(descontar)==0 || isNaN(this.value) || !this.value ) { 
							
							$("#valor-descontar").val('');
							$("#valor-descontar").addClass('border border-danger');
							$( "#paybutton" ).prop( "disabled", true );
							$('.money-descontar').text('');
							$("#valor-nuevo-saldo").html(intvp).simpleMoneyFormat();
						}
						else{
							
							$("#valor-descontar").removeClass('border border-danger');
							if(voidFlag!=1)
								$( "#paybutton" ).prop( "disabled", false );
							let ns=intvp-value-abonar;
							$("#valor-nuevo-saldo").html(ns).simpleMoneyFormat();
							
						}
					});
					//end block pasted			
					$('#btn-paym').click(function() {
						$.ajax({
							type: 'post',
							url: 'fetch_payModal_payment.php',
							data: {
								rowid: rowid,
								cedula: cedula,
								telefono: telefono
							},
							success: function(data) {
								$('#payment').html(data);
								$("#payment-div").toggleClass("border border-info rounded");
								if (!$.fn.DataTable.isDataTable('#table_past_payment')) {
									var table_last_payment = $('#table_past_payment').DataTable({
										"responsive": true,
										"paging": true,
										"searching": false,
										"info": false,
										"order": [
											[0, "desc"]
										],
										"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
											if (aData[4] != "0") {
												$('td', nRow).css('color', '#d9534f');
											}
											if ((aData[3] == "Ajuste") && (aData[4] == "0")) {
												$('td', nRow).css('color', '#428bca');
											}

										} 

									});

								}
								$('#payment').toggle();
								$("#icon-down-open").toggle();
							}
						});
					});

				}
			});
		});

		$(document).ajaxComplete(function() {

			$('.icon-client').click(function() {
				$("#paybutton").show();
			});

			var vp = $("#valor-pago").text();
			console.log("valor-pago:"+vp);
			var intvp = vp.replace(/[^0-9]/gi, '');
			if (intvp == 0) {
				$("#paybutton").hide();
				$("#tr-chkb-abonar").hide();
			}
			$('#paybutton').click(function() {
				let strPrompt1="Ingreso de Efectivo";
				let strPrompt2="Efectivo:";
				if(!$('#checkbox-abonar').is(":checked") && $('#checkbox-descontar').is(":checked")) {
					strPrompt1="Presione enter para continuar";
					strPrompt2="";
				}
				alertify.prompt(strPrompt1, strPrompt2, "",
					function(evt, value) {
						var vplanRow=parseInt($("#valor-plan").html().replace(/[^0-9]/gi,''));
						console.log("valor del plan:"+vplanRow);
						var pass = 0;
						var cambio = 0;
						if($('#checkbox-descontar').is(":checked")){
							pass=1;
						} 
						if ($('#checkbox-abonar').is(":checked")) {
							let tmp1=0;							
							if($("#valor-abonar").val())
								tmp1=parseInt($("#valor-abonar").val());							
								var vaa = tmp1;
							if (value >= parseInt(vaa)) {
								pass = 1;
								cambio = value - parseInt(vaa);
							} else
								pass = 0;
							
						}
						if(!$('#checkbox-abonar').is(":checked") && !$('#checkbox-descontar').is(":checked")) {
							var vap = $("#valor-pago").text();
							var intvap = vap.replace(/[^0-9]/gi, '');
							if (value >= parseInt(intvap)) {
								pass = 1;
								cambio = value - parseInt(intvap);
							} else
								pass = 0;
							
						}						
						if (pass == 1) {
							let idcRow=0;
							let vapRow=0;
							let vaaRow=0;
							let vreRow=0;
							let cambioRow=0;
							let vadRow=0;
							let vaa=0;
							let vad=0;
							let rec=0;
							if ($('#checkbox-reconectar').is(":checked")) {	
								rec=1
							}
							if ($('#checkbox-abonar').is(":checked") || $('#checkbox-descontar').is(":checked") ) {								
								var idc = $("#id-client").text();
								var vap = -1;
								if($('#checkbox-abonar').is(":checked")){
								vaa = $("#valor-abonar").val();
								}
								if($('#checkbox-descontar').is(":checked")){
								vad = $("#valor-descontar").val();
								}
								idcRow=idc;
								vapRow=vap;
								vaaRow=vaa;
								vreRow=value;
								cambioRow=cambio;
								vadRow=vad;
							} else {								
								var idc = $("#id-client").text();
								var vap = $("#valor-pago").text();
								var intvap = vap.replace(/[^0-9]/gi, '');
								idcRow=idc;
								vapRow=intvap;
								vaaRow=0;
								vreRow=value;
								cambioRow=cambio;
								vadRow=0;
							}
							console.log("valores q voy a enviar \n idc: "+idcRow+",vap:"+vapRow+",vaa:"+vaaRow+",vre: "+vreRow+",cam: "+cambioRow+",vad: "+vadRow+",vpl: "+vplanRow);
							$.ajax({
								type: 'post',
								url: 'fetch_payFact.php',
								data: {
									idc: idcRow,
									vap: vapRow,
									vaa: vaaRow,
									vre: vreRow,
									cam: cambioRow,
									vad: vadRow,
									vpl: vplanRow,
									rec: rec
								},
								success: function(data) {
									var arr = data.split('/');
									var msj = arr[0];
									var cod = arr[1];
									console.log(data);
									//console.log("data:"+data+"-msj:"+msj+"-code:"+cod);
									//alertify.success(msj);
									$("#idt").val(cod);
									$("#form").submit();
								}
							});
						} else
							alertify.error('Efectivo invalido!');
					},
					function() {
						alertify.error('Error,problema con valor de efectivo!');
					});;
				$('#payModal').modal('hide');
			});



		})
	</script>
</body>

</html>