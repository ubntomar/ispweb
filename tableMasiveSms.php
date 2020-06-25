<?php 
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
        {
        //header('Location: login/index.php');
        //exit;
        }
else    {
        $user=$_SESSION['username'];
        }
include("login/db.php");
include("dateHuman.php");
date_default_timezone_set('America/Bogota');
$date = new DateTime('NOW');
$date->format('Y-m-d');
$hoy=$date->format('Y-m-d');
$date->format('Y/m/d');
$today=$date->format('Y/m/d');
$date->modify('-3 day');
$yesterday=$date->format('Y/m/d');
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
    //echo $sqlprepared;
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
<table id=\"table_client_to_sms\" class=\"display row-border stripe\" cellspacing=\"0\" width=\"100%\">
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
        $id=$row["id"];
        $ping=$row["ping"];
        $pingDate=$row["pingDate"];
        $sql="select * from `redesagi_facturacion`.`sent_messages` where `fecha` >= '$yesterday' and `fecha` <= '$today' and `id_client`= '$id'";    
        if ($res = $mysqli->query($sql)) {
            $row_cnt = $res->num_rows;
            if($row_cnt>0){
                $smsFlag="<div class='d-flex justify-content-center'><strong class='border border-success text-dark m-o mb-1 p-1' >SMS O.K</strong></div>";
            }
            else{
                $smsFlag="";
            }
            $res->free();
        }
        if($row["suspender"]==1)$text1="<p class='border border-warning bg-info text-white'>Suspendido</p>";
        else $text1="";
        if($row["shutoffpending"]==1)$text2="<p class='border border-warning bg-info text-white'>en proceso...</p>";
        else $text2="";
        $day=date('d');
        $sqlSaldo="SELECT SUM(saldo) saldo,ANY_VALUE(valorf) FROM redesagi_facturacion.factura where `id-afiliado`={$row['id']} and cerrado=0 ;";   
        //echo $sqlSaldo;      
        if ($resultsaldo = $mysqli->query($sqlSaldo)) {
            $rowsaldo = $resultsaldo->fetch_assoc();
            $saldo=$rowsaldo["saldo"];  
            $valor_factura=$rowsaldo["valorf"];
            $resultsaldo->free();
            }
        $dias_plazo=5;   
        if(($sincedexDays=get_date_diff( $pingDate, $hoy, 2 ))=="") $sinceString="Ultimo ping: Hoy"; else $sinceString="Hace $sincedexDays Dias";
        if($pingDate) $iconPing="<div class=\"d-flex align-items-center justify-content-between\"><div id=\"textCheckPing-$id\" class=\"border border-1 border-success rounded m-1 p-1 \"><h6 class=\"mb-0\"><small><i class=\"icon-smile text-success\">Ping O.K</i></small></h6><small class=\"font-italic\"><b class=\"text-uppercase\">$sinceString</b></small></div><button class=\"checkPing\" id=\"checkPing-$id\" class=\"m-1 border border-rounded\"><i class=\"icon-arrows-ccw\"></i></button></div>"; 
        else $iconPing="<div class=\"d-flex align-items-center justify-content-between\"><div id=\"textCheckPing-$id\"><h6 class=\"mb-0\"><small><i class=\"icon-emo-unhappy text-danger\">No ping</i></small></h6></div><button class=\"checkPing\" id=\"checkPing-$id\" class=\"m-1 border border-rounded\"><i class=\"icon-arrows-ccw\"></i></button></div>";  
        //echo "1:$criterioFacturacion 2:$saldo 3:$valor_factura 4:$day 5:{$row['corte']} 6:$dias_plazo <br>";
        if ($criterioFacturacion=="1") {
            if (($saldo<=$valor_factura) && ($day<= ( $row['corte'] +$dias_plazo) )  ) {
                $saldoTotal+=$saldo; 
                echo"<tr class=\"\"   id='tr-{$row['id']}' >
                
                <td>{$row['id']}  </td>
                <td>{$row['cliente']} {$row['apellido']} $smsFlag<input type='hidden' size='3' id='dsb-{$row['id']}' value='' > <input size='11' class=\"inputIp font-weight-bold\" type='text'   id='ip--{$row['id']}' value='{$row['ip']}' > $iconPing <p  id='p-{$row['id']}' ></p> $text1 $text2 </td>
                <td>{$row['direccion']}</td>
                
                <td>{$row['corte']}</td>
                <td>{$row['pago']}</td>
                
                <td>$$saldo</td>
                <td>{$row['telefono']}</td>
                
                <td>{$row['ciudad']}</td>
                </tr>" ;
            }
        
        } 
        if ($criterioFacturacion=="-1"   ) {   
            if (( ($saldo>0) && ($day> ( $row['corte'] +$dias_plazo) )) || ( ($day<=($row['corte']+$dias_plazo))&&($saldo>$valor_factura) )) { 
                $saldoTotal+=$saldo; 
                echo"<tr class=\"\"   id='tr-{$row['id']}' > 
                
                <td>{$row['id']}  </td>
                <td>{$row['cliente']} {$row['apellido']}({$row['id']})$smsFlag<input type='hidden' size='3' id='dsb-{$row['id']}' value='' > <input size='11' class=\"inputIp font-weight-bold\" type='text'   id='ip--{$row['id']}' value='{$row['ip']}'> $iconPing <p  id='p-{$row['id']}' ></p> $text1 $text2 </td>
                <td>{$row['direccion']}</td>
                
                <td>{$row['corte']}</td>
                <td>{$row['pago']}</td>
                
                <td>$$saldo</td>
                <td>{$row['telefono']}</td>
                
                <td>{$row['ciudad']}</td>
                </tr>" ;
            }
        
        }  
        if ($criterioFacturacion=="0") {  
            $saldoTotal+=$saldo;       
            echo"<tr class=\"\"   id='tr-{$row['id']}' >
                
                <td>{$row['id']}  </td>
                <td>{$row['cliente']} {$row['apellido']} $smsFlag<input type='hidden' size='3' id='dsb-{$row['id']}' value='' > <input size='11' class=\"inputIp font-weight-bold\" type='text'   id='ip--{$row['id']}' value='{$row['ip']}'> $iconPing <p  id='p-{$row['id']}' ></p> $text1 $text2 </td>
                <td>{$row['direccion']}</td>
                
                <td>{$row['corte']}</td>
                <td>{$row['pago']}</td>
                
                <td>$$saldo</td>
                <td>{$row['telefono']}</td>
                
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
<div class="d-flex justify-content-center my-3" id="smsaddcut" >
    <div class="btn-group btn-group-toggle my-3 w-50" data-toggle="buttons">														
    <label class="btn btn-success mr-2">
        <input type="radio" name="options" id="option2"  value="1"  autocomplete="off"> SMS
    </label>
    <label class="btn btn-success ml-2">
        <input type="radio" name="options" id="option3"  value="2"  autocomplete="off"> Cortar Servicio 
    </label>
    </div>														
</div>
<div class="form-group px-1 border border-success rounded mx-1 my-3" id="message_box">
    <label for="sel1">Escriba el mensaje que será enviado a los clientes que ha seleccionado </label>
    <textarea class="form-control" id="sms_text_content" rows="3" placeholder="Ingrese el mensaje a enviar"></textarea>
    <small id="" class="form-text text-muted">Campo obligatorio.</small>
</div>
<div id="sendsmsbutton" >
    <button type="text" class="btn btn-primary mb-2" id="btn_send_sms">
        <span class=" spinner-border-sm" role="status" aria-hidden="true" id="spinner-enviar"></span>
        Enviar SMS
    </button>
</div>
<div id="cutServiceButton" >
    <button type="text" class="btn btn-primary mb-2" id="btn_cut_service">
        <span class=" spinner-border-sm" role="status" aria-hidden="true" id="spinner-enviar"></span>
        Remove Client Service
    </button>
</div>