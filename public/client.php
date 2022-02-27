	<?php
	session_start();
	if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
		header('Location: ../login/index.php');
		exit;
	} else {
		$user = $_SESSION['username'];
		$idAreaDefault=$_SESSION["idAreaDefault"];
		 
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
		<title>IspExperts-Administrador ISP</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css">
		<link rel="stylesheet" href="../bower_components/alertify/css/alertify.min.css" />
		<link rel="stylesheet" href="../bower_components/alertify/css/themes/default.min.css" />
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css">
		<link rel="stylesheet" href="../css/fontello.css">
		<link rel="stylesheet" href="../css/estilos.css">
		<link rel="stylesheet" href="../css/dataTables.checkboxes.css">
		<style>
			.caja {
				border-bottom: 1px solid #000;
			}
			#table_client_to_sms{
				font-size:10px;
			}
			.btn {
				background-color: DodgerBlue; /* Blue background */
				border: none; /* Remove borders */
				color: white; /* White text */
				padding: 5px 5px; /* Some padding */
				font-size: 10px; /* Set a font size */
				cursor: pointer; /* Mouse pointer on hover */
			}

			/* Darker background on mouse-over */
			.btn:hover {
			background-color: RoyalBlue;
			}
		</style>
	</head>

	<body>
		<?php
		include("../login/db.php");
		include("../pingTime.php");
		$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: " . $mysqli->connect_error;
		}
		mysqli_set_charset($mysqli, "utf8");
		date_default_timezone_set('America/Bogota');
		$today = date("Y-m-d");
		$hoy = date("d-m-Y");
		$hourMin = date('H:i');
		?>
		<div class="container-fluid px-0">
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top   ">
				<div class="container img-logo ">
					<!-- <img src="img/wisp.png"> -->
					<!-- Nos sirve para agregar un logotipo al menu -->
					<a href="main.php" class="navbar-brand ">Wisdev</a> 

					<!-- Nos permite usar el componente collapse para dispositivos moviles . ...-->
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
									<a class="nav-link disabled text-white "><i class="icon-user"></i><?php echo "Hola " . $_SESSION['username']; ?></a>
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
						<a href="fact.php"><i class="icon-doc-text"></i><span>Facturas</span></a>
						<a href="client.php"><i class="icon-users"></i><span>Clientes</span></a>
						<a href="mktik.php"><i class="icon-network"></i><span>Mktik</span></a>
						<a href="egr.php"><i class="icon-money"></i><span>Egresos</span></a>
						<a href="../login/logout.php"><i class="icon-logout"></i><span>Salir</span></a>
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
												<a class="nav-link " id="active_client_currently" href="#">Clientes </a>
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
										<div class="row">
											<div class="col-sm-12">
												<div class="d-flex justify-content-center">
													<div class="card">
														<h5 class="card-header info-color white-text text-center py-4">
															<strong></strong>
														</h5>
														<!--Card content-->
														<div class="card-body px-lg-5 pt-0">
															<div id="new_client_registration_content" class=" px-3 py-3 text-left ">
																<div class="card" id="personal-info">
																	<h5 class="card-header text-white bg-primary"><i class="icon-user"></i>Información personal del titular</h5>
																	<div class="card-body bg-light">
																		<div class="form-row">
																			<div class="form-group col-md-4 ">
																				<label>Nombres</label>
																				<input class="form-control" type="text" value="" id="name">
																			</div>
																			<div class="form-group col-md-4 ">
																				<label>Apellidos.</label>
																				<input class="form-control" type="text" value="" id="last-name">
																			</div>
																			<div class="form-group col-md-4 ">
																				<label>Cedula.</label>
																				<input class="form-control" type="text" value="" id="cedula">
																			</div>
																			<div class="form-group col-md-4 ">
																				<label>Dirección.</label>
																				<input class="form-control" type="text" value="" id="address">
																			</div>
																			<div class="form-group col-md-4 ">
																				<label>Ciudad.</label>
																				<select  class="custom-select" id="ciudad">
																					<?php
																					$sql = "SELECT * FROM `areas` WHERE 1 ";
																					if ($result = $mysqli->query($sql)) {
																						
																						while ($row = $result->fetch_assoc()) {
																							$id=$row["id"];
																							$t="";
																							if($id==$idAreaDefault)$t="SELECTED";
																							$nombre=$row["nombre"];
																							$ciudad=$row["ciudad"];
																							echo "<option $t value=\"$id-$ciudad\">$nombre $ciudad</option>";
																						}
																						$result->free();
																					}
																					?>
																				</select>
																			</div>
																			<div class="form-group col-md-4 ">
																				<label>Departamento.</label>
																				<input class="form-control" type="text" value="" id="departamento">
																			</div>
																			<div class="form-group col-md-4 ">
																				<label>Telefono.</label>
																				<input class="form-control" type="text" value="" id="phone">
																			</div>
																			<div class="form-group col-md-4 ">
																				<label>Email.</label>
																				<input class="form-control" type="email" value="" id="email">
																			</div>
																			<div class="form-group col-md-4 ">
																				<label>Confirmar email</label>
																				<input class="form-control" type="email" value="" id="email-confirmar">
																			</div>
																			<div class="form-group col-md-4 ">
																				<label>Ip Address</label>
																				<input class="form-control" type="text" value="0.0.0.0" id="ipAddress">
																			</div>
																			<div class="form-group col-md-4 ">
																				<label>Cotizacion?</label>
																				<select  id="quote">
																					<option value="0">No</option>
																					<option value="1">Si</option>
																				</select>
																			</div>
																			<div class="form-group col-md-2 ">
																				<label></label>
																				<button type="button" class="btn btn-success" id="continue-btn-registration">Continuar</button>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="card" id="business-info">
																	<!-- Modal -->
																	<div class="modal fade" id="ClientModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
																		<div class="modal-dialog modal-lg" role="document">
																			<div class="modal-content">
																				<div class="modal-header">
																					<h5 class="modal-title" id="ModalLabel"></h5>
																					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																						<span aria-hidden="true">&times;</span>
																					</button>
																				</div>
																				<div class="modal-body">
																					<h5 class="card-header text-white bg-primary"><i class="icon-network"></i>Información de Servicio a contratar</h5>
																					<div class="card-body bg-light">
																						<div class="form-row">
																							<div class="form-group col-md-2    ">
																								<small>Hoy </small>
																								<input disabled class="form-control" type="text" value="<?php echo $hoy;  ?>" id="date-today">

																							</div>
																							<div class="form-group col-md-6  border border-info rounded " id="div-sm" data-toggle="tooltip" data-placement="top" title="Ingresa fecha de pago mensual a partir del día en que los técnicos instalen el servicio">
																								<small id="cada-txt">Fecha estimada instalación del servicio: </small>
																								<input class="form-control" type="text" id="date-picker">
																								<div class="form-group form-check">
																									<input type="checkbox" class="form-check-input" id="left-day">
																									<small class="text-info" id="left-day-text"></small>
																								</div>
																							</div>
																							<div class="form-group col-md-2   ">
																								<small>Factura </small>
																								<select DISABLED class="custom-select " id="corte">
																									<?php
																									$day = date("j");
																									if (($day > 0 && $day <= 10) || ($day >= 20 && $day <= 31)) {
																										echo "<option selected value=\"1\" >Corte 1</option>";
																										echo "<option value=\"15\">Corte 15</option>";
																									} else {
																										echo "<option  value=\"1\" >Corte 1</option>";
																										echo "<option selected value=\"15\">Corte 15</option>";
																									}

																									?>
																								</select>

																							</div>
																							<div class="form-group col-md-2   ">
																								<small>A partir de:</small>
																								<select DISABLED class="custom-select " id="mes">
																									<?php
																									$mes = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
																									$month = date("n");
																									if ($month == 12)
																										$nexxtMonth = 1;
																									else $nexxtMonth = $month + 1;
																									$day = date("j");
																									if ($day > 0 && $day <= 18) {
																										for ($x = 0; $x <= 12; $x++) {
																											if ($x == $month) {
																												echo "<option SELECTED value=\"$x\" >{$mes[$x]}</option>";
																											}
																											if ($x == $nexxtMonth) {
																												echo "<option value=\"$x\" >{$mes[$x]}</option>";
																											}
																										}
																									} else {
																										for ($x = 0; $x <= 12; $x++) {
																											if ($x == $month + 1) {
																												echo "<option SELECTED value=\"$x\" >{$mes[$x]}</option>";
																											}
																											if ($x == $month) {
																												echo "<option value=\"$x\" >{$mes[$x]}</option>";
																											}
																										}
																									}
																									?>

																								</select>
																								
																								<input type="text" id="dayOfMonthSelected">	
																								<input type="text" id="monthSelected">	
																							</div>
																							<div class="form-group col-md-3 ">
																								<small>Tipo de Plan.</small>
																								<select class="custom-select " id="plan">
																									<option value="Residencial" selected>Residencial.</option>
																									<option value="Comercial">Comercial.</option>
																								</select>
																							</div>
																							<div class="form-group col-md-3 ">
																								<small>Velocidad Plan.</small>
																								<select class="custom-select " id="velocidad-plan">
																									<option value="1">1 Mbps.</option>
																									<option value="2">2 Mbps.</option>
																									<option value="3" selected>3 Mbps.</option>
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

																							<div class="form-group col-md-3 ">
																								<small>Valor de Plan mensual.</small>
																								<input class="form-control" type="number" value="50000" id="valor-plan">
																							</div>
																							<!-- <div class="form-group col-md-4 "> -->
																							<!-- <small>Dirección ip.</small>				 -->
																							<input class="form-control" type="hidden" value="" id="ip-address">
																							<!-- </div> -->
																							<div class="form-group col-md-3 ">
																								<small>Generar Factura</small>
																								<select disabled class="custom-select " id="generar-factura">
																									<option value="1" selected>Si.</option>
																									<option value="0">No.</option>
																								</select>
																							</div>
																							<div class="form-group col-md-3 border border-info rounded">
																								<small>Valor de Afiliación</small>
																								<input class="form-control" type="number" value="0" id="valorAfiliacion">
																								<div class="form-group form-check mb-0" id="standbyDiv">
																									<input type="checkbox" class="form-check-input" value="0" id="mergeItems">
																									<small class="text-info">Valor afililiación incluye valor factura de servicio?.</small>
																								</div>
																								
																							</div>  
																							<div class="form-group col-md-3 ">
																								<small>En este momento que está afiliando , el cliente ya le fue instalado el servicio?</small>
																								<select  class="custom-select " id="serviceIsAlreadyInstalled">
																									<option value="1" >Si.</option>
																									<option value="0" SELECTED >No.</option>
																								</select>
																							</div>   
																							<div class="form-group col-md-3 border border-info rounded ml-1" id="valorProrrateoDiv2" >
																								<small>Total Prorrateo-Pagado </small>
																								<input class="form-control" type="number" value="" id="valorProrrateo">
																							</div>

																							<div class="form-group col-md-3 border border-info rounded ml-1" id="valorAdicionalServicioDiv" >
																								<small>Valor adicional de Servicio-No pagado. </small>
																								<input class="form-control" type="number" value="" id="valorAdicionalServicio">
																								<input class="form-control" type="number" value="" id="valorAdicionalServicioDescripcion">
																							</div>
																							<div class="form-group col-md-3 border border-info rounded ml-1" id="valorApagarDiv" >
																								<small>Total de la factura </small>
																								<input class="form-control" type="number" value="" id="valorApagar">
																							</div>
																							<div class="form-group col-md-3 border border-info rounded ml-1" id="standbyDiv2" >
																								<small>Standby Counter </small>
																								<input class="form-control" type="number" value="" id="standby">
																							</div>
																							<div class="form-group col-md-3 border border-info rounded ml-1"  id="standarServiceFlagDiv">
																								<small>Standar service flag   </small>
																								<input class="form-control" type="number" value="" id="standarServiceFlag">
																							</div>
																							<div class="form-group col-md-3 border border-info rounded ml-1" id="AfiliacionItemValueDiv" >
																								<small>Afiliacion Item Value   </small>
																								<input class="form-control" type="number" value="" id="AfiliacionItemValue">
																							</div>
																						</div>
																						<div id="date-info" class="alert alert-secondary" role="alert">
																							<div class="table-responsive">
																								<table class="table table-striped table-hover table-bordered rounded" id="table-afi">
																									<thead class="bg-primary text-light">
																										<tr>
																											<th></th>
																											<th>Item</th> 
																											<th>Descripción</th>
																											<th>Valor</th>
																											<th>Importe(Iva)</th>
																											<th>Subtotal</th>
																										</tr>
																									</thead>
																									<tbody>
																										<tr>
																											<td></td>
																											<td>Afiliación</td>
																											<td id="td-afiliacion">Instalación servicio de Internet B Ancha ,equipos en alquiler.Paga los 1 de cada mes.Suspensión 6 de cada mes.</td>
																											<td id="td-valorAfiliacion">$0</td>
																											<td id="td-importeAfiliacion">0%</td>
																											<td id="td-subtotalAfiliacion">$0</td>
																										</tr>
																										<tr id="tr-servicio">
																											<td></td>
																											<td>Servicio</td>
																											<td id="td-servicio">Plan mensual 3Mbps-Down 1Mbps-Up megas de Internet -Primer mes.</td>
																											<td id="td-valorServicio">$0</td>
																											<td id="td-importeServicio">0%</td>
																											<td id="td-subtotalServicio">$0</td>
																										</tr>
																										<tr id="tr-prorrateo" class="table-danger">
																											<td class="px-1"></td>
																											<td>Días de Servicio</td>
																											<td id="td-servicio-prorrateo">12 Dias desde 18 Septiembre a 30 de Septiembre</td>
																											<td id="td-valorServicio-prorrateo">$12150</td>
																											<td id="td-importeServicio-prorrateo">19% $2850</td>
																											<td id="td-subtotalServicio-prorrateo">$15000</td>
																										</tr>
																										<tr>
																											<td colspan="5">
																												<p class="text-right">Total:</p>
																												</th>

																											<td class="bg-primary text-white rounded " id="td-total"></td>
																										</tr>

																									</tbody>
																								</table>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div class="modal-footer">
																					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
																					<button type="button" class="btn btn-primary" id="save">Guardar</button>
																				</div>
																			</div>
																		</div>
																	</div>

																</div>

															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div id="active_client_currently_content" class=" px-3 py-3 text-left    border border-danger">

											<div class="  border border-success">
												<h4 class="card-title mt-4">Lista de Clientes <strong>Activos</strong> </h4>
												<table id="table_active_client" class="display datatable_table_active_client " cellspacing="0" width="100%">
													<thead class="bg-primary">
														<tr>
															<td>cod</td>
															<td>Cliente</td>
															<td>Dirección</td>
															<td>Corte</td>
															<td>Pago</td>
															<td>Velocidad</td>
															<td>IP</td>
															<td>Suspender</td>
															<td>Eliminar</td>
														</tr>
													</thead>
													<tfoot class="py-3 text-right">
														<tr>

															<td colspan="3"></td>
															<td colspan="3"></td>

														</tr>
													</tfoot>
													<tbody>
														<?php
														$contPago = 0;
														$sql = "SELECT * FROM `afiliados` WHERE activo=1 AND eliminar=0 ORDER BY `id` DESC ";
														if ($result = $mysqli->query($sql)) {
															while ($row = $result->fetch_assoc()) {
																$cod = $row["id"];
																$cliente = $row["cliente"] . " " . $row["apellido"];
																$direccion = $row["direccion"];
																$corte = $row["corte"];
																$pago = $row["pago"];
																$contPago += $pago;
																$velocidad = $row["velocidad-plan"] . " Megas";
																$activo = $row["activo"];
																$ip = $row["ip"];
																echo "<tr class=\"text-center  \">";
																echo "<td>" . $cod . "</td>";
																echo "<td>" . $cliente . "</td>";
																echo "<td>" . $direccion . "</td>";
																echo "<td>" . $corte . "</td>";
																echo "<td>" . $pago . "</td>";
																echo "<td>" . $velocidad . "</td>";
																echo "<td>" . $ip . "</td>";
																echo "<td><button type=\"button\" disabled class=\"btn btn-warning suspender\" id=\"suspender" . $cod . "\" ><i class=\"icon-scissors text-dark\"></i></button></td>";
																echo "<td><button type=\"button\" class=\"btn btn-primary eliminar\" id=\"activo" . $cod . "\" ><i class=\"icon-scissors text-dark\"></i></button></td>";
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
													<thead class="bg-dark text-light">
														<tr>
															<td>cod</td>
															<td>Cliente</td>
															<td>Dirección</td>
															<td>Corte</td>
															<td>Pago</td>
															<td>Velocidad</td>
															<td>IP</td>

															<td>Reactivar</td>

														</tr>
													</thead>
													<tfoot class="py-3 text-right">
														<tr>

															<td colspan="3"></td>
															<td colspan="3"></td>

														</tr>
													</tfoot>
													<tbody>
														<?php
														$sql = "SELECT * FROM `afiliados` WHERE eliminar=1 ORDER BY `id` DESC ";
														if ($result = $mysqli->query($sql)) {
															while ($row = $result->fetch_assoc()) {
																$cod = $row["id"];
																$cliente = $row["cliente"] . " " . $row["apellido"];
																$direccion = $row["direccion"];
																$corte = $row["corte"];
																$pago = $row["pago"];
																$velocidad = $row["velocidad-plan"] . " Megas";
																$activo = $row["activo"];
																$ip = $row["ip"];
																echo "<tr class=\"text-center  \">";
																echo "<td>" . $cod . "</td>";
																echo "<td>" . $cliente . "</td>";
																echo "<td>" . $direccion . "</td>";
																echo "<td>" . $corte . "</td>";
																echo "<td>" . $pago . "</td>";
																echo "<td>" . $velocidad . "</td>";
																echo "<td>" . $ip . "</td>";

																echo "<td><button type=\"button\" class=\"btn btn-dark reactivar\" id=\"inactivo" . $cod . "\" ><i class=\"icon-exchange text-success\"></i></button></td>";
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
												<thead class="bg-primary">
													<tr>
														<td style="width:20px;">Id</td>
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
												<tbody>
													<?php
													$sql = "SELECT id,cliente,apellido,telefono,direccion,corte,any_value(valorf),ip,SUM( saldo )as mysum,`velocidad-plan` FROM `afiliados` INNER JOIN factura ON `afiliados`.`id`=`factura`.`id-afiliado` WHERE factura.periodo !='' AND factura.cerrado=0 AND `afiliados`.`activo`=1 AND eliminar !=1  GROUP BY`afiliados`.`id` ORDER BY mysum DESC ";
													if ($result = $mysqli->query($sql)) {
														while ($row = $result->fetch_assoc()) {
															$idCliente = $row["id"];
															$cliente = $row["cliente"] . " " . $row["apellido"];
															$telefono = $row["telefono"];
															$direccion = $row["direccion"];
															$corte = $row["corte"];
															$pago = $row["valorf"];
															$saldo = $row["mysum"];
															$velocidad = $row["velocidad-plan"] . " Megas";
															$ip = $row["ip"];
															$textTelefono = $telefono;
															if ($telefono == "") {
																$textTelefono = "<input placeholder=\" Telefono\" class=\"form-control my-1 telefono" . $row["id"] . " p-0  \" type=\"text\" value=\"\" >";
															} else {
																$textTelefono = "<input placeholder=\" Telefono\" class=\"form-control my-1 telefono" . $row["id"] . " p-0   \" type=\"text\" value=\"$telefono\" id=\"-1\"  >";
															}
															if ($saldo != $pago) {
																echo "<tr class=\"text-center small \">";
																echo "<td>" . $idCliente . "</td>";
																echo "<td>" . $cliente . "</td>";
																echo "<td>" . $direccion . "</td>";
																echo "<td>" . $corte . "</td>";
																echo "<td>" . $pago . "</td>";
																echo "<td>" . $saldo . "</td>";
																echo "<td>" . $velocidad . "</td>";
																echo "<td>" . $ip . "</td>";
																echo "<td>" . $textTelefono . "<button type=\"button\" class=\"btn btn-light updateTel\" id=\"$idCliente\" ><i class=\"icon-arrows-cw text-success\"></i></button></td>";
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
											<h4 class="card-title mt-4">Lista de Clientes de este mes <strong>Corte 1 retrasados </strong> de pago </h4>
											<table id="Table_clientes_cte1_retrasados" class="display responsive datatable_Table_clientes_cte1_retrasados cell-border" cellspacing="0" width="100%">
												<thead class="bg-danger text-light">
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
												<tbody>
													<?php
													$sql = "SELECT id,cliente,apellido,telefono,direccion,corte,activo,`velocidad-plan`,ip,pago FROM `redesagi_facturacion`.`afiliados` WHERE `afiliados`.`eliminar` =0   AND `afiliados`.`activo` =1 AND `afiliados`.`corte` =1 ORDER BY `id` DESC ";
                                                    if ($result = $mysqli->query($sql)){
														while ($row = $result->fetch_assoc()) {
															$cod = $row["id"];
															$sqlz = "SELECT id,cliente,apellido,telefono,direccion,corte,activo,`velocidad-plan`,ip,pago,cerrado, COUNT(`factura`.`cerrado`) as counts 
																FROM `redesagi_facturacion`.`afiliados`     
																INNER JOIN `factura`
																				ON `afiliados`.`id` = `factura`.`id-afiliado`
																WHERE `factura`.`id-afiliado`=$cod AND `factura`.`cerrado`=0 
                                                                ";
                                                            if ($resultz = $mysqli->query($sqlz)) {
																$rowz = $resultz->fetch_assoc();
																$counts = $rowz["counts"];
																//echo "<p>id:-$cod-:cerrado:".$rowz["cerrado"].":-$counts-</p>";
																if (($rowz["cerrado"] != "") && ($counts == 1)) {
																	$cliente = $rowz["cliente"] . " " . $row["apellido"];
																	$direccion = $rowz["direccion"];
																	$corte = $rowz["corte"];
																	$pago = $rowz["pago"];
																	$velocidad = $rowz["velocidad-plan"] . " Megas";
																	$activo = $rowz["activo"];
																	$telefono = $rowz["telefono"];
																	if ($telefono == "") {
																		$textTelefono = "<input placeholder=\" Telefono\" class=\"form-control my-1 telefonoAtrasado" . $cod . " p-0  \" type=\"text\" value=\"\" >";
																	} else {
																		$textTelefono = "<input placeholder=\" Telefono\" class=\"form-control my-1 telefonoAtrasado" . $cod . " p-0   \" type=\"text\" value=\"$telefono\" id=\"-1\"  >";
																	}
																	echo "<tr class=\"text-center  \">";
																	echo "<td>" . $cod . "</td>";
																	echo "<td>" . $cliente . "</td>";
																	echo "<td>" . $direccion . "</td>";
																	echo "<td>" . $corte . "</td>";
																	echo "<td>" . $pago . "</td>";
																	echo "<td>" . $velocidad . "</td>";
																	echo "<td>" . $textTelefono . "<button type=\"button\" class=\"btn btn-light updateTelAtrasado\" id=\"$cod\" ><i class=\"icon-arrows-cw text-success\"></i></button></td>";
																	echo "<td><button type=\"button\" class=\"btn btn-light smsclientAtrasado\" id=\"$cod\" ><i class=\"icon-export text-success\"></i></button></td>";
																	echo "</tr>";
																}
															$resultz->free();
															}
														}
														$result->free();
													}
													?>
												</tbody>
											</table>
											<hr>
											<hr>
											<hr>
											<h4 class="card-title mt-4">Lista de Clientes de este mes <strong>Corte 15 retrasados </strong> de pago </h4>
											<table id="Table_clientes_cte15_retrasados" class="display datatable_Table_clientes_cte15_retrasados cell-border" cellspacing="0" width="100%">
												<thead class="bg-info text-dark">
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
												<tbody>
													<?php
													$sql = "SELECT id,cliente,apellido,telefono,direccion,corte,activo,`velocidad-plan`,ip,pago FROM `redesagi_facturacion`.`afiliados` WHERE `afiliados`.`eliminar` =0   AND `afiliados`.`activo` =1 AND `afiliados`.`corte` =15 ORDER BY `id` DESC ";
													if ($result = $mysqli->query($sql)){
														while ($row = $result->fetch_assoc()) {
															$cod = $row["id"];
															$sqlz = "SELECT id,cliente,apellido,telefono,direccion,corte,activo,`velocidad-plan`,ip,pago,cerrado,  COUNT(`factura`.`cerrado`) as counts 
															FROM `redesagi_facturacion`.`afiliados`     
															INNER JOIN `factura`
																			ON `afiliados`.`id` = `factura`.`id-afiliado`
															WHERE `factura`.`id-afiliado`=$cod AND `factura`.`cerrado`=0 
															";
															if ($resultz = $mysqli->query($sqlz)) {
																$rowz = $resultz->fetch_assoc();
																$counts = $rowz["counts"];
																//echo "<p>id:-$cod-:cerrado:".$rowz["cerrado"].":-$counts-</p>";
																if (($rowz["cerrado"] != "") && ($counts == 1)) {
																	$cliente = $rowz["cliente"] . " " . $row["apellido"];
																	$direccion = $rowz["direccion"];
																	$corte = $rowz["corte"];
																	$pago = $rowz["pago"];
																	$velocidad = $rowz["velocidad-plan"] . " Megas";
																	$activo = $rowz["activo"];
																	$telefono = $rowz["telefono"];
																	if ($telefono == "") {
																		$textTelefono = "<input placeholder=\" Telefono\" class=\"form-control my-1 telefonoAtrasadoc15" . $cod . " p-0  \" type=\"text\" value=\"\" >";
																	} else {
																		$textTelefono = "<input placeholder=\" Telefono\" class=\"form-control my-1 telefonoAtrasadoc15" . $cod . " p-0   \" type=\"text\" value=\"$telefono\" id=\"-1\"  >";
																	}
																	echo "<tr class=\"text-center  \">";
																	echo "<td>" . $cod . "</td>";
																	echo "<td>" . $cliente . "</td>";
																	echo "<td>" . $direccion . "</td>";
																	echo "<td>" . $corte . "</td>";
																	echo "<td>" . $pago . "</td>";
																	echo "<td>" . $velocidad . "</td>";
																	echo "<td>" . $textTelefono . "<button type=\"button\" class=\"btn btn-light updateTelAtrasadoc15\" id=\"$cod\" ><i class=\"icon-arrows-cw text-success\"></i></button></td>";
																	echo "<td><button type=\"button\" class=\"btn btn-light smsclientAtrasadoc15\" id=\"$cod\" ><i class=\"icon-export text-success\"></i></button></td>";
																	echo "</tr>";
																}
																$resultz->free();
															}
														}
														$result->free();
													}
													?>
												</tbody>
											</table>
											<hr>
										</div>
										<div id="massive_notifications_content" class="col-12 border border-dark">
											<!-- Inicio  de contenido de sms mensual... -->
											<div class="row">
												<div class=" border border-warning col-md-8">
													
													<div class=" d-flex flex-column     col-12">
														<div class="" id="criterios_content">
															<div>
																<h1 class="bg-dark text-white rounded p-1 my-1">Criterio Personalizado (Onurix)</h1>
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
																			<option SELECTED value="-1:-1">Todas</option>
																		<?php
																					$sql = "SELECT * FROM `areas` WHERE 1 ";
																					if ($result = $mysqli->query($sql)) {
																						
																						while ($row = $result->fetch_assoc()) {
																							$id=$row["id"];
																							$t="";
																							if($id==$idAreaDefault)$t="";
																							$nombre=$row["nombre"];
																							$ciudad=$row["ciudad"];
																							echo "<option $t value=\"$id:$ciudad\">$nombre $ciudad</option>";
																						}
																						$result->free();
																					}
																					?>

																		</select>
																		<label for="">Exepto </label>
																		<select class="form-control" id="sms_masive_city_except">
																				<option SELECTED value="-2:-2">Ninguna</option>
																		<?php
																					$sql = "SELECT * FROM `areas` WHERE 1 ";
																					if ($result = $mysqli->query($sql)) {
																						
																						while ($row = $result->fetch_assoc()) {
																							$id=$row["id"];
																							$t="";
																							if($id==$idAreaDefault)$t="";
																							$nombre=$row["nombre"];
																							$ciudad=$row["ciudad"];
																							echo "<option $t value=\"$id:$ciudad\">$nombre $ciudad</option>";
																						}
																						$result->free();
																					}
																					?>

																		</select>
																		<small id="" class="form-text text-muted">Por defecto se buscan clientes de cualquier ciudad.</small>
																	</div>
																	<div class="form-group px-1 border border-success rounded mx-1 my-3">
																		<label for="sel1">Fecha de Pago </label>
																		<select class="form-control" id="payment_date">
																			<option value="1">1 de cada mes</option>
																			<option value="15">15 de cada mes</option>
																			<option value="" selected>Cualquier fecha de pago</option>
																		</select>
																		<small id="" class="form-text text-muted">Por defecto se buscan clientes de cualquier fecha de pago.</small>
																	</div>
																	<div class="form-group px-1 border border-success rounded mx-1 my-3">
																		<label for="sel1">Estado de cuenta del cliente </label>
																		<select class="form-control" id="client_state">
																			<option value="1">Facturación al día</option>
																			<option value="-1">Facturación en mora</option>
																			<option value="0" selected>Cualquier estado de cuenta</option>
																		</select>
																		<small id="" class="form-text text-muted">Por defecto se buscan clientes de cualquier estado de cuenta.</small>
																	</div>
																	<div class="form-group px-1 border border-success rounded mx-1 my-3">
																		<label for="client_state_suspension">Estado suspensiòn del cliente </label>
																		<select class="form-control" id="client_state_suspension">
																			<option value="1">Suspendidos</option>
																			<option value="0" selected>No suspendidos</option>
																		</select>
																		<small id="" class="form-text text-muted">Por defecto se buscan clientes NO suspendidos actualmente.</small>
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
														

														


													</div>
												</div>
												<div class=" border border-success col-md-4">
													<div class="col-12 px-0 ">
														<h1 class="bg-dark text-white rounded py-1 px-0 my-1">Statistics</h1>
													</div>

													<div class=" d-flex flex-column  p-1 border border-danger col-12">
														<div class="p-1">
															<div class="card">
																<div class="card-header bg-info">
																	Last Ip Shut-Off
																</div>
																<div class="card-body" id="divIpShutOff">
																	
																</div>
																
															</div>
															<div class="card mt-3">
																
																<div class="card-header bg-info ">
																	last sms sent
																</div>
																<div class="card-body" id="div_sms_statistics">
																	
																</div>
															</div>
														</div>
														<!--  -->
														<?php
														$sql_msj_types = "select * from redesagi_facturacion.smscontent where 1 ";
														$result_sms = mysqli_query($mysqli, $sql_msj_types) or die('error');
														while ($row = $result_sms->fetch_assoc()) {

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
																				";
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
		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
		<script src="../bower_components/Popper/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
		<script src="../bower_components/alertify/js/alertify.min.js"></script>
		<script src="../bower_components/AutoFormatCurrency/simple.money.format.js"></script>
		<script src="../js/dataTables.checkboxes.min.js"></script>

		<script>
			<?php     
			
			if ($_GET['opc']) {
				echo "
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
			} else {
				echo "
			$('#active_client_currently').removeClass(\"active\");
			$('#active_client_currently_content').hide();
			$('#sms_notification').removeClass(\"active\");
			$('#sms_notification_content').hide();
			$('#business-info').hide();
			$('#massive_notifications').removeClass(\"active\");
			$('#massive_notifications_content').hide();
			$('#new_client_registration').addClass(\"active\");
			$('#new_client_registration_content').show();\n
			\n";
			}
			echo "\n";
			?>
			console.log('hola mundo')
			$('#new_client_registration').click(function() {
				$('#active_client_currently').removeClass("active");
				$('#active_client_currently_content').hide();
				$('#sms_notification').removeClass("active");
				$('#sms_notification_content').hide();
				$('#massive_notifications').removeClass("active");
				$('#massive_notifications_content').hide();
				$('#new_client_registration').addClass("active");
				$('#new_client_registration_content').show();
				$("#business-info").hide();
			});
			$('#active_client_currently').click(function() {
				console.log("voy a mostrar clientes")
				$('#new_client_registration').removeClass("active");
				$('#new_client_registration_content').hide();
				$('#sms_notification').removeClass("active");
				$('#sms_notification_content').hide();
				$('#massive_notifications').removeClass("active");
				$('#massive_notifications_content').hide();
				$('#active_client_currently').addClass("active");
				$('#active_client_currently_content').show();
				if (!$.fn.DataTable.isDataTable('#table_active_client')) {
					console.log('voy a dibujar la tabla')
					var table_active_client = $('#table_active_client').DataTable({
						"responsive": true,
						"paging": true,
						"searching": true,
						"info": true
					});
					table_active_client.order([0, 'desc']);
					table_active_client.draw();
					var table_no_active_client = $('#table_no_active_client').DataTable();
					table_no_active_client.draw();

				}
			});
			$('#sms_notification').click(function() {
				$('#active_client_currently').removeClass("active");
				$('#active_client_currently_content').hide();
				$('#new_client_registration').removeClass("active");
				$('#new_client_registration_content').hide();
				$('#massive_notifications').removeClass("active");
				$('#massive_notifications_content').hide();
				$('#sms_notification').addClass("active");
				$('#sms_notification_content').show();
				if (!$.fn.DataTable.isDataTable('#Table_morosos')) {
					var tableMorosos = $('#Table_morosos').DataTable({
						"responsive": true,
						"paging": true,
						"searching": true,
						"info": true
					});
					tableMorosos.order([4, 'desc']);
					tableMorosos.draw();
					var Table_clientes_cte1_retrasados = $('#Table_clientes_cte1_retrasados').DataTable({
						"responsive": true,
						"paging": true,
						"searching": true,
						"info": true
					});
					Table_clientes_cte1_retrasados.draw();
					var Table_clientes_cte15_retrasados = $('#Table_clientes_cte15_retrasados').DataTable({
						"responsive": true,
						"paging": true,
						"searching": true,
						"info": true
					});
					Table_clientes_cte15_retrasados.draw();

				}
			});
			$(".dataTable_Morosos").on('click', '.updateTel', function() {
				var id = $(this).attr('id');
				$(this).toggleClass("btn-light");
				var telefono = $(".telefono" + id).val();
				if ($.isNumeric(telefono) && (telefono.length == 10)) {
					if ((telefono).match(/^\d+$/)) {
						//alertify.success("Número Telefónico actualizado correctamente."); 
						$.ajax({
							type: 'post',
							url: '../edit_cli.php',
							data: {
								id: id,
								telefono: telefono
							},
							success: function(data) {
								alertify.success(data);
							}
						});
					} else {
						alertify.error('telefono invalido, tiene puntos decimales!!');
					}
				} else {
					alertify.error('telefono invalido!!');
				}
			});

			$('#massive_notifications').click(function() {
				$('#active_client_currently').removeClass("active");
				$('#active_client_currently_content').hide();
				$('#sms_notification').removeClass("active");
				$('#sms_notification_content').hide();
				$('#new_client_registration').removeClass("active");
				$('#new_client_registration_content').hide();
				$('#massive_notifications').addClass("active");
				$('#massive_notifications_content').show();
				$('#sms_masivo_container_buscar').hide();
							
				$('#btn_enviar').hide();   
				$.ajax({
					url: "../sms_statistics.php",
					success: function(result) {
						$("#div_sms_statistics").html(result);
						if (!$.fn.DataTable.isDataTable('#table_statistics')) {
							var statistics = $('#table_statistics').DataTable({
								"responsive": true,
								"paging": true,
								"searching": false,
								"info": true,
								"order": [[ 3, "desc" ]]
							});

						}
					}
				});
				$.ajax({
					url: "../shutoff_statistics.php",   
					success: function(result) {
						$("#divIpShutOff").html(result);
						if (!$.fn.DataTable.isDataTable('#table_ip_shutoff')) {
							var statistics = $('#table_ip_shutoff').DataTable({
								"responsive": true,
								"paging": true,
								"searching": false,
								"info": true,
								"order": [[ 1, "desc" ]]
							});

						}
					}
				});

			});
			$("#continue-btn-registration").on('click', function() {
				if (allLetter($("#name").val()) == true) {
					$("#name").removeClass("border border-danger");
					$("#name").addClass("border border-success");
					if (allLetter($("#last-name").val()) == true) {
						$("#last-name").removeClass("border border-danger");
						$("#last-name").addClass("border border-success");
						if (allnumeric($("#cedula").val()) == true) {
							$("#cedula").removeClass("border border-danger");
							$("#cedula").addClass("border border-success");
							if (required($("#address").val()) == true) {
								$("#address").removeClass("border border-danger");
								$("#address").addClass("border border-success");
									if (allLetter($("#departamento").val()) == true) {
										$("#departamento").removeClass("border border-danger");
										$("#departamento").addClass("border border-success");
										if (phonenumber($("#phone").val()) == true) {
											$("#phone").removeClass("border border-danger");
											$("#phone").addClass("border border-success");
											if (validEmail($("#email").val())) { 
												$("#email").removeClass("border border-danger");
												$("#email").addClass("border border-success");
												if (validEmail($("#email-confirmar").val()) && ($("#email-confirmar").val() == $("#email").val())) {
													$("#email-confirmar").removeClass("border border-danger");
													$("#email-confirmar").addClass("border border-success");
													if(ValidateIPaddress($("#ipAddress").val())){
														$("#business-info").show();
														$('#ClientModal').modal('toggle');
														vistaModal();
													}else{
														alertify.error('ip address incorrecto');
														$("#ipAddress").addClass("border border-danger");
													}
												} else {
													alertify.error('Confirmación de email incorrecto');
													$("#email-confirmar").addClass("border border-danger");
												}
											} else {
												alertify.error('Email incorrecto');
												$("#email").addClass("border border-danger");
											}
										} else {
											alertify.error('Teléfono incorrecto');
											$("#phone").addClass("border border-danger");
										}
									} else {
										alertify.error('Departamento incorrecto');
										$("#departamento").addClass("border border-danger");
									}
							} else {
								alertify.error('Dirección incorrecto');
								$("#address").addClass("border border-danger");
							}
						} else {
							alertify.error('Cédula incorrecto');
							$("#cedula").addClass("border border-danger");
						}
					} else {
						alertify.error('Apellido incorrecto');
						$("#last-name").addClass("border border-danger");
					}
				} else {
					alertify.error('Nombre incorrecto');
					$("#name").addClass("border border-danger");
				}
			})

			$(".datatable_Table_clientes_cte1_retrasados").on('click', '.updateTelAtrasado', function() {
				var id = $(this).attr('id');
				$(this).toggleClass("btn-light");
				var telefono = $(".telefonoAtrasado" + id).val();
				if ($.isNumeric(telefono) && (telefono.length == 10)) {
					if ((telefono).match(/^\d+$/)) {
						//alertify.success("Número Telefónico actualizado correctamente.");
						$.ajax({
							type: 'post',
							url: '../edit_cli.php',
							data: {
								id: id,
								telefono: telefono
							},
							success: function(data) {
								alertify.success(data);
							}
						});
					} else {
						alertify.error('telefono invalido, tiene puntos decimales!!');
					}
				} else {
					alertify.error('telefono invalido!!');
				}
			});
			$(".datatable_Table_clientes_cte15_retrasados").on('click', '.updateTelAtrasadoc15', function() {
				var id = $(this).attr('id');
				$(this).toggleClass("btn-light");
				var telefono = $(".telefonoAtrasadoc15" + id).val();
				if ($.isNumeric(telefono) && (telefono.length == 10)) {
					if ((telefono).match(/^\d+$/)) {
						//alertify.success("Número Telefónico actualizado correctamente.");
						$.ajax({
							type: 'post',
							url: '../edit_cli.php',
							data: {
								id: id,
								telefono: telefono
							},
							success: function(data) {
								alertify.success(data);
							}
						});
					} else {
						alertify.error('telefono invalido, tiene puntos decimales!!');
					}
				} else {
					alertify.error('telefono invalido!!');
				}
			});
			$(".dataTable_Morosos").on('click', '.smsclientMoroso', function() {
				var id = $(this).attr('id');
				alertify.confirm("Desea enviar mensaje de texto a cliente moroso?",
					function() {
						var telefono = $(".telefono" + id).val();
						var smsText = "Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio. www.ispexperts.com";

						$.post("../smst.php", {
								telefono: telefono,
								msj: smsText
							},
							function(data, status) {
								var res = data.substring(0, 2);
								console.log("status" + status);
								if (res == "ok") {
									alertify.success("Mensaje ha sido enviado con éxito a cliente moroso");

								} else if (res == "no") {
									alertify.error("Información enviada contiene errores a cliente moroso");

								} else {
									alertify.alert("Fordibben from morosos scheme ");

								}
							});


					},
					function() {
						alertify.error('Cancel');
					});
			});
			$(".datatable_Table_clientes_cte1_retrasados").on('click', '.smsclientAtrasado', function() {
				var id = $(this).attr('id');
				alertify.confirm("Desea enviar mensaje de texto a cliente atrasado cliente corte 1 ?",
					function() {

						var telefono = $(".telefonoAtrasado" + id).val();
						var smsText = "Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio. www.ispexperts.com";
						$.post("../smst.php", {
								telefono: telefono,
								msj: smsText
							},
							function(data, status) {
								var res = data.substring(0, 2);
								console.log("status" + status);
								if (res == "ok") {
									alertify.success("Mensaje ha sido enviado con éxito a cliente corte 1");

								} else if (res == "no") {
									alertify.error("Información enviada contiene errores a cliente corte 1");

								} else {
									alertify.alert("Fordibben from corte 1 scheme ");

								}
							});

					},
					function() {
						alertify.error('Cancel');
					});
			});
			$(".datatable_Table_clientes_cte15_retrasados").on('click', '.smsclientAtrasadoc15', function() {
				var id = $(this).attr('id');
				alertify.confirm("Desea enviar mensaje de texto a cliente Corte 15 atrasado?",
					function() {
						var telefono = $(".telefonoAtrasadoc15" + id).val();
						var smsText = "Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio. www.ispexperts.com";
						$.post("../smst.php", {
								telefono: telefono,
								msj: smsText
							},
							function(data, status) {
								var res = data.substring(0, 2);
								console.log("status" + status);
								if (res == "ok") {
									alertify.success("Mensaje ha sido enviado con éxito cliente corte 15");

								} else if (res == "no") {
									alertify.error("Información enviada contiene errores cliente corte 15");

								} else {
									alertify.alert("Fordibben from corte 15 scheme ");

								}
							});


					},
					function() {
						alertify.error('Cancel');
					});
			});
			$(".datatable_table_active_client").on('click', '.eliminar', function() {
				var x = $(this).attr('id');
				var idCli = x.match(/\d+/); // 123456	
				var idc = idCli * 1;
				var source = "eliminar";
				alertify.prompt("Escriba razón de eliminar usuario del servicio .Gracias", "",
					function(evt, value) {
						var detalle = value;
						$("#" + x).removeClass("btn-primary");
						$("#" + x).addClass("disabled");
						$("#" + x + " > i").removeClass("text-dark");
						$("#" + x + " > i").addClass("text-danger");
						$.ajax({
							type: 'post',
							url: '../upd_cli.php',
							data: {
								idClient: idc,
								detalle: detalle,
								source: source
							},
							success: function(data) {
								alertify.success(data);
								window.location.href = 'client.php?opc=reload';
							}
						});
					},
					function() {
						alertify.error('Cancel');
					});
			});

			$(".datatable_table_active_client").on('click', '.suspender', function() {
				var x = $(this).attr('id');
				var idCli = x.match(/\d+/); // 123456	
				var idc = idCli * 1;
				var source = "suspender";
				alertify.prompt("Escriba razón de suspensión del servicio.Gracias", "",
					function(evt, value) {
						var detalle = value;
						$("#" + x).removeClass("btn-primary");
						$("#" + x).addClass("disabled");
						$("#" + x + " > i").removeClass("text-dark");
						$("#" + x + " > i").addClass("text-danger");
						$.ajax({
							type: 'post',
							url: '../upd_cli.php',
							data: {
								idClient: idc,
								detalle: detalle,
								source: source
							},
							success: function(data) {
								alertify.success(data);
								window.location.href = 'client.php?opc=reload';
							}
						});
					},
					function() {
						alertify.error('Cancel');
					});
			});

			$("#table_no_active_client").on('click', '.reactivar', function() {
				var x = $(this).attr('id');
				var idCli = x.match(/\d+/); // 123456	
				var idc = idCli * 1;
				var source = "reactivar";
				alertify.prompt("Escriba razón de reconexión del servicio.Gracias", "",
					function(evt, value) {
						var detalle = value;
						$("#" + x).removeClass("btn-primary");
						$("#" + x).addClass("disabled");
						$("#" + x + " > i").removeClass("text-dark");
						$("#" + x + " > i").addClass("text-danger");
						$.ajax({
							type: 'post',
							url: '../upd_cli.php',
							data: {
								idClient: idc,
								detalle: detalle,
								source: source
							},
							success: function(data) {
								alertify.success(data);
								window.location.href = 'client.php?opc=reload';
							}
						});
					},
					function() {
						alertify.error('Cancel');
					});
			});

			$('#save').click(function() {
				var afiliacion = $("#valorAfiliacion").val();
				if ($.isNumeric(afiliacion)) {
					$('#valorAfiliacion').removeClass("border border-danger");
					var valorPlan = $("#valor-plan").val();
					var fecha = $("#date-picker").val();
					if (validDate(fecha)) {
						$("#date-picker").removeClass("border border-danger");
						var mergeItems = $("#mergeItems").val();

						if (subtotal()) {
							if ($.isNumeric(valorPlan) && (valorPlan != "0")) {
								$("#valor-plan").removeClass("border border-danger");
								var recibo = 0;
								var name =ucwords($("#name").val()); 
								var lastName = ucwords($("#last-name").val());
								var cedula = $("#cedula").val();
								var address = ucwords($("#address").val());
								var ciudad = ucwords($("#ciudad").val());
								var departamento = ucwords($("#departamento").val());
								var phone = $("#phone").val();
								var email = $("#email").val();
								var corte = $("#corte").val();
								var monthSelected = $("#mes").val();
								var plan = $("#plan").val();
								var velocidadPlan = $("#velocidad-plan").val();
								var ipAddress =$("#ipAddress").val(); 
								var quote =$("#quote").val(); 
								var generarFactura = $("#generar-factura").val();
								var valorAfiliacion = $("#valorAfiliacion").val();
								var mergeItems = $("#mergeItems").val();
								var standby = $("#standby").val();
								var standarServiceFlag = $("#standarServiceFlag").val();
								var AfiliacionItemValue = $("#AfiliacionItemValue").val();
								var valorProrrateo = $("#valorProrrateo").val();
								var valorApagar = $("#valorApagar").val();
								var ivaV=iva();
								var valorAdicionalServicio = $("#valorAdicionalServicio").val();
								var valorAdicionalServicioDescripcion = $("#valorAdicionalServicioDescipcion").val();
								var serviceIsAlreadyInstalled= $("#serviceIsAlreadyInstalled").val();
								var dayOfMonthSelected= $("#dayOfMonthSelected").val();
								var monthSelected= $("#monthSelected").val();
								alertify.confirm("Desea imprimir recibo?",
									function() {
										recibo = 1;
										$.ajax({
											type: 'post',
											url: '../new_cli.php',
											data: {
												valorPlan: valorPlan,
												name: name,
												lastName: lastName,
												address: address,
												ciudad: ciudad,
												departamento: departamento,
												phone: phone,
												email: email,
												corte: corte,
												plan: plan,
												velocidadPlan: velocidadPlan,
												cedula: cedula,
												generarFactura: generarFactura,
												ipAddress: ipAddress,
												quote: quote,
												mergeItems: mergeItems,
												recibo: recibo,
												valorAfiliacion: valorAfiliacion,
												standy:standby,
												standarServiceFlag:standarServiceFlag,
												AfiliacionItemValue:AfiliacionItemValue,
												valorProrrateo:valorProrrateo,
												valorApagar:valorApagar,
												iva:ivaV,
												valorAdicionalServicio:valorAdicionalServicio,
												valorAdicionalServicioDescripcion:valorAdicionalServicioDescripcion,
												serviceIsAlreadyInstalled:serviceIsAlreadyInstalled,
												dayOfMonthSelected:dayOfMonthSelected,
												monthSelected:monthSelected
											},
											success: function(data) {
												console.log('los dato devuletos:'+data)//
												var result = data.split(':');
												var idCl = result[0];
												var messag = result[1];
												alertify.success(messag);
												if (recibo == 1) {
													if (idCl != 'Error') {
														window.open('../factura_new_cli.php?rpp=1&idc='+idCl, '_blank');
														//window.location.href = 'transacciones.php';
													}
												}


											}
										});

									},
									function() {
										recibo = 1;

										$.ajax({
											type: 'post',
											url: '../new_cli.php',
											data: {
												valorPlan: valorPlan,
												name: name,
												lastName: lastName,
												address: address,
												ciudad: ciudad,
												departamento: departamento,
												phone: phone,
												email: email,
												corte: corte,
												plan: plan,
												velocidadPlan: velocidadPlan,
												cedula: cedula,
												generarFactura: generarFactura,
												ipAddress: ipAddress,
												quote: quote,
												mergeItems: mergeItems,
												recibo: recibo,
												valorAfiliacion: valorAfiliacion,
												standy:standby,
												standarServiceFlag:standarServiceFlag,
												AfiliacionItemValue:AfiliacionItemValue,
												valorProrrateo:valorProrrateo,
												valorApagar:valorApagar,
												iva:ivaV,
												valorAdicionalServicio:valorAdicionalServicio,
												valorAdicionalServicioDescripcion:valorAdicionalServicioDescripcion,
												serviceIsAlreadyInstalled:serviceIsAlreadyInstalled,
												dayOfMonthSelected:dayOfMonthSelected,
												monthSelected:monthSelected
											},
											success: function(data) {
												var result = data.split(':');
												var idCl = result[0];
												var messag = result[1];
												alertify.success(messag);
												//console.log(data)
												if (recibo == 1) {
													if (idCl != 'Error') {
														//window.location.href = 'transacciones.php'; 
													}
												}

											}
										});
									}
								).set('labels', {
									ok: 'Sí',
									cancel: 'No'
								});
							} else {
								$("#valor-plan").addClass("border border-danger");

							}
						} else {
							alertify.error("valor Afiliación no puede ser menor que el valor del plan en este caso!");
						}
					} else {
						$("#date-picker").addClass("border border-danger");
						alertify.error("Fecha servicio incorrecta");
					}

				} else {
					$("#valorAfiliacion").addClass("border border-danger");
					alertify.error("valor Afiliacion Incorrecto");
				}

			});


			$('#sms_masivo_btn_buscar').click(function() {
				$('#spinner-buscar').addClass('spinner-border');
				$('#btn_enviar').show();
				$('#sms_masivo_container_buscar').show();				
				$('#spinner-enviar').removeClass('spinner-border');
				var name = $("#sms_masive_name").val();
				var address = $("#sms_masive_address").val();
				var ciudad = $("#sms_masive_city").val();
				var ciudadExcept = $("#sms_masive_city_except").val();
				var corte = $("#payment_date").val();
				var criterioFacturacion = $("#client_state").val();
				var criterioFacturacionSuspencion = $("#client_state_suspension").val();

				$.ajax({
					type: 'post',
					url: '../tableMasiveSms.php',
					data: {
						name: name,
						address: address,
						ciudad: ciudad,
						ciudadExcept: ciudadExcept,
						corte: corte,
						criterioFacturacion: criterioFacturacion,
						criterioFacturacionSuspencion: criterioFacturacionSuspencion
					},
					success: function(data) {
						alertify.success("Información en la tabla ha sido actualizada"); 
						console.log(data)
						$('#sms_masivo_container_buscar').html(data);
						var table = $('#table_client_to_sms').DataTable({
							responsive: {
								details: {
									type: 'column',
									target: 'tr'
								}
							},
							"order": [[ 5, "desc" ]],
							"paging": true,
							"searching": true,
							"info": true,
							'columnDefs': [{
								'targets': 0,
								'checkboxes': {
									'selectRow': true,
									'selectCallback': function(nodes, selected){										
											selectedIpValidator(table);						
													
									}									
								}
							}]
							
						});
						table.on( 'draw', function () {
							console.log( 'Redraw occurred at: '+new Date().getTime() );
							var info = table.page.info();
							console.log( 'Showing page: '+info.page+' of '+info.pages );
						 	selectedIpValidator(table);	

						} );	
						// $('#table_client_to_sms').on( 'page.dt', function () {
						// 	var info = table.page.info();
						// 	console.log( 'Showing page: '+info.page+' of '+info.pages );
							
						// });	

						$('#spinner-buscar').removeClass('spinner-border');
						$('#message_box').hide();
						$('#sendsmsbutton').hide();
						$('#cutServiceButton').hide();
						$("input[name=options]").change(function () {	 
							if($(this).val()==1 ){
								$('#message_box').show();
								$('#sendsmsbutton').show();
								$('#cutServiceButton').hide();
							}
							else{
								$('#message_box').hide();
								$('#sendsmsbutton').hide();
								$('#cutServiceButton').show();
							}
						});	 				
						$('#table_client_to_sms').on('click', '.checkPing', function(){ 
							let id=this.id.split('-')[1];
							let ipstr= '#ip--'+id;
							let ip=$(ipstr).val();
							let strButton="checkPing-"+id
							let textCheckPing="textCheckPing-"+id
							$("#"+strButton+" i").removeClass("icon-arrows-ccw")
							$("#"+strButton+" i").addClass("icon-clock bg-warning")
							$("#"+textCheckPing+" h6 small ").html("<i class=\" text-info\">Esperando</i>")
							console.log("La ip es:"+ip+" el id es:"+id)
							$.ajax({
								type: 'post',
								url: '../devicePingResponse.php',
								data: {
									ip: ip						
								},
								success: function(data) {
									console.log(JSON.stringify(data))							
									let obj =JSON.parse(data)
									console.log("el dato es:"+obj.time)
									$("#"+strButton+" i").removeClass("icon-clock bg-warning")
									$("#"+strButton+" i").addClass("icon-arrows-ccw")
									if(obj.time){
										$("#"+textCheckPing+" h6 small ").html("<i class=\"icon-smile text-primary\">Ping:"+obj.time+"ms</i>")
									}
									else{
										$("#"+textCheckPing+" h6 small ").html("<i class=\"icon-emo-unhappy text-danger\">No-ping</i>")
									}
								}
								})
							
						})
						$('#table_client_to_sms').on('focusout', '.inputIp', function(){
							let id=this.id.split('--')[1];
							let dsb='#dsb-'+id;
							let ipstr= '#'+this.id;
							let pstr='#p-'+this.id;
							let ip=$(ipstr).val();
							if(ValidateIPaddress(ip)){
								$(ipstr).removeClass('bg-danger');								
								$.ajax({
									type: 'post',
									url: '../ipupdater.php',
									data: {
										id: this.id.split('--')[1],
										ip: ip
									},
									success: function(data) {
										if(data){
											console.log(data);
											let tr='#tr-'+id;
											if(data==1){
												$(ipstr).addClass('bg-success text-dark');	
												$(dsb).val('');
												$(pstr).removeClass('border border-danger rounded');
												$(tr).removeClass('bg-warning');
												$(tr+' td:first-child + td ').removeClass('bg-warning');
												$(tr+' td:first-child input ').prop('disabled', false);
												alertify.success('Ip actualizada con éxito');	//	
											}
											else{												
												alertify.error('ups '+data,10);
												$(dsb).val(id);
												$(ipstr).addClass('bg-danger text-dark');
												$(tr+' td:first-child input ').prop('disabled', true);
											}

										}
										else
											alertify.error('Error al actializar la ip!');								
									}
								});

							}
							else{
								$(dsb).val(id);
								$(ipstr).removeClass('bg-success ');
								$(ipstr).addClass('bg-danger text-dark');
							}						

						});


						$('#btn_send_sms').click(function() {							
							$('#spinner-enviar').addClass('spinner-border');
							var message = $('#sms_text_content').val();
							var rows_selected = table.column(0).checkboxes.selected();							
							var iddata = rows_selected.join(",");
							if (iddata && message) {
								$.ajax({
									type: 'post',
									url: '../smsender.php',
									data: {
										datos: iddata,
										message: message
									},
									success: function(data) {
										console.log(data);
										alertify.dismissAll();
										alertify.success('Solicitud ha procesada');
										$('#spinner-enviar').removeClass('spinner-border');
										
									}
								});
							} else {
								$('#spinner-enviar').removeClass('spinner-border');
								alertify.dismissAll();
								if (!message)
									alertify.error('No has seleccionado mensaje para enviar!');
								if (!iddata)
									alertify.error('No has seleccionado clientes!');
							}
						});

						/** */
						$('#btn_cut_service').click(function() {							
							$('#spinner-enviar').addClass('spinner-border');							
							var rows_selected = table.column(0).checkboxes.selected();
							let invalid= [];
							$.each(rows_selected, function(index, rowId) {
								let dsb='#dsb-'+rowId;
								let idSelectedFlag=$(dsb).val();
								let tr='#tr-'+rowId;
								if(idSelectedFlag!=""){
									invalid.push(rowId);
								}
								$(tr+' td:first-child input ').prop('disabled', false);
								$(tr).removeClass('bg-warning');
								$(tr+' td:first-child + td ').removeClass('bg-warning');
								table.column(0).checkboxes.deselect();

							});
							var iddata = rows_selected.join(",");
							let temp = invalid.join(",");
							let valid= array_diff(rows_selected,invalid);
							let validIdData=valid.join(",");
							console.log("Selected:"+iddata+"  Invalid: "+temp  +"  Valid Array:"+validIdData);
							if (validIdData) {
								$.ajax({
									type: 'post',
									url: '../removeclientservice.php',
									data: {
										datos: validIdData										
									},
									success: function(data) {
										alertify.dismissAll();
										alertify.success(data);
										$('#spinner-enviar').removeClass('spinner-border');
										$.ajax({
											url: "../shutoff_statistics.php",   
											success: function(result) {
												$("#divIpShutOff").html(result);
												if (!$.fn.DataTable.isDataTable('#table_ip_shutoff')) { 
													var statistics = $('#table_ip_shutoff').DataTable({
														"responsive": true,
														"paging": true,
														"searching": false,
														"info": true,
														"order": [[ 1, "desc" ]]
													});

												}
											}
										});										
									}
								 });
							} else {
								$('#spinner-enviar').removeClass('spinner-border');
								alertify.dismissAll();								
								if (!validIdData)
									alertify.error('No has seleccionado clientes con Ip válida!');
							}
						});
						/** */
					}
				});


			});

			$("#mes").on('change', function() {
				//invoicegenerator();
			});
			$("#mergeItems").on('change', function() {
				if ($('#mergeItems').is(":checked")) {
					$("#mergeItems").val(1);
					$('#afiliacionProrrateoDiv').show();
				} else {
					$("#mergeItems").val(0);
					$('#afiliacionProrrateoDiv').hide();
				}
				invoicegenerator();
			});
			$("#left-day").on('change', function() {
				if ($("#left-day").is(":checked")) {
					$('#valorAfiliacion').val(0);
					afiliacionTrView(0);
					prorrateorow();
					$("#tr-prorrateo").show();
					$("#afiliacion-prorrateo").show();
				} else {
					$('#valorAfiliacion').val(0);
					afiliacionTrView(0);
					prorrateorow();
					$("#tr-prorrateo").hide();
					$("#afiliacion-prorrateo").hide();
					$("#valorApagar").val($('#valorAfiliacion').val());
					$("#valorProrrateo").val(0);

				}

			});
			$("#afiliacion-prorrateo").on('change', function() {
				if ($('#afiliacion-prorrateo').is(":checked")) {
					$("#afiliacion-prorrateo").val(1);
				} else {
					$("#afiliacion-prorrateo").val(0);
				}
				invoicegenerator();
			});
			$("#plan").on('change', function() {
				invoicegenerator();
			});
			$("#velocidad-plan").on('change', function() {
				invoicegenerator();
			});
			$("#valor-plan").keyup(function() {
				var valorPlan = $("#valor-plan").val();
				if (parseInt(valorPlan)) {					
					invoicegenerator();
				} else {
					$("#valor-plan").val("");
					alertify.error("Solo se adminten valores numéricos en el valor del plan!")
				}
			});
			$("#valorAfiliacion").keyup(function() {
				invoicegenerator("afiliacion");
			});

			function afiliacionDivItems(caso) {
				var afiliacion = parseInt($("#valorAfiliacion").val());
				var valorPlan = parseInt($("#valor-plan").val());
				var valorProrrateo = parseInt( $("#valorProrrateo").val() );
				var valorAdicionalServicio = parseInt( $("#valorAdicionalServicio").val() );
				var fecha = $("#date-picker").val();
				if ($("#left-day").is(":checked")) {
					if(caso=="caso1"){
						if (afiliacion >= (valorProrrateo) ){
							if (fecha != "") {
								$("#standbyDiv").show();
							}
						} else if (afiliacion < valorProrrateo) {
							$("#standbyDiv").hide();
						}	
					}
					if(caso=="caso2"){
						console.log("Caso 2!  Afiliación:"+afiliacion+" valor plan:"+valorPlan+"valorAdicionalServicio:"+valorAdicionalServicio)
						if (afiliacion >= (valorPlan+valorAdicionalServicio) ){
							if (fecha != "") {
								$("#standbyDiv").show();
							}
						} else if (afiliacion < (valorPlan+valorAdicionalServicio) ) {
							$("#standbyDiv").hide();
						}
					}
				}
				else{
					if (afiliacion >= (valorPlan) ){
						if (fecha != "") {
							$("#standbyDiv").show();
						}
					} else if (afiliacion < valorPlan) {
						$("#standbyDiv").hide();
					}
				}
			}

			function subtotal() {
				var valorPlan = $("#valor-plan").val();
				var vAfiliacion = $("#valorAfiliacion").val();
				var percentValue = iva();
				var importeAfiliacion = (parseInt(vAfiliacion) - parseInt(valorPlan)) * (percentValue / 100); //Iva
				var baseGravableAfiliacion = parseInt(vAfiliacion) - parseInt(valorPlan) - importeAfiliacion;
				var subtotalAfiliacion = importeAfiliacion + baseGravableAfiliacion;
				var mergeItems = $("#mergeItems").val();
				if (mergeItems == 0)
					return true;
				if (mergeItems == 1 && subtotalAfiliacion >= 0)
					return true;
				return false;
			}

			function vistaModal() {
				var date = new Date();
				var currentDay = parseInt(date.getDate());
				var minDay = (currentDay - 1) * -1;
				$("#valorProrrateoDiv2").hide();
				$("#valorAdicionalServicioDiv").hide();
				$("#valorApagarDiv").hide();
				$("#standbyDiv2").hide();
				$("#standarServiceFlagDiv").hide();
				$("#AfiliacionItemValueDiv").hide();	
				$("#dayOfMonthSelected").hide();
				$("#monthSelected").hide(); 
				$("#tr-prorrateo").hide();
				$("#left-day").hide();
				$("#afiliacionProrrateoDiv").hide();
				$("#div-sm").toggleClass("border");
				$("#date-picker").datepicker({
					dateFormat: 'dd/mm/yy',
					minDate: minDay,
					maxDate: 30,
					onSelect: function(dateText) {
						$("#valorAfiliacion").val(0);
						$("#mergeItems").prop("checked", false);						
						invoicegenerator();
						prorrateorow();
						
					}
				});
			}

			function invoicegenerator(param="") {
				
				$("#valorAdicionalServicio").val(0);
				$("#valorProrrateo").val(0);
				afiliacionDivItems("nada");
				var vPlan = $("#velocidad-plan").val();
				var dateText = $("#date-picker").val();				
				var day = dateText.split('/');
				var daySelected = parseInt(day[0]);
				var monthSelected = parseInt(day[1]);
				var yearSelected = parseInt(day[2]);
				$("#dayOfMonthSelected").val(daySelected)
				$("#monthSelected").val(parseInt(monthSelected))
				//
				var a = new Date();
        		var dayOfMonth = a.getDate();
				//
				console.log("linea 20154, cambiando al false el valor del checkbox del porrateo")
				if(daySelected==1 || daySelected==15){
					if(daySelected==1)$("#corte").val(1);
					if(daySelected==15)$("#corte").val(15);
					$("#left-day").prop("checked", false);
					$("#tr-prorrateo").hide();
					$("#tr-servicio").show();
				}
				if(daySelected>19&&param!="afiliacion"){
					$("#mes").val(parseInt(monthSelected)+1)
					alertify.success("Fechas del 20 en adelante pasan a ser facturas corte 1, empezando a pagar del 1 al 7 del siguiente mes.")
				}else{
					$("#mes").val(parseInt(monthSelected))
					// if(parseInt(dayOfMonth)>19){
					// 	alertify.error("Fecha no permitida ,dia seleccionado vale"+dayOfMonth+"param vale"+param)
					// 	console.log("Fecha no permitida ,dia seleccionado vale "+dayOfMonth+" param vale"+param)
					// 	//$( ".selector" ).datepicker( "refresh" ); //reiniciar picker
					// }
				}
				var days = daysInMonth(monthSelected, yearSelected);
				var periodo = parseInt( $("#mes").val() );
				console.log("periodo vale:"+periodo+"corte vale:"+$("#corte").val())
				 
				var nextMonth=periodo+1;
				if (nextMonth==13) nextMonth=1;
				var month = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
				if (parseInt($("#corte").val()) == 15) {
					$("#td-afiliacion").html('Instalación servicio de Internet B Ancha ,equipos en alquiler.<br><strong>Paga los 15 de cada mes</strong>.<br>Suspensión 20 de cada mes.');
					$("#td-servicio").html('Plan mensual ' + month[periodo].toUpperCase() +'15 a '+ month[nextMonth].toUpperCase()+' 15  <br>' + vPlan + ' Mbps Download <br>1.5 Upload <br><strong>Primer mes de servicio.</strong>');

				}
				if (parseInt($("#corte").val()) == 1) {
					$("#td-afiliacion").html('Instalación servicio de Internet B Ancha ,equipos en alquiler.<br><strong>Paga los 1 de cada mes</strong>.<br>Suspensión 6 de cada mes.');
					$("#td-servicio").html('Plan mensual  ' + month[periodo].toUpperCase() +'1 a '+ month[periodo].toUpperCase()+' '+days+ '<br>' + vPlan + ' Mbps Download <br>1.5 Mbps Upload <br><strong>Primer mes de servicio.</strong>');
				}
				var vAfiliacion = $("#valorAfiliacion").val();
				var percentValue = iva();
				var valorPlan = $("#valor-plan").val();
				if ($('#mergeItems').is(":checked")) {
					var stdby = 1;
				}
				else{
					var stdby = 0;
				}
				
				if (stdby == 1) { //se pagan ambas cosas de una ves, pero se discriminan en la factura.
					var importeAfiliacion = (parseInt(vAfiliacion) - parseInt(valorPlan)) * (percentValue / 100); //Iva
					var baseGravableAfiliacion = parseInt(vAfiliacion) - parseInt(valorPlan) - importeAfiliacion;
					var subtotalAfiliacion = importeAfiliacion + baseGravableAfiliacion;
					var importeServicio = parseInt(valorPlan) * (percentValue / 100); //Iva
					var baseGravableServicio = parseInt(valorPlan) - importeServicio;
					var subtotalServicio = baseGravableServicio + importeServicio;


					if (vAfiliacion == 0 && valorPlan > 0) {
						$("#td-valorAfiliacion").html('$0');

						$("#td-importeAfiliacion").html("%");
						$("#td-subtotalAfiliacion").html("$0");
						$("#AfiliacionItemValue").val(0);
						$("#td-valorServicio").html("$" + baseGravableServicio);
						$("#td-importeServicio").html(percentValue + "% $" + importeServicio);
						$("#td-subtotalServicio").html("$" + subtotalServicio);
						$("#tr-servicio").removeClass("table-danger");
						var valorTotal = parseInt(vAfiliacion);
						$("#td-total").html("$" + valorPlan);
						$("#valorApagar").val(valorPlan);
						//$("#valorServicio").val(subtotalServicio);

					}
					if (vAfiliacion > 0) {
						$("#td-valorAfiliacion").html('$' + baseGravableAfiliacion);
						$("#td-importeAfiliacion").html(percentValue + "% $" + importeAfiliacion);
						$("#td-subtotalAfiliacion").html("$" + subtotalAfiliacion);
						$("#AfiliacionItemValue").val(subtotalAfiliacion);
						$("#td-valorServicio").html("$" + baseGravableServicio);
						$("#td-importeServicio").html(percentValue + "% $" + importeServicio);
						$("#td-subtotalServicio").html("$" + subtotalServicio);
						$("#tr-servicio").removeClass("table-danger");
						var valorTotal = parseInt(vAfiliacion);
						$("#td-total").html("$" + valorTotal);
						$("#valorApagar").val(valorTotal);
						
					}
				}
				if (stdby == 0) {
					var valorTotal = parseInt(vAfiliacion);
					$("#td-total").html("$" + valorTotal);
					$("#valorApagar").val(valorTotal);
					var importeAfiliacion = parseInt(vAfiliacion) * (percentValue / 100); //Iva
					var baseGravableAfiliacion = parseInt(vAfiliacion) - importeAfiliacion;
					var subtotalAfiliacion = importeAfiliacion + baseGravableAfiliacion;
					var importeServicio = parseInt(valorPlan) * (percentValue / 100); //Iva
					var baseGravableServicio = parseInt(valorPlan) - importeServicio;
					var subtotalServicio = baseGravableServicio + importeServicio;
					if (vAfiliacion == 0) {
						$("#td-valorAfiliacion").html('$0');
						$("#td-importeAfiliacion").html("%");
						$("#td-subtotalAfiliacion").html("$0");
						$("#AfiliacionItemValue").val(0);
						$("#td-valorServicio").html("$" + baseGravableServicio);
						$("#td-importeServicio").html(percentValue + "% $" + importeServicio);
						$("#td-subtotalServicio").html("$" + subtotalServicio + "<br><strong>Pendiente de pago</strong>");
						$("#tr-servicio").addClass("table-danger");
					}
					if (vAfiliacion > 0) {
						$("#td-valorAfiliacion").html('$' + baseGravableAfiliacion);
						$("#td-importeAfiliacion").html(percentValue + "% $" + importeAfiliacion);
						$("#td-subtotalAfiliacion").html("$" + subtotalAfiliacion);
						$("#AfiliacionItemValue").val(subtotalAfiliacion);
						$("#td-valorServicio").html("$" + baseGravableServicio);
						$("#td-importeServicio").html(percentValue + "% $" + importeServicio);
						$("#td-subtotalServicio").html("$" + subtotalServicio + "<br><strong>Pendiente de pago</strong>");
						$("#tr-servicio").addClass("table-danger");
					}
				}
				if ($("#left-day").is(":checked")) {
					prorrateorow();
				}
				console.log("valor valorApagar:"+$("#valorApagar").val())//valorApagar 
				console.log("valor afiliacion:"+$("#valorAfiliacion").val())//
				console.log("valor AfiliacionItemValue:"+$("#AfiliacionItemValue").val())//valorApagar
				console.log("valor Valor Plan :"+$("#valor-plan").val())//valorApagar
				
			}

			function prorrateorow() {
				var valorPlan = $("#valor-plan").val();
				var dateText = $("#date-picker").val();
				var dateArray = dateText.split('/');
				var daySelected = parseInt(dateArray[0]);
				var monthSelected = parseInt(dateArray[1]);
				var yearSelected = dateArray[2];
				var increasedMonth = monthSelected;
				var corte = 0;
				var days = daysInMonth(monthSelected, yearSelected);
				var leftDays = days - daySelected;
				var monthN = monthName(monthSelected);
				$("#left-day").hide();
				$("#left-day-text").hide();
				$("#div-sm").addClass("border");
				if(daySelected==1){
					corte=1;
					$("#standarServiceFlag").val(1);
				}
				if (daySelected > 1 && daySelected <= 10) {
					corte = 1;
					$("#standby").val(1);
					if (daySelected > 1 && daySelected <= 10) {
						$("#left-day").show();
						$("#left-day-text").show();
						$("#afiliacion-prorrateo").show();
						$("#left-day-text").html("Cobrar " + leftDays + " días desde " + daySelected + " de " + monthN + " hasta " + days + " de " + monthN);
						tdProrrateo(leftDays, daySelected, monthN, days, valorPlan, monthN);
						if ($("#left-day").is(":checked")) {
							$("#standarServiceFlag").val(0);					
							$("#tr-servicio").hide();
						} else {
							$("#tr-servicio").show();
							$("#standarServiceFlag").val(1);
					
						}
					}

					afiliacionDivItems("caso1"); 
				}

				if(daySelected==15){
					$("#standarServiceFlag").val(1);
					corte = 15;
				}	
				if (daySelected > 10 && daySelected < 20 && daySelected!=15) {
					corte = 15;
					$("#standby").val(1);
					if (daySelected > 10 && daySelected < 15) {						
						var lfdayc15 = 15 - daySelected;
						var serviceValueFlag = false;
						if ($("#left-day").is(":checked")) {
							var serviceValueFlag = true;
							$("#standarServiceFlag").val(1);					
						}
						else{
							$("#standarServiceFlag").val(1);					
						}
						$("#left-day").show();
						$("#left-day-text").show();
						$("#afiliacion-prorrateo").show();
						$("#left-day-text").html("Cobrar " + lfdayc15 + " días desde " + daySelected + " de " + monthN + " hasta 15 de " + monthN);
						tdProrrateo(lfdayc15, daySelected, monthN, 15, valorPlan, monthN, serviceValueFlag);
						$("#tr-servicio").show();
						afiliacionDivItems("caso2"); 
					}
					if (daySelected > 15 && daySelected < 20) {
						var lfdayc15 = leftDays + 15;
						var monthNc15 = monthName(parseInt(monthSelected) + 1);
						$("#left-day").show();
						$("#left-day-text").show();
						$("#afiliacion-prorrateo").show();
						$("#left-day-text").html("Cobrar " + lfdayc15 + " días desde " + daySelected + " de " + monthN + " hasta 15 de " + monthNc15);
						tdProrrateo(lfdayc15, daySelected, monthN, days, valorPlan, monthNc15);
						if ($("#left-day").is(":checked")) {
							$("#tr-servicio").hide();
							$("#standarServiceFlag").val(0);					
						} else {
							$("#tr-servicio").show();
							$("#standarServiceFlag").val(1);	
						}
						afiliacionDivItems("caso1"); 
					}
				}
				if(leftDays==0) $("#standarServiceFlag").val(1);
				if (daySelected >= 20 ) {
					corte = 1;
					$("#standby").val(2);
					var serviceValueFlag = false;
					if ($("#left-day").is(":checked")) {
						var serviceValueFlag = true;
						$("#standarServiceFlag").val(1);	
					}
					else{
						$("#standarServiceFlag").val(1);	
					}
					if( (days - daySelected) !=0 ){
						$("#left-day").show();
						$("#left-day-text").show();
						$("#left-day-text").html("Cobrar " + leftDays + " días desde " + daySelected + " de " + monthN + " hasta " + days + " de " + monthN);
					}
					
					if (monthSelected != 12)
						increasedMonth += 1;
					if (monthSelected == 12)
						increasedMonth = 1;
					tdProrrateo(leftDays, daySelected, monthN, days, valorPlan, monthN, serviceValueFlag);
					$("#tr-servicio").show();
					afiliacionDivItems("caso2"); 

				}
				console.log("corte vale:"+corte)
				console.log("Valor prorrateo:"+$("#valorProrrateo").val())
				console.log("Valor valorAdicionalServicio:"+$("#valorAdicionalServicio").val())
				$("#corte").val(corte);
				//$("#mes").val(increasedMonth);
			}

			function tdProrrateo(leftDays, daySelected, monthN, days, valorPlan, monthN2, serviceValueFlag) {
				$("#td-servicio-prorrateo").html(leftDays + " días desde " + daySelected + " de " + monthN + " a " + days + " de " + monthN2);
				var varlorDiasProrrateo = (parseInt(valorPlan) / days) * leftDays;
				var result = invoiceValues(varlorDiasProrrateo);
				var vAp = 0;
				var ServiceToPay = 0;
				var valorPLan = $("#valor-plan").val();
				var mergeItems = $("#mergeItems").val();
				$("#td-valorServicio-prorrateo").html("$" + result.baseValue);
				$("#td-importeServicio-prorrateo").html(result.percent + "% " + result.ivaValue);				
				vAp = parseInt($("#valorAfiliacion").val());
				if (serviceValueFlag) { //  only to date between   11-15 && 21-31
					if ($('#mergeItems').is(":checked")) {
						if ($("#left-day").is(":checked")) {
							ServiceToPay = vAp;
							$("#valorApagar").val(ServiceToPay);
							$("#td-total").html("$" + ServiceToPay);
							$("#td-subtotalServicio-prorrateo").html("$" + result.subtotalValue);
							//$("#valorAdicionalServicio").val(0);
							//$("#valorAdicionalServicioDescripcion").val("");
							$("#valorAdicionalServicio").val(result.subtotalValue);
							$("#tr-prorrateo").removeClass("table-danger"); 
							afiliacionTrView(ServiceToPay-valorPLan-result.subtotalValue);
						}
						else{
							ServiceToPay = vAp;
							$("#valorApagar").val(ServiceToPay);
							$("#td-total").html("$" + ServiceToPay);
							$("#td-subtotalServicio-prorrateo").html("$" + result.subtotalValue);
							$("#valorAdicionalServicio").val(result.subtotalValue);
							$("#valorProrrateo").val(result.subtotalValue);
							$("#tr-prorrateo").removeClass("table-danger");
							$("#tr-prorrateo").addClass("table-success");
							afiliacionTrView(ServiceToPay-valorPLan);

						}
					} 
					else {
						if ($("#left-day").is(":checked")) {
							ServiceToPay = vAp;
							$("#valorApagar").val(ServiceToPay);
							$("#td-total").html("$" + ServiceToPay);
							$("#td-subtotalServicio-prorrateo").html("$" + result.subtotalValue+ " <br><strong>Pendienete de Pago</strong>");
							$("#valorAdicionalServicio").val(result.subtotalValue);
							$("#tr-prorrateo").removeClass("table-success");
							$("#tr-prorrateo").addClass("table-danger");
							afiliacionTrView(vAp);
						}
						else{
							ServiceToPay = vAp;
							$("#valorApagar").val(ServiceToPay);
							$("#td-total").html("$" + ServiceToPay);
							$("#valorAdicionalServicio").val(0);
							$("#valorAdicionalServicioDescripcion").val("");
							
							
						}

					}
				}
				if (!serviceValueFlag) {
					if ($("#left-day").is(":checked")) {						
						if ($('#mergeItems').is(":checked")) {
							var total = vAp - result.subtotalValue;
							afiliacionTrView(total);
							$("#td-total").html("$" + vAp);
							$("#valorApagar").val(vAp);
							$("#tr-prorrateo").removeClass("table-danger");
							$("#td-subtotalServicio-prorrateo").html("$" + result.subtotalValue );
							$("#valorProrrateo").val(result.subtotalValue);
							$("#valorAdicionalServicio").val(0);
							$("#valorAdicionalServicioDescripcion").val("");
							afiliacionTrView(vAp-result.subtotalValue);
						} 
						else {
							$("#td-total").html("$"+vAp);
							$("#valorApagar").val(vAp);
							$("#td-subtotalServicio-prorrateo").html("$" + result.subtotalValue+ "<br><strong>Pendienete de Pago</strong>");
							$("#valorAdicionalServicio").val(result.subtotalValue);
							$("#valorProrrateo").val(result.subtotalValue);
							$("#tr-prorrateo").removeClass("table-success"); 
							$("#tr-prorrateo").addClass("table-danger");
							
						}
					} 
					else {
						
						if ($('#mergeItems').is(":checked")) {
							ServiceToPay = vAp;
							$("#valorAdicionalServicio").val(0);
							$("#valorAdicionalServicioDescripcion").val("");
							$("#valorProrrateo").val(0);
							$("#valorApagar").val(ServiceToPay);
							$("#td-total").html("$" + ServiceToPay);
							afiliacionTrView(vAp-valorPLan);
						}
						else{
							ServiceToPay = vAp;
							$("#valorAdicionalServicio").val(0);
							$("#valorAdicionalServicioDescripcion").val("");
							$("#valorProrrateo").val(0);
							$("#valorApagar").val(ServiceToPay);
							$("#td-total").html("$" + ServiceToPay);							
						}
					}
				}
			}

			function afiliacionTrView(total) {
				var percentValue = iva();
				var importeAfiliacion = parseInt(total * (percentValue / 100));
				var baseGravableAfiliacion = parseInt(total) - importeAfiliacion;
				var subtotalAfiliacion = total;
				$("#td-valorAfiliacion").html('$' + baseGravableAfiliacion);
				$("#td-importeAfiliacion").html(percentValue + "% $" + importeAfiliacion);
				$("#td-subtotalAfiliacion").html("$" + subtotalAfiliacion);
				$("#AfiliacionItemValue").val(subtotalAfiliacion);	
			}

			function daysInMonth(month, year) {
				if (parseInt(month) == 13)
					var newMonth = 1;
				else
					var newMonth = month;
				return new Date(year, newMonth, 0).getDate();
			}

			function validEmail(email) {
				if (email=="")return true;
				var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				return re.test(email);
			}

			function validDate(date) {
				var dateRegex = /^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)?\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/;
				return dateRegex.test(date);
			}

			function allLetter(inputtxt) {
				var letters = /^[A-Za-z ]+$/;
				if (inputtxt.match(letters)) {
					return true;
				} else {
					return false;
				}
			}

			function phonenumber(inputtxt) {
				var phoneno = /^\d{10}$/;
				if ((inputtxt.match(phoneno))) {
					return true;
				} else {
					return false;
				}
			}

			function allnumeric(inputtxt) {
				var numbers = /^[0-9]+$/;
				if (inputtxt.match(numbers)) {
					return true;
				} else {
					return false;
				}
			}

			function required(inputtx) {
				var rx = /[a-z1-9]/gi;
				if (inputtx.length == 0 || !inputtx.match(rx)) {
					return false;
				}
				return true;
				$("#valorApagar").val($("#valorApagar").val());
			}

			function monthName(month) {
				if (parseInt(month) == 13)
					var newMonth = 1;
				else
					var newMonth = month;
				var months = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
				return months[newMonth];
			}

			function invoiceValues(total) {
				var percentIva = iva();
				var ivaV = (parseInt(total) * percentIva) / 100;
				var base = parseInt(total - ivaV);
				var subtotal = parseInt(total);
				return {
					percent: percentIva,
					ivaValue: ivaV,
					baseValue: base,
					subtotalValue: subtotal
				};
			}

			function iva() { 
				var iva = 19;
				return iva;
			}
			function ValidateIPaddress(inputText)
			{
				var ipformat = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
				if(inputText!=null){
					if(inputText.match(ipformat))
					{					
						return true;
					}
					else
					{
						
						return false;					
					}
				}
				return false;
			}
			function array_diff (a1, a2) {
				var a = [], diff = [];
				for (var i = 0; i < a1.length; i++) {
					a[a1[i]] = true;
				}
				for (var i = 0; i < a2.length; i++) {
					if (a[a2[i]]) {
						delete a[a2[i]];
					} else {
						a[a2[i]] = true;
					}
				}
				for (var k in a) {
					diff.push(k);
				}
				return diff;
			}
			function ucwords(msj){
				var lsCadena = msj;
				return lsCadena.toLowerCase().replace(/\b[a-z]/g, function(letra) {
					return letra.toUpperCase();
					});;
			}	
			function selectedIpValidator(table){
				var rows_selected = table.column(0).checkboxes.selected();											
				$.each(rows_selected, function(index, rowId){
					if(rowId){
						console.log("el rowId:"+rowId);
						let ipstr= '#ip--'+rowId;
						let pstr='#p-'+rowId;
						let tr='#tr-'+rowId;
						let dsb='#dsb-'+rowId;
						let ip=$(ipstr).val();
						let id=rowId;
						if(ValidateIPaddress(ip)){
							$.ajax({
							type: 'post',
							url: '../ipupdater.php',
							data: {
								id: id,
								ip: ip
							},
							success: function(data) {
								if(data){
									let tr='#tr-'+id;
									if(data==1){
										$(ipstr).addClass('bg-success text-dark');	
										$(dsb).val('');
										$(pstr).html('');
										$(pstr).removeClass('border border-danger rounded');
										$(tr).removeClass('bg-warning');
										$(tr+' td:first-child + td ').removeClass('bg-warning');
										$(tr+' td:first-child input ').prop('disabled', false);
												
									}
									else{												
										alertify.error('ups '+data,10);
										$(dsb).val(id);
										$(ipstr).addClass('bg-danger text-dark');
										//$(tr+' td:first-child input ').prop('disabled', true);
										$(pstr).html(data);
										$(pstr).addClass('border border-danger rounded');
									}

								}
								else
									alertify.error('Error al actializar la ip!');								
							}
						});
						}
						else{
							$(dsb).val(rowId);			
							$(tr).addClass('bg-warning');
							$(tr+' + tr .child ').addClass('bg-info');
							$(tr+' td:first-child + td ').addClass('bg-warning');
							$(tr+' td:first-child input ').prop('disabled', true);
							
						}

					}
					
				});	
			}

		</script>

	</body>

	</html>