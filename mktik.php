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
	<div class="container-fluid px-0">		
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top   ">		
			<div class="container img-logo " >
				<img src="img/wisp.png">
				<!-- Nos sirve para agregar un logotipo al menu -->
				<a href="main.php" class="navbar-brand link-border">Wisdev</a>
				
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
					<div class="columna col-lg-7">
						
						<div class=" p-2 rounded nuevo_contenido border border-primary">
							<h3 class="titulo text-center">Area de suspensión de servicio a clientes.</h3>
							<!-- inicio de contenido de pagina -->
                            <div class="text-light bg-dark rounded p-2 text-justify">
                                <p>Aquí vamos a listar los clientes que están en mora y se da la posibilidad de filtrar por ciudad o corte de facturación.</p>
                                <p>Dejar abierta la posibilidad de nuevos criterios de búsqueda que se puedan necesitar.</p>
                                                         
                            </div>
                            <!-- fin de contenido de pagina -->	
                             
						</div>
					</div>

					<div class="columna col-lg-5">
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
</body>
</html>