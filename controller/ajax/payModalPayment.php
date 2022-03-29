<?php
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: ../../login/index.php');
		exit;
		}
else    {
        $user = $_SESSION['username'];
	    $idCajero = $_SESSION['idCajero'];
		} 
if($_POST['rowid']) {
		$id = $_POST['rowid'];
		$cedula = $_POST['cedula']; 
		$telefono = $_POST['telefono'];   
		include("../../login/db.php");
		$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
		if ($mysqli->connect_errno) {
	    	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
			}	
		mysqli_set_charset($mysqli,"utf8");
		$today = date("Y-m-d");   
		$convertdate= date("d-m-Y" , strtotime($today));
		
		$sql = "SELECT * FROM `afiliados` WHERE `id`=$id ";
		if ($result = $mysqli->query($sql)) {
			$row = $result->fetch_assoc();	 
			 
			$registration=$row["registration-date"];
            $corte=$row["corte"];
            			
			$sql = "SELECT * FROM `factura`  WHERE `factura`.`id-afiliado`='$id' ";
            //table header
            $table_content="
            <table id=\"table_past_payment\" class=\"display compact\" cellspacing=\"0\" width=\"100%\">
            <thead>
                <tr>
                <th>Id</th>
                <th>Fecha</th>
                <th>Valor</th>
                <th>Cajero</th>
                <th>Saldo</th>
                <th>Mes</th>
                </tr>
            </thead>
            <tbody>
            ";
			if ($result = $mysqli->query($sql)) {
                while ($rowf = $result->fetch_assoc()) {  
                    $id_factura=$rowf["id-factura"];
                    $fecha_pago=$rowf["fecha-pago"];	
                    $valor_factura=$rowf["valorf"];
                    $saldo=$rowf["saldo"];
                    $periodo=$rowf["periodo"];
                    $sqlr="SELECT * FROM   redesagi_facturacion.recaudo   WHERE  idfactura = $id_factura;";
                    $resultr = $mysqli->query($sqlr);
                    $rowr = $resultr->fetch_assoc();
                    $cajero=$rowr["vendedor"];
                    if($cajero=="" && $saldo =="0" )$cajero="Ajuste";
                    $table_content.="
                    <tr><td>$id_factura</td>
                        <td>$fecha_pago</td>
                        <td>$valor_factura</td>
                        <td>$cajero</td>
                        <td>$saldo</td>
                        <td>$periodo</td>
                    </tr>
                    "; 
                    }
            $result->free();
            }
            //table footer			
            $table_content.="   
            </tbody>
            <tfoot>
                <tr>
                <th>Id</th>
                <th>Fecha</th>
                <th>Valor</th>
                <th>Cajero</th>
                <th>Saldo</th>
                <th>Mes</th>
                </tr>    
            </tfoot>
            </table>	
            ";
            echo $table_content;
            			
			
			}
 		}

 ?>