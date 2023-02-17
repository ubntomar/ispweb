<?php 
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true){
		header('Location: ../login/index.php');
		exit;
}
else{
    $user=$_SESSION['username'];
    if($_SESSION['role']=='cajero'){
        header('Location: registerPay.php');
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <title>DevXm-Administrador ISP</title>
    <link rel="stylesheet" href="../css/estilos.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="../bower_components/alertify/css/alertify.min.css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css" />
    <!-- <link rel="stylesheet" href="bower_components/alertify/css/themes/default.min.css" /> -->
    <link rel="stylesheet" href="../css/fontello.css" />
    <link rel="stylesheet" href="../css/dataTables.checkboxes.css" />
    <link rel="stylesheet" href="../css/animation.css">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>

<body>
    <div class="container-fluid px-0">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top   ">
            <div class="container img-logo ">
                <!-- <img src="img/wisp.png" /> -->
                <!-- Nos sirve  para agregar un logotipo al menu -->
                <a href="main.php" class="navbar-brand link-border">DevXm</a>

                <!-- Nos permite usar el componente collapse para dispositivos moviles  -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar"
                    aria-controls="navbar" aria-expanded="false" aria-label="Menu de Navegacion">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse " id="navbar">
                    <ul class="navbar-nav  navclass ">
                        <li class="nav-item ">
                            <a href="#" class="nav-link"><i class="icon-money"></i>Cierre de caja
                                <span class="sr-only">(Actual)</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php if($_SESSION['role']!='tecnico')echo "registerPay.php";?>"
                                class="nav-link "><i class="icon-money"></i>Registrar Pago</a>
                        </li>
                        <li class="nav-item  ">
                            <a href="<?php if($_SESSION['role']!='tecnico')echo "transacciones.php";?>"
                                class="nav-link  "><i class="icon-print "></i>Transacciones</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="icon-mail"></i>Contacto</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php if($_SESSION['role']!='tecnico')echo "reclist.php";?>" class="nav-link "><i
                                    class="icon-money"></i>Formato Recibo</a>
                        </li>
                    </ul>
                    <div class="ml-auto">
                        <ul class="nav navbar-nav   ">
                            <li class="nav-item ">
                                <a class="nav-link disabled text-white "><i
                                        class="icon-user"></i><?php echo "Hola ".$_SESSION['name']; ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div class="row">
            <div class="barra-lateral  col-sm-auto ">
                <nav class="menu d-flex d-sm-block justify-content-center flex-wrap">
                    <a href="tick.php"><i class="icon-pinboard"></i><span>Tickets</span></a>
                    <a href="<?php if($_SESSION['role']!='tecnico')echo "fact.php";?>"><i
                            class="icon-docs-1"></i><span>Facturas</span></a>
                    <a href="<?php if($_SESSION['role']!='tecnico')echo "client.php";?>"><i
                            class="icon-users"></i><span>Clientes</span></a>
                    <a href="mktik.php"><i class="icon-network"></i><span>Mktik</span></a>
                    <a href="<?php if($_SESSION['role']!='tecnico')echo "egr.php";?>"><i
                            class="icon-money"></i><span>Egresos</span></a>
                    <a href="../login/logout.php"><i class="icon-logout"></i><span>Salir</span></a>
                </nav>
            </div>

            <main class="main col">
                <div class="row" id="app">
                    <div class="columna col-lg-7">
                        <div class=" nuevo_contenido p-2 border border-info rounded">
                            <div class="d-flex justify-content-center">
                                <h3 class="titulo">Estado de conexión de Clientes</h3>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="d-flex align-items-center">
                                    <div></div>
                                    <div><input type="text" value="" id="search" placeholder="1 Nombre 1 Apellido o IP"
                                            class="form-control form-control-sm ml-1" v-model="searchString"></div>
                                    <div><button class="icon-search form-control form-control-sm "
                                            v-on:click="searchFn"></button>
                                    </div>
                                    <i v-if="getUserSpin" v-bind:class="{'animate-spin':getUserSpin}"
                                        class="icon-spin6 "></i>
                                </div>
                                <div>
                                    <select class="form-control form-control-sm" v-model="searchOption"
                                        v-on:change="getSelected">
                                        <option selected>Todos</option>
                                        <option>Cortado</option>
                                        <option>Ping OK</option>
                                        <option>Ping Down</option>
                                    </select>
                                    <i v-if="selectSpin" v-bind:class="{'animate-spin':selectSpin}"
                                        class="icon-spin6 "></i>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped  table-hover table-sm">
                                    <thead>
                                        <th>Nombre {{searchOption}} <i class="icon-users"></i></th>
                                        <th>IP Address <i class="icon-exchange"></i> </th>
                                        <th>Ping <i class="icon-clock"></i></th>
                                        <th> <i class="icon-signal"></i></th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="cliente in clientes"
                                            v-bind:class="{'table-warning':!cliente.pingStatus}"
                                            v-if="cliente.counter<=totalRows">
                                            <td class="font-weight-bold">
                                                <div>
                                                    {{cliente.name}}-{{cliente.id}}
                                                    <div>
                                                        <small>{{cliente.direccion}}</small>
                                                    </div>
                                                </div>
                                                <div class="timeElapsded border  rounded d-flex justify-content-center pl-1 font-italic w-50"
                                                    v-bind:class="{'border-danger':cliente.suspender}">
                                                    <small>{{cliente.suspender}}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="ip">
                                                    <div class="input-ip">
                                                        <input type="text" v-model="cliente.ipAddress"
                                                            v-on:keyup="cliente.ipText='',cliente.validIp=true">
                                                        <button class="border border-rounded"
                                                            v-on:click="updateIp(cliente)"><i
                                                                v-bind:class="{'animate-spin':cliente.ipIconSpin}"
                                                                class="icon-spin6 "></i>
                                                        </button>
                                                    </div>
                                                    <div class="server-info">
                                                        <small
                                                            v-bind:class="{'border-danger':!cliente.validIp,'border-success':cliente.ipText=='Actualizado con Exito'}"
                                                            class="m-1 p-1 border  border-rounded  font-italic">{{cliente.ipText}}
                                                        </small>
                                                    </div>
                                                    <div class="server-info">
                                                        <small>Server Ip: <a v-bind:href="'http://'+cliente.serverIp"
                                                                target="_blank">{{cliente.serverIp}} (señal:{{signalOfRepeater}})</a>
                                                                <button class="border border-rounded" v-on:click="pingtoIp(cliente.serverIp)">
                                                                    <i v-bind:class="{'animate-spin':pingToServerSpin}" class="icon-spin6 "></i>
                                                                </button>
                                                        </small>
                                                        <small v-bind:class=" {'text-success':ipTargetStatus=='up','text-danger':ipTargetStatus=='down'} ">time:{{ipTargetTime}} ms {{ipTargetStatus}}</small>

                                                    </div>
                                                    <div class="server-info" v-if="serverType">
                                                        <small>Antena Servidor: <a v-bind:href="'http://'+cliente.ipAddress"
                                                                target="_blank">{{serverType}}</a></small>

                                                    </div>
                                                    <div class="server-info">
                                                        <small>Signal: <a v-bind:href="'http://'+cliente.ipAddress"
                                                                target="_blank">{{cliente.signal}}</a></small>

                                                    </div>
                                                    <div class="dst-nat">
                                                        <small>Dst-nat:</small><small
                                                            :class="{'text-primary':cliente.dstnatResponse=='Actived-Mikrotik','text-danger':cliente.dstnatResponse=='Inactive'}">&nbsp;{{cliente.dstnatResponse}}</small>
                                                        <div class="dst-nat-target">
                                                            <small>Dst-nat target:&nbsp;</small><small
                                                                class="text-primary">{{cliente.dstnatTarget}}</small>
                                                        </div>
                                                    </div>
                                                    <div class="router">
                                                        <small>Router: <a target="_blank"
                                                                :href=" 'http://'+cliente.ipAddress+':'+cliente.port ">{{cliente.ipAddress}}:</a></small><small
                                                            :class="{'text-success':cliente.portStatus=='Open','text-danger':cliente.portStatus=='Closed'}">
                                                            {{cliente.port}}</small>
                                                    </div>
                                                    <div class="arp-list">
                                                        <small>Arp list for :&nbsp;</small><small
                                                            class="text-success">{{cliente.arpTarget}}</small>
                                                        <ol>
                                                            <li :class="{'text-success':cliente.ipAddress==item,'font-weight-bold':cliente.ipAddress==item}"  v-for="item in cliente.arp" :key="">
                                                                {{ item }}
                                                            </li>
                                                        </ol>
                                                    </div>
                                                    <div class="queue">
                                                        <small>Queue:&nbsp;</small><small :class="{'font-weight-bold':true,'text-success':cliente.queue=='Success','text-danger':cliente.queue=='Fail' }" >{{cliente.queue}} </small>

                                                    </div>
                                                </div>



                                            </td>
                                            <td><strong class="font-italic"
                                                    v-if="cliente.responseTime"><small>{{cliente.responseTime}}
                                                    </small><small
                                                        v-if="!isNaN(cliente.responseTime)">ms</small></strong>
                                                <div class="timeElapsded border border-info rounded d-flex justify-content-center px-1 font-italic"
                                                    v-if="cliente.elapsedTime">
                                                    <small v-if="cliente.elapsedTime!='Hoy'"> Hace
                                                        {{cliente.elapsedTime}}</small>
                                                    <small v-if="cliente.elapsedTime=='Hoy'">
                                                        {{cliente.elapsedTime}}</small>
                                                </div>
                                            </td>
                                            <td class=""
                                                v-bind:class="{'text-success':cliente.pingStatus=='up','text-danger':cliente.pingStatus=='down'}">
                                                {{cliente.pingStatus}}<i class=""
                                                    v-bind:class="{'icon-smile':cliente.pingStatus=='up','icon-emo-unhappy':cliente.pingStatus=='down'}">
                                                    <p><button class="border border-rounded icon-arrows-ccw"
                                                            v-on:click="setPing(cliente)"></button></p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div><button class="bg-success text-white border border-0 p-2">
                                        <i class="icono icon-ok"></i>{{totalRows}} resultados
                                    </button></div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="d-inline  mx-1">
                                    <input class="rounded-circle border-0 h2" type="button" name="previus" value='<'>
                                </div>
                                <div class="d-inline mx-1">
                                    <input v-on:click="getUser()" class="rounded-circle border-0 h2" type="button"
                                        id='nexxt' value='>'>
                                </div>
                            </div>

                        </div>
                        <div class="mt-2 mb-3 nuevo_contenido p-2 border border-info rounded">
                            <div class="d-flex justify-content-center">
                                <h3 class="titulo">Repetidores actualmente</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table"> 
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Nombre de Repetidor</th>
                                            <th>Ip de Repetidor</th>
                                            <th>Lan de los clientes</th>
                                            <th>fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="repeater in repeaters" >
                                            <td><small>{{repeater.id}}</small></td> 
                                            <td><small>{{repeater.serverName}}</small></td>  
                                            <td>{{repeater.serverIp}}</td>
                                            <td  ><strong v-if="repeater.ping" v-bind:class="{'bg-success':'repeater.ping','text-light':'repeater.ping','p-2':'repeater.ping'}" >O.K</strong> {{repeater.ipSegment}} </td>
                                            <td>{{repeater.fecha}}</td> 
                                        </tr>
                                    </tbody>
                                </table>
                                
                            </div> 
                        </div>
                    </div>

                    <div class="columna col-lg-5">
                        <div class="widget estadisticas">
                            <div class="contenedor d-flex flex-wrap">
                                <div class="caja">
                                    <h3 class="titulo">Ping Test</h3>
                                    <label class="text-white bg-secondary border border rounded mx-1 px-1"
                                        for="ipAddress">Ip</label>
                                    <input v-model="ipAddressInput" v-on:click="inputPingClick()" maxlength="15"
                                        size="11" id="ipAddress" placeholder="">
                                    <button v-on:click="pingToIpButtonClick()" class="border border-rounded "><i
                                            v-bind:class="{'animate-spin':spinIcon}" class="icon-spin6 "></i></button>
                                    <div v-if="pingDataError" class="d-flex justify-content-center">
                                        <div class="border rounded bg-danger">
                                            <span class="  p-1 text-light"><i class="icon-attention"></i>Error: Invalid
                                                Ip Address.
                                            </span>
                                        </div>
                                    </div>
                                    <div v-if="pingSuccess=='ok'" class="d-flex justify-content-center">
                                        <div class="border rounded bg-success">
                                            <span class="  p-1 text-light"><i class="icon-ok"></i> Response Time:
                                                {{pingSuccessTime}} ms</span>
                                        </div>
                                    </div>
                                    <div v-if="pingSuccess=='no'" class="d-flex justify-content-center">
                                        <div class="border rounded bg-info">
                                            <span class="  p-1 text-light"><i class="icon-ok"></i> No Ip response,
                                                Disponible para uso.</span>
                                        </div>
                                    </div>
                                    <div v-if="pingSuccess=='serverError'" class="d-flex justify-content-center">
                                        <div class="border rounded bg-danger">
                                            <span class="  p-1 text-light"><i class="icon-ok"></i> No Server
                                                response,try it later.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget estadisticas">
                            <div>
                                <div class="title-caja text-light  m-o bg-secondary"><span>RED MONTECRISTO</span></div>
                            </div>
                            <div class="contenedor d-flex flex-wrap">
                                <div class="caja">
                                    <div class="d-flex justify-content-center mb-1">
                                        <h3 class="titulo">Ip disponibles</h3>
                                        <button v-on:click="ipListBox1reload()" class="ml-1 border border-rounded "><i
                                                v-bind:class="{'animate-spin':spinIconBox1}" class="icon-spin6 "></i>
                                        </button>
                                    </div>
                                    <div v-for="ip in ipListBox1" class="d-flex justify-content-center">
                                        <div class="border rounded bg-info">
                                            <span class="  p-1 text-light">{{ip}}
                                            </span>
                                            <i class="icon-ok"></i>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="widget estadisticas">
                            <div>
                                <div class="title-caja text-light  m-o bg-secondary"><span>RED EL RETIRO</span></div>
                            </div>
                            <div class="contenedor d-flex flex-wrap">
                                <div class="caja">
                                    <div class="d-flex justify-content-center mb-1">
                                        <h3 class="titulo">Ip disponibles</h3>
                                        <button v-on:click="ipListBox2reload()" class="ml-1 border border-rounded "><i
                                                v-bind:class="{'animate-spin':spinIconBox2}" class="icon-spin6 "></i>
                                        </button>
                                    </div>
                                    <div v-for="ip in ipListBox2" class="d-flex justify-content-center">
                                        <div class="border rounded bg-info">
                                            <span class="  p-1 text-light">{{ip}}
                                            </span>
                                            <i class="icon-ok"></i>
                                        </div>
                                    </div>
                                    <div class="widget estadisticas text-left ">
                                        <div>
                                            <div class="title-caja text-light  mt-2 bg-secondary"><span>VOLTAGE HEALTH</span>
                                            <button v-on:click="voltageReload()" class="ml-1 border border-rounded "><i
                                                v-bind:class="{'animate-spin':spinIconVoltage}" class="icon-spin6 "></i>
                                            </button>
                                        </div>
                                        </div>
                                        <div class="p-2"> 
                                            <div>
                                                <i class="icon-network"></i><span>  {{ipVoltageBox2.dc}} Volts</span>
                                            </div>
                                            <div>
                                                <i class="icon-network"></i><span>   {{ipVoltageBox2.rele?"Charger Battery ON ":"Charger Battery OFF"}}</span>
                                            </div>
                                            <div>
                                                <i class="icon-network"></i><span>   {{ipVoltageBox2.ac}} AC/DC Volts</span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget estadisticas">
                            <div>
                                <div class="title-caja text-light  m-o bg-secondary"><span>RED ORLANDO</span></div>
                            </div>
                            <div class="contenedor d-flex flex-wrap">
                                <div class="caja">
                                    <div class="d-flex justify-content-center mb-1">
                                        <h3 class="titulo">Ip disponibles</h3>
                                        <button v-on:click="ipListBox3reload()" class="ml-1 border border-rounded "><i
                                                v-bind:class="{'animate-spin':spinIconBox3}" class="icon-spin6 "></i>
                                        </button>
                                    </div>
                                    <div v-for="ip in ipListBox3" class="d-flex justify-content-center">
                                        <div class="border rounded bg-info">
                                            <span class="  p-1 text-light">{{ip}}
                                            </span>
                                            <i class="icon-ok"></i>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="container-fluid 	">
        <div class="row">
            <div class="col text-light bg-dark py-2 d-flex justify-content-center footer-text">
                <p>
                    Copyright ©2014-2017 DevXm-Administrador ISP -
                    <small>All Rights Reserved.</small>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col text-light bg-dark  d-flex justify-content-center">
                <p>
                    <i class="icon-facebook-official"></i><i class="icon-twitter-squared"></i>
                </p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="../bower_components/alertify/js/alertify.min.js"></script>
    <script src="../bower_components/AutoFormatCurrency/simple.money.format.js"></script>
    <script src="../js/dataTables.checkboxes.min.js"></script>
    <script>
    var app = new Vue({
        el: "#app",
        data: {
            mikrotikEndpoint: "../utils/fetch/mikrotikAPI.php?option=repeaterSignal",
            ubiquitiEndpoint: "../utils/fetch/ubiquitiAPI.php?option=repeaterSignal",
            serverType:null,
            signalOfRepeater:null,
            stat: false,
            cont: "0",
            thename: "",
            id: "1",
            clientes: [],
            searchString: "",
            searchOption: "Todos",
            totalRows: "",
            pingDataError: false,
            pingSuccess: "waiting",
            ipAddressInput: "",
            pingSuccessTime: "",
            spinIcon: false,
            ipListBox1: [],
            spinIconBox1: false,
            ipListBox2: [],
            ipVoltageBox2: {
                "dc":"",
                "rele":"",
                "ac":""
            },
            spinIconBox2: false,
            spinIconVoltage: false,
            ipListBox3: [],
            spinIconBox3: false,
            selectSpin: false,
            getUserSpin: false,
            repeaters:[] ,
            ipTargetTime:null,
            ipTargetStatus:null,
            pingToServerSpin:false,
        },
        methods: {
            getRepeaterList:async function(){
                
                const endPoint=`../controller/axios/repeaterApi.php?option=getRepeaterList`
                let res = await axios.get(endPoint);
                let data = res.data;
                //console.log(data);
                this.repeaters=data
            },
            getUser: function() {
                return new Promise((resolve, reject) => {
                    axios.get('../fetchUsers.php', {
                        params: {
                            id: this.id,
                            searchString: this.searchString,
                            searchOption: this.searchOption

                        },timeout: 30000
                    }).then(response => {
                         //console.log(response)
                        this.clientes = response.data
                        //console.log(JSON.stringify(this.clientes))
                        this.totalRows = response.data.length - 1
                        this.selectSpin = false
                        this.getUserSpin = false
                        //console.log("voya hacer ping al servidor"+response.data[0].serverIp)
                        if(response.data[0].serverIp){
                            this.pingtoIp(response.data[0].serverIp)
                            this.setPing(response.data[0])
                        }
                        resolve("ok")
                    }).catch(e => {
                        this.selectSpin = false
                        this.getUserSpin = false
                        //console.log('error' + e) 
                        //reject(e) 
                    })
                    this.ipTargetTime=null
                    this.ipTargetStatus=null
                })
            },
            updateIp: function(data) {
                data.ipText = "Actualizando..."
                data.ipIconSpin = true
                let idAfiliado = data.id
                let ipAddress = data.ipAddress
                var bodyFormData = new FormData();
                bodyFormData.append('idRow', idAfiliado);
                bodyFormData.append('ipRow', ipAddress);
                if (this.validateIpAddress(ipAddress)) {
                    axios({
                            method: "post",
                            url: "../fetchUsers.php",
                            data: bodyFormData
                        })
                        .then(function(response) { 
                            ////console.log(response);
                            data.ipText = "Actualizado  con éxito(G-" + response.data.idGroup + ")";
                            data.dstnatResponse = response.data.dstnatResponse
                            data.dstnatTarget = response.data.dstnatTarget
                            data.ipAddress = response.data.ipAddress
                            data.port = response.data.port
                            data.portStatus = response.data.portStatus
                            data.arpTarget = response.data.arpTarget
                            data.ipIconSpin = false
                        })
                        .catch(function(response) {
                            //handle error
                            ////console.log(response);
                            data.ipIconSpin = false
                        });

                } else {
                    data.validIp = false
                    data.ipIconSpin = false
                    data.ipText = "Error: Ip invalida!!"

                }
            },
            searchFn: function() {
                this.getUserSpin = true
                this.searchOption = "Todos"
                this.getUser()
                
                //this.clearSearch() 
            },
            clearSearch: function() {
                this.searchString = ""
            },
            getSelected: function() {
                this.selectSpin = true
                this.searchString = ""
                this.getUser().then((resolve) => {
                    //console.log("promesa termminada despues de change on select!")
                })

            },
            setPing: function(data) {
                console.log(`staus clicked: ${data.ipAddress} con id cliente: ${data.id}`)
                data.responseTime = "Esperando"
                data.pingStatus = "******"
                axios.get('../devicePingResponse.php', {
                    params: {
                        ip: data.ipAddress,
                        id: data.id,
                    }
                }).then(response => {
                    data.responseTime = response.data.time
                    if (response.data.time) data.pingStatus = "up"
                    else {
                        data.responseTime = "No-response"
                        data.pingStatus = "down"
                    }
                }).catch(e => {
                    //console.log('error' + e)
                })

            },
            pingtoIp: function(ipAddress) {
                this.serverType=null
                this.pingToServerSpin=true;
                axios.get('../devicePingResponse.php', {
                    params: {
                        ip: ipAddress
                    }
                }).then(response => {
                    if (response.data.time){
                        this.ipTargetTime = response.data.time
                        this.ipTargetStatus = "up"
                    } 
                    else {
                        this.ipTargetStatus = "down"
                    }
                    
                }).catch(e => {
                    //console.log('error' + e)
                })
                this.repeaterSignal(ipAddress)
            },
            repeaterSignal: async function(ipAddress){
                var param="&ipAddress="+ipAddress
                var url = this.mikrotikEndpoint+param
                console.log(`Tryng Mikrotik,ip address: ${url}`)
                const mikrotikResponse = await fetch(url, {
                    method: 'GET',
                    headers : { 
                        'Accept': 'application/json'
                    }
                })
                let promiseResponse = await mikrotikResponse.json();
                if(promiseResponse.status!='fail' && promiseResponse.signal !='0' ){
                    this.signalOfRepeater=promiseResponse.signal
                    this.serverType="Mikrotik"
                }else{
                    var url = this.ubiquitiEndpoint+param
                    console.log(`Tryng Ubiquiti,ip address: ${url}`)
                    const ubiquitiResponse = await fetch(url, {
                        method: 'GET',
                        headers : { 
                            'Accept': 'application/json'
                        }
                    })
                    let promiseRes = await ubiquitiResponse.json(); 
                    if(promiseRes.status!='fail'){
                        this.signalOfRepeater=promiseRes.signal
                        this.serverType="Ubiquiti"
                    }
                }
                this.pingToServerSpin=false;
            },
            pingToIpButtonClick: function(data) {
                this.pingSuccess = "waiting"
                if (this.validateIpAddress(this.ipAddressInput)) {
                    this.spinIcon = true
                    axios.get('../devicePingResponse.php', {
                        params: {
                            ip: this.ipAddressInput,
                            mainServerIp: this.mainServerIp
                        }
                    }).then(response => {
                        this.spinIcon = false
                        if (response.data.time) {
                            if (response.data.time != "-1") {
                                this.pingSuccess = "ok"
                                this.pingSuccessTime = response.data.time
                            } else {
                                this.pingSuccess = "serverError"
                            }
                        } else {
                            this.pingSuccess = "no"
                        }
                    }).catch(e => {
                        this.spinIcon = false
                        //console.log('error' + e)
                    })
                } else {
                    this.pingDataError = true;

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
            inputPingClick: function(data) {
                if (this.pingDataError) {
                    this.ipAddressInput = ""
                    this.pingDataError = false
                    this.pingSuccess = "waiting"
                }
            },
            getIpListBox1: function(data) {
                return new Promise((resolve, reject) => {
                    this.spinIconBox1 = true
                    axios.get('../devicePingResponseList.php', {//
                        params: {
                            mainServerIp: "192.168.20.1",
                            ipsToDiscovery: "1",
                            from: "192.168.20.60",
                            to: "192.168.20.254",
                            byteToChange: "3" 
                        }
                    }).then(response => {
                        this.ipListBox1=response.data[0].includes("192")?response.data:""
                        //console.log(response.data)
                        this.spinIconBox1 = false
                        resolve("ok")
                    })

                })
            },
            ipListBox1reload: function(data) {
                this.ipListBox1 = [];
                this.getIpListBox1()
            },
            getIpListBox2: function(data) {
                return new Promise((resolve, reject) => {
                    this.spinIconBox2 = true
                    axios.get('../devicePingResponseList.php', {
                        params: {
                            mainServerIp: "192.168.30.1",
                            ipsToDiscovery: "1",
                            from: "192.168.30.60",
                            to: "192.168.30.254",
                            byteToChange: "3"
                        }
                    }).then(response => {
                        this.ipListBox2 = response.data
                        this.spinIconBox2 = false 
                        resolve("ok")
                    })
                })
            },
            getVoltage: function(data) {
                // return new Promise((resolve, reject) => {
                //     this.spinIconVoltage = true
                //     axios.get('../utils/voltageMonitor.php', {
                //         params: {
                //             location: "retiro"
                //         }
                //     }).then(response => {
                //         this.ipVoltageBox2 = JSON.parse(response.data)
                //         this.spinIconVoltage = false
                //         resolve("ok")
                //     })
                // })
            },
            ipListBox2reload: function(data) {
                this.ipListBox2 = [];
                this.getIpListBox2()
            },
            voltageReload: function(data) {
                this.ipVoltageBox2 = {}
                this.getVoltage()
            },
            getIpListBox3: function(data) {
                return new Promise((resolve, reject) => {
                    this.spinIconBox3 = true
                    axios.get('../devicePingResponseList.php', {
                        params: {
                            mainServerIp: "192.168.26.1",
                            ipsToDiscovery: "1",
                            from: "192.168.26.155",
                            to: "192.168.26.254",
                            byteToChange: "3"
                        }
                    }).then(response => {
                        this.ipListBox3 = response.data
                        this.spinIconBox3 = false
                        resolve("ok")
                    })

                })
            },
            ipListBox3reload: function(data) {
                this.ipListBox3 = [];
                this.getIpListBox3()
            }
        },
        mounted() {
            this.getRepeaterList()
            this.getVoltage()
            this.getUserSpin = true
            Promise.all([this.getUser()]) //this.getIpListBox1(), this.getIpListBox2(), this.getIpListBox3(),
                .then((resolve) => {
                    //console.log("success")
                })
                .catch((reject) => {
                    //console.log("error" + reject)
                })
        },
    });
    </script>
</body>

</html>