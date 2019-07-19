<?php 
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: login/index.php');
		exit;
		}
else    {
        $user=$_SESSION['username'];
        $role=$_SESSION["role"];
		}
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$month = date('n');
$year=date("Y");
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$cajero=$_POST["cajero"];
$from=$_POST["from"];
$to=$_POST["to"];
if($cajero=="todos")
    $txt_cajero="";
else
    $txt_cajero="AND  redesagi_facturacion.transacciones.cajero='$cajero' ";     
echo "
<form style=\"display: hidden\" action=\"printable.php\" method=\"POST\" id=\"form\">
        <input type=\"hidden\" id=\"idt\" name=\"idt\" value=\"0\"/>
        <input type=\"hidden\" id=\"rpp\" name=\"rpp\" value=\"register-pay\"/>
</form>
<table id=\"clientList\" class=\"display dataTable_Morosos cell-border\" cellspacing=\"0\" width=\"100%\">
    <thead  class=\"bg-primary\">
        <tr>
            <td>Nombre</td>
            <td>Dirección</td>
            <td>Pago</td>
            <td>Fecha</td>
            <td>Cajero</td>				
            <td><i class=\" icon-print  \"></i></td>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td>Nombre</td>
            <td>Dirección</td>
            <td>Pago</td>
            <td>Fecha</td>
            <td>Cajero</td>
            <td><i class=\" icon-print  \"></i></td>
        </tr>
    </tfoot>
    <tbody > 
";
    $sql = "SELECT * FROM redesagi_facturacion.transacciones where redesagi_facturacion.transacciones.fecha >='$from' AND redesagi_facturacion.transacciones.fecha <='$to' $txt_cajero   ;";
    if ($result = $mysqli->query($sql)) {
        $recaudo=0;
        $cnt=0;

        while ($row = $result->fetch_assoc()) {
            $cnt+=1;
            $idtransaccion=$row["idtransaccion"];
            $idafi=$row["id-cliente"];
            $sqlafi="SELECT * FROM `afiliados` WHERE `id` = $idafi  ";
            $resultafi = $mysqli->query($sqlafi);
            $rowafi = $resultafi->fetch_assoc();
            $recaudo+=$row["valor-a-pagar"];
            echo "<tr class=\"text-center  \">";				
            echo "<td>".$rowafi["cliente"]." ".$rowafi["apellido"]."</td>";
            echo "<td>".$rowafi["direccion"]."</td>";
            echo "<td>".$row["valor-a-pagar"]."</td>";
            echo "<td class=\" align-middle \">".$row["fecha"]." ".$row["hora"]."</td>";
            echo "<td class=\" align-middle \"> {$row["cajero"]} </td>";
            echo "<td class=\" align-middle \"><a href=\"printable.php?idt=$idtransaccion&rpp=0\" class=\"text-primary icon-client \" ><i class=\" icon-print  \"></i></a></td>";
            echo "</tr>";		
            }
            $result->free();
        }
        	
        echo"
            <!-- Modal -->
            <div class=\"modal fade\" id=\"payModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog\" role=\"document\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                    <h5 class=\"modal-title\" id=\"exampleModalLabel\">Detalles de Pago</h5>
                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                        <span aria-hidden=\"true\">&times;</span>
                    </button>
                    </div>  
                    
                    <div class=\"modal-body\">
                    <div class=\"fetched-data\"></div>  
                    
                    </div>
                    <div class=\"modal-footer\">
                    <button type=\"button\" id=\"cancelbutton\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Cancelar</button>
                    <button type=\"button\" id=\"paybutton\" class=\"btn btn-primary\">Pagar</button>
                    </div>
                </div>
                </div>
            </div>									
        
    </tbody>
</table>
<p class=\"p-3 mb-2 bg-dark text-white\" >Total recibido:$$recaudo</p>
";
?>
