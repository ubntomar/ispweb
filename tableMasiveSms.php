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
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    }	
mysqli_set_charset($mysqli,"utf8");
$activo=1;
$suspender=0;
$criterioFacturacion=$_POST["criterioFacturacion"];
if (($_POST["corte"]==1 || $_POST["corte"]==15 )) {
    $sqlprepared="SELECT * FROM redesagi_facturacion.afiliados where ( `cliente` like ? or `apellido` like ? ) and `direccion` like ? and `ciudad` like ? and `corte` = ? and  (`activo` = 1) and (`eliminar` = 0)";
    //echo $sqlprepared;
    $stmt = $mysqli->prepare($sqlprepared);
    $direccion="%{$_POST["address"]}%";
    $name="%{$_POST["name"]}%";
    $ciudad="%{$_POST["ciudad"]}%";
    $corte="{$_POST["corte"]}";
    $stmt->bind_param("ssssi",$name,$name,$direccion,$ciudad,$corte);
}
if  ($_POST["corte"]=="") { 
    $sqlprepared="SELECT * FROM redesagi_facturacion.afiliados  where ( `cliente` like ? or `apellido` like ? ) and `direccion` like ? and `ciudad` like ?  and  (`activo` = 1) and (`eliminar` = 0)";
    $stmt = $mysqli->prepare($sqlprepared);
    $direccion="%{$_POST["address"]}%";
    $name="%{$_POST["name"]}%";
    $ciudad="%{$_POST["ciudad"]}%";
    $stmt->bind_param("ssss",$name,$name,$direccion,$ciudad);
}
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0) {exit("No hay resultados....");echo "<h1>no hay resultados</h1>"; }
else $response_ok;

echo "
<div class=\"card-header\">	Seleccione una lista de clientes</div>
<table id=\"table_client_to_sms\" class=\"display\" cellspacing=\"0\" width=\"100%\">
    <thead>
        <tr>
            
            <th>Id</th>
            <th>Cliente</th>
            <th>Dirección</th>
            
            <th>Corte</th>
            <th>Valor Plan</th>
            
            <th>Saldo a hoy</th>
            <th>teléfono</th>
            <th>Ciudad</th>
        </tr>
    </thead>
    <tbody>";
    $saldoTotal=0;
    while($row = $result->fetch_assoc()) {

        $day=date('d');
        $sqlSaldo="SELECT SUM(saldo) saldo,valorf FROM redesagi_facturacion.factura where `id-afiliado`={$row['id']} and cerrado=0 ;";            
       
        if ($resultsaldo = $mysqli->query($sqlSaldo)) {
            $rowsaldo = $resultsaldo->fetch_assoc();
            $saldo=$rowsaldo["saldo"];  
            $valor_factura=$rowsaldo["valorf"];
            $resultsaldo->free();
            }
        $dias_plazo=5;    
        //echo "1:$criterioFacturacion 2:$saldo 3:$valor_factura 4:$day 5:{$row['corte']} 6:$dias_plazo <br>";
        if ($criterioFacturacion=="1") {
            if (($saldo<=$valor_factura) && ($day<= ( $row['corte'] +$dias_plazo) )  ) {
                $saldoTotal+=$saldo; 
                echo"<tr>
                
                <td>{$row['id']}</td>
                <td>{$row['cliente']} {$row['apellido']}</td>
                <td>{$row['direccion']}</td>
                
                <td>{$row['corte']}</td>
                <td>{$row['pago']}</td>
                
                <td>$$saldo</td>
                <th>{$row['telefono']}</th>
                <td>{$row['ciudad']}</td>
                </tr>" ;
            }
        
        } 
        if ($criterioFacturacion=="-1"   ) {
            if (( ($saldo>0) && ($day> ( $row['corte'] +$dias_plazo) )) || ( ($day<=($row['corte']+$dias_plazo))&&($saldo>$valor_factura) )) {
                $saldoTotal+=$saldo; 
                echo"<tr>
                
                <td>{$row['id']}</td>
                <td>{$row['cliente']} {$row['apellido']}({$row['id']})</td>
                <td>{$row['direccion']}</td>
                
                <td>{$row['corte']}</td>
                <td>{$row['pago']}</td>
                
                <td>$$saldo</td>
                <th>{$row['telefono']}</th>
                <td>{$row['ciudad']}</td>
                </tr>" ;
            }
        
        }  
        if ($criterioFacturacion=="0") {  
            $saldoTotal+=$saldo;       
            echo"<tr>
                
                <td>{$row['id']}</td>
                <td>{$row['cliente']} {$row['apellido']}</td>
                <td>{$row['direccion']}</td>
                
                <td>{$row['corte']}</td>
                <td>{$row['pago']}</td>
                
                <td>$$saldo</td>
                <th>{$row['telefono']}</th>
                <td>{$row['ciudad']}</td>
                </tr>" ;
        }      
        
    }    
        
    echo "</tbody>

    <tfoot>
        <tr>
           
            <th>Id</th>
            <th>Cliente</th>
            <th>Dirección</th>
            
            <th>Corte</th>
            <th></th>
            
            <th>Ciudad</th>
            <th>teléfono</th>
            <th class=\" rounded text-light bg-dark\">$".number_format($saldoTotal,0)." </th>
        </tr>
    </tfoot>
</table>
";
echo "<div class=\"sumTotal border border-secondary rounded my-2 p-2\"><a>Saldo total a hoy: </a><a class=\" rounded text-light bg-dark p-1 \">$".number_format($saldoTotal,0)." </a></div>";

$stmt->close();
?>