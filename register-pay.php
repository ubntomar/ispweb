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
					<div class="columna col-lg-9">
						<div class="nuevo_contenido pb-2 mb-2 caja border-primary">
							<h5 class="my-3 pb-2 caja border-primary">Registrar pago</h5>
							<!-- inicio de contenido de pagina -->
							<form style="display: hidden" action="printable.php" method="POST" id="form">
							 	 <input type="hidden" id="idt" name="idt" value="0"/>
							 	 <input type="hidden" id="rpp" name="rpp" value="register-pay"/>
							</form>
							<table id="clientList" class="display compact table text-dark table-bordered table-responsive  table-hover ">
								<thead  class="bg-primary">
									<tr>
										<td>Nombre Titular</td>
										<td>Dirección</td>
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
										<td>Corte</td>
										<td>Cedula Titular</td>
										<td>Telefono</td>
										<td>Pay</td>
									</tr>
								</tfoot>
								<tbody >
									
										<?php 					
											$sql = "SELECT * FROM `afiliados` WHERE `afiliados`.`activo`=1 ORDER BY `afiliados`.`id`  ASC ";
											if ($result = $mysqli->query($sql)) {
												while ($row = $result->fetch_assoc()) {	
													$idCliente=$row["id"];
													$cedula=$row["cedula"];
													$telefono=$row["telefono"];
													if($row["eliminar"]==1){
														$statusText="Inactivo";
														$style="border-dark text-secundary ";	
													}
													else{
														$statusText="Activo";			
														$style="border-primary text-success ";	
													}	
													if($row["suspender"]==1){
														$statusText="Cortado";
														$style="border-info text-danger ";	
													}
													$textCedula=$cedula;
													if ($cedula==0){
														$textCedula="<input class=\"form-control cedula".$row["id"]." p-0\" type=\"text\" value=\"\" >";
														}
													else{
														$textCedula="<input class=\"form-control cedula".$row["id"]." p-0\" type=\"text\" value=\"$cedula\" id=\"-1\" 	 >";
													}	 	
													
													$textTelefono=$telefono;
													if ($telefono==""){
														$textTelefono="<input class=\"form-control telefono".$row["id"]." p-0 \" type=\"text\" value=\"\" >";
														}
													else{
														$textTelefono="<input class=\"form-control telefono".$row["id"]." p-0 \" type=\"text\" value=\"$telefono\" id=\"-1\"  >";
													}	

													$telefono=$row["telefono"];
													echo "<tr class=\"text-center  \">";				
													echo "<td> {$row["cliente"]}  {$row["apellido"]} <small class=\"px-1 border $style rounded \">$statusText</small></td>";
													echo "<td>".$row["direccion"]."</td>";
													echo "<td class=\" align-middle \">".$row["corte"]."</td>";
													echo "<td class=\" align-middle \">$textCedula</td>";
													echo "<td class=\" align-middle \">$textTelefono</td>";
													echo "<td class=\" align-middle \"><a href=\"#\" class=\"text-primary icon-client \" data-toggle=\"modal\" 	data-target=\"#payModal\" data-id=\"".$row["id"]."\"><i class=\"icon-money\"></i></a></td>";
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

	
	<script src="bower_components/jquery/dist/jquery.min.js"></script>
	<script src="bower_components/DataTables/media/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/Popper/popper.min.js" ></script>  
    <script src="bower_components/bootstrap/dist/js/bootstrap.js"></script> 
	<script src="bower_components/alertify/js/alertify.min.js"></script>
	<script src="bower_components/AutoFormatCurrency/simple.money.format.js"></script>
    <script>
		

		$(document).ready(function(){

			$(document).ready(function(){$('#clientList').DataTable();} );
			$('#payModal').on('show.bs.modal', function (e) {				
		        var rowid = $(e.relatedTarget).data('id');
		        console.log("------cedula:"+$(".cedula"+rowid).val()+"-----telefono:"+$(".telefono"+rowid).val());
		        var cedula=0;
		        if($.isNumeric($(".cedula"+rowid).val())){
		        	if ( ($(".cedula"+rowid).val()+"").match(/^\d+$/) ) {
   						console.log("cedula es valor o.k");
		        		cedula=$(".cedula"+rowid).val();
						}
		        	else{
		        		alertify.error('Cedula invalido, tiene puntos decimales!!');
		        	}
		        }
		        else{
		        	if($(".cedula"+rowid).val()=="")
	        			alertify.warning('Cedula  no sera actualizada!!');
		        	else
		        		alertify.error('Cedula valor invalido, no sera actualizada!!');
		        }
		        var telefono=0;
		        if($.isNumeric($(".telefono"+rowid).val())){
		        	if ( ($(".telefono"+rowid).val()+"").match(/^\d+$/) ) {
   						console.log("telefono es valor o.k");
		        		telefono=$(".telefono"+rowid).val();
						}
		        	else{
		        		alertify.error('telefono invalido, tiene puntos decimales!!');
		        	}
		        }
		        else{
		        	if($(".telefono"+rowid).val()=="")
	        			alertify.warning('Telefono  no sera actualizado!!');
		        	else
		        		alertify.error('Telefono valor invalido, no sera actualizada!!');
		        }		        
		        $.ajax({
		            type : 'post',
		            url : 'fetch_payModal.php', //Here you will fetch records 
		            data: {rowid:rowid,cedula:cedula,telefono:telefono} ,
		            success : function(data){
		            $('.fetched-data').html(data);//Show fetched data from database
		            }
		        });	
		      });  		
		});
	$(document).ajaxComplete(function(){
		
		$('.icon-client').click(function(){
				$("#paybutton").show();
				});	
    	$( "#tr-valor-abonar" ).hide();
    	var vp=$("#valor-pago").text();
		var intvp=vp.replace(/[^0-9]/gi, '');
		if(intvp==0){
			$("#paybutton").hide();
			$("#tr-chkb-abonar").hide();			
			}
    	$('#paybutton').click(function(){
    					
						alertify.prompt("Ingreso de Efectivo","Efectivo: ", "",
				  		function(evt, value ){
				  			var pass=0;
				  			var cambio=0;
				  			if ($('#checkbox-abonar').is(":checked")){
				  				var vaa=$("#valor-abonar").val();
				  				if (value >= parseInt(vaa)){
				  					pass=1;
				  					cambio=value-parseInt(vaa);				  				
				  				}
				  				else
				  					pass=0;
				  				console.log('pass1:'+pass);
				  			}	
				  			else{
				  				var vap=$("#valor-pago").text();
					  			var intvap=vap.replace(/[^0-9]/gi, '');
					  			if (value >= parseInt(intvap)){
				  					pass=1;
				  					cambio=value-parseInt(intvap);				  				
					  			}
				  				else
				  					pass=0;
				  				console.log('pass2:'+pass);
				  			}
				  			console.log('pass='+pass+'efectivo'+value+'preparado para abonar:'+vaa+' cambio:'+cambio+'valor a pagar:'+intvap);
				  			if(pass==1){
				  				if ($('#checkbox-abonar').is(":checked")){
								  	console.log('Preparado para abonar:$'+$("#valor-abonar").val());
									var idc=$("#id-client").text();
					  				var vap=-1;	
					  				var vaa=$("#valor-abonar").val();
					  				console.log('idc='+idc+'vap='+vap+'vaa='+vaa);
					  				$.ajax({
							            type : 'post',
							            url : 'fetch_payFact.php', 
							            data: {idc:idc, vap:vap, vaa:vaa, vre:value, cam:cambio} ,
							            success : function(data){							            	
							            	var arr = data.split('/');
							            	var msj= arr[0];
							            	var cod= arr[1];
							            	//alertify.success("data:"+data+"-msj:"+msj+"-code:"+cod);
							            	alertify.success(msj);	
							            	$("#idt").val(cod);
							            	$("#form").submit();					            	
							            }
							        	});		  
								}	
				  				else{
					  				console.log('Preparado para pagar todo...');
					  				var idc=$("#id-client").text();
					  				var vap=$("#valor-pago").text();
					  				var intvap=vap.replace(/[^0-9]/gi, '');
					  				var vaa=$("#valor-abonar").val();
					  				console.log('idc='+idc+'vap='+intvap+'vaa='+vaa);
					  				$.ajax({
							            type : 'post',
							            url : 'fetch_payFact.php', 
							            data: {idc:idc, vap:intvap, vaa:vaa, vre:value, cam:cambio} ,
							            success : function(data){							            	
							            	var arr = data.split('/');
							            	var msj= arr[0];
							            	var cod= arr[1];
							            	//alertify.success("data:"+data+"-msj:"+msj+"-code:"+cod);
							            	alertify.success(msj);	
							            	$("#idt").val(cod);
							            	$("#form").submit();
							            }	
							        	});		
				  				}
				  			}					    			
				    		else
				    			alertify.error('Efectivo invalido!');
				  		},
				  		function(){
				 			alertify.error('Error,problema con valor de efectivo!');
				 			}); 
				 		;			
				$('#payModal').modal('hide');
		});
		console.log("linea antes de cancelar")	
		$('#cancelbutton').click(function(){				
			$('#payModal').modal('hide');
			 alertify.error('Operacion Cancelada').dismissOthers(); 
		});	
		console.log("linea despues de cancelar")	
    	$("#checkbox-abonar").click(function(){		    	
        		$("#td-pago").toggleClass("texto-subrayado");
        		if($('#tr-valor-abonar:visible').length)
        			$('#tr-valor-abonar').hide();
    			else
        			$('#tr-valor-abonar').show(); 
    			});
    	$("#valor-abonar").keyup(function(){
    		this.value = this.value.replace(/[^0-9\.]/g,''); 
    		$('.money').text($("#valor-abonar").val());
    		$('.money').simpleMoneyFormat();
    		var vp=$("#valor-pago").text();
			var intvp=vp.replace(/[^0-9]/gi, ''); 
			if (parseInt(this.value)>parseInt(intvp)) {
				this.value='';
				$('.money').text('');
			}
    		});	
		})	
	</script>
</body>
</html>