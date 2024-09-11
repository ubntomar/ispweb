<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
		header('Location: login/index.php');
		exit;
	} else {
	$user = $_SESSION['username'];
	$name = $_SESSION['name'];
	$lastName = $_SESSION['lastName'];
	$role = $_SESSION['role'];
	$jurisdiccion = $_SESSION['jurisdiccion'];
	$empresa = $_SESSION['empresa'];
}

function navbar(){
    $content="
        <nav class=\"navbar navbar-expand-lg navbar-dark bg-dark fixed-top   \">
			<div class=\"container img-logo \">
				<!-- <img src=\"img/wisp.png\"> -->
				<!-- Nos sirve para agregar un logotipo al menu -->
				<a href=\"main.php\" class=\"navbar-brand\">FastNet</a>

				<!-- Nos permite usar el componente collapse para dispositivos moviles -->
				<button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbar\" aria-controls=\"navbar\" aria-expanded=\"false\" aria-label=\"Menu de Navegacion\">
					<span class=\"navbar-toggler-icon\"></span>
				</button>

				<div class=\"collapse navbar-collapse \" id=\"navbar\">
					<ul class=\"navbar-nav  navclass \">
						<li class=\"nav-item \">
							<a href=\"#\" class=\"nav-link\"><i class=\"icon-money\"></i>Cierre de caja <span class=\"sr-only\">(Actual)</span></a>
						</li>
						<li class=\"nav-item  \">
							<a href=\"register-pay.php \" class=\"nav-link link-border \"><i class=\"icon-money \"></i>Registrar Pago</a>
						</li>
						<li class=\"nav-item  \">
							<a href=\"transacciones.php\" class=\"nav-link  \"><i class=\"icon-print \"></i>Transacciones</a>
						</li>
						<li class=\"nav-item\">
							<a href=\"#\" class=\"nav-link\"><i class=\"icon-mail\"></i>Contacto</a>
						</li>
						<li class=\"nav-item\">
							<a href=\"reclist.php\" class=\"nav-link \"><i class=\"icon-money\"></i>Formato Recibo</a>
						</li>
					</ul>
					<div class=\" ml-auto  \">
						<ul class=\"nav navbar-nav   \">
							<li class=\"nav-item \">
								<a class=\"nav-link disabled text-white \"><i class=\"icon-user\"></i><?php echo \"Hola \" . $_SESSION['name']; ?></a>
							</li>
						</ul>
					</div>

				</div>
			</div>
		</nav>
    ";


    return $content;
}




?>