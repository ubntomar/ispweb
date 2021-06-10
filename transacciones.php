<?php 
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		//header('Location: login/index.php');
		//exit;
		}
else{
    $user = $_SESSION['username'];
    $name = $_SESSION['name'];
    $lastName = $_SESSION['lastName'];
    $role = $_SESSION['role'];
    $jurisdiccion = $_SESSION['jurisdiccion'];
    $empresa = $_SESSION['empresa'];
    $sharedCode = $_SESSION['sharedCode'];
}
if($_SESSION['role']=='tecnico'){
	header('Location: tick.php');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Isp Experts-Administrador ISP</title>

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

<body>
    <?php 
		include("login/db.php");
		$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
		if ($mysqli->connect_errno) {
	    	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
			}	
		mysqli_set_charset($mysqli,"utf8");
		date_default_timezone_set('America/Bogota');
		$mons = array(1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre");
		$month = date('n');
		$month_name = $mons[$month];
		$year=date("Y");
		$today= date("j");
		$hour=date("H:i");
		$last_day=date("t", strtotime('now'));
	?>
    <div class="container-fluid px-0">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top   ">
            <div class="container img-logo ">
                
                <!-- Nos sirve para agregar un logotipo al menu -->
                <a href="main.php" class="navbar-brand ">Wisdev</a>

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
                            <a href="register-pay.php" class="nav-link "><i class="icon-money"></i>Registrar Pago</a>
                        </li>
                        <li class="nav-item  ">
                            <a href="transacciones.php" class="nav-link link-border "><i
                                    class="icon-print "></i>Transacciones</a>
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
                                        class="icon-user"></i><?php echo "Hola ".$_SESSION['name']; ?></a>
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
                <div class="row" id="app">
                    <div class="columna col-lg-7">

                        <div class="nuevo_contenido pb-2 mb-2 caja border-primary">
                            <h5 class="my-3 pb-2 caja border-primary">Transacciones HOY <?php  echo $today." ".$month_name." de ".$year;echo "  -Hora:  
							".$hour; ?>.</h5>
                            <!-- inicio de contenido de pagina -->
                            <p class="mt-3 ">Seleccione Cajero.</p>
                            <div class="my-3">
                                <select class="custom-select " id="ventas-dia">
                                    <option value="todos">Todos</option>
                                    <?php
							if($_GET['cajero']){
								$caj= mysqli_real_escape_string($mysqli, $_REQUEST['cajero']);
								if($caj=="todos"){
									$sqlcaj="";
								}
								else{
									$sqlcaj="AND `transacciones`.`cajero`='$caj' ";	
								}								
							}	
							$sqlcajero="SELECT DISTINCT `transacciones`.`cajero` FROM `transacciones` ";
							echo $sqlcajero;									
							$i=0;
							if ($result = $mysqli->query($sqlcajero)) {
								while ($row = $result->fetch_assoc()) {
									$i+=1;
									$cajero=$row["cajero"];
									$caje[$i]=$cajero;										
									if($caj==$cajero)
										$tx="selected";
									else
										$tx="";

									echo"<option value=\"$cajero\"  $tx >$cajero</option>";
								}
							}																						  
											echo"</select>";	
		    							echo"</div>";
		    				  
		    				?>
                                    <table id="clientList2" class="display compact dataTable_Morosos cell-border "
                                        cellspacing="0" width="100%">
                                        <thead class="bg-success">
                                            <tr>
                                                <td>Nombre</td>
                                                <td>Dirección</td>
                                                <td>Pago</td>
                                                <td>Fecha</td>
                                                <td>Ticket</td>
                                                <td>Recibo</td>
                                            </tr>
                                        </thead>
                                        <tfoot class="bg-success">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                        <tbody>

                                            <?php 					
											$sql = "SELECT * FROM `transacciones` WHERE MONTH(fecha) = $month AND YEAR(fecha) = $year AND DAY(fecha) = $today $sqlcaj ORDER BY `transacciones`.`idtransaccion` DESC ";
											if ($result = $mysqli->query($sql)) {
												$recaudo=0;
												$registros=$result->num_rows;
												$cnt=0;
												$sum=0;
												while ($row = $result->fetch_assoc()) {
													$cnt+=1;
													$idtransaccion=$row["idtransaccion"];
													$idafi=$row["id-cliente"];
													$userNameCajero=$row["cajero"];
													$sqlafi="SELECT * FROM `afiliados` WHERE `id` = $idafi  ";
													$resultafi = $mysqli->query($sqlafi);
                                                    $rowafi = $resultafi->fetch_assoc();
													$recaudo+=$row["valor-a-pagar"];
													if($row["descontar"]!=0 ){
                                                        $tclass="class= 'text-info' ";
														$descontar=$row["descontar"];
														$subtotalDesc=$row["valor-a-pagar"]-$row["descontar"];
														$descHtml="<p class=' text-danger p-0 m-0'>Descuento:</p><p class='text-danger p-0 m-0 border-bottom border-primary' ><strong>$descontar</strong></p><p class=' p-0 m-0' >Subtotal: <strong>$subtotalDesc</strong></p>";
														$sum-=$descontar;
													}
													else{
                                                        $tclass="class= '' ";
														$descHtml="";
													}
                                                    $idArea= $rowafi["id_client_area"];
                                                    $sq="SELECT * FROM `items_grupo_area` WHERE `id-area`=$idArea";
                                                    $resultIdArea=$mysqli->query($sq);
                                                    $rw=$resultIdArea->fetch_assoc();
                                                    $idGrupo=$rw["id-grupo"];
                                                    $resultIdArea->free();
                                                    $sq2="SELECT * FROM `jurisdicciones` WHERE `id-grupo-area`=$idGrupo";
                                                    $resultIdArea=$mysqli->query($sq2);
                                                    $rw2=$resultIdArea->fetch_assoc();
                                                    $idjurisdiccion=$rw2["id"];
                                                    $resultIdArea->free();

                                                    $sql="SELECT * FROM `users` WHERE `users`.`username` LIKE '".$userNameCajero."' ";
                                                    if($resultC=$mysqli->query($sql)){
                                                        $rowr=$resultC->fetch_assoc();
                                                        $shared=$rowr["shared-code"];
                                                        $resultC->free();
                                                    }
                                                    if(  ($jurisdiccion==$idjurisdiccion) && ($shared==$sharedCode) ){
                                                        echo "<tr class=\"text-center  \">";				
                                                        echo "<td>".$rowafi["cliente"]." ".$rowafi["apellido"]."-{$row["idtransaccion"]}</td>";
                                                        echo "<td>".$rowafi["direccion"]."</td>";
                                                        echo "<td><strong $tclass >{$row["valor-a-pagar"]}</strong>$descHtml</td>";
                                                        echo "<td class=\" align-middle \"><small>".$row["fecha"]." ".$row["hora"]."</small></td>";
                                                        echo "<td class=\" align-middle \"><a href=\"printable.php?idt=$idtransaccion&rpp=0\" class=\"text-success icon-client \" ><i class=\" icon-print  \"></i></a></td>";
                                                        echo "<td class=\" align-middle \"><a href=\"recibos.php?idc=$idafi&rpp=0\" class=\"text-info icon-client \" target=\"_blank\" ><i class=\" icon-print  \"></i></a></td>";
    
                                                        echo "</tr>";
                                                        $sum+=$row["valor-a-pagar"];	
                                                        if($cnt==$registros){
                                                            $formatSum=number_format($sum);
                                                            echo "<tr class=\"text-center  \">";				
                                                            echo "<td></td>";
                                                            echo "<td class=\" text-right \">Total:</td>";
                                                            echo "<td><strong>$$formatSum</strong></td>";
                                                            echo "<td></td>";
                                                            echo "<td></td>";
                                                            echo "<td></td>";
    
                                                            echo "</tr>";
                                                        }		
                                                    }
												}
											    	$result->free();
												}
										?>
                                            <!-- Modal -->


                                        </tbody>
                                    </table>
                                    <!-- fin de contenido de pagina -->
                            </div>

                            <div class="nuevo_contenido pb-2 mb-2 caja border-primary">
                                <hr>
                                <hr>
                                <h5 class="my-3 pb-2 caja border-primary">Transacciones
                                    <?php  echo $month_name." de ".$year; ?>.</h5>
                                <!-- inicio de contenido de pagina -->
                                <p class="mt-3 ">Seleccione Cajero.</p>
                                <div class="form-group px-1 border border-success rounded mx-1 my-3">
                                    <select class="form-control" id="transacciones-mes">
                                        <option value="todos" selected>Todos</option>
                                        <?php
								$sqlcajero="SELECT DISTINCT `transacciones`.`cajero` FROM `transacciones` ";
								if ($result = $mysqli->query($sqlcajero)) {
									while ($row = $result->fetch_assoc()) {
										$cajero=$row["cajero"];
										echo"<option value=\"$cajero\">$cajero</option>";
									}
								}
								?>
                                    </select>
                                </div>
                                <div class="form-group px-1 border border-success rounded mx-1 my-3">
                                    <label for="">Desde </label>
                                    <input type="text" class="form-control  " id="from-month-day" aria-describedby=""
                                        value=" <?php echo"$year/$month/01";  ?> ">
                                    <small id="" class="form-text text-muted">Desde el día ...</small>
                                </div>
                                <div class="form-group px-1 border border-success rounded mx-1 my-3">
                                    <label for="">Hasta </label>
                                    <input type="text" class="form-control " id="to-month-day" aria-describedby=""
                                        value="  <?php echo"$year/$month/$today";  ?>  ">
                                    <small id="" class="form-text text-muted">Hasta el día...</small>
                                </div>
                                <div>
                                    <button type="text" class="btn btn-primary mb-2" id="btn_enviar">
                                        <span class=" spinner-border-sm" role="status" aria-hidden="true"
                                            id="spinner-enviar"></span>
                                        Enviar
                                    </button>
                                </div>

                                <div id="table-month-content">

                                </div>

                                <!-- fin de contenido de pagina -->
                            </div>



                        </div>

                        <?php 					
                            $sql = "SELECT * FROM `transacciones` WHERE MONTH(fecha) = $month AND YEAR(fecha) = $year  ORDER BY `transacciones`.`idtransaccion` DESC ";
                            if ($result = $mysqli->query($sql)) {
                                $recaudo=0;
                                $descuento=0;
                                $sumDescuento=0;
                                $contDescuento=0;
                                $registros=$result->num_rows;
                                $sumAfiliacion=0;
                                $sumRecaudoMensualidad=0;
                                $cnt=0;
                                $sum=0;
                                $cajeroArray=array();
                                while ($row = $result->fetch_assoc()) {
                                    $cnt+=1;
                                    $idtransaccion=$row["idtransaccion"];
                                    $idafi=$row["id-cliente"];												
                                    $descuento=$row["descontar"];
                                    $cajeroName=$row["cajero"]; 
                                    $cajeroArray+=array($cajeroName=>$idafi);
                                    if ($descuento!=0){
                                        $registros-=1;
                                        $contDescuento+=1;
                                        $sumDescuento+=$descuento;
                                    } 
                                    else{
                                        $registros-=0;
                                    }
                                    $recaudo+=$row["valor-a-pagar"];
                                    $recaudo-=$descuento;
                                    $descripcion=$row["descripcion"];
                                    if (strpos($descripcion, 'Afilia') !== false) {
                                        $sumAfiliacion+=$row["valor-recibido"];
                                    }
                                }
                                $sumRecaudoMensualidad=$recaudo-$sumAfiliacion;
                                $distinctCajeros=array_unique($cajeroArray);
                            }
                            $result->free();
						?>
                        <div class="columna col-lg-5">
                            <div class="widget estadisticas">
                                <div v-if="adminBox">
                                    <h3 class="titulo">Transacciones <?=$month_name;?> </h3>
                                    <div class="contenedor d-flex flex-wrap">
                                        <div class="caja">
                                            <p>Total pagos</p>
                                            <h3><?=$registros;?> </h3>
                                            <div class="caja border border-info">
                                                <h6>+<?=$contDescuento;?></h6>
                                                <p>Pagos</p>
                                                <h6>$<?=number_format($sumDescuento);?></h6>
                                                <p>Descontado</p>
                                            </div>
                                        </div>
                                        <div class="caja">
                                            <p><i class="icon-money"></i>Total ingresos</p>
                                            <h3>$<?=number_format($recaudo);?></h3>
                                            <div class="caja border border-info">
                                                <h6>$<?=number_format($sumAfiliacion);?></h6>
                                                <p>Afiliaciones</p>
                                                <h6>$<?=number_format($sumRecaudoMensualidad);?></h6>
                                                <p>Mensualidad</p>
                                            </div>
                                        </div>

                                        <?php
                                    foreach ($distinctCajeros as $name=>$id){
                                        $sql = "SELECT * FROM `transacciones` WHERE MONTH(fecha) = $month AND YEAR(fecha) = $year AND `cajero` LIKE '$name'  ORDER BY `transacciones`.`idtransaccion`  ";
                                        if ($result = $mysqli->query($sql)){
                                            $numRows = $result->num_rows;
                                            $recaudo=0;
                                            $sumDescuento=0;
                                            $sumAfiliacion=0;
                                            $sumRecaudoMensualidad=0;
                                            while ($row = $result->fetch_assoc()) {
                                                $descuento=$row["descontar"];
                                                $recaudo+=$row["valor-a-pagar"];
                                                $recaudo-=$descuento;
                                                if ($descuento!=0){
                                                    $sumDescuento+=$descuento;
                                                    $numRows-=1;
                                                }
                                                $descripcion=$row["descripcion"];
                                                if (strpos($descripcion, 'Afilia') !== false) {
                                                    $sumAfiliacion+=$row["valor-recibido"];
                                                }
                                            }
                                            $sumRecaudoMensualidad=$recaudo-$sumAfiliacion;
                                        }
                                        $result->free();   
                                        echo "  <div class=\"caja\">
                                                <p><i class=\"icon-user\"></i>$name</p>
                                                <h3>$".number_format($recaudo)."</h3> 
                                                <div class=\"caja border border-info\">
                                                    <h6>$".number_format($sumAfiliacion)."</h6> 
                                                    <p>Afiliaciones</p>
                                                    <h6>$".number_format($sumRecaudoMensualidad)."</h6>
                                                    <p>Mensualidad</p>
                                                    <h6>$numRows</h6>
                                                    <p>Pagos</p>
                                                    <h6>$".number_format($sumDescuento)."</h6>
                                                    <p>Descontado</p>
                                                </div>
                                                </div>";
                                    }
                                    ?>
                                    </div>
                                </div>

                            </div>

                            <div class="widget estadisticas">
                                <h3 class="titulo">Balance Hoy </h3>
                                <div class="contenedor d-flex flex-wrap">
                                    <div class="caja">
                                        <?php
									echo "<table class=\"table text-center \">
											  <thead class=\" text-success \">
											    <tr>
											      <td><h3>Cajero</h3></th>
											      <td><h3>Recaudo</h3></th>
											    </tr>
											  </thead>
											  <tbody>";
											for( $x=1; $x<=count($caje); $x++ ){
												$sqltotCaj="SELECT  SUM(`valor-a-pagar`-`descontar`) AS subtotal FROM `redesagi_facturacion`.`transacciones` WHERE `redesagi_facturacion`.`transacciones`.`cajero` LIKE  '".$caje[$x]."' AND MONTH(fecha) = $month AND YEAR(fecha) = $year AND DAY(fecha) = $today ";
												//echo $sqltotCaj;
												if ($result = $mysqli->query($sqltotCaj)){
													$rowcj = $result->fetch_assoc();
                                                    $subt=$rowcj["subtotal"];
                                                    $result->free();							
                                                } 
                                                $sql="SELECT * FROM `users` WHERE `users`.`username` LIKE '".$caje[$x]."' ";
                                                if($result=$mysqli->query($sql)){
                                                    $row=$result->fetch_assoc();
                                                    $shared=$row["shared-code"];
                                                    $result->free();
                                                }
                                                if ($shared==$sharedCode)
									                echo "  <tr>
                                                                <td>".$caje[$x]."</td>
                                                                <td>$".number_format($subt)."</td>							      
                                                             </tr>";
											}	  	
											

									echo "  </tbody>
										</table>";
								?>
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
                                                Lorem ipsum dolor sit amet, cossdnsectetur adipisicing elit. Blanditiis
                                                natus ex
                                                inventore provident modi id distinctio non minus, magni quia officiis,
                                                vel debitis
                                                doloremque ratione, consequuntur omnis hic voluptatem asperiores?
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
                                                <img src="img/persona2.jpg" width="100" alt="">
                                            </a>
                                        </div>
                                        <div class="texto">
                                            <a href="#">Jhon Doe</a>
                                            <p>en <a href="#">Mi primer entrada</a></p>
                                            <p class="texto-comentario">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis
                                                natus ex
                                                inventore provident modi id distinctio non minus, magni quia officiis,
                                                vel debitis
                                                doloremque ratione, consequuntur omnis hic voluptatem asperiores?
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
                                                <img src="img/persona3.jpg" width="100" alt=""> 
                                            </a>
                                        </div>
                                        <div class="texto">
                                            <a href="#">Jhon Doe</a>
                                            <p>en <a href="#">Mi primer entrada</a></p> 
                                            <p class="texto-comentario">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis
                                                natus ex
                                                inventore provident modi id distinctio non minus, magni quia officiis,
                                                vel debitis
                                                doloremque ratione, consequuntur omnis hic voluptatem asperiores?
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


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js">
    </script>
    <!-- <script src="bower_components/Popper/popper.min.js"></script> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="bower_components/alertify/js/alertify.min.js"></script>
    <script src="bower_components/AutoFormatCurrency/simple.money.format.js"></script>
    <script src="js/dataTables.checkboxes.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $('#ventas-dia').change(function() {
            console.log($(this).val()); // $(this).val() will work here
            window.location.href = 'transacciones.php?cajero=' + $(this).val();
        });
        $('#btn_enviar').on('click', function() {
            $('#spinner-enviar').addClass('spinner-border');
            var cajero = $('#transacciones-mes').val();
            var from = $('#from-month-day').val();
            var to = $('#to-month-day').val();
            console.log(cajero + ":" + from + ":" + to);
            $.ajax({
                type: 'post',
                url: 'transacciones_mes.php',
                data: {
                    cajero: cajero,
                    from: from,
                    to: to
                },
                success: function(data) {
                    $("#table-month-content").html(data);
                    var Table_transacciones_month = $('#clientList').DataTable({
                        "responsive": true,
                        "paging": true,
                        "searching": true,
                        "info": true
                    });
                    Table_transacciones_month.order([3, 'desc']);
                    Table_transacciones_month.draw();
                    $('#spinner-enviar').removeClass('spinner-border');
                }
            });
        });
        var Table_transacciones_today = $('#clientList2').DataTable({
            "responsive": true,
            "paging": true,
            "searching": true,
            "info": true
        });
        Table_transacciones_today.order([3, 'desc']);
        Table_transacciones_today.draw();
    });
    var app = new Vue({
        el: '#app',
        data: {
            message: 'Hello Vue!',
            adminBox: <?php echo ($role=="admin"?"true":"false"); ?>
        }

    })
    </script>
</body>

</html>