<?php
// Id Empresa:  1
//     AG INGENIERIA - B
//     Omar Hernandez Diaz
// Id grupo Empresa: 1
//     Empresa Ag ingenieria A y B juntas
// //**Pasos para crear repetidor**//  Ej. Repetidor Yulieth 17.152 (Tiene dos saltos primero por acacias y luego por guamal)
// *1   Crear nuevo grupo de repetidor  (Table "repeater_subnets_group")  "27"  -> (Every User belong to only one repeater!)
// *2   Crear nuevo Segmento de Ip "123"  ppp0 ppp1 ppp2    **(Table "items_repeater_subnet_group" ->Unique Lan Segments! )
// *3   Crear nuevo servidor repetdior =>   **(table "vpn_tarjets") ! Here it define every server  ip
// *4   Actualizar la ip del afiliado (table afiliados) junto con su grupo Id(`repeater_subnets_group`) "27"
// *    "Montecristo(1), Retiro(2) , Red32(3)  table id-"aws-vpn-client" -> Here it define local static  ip on l2tp Client configuration conection to aws l2tp server  )"
// *   *****   1  -> Guamal(42.10)  2 ->  Retiro(42.11)  3 ->  Salcedo-Red 32(42.12)
// *5 Fill table "static_routes" and "static_route_steps" ("0"->"No saltos","1"->"primer.","2"->"Segundo..").. ojo por q   se pide el id pero del "items_repeater_subnet_group" (26->!37)
// *   
// Listo, actualiza el server aws ip route: php vpnAwsIpRoute.php -p"".....-p"......(ambas -p requeridas)
// Fianalmente se deben agregar las rutas estáticas a las rb para alcanzar las ip de los clientes en los repetidores.(Los archivos quedan en la carpeta html del servidor)
// 1AwsStaticRouteStep1.src          
// 1AwsStaticRouteStep2.src          
// 2AwsStaticRouteStep1.src
// pendiente Naalí Colón Po Switch rb.. repetidor
// Pendiente pasar ips de Yulieth La Vara por la 123
// pendiente crear repetidor ..Javier Pardo
// pendiente crear repetidor Opitas ..Milena sarmiento  117.13
// pendiene crear repetidor natalì colòn.
require("../PingTime.php");
require("../Client.php");
require("../Mkt.php");
require("../login/db.php");
require("../components/views/TemplateDark.php");
require("../components/views/Html.php");
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
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <title>Wisdev-Administrador ISP</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet" />
    <link rel="stylesheet" href="../css/fontello.css" />
    <link rel="stylesheet" href="../css/animation.css">
    <link rel="stylesheet" href="../bower_components/alertify/css/alertify.min.css" />
    <link rel="stylesheet" href="../bower_components/alertify/css/themes/default.min.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>

<body>
    <header>
        <div class="logo">
            <a href="main.php">
                <h1>Isp Experts</h1>
            </a>
            <h4>Monitoreo y administraciòn</h4>

        </div>
        <div class="header-box">
            <div class="user">
                <i class="icon-user"></i><span><?php echo "Hola ".$_SESSION['name'];?></span>
            </div>
            <div class="button-collapse">
                <button>☰</button>
            </div>
        </div>
    </header>
    <nav class="navTop">
        <ul>
            <li><a href="<?php if($_SESSION['role']!='tecnico')echo "registerPay.php";?>"><i
                        class="icon-money"></i>Registrar Pago</a></li>
            <li><a href="<?php if($_SESSION['role']!='tecnico')echo "transacciones.php";?>"><i
                        class="icon-print"></i>Transacciones</a></li>
            <li><a href="<?php if($_SESSION['role']!='tecnico')echo "reclist.php";?>"><i class="icon-money"></i>Formato
                    Recibo</a></li>
        </ul>
    </nav>
    <main id="app">
        <nav class="navLeft">
            <ul>
                <li class="selected"><a href="tick.php"><i class="icon-pinboard"></i><span>Tickets</span></a></li>
                <li><a href="<?php if($_SESSION['role']!='tecnico')echo "fact.php";?>"><i
                            class="icon-docs"></i><span>Facturas</span></a></li>
                <li><a href="<?php if($_SESSION['role']!='tecnico')echo "client.php";?>"><i
                            class="icon-users"></i><span>Clientes</span></a></li>
                <li><a href="<?php if($_SESSION['role']!='tecnico')echo "mktik.php";?>"><i
                            class="icon-network"></i><span>Mktik</span></a></li>
                <li><a href="<?php if($_SESSION['role']!='tecnico')echo "egr.php";?>"><i
                            class="icon-money"></i><span>Egresos</span></a></li>
                <li><a href="../login/logout.php"><i class="icon-logout"></i><span>Salir</span></a></li>
            </ul>
        </nav>
        <section>
            <div class="section-title">
                <img src="../public/img/support.png" alt="">
                <h1>ADMINISTRAR REPETIDORES</h1>
            </div>
            <div class=box-container>
                <div class="box box-new-ticket">
                    <div class="title">
                        <h3><i class="icon-user"></i> NUEVO REPETIDOR</h3> 
                    </div>
                    <div class="box-content">
                        <p>Crear nuevo grupo de repetidor  (Table "repeater_subnets_group")  "27"  -> (Every User belong to only one repeater!)</p>
                        <p>Repetidor disponible:<strong>ID: {{getIdRepeaterSubnetsGroup()}}</strong></p>
                        
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <div>
            <span>Isp Experts- Adminstraciòn Redes </span>
        </div> 
    </footer>
<script src="../bower_components/alertify/js/alertify.min.js"></script>
<script>
var app = new Vue({
    el: "#app",
    data: {
        url: "fetch/repeaterAPI.php",
        idRepeaterSubnetsGroup: "36",
       
    },
    methods: {
        getIdRepeaterSubnetsGroup: async ()=>{
            const response = await fetch(
                url+ new URLSearchParams({
                    foo: 'value',
                    bar: 2,
                    }),{
                method: 'GET', // *GET, POST, PUT, DELETE, etc.
                mode: 'cors', // no-cors, *cors, same-origin
                cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                headers: {
                'Content-Type': 'application/json'
                },
            });
        return response.json(); // parses JSON response into native JavaScript objects
        },
        
        validateIpAddress: function(data) {
            var ipformat =
                /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
            if (data != null) {
                if (data.match(ipformat)) {
                    return true;
                } else {

                    return false;
                }
            }
            return false;
        },
        validateMacAddress: function(data) {
            var regexp = /^(([A-Fa-f0-9]{2}[:]){5}[A-Fa-f0-9]{2}[,]?)+$/i;
            if (regexp.test(data)) {
                return true
            } else {
                return false
            }
        },

        
    },
    mounted() {
       this.getIdRepeaterSubnetsGroup()
    },
});
</script>
</body>

</html> 
