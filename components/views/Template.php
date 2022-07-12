<?php
class Template{
    public function  __construct(){

    }
    public function nav($sessionName){ 
        $content='
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top   ">
			<div class="container img-logo ">
				<!-- <img src="img/wisp.png"> -->
				<!-- Nos sirve para agregar un logotipo al menu -->
				<a href="main.php" class="navbar-brand">Netmx</a>

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
								<a class="nav-link disabled text-white "><i class="icon-user"></i>'.$$sessionName.' </a>
							</li>
						</ul>
					</div>

				</div>
			</div>
		</nav>

        ';
        return $content;
    }
    public function head(){
        $content='
        <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        </head>
        ';
        return $content; 
    }
    public function sideBar(){
        $content='
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
        ';
        return $content;
    }
    public function aside(){
        $content='
        <div class="columna col-lg-3">
                        <div class="widget estadisticas">
                            <h3 class="titulo">Estadisticas</h3>
                            <div class="contenedor d-flex flex-wrap">
                                <div class="caja">
                                    <h3>0,000</h3>
                                    <p>Pagos</p>
                                </div>
                                <div class="caja">
                                    <h3>0000</h3>
                                    <p>Clientes</p>
                                </div>
                                <div class="caja">
                                    <h3>$0.000.000</h3>
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
                                            <!-- <img src="img/persona1.jpg" width="100" alt=""> -->
                                        </a>
                                    </div>


                                    <div class="texto">
                                        <a href="#">Jhon Doe</a>
                                        <p>en <a href="#">Mi primer entrada</a></p>
                                        <p class="texto-comentario">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis natus
                                            ex inventore provident modi id distinctio non minus, magni quia officiis,
                                            vel debitis doloremque ratione, consequuntur omnis hic voluptatem
                                            asperiores?
                                        </p>
                                    </div>
                                    <div class="botones d-flex justify-content-start flex-wrap w-100">
                                        <button class="aprobar"><i class="icono icon-ok"></i>Aprobar</button>
                                        <button class="eliminar"><i class="icono icon-cancel"></i>Eliminar</button>
                                        <button class="bloquear"><i class="icono icon-flag"></i>Bloquear
                                            Usuario</button>
                                    </div>
                                </div>

                                <div class="comentario d-flex flex-wrap">
                                    <div class="foto">
                                        <a href="#">
                                            <!-- <img src="img/persona2.jpg" width="100" alt=""> -->
                                        </a>
                                    </div>
                                    <div class="texto">
                                        <a href="#">Jhon Doe</a>
                                        <p>en <a href="#">Mi primer entrada</a></p>
                                        <p class="texto-comentario">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis natus
                                            ex inventore provident modi id distinctio non minus, magni quia officiis,
                                            vel debitis doloremque ratione, consequuntur omnis hic voluptatem
                                            asperiores?
                                        </p>
                                    </div>
                                    <div class="botones d-flex justify-content-start flex-wrap w-100">
                                        <button class="aprobar"><i class="icono icon-ok"></i>Aprobar</button>
                                        <button class="eliminar"><i class="icono icon-cancel"></i>Eliminar</button>
                                        <button class="bloquear"><i class="icono icon-flag"></i>Bloquear
                                            Usuario</button>
                                    </div>
                                </div>

                                <div class="comentario d-flex flex-wrap">
                                    <div class="foto">
                                        <a href="#">
                                            <!-- <img src="img/persona3.jpg" width="100" alt=""> -->
                                        </a>
                                    </div>
                                    <div class="texto">
                                        <a href="#">Jhon Doe</a>
                                        <p>en <a href="#">Mi primer entrada</a></p>
                                        <p class="texto-comentario">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis natus
                                            ex inventore provident modi id distinctio non minus, magni quia officiis,
                                            vel debitis doloremque ratione, consequuntur omnis hic voluptatem
                                            asperiores?
                                        </p>
                                    </div>
                                    <div class="botones d-flex justify-content-start flex-wrap w-100">
                                        <button class="aprobar"><i class="icono icon-ok"></i>Aprobar</button>
                                        <button class="eliminar"><i class="icono icon-cancel"></i>Eliminar</button>
                                        <button class="bloquear"><i class="icono icon-flag"></i>Bloquear
                                            Usuario</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
        
        ';
        return $content;
    }
    public function footer(){     
        $content='
        <div class="container-fluid 	">
            <div class="row">
                <div class="col text-light bg-dark py-2 d-flex justify-content-center footer-text">
                    <p>Copyright ©2014-2017 Netmx-Administrador ISP - <small>All Rights Reserved.</small></p>

                </div>
            </div>
            <div class="row">
                <div class="col text-light bg-dark  d-flex justify-content-center">

                    <p><i class="icon-facebook-official"></i><i class="icon-twitter-squared"></i></p>
                </div>
            </div>
        </div>
        ';
        return $content;
    }
}



?>