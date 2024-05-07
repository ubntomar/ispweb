<?php
if (file_exists("/var/www/ispexperts/login/db.php")) {
    require("/var/www/ispexperts/login/db.php");
} else {
    require("/home/omar/docker-work-area/go/ispweb/login/db.php");
    $server="localhost:3306";
}

if (file_exists("/var/www/ispexperts/Mkt.php")) {
    require("/var/www/ispexperts/Mkt.php");
} else {
    require("/home/omar/docker-work-area/go/ispweb/Mkt.php");
}


if (file_exists('/home/omar/docker-work-area/go/ispweb/vendor/autoload.php')) {
    require_once '/home/omar/docker-work-area/go/ispweb/vendor/autoload.php'; // Incluir el autocargador de Composer
} else {
    require_once '/var/www/ispexperts/vendor/autoload.php'; // Incluir el autocargador alternativo
}
// Referenciamos la clase requerida
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    print "Failed to connect to MySQL: " . $mysqli->connect_error;
}
mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");
$currentMonth = date('n');
$currentYear = date('Y'); 
$currentDay = date('j');
$currentHour = date('h:i A'); // h para hora en formato 12 horas, i para minutos, A para AM/PM
$convertdate = date("d-m-Y", strtotime($today));


echo "Iniciando proceso de creación de facturas\n";
$sqlSelect = "SELECT * FROM `afiliados` WHERE `eliminar` = 0 AND `activo` = 1 AND `id-formato-factura` IS NOT NULL";
    $result = $mysqli->query($sqlSelect);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Creando factura para el cliente: ".$row["cliente"]." ".$row["apellido"]."\n";
            $id = $row["id"];
            $cliente = $row["cliente"]."  ".$row["apellido"];
            $direccion = $row["direccion"];
            $telefono = $row["telefono"];
            $nit = $row["cedula"];
            $ciudad = $row["ciudad"];
            $departamento = $row["departamento"];
            $mail = $row["mail"];
            $valorPlan = $row["pago"];
            $ifFormatoFactura = $row["id-formato-factura"];
            $sqlSelectFormato = "SELECT * FROM `formato_factura` WHERE `id`=$ifFormatoFactura";
            $resultFormato = $mysqli->query($sqlSelectFormato);
            if ($resultFormato->num_rows > 0) {
                while($rowFormato = $resultFormato->fetch_assoc()) {
                    $representante=$rowFormato["representante"];
                    $nitEmpresa=$rowFormato["nit"];
                    $direccionEmpresa=$rowFormato["direccion"];
                    $telefonoEmpresa=$rowFormato["telefono"];
                    $mailEmpresa=$rowFormato["mail"];
                    $ciudadEmpresa=$rowFormato["ciudad"];
                    $departamentoEmpresa=$rowFormato["departamento"]; 
                }



            createPdf($id,$cliente, $direccion, $telefono, $nit, $ciudad, $departamento,$mail,new Dompdf(),$valorPlan,$representante,$nitEmpresa,$direccionEmpresa,$telefonoEmpresa,$mailEmpresa,$ciudadEmpresa,$departamentoEmpresa);
            }

        }
    }


function createPdf($id,$cliente, $direccion, $telefono, $nit, $ciudad, $departamento,$mail,$dompdf,$valorPlan,$representante,$nitEmpresa,$direccionEmpresa,$telefonoEmpresa,$mailEmpresa,$ciudadEmpresa,$departamentoEmpresa){
    echo "Creando factura para el cliente: $cliente\n";
    $valoAPagar = "$".number_format($valorPlan, 0, '.', ',');
    $year = date('Y');
    $mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
    $month = $mes[date('n')];
    $fechaFactura=date("d")." de ".$month." de ".$year;
    $html = <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Factura</title>
        <style>
            /* Estilos CSS */
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 10px;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .header {
                border-bottom: 1px solid #000;
            }
            .header table tr td {
                width: 400px;
                margin-bottom: 5px;
                /* font-size: 24px; */
            }
            .header table tr td h2{
                font-size: 44px;
                color: #333;
            }
            .small-text {
                font-size: 12px;
                line-height: 1.4;
                margin-top: 20px;
                text-align: justify;
            }
            .invoice-details {
                margin-bottom: 5px;
                border-bottom: 1px solid #000;
            }
            .client-info-left, .client-info-right {
                width: 100%;
            }
            .client-info-left table, .client-info-right table {
                width: 50%;
                border-collapse: collapse;
            }
            .client-info-left th, .client-info-left td,
            .client-info-right th, .client-info-right td {
                padding: 3px;
                text-align: left;
                /* border-bottom: 1px solid #ddd; */
            }
            .item-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            .item-table th, .item-table td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            .item-table th {
                background-color: #f2f2f2;
            }
            .totals {
                text-align: right;
                margin-top: 20px;
            }
            .totals-total {
                font-style: bold;
            }
            .signature {
                margin-top: 40px;
                border-top: 1px solid #ddd;
                padding-top: 20px;
                text-align: center;
            }
            .signature-line {
                border-bottom: 1px solid #000;
                height: 30px;
                width: 100%;
            }
        </style>
    </head> 
    <body>
        <div class="container">
            <div class="header">
                <table>
                    <tr>
                        <td>
                            <h2>$representante</h2>
                            <p>NIT $nitEmpresa Reg. Simplificado</p>
                            <p>Redes y soluciones de Software.</p>
                        </td>
                        <td>
                            <p>$direccionEmpresa. $ciudadEmpresa</p>
                            <p>$departamentoEmpresa, CO - (57+) $telefonoEmpresa</p>
                            <p>www.ispexperts.com</p>
                            <p>ag.ingenieria.wist@gmail.com</p>
                        </td>
                    </tr>
                </table>
                <!-- <div class="header-left">
                    
                </div>
                <div class="header-right">
                    
                </div> -->
            </div>
            <div class="invoice-details">
                <div class="client-info-left">
                    <table>
                        <tr>
                            <th>Fecha de Factura:</th>
                            <td>$fechaFactura</td>
                        </tr>
                        <tr>
                            <th>Serial de Factura:</th>
                            <td>INV-$id</td>
                        </tr>
                        <tr>
                            <th>Nombre del Cliente:</th>
                            <td>$cliente</td>
                        </tr>
                        <tr>
                            <th>Dirección:</th>
                            <td>$direccion</td>
                        </tr>
                        <tr>
                            <th>Teléfono:</th>
                            <td>$telefono</td>
                        </tr>
                        <tr>
                            <th>NIT:</th>
                            <td>$nit</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>$mail</td>
                        </tr>
                    </table>
                </div>
                
            </div>
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Subtotal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Servicio de Internet Banda Ancha</td>
                        <td>$valoAPagar</td>
                        <td>$valoAPagar</td>
                    </tr>
                    
                </tbody>
            </table>
            <div class="totals">
                <p>Valor a Pagar: $valoAPagar</p>
                <p>Subtotal: $valoAPagar</p>
                <p class="totals-total">Total: $valoAPagar</p>
            </div>
            <div class="small-text">
            </div>
            <div class="signature">
                <h3>Firma del Cliente</h3>
                <div class="signature-line"></div>
            </div>
        </div>
    </body>
    </html>
    HTML;
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $output = $dompdf->output();
    echo "Guardando factura en el servidor\n";
    
    
    
    if (file_exists("/var/www/ispexperts/controller/cron/")) {
        $directory = "/var/www/ispexperts/controller/cron/facturas/$year/$month/";
    } else {
        $directory = "/home/omar/docker-work-area/go/ispweb/controller/cron/facturas/$year/$month/";
    }

    // Verificar si el directorio existe, si no, crearlo ///
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    // Nombre del archivo
    $filename = "factura_{$month}_{$year}_{$id}_".str_replace(" ", "-", $cliente).".pdf";

    // Guardar el PDF en un archivo
    file_put_contents($directory.$filename, $output); 
    echo "Factura guardada en el servidor: $directory.$filename, \n";
    $html = null;      
}





?>



