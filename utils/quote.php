<?php
// session_start();
// if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
//     header('Location: login/index.php');
//     exit;
// } else {
//     $user = $_SESSION['username'];
// }
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Generador de Recibos</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet">

    <link rel="stylesheet" href="bower_components/alertify/css/alertify.min.css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css">



    <style type="text/css" id="formato">
        #invoice {
            padding: 30px;
        }

        .invoice {
            position: relative;
            background-color: #FFF;
            min-height: 680px;
            padding: 15px
        }

        .invoice header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #3989c6
        }

        .invoice .company-details {
            text-align: right
        }

        .invoice .company-details .name {
            margin-top: 0;
            margin-bottom: 0
        }

        .invoice .contacts {
            margin-bottom: 20px
        }

        .invoice .invoice-to {
            text-align: left
        }

        .invoice .invoice-to .to {
            margin-top: 0;
            margin-bottom: 0
        }

        .invoice .invoice-details {
            text-align: right
        }

        .invoice .invoice-details .invoice-id {
            margin-top: 0;
            color: #3989c6
        }

        .invoice main {
            padding-bottom: 50px
        }

        .invoice main .thanks {
            margin-top: -100px;
            font-size: 2em;
            margin-bottom: 50px
        }

        .invoice main .notices {
            padding-left: 6px;
            border-left: 6px solid #3989c6
        }

        .invoice main .notices .notice {
            font-size: 1.2em
        }

        .invoice table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px
        }

        .invoice table td,
        .invoice table th {
            padding: 15px;
            background: #eee;
            border-bottom: 1px solid #fff
        }

        .invoice table th {
            white-space: nowrap;
            font-weight: 400;
            font-size: 16px
        }

        .invoice table td h3 {
            margin: 0;
            font-weight: 400;
            color: #3989c6;
            font-size: 1.2em
        }

        .invoice table .qty,
        .invoice table .total,
        .invoice table .unit {
            text-align: right;
            font-size: 1.2em
        }

        .invoice table .no {
            color: #fff;
            font-size: 1.6em;
            background: #3989c6
        }

        .invoice table .unit {
            background: #ddd
        }

        .invoice table .total {
            background: #3989c6;
            color: #fff
        }

        .invoice table tbody tr:last-child td {
            border: none
        }

        .invoice table tfoot td {
            background: 0 0;
            border-bottom: none;
            white-space: nowrap;
            text-align: right;
            padding: 10px 20px;
            font-size: 1.2em;
            border-top: 1px solid #aaa
        }

        .invoice table tfoot tr:first-child td {
            border-top: none
        }

        .invoice table tfoot tr:last-child td {
            color: #3989c6;
            font-size: 1.4em;
            border-top: 1px solid #3989c6
        }

        .invoice table tfoot tr td:first-child {
            border: none
        }

        .invoice footer {
            width: 100%;
            text-align: center;
            color: #777;
            border-top: 1px solid #aaa;
            padding: 8px 0
        }

        .zoekResultatLine {
            text-decoration: overline;
            color: rgb(217, 217, 217);
        }

        .zoekResultat {
            color: rgb(0, 158, 224);
        }

        @media print {
            .invoice {
                font-size: 11px !important;
                overflow: hidden !important
            }

            .invoice footer {
                position: absolute;
                bottom: 10px;
                page-break-after: always
            }

            .invoice>div:last-child {
                page-break-before: always
            }
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
    mysqli_set_charset($mysqli, "utf8");
    date_default_timezone_set("America/Bogota");
    setlocale(LC_MONETARY,"es_CO");
    if ($_GET['idc']) {

        $id = mysqli_real_escape_string($mysqli, $_REQUEST['idc']);
        $mons = array(1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre");
        $date = getdate();
        $month = date('n');
        $month_name = $mons[$month];
        $month_name2 = $mons[$month + 1];
        $fecha_recibo = $date['year'] . '/' . $date['mon'] . '/' . $date['mday'];
        //echo "Fecha de impresion de recibo :$fecha_recibo ";
        $hoy = date("d/m/Y");
        $query = "SELECT * FROM `redesagi_facturacion`.`afiliados` WHERE id=" . $id . "";
        if ($result = $mysqli->query($query)) {
            $row = $result->fetch_assoc();
            $cliente = $row['cliente'] . "  " . $row['apellido'];
            $corte = $row['corte'];
            $direccion = $row['direccion'];
            $ciudad = $row['ciudad'];
            $departamento = $row['departamento'];
            $pago = $row['pago'];
            $telefono = $row['telefono'];
            $valorAfiliacion = $row['valorAfiliacion'];
            $email = $row['mail'];
            $registrationDate = $row['registration-date'];
            $velocidad = $row['velocidad-plan'];
            $cajero= $row['cajero'];
            $result->free();
        }
        // $ultimopago = "";
        // $pagoAnterior = "";
        // $codigo = 300 + $id;
        // $year = date("Y");
        // $countDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        // $periodo = "1 de $month_name a $countDay de $month_name de $year";
        // $paguehasta = "5 DE $month_name";
        // $suspension = "7 DE $month_name";

        
    }
    ?>
    <div id="invoice">

        <div class="toolbar hidden-print">
            <div class="text-right">
                <button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button>

            </div>
            <hr>
        </div>
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col">
                            <h1>Ag Ingenenieria Wist</h1>
                            <h3>NIT 40434575-1</h3>
                            <small>Reg. Simplificado</small>
                            <h6 class="text-muted">Especialistas en implementación de redes y soluciones de Software.</h6>
                        </div>
                        <div class="col company-details">
                            <h2 class="name">
                                <hr>
                            </h2>
                            <div>Cll 13#8-47 Brr. GuamalMeta,CO-</div>
                            <div>(57+) 314-765-46-55</div>
                            <div>www.agingenieriawisp.com</div>
                            <div>ventas@agingenieria.tech</div>
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">Cliente:</div>
                            <h2 class="to"><?php echo $cliente;  ?></h2>
                            <div class="address"><?php echo "$direccion $ciudad $departamento";  ?>, CO</div>
                            <div class="email"><a href="mailto:john@example.com"><?php echo $email;  ?></a></div>
                            <div class="address"><?php echo "$telefono";  ?></div>
                        </div>
                        <div class="col invoice-details">
                            <h1 class="invoice-id">COTIZACIÓN</h1>
                            <h2 class="name">SN1688-GMC</h2>
                            <div class="date">Fecha de Factura: <?php echo $registrationDate;  ?></div>
                            <div class="date">Vence: <?php echo $registrationDate;  ?></div>
                            <div class="date">Cajero: <?php echo $cajero;  ?></div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-left">DESCRIPCIÓN</th>
                                <th class="text-right">VALOR UNITARIO</th>
                                <th class="text-right">CANTIDAD</th>
                                <th class="text-right">SUBTOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                                <?php
                                $sql = "SELECT * FROM `redesagi_facturacion`.`factura` where `id-afiliado` = '$id' AND (`notas` LIKE '%1er Mes%' OR `notas` LIKE '%Afiliacion%' OR `notas` LIKE '%Prorrateo%') ORDER BY `id-factura` ASC";
                                //echo "<p>$sql</p>";
                                $total=0;
                                $cont=0;
                                if ($result = $mysqli->query($sql)) {
                                    while ($row = $result->fetch_assoc()) {
                                        $cont+=1;
                                        $notas=$row["notas"];
                                        $valorf=$row["valorf"];
                                        $cerrado = $row["cerrado"];
                                        $periodo=$row["periodo"];
                                        if($notas=="Afiliacion" || $notas=="Afiliacion de servicio"){
                                            $descripcion="Afiliación servicio  de Internet B Ancha ,equipos en alquiler.
                                            Paga los $corte de cada mes.
                                            Suspensión ".($corte+6)." de cada mes.";
                                            $periodo="";
                                        }elseif($notas=="Servicio-1er mes"){
                                            $descripcion="Servicio mensual de Internet ";
                                        }elseif($notas=="Prorrateo"){
                                            $descripcion="Días adicionales de servicio, valor de prorrateo "; 
                                        }
                                        if($cerrado==1){
                                            $total+=$valorf;
                                            echo "
                                            <tr>
                                                <td class=\"no\">SA$cont</td>
                                                <td class=\"text-left\">
                                                    <h3>
                                                    $periodo
                                                    </h3>
                                                    $descripcion 
                                                </td>
                                                <td class=\"unit\">$$valorf</td>
                                                <td class=\"qty\">1</td>
                                                <td class=\"total\">$$valorf</td>
                                            </tr>";                                            
                                        }
                                        if($cerrado==0){
                                            echo "
                                            <tr>
                                                <td class=\"no\">SA$cont</td>
                                                <td class=\"text-left\">
                                                    <h3>
                                                    $periodo
                                                    </h3>
                                                    $descripcion <strong>$velocidad</strong>Mbps
                                                </td>
                                                <td class=\"unit\">$$valorf</td>
                                                <td class=\"qty\">1</td>
                                                <td class=\"total\">$$valorf</td> 
                                            </tr>";                                            
                                        }
                                    }
                                }
                                ?>
                            

                            <tr>
                                <td class="no">C02</td>
                                <td class="text-left">
                                    <h3></h3>
                                </td>
                                <td class="unit">$0</td>
                                <td class="qty">0</td>
                                <td class="total">$  </td>
                            </tr>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2">SUBTOTAL</td>
                                <td>$<?php echo $valorAfiliacion+$pago;  ?></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2">DESCUENTO</td>
                                <td>$0</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2">TOTAL</td>
                                <td>$<?php echo $valorAfiliacion+$pago;  ?></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="row">
                        <div class="col text-justify">
                            <p>
                                <small>La Empresa de Telecomunicaciones AG INGENIERIA WIST, en adelante AG INGENIERIA o La Empresa, mediante el
                                    presente documento y sus Anexos señala las condiciones de prestación de sus servicios de comunicaciones,
                                    que a la vez constituyen la oferta por medio de la cual está dispuesta a prestar dichos servicios al CLIENTE o
                                    USUARIO quienes en adelante se denominarán individual o conjuntamente como AG INGENIERIA y EL USUARIO, quien
                                    suscribió la carátula o solicitud de prestación de servicios de comunicaciones y/o quien efectuó la solicitud del
                                    servicio a través de cualquiera de los medios establecidos por AG INGENIERIA y recibió aceptación por parte de AG INGENIERIA de la
                                    solicitud. Las partes han celebrado el presente contrato de Servicios de Telecomunicaciones (En adelante “el
                                    contrato”), que se regirá por los siguientes términos:
                                </small>
                            </p>

                            <P>
                                <small>
                                    REPORTE DE FALLA DEL SERVICIO:
                                    Es requerido que el cliente NO realice modificaciones de las conexiones
                                    ni de las condiciones luego de la instalación. Solo personal autorizado de AG INGENIERIA debería hacerlo. En evento de
                                    daño del cableado, el cliente deberá asumir por su cuenta la recuperación en óptimas condiciones del mismo,
                                    cumpliendo las normas establecidas para este tipo de cableados. AG INGENIERIA Será responsable por fallas o suspensión del servicio que no se ocasionen por fallas técnicas
                                    y/o actos de terceros. Las visitas técnicas tendrán costo cuando la falla se origine en la red interna de EL
                                    USUARIO. Cuando las causas puedan ser solucionadas directamente, AG INGENIERIA se obliga a restablecer el servicio
                                    en el menor tiempo posible. AG INGENIERIA no se responsabiliza de los daños y perjuicios producidos por causas no
                                    imputables o ajenas a ella, como los causados por EL USUARIO, un tercero, o por fuerza mayor o caso fortuito.Así mismo, la configuración, mantenimiento
                                    preventivo o correctivo de estos equipos, se hará únicamente por el personal autorizado por AG INGENIERIA, quien se
                                    reserva el derecho a efectuar los cambios que considere convenientes o necesarios. Si se efectúa algún cambio
                                    de equipos, se suscribirá un nuevo documento de entrega. EL USUARIO no tiene derecho de retención sobre
                                    los equipos entregados por AG INGENIERIA en comodato y por lo tanto es su obligación proceder con la entrega en los
                                    términos establecidos en la normatividad vigente a la terminación de EL SERVICIO respectivo o del contrato o
                                    cuando las circunstancias dentro de la ejecución del mismo así lo hagan necesario. Si EL USUARIO no atiende
                                    los lineamientos normativos relacionados con la devolución de los equipos, dentro de los términos establecido
                                    para ello,el cual es de 15 días hábiles , deberá cancelar el valor de dichos equipos según el valor comercial actual de la marca y modelo de equipo instalado al USUARIO.El Usuario que deba 1 o más meses de servicio será notificado ,
                                    de no recibir respuesta o pago en el tiempo de 8 días hábiles AG INGENIERIA podrá dar por terminada la prestación del servicio y proceder a recoger los mismos.
                                </small>
                            </P>

                        </div>
                    </div>
                    <div class="row py-5">
                        <div class="col">
                            <div class="notices">
                                <div>Aviso importante:</div>
                                <div class="notice">Los equipos instalados por AG INGENIERIA WIST son propiedad de la misma.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row py-5">
                        <div class="col">
                            <div class="zoekResultatLine">
                                <h2 class="zoekResultat">Firma del Cliente</h2>
                            </div>
                        </div>
                    </div>
                </main>
                <footer>
                    Ag Ingeneiria Wist 2019 - ventas@agingenieria.tech
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="bower_components/Popper/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        $('#printInvoice').click(function() {
            Popup($('.invoice')[0].outerHTML);

            function Popup(data) {
                window.print();
                return true; 
            }
        });
    </script>


</body>

</html>