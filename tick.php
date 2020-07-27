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
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <title>Wisdev-Administrador ISP</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet" />
    <link rel="stylesheet" href="css/fontello.css" />
    <link rel="stylesheet" href="css/animation.css">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="bower_components/alertify/css/alertify.min.css" />
    <link rel="stylesheet" href="bower_components/alertify/css/themes/default.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>

<body>
    <header>
        <div class="logo">
            <h1>Isp Experts <h4>Monitoreo y administraciòn</h4>
            </h1>
        </div>
        <div class="header-box">
            <div class="user">
                <i class="icon-user"></i><span><?php echo "Hola ".$_SESSION['username'];?></span>
            </div>
            <div class="button-collapse">
                <button>☰</button>
            </div>
        </div>
    </header>
    <nav class="navTop">
        <ul>
            <li><a href="#"><i class="icon-money"></i>Registrar Pago</a></li>
            <li><a href="#"><i class="icon-print"></i>Transacciones</a></li>
            <li><a href="#"><i class="icon-money"></i>Formato Recibo</a></li>
        </ul>
    </nav>
    <main id="app">
        <nav class="navLeft">
            <ul>
                <li class="selected"><a href="#"><i class="icon-pinboard"></i><span>Tickets</span></a></li>
                <li><a href="#"><i class="icon-docs"></i><span>Facturas</span></a></li>
                <li><a href="#"><i class="icon-users"></i><span>Clientes</span></a></li>
                <li><a href="#"><i class="icon-network"></i><span>Mktik</span></a></li>
                <li><a href="#"><i class="icon-money"></i><span>Egresos</span></a></li>
                <li><a href="#"><i class="icon-logout"></i><span>Salir</span></a></li>
            </ul>
        </nav>
        <section>
            <div class="section-title">
                <img src="img/support.png" alt="">
                <h1>ADMINISTRAR TICKETS DE SOPORTE TÈCNICO</h1>
            </div>
            <div class=box-container>
                <div class="box box-new-ticket">
                    <div class="title">
                        <h3><i class="icon-user"></i> Nuevo Ticket</h3>
                    </div>
                    <div class="box-content">
                        <div class="search">
                            <input v-model="searchClientContent" type="text" placeholder="Nombre de Cliente">
                            <button v-on:click="searchClient" :disabled="searchClientContent==''"
                                :class="{'button-disabled':searchClientContent==''}"><i
                                    class="icon-search"></i></button>
                        </div>
                        <div v-bind:class="{'hide-box-result':hideTicketResult}">
                            <div class="title">
                                <h3 class="icon-docs">Result</h3>
                            </div>
                            <div class="result-content">
                                <div class="result-container">
                                    <div>
                                        <p>Selecciona cliente.</p>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Cliente</th>
                                                    <th>Ip Address</th>

                                                    <th>Recibe</th>
                                                    <th class="close-result-table">
                                                        <div><span>Fecha</span></div>
                                                        <div><button @click="closeResultTable"
                                                                class="icon-cancel"></button></div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="client in clientes" :key="client.id"
                                                    @click="selectedRowNewTicket(client.id,client.cliente,client)">
                                                    <td>{{client.cliente}}</td>
                                                    <td>{{client.ip}}</td>

                                                    <td>{{client.recibe}}</td>
                                                    <td>{{client.fecha}}</td>
                                                </tr>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td>{{totalRows}} rows</td>
                                                    <td></td>

                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="selected-client">
                                        <p>Cliente seleccionado New Ticket</p>
                                        <input type="text" :value="newTicketSelectedClient" disabled
                                            placeholder="Selecciona cliente">
                                        <input type="hidden" id="newTicketForId" :value="newTicketSelectedId">
                                        <button @click="continueToResultModal(true)"
                                            :disabled="newTicketSelectedClient=='' ">Continuar</button><button
                                            @click="closeResultTable" class="icon-cancel"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box new-ticket " v-bind:class="{'hide-new-ticket':hideResultModal}">
                    <div class="new-ticket-modal-content">
                        <form v-on:submit.prevent="checkFormNewTicket">
                            <div class="title-modal">
                                <h3>Registrar Nuevo Ticket</h3>
                            </div>
                            <div class="form-new-ticket">
                                <div class="form-group new-cli">
                                    <label for="cli">Cliente</label>
                                    <input type="text" id="cli" :value="clientNewTicketSelected.cliente">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="clientTelefono">Telèfono de Cliente</label>
                                    <input required type="number" v-model="clientNewTicketSelected.telefono"
                                        placeholder="10 digitos">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="clientTelefonoAdicional">Telèfono Adicional ::<input type="checkbox"
                                            v-model="newClientCheck"></label>
                                    <input type="number" v-model="clientNewTicketSelected.telefonoAdicional"
                                        placeholder="10 digitos" :disabled="!newClientCheck" :required="newClientCheck">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="direccion">Direcciòn</label>
                                    <input required type="text" v-model="clientNewTicketSelected.direccion">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="email">Email de cliente</label>
                                    <input type="email" v-model="clientNewTicketSelected.email">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="ipAddre">Ip Address</label>
                                    <input type="text" required :placeholder="clientNewTicketSelected.ipBackup"
                                        v-model="clientNewTicketSelected.ip">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="fechaSugerida">Fecha Sugerida</label>
                                    <input type="date" required placeholder="xx/xx/xxxx"
                                        v-model="clientNewTicketSelected.fechaSugerida">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="precioSoporte">Precio Soporte</label>
                                    <input type="number" step="0.01" min="0" required placeholder="$"
                                        v-model="clientNewTicketSelected.precioSoporte">
                                </div>
                                <div class="form-group new-cli w100">
                                    <label for="diagnostico-previo">Solicitud de Cliente</label>
                                    <textarea required minlength="6" rows="10" cols=""
                                        v-model="clientNewTicketSelected.diagnosticoPrevio"></textarea>
                                </div>
                                <div class="form-group new-cli w100">
                                    <label for="sugerencia">Sugerencia de soluciòn</label>
                                    <textarea required minlength="6" rows="10" cols=""
                                        v-model="clientNewTicketSelected.sugerencia"></textarea>
                                </div>
                            </div>
                            <div class="footer-modal">
                                <input type="submit" value="Enviar"><span class="icon-cancel"
                                    @click="continueToResultModal(false)"></span>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box">
                    <div class="title">
                        <h3><i class="icon-wrench"></i> Tickets Abiertos</h3>
                    </div>
                    <div>
                        <div class="box-content">
                            <table>
                                <thead>
                                    <tr>
                                        <th>#ticket</th>
                                        <th>Cliente</th>
                                        <th>Ip Address</th>
                                        <th>Fecha</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ticketAbierto in ticketsAbiertos" :key="ticketAbierto.id"
                                        @click="selectedRowTicketAbiero(ticketAbierto.id,ticketAbierto.cliente,ticketAbierto)">
                                        <td>{{ticketAbierto.id}}</td>
                                        <td>{{ticketAbierto.cliente}}</td>
                                        <td>{{ticketAbierto.ip}}</td>
                                        <td>{{ticketAbierto.fecha}}</td>
                                        <td>{{ticketAbierto.status}}</td>
                                    </tr>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>{{totalRowsAbiertos}} rows</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="selected-client">
                        <p>Cliente seleccionado</p>
                        <input type="text" :value="abiertoTicketSelectedClient" placeholder="Selecciona cliente">
                        <input type="hidden" id="" :value="abiertoTicketSelectedId">
                        <button @click="continueToAbiertoTicketModal(true)">Continuar</button>
                    </div>
                    <div class="close-ticket-modal" v-bind:class="{'hide-close-ticket-modal':hideTicketAbiertoModal}">
                        <div class="close-ticket-content">
                            <div class="title-modal">
                                <h3>Cerrar Ticket</h3>
                            </div>
                            <form v-on:submit.prevent="checkFormCerrarTicket()">
                                <div class="form-close-ticket">
                                    <div class="form-group">
                                        <p>Fecha: <span>19/07/2020</span></p>
                                        <p>Tècnico: <span>Juan Pablo</span></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="cliente">Cliente</label>
                                        <input v-model="clientAbiertoTicketSelected.cliente" type="text" id="cliente">
                                    </div>
                                    <div class="form-group">
                                        <label  for="telefonoCliente">Telèfono Cliente</label>
                                        <input required v-model="clientAbiertoTicketSelected.telefono" type="number" id="telefonoCliente">
                                    </div>
                                    <div class="form-group">
                                        <label for="telefono">Telèfono Contacto</label>
                                        <input v-model="clientAbiertoTicketSelected.telefonoContacto" type="number" name="telefono" id="telefono">
                                    </div>
                                    <div class="form-group">
                                        <label for="clientAdd">Direcciòn</label>
                                        <input required v-model="clientAbiertoTicketSelected.direccion" type="text" name="clientAdd" id="clientAdd">
                                    </div>
                                    <div class="form-group">
                                        <label for="mail">Email de cliente</label>
                                        <input required v-model="clientAbiertoTicketSelected.email" type="email" name="mail" id="mail">
                                    </div>
                                    <div class="form-group">
                                        <div class="radio-button">
                                            <label for="ipAddress">Ip Address,cambiar ip:</label>
                                            <input type="radio" name="changeIp" @click="radioButtonDisabled=false"
                                                :checked="clientAbiertoTicketSelected.ip"> SI
                                            <input type="radio" name="changeIp" @click="radioButtonDisabled=true"
                                                :checked="!clientAbiertoTicketSelected.ip">NO
                                        </div>
                                        <input required placeholder="Ingrese Direcciòn Ip"
                                            v-model="clientAbiertoTicketSelected.ip" type="text" name="ipAddress" id="ipAddress"
                                            :disabled="radioButtonDisabled">
                                    </div>
                                    <div class="form-group">
                                        <label for="routerModel">Marca de Router</label>
                                        <input required v-model="clientAbiertoTicketSelected.marcaRouter" type="text" name="routerModel" id="routerModel">
                                    </div>
                                    <div class="form-group">
                                        <label for="routerMacAddress">MAC Address Router</label>
                                        <input v-model="clientAbiertoTicketSelected.macRouter" type="text" name="routerMacAddress" id="routerMacAddress"
                                            placeholder="0-9 & A-F">
                                    </div>
                                    <div class="form-group">
                                        <label for="antenaMacAddress">MAC Address Antena</label>
                                        <input required v-model="clientAbiertoTicketSelected.macAntena" type="text" name="antenaMacAddress" id="antenaMacAddress"
                                            placeholder="0-9 & A-F">
                                    </div>
                                    <div class="form-group">
                                        <label for="inyectorPoe">Inyector POE</label>
                                        <select required v-model="clientAbiertoTicketSelected.inyectorPoe" id="inyectorPoe">
                                            <option value="inyectorUbiquiti">Ubiquiti</option>
                                            <option value="inyectorMikrotik">Mikrotik</option>
                                            <option value="inyectorOtro">Otro</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Apuntamiento">Apuntamiento</label>
                                        <select required v-model="clientAbiertoTicketSelected.apuntamiento" id="Apuntamiento">
                                            <option value="montecristo">Montecristo</option>
                                            <option value="retiro">Retiro</option>
                                            <option value="calizas">Calizas</option>
                                            <option value="alcaravan">Alcaravan</option>
                                            <option value="sapitos">Sapitos</option>
                                            <option value="santa-ana-1">Santa Ana 1</option>
                                            <option value="santa-ana-2">Santa Ana 2</option>
                                            <option value="vereda-centro">Vereda Centro</option>
                                            <option value="barrio-costeños">Brr Costeños</option>
                                        </select>
                                    </div>
                                    <div class="select-group">
                                        <div class="form-group">
                                            <label for="router-remote-admin">Acceso Remoto habilitado</label>
                                            <select required v-model="clientAbiertoTicketSelected.accesoRemoto" id="router-remote-admin">
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tipo-antena">Tipo de Antena</label>
                                            <select required v-model="clientAbiertoTicketSelected.tipoAntena" id="tipo-antena">
                                                <option value="none">Ninguna</option>
                                                <option value="mikrotik-lhg5">Mikrotik, LGH 5</option>
                                                <option value="mikrotik-lhg5ac">Mikrotik, LHG5 AC</option>
                                                <option value="mikrotik-metal">Mikrotik, METAL</option>
                                                <option value="mikrotik-sxtlite2">Mikrotik, SXT LITE 2</option>
                                                <option value="mikrotik-sxtlite5">Mikrotik, SXT LITE 5</option>
                                                <option value="mikrotik-disclite5">Mikrotik, DISC LITE 5</option>
                                                <option value="mikrotik-sqlite5">Mikrotik, SQ LITE 5</option>
                                                <option value="mikrotik-otro">Mikrotik, OTRO</option>
                                                <option value="ubiquiti-liteBeam">Ubiquiti, LITE BEAM</option>
                                                <option value="ubiquiti-nanobride">Ubiquiti, NANOBRIDGE</option>
                                                <option value="ubiquiti-powerbeam">Ubiquiti, POWERBEAM</option>
                                                <option value="ubiquiti-nanostation">Ubiquiti, NANOSTATION</option>
                                                <option value="ubiquiti-locom2">Ubiquiti, NANOSTATION LOCO M2</option>
                                                <option value="ubiquiti-locom5">Ubiquiti, NANOSTATION LOCO M5</option>
                                                <option value="ubiquiti-otro">Ubiquiti, OTRO</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tipo-instalacion">Tipo de Instalaciòn</label>
                                            <select required v-model="clientAbiertoTicketSelected.tipoInstalacion" id="tipo-instalacion">
                                                <option value="repetidor">X Repetidor</option>
                                                <option value="antena">X Antena</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tipo-soporte">Tipo de Soporte</label>
                                            <select required v-model="clientAbiertoTicketSelected.tipoSoporte" id="tipo-soporte">
                                                <option value="varios">Varios. Cuàles?</option>
                                                <option value="ampliar-velocidad">Ampliaciòn de velocidad</option>
                                                <option value="traslado">Traslado</option>
                                                <option value="dano-antena">Daño de Antena</option>
                                                <option value="clave">Cambio de clave</option>
                                                <option value="dano-router">Daño de router</option>
                                                <option value="dano-router">Daño de switch</option>
                                                <option value="dano-cable">Daño de cable</option>
                                                <option value="instalacionServicio">Instalacion de servicio</option>
                                                <option value="direccionamiento">Direccionamiento de antena. Por què?</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="diagnostico">Solicitud de Cliente</label>
                                        <textarea  v-model="clientAbiertoTicketSelected.solicitudCliente" cols="" id="diagnostico" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="solucion">Describir soluciòn</label>
                                        <textarea v-model="clientAbiertoTicketSelected.solucion" cols="" id="solucion" required minlength="30"
                                            placeholder="Si recibe dinero, especificar"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="sugerencias">Sugerencias</label>
                                        <textarea v-model="clientAbiertoTicketSelected.sugerencia" cols="" id="sugerencias"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="cargar-a-factura-valor">Cargar a factura valor</label>
                                        <input v-model="clientAbiertoTicketSelected.cargarAfacturaVaĺor" type="number" name="cargar-a-factura-valor" id="cargar-a-factura-valor"
                                            placeholder="Queda Pendiente Pagar?">
                                    </div>
                                    <div class="form-group">
                                        <label for="cargar-a-factura-descripcion">Cargar a factura descripciòn</label>
                                        <textarea v-model="clientAbiertoTicketSelected.cargarAfacturaDescripcion" cols="" id="cargar-a-factura-descripcion" placeholder="Què es lo que queda pdte pagar?"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="resuelto">El problema fue resuelto?</label>
                                        <select required v-model="clientAbiertoTicketSelected.problemaResuelto" id="resuelto">
                                            <option value="si">Si, ya se puede cerrar este ticket</option>
                                            <option value="no">No, queda pendiente.</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="footer-modal">
                                    <input type="submit" value="Enviar"><span class="icon-cancel"
                                        @click="continueToAbiertoTicketModal(false)"></span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="title">
                        <h3><i class="icon-wrench"></i> Tickets Cerrados</h3>
                    </div>
                    <div>
                        <div class="box-content">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Ip Address</th>
                                        <th>Tècnico</th>
                                        <th>Recibe</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ticketCerrado in ticketsCerrados" :key="ticketCerrado.id"
                                        @click="selectedRowTicketCerrado(ticketCerrado.id,ticketCerrado.cliente)">
                                        <td>{{ticketCerrado.cliente}}</td>
                                        <td>{{ticketCerrado.ip}}</td>
                                        <td>{{ticketCerrado.tecnico}}</td>
                                        <td>{{ticketCerrado.recibe}}</td>
                                        <td>{{ticketCerrado.fecha}}</td>
                                        <td>{{ticketCerrado.hora}}</td>
                                    </tr>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>{{totalRowsCerrados}} rows</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="selected-client">
                        <p>Cliente seleccionado</p>
                        <input type="text" :value="cerradoTicketSelectedClient" placeholder="Selecciona cliente">
                        <input type="hidden" id="" :value="cerradoTicketSelectedId">
                        <button @click="continueToClosedTicketsModal(true)">Continuar</button>
                    </div>
                    <div class="closed-tickets-modal"
                        v-bind:class="{'hide-closed-tickets-modal':hideTicketsClosedModal}">
                        <div class="close-ticket-content">
                            <div class="title-modal">
                                <h3>TICKETS FINALIZADOS</h3>
                            </div>
                            <form action="">
                                <div class="form-close-ticket">
                                    <div class="form-group">
                                        <p>Fecha: <span>22/07/2020</span></p>
                                        <p>Tècnico: <span>Sebastian</span></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="clienteCerrado">Cliente</label>
                                        <input type="text" id="clienteCerrado" value="Antonio Morales">
                                    </div>
                                    <div class="form-group">
                                        <label for="telefonoCerrado">Telèfono</label>
                                        <input type="text" name="telefonoCerrado" id="telefonoCerrado"
                                            value="3215452635">
                                    </div>
                                    <div class="form-group">
                                        <label for="direccionCerrado">DirecciònCerrado</label>
                                        <input type="text" name="direccionCerrado" value="Cll 13#15-22"
                                            id="direccionCerrado">
                                    </div>
                                    <div class="form-group">
                                        <label for="emailCerrado">Email de cliente</label>
                                        <input type="email" name="emailCerrado" value="omar_alberto_h@yahoo.es"
                                            id="emailCerrado">
                                    </div>
                                    <div class="form-group">
                                        <label for="ipAddressCerrado">Ip Address</label>
                                        <input type="text" name="ipAddressCerrado" value="192.168.20.6"
                                            id="ipAddressCerrado" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="routerModelCerrado">Marca de Router</label>
                                        <input type="text" name="routerModelCerrado" value="Tp-Link"
                                            id="routerModelCerrado">
                                    </div>
                                    <div class="select-group">
                                        <div class="form-group">
                                            <label for="routerRemoteAdminCerrado">Acceso Remoto habilitado</label>
                                            <select name="routerRemoteAdminCerrado" id="routerRemoteAdminCerrado">
                                                <option value="yes">Yes</option>
                                                <option selected value="no">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tipoAntenaCerrado">Tipo de Antena</label>
                                            <select name="tipoAntenaCerrado" id="tipoAntenaCerrado">
                                                <option value="ninguna">Ninguna</option>
                                                <option value="mikrotik">Mikrotik</option>
                                                <option value="ubiquiti">Ubiquiti</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tipoInstalacionCerrado">Tipo de Instalaciòn</label>
                                            <select name="tipoInstalacionCerrado" id="tipoInstalacionCerrado">
                                                <option value="repetidor">X Repetidor</option>
                                                <option value="antena">X Antena</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tipoSoporteCerrado">Tipo de Soporte</label>
                                            <select name="tipoSoporteCerrado" id="tipoSoporteCerrado">
                                                <option value="varios">Varios. Cuàles?</option>
                                                <option value="ampliar-velocidad">Ampliaciòn de velocidad</option>
                                                <option value="traslado">Traslado</option>
                                                <option value="dano-antena">Daño de Antena</option>
                                                <option value="clave">Cambio de clave</option>
                                                <option value="dano-router">Daño de router</option>
                                                <option value="dano-cable">Daño de cable</option>
                                                <option value="direccionamiento">Direccionamiento de antena. Por què?
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="diagnosticoCerrado">Solicitud de Cliente</label>
                                        <textarea cols=""
                                            id="diagnosticoCerrado">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, iste? Recusandae, possimus harum amet non cum rerum? Corrupti quos, quae est iste quis ratione. Ipsum debitis tempora velit incidunt natus!</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="solucionCerrado">Describir soluciòn</label>
                                        <textarea cols=""
                                            id="solucionCerrado">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Officiis porro perferendis labore veniam! Beatae ab, ut in repellat laudantium tenetur reiciendis voluptatibus ex est voluptatem eius quidem rerum? In, eveniet?</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="sugerenciasCerrado">Sugerencias</label>
                                        <textarea cols="" id="sugerenciasCerrado">loremjhkjgjhgjhgjhgjhgj</textarea>
                                    </div>

                                </div>
                            </form>
                            <div class="footer-modal">
                                <input type="submit" value="Enviar"><button class="icon-cancel"
                                    @click="continueToClosedTicketsModal(false)"></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-statitics">
                    <div class="title">
                        <h3><i class="icon-chart-line"></i> Statitics</h3>
                    </div>
                    <div class="resume">
                        <div class="title">
                            <h3>JULIO</h3>
                        </div>
                        <div class="content">
                            <div class="box-child">
                                <h1>50</h1>
                                <p>SOPORTES</p>
                            </div>
                            <div class="box-child">
                                <h1>25</h1>
                                <p>JUAN PABLO</p>
                            </div>
                            <div class="box-child">
                                <h1>25</h1>
                                <p>SEBASTIAN</p>
                            </div>
                        </div>
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
</body>
<script src="bower_components/alertify/js/alertify.min.js"></script>
<script>
var app = new Vue({
    el: "#app",
    data: {
        searchClientContent: "",
        clientes: [],
        clientNewTicketSelected: [],
        ticketsAbiertos: [],
        clientAbiertoTicketSelected: [],
        ticketsCerrados: [],
        totalRows: "",
        totalRowsAbiertos: "",
        totalRowsCerrados: "",
        newTicketSelectedClient: "",
        newTicketSelectedId: "",
        abiertoTicketSelectedClient: "",
        abiertoTicketSelectedId: "",
        cerradoTicketSelectedClient: "",
        cerradoTicketSelectedId: "",
        hideTicketResult: true,
        hideResultModal: true,
        hideTicketAbiertoModal: true,
        hideTicketsClosedModal: true,
        radioButtonDisabled: false,
        ipAddressContentFlag: true,
        selectedNewClient: "",
        newClientCheck: false
    },
    methods: {
        continueToAbiertoTicketModal: function(data) {
            if (data)
                this.hideTicketAbiertoModal = false
            else
                this.hideTicketAbiertoModal = true
        },
        continueToClosedTicketsModal: function(data) {
            if (data)
                this.hideTicketsClosedModal = false
            else
                this.hideTicketsClosedModal = true
        },
        checkFormNewTicket: function() {
            let valid = true
            if (this.clientNewTicketSelected.telefono.length != 10) {
                this.clientNewTicketSelected.telefono = ""
                valid = false
            }
            if (this.newClientCheck) {
                if (this.clientNewTicketSelected.telefonoAdicional.length != 10)
                    this.clientNewTicketSelected.telefonoAdicional = ""
                valid = false
            }
            if (!this.validateIpAddress(this.clientNewTicketSelected.ip)) {
                this.clientNewTicketSelected.ip = ""
                valid = false
            }
            if (valid) {
                let r = confirm("Confirmar!");
                if (r) {
                    this.saveNewticket()
                    this.hideResultModal = true
                }
            }

        },
        saveNewticket: function() {
            console.log("Saving the ticket!")
            axios.get('saveNewTicket.php', {
                params: {
                    ticketData: this.clientNewTicketSelected
                }
            }).then(response => {
                if (response.data == "saved") {
                    alertify.success("Ticket guardado con èxito.")
                } else {
                    alertify.error("No fue posible guardar el ticket.")
                }

            }).catch(e => {
                console.log("error: " + e)
            })
        },
        checkFormCerrarTicket: function() {
            if (!this.validateIpAddress(this.clientAbiertoTicketSelected.ip))
                this.clientAbiertoTicketSelected.ip = ""
        },
        continueToResultModal: function(data) {
            if (data)
                this.hideResultModal = false
            else
                this.hideResultModal = true
        },
        closeResultTable: function() {
            this.hideTicketResult = true
        },
        searchClient: function() {
            this.getUser()
        },
        getUser: function() {
            axios.get('fetchClientList.php', {
                params: {
                    searchClientContent: this.searchClientContent
                }
            }).then(response => {
                this.totalRows = response.data.length
                this.clientes = response.data
                this.hideTicketResult = false

            }).catch(e => {
                console.log('error' + e)
            })
        },
        getTicketAbierto: function() {
            axios.get('fetchTicketCerrados.php', {}).then(response => {
                this.totalRowsCerrados = response.data.length
                this.ticketsCerrados = response.data
            }).catch(e => {
                console.log('error' + e)
            })
        },
        getTicketCerrado: function() {
            axios.get('fetchTicketAbiertos.php', {}).then(response => {
                this.totalRowsAbiertos = response.data.length
                this.ticketsAbiertos = response.data
                console.log(JSON.stringify(response.data))
            }).catch(e => {
                console.log('error' + e)
            })
        },
        selectedRowTicketAbiero: function(id, client,ticketSelectedObjet) {
            this.abiertoTicketSelectedClient = client
            this.abiertoTicketSelectedId = id
            this.clientAbiertoTicketSelected=ticketSelectedObjet
        },
        selectedRowTicketCerrado: function(id, client) {
            this.cerradoTicketSelectedClient = client
            this.cerradoTicketSelectedId = id
        },
        selectedRowNewTicket: function(id, client, clientObject) {
            this.newTicketSelectedId = id
            this.newTicketSelectedClient = client
            this.clientNewTicketSelected = clientObject
            this.clientNewTicketSelected.ipBackup = this.clientNewTicketSelected.ip
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
        }
    },
    mounted() {

        this.getTicketAbierto()
        this.getTicketCerrado()
        // if (this.clientAbiertoTicketSelected.ip.length != 0)
        //     this.radioButtonDisabled = true
    },
});
</script>
</body>

</html>