<?php 
	session_start();
	if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
			{
			header('Location: ./login/index.php');
			exit;
			}
	else    {
			$user=$_SESSION['username'];
			}
	include("./login/db.php");
	$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: " . $mysqli->connect_error;
		}	
	mysqli_set_charset($mysqli,"utf8");
	$today = date("Y-m-d");   
	$convertdate= date("d-m-Y" , strtotime($today));
	$hourMin = date('H:i');
	$usuario=$_SESSION['username'];
	if(($_POST['idt'])&&($_POST['rpp'])) {
		$idt = mysqli_real_escape_string($mysqli, $_REQUEST['idt']);
		$rpp = mysqli_real_escape_string($mysqli, $_REQUEST['rpp']);

	}
	if($_GET['idt']) {
		$idt =$_GET['idt'];
		$rpp =0;
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
	<link rel="stylesheet" href="css/fontello.css">
	<link rel="stylesheet" href="css/estilos.css">

	<style type="text/css">				
		@media print{			
			@page{
				margin: 0cm;
				padding: 0cm;
			}
			*{	
				margin: 0px;
				padding: 0px;
				}
			body{
				margin: 0px;
				padding: 0px;
				font-family: sans-serif;
				font-weight: bold;
				font-size: 9px;
				color: black;
			}
			.mod-body{
				padding:0px;
				margin: 0px;			
			}	
			.table-fact{
				border:1px;
				margin-left:0px;
				margin-right: 0px;
				padding-left: 0px;
				padding-right: 0px;
				width: 100%;
			}	
			.table-fact tr td:first-child {
				width:40px;

			}
			.modal,.fade,.modal-dialog,.modal-content,.modal-body,.title p{
				margin: 0px;
				padding: 0px;
				font-family: sans-serif;
				font-weight: bold;
				font-size: 9px;
			}
			}		
			.width-fact{
				width:56mm; 
			}
				
			
			.size-text {
				font-size: 9px;			
				}	
			.size-text p {
				text-align: left;
				font-weight: bold;
				margin:  0px;
				padding: 0px;				
			}	
			table tr td{
				padding: 0px;
				margin: 0px;	
			}	
		}	
						
	</style>
</head>
<body>
<?php 
//echo "transaccion: $idt  origen: $rpp".".php";
$sql="SELECT * FROM `transacciones` WHERE `idtransaccion` = $idt ORDER BY `idtransaccion` DESC ";
if ($result = $mysqli->query($sql)) {
	$rowf = $result->fetch_assoc();
	$valorRecibido=$rowf["valor-recibido"];
	$valorApagar=$rowf["valor-a-pagar"]; 
	$cambio=$rowf["cambio"]; 	
	$idCliente=$rowf["id-cliente"]; 	
	$fecha=$rowf["fecha"]; 	
	$aprobado=$rowf["aprobado"]; 	
	$hora=$rowf["hora"]; 	
	$cajero=$rowf["cajero"]; 	    
} 
else 
	echo "Error: " . $sql . "<br>" . $conn->error;

$sqlida="SELECT * FROM `afiliados` WHERE `id` = $idCliente ORDER BY `id` ASC ";
if ($result = $mysqli->query($sqlida)) {
	$rowa = $result->fetch_assoc();
	$nombre=$rowa["cliente"];   
	$apellido=$rowa["apellido"];  	
} 
else 
	echo "Error: " . $sqlida . "<br>" . $conn->error;


 ?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-body mod-body">
       <div class="width-fact px-0 mx-0">
			<div class="text-center title">
				<p>AG INGENIERIA WIST</p>
				<p>Nit 40.434.575-1</p>
			</div>	

			<div class="d-flex justify-content-center" >	
			<table class="table-fact">	
					<tr>
						<td>Trans:</td><td>l500<?php echo $idt;  ?></td>
					</tr>
					<tr>
						<td>Fecha:</td><td><?php echo $fecha;echo "  ".$hora;  ?></td>
					</tr>
					<tr>
						<td>Cliente:</td><td><?php echo $nombre." ".$apellido;  ?></td>
					</tr>
					<tr>
						<td>Cajero:</td><td><?php echo $cajero;  ?></td>
					</tr>
					<tr>
						<td>Valor A Pagar:</td><td>$<?php echo number_format($valorApagar);  ?></td>
					</tr>
					<tr>
						<td>Total Recibido:</td><td>$<?php echo number_format($valorRecibido);  ?></td>
					</tr>
					<tr>
						<td  class="table-left-col">Cambio:</td><td>$<?php echo number_format($cambio);  ?></td>
					</tr>
				
			</table>
			</div>

			<div class="size-text  px-0 mx-0">
				<p> ************************************** </p>
				<p> Cll 13#8-47 Fundadores </p>
				<p> Guamal-Meta </p>
				<p> FAVOR VERIFICAR SU CAMBIO </p>
				<p> GRACIAS POR SU PAGO </p>
				<p> NO SE ADMITEN RECLAMACIONES POSTERIORES</p> 
				<p> ***************************************</p>
			</div>	
		</div> 
      </div>
      <div class="modal-footer">        
    	<button type="button" class="btn btn-primary imp">Imprimir</button>
      </div>
    </div>
  </div>
</div>

	<script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/Popper/popper.min.js" ></script>  
    <script src="bower_components/bootstrap/dist/js/bootstrap.js"></script> 
    <script type="text/javascript">
    	$(document).ready(function(){
    		$('#exampleModal').modal('show');
			$('.imp').click(function(){
				$("#btn-md").hide();
				$(this).hide();
				 window.print();
				});	
			$('#exampleModal').on('hidden.bs.modal', function () {
				<?php 
				if ($rpp==0)
					echo "window.location.assign(\"public/transacciones.php\");";
				else
					echo "window.location.assign(\"public/registerPay.php\");"; 
				 ?>
			});
		   	
		});
    </script>
</body>
</html>