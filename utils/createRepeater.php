<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
		header('Location: ../login/index.php');
		exit;
	} else {
	$user = $_SESSION['username'];
	$name = $_SESSION['name'];
	$lastName = $_SESSION['lastName'];
	$role = $_SESSION['role'];
	$jurisdiccion = $_SESSION['jurisdiccion'];
	$idEmpresa = $_SESSION['empresa'];
    $idCajero = $_SESSION['idCajero'];
    $convenio=$_SESSION['convenio'];
}
if($_SESSION['role']=='tecnico'){
	header('Location: ../public/tick.php');
}
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
    <style>
        .box-content div{
            margin-top:2rem;
            background-color:#ECE0DE;
            padding: 1rem;
        }
        .box-content div p{
            margin-bottom:1rem;
            margin-top:1rem;
        }
        .box-content ul {
            margin-left:1rem;
            padding-left:1rem;
        }
        .success{
            color:#f7f7f7;
            background-color:#5cb85c;
            padding: .5rem;
            border-radius:5px;
            font-style:italic;
            font-weight:bold;
            margin-left:1rem;
            margin-right:.5rem;
        }
        .radio-group{
            margin-top:3rem;
        }
        .radio-group .label {
            color:#f7f7f7;
            background-color:#5bc0de;
            padding: .5rem;
            border-radius:5px;
            font-style:italic;
            font-weight:bold;
            margin-left:1rem;
            margin-right:.5rem;
            margin-bottom:1rem;
        }
        .button{
            color:#f7f7f7;
            background-color:#0275d8;
            padding:.5rem;
            margin-left:1rem;
            margin-right:.5rem;
            border-radius:5px;
            border: none;
        }
        .button:hover{
            font-size: larger;
        }
        .submitErrro{
            color:#f7f7f7;
            background-color:#d9534f;
            padding:.5rem;
            margin-left:1rem;
            margin-right:.5rem;
            border-radius:5px;
        }
        select {
        appearance: none;
        background-color: transparent;
        color:#404040;
        border: none;
        padding: 0 1em 0 0;
        margin: 0;
        font-family: inherit;
        font-size: inherit;
        cursor: inherit;
        line-height: inherit;
        }
        .steps{
            margin-top:1rem;
            padding:1rem;
            color:#404040;
        }
        .steps ul li {
            margin-bottom:1rem;
        }
        .active{
            color:#f7f7f7;
            background-color:#5cb85c;
            padding: .5rem;
            border-radius:5px;
            font-style:italic;
            font-weight:bold;
            margin-left:1rem;
            margin-right:.5rem;
            margin-bottom:1rem;
        }
    </style>
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
                        <form v-on:submit.prevent>
                            <div>
                                <p>*1-Crear nuevo grupo de repetidor  (Table "repeater_subnets_group") id , descripcion , date    "27"  -> (Every User belong to only one repeater!)</p>
                                <p v-if="!idRepeaterSubnetsGroup"><input class="button" type="button"  @click="requestIdSubnet()" value="Crear" ><i v-if="selectSpinSubnet" v-bind:class="{'animate-spin':selectSpinSubnet}" class="icon-spin6 "></i></p>
                                <p v-if="idRepeaterSubnetsGroup"><small class="success">success</small>Repetidor disponible:<strong>id-repeater-subnets-group: {{idRepeaterSubnetsGroup}}</strong></p>
                            </div>
                            <div v-if="idRepeaterSubnetsGroup">
                                <p>*2-Crear nuevo Segmento de Ip "xxx"  ppp0 ppp1 ppp2    **(Table "items_repeater_subnet_group" ->Unique Lan Segments! )</p>
                                <div class="radio-group">
                                        <ul>
                                            <li>SELECCIONE NOMBRE DEL REPETIDOR</li>
                                        </ul>
                                        <P>
                                            <label class="label"><input  v-model="repeaterName" type="text" placeholder="mínimo 10 caracteres" ></label><small class="submitErrro" v-if="nameErrorText">Error, {{inputNameErrorText}}</small>

                                        </P>
                                        <ul>
                                            <li>SELECCIONE A QUE RED ESTA CONECTADTO EL REPETIDOR <small>aws-vpn-client</small></li>
                                        </ul>
                                        <P>
                                            <label :class="{ active: picked==1, 'label': picked!=1 }" @click="radioSelected(1)">Vpn Montecristo</label>
                                            <label :class="{ active: picked==2, 'label': picked!=2 }" @click="radioSelected(2)">Vpn Retiro</label>
                                            <label :class="{ active: picked==3, 'label': picked!=3 }" @click="radioSelected(3)">Vpn Red 32</label>

                                        </P>
                                        
                                    
                                </div>
                                <p v-if="!createdNewIpSegment">
                                    <input class="button"  type="submit" @click="createSegmentButtom()" value="Crear">
                                    <i v-if="selectSpin" v-bind:class="{'animate-spin':selectSpin}" class="icon-spin6 "></i>
                                    <small class="submitErrro" v-if="radioError">Error, {{radioErrorText}}</small>
                                </p>
                                <p v-if="createdNewIpSegment"><small class="success">success</small>Nuevo segmento de red alistado para  {{repeaterName}} es por la <strong>{{newIpSegment}}</strong> </p>
                                <p v-if="tableItemSubnetCreated"><small class="success">success</small><small> Tabla items_repeater_subnet_group creada con éxito!, nuevo id: {{idItemsRepeaterSubnetGroup}}</small></p>
                            </div>
                            <div v-if="createdNewIpSegment"> 
                                <p>*3- Crear nuevo servidor repetdior =>   **(table "vpn_tarjets") ! Here it define every server  ip</p>
                                <div class="radio-group">
                                        <ul>
                                            <li>SELECCIONE DIRECCION IP DEL REPETIDOR EN CAMPO</li>
                                        </ul>
                                        <P>
                                            <label><input  v-model="ipServer" type="text"  ></label><small class="submitErrro" v-if="ipErrorText">Error, {{inputIpErrorText}}</small>

                                        </P>
                                        <ul>
                                            <li>SELECCIONE A QUE EMPRESA CREA EL  REPETIDOR EN CAMPO</li>
                                        </ul>
                                        <P>
                                            <label class="label"><select v-model="empresa" >
                                                <option value="1">AG INGENIERIA OMAR</option>
                                                <option value="2">AG INGENIERIA WILLIAN</option>
                                                <option value="2">TURBO RED</option>
                                            </select></label>
                                        </P>
                                        
                                        
                                </div>
                                <p v-if="createdNewIpSegment">
                                    <input class="button"  type="submit" @click="createServerButtom()" value="Crear vpn_tarjets">
                                    <i v-if="selectSpin" v-bind:class="{'animate-spin':selectSpin}" class="icon-spin6 "></i>
                                </p>
                                <p v-if="createdNewServer"><small class="success">success</small>Table vpn_targets creada con éxito ..el id es:{{idVpnTarget}}</p>
                            </div>
                            <div v-if="createdNewIpSegment">
                                <p>*4-Actualizar la ip del afiliado (table afiliados) junto con su grupo Id(`repeater_subnets_group`)</p>
                            </div>
                            <div v-if="createdNewIpSegment">
                                <p>*5-Fill table "static_routes" and "static_route_steps" ("0"->"No saltos","1"->"primer.","2"->"Segundo..").. ojo por q   se pide el id pero del "items_repeater_subnet_group"</p>
                                <div style="background-color:#e8c9c9;" class="steps">
                                            <p>TABLA : `static_routers`</p>
                                            <ul>
                                                <li>id , ip-segment, descripcion , id-items-repeater-subnet-group</li>
                                                <li>SE DEBE CREAR ESTA TABLE Y LUEGO EXTRAE EL id y luego se almacena convertido  en id-static-route</li>
                                            </ul>
                                            <p v-if="StaticRoutersCreated"><small class="success">success</small>Table static_routes creada con éxito ..el id es:<strong>{{idStaticRoutes}}</strong></p>
                                </div>
                                <div style="background-color:#e8c9c9;" class="steps">
                                            <p>TABLA : `static_route_steps` : id, step, id-static-route , local-server-ip , dst-ip-address , gateway , id-aws-vpn-client </p>
                                            <ul v-for="awsSelected in awsSelecteds" >
                                                <li>step:{{awsSelected.number}} id-static-route:? local-server-ip:{{awsSelected.localServerIp}}  dst-ip-address: {{dstAddress}}  gateway: {{!awsSelected.gateway?gatewayCatched:awsSelected.gateway}} id-aws-vpn-client:{{picked}} </li>
                                            </ul>
                                            <p v-if="StaticRouteStepsCreated"><small class="success">success</small>Table(s) static_route_steps creada con éxito </p>
                                </div>
                            </div>
                        </form> 
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
        endpoint: "fetch/repeaterAPI.php?",
        StaticRouteStepsCreated:false,
        idStaticRoutes:null,
        StaticRoutersCreated:false,
        tableItemSubnetCreated:false,
        selectSpinSubnet:false,
        idRepeaterSubnetsGroup: null,
        createdNewIpSegment: null,
        newIpSegment: null,
        dstAddress:null,
        selectSpin: null,
        picked:null,
        radioError:false,
        nameErrorText:false,
        inputNameErrorText:"debe ingresar un nombre de repetidor minimo 10 caracetes",
        radioErrorText:"debe seleccionar a que servidor está el repetidor",
        inputIpErrorText:"debe ingresar una dirección IP válida y privada",
        repeaterName:"",
        ipServer:null,
        empresa:"1",
        ipErrorText:false,
        createdNewServer:false,
        awsVpnClients:{  
                        1:[
                            {
                            "steps":[
                                    {
                                        "number":"1",
                                        "localServerIp":"192.168.65.1",
                                        "gateway":"192.168.65.7",
                                    },
                                    {
                                        "number":"2",
                                        "localServerIp":"192.168.21.1"
                                    },
                            ],
                            }
                        ],
                        2:[
                            {
                            "steps":[
                                    {
                                        "number":"1",
                                        "localServerIp":"192.168.30.1",
                                    },
                                    
                            ],
                            }
                        ],
                        3:[
                            {
                            "steps":[
                                    {
                                        "number":"1",
                                        "localServerIp":"192.168.32.1",
                                    },
                                    
                            ],
                            }
                        ],
                        
                        
                    },
        gatewayCatched:null,
        awsSelecteds:{},
        idItemsRepeaterSubnetGroup:null,
        idVpnTarget:null,            
                        
    },
    methods: { 
        createTablestaticRouteSteps:async function(number,idStaticRoutes,localServerIp,dstAddress,gateway,idAwsVpnClient){
            console.log(`info para crear steps: ${number}  ${idStaticRoutes}  ${localServerIp}  ${dstAddress}  ${gateway}  ${idAwsVpnClient}`)
            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers : { 
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({
                    option:"staticRouteSteps",
                    step:number,
                    idStaticRoutes,
                    localServerIp,
                    dstAddress,
                    gateway,
                    idAwsVpnClient
                })
            })
            let promiseResponse = await response.json();
            if(promiseResponse.id!=0)this.StaticRouteStepsCreated=true
        },
        staticRouteSteps:function(){
            const myObj = this.awsSelecteds
            for (let x in myObj) {
                let number=myObj[x].number
                let idStaticRoutes=this.idStaticRoutes
                let localServerIp=myObj[x].localServerIp
                let dstAddress=this.dstAddress
                let gateway=!myObj[x].gateway?this.gatewayCatched:myObj[x].gateway
                let idAwsVpnClient=this.picked
                this.createTablestaticRouteSteps(number,idStaticRoutes,localServerIp,dstAddress,gateway,idAwsVpnClient)
            }
            
        },
        createTableStaticRoutes: async function(){
            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers : { 
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({
                    option:"staticRoutes",
                    newIpSegment:this.newIpSegment,
                    repeaterName:this.repeaterName,
                    idItemsRepeaterSubnetGroup:this.idItemsRepeaterSubnetGroup,

                })
            })
            let promiseResponse = await response.json();
            this.idStaticRoutes=promiseResponse.id
            this.StaticRoutersCreated=true
            this.staticRouteSteps()
        },
        createTableVpnTargets:async function(){
            //``, ``, `server-name`, `server-ip`, ``, ``, `id-repeater-subnet-group`, ``, `id-empresa`, ``, ``
            let idEmpresa=<?=$idEmpresa?>;
            console.log("voya crear la table VpnTargets cin idEmpresa:"+idEmpresa)
            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers : { 
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({
                    option:"vpnTargets",
                    repeaterName:this.repeaterName,
                    ipServer:this.ipServer,
                    idRepeaterSubnetsGroup:this.idRepeaterSubnetsGroup,
                    idEmpresa:idEmpresa,
                })
            })
            let promiseResponse = await response.json();
            this.idVpnTarget=promiseResponse.id
            console.log("nuevo vpn_target creado , el id es:"+promiseResponse.id)
            this.createTableStaticRoutes()
        },
        createtableItemSubnet: async function(){
            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers : { 
                    'Accept': 'application/json' 
                },
                body: new URLSearchParams({    
                    option:"itemRepeater", 
                    idRepeaterSubnetsGroup:this.idRepeaterSubnetsGroup,
                    newIpSegment:this.newIpSegment,
                    repeaterName:this.repeaterName,   
                    picked:this.picked,
                })
            }) 
            let promiseResponse = await response.json();
            this.idItemsRepeaterSubnetGroup=promiseResponse.id     
            this.tableItemSubnetCreated=true
        }, 
        requestIdSubnet:function(){
            this.selectSpinSubnet=true
            this.getIdRepeaterSubnetsGroup()   
        },
        createServerButtom: function(){
                if(this.validateIpAddress(this.ipServer)){
                    if (this.isPrivateIP(this.ipServer)){
                        this.selectSpin=true
                        console.log("ip valida")
                        this.ipErrorText=false
                        this.gatewayCatched=this.ipServer
                        this.createTableVpnTargets()
                        this.selectSpin=false
                        this.createdNewServer=true 
                    }else{
                        console.log("ip NO es privada")
                    }
                }else{
                    console.log("ip NO valida")
                    this.ipErrorText=true
                    this.selectSpin=false
                    
                }
        },
        radioSelected:function(value){
            this.picked=value
            this.radioError=false
            this.awsSelecteds=this.awsVpnClients[value][0]['steps']
            
        },
        getNewIpSegment: async function(){
            const response = await fetch(this.endpoint + new URLSearchParams({option: 'ipSegment'}),
                {
                    method: 'GET', 
                    headers: {
                    'Content-Type': 'application/json'
                    },
                });
            let promiseResponse = await response.json();
            this.newIpSegment=promiseResponse.ipSegment
            this.dstAddress="192.168."+this.newIpSegment+".0/24" 
            this.createdNewIpSegment=true
            this.createtableItemSubnet()
            this.selectSpin=false

        },
        getIdRepeaterSubnetsGroup: async function(){
            const response = await fetch(this.endpoint + new URLSearchParams({option: 'subnet'}),
                {
                    method: 'GET', 
                    headers: {
                    'Content-Type': 'application/json'
                    },
                });
            promiseResponse = await response.json();
            this.idRepeaterSubnetsGroup=promiseResponse.id==0?0:promiseResponse.id
            //this.selectSpin=false
            this.selectSpinSubnet=false

        },
        createSegmentButtom: function(){
            if(this.picked && this.repeaterName.length>4){
                this.selectSpin=true
                this.nameErrorText=false
                this.radioError=false
                console.log("Create segment para el nuevo repetidor "+this.idRepeaterSubnetsGroup)
                console.log("Vpn seleccionado es "+this.picked)
                this.getNewIpSegment()
            }else{
                console.log("repeaterName vale"+this.repeaterName)
                this.radioError=!this.picked?true:false
                this.nameErrorText=this.repeaterName.length<=4?true:false
                this.selectSpin=false
            }
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
        isPrivateIP:function (ip) {
            var parts = ip.split('.');
            return parts[0] === '10' || 
            (parts[0] === '172' && (parseInt(parts[1], 10) >= 16 && parseInt(parts[1], 10) <= 31)) || 
            (parts[0] === '192' && parts[1] === '168');
        },
    },
    mounted() {
        
        
       
    },
});
</script>
</body>

</html> 
