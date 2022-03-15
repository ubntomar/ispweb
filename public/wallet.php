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
                <h1>ADMINISTRAR BILLETERA DEL CLIENTE</h1>
            </div>
            <div class=box-container>
                <div class="box box-new-ticket">
                    <div class="title">
                        <h3><i class="icon-user"></i> Primero, busquemos el cliente!</h3>
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
                                        <p>Selecciona cliente</p>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Cédula</th>
                                                    <th>Cliente</th>
                                                    <th>Direcciòn</th>
                                                    <th>Telèfono</th>
                                                    <th class="close-result-table">
                                                        <div><span>Disponible en Billetera!</span></div>
                                                        <div><button @click="closeResultTable"
                                                                class="icon-cancel"></button></div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="client in clientes" :key="client.id"
                                                    @click="selectedRowNewWallet(client.id,client.cliente+' '+client.apellido,client)">
                                                    <td>{{client.cedula}}</td>
                                                    <td>{{client.cliente}} {{client.apellido}}</td>
                                                    <td>{{client.direccion}}</td>
                                                    <td>{{client.telefono}}</td>
                                                    <td v-if="(client.wallet!=''||client.wallet!=0)">${{client.wallet}}</td>
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
                                        <p>A quien va a cargar dinero?</p>
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
                        <form v-on:submit.prevent="checkFormNewWallet()">
                            <div class="title-modal">
                                <h3>Agregar dinero a Billetera(cuenta)  personal</h3>
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
                                <div class="form-group new-cli">
                                    <label class="wallet-current-money"   for="money"><p>Actualmente dispone:</p><p>${{clientNewTicketSelected.wallet}}</p><p>pesos</p></label>
                                    <input type="number" step="1" min="0" required placeholder="Cuánto dinero desea agregar?"
                                    v-model="clientNewTicketSelected.money">
                                    <label class="wallet-new-money"  >Nuevo saldo <p>${{(clientNewTicketSelected.wallet>=0?clientNewTicketSelected.wallet*1:0*1)+(clientNewTicketSelected.money>=0?clientNewTicketSelected.money*1:0*1)}}</p></label>
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
                        <h3><i class="icon-wrench"></i> Ultimos movimientos</h3>
                    </div>
                    <div>
                        <div class="box-content">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Recarga</th>
                                        <th>Fecha</th>
                                        <th>Cajero</th>
                                        <th>Disponible</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="WalletList in WalletsList" :key="WalletList.id">
                                        <td>{{WalletList.cliente}} {{WalletList.apellido}}</td>
                                        <td><strong>${{new Intl.NumberFormat('de-DE').format(WalletList.recarga)}}</td>
                                        <td>{{WalletList.date}}</td>
                                        <td>{{WalletList.cajero}}</td>
                                        <td><strong>${{new Intl.NumberFormat('de-DE').format(WalletList.nuevoSaldo)}}</strong></td>
                                    </tr>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
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
<?=$htmlObject->jsScript($path="../")?>
</body>

<script>
var app = new Vue({
    el: "#app",
    data: {
        url: "img/persona3.jpg",
        url2: "img/persona2.jpg",
        searchClientContent: "",
        clientes: [],
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
        hideResultModal: true,
        hideWalletListModal: true,
        newClientCheck: false,
        file1: "",
        file2: "",
        img1Error: "",
        img2Error: ""
    },
    methods: {
        continueToAbiertoWalletModal: function(data) {
            this.hideTicketResult = true
            if (data) {
                this.hideWalletListModal = false
            } else
                this.hideWalletListModal = true
        },
        
        checkFormNewWallet: function() {
            let valid = true
            if(this.clientNewTicketSelected.money==0){
                this.clientNewTicketSelected.money="";
                valid=false
            }
            
            
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
                    this.clientNewTicketSelected.wallet=(this.clientNewTicketSelected.wallet>=0?this.clientNewTicketSelected.wallet*1:0*1)+(this.clientNewTicketSelected.money>=0?this.clientNewTicketSelected.money*1:0*1)
                    console.log("wallet value:${wallet}"+this.clientNewTicketSelected.wallet)
                    this.saveNewWallet()
                    this.hideResultModal = true
                }
            }
        },
        saveNewWallet: function() {
            axios.get('../controller/axios/saveNewWallet.php', {
                params: {
                    ticketData: this.clientNewTicketSelected
                }
            }).then(response => {
                console.log("Respuesta:" + JSON.stringify(response.data))//JSON.stringify
                
                if (response.data.client == true) {
                    alertify.success("Dinero fue agregado con éxito!")
                }else{
                    alertify.error("Error al cargar el dinero al cliente, favor comunicarse con el administrador del sistema")

                }
                if (response.data.idWallet > 0 ) { 
                    alertify.success("Transaccion realizada con éxito!")
                    this.getWallet()
                }else{
                    alertify.error("Error al crear el registro de transacción de dinero, favor comunicarse con el administrador del sistema")
                }
                if (response.data.sms == "success" ) {
                    alertify.success("Sms enviado con éxito!")
                }else{
                    alertify.error("Error al enviar SMS")
                }
                if (response.data.email) {
                    alertify.success("Email enviado con éxito!")
                }else{
                    alertify.error("Error al enviar Email")
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
        this.getWallet()
    },
});
</script>
</body>

</html>