<?php
header("Location: /public/registerPay.php");
exit; 
?>
<?php 
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: ../login/index.php');
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
    <title>FastNet-Administrador ISP</title>
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
                <h1>FastNet</h1>
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
                            <button v-on:click="searchClient"  :disabled="(searchClientContent==''<?php if($_SESSION['role']=='tecnico')echo " || 1";?>)"
                                :class=" {'button-disabled':searchClientContent==''} "><i
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
                                                    <th>Direcciòn</th>
                                                    <th>Telèfono</th>
                                                    <th class="close-result-table">
                                                        <div><span>Apuntamiento</span></div>
                                                        <div><button @click="closeResultTable"
                                                                class="icon-cancel"></button></div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="client in clientes" :key="client.id"
                                                    @click="selectedRowNewTicket(client.id,client.cliente,client)">
                                                    <td>{{client.cliente}} {{client.apellido}}</td>
                                                    <td>{{client.direccion}}</td>

                                                    <td>{{client.telefono}}</td>
                                                    <td>{{client.apuntamiento}}</td>
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
                                            :disabled="newTicketSelectedClient=='' ">Continuar</button>
                                        <button @click="closeResultTable" class="icon-cancel"></button>
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
                                    <input disabled type="text" id="cli" :value="clientNewTicketSelected.cliente">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="apellido">Apellido</label>
                                    <input disabled type="text" id="apellido" :value="clientNewTicketSelected.apellido">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="clientTelefono">Telèfono de Cliente</label>
                                    <input required type="number" v-model="clientNewTicketSelected.telefono"
                                        placeholder="10 digitos">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="telefonoContacto">Telèfono Adicional ::<input type="checkbox"
                                            v-model="newClientCheck"></label>
                                    <input type="number" v-model="clientNewTicketSelected.telefonoContacto"
                                        placeholder="10 digitos" :disabled="!newClientCheck" :required="newClientCheck">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="direccion">Direcciòn</label>
                                    <input required type="text" v-model="clientNewTicketSelected.direccion">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="ciudad">Ciudad</label>
                                    <input required type="text" v-model="clientNewTicketSelected.ciudad"
                                        placeholder="Guamal?Castila?Acacias?">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="email">Email de cliente</label>
                                    <input type="email" v-model="clientNewTicketSelected.email">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="ipAddre">Ip Address</label>
                                    <input type="text"  :placeholder="clientNewTicketSelected.ipBackup"
                                        v-model="clientNewTicketSelected.ip">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="fechaSugerida">Fecha Sugerida</label>
                                    <input type="date" required placeholder="xx/xx/xxxx"
                                        v-model="clientNewTicketSelected.fechaSugerida">
                                </div>
                                <div class="form-group new-cli">
                                    <label>Hora Sugerida</label>
                                    <input type="text" required placeholder="08:00 am"
                                        v-model="clientNewTicketSelected.horaSugerida">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="precioSoporte">Precio Soporte</label>
                                    <input type="number" step="0.01" min="0" required placeholder="$"
                                        v-model="clientNewTicketSelected.precioSoporte">
                                </div>
                                <div class="form-group new-cli ">
                                    <label for="solicitud-cliente">Solicitud de Cliente</label>
                                    <textarea required minlength="6" rows="10" cols=""
                                        v-model="clientNewTicketSelected.solicitudCliente"></textarea>
                                </div>
                                <div class="form-group new-cli ">
                                    <label for="sugerencia">Sugerencia de administrador</label>
                                    <textarea required minlength="6" rows="10" cols=""
                                        v-model="clientNewTicketSelected.sugerenciaSolucion"></textarea>
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
                                        <th># Ticket</th>
                                        <th>Cliente</th>
                                        <th>Ip Address</th>
                                        <th>Fecha apertura</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ticketAbierto in ticketsAbiertos" :key="ticketAbierto.id"
                                        @click="selectedRowTicketAbiero(ticketAbierto.id,ticketAbierto)">
                                        <td>{{ticketAbierto.id}}</td>
                                        <td>{{ticketAbierto.cliente}} {{ticketAbierto.apellido}}</td>
                                        <td>{{ticketAbierto.ip}}</td>
                                        <td>{{ticketAbierto.fechaCreacionTicket}}</td>
                                    </tr>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>{{totalRowsAbiertos}} rows</td>
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
                        <input disabled type="text" :value="abiertoTicketSelectedClient"
                            placeholder="Selecciona cliente">
                        <input type="hidden" id="" :value="abiertoTicketSelectedId">
                        <button :disabled='!abiertoTicketSelectedId'
                            @click="continueToAbiertoTicketModal(true)">Continuar</button>
                    </div>
                    <div class="close-ticket-modal" v-bind:class="{'hide-close-ticket-modal':hideTicketAbiertoModal}">
                        <div class="close-ticket-content">
                            <div class="title-modal">
                                <h3>Cerrar Ticket</h3>
                            </div>
                            <form v-on:submit.prevent="checkFormCerrarTicket()">
                                <div class="form-close-ticket">
                                    <div class="form-group ">
                                        <p>Fecha Ticket:
                                            <span>{{clientAbiertoTicketSelected.fechaCreacionTicket}}</span></p>
                                        <p>Tècnico: <span>{{clientAbiertoTicketSelected.tecnico}}</span></p>
                                        <p>Fecha Prevista: <span>{{clientAbiertoTicketSelected.fechaSugerida}}</span>
                                        </p>
                                        <p>Hora Prevista: <span>{{clientAbiertoTicketSelected.horaSugerida}}</span></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Cliente #{{clientAbiertoTicketSelected.idCliente}}</label>
                                        <input v-model="clientAbiertoTicketSelected.cliente" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label>Apellido </label>
                                        <input v-model="clientAbiertoTicketSelected.apellido" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="telefonoCliente">Telèfono Cliente</label>
                                        <input required v-model="clientAbiertoTicketSelected.telefono" type="number">
                                    </div>
                                    <div class="form-group">
                                        <label for="telefono">Telèfono Contacto</label>
                                        <input v-model="clientAbiertoTicketSelected.telefonoContacto" type="number"
                                            name="telefono">
                                    </div>
                                    <div class="form-group">
                                        <label for="clientAdd">Direcciòn</label>
                                        <input required v-model="clientAbiertoTicketSelected.direccion" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="clientAdd">Ciudad</label>
                                        <input required v-model="clientAbiertoTicketSelected.ciudad" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="clientAdd">Departamento</label>
                                        <input required v-model="clientAbiertoTicketSelected.departamento" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="mail">Email de cliente</label>
                                        <input required v-model="clientAbiertoTicketSelected.email" type="email"
                                            name="mail">
                                    </div>
                                    <div class="form-group">
                                        <div class="radio-button">
                                            <label for="ipAddress">Ip Address,cambiar ip:</label>
                                            <input type="radio" name="changeIp" @click="radioButtonDisabled=false"
                                                :checked="clientAbiertoTicketSelected.ip"> SI
                                            <input disabled type="radio" name="changeIp" @click="radioButtonDisabled=true"
                                                :checked="!clientAbiertoTicketSelected.ip">NO
                                        </div>
                                        <input required placeholder="Ingrese Direcciòn Ip"
                                            v-model="clientAbiertoTicketSelected.ip" type="text" name="ipAddress"
                                            :disabled="radioButtonDisabled">
                                    </div>
                                    <div class="form-group">
                                        <label>Marca de Router</label>
                                        <input required v-model="clientAbiertoTicketSelected.router" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="routerMacAddress">MAC Address Router</label>
                                        <input required v-model="clientAbiertoTicketSelected.macRouter" type="text"
                                            placeholder="0-9 & A-F">
                                    </div>
                                    <div class="form-group">
                                        <label for="antenaMacAddress">MAC Address Antena</label>
                                        <input required v-model="clientAbiertoTicketSelected.macAntena" type="text"
                                            placeholder="0-9 & A-F">
                                    </div>
                                    <div class="select-group">
                                        <div class="form-group-select">
                                            <label for="inyectorPoe">Inyector POE</label>
                                            <select required v-model="clientAbiertoTicketSelected.inyectorPoe">
                                                <option value="inyectorUbiquiti">Ubiquiti</option>
                                                <option value="inyectorMikrotik">Mikrotik</option>
                                                <option value="inyectorOtro">Otro</option>
                                            </select>
                                        </div>
                                        <div class="form-group-select">
                                            <label for="Apuntamiento">Apuntamiento</label>
                                            <select required v-model="clientAbiertoTicketSelected.apuntamiento">
                                                <option value="montecristo">Montecristo</option>
                                                <option value="retiro">Retiro</option>
                                                <option value="calizas">Orlando-Calizas</option>
                                                <option value="alcaravan">Alcaravan</option>
                                                <option value="sapitos">Sapitos</option>
                                                <option value="santa-ana-1">Santa Ana 1</option>
                                                <option value="santa-ana-2">Santa Ana 2</option>
                                                <option value="vereda-centro">Vereda Centro</option>
                                                <option value="barrio-costeños">Brr Costeños</option>
                                                <option value="torre-guamal">Torre Guamal</option>
                                                <option value="torre-castilla">Torre Castilla</option>
                                                <option value="torre-mundo-mujer">Torre Mundo Mujer</option>
                                                <option value="humadea">Humadea</option>
                                                <option value="koinonia">Koinonia</option>
                                                <option value="koinonia">Rep Guamal Plaza</option>
                                                <option value="koinonia">REp Geinpetrol</option>
                                            </select>
                                        </div>
                                        <div class="form-group-select">
                                            <label for="router-remote-admin">Acceso Remoto habilitado</label>
                                            <select required v-model="clientAbiertoTicketSelected.accesoRemoto">
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group-select">
                                            <label for="tipo-antena">Tipo de Antena</label>
                                            <select required v-model="clientAbiertoTicketSelected.tipoAntena">
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
                                                <option value="ubiquiti-picostation">Ubiquiti, PICOSTATION</option>
                                                <option value="ubiquiti-otro">Ubiquiti, OTRO</option>
                                            </select>
                                        </div>
                                        <div class="form-group-select">
                                            <label for="tipo-instalacion">Tipo de Instalaciòn</label>
                                            <select required v-model="clientAbiertoTicketSelected.tipoInstalacion">
                                                <option value="repetidor">X Repetidor</option>
                                                <option value="antena">X Antena</option>
                                            </select>
                                        </div>
                                        <div class="form-group-select">
                                            <label for="tipo-soporte">Tipo de Soporte realizado </label>
                                            <select required v-model="clientAbiertoTicketSelected.tipoSoporte">
                                                <option value="varios">Varios. Cuàles?</option>
                                                <option value="ampliar-velocidad">Ampliaciòn de velocidad</option>
                                                <option value="instalacion">Instalaciòn Nuevo Servicio</option>
                                                <option value="traslado">Traslado</option>
                                                <option value="dano-antena">Daño de Antena</option>
                                                <option value="clave">Cambio de clave</option>
                                                <option value="dano-router">Daño de router</option>
                                                <option value="dano-router">Daño de switch</option>
                                                <option value="dano-cable">Daño de cable</option>
                                                <option value="instalacionServicio">Instalacion de servicio</option>
                                                <option value="direccionamiento">Direccionamiento de antena. Por què?
                                                </option>
                                                <option value="retiro">Retirar Equipos</option>
                                                <option value="router-desconfigurado">Router Desconfigurado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="diagnostico">Solicitud de Cliente</label>
                                        <textarea v-model="clientAbiertoTicketSelected.solicitudCliente" cols=""
                                            required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="solucion">Describir soluciòn</label>
                                        <textarea v-model="clientAbiertoTicketSelected.solucion" cols="" required
                                            minlength="30" placeholder="Si recibe dinero, especificar"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="sugerencias">Sugerencias del administrador</label>
                                        <textarea v-model="clientAbiertoTicketSelected.sugerenciaSolucion"
                                            cols=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="sugerencias">Recomendacones a cliente</label>
                                        <textarea v-model="clientAbiertoTicketSelected.recomendaciones"
                                            cols=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Cargar a factura valor</label>
                                        <input v-model="clientAbiertoTicketSelected.precioSoporte" type="number"
                                            name="cargar-a-factura-valor" required placeholder="Queda Pendiente Pagar?">
                                    </div>
                                    <div class="form-group">
                                        <label>Cargar a factura descripciòn</label>
                                        <textarea v-model="clientAbiertoTicketSelected.precioSoporteDescripcion" cols=""
                                            placeholder="Què es lo que queda pdte pagar?"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="resuelto">El problema fue resuelto?</label>
                                        <select required v-model="clientAbiertoTicketSelected.status">
                                            <option value="cerrado">Si, ya se puede cerrar este ticket</option>
                                            <option value="abierto">No, queda pendiente.</option>
                                        </select>
                                    </div>
                                    <div class="evidencia-fotografica">
                                        <div class="form-group-evidencia">
                                            <p>Evidencia Fotogràfica</p>
                                            <input required type="file" ref="file1" @change="handleFileUpload(1)">
                                            <div class="img-container">
                                                <img :src="url" alt="evidencia">
                                                <p>{{img1Error}}</p>
                                            </div>
                                            <p><a :href="url" target="_blank">Abrir</a></p>
                                        </div>
                                        <div class="form-group-evidencia">
                                            <p>Evidencia Fotogràfica</p>
                                            <input required type="file" ref="file2" @change="handleFileUpload(2)">
                                            <div class="img-container">
                                                <img :src="url2" alt="evidencia">
                                                <p>{{img2Error}}</p>
                                            </div>
                                            <p><a :href="url2" target="_blank">Abrir</a></p>
                                        </div>
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
                                        <th>Ticket</th>
                                        <th>Cliente</th>
                                        <th>Ip Address</th>
                                        <th>Tècnico</th>
                                        <th>Cierre</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ticketCerrado in ticketsCerrados" :key="ticketCerrado.id"
                                        @click="selectedRowTicketCerrado(ticketCerrado.id,ticketCerrado)">
                                        <td>{{ticketCerrado.id}}</td>
                                        <td>{{ticketCerrado.cliente}} {{ticketCerrado.apellido}}</td>
                                        <td>{{ticketCerrado.ip}}</td>
                                        <td>{{ticketCerrado.tecnico}}</td>
                                        <td>{{ticketCerrado.fechaCierreTicket}}</td>
                                    </tr>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>{{totalRowsCerrados}} rows</td>
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
                        <button :disabled='!cerradoTicketSelectedClient'
                            @click="continueToClosedTicketsModal(true)">Continuar</button>
                    </div>
                    <div class="closed-tickets-modal"
                        v-bind:class="{'hide-closed-tickets-modal':hideTicketsClosedModal}">
                        <div class="close-ticket-content">
                            <div class="title-modal">
                                <h3>TICKETS FINALIZADOS</h3>
                            </div>
                            <form v-on:submit.prevent="true">
                                <div class="form-close-ticket">
                                    <div class="form-group">
                                        <div>
                                            <p>Fecha Apertura:
                                                <span>{{clientCerradoTicketSelected.fechaCreacionTicket}}</span></p>
                                            <p>Tècnico: <span>{{clientCerradoTicketSelected.tecnico}}</span></p>
                                            <p>Fecha Cierre:
                                                <span>{{clientCerradoTicketSelected.fechaCierreTicket}}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cliente">Cliente #{{clientCerradoTicketSelected.idCliente}}</label>
                                        <input disabled v-model="clientCerradoTicketSelected.cliente" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="cliente">Apellido</label>
                                        <input disabled v-model="clientCerradoTicketSelected.apellido" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="telefonoCliente">Telèfono Cliente</label>
                                        <input disabled v-model="clientCerradoTicketSelected.telefono" type="number">
                                    </div>
                                    <div class="form-group">
                                        <label for="telefono">Telèfono Contacto</label>
                                        <input disabled v-model="clientCerradoTicketSelected.telefonoContacto"
                                            type="number" name="telefono">
                                    </div>
                                    <div class="form-group">
                                        <label for="clientAdd">Direcciòn</label>
                                        <input disabled v-model="clientCerradoTicketSelected.direccion" type="text"
                                            name="clientAdd">
                                    </div>
                                    <div class="form-group">
                                        <label for="mail">Email de cliente</label>
                                        <input disabled v-model="clientCerradoTicketSelected.email" type="email"
                                            name="mail">
                                    </div>
                                    <div class="form-group">
                                        <label for="ipAddress">Ip Address</label>
                                        <input disabled placeholder="Ingrese Direcciòn Ip"
                                            v-model="clientCerradoTicketSelected.ip" type="text" name="ipAddress">
                                    </div>
                                    <div class="form-group">
                                        <label for="routerModel">Marca de Router</label>
                                        <input disabled v-model="clientCerradoTicketSelected.router" type="text"
                                            name="routerModel">
                                    </div>
                                    <div class="form-group">
                                        <label for="routerMacAddress">MAC Address Router</label>
                                        <input disabled v-model="clientCerradoTicketSelected.macRouter" type="text"
                                            name="routerMacAddress" placeholder="0-9 & A-F">
                                    </div>
                                    <div class="form-group">
                                        <label for="antenaMacAddress">MAC Address Antena</label>
                                        <input disabled v-model="clientCerradoTicketSelected.macAntena" type="text"
                                            placeholder="0-9 & A-F">
                                    </div>

                                    <div class="select-group">
                                        <div class="form-group-select">
                                            <label for="inyectorPoe">Inyector POE</label>
                                            <select disabled v-model="clientCerradoTicketSelected.inyectorPoe">
                                                <option value="inyectorUbiquiti">Ubiquiti</option>
                                                <option value="inyectorMikrotik">Mikrotik</option>
                                                <option value="inyectorOtro">Otro</option>
                                            </select>
                                        </div>
                                        <div class="form-group-select">
                                            <label for="Apuntamiento">Apuntamiento</label>
                                            <select disabled v-model="clientCerradoTicketSelected.apuntamiento">
                                                <option value="montecristo">Montecristo</option>
                                                <option value="retiro">Retiro</option>
                                                <option value="calizas">Orlando-Calizas</option>
                                                <option value="alcaravan">Alcaravan</option>
                                                <option value="sapitos">Sapitos</option>
                                                <option value="santa-ana-1">Santa Ana 1</option>
                                                <option value="santa-ana-2">Santa Ana 2</option>
                                                <option value="vereda-centro">Vereda Centro</option>
                                                <option value="barrio-costeños">Brr Costeños</option>
                                                <option value="torre-guamal">Torre Guamal</option>
                                                <option value="torre-castilla">Torre Castilla</option>
                                                <option value="torre-mundo-mujer">Torre Mundo Mujer</option>
                                                <option value="humadea">Humadea</option>
                                                <option value="koinonia">Koinonia</option>
                                                <option value="koinonia">Rep Guamal Plaza</option>
                                                <option value="koinonia">REp Geinpetrol</option>
                                            </select>
                                        </div>
                                        <div class="form-group-select">
                                            <label>Acceso Remoto habilitado</label>
                                            <select disabled v-model="clientCerradoTicketSelected.accesoRemoto">
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group-select">
                                            <label for="tipo-antena">Tipo de Antena</label>
                                            <select disabled v-model="clientCerradoTicketSelected.tipoAntena">
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
                                                <option value="ubiquiti-picostation">Ubiquiti, PICOSTATION</option>
                                                <option value="ubiquiti-otro">Ubiquiti, OTRO</option>
                                            </select>
                                        </div>
                                        <div class="form-group-select">
                                            <label for="tipo-instalacion">Tipo de Instalaciòn</label>
                                            <select disabled v-model="clientCerradoTicketSelected.tipoInstalacion">
                                                <option value="repetidor">X Repetidor</option>
                                                <option value="antena">X Antena</option>
                                            </select>
                                        </div>
                                        <div class="form-group-select">
                                            <label for="tipo-soporte">Tipo de Soporte realizado</label>
                                            <select disabled v-model="clientCerradoTicketSelected.tipoSoporte">
                                                <option value="varios">Varios. Cuàles?</option>
                                                <option value="ampliar-velocidad">Ampliaciòn de velocidad</option>
                                                <option value="instalacion">Instalaciòn Nuevo Servicio</option>
                                                <option value="traslado">Traslado</option>
                                                <option value="dano-antena">Daño de Antena</option>
                                                <option value="clave">Cambio de clave</option>
                                                <option value="dano-router">Daño de router</option>
                                                <option value="dano-router">Daño de switch</option>
                                                <option value="dano-cable">Daño de cable</option>
                                                <option value="instalacionServicio">Instalacion de servicio</option>
                                                <option value="direccionamiento">Direccionamiento de antena. Por què?</option>
                                                <option value="retiro">Retirar Equipos</option>
                                                <option value="router-desconfigurado">Router Desconfigurado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="diagnostico">Solicitud de Cliente</label>
                                        <textarea disabled v-model="clientCerradoTicketSelected.solicitudCliente"
                                            cols=""></textarea>
                                    </div>
                                    <div class="form-group ticket-closed-solution">
                                        <label for="solucion">Describir soluciòn</label>
                                        <textarea disabled v-model="clientCerradoTicketSelected.solucion" cols=""
                                            minlength="30" placeholder="Si recibe dinero, especificar"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Sugerencia de adminstrador</label>
                                        <textarea disabled v-model="clientCerradoTicketSelected.sugerenciaSolucion"
                                            cols=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Recomendaciones a cliente</label>
                                        <textarea disabled v-model="clientCerradoTicketSelected.recomendaciones"
                                            cols=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="cargar-a-factura-valor">Cargar a factura valor</label>
                                        <input disabled v-model="clientCerradoTicketSelected.cargarAfacturaVaĺor"
                                            type="number" name="cargar-a-factura-valor">
                                    </div>
                                    <div class="form-group">
                                        <label for="cargar-a-factura-descripcion">Cargar a factura descripciòn</label>
                                        <textarea disabled
                                            v-model="clientCerradoTicketSelected.cargarAfacturaDescripcion"
                                            cols=""></textarea>
                                    </div>
                                    <div class="evidencia-fotografica">
                                        <div class="form-group-evidencia">
                                            <label>Evidencia Fotogràfica</label>
                                            <div class="img-container">
                                                <img :src="'img/'+clientCerradoTicketSelected.evidenciaFotografica1"
                                                    alt="evidencia 1">
                                            </div>
                                            <p><a :href="'img/'+clientCerradoTicketSelected.evidenciaFotografica1" target="_blank">Abrir</a></p>
                                        </div>
                                        <div class="form-group-evidencia">
                                            <label>Evidencia Fotogràfica</label>
                                            <div class="img-container">
                                                <img :src="'img/'+clientCerradoTicketSelected.evidenciaFotografica2"
                                                alt="evidencia 2">
                                            </div>
                                            <p><a :href="'img/'+clientCerradoTicketSelected.evidenciaFotografica2" target="_blank">Abrir</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer-modal">
                                    <span class="icon-cancel" @click="continueToClosedTicketsModal(false)"></span>
                                </div>
                            </form>
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
            <span>FastNet- Adminstraciòn Redes </span>
        </div>
    </footer>
</body>
<script src="../bower_components/alertify/js/alertify.min.js"></script>
<script>
var app = new Vue({
    el: "#app",
    data: {
        url: "img/persona3.jpg",
        url2: "img/persona2.jpg",
        searchClientContent: "",
        clientes: [],
        clientNewTicketSelected: [],
        ticketsAbiertos: [],
        clientAbiertoTicketSelected: [],
        ticketsCerrados: [],
        clientCerradoTicketSelected: [],
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
        newClientCheck: false,
        file1: "",
        file2: "",
        img1Error: "",
        img2Error: ""
    },
    methods: {
        continueToAbiertoTicketModal: function(data) {
            this.hideTicketResult = true
            if (data) {
                this.hideTicketAbiertoModal = false
            } else
                this.hideTicketAbiertoModal = true
        },
        continueToClosedTicketsModal: function(data) {
            this.hideTicketResult = true
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
                if (this.clientNewTicketSelected.telefonoContacto.length != 10) {
                    this.clientNewTicketSelected.telefonoContacto = ""
                    valid = false
                }
            }
            // if (!this.validateIpAddress(this.clientNewTicketSelected.ip)) {
            //     this.clientNewTicketSelected.ip = ""
            //     //valid = false
            // }
            if (valid) {
                let r = confirm("Confirmar!");
                if (r) {
                    this.saveNewticket()
                    this.hideResultModal = true
                }
            }
        },
        saveNewticket: function() {
            axios.get('../saveNewTicket.php', {
                params: {
                    ticketData: this.clientNewTicketSelected
                }
            }).then(response => {
                console.log("Respuesta a salvar ticket:" + response.data)
                if (response.data == "savedEmailOk") {
                    this.getTicketCerrado()
                    this.getTicketAbierto()
                    alertify.success("Ticket cerrado y guardado con èxito.")
                }
                if (response.data == "savedEmailNo") {
                    this.getTicketCerrado()
                    this.getTicketAbierto()
                    alertify.success("Ticket  guardado con èxito.")
                    alertify.error("No fue posible enivar el email!")
                }
                if (response.data != "savedEmailOk" && response.data != "savedEmailNo") {
                    this.getTicketCerrado()
                    this.getTicketAbierto()
                    alertify.error("No fue posible guardar el ticket!!!")
                }
            }).catch(e => {
                console.log("error: " + e)
            })
        },
        checkFormCerrarTicket: function() {
            let valid = true
            if (!this.validateIpAddress(this.clientAbiertoTicketSelected.ip)) {
                this.clientAbiertoTicketSelected.ip = ""
                valid = false
            }
            if (!this.validateMacAddress(this.clientAbiertoTicketSelected.macRouter)) {
                this.clientAbiertoTicketSelected.macRouter = ""
                valid = false
            }
            if (!this.validateMacAddress(this.clientAbiertoTicketSelected.macAntena)) {
                this.clientAbiertoTicketSelected.macAntena = ""
                valid = false
            }
            if (valid) {
                let r = confirm("Confirmar, el email a usar es:\t" + this.clientAbiertoTicketSelected
                    .email);
                if (r) {
                    this.saveFormCerrarticket()
                    this.hideTicketAbiertoModal = true
                }
            }
        },
        saveFormCerrarticket: function() {
            axios.get('../saveClosingTicket.php', {
                params: {
                    ticketData: this.clientAbiertoTicketSelected
                }
            }).then(response => {
                if (response.data == "updatedEmailOk") {
                    this.getTicketCerrado()
                    this.getTicketAbierto()
                    alertify.success("Ticket cerrado y guardado con èxito.")
                }
                if (response.data == "updatedEmailNo") {
                    this.getTicketCerrado()
                    this.getTicketAbierto()
                    alertify.success("Ticket  guardado con èxito.")
                    alertify.error("No fue posible enivar el email!")
                }
                if (response.data != "updatedEmailOk" && response.data != "updatedEmailNo") {
                    this.getTicketCerrado()
                    this.getTicketAbierto()
                    alertify.error("No fue posible guardar el ticket!!!") //vueltas del almuerzo
                }

            }).catch(e => {
                console.log("error: " + e)
            })
        },
        continueToResultModal: function(data) {
            if (data) {
                this.hideTicketResult = true
                this.hideResultModal = false
            } else
                this.hideResultModal = true
        },
        closeResultTable: function() {
            this.hideTicketResult = true
        },
        searchClient: function() {
            this.getUser()
        },
        getUser: function() {
            axios.get('../fetchClientList.php', {
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
            axios.get('../fetchTicketAbiertos.php', {}).then(response => {
                this.totalRowsAbiertos = response.data.length
                this.ticketsAbiertos = response.data

            }).catch(e => {
                console.log('error' + e)
            })
        },
        getTicketCerrado: function() {
            axios.get('../fetchTicketCerrados.php', {}).then(response => {
                this.totalRowsCerrados = response.data.length
                this.ticketsCerrados = response.data
            }).catch(e => {
                console.log('error' + e)
            })
        },
        selectedRowNewTicket: function(id, client, clientObject) {
            this.newTicketSelectedId = id
            this.newTicketSelectedClient = client
            this.clientNewTicketSelected = clientObject
            this.clientNewTicketSelected.ipBackup = this.clientNewTicketSelected.ip
        },
        selectedRowTicketAbiero: function(id, ticketSelectedObjet) {
            this.abiertoTicketSelectedClient = ticketSelectedObjet.cliente + " " + ticketSelectedObjet
                .apellido
            this.abiertoTicketSelectedId = id
            this.clientAbiertoTicketSelected = ticketSelectedObjet
        },
        selectedRowTicketCerrado: function(id, ticketSelectedObjet) {
            this.cerradoTicketSelectedClient = ticketSelectedObjet.cliente + " " + ticketSelectedObjet
                .apellido
            this.cerradoTicketSelectedId = id
            this.clientCerradoTicketSelected = ticketSelectedObjet
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

        handleFileUpload: function(image) {
            var vm = this
            if (image == 1) {
                this.img1Error = "Uploading..."
                vm.url = ""
            }
            if (image == 2) {
                this.img2Error = "Uploading..."
                vm.url2 = ""
            }
            let formData = new FormData()
            if (image == 1) {
                this.file1 = this.$refs.file1.files[0]
                console.log("file 1 es:" + this.file1.name)
                formData.append('file1', this.file1)
                this.clientAbiertoTicketSelected.evidenciaFotografica1 = this.file1.name
            }
            if (image == 2) {
                this.file2 = this.$refs.file2.files[0]
                formData.append('file2', this.file2)
                this.clientAbiertoTicketSelected.evidenciaFotografica2 = this.file2.name
            }
            axios.post('../uploadImage.php', formData, {
                header: {
                    'content-type': 'multipart/form-data'
                }
            }).then(function(response) {
                if (image == 1) vm.img1Error = "Waiting..."
                if (image == 2) vm.img2Error = "Waiting..."
                if (response.data.stat == "error") {
                    if (response.data.source == "file1") {
                        vm.img1Error = "Error.." + response.data.msj
                        vm.url = ""
                    }
                    if (response.data.source == "file2") {
                        vm.img2Error = "Error.." + response.data.msj
                        vm.url2 = ""
                    }
                } else {
                    if (response.data.source == "file1") {
                        vm.img1Error = "Waiting...."
                        vm.url = "img/" + vm.file1.name
                        vm.img1Error = ""
                    }
                    if (response.data.source == "file2") {
                        vm.img2Error = "Waiting...."
                        vm.url2 = "img/" + vm.file2.name
                        vm.img2Error = ""
                    }
                }


            }).catch(function(error) {
                console.log("error!!!" + error)
            })
        }
    },
    mounted() {
        this.getTicketAbierto()
        this.getTicketCerrado() 
    },
});
</script>
</body>

</html> 