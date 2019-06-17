<!DOCTYPE html>
<html lang="en">
<head>   
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">	   
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css"> 
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css"> 
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
    <div class="container-fluid border border-primary">
		<table id="table_active_client"  class="display responsive nowrap" cellspacing="0" width="100%">
            <thead  class="bg-primary">
            <tr>
                <td>cod</td>
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
    </div> 
    <script  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
    <script src="bower_components/Popper/popper.min.js" ></script>  
    <script src="bower_components/bootstrap/dist/js/bootstrap.js"></script> 
	<script src="bower_components/alertify/js/alertify.min.js"></script>
	<script src="bower_components/AutoFormatCurrency/simple.money.format.js"></script>      
    <script type="text/javascript">
    var table2 =$('#table_active_client').DataTable();
    table2.order( [ 0, 'desc' ] );
    table2.draw();
                     
    </script> 
</body>
</html>



                                            <div class="  border border-success">
												<h4 class="card-title mt-4">Lista de Clientes en <strong> SUSPENSIÓN</strong> </h4>	
												<table id="table_no_active_client" class="display responsive nowrap" cellspacing="0" width="100%">
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
											</div>

                                            