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
	$empresa = $_SESSION['empresa'];
}
if($_SESSION['role']=='tecnico'){
	header('Location: tick.php');
}
require("../login/db.php");
require("../components/views/TemplateDark.php");
require("../components/views/Html.php");
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("d-m-Y"); 
$hourMin = date('H:i');
$templateObject=new TemplateDark();    
$htmlObject=new Html();
?>
<!DOCTYPE html>
<html lang="es">
<?=$htmlObject->head($path="../")?> 

<body>
    <?=$templateObject->header($user="omar")?>
    <?=$templateObject->navTop($_SESSION['role'],$path="")?>
    <main id="app">
        <?=$templateObject->navLeft($_SESSION['role'],$path="")?>
        <section>
            <div class="section-title">
                <img src="img/support.png" alt="">
                <h1>FACTURAS</h1>
            </div>
            <div class=box-container>
                <div class="box box-new-ticket">
                    <div class="title">
                        <h3><i class="icon-user"></i>Busquemos el cliente</h3>
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
                                <h3 class="icon-docs">SELECCIONA  DE LA LISTA</h3>
                            </div>
                            <div class="result-content">
                                <div class="result-container">
                                    <div>
                                        <p>Selecciona cliente</p>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Cédula</th>
                                                    <th>Cliente</th>
                                                    <th>Direcciòn</th>
                                                    <th>Telèfono</th>
                                                    <th class="close-result-table">
                                                        
                                                        <div><button @click="closeResultTable"
                                                                class="icon-cancel"></button></div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr :class="{'rowSelected':client.id==selectedId}"  v-for="client in clientes" :key="client.id"
                                                    @click="selectedRowNewWallet(client.id,client.cliente+' '+client.apellido,client)">
                                                    <td>{{client.cedula}}</td>
                                                    <td>{{client.cliente}} {{client.apellido}}</td>
                                                    <td>{{client.direccion}}</td>
                                                    <td>{{client.telefono}}</td>
                                                    <td></td>
                                                </tr>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td>{{totalRows}} rows</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>

                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="aditional-info" v-if="info.planPrice">
                                        <p> Id:{{info.id}} Velocidad {{info.speed}} Megas -- Plan de ${{info.planPrice}}</p>
                                    </div>
                                    <div v-if="menuBill" class="selected-client">
                                        <input type="hidden" :value="newTicketSelectedClient" disabled
                                            placeholder="Cliente Seleccionado">
                                        <input type="hidden" id="newTicketForId" :value="newTicketSelectedId">
                                        <div class="selected-client__menu">
                                            <p :class="billTypeClass.plan" @click="type('plan')">Mi Plan de Internet</p>
                                            <p id="p":class="billTypeClass.bill" @click="type('bill')">Mis Facturas</p>
                                        </div>
                                        <input type="hidden" :value="billType" disabled
                                            placeholder="Selected">
                                        <div class="selected-client__submit">
                                            <button @click="closeResultTable" class="button-danger">Cancelar</button>
                                            <button :class="{'button-success':billType,'button-disabled':!billType}"  @click="continueToResultModal(true)"
                                                :disabled="!billType">Continuar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box new-ticket " v-bind:class="{'hide':hideResultModalPlan}">
                    <div class="new-ticket-modal-content">
                        <form id="formFactData" method="POST" v-on:submit.prevent="checkFormsaveData()">
                            <div class="title-modal">
                                <h3>PLAN DEL CLIENTE</h3>
                            </div>
                            <div class="form-new-ticket">
                                <div class="form-group new-cli">
                                    <label for="cli">Cliente</label>
                                    <input disabled type="text" id="cliente" :value="clientDataSaveSelected.cliente">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="apellido">Apellido</label>
                                    <input disabled type="text" id="apellido" :value="clientDataSaveSelected.apellido">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="clientTelefono">Telèfono de Cliente</label>
                                    <input disabled type="number" v-model="clientDataSaveSelected.telefono"
                                        placeholder="10 digitos">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="telefonoContacto">Telèfono Adicional ::<input disabled type="checkbox"
                                            v-model="newClientCheck"></label>
                                    <input type="number" v-model="clientDataSaveSelected.telefonoContacto"
                                        placeholder="10 digitos" :disabled="!newClientCheck" :required="newClientCheck">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="direccion">Direcciòn</label>
                                    <input disabled type="text" v-model="clientDataSaveSelected.direccion">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="ciudad">Ciudad</label>
                                    <input required type="text" v-model="clientDataSaveSelected.ciudad" disabled
                                        placeholder="Guamal?Castila?Acacias?">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="email">Email de cliente</label>
                                    <input type="email" v-model="clientDataSaveSelected.email">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="ipAddre">Ip Address</label>
                                    <input type="text"  :placeholder="clientDataSaveSelected.ipBackup" disabled
                                        v-model="clientDataSaveSelected.ip">
                                </div>
                                <div class="form-group   new-cli">
                                    <strong class="strong--green" for="speed">Velocidad de Bajada(Megas)</strong>
                                    <input type="number" step="0.5" placeholder="" id="speed" v-model="clientDataSaveSelected.speed" required  >
                                </div>
                                <div class="form-group   new-cli">
                                    <strong class="strong--green" for="planPrice">Valor de Plan</strong>
                                    <input type="number" step="5000" placeholder="p.e 55000" id="planPrice" v-model="clientDataSaveSelected.planPrice" required > 
                                </div>
                                <div class="form-group  new-cli">
                                    <label for="fechaSugerida">Fecha Actual</label>
                                    <input type="text" disabled placeholder="<?=$today?>">
                                </div>
                                <div class="form-group new-cli">
                                    <label>Hora Actual</label>
                                    <input type="text" disabled placeholder="<?=$hourMin?> am"> 
                                </div>
                            </div>
                           
                            <div class="footer-modal">
                                <input type="submit" value="Enviar" :class="{'hide':info.speed==clientDataSaveSelected.speed&&info.planPrice==clientDataSaveSelected.planPrice}"   ><span class="icon-cancel"
                                    @click="continueToResultModal(false)"></span> 
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box new-ticket " v-bind:class="{'hide':hideResultModalBill}">
                    <div class="new-ticket-modal-content">
                        <form v-on:submit.prevent="checkFormNewPlan()">
                            <div class="title-modal">
                                <h3>BILL DEL CLIENTE</h3>
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
                                    <input disabled type="text" v-model="clientNewTicketSelected.direccion">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="ciudad">Ciudad</label>
                                    <input required type="text" v-model="clientNewTicketSelected.ciudad" disabled
                                        placeholder="Guamal?Castila?Acacias?">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="email">Email de cliente</label>
                                    <input type="email" v-model="clientNewTicketSelected.email">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="ipAddre">Ip Address</label>
                                    <input type="text"  :placeholder="clientNewTicketSelected.ipBackup" disabled
                                        v-model="clientNewTicketSelected.ip">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="fechaSugerida">Fecha Actual</label>
                                    <input type="text" disabled placeholder="<?=$today?>">
                                </div>
                                <div class="form-group new-cli">
                                    <label>Hora Actual</label>
                                    <input type="text" disabled placeholder="<?=$hourMin?> am"> 
                                </div>
                            </div>
                            <div class="facts">
                                <div class="facts--table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>FACTURA</th>
                                                <th>ITEM</th>
                                                <th>VALOR</th>
                                                <th>NOTA</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>12</td>
                                                <td>Tenda</td>
                                                <td>$60.000</td>
                                                <td>Cambio de Router</td>
                                                <td>Edit-Delete</td>
                                            </tr>
                                            <tr>
                                                <td>12</td>
                                                <td>Tp-Link</td>
                                                <td>$55.000</td>
                                                <td>Cambio de Router</td>
                                                <td>Edit-Delete</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="1"><button>Nuevo</button></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="facts--newFact">
                                    <h5>CREAR NUEVA FACTURA</h5>
                                    <form action="#" id="newFact">
                                        <div class="form-group form-group--100"> 
                                            <label for="item">Item</label>
                                            <input type="text" name="item" id="item">
                                        </div>  
                                        <div class="form-group form-group--100">
                                            <label for="itemValue">Valor</label>
                                            <input type="number" name="itemValue" id="itemValue" step="5000">
                                        </div>  
                                                     
                                    </form>
                                </div>
                            </div>

                            <div>
                            
                            </div>
                            <div class="footer-modal">
                                <input type="submit" value="Enviar"><span class="icon-cancel"
                                    @click="continueToResultModal(false)"></span>
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
<?=$htmlObject->jsScript($path="../")?>
<script>
var app = new Vue({
    el: "#app",
    data: {
        
        searchClientContent: "",
        clientes: [],
        clientDataSaveSelected: [],
        clientNewTicketSelected: [],
        WalletsList: [],
        clientAbiertoTicketSelected: [],
        totalRows: "",
        totalRowsAbiertos: "",
        newTicketSelectedClient: "",
        newTicketSelectedId: "",
        abiertoTicketSelectedClient: "",
        abiertoTicketSelectedId: "",
        hideTicketResult: true,
        hideResultModalPlan: true,
        hideResultModalBill: true,
        hideWalletListModal: true,
        newClientCheck: false,
        billType:null,
        billTypeClass:{
            plan:'selected-client__menu-p',
            bill:'selected-client__menu-p'
        },
        selectedId:null,
        menuBill:null,
        info:{
            speed:null,
            planPrice:null
        },

    },
    methods: {
        continueToAbiertoWalletModal: function(data) {
            this.hideTicketResult = true
            if (data) {
                this.hideWalletListModal = false
            } else
                this.hideWalletListModal = true
        },
        type:function(opc){
            this.billType=opc
            this.billTypeClass.plan=opc==='plan'?'selected-client__menu-p selected-client__menu--selected':'selected-client__menu-p'
            this.billTypeClass.bill=opc==='bill'?'selected-client__menu-p selected-client__menu--selected':'selected-client__menu-p'
        },  
        checkFormsaveData:function(){
            console.log("Actualizar la info en la base de datos")
            const data = new FormData();
            data.append('option', 'updateClient');
            data.append('id', this.clientDataSaveSelected.id);
            data.append('planPrice', this.clientDataSaveSelected.planPrice);
            data.append('speed', this.clientDataSaveSelected.speed);
            fetch('../controller/bill/billAPI.php', {
            method: 'POST',
            body: data
            }).then(response=> {
                if(response.ok) {
                    return response.text()
                } else {
                    throw "Error en la llamada";
                }
                }).then(texto=> {
                    console.log(texto);
                }).catch(err=> {
                    console.log(err); });

        },

        continueToResultModal: function(data) {
            
            if(this.billType==='plan'){
                this.hideResultModalPlan = false
                this.hideResultModalBill = true
                this.hideTicketResult = false
            }
            if(this.billType==='bill'){
                this.hideResultModalPlan = true
                this.hideResultModalBill = false
                this.hideTicketResult = false
            }
            if(!data){
                this.hideTicketResult = true
                this.hideResultModalPlan = true
                this.hideResultModalBill = true
            }
            
        },
        closeResultTable: function() {
            this.hideTicketResult = true
            this.info.speed=null
            this.info.planPrice=null
        },
        searchClient: function() {
            this.info={}
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
        getWallet: function() {
            axios.get('../controller/axios/walletList.php', {
            }).then(response => {
                this.WalletsList = response.data
            }).catch(e => {
                console.log('error' + e)
            })
        },
        selectedRowNewWallet: function(id, client, clientObject) {
            this.newTicketSelectedId = id
            this.newTicketSelectedClient = client
            this.clientDataSaveSelected = clientObject
            this.clientDataSaveSelected.ipBackup = this.clientDataSaveSelected.ip,
            this.selectedId=id
            this.menuBill=true
            this.info.id=clientObject.id
            this.info.speed=clientObject.speed
            this.info.planPrice=clientObject.planPrice
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
        this.getWallet()
    },
});
</script>
</body>

</html>