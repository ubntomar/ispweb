<?
class InvoiceToEmail {
    private $mysqli;
    private $path=13;
    function __construct($server, $db_user, $db_pwd, $db_name , $absolutePath){
        $this->mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
		if ($this->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: ";
            return false;
        }	
        $this->path=$absolutePath;
		mysqli_set_charset($this->mysqli,"utf8");
		date_default_timezone_set('America/Bogota'); 
        setlocale(LC_MONETARY,"es_CO");
        
    }
    function htmlHead(){
        return  <<<GTX
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Generador de Recibos</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet">
        <link rel="stylesheet" href="../../bower_components/alertify/css/alertify.min.css" />
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css">
        <link rel="stylesheet" href="../../css/invoice.css" />
        GTX;
            
        
    }
    function htmlBody($cliente,$direccion,$ciudad,$departamento,$email,$date,$cajero,$cont,$periodo,$descripcion,$velocidad,$pago){
        return <<<INVOICE
        <div id="invoice">
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
                                <div>(57+)3147654655</div>
                                <div>http://146.71.79.111</div>
                                <div>ag.ingenieria.wist@gmail.com</div>
                            </div>
                        </div>
                    </header>
                    <main>
                        <div class="row contacts">
                            <div class="col invoice-to">
                                <div class="text-gray-light">Cliente:</div>
                                <h3 class="to">$cliente</h3>
                                <div class="address">$direccion $ciudad $departamento, CO</div>
                                <div class="email"><a href="mailto:john@example.com">$email</a></div>
                                <div class="address">$telefono</div>
                            </div>
                            <div class="col invoice-details">
                                <h5 class="invoice-id">FACTURA DE VENTA</h5>
                                <h4 class="name">SN1688-GMC</h4>
                                <div class="date">Fecha de Factura: $date</div>
                                <div class="date">Vence: $date</div>
                                <div class="date">Envia: $cajero</div>
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
                                <tr>
                                    <td class="no">SA$cont</td>
                                    <td class="text-left">
                                        <h3>
                                        $periodo
                                        </h3>
                                        $descripcion <strong>$velocidad</strong>Mbps
                                    </td>
                                    <td class="unit">$$pago</td>
                                    <td class="qty">1</td>
                                    <td class="total">$$pago</td> 
                                </tr>                                           
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">SUBTOTAL</td>
                                    <td>$$pago</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">DESCUENTO</td>
                                    <td>$0</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">TOTAL</td>
                                    <td>$$pago</td>
                                </tr>
                            </tfoot>
                        </table>
                        
                    </main>
                    <footer>
                        Ag Ingeneiria Wist 2023 - ag.ingenieria.wist@gmail.com
                    </footer>
                </div>
                <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                <div></div>
            </div>
        </div>
        INVOICE;


    }





}
?>

