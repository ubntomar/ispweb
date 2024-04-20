<!DOCTYPE html>
<html lang="en">
    <?
    require("/var/www/ispexperts/login/db.php");
    include("/var/www/ispexperts/utils/InvoiceToEmail.php");
    $invoice=new InvoiceToEmail($server, $db_user, $db_pwd, $db_name,"/var/www/ispexperts");
    $mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
    if ($mysqli->connect_errno) echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    ?>
    <head>
        <?echo $invoice->htmlHead();?>
    </head>
    <body>
        <?php
        if ($_GET['idc']) {
            $id = mysqli_real_escape_string($mysqli, $_REQUEST['idc']);
            $mons = array(1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre");
            $date = getdate();
            $month = date('n');
            $month_name = $mons[$month];
            $month_name2 = $mons[$month + 1];
            $fecha_recibo = $date['year'] . '/' . $date['mon'] . '/' . $date['mday'];
            $date = date("d/m/Y");
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
            $cont="1354";
            $descripcion="Internet Banda Ancha";
            $velocidad="10";
            echo $invoice->htmlBody($cliente,$direccion,$ciudad,$departamento,$email,$date,$cajero,$cont,$month_name,$descripcion,$velocidad,$pago);
        }

        //search id-formato-factura for obtain company info. 
        //id-formato-factura=0 means we dont send email .  
        //id-formato-factura=1 means we  send "FACTURA" de pago email
        //id-formato-factura=2 means we  send "RECIBO DE PAGO" email
        // $FACTURA=1;
        // $RECIBO=2;
        // $sql="SELECT * FROM `afiliados` WHERE `id-formato-factura` = '$FACTURA'  AND  `activo`='1' AND `eliminar`='0' ";

        ?>



        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="../../bower_components/Popper/popper.min.js"></script>
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


