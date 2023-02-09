<?php 
session_start();


if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: ../login/index.php');
		exit;
		}
else    {
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
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>DevXm-Administrador ISP</title>
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
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
	?>
    <div class="container-fluid px-0">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top   ">
            <div class="container img-logo ">
                <img src="img/wisp.png">
                <!-- Nos sirve para agregar un logotipo al menu -->
                <a href="main.php" class="navbar-brand link-border">DevXm</a>

                <!-- Nos permite usar el componente collapse para dispositivos moviles -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar"
                    aria-controls="navbar" aria-expanded="false" aria-label="Menu de Navegacion">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse " id="navbar">
                    <ul class="navbar-nav  navclass ">
                        <li class="nav-item ">
                            <a href="#" class="nav-link"><i class="icon-money"></i>Cierre de caja <span
                                    class="sr-only">(Actual)</span></a>
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
                                <a class="nav-link disabled text-white "><i
                                        class="icon-user"></i><?php echo "Hola ".$_SESSION['username']; ?></a>
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
                    <div class="columna col-12 col-sm-6   col-md-6	 col-lg-11  ">

                        <div class="nuevo_contenido pb-2 mb-2 caja border-primary">
                            <h5 class="my-3 pb-2 caja border-primary">Recibos este mes.</h5>
                            <!-- inicio de contenido de pagina -->

                            <table id="clientList"
                                class="display compact table text-dark table-bordered table-responsive  table-hover ">
                                <thead class="bg-primary">
                                    <tr>
                                        <td>Entrega</td>
                                        <td>Nombre</td>
                                        <td>Apellido</td>
                                        <td>Dirección</td>
                                        <td>Corte</td>
                                        <td><i class=" icon-print  "></i></td>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <td>Entrega</td>
                                        <td>Nombre</td>
                                        <td>Apellido</td>
                                        <td>Dirección</td>
                                        <td>Corte</td>
                                        <td><i class=" icon-print  "></i></td>
                                    </tr>
                                </tfoot>
                                <tbody>

                                    <?php 					
											
												$sql = "SELECT * FROM afiliados  WHERE `mesenmora`!= -1 AND `activo`=1 AND `suspender`=0 ORDER BY orden_reparto DESC";
												if ($result = $mysqli->query($sql)) {
													while ($row = $result->fetch_assoc()) {	
													$idc=$row["id"];				
													echo "<tr class=\"text-center  \">";
													echo "<td>".$row["orden_reparto"]."</td>";				
													echo "<td>".$row["cliente"]."</td>";
													echo "<td>".$row["apellido"]."</td>";
													echo "<td>".$row["direccion"]."</td>";
													echo "<td class=\" align-middle \">".$row["corte"]."</td>";
													echo "<td class=\" align-middle \"><a  target=\"_blank\" href=\"recibos.php?idc=$idc&rpp=0\" class=\"text-primary icon-client myprint \" id=\"$idc\" ><i class=\" icon-print  \"></i></a></td>";
													echo "</tr>";		
													}
											    	
                                                $result->free();
												}
														
												
										?>


                                </tbody>
                            </table>
                            <!-- fin de contenido de pagina -->
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <div class="container-fluid 	">
        <div class="row">
            <div class="col text-light bg-dark py-2 d-flex justify-content-center footer-text">
                <p>Copyright ©2014-2017 DevXm-Administrador ISP - <small>All Rights Reserved.</small></p>

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
    <script src="../bower_components/Popper/popper.min.js"></script>
    <script src="../bower_components/bootstrap/dist/js/bootstrap.js"></script>
    <script src="../bower_components/alertify/js/alertify.min.js"></script>
    <script src="../bower_components/AutoFormatCurrency/simple.money.format.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $(document).ready(function() {
            $('#clientList').DataTable();
        });
        $('.myprint').click(function() {
            //$(this).toggleClass("text-danger");
            var x = $(this).attr('id');
            $("#" + x).addClass("text-danger");
        });
        var table = $('#clientList').DataTable();
        table.order([0, 'desc']);
        table.draw();


    });
    </script>

</body>

</html>