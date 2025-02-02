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
    <?=$templateObject->header($user)?>
    <?=$templateObject->navTop($_SESSION['role'],$path="")?>
    <main id="app">
        <?=$templateObject->navLeft($_SESSION['role'],$path="")?>
        <section>
            <div  :class="{'message message__success message--show':message.status,'message message__danger message--show':!message.status,'hide':!message.show,'show':message.show}">
                <p>{{message.text}}</p>
            </div>
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
                        <p v-if="resultsAlertToShow">NO se encuentra usuario, se recomienda buscar  por apellido también</p>
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
                                        <div class="selected-client__menu">
                                            <p :class="billTypeClass.plan" @click="type('plan')">Mi Plan de Internet</p>
                                            <p id="p":class="billTypeClass.bill" @click="type('bill')">Mis Facturas</p>
                                        </div>
                                        <input type="hidden" :value="billType" disabled
                                            placeholder="Selected">
                                        <div class="selected-client__submit">
                                            <button @click="closeResultTable" class="button-danger">Cancelar</button>
                                            <button :class="{'button-success':billType,'button-disabled':!billType,'button-disabled':!selectedId}"  @click="continueToResultModal(true)"
                                                :disabled="!billType||!selectedId">Continuar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box new-ticket " v-bind:class="{'hide':hideResultModalPlan}">
                    <div class="new-ticket-modal-content">
                        <form id="formFactData" method="POST" v-on:submit.prevent="checkFormAndSaveData()">
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
                                    <label for="corte">Corte  </label>
                                    <input type="text"  :placeholder="clientDataSaveSelected.corte" v-model="clientDataSaveSelected.corte" required>
                                    <p :class="{'text-danger':message2.text}">{{message2.text}}</p>
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
                                <input type="submit" value="Enviar" :class="{'hide':info.speed==clientDataSaveSelected.speed&&info.planPrice==clientDataSaveSelected.planPrice&&info.corte==clientDataSaveSelected.corte}"   ><span class="icon-cancel"
                                    @click="continueToResultModal(false)"></span> 
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box new-ticket " v-bind:class="{'hide':hideResultModalBill}">
                    <div class="new-ticket-modal-content">
                        <form  method="POST" v-on:submit.prevent="formBills()">
                            <div class="title-modal">
                                <h3>FACTURAS</h3>
                                <div class="title-modal--close">
                                    <h4 @click="hideResultModalBill=true">X</h4>
                                </div>
                            </div>
                            <div v-if="!editEnabled" class="form-new-ticket">
                                <div class="form-group new-cli">
                                    <label for="cli">Cliente</label>
                                    <input disabled type="text" id="cli" :value="`[${clientDataSaveSelected.id}] ${clientDataSaveSelected.cliente}`">
                                </div>
                                <div class="form-group new-cli">
                                    <label for="apellido">Apellido</label>
                                    <input disabled type="text" id="apellido" :value="clientDataSaveSelected.apellido">
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
                            </div>
                            <div class="facts">
                                <div v-if="!editEnabled" class="facts--table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>COD</th>
                                                <th>ITEM</th>
                                                <th>VALOR</th>
                                                <th>SALDO</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for=" bill in bills"  >
                                                <td>{{ bill['id-factura'] }}</td>
                                                <td>{{bill.periodo}}</td>
                                                <td>${{bill.valorf}}</td>
                                                <td>${{bill.saldo}}</td>
                                                <td><button type="button"  @click="billAction('editar',bill)" class="button-edit">Editar</button><button type="button" @click="billAction('borrar',bill)" class="button-delete">Borrar</button></td>
                                            </tr>
                                            
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="1"></td>
                                                <td colspan="4">
                                                    <button @click="billAction('',{},'new')" class="button-success">Agregar</button>
                                                    <input @click="delConfirm=''" type="text" placeholder="Borrar?" v-model="delConfirm" >
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div v-if="billsBox" class="facts--newFact">
                                        <h5>{{billActionOptionSelected}} FACTURA </h5>
                                        <p class="facts--newFact-descr">{{clientDataSaveSelected.id}}:{{clientDataSaveSelected.cliente}} {{clientDataSaveSelected.apellido}} : {{clientDataSaveSelected.direccion}}</p>
                                        <div class="form-group form-group--100"> 
                                            <label for="item">Item a Facturar</label>
                                            <input  type="text" name="item" v-model="billDataToBox.item" required>
                                        </div>  
                                        <div class="form-group form-group--100">
                                            <label for="itemValue">Valor</label>
                                            <input  type="number" name="itemValue" v-model="billDataToBox.valor" step="1000" required>
                                        </div>  
                                        <div class="form-group form-group--100">
                                            <label for="itemValue">Saldo</label>
                                            <input  type="number" name="itemValue" v-model="billDataToBox.saldo"  min="1" required>
                                        </div>  
                                        <div class="form-group form-group--100">
                                            <label for="notaValue">Nota</label>
                                            <input  type="text" name="notaValue" v-model="billDataToBox.nota" >
                                        </div>  
                                    <div class="footer-modal">
                                        <input v-if="applyEnabled" type="submit" value="Aplicar" ><span class="icon-cancel"
                                        @click="billsBox=false,editEnabled=false"></span>
                                    </div>                
                                </div>
                                
                            </div>
                            
                            <div>
                            
                            </div>
                            
                        </form>
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
<?=$htmlObject->jsScript($path="../")?>
<script>
var app = new Vue({
    el: "#app",
    data: {
        billEndPoint:'../controller/bill/billAPI.php',
        searchClientContent: "",
        clientes: [],
        clientDataSaveSelected: [],
        WalletsList: [],
        clientAbiertoTicketSelected: [],
        totalRows: "",
        totalRowsAbiertos: "",
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
            planPrice:null,
            corte:null
        },
        message:{
            text:"Falló al hacer la operación",
            status:false,
            show:false
        },
        message2:{
            text:""
        },
        billsBox:false,
        bills:{},
        applyEnabled:false,
        editEnabled:false,
        billDataToBox:{},
        billActionOptionSelected:null,
        formTask:null,
        delConfirm:null,
        resultsAlertToShow: false
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
        checkFormAndSaveData:function(){

            if (!this.validateCorte(this.clientDataSaveSelected.corte)) {
                this.message2.status = false;
                this.message2.show = true;
                this.message2.text = "El corte debe estar entre 2 y 28 y no puede ser 15";
                return;
            }else{
                this.message2.text = "";
            }
            console.log("Actualizar la info en la base de datos")
            const data = new FormData();
            data.append('option', 'updateClient');
            data.append('id', this.clientDataSaveSelected.id)
            if(this.info.speed!=this.clientDataSaveSelected.speed) data.append('speed', this.clientDataSaveSelected.speed)
            if(this.info.planPrice!=this.clientDataSaveSelected.planPrice)data.append('planPrice', this.clientDataSaveSelected.planPrice) 
            if(this.info.corte!=this.clientDataSaveSelected.corte)data.append('corte', this.clientDataSaveSelected.corte) 
            
            fetch(this.billEndPoint, {
            method: 'POST',
            body: data
            }).then(response=> {
                    return  response.json()
                }).then(result=> {
                    this.continueToResultModal(false)
                    this.message.status=true
                    this.message.show=true
                    this.message.text=`Datos actualizados [speed:${result[0].speed}] [pago:${result[1].pago}] a ${this.clientDataSaveSelected.cliente} ${this.clientDataSaveSelected.apellido} `
                }).catch(err=> {
                    console.log(err); });
        },
        validateCorte: function (corte) {
            const corteValue = Number(corte);
            return corteValue !== "" && !isNaN(corteValue) && corteValue >1 && corteValue < 29  && corteValue!=15;
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
                this.getBillList()
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
            this.info.corte=null
        },
        searchClient: function() {
            this.info={}
            this.billType=null
            this.message.show=false
            this.selectedId=null
            this.clientDataSaveSelected={}
            this.getUser()
        },
        getUser: function() {
            this.resultsAlertToShow=false
            this.hideTicketResult = true
            axios.get('../fetchClientList.php', {
                params: {
                    searchClientContent: this.searchClientContent
                }
            }).then(response => {
                if (response.data){
                    this.totalRows = response.data.length
                    this.resultsAlertToShow=false
                    this.clientes = response.data
                    this.hideTicketResult = false
                }else{
                    this.resultsAlertToShow=true
                }
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
            this.clientDataSaveSelected = clientObject
            this.clientDataSaveSelected.ipBackup = this.clientDataSaveSelected.ip,
            this.selectedId=id
            this.menuBill=true
            this.info.id=clientObject.id
            this.info.speed=clientObject.speed
            this.info.planPrice=clientObject.planPrice
            this.info.corte=clientObject.corte
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
        getBillList:function(){
            fetch(this.billEndPoint+'?'+new URLSearchParams({
                option:'getBillList',
                idClient:this.selectedId
            }),{
                method:'GET'
            }).then(res=>{
                return res.json()
            }).then(json=>{
                if(json){
                    this.bills=json
                    //console.log(JSON.stringify(this.bills))
                }else{
                    console.log("NO tiene deuda!")
                }
            }).catch(error=>console.log("Error :"+error))            
        },
        billAction:function(opt,bill,newBill){
            this.billsBox=true
            this.applyEnabled=true
            this.billActionOptionSelected="Nueva"
            this.editEnabled=true
            if(newBill){
                this.formTask="new"
                this.billDataToBox={}
            }else{
                this.billActionOptionSelected=opt.toUpperCase()
                this.billDataToBox={
                    id:bill["id-factura"],
                    valor:bill.valorf,
                    saldo:bill.saldo,
                    item:bill.periodo,
                    nota:bill.notas
                }
                if(opt=="editar")this.formTask="editar"
                if(opt=="borrar"){
                    this.formTask="borrar"
                    this.editEnabled=false
                    this.billsBox=false
                    if(this.delConfirm==this.billDataToBox.id){
                        this.formBills()
                    }else{
                        this.delConfirm="Code Error!!"
                    }
                    }
                
            
            }

        },
        formBills:function(){
            if(this.formTask==="new"){
                this.billsBox=true
                this.editEnabled=true
                const data = new FormData();
                data.append('option',"createBill")
                data.append('id',this.clientDataSaveSelected.id)
                console.log("submiting New form data id:"+this.clientDataSaveSelected.id+" item:"+this.billDataToBox.item+" valor:"+this.billDataToBox.valor+" saldo:"+this.billDataToBox.saldo+" nota:"+this.billDataToBox.nota)
                data.append('item',this.billDataToBox.item)
                data.append('valor',this.billDataToBox.valor)
                data.append('saldo',this.billDataToBox.saldo)
                data.append('nota',this.billDataToBox.nota)
                console.log("sending...")
                fetch(this.billEndPoint, {
                method: 'POST',
                body: data
                }).then(response=> {
                        return  response.json()
                    }).then(result=> {
                        console.log("sent!:"+result)
                        this.getBillList()
                        this.editEnabled=false
                        this.billsBox=false
                    }).catch(err=> {
                        console.log(err); });
            }
            if(this.formTask==="editar"){
                const data = new FormData();
                data.append('option',"updateBill")
                data.append('id',this.billDataToBox.id)
                data.append('item',this.billDataToBox.item)
                data.append('valor',this.billDataToBox.valor)
                data.append('saldo',this.billDataToBox.saldo)
                data.append('nota',this.billDataToBox.nota)
                console.log("submiting Edit form data ")
                fetch(this.billEndPoint, {
                method: 'POST',
                body: data
                }).then(response=> {
                        return  response.json()
                    }).then(result=> {
                        console.log("sent!:"+result)
                        this.getBillList()
                        this.editEnabled=false
                        this.billsBox=false
                    }).catch(err=> {
                        console.log(err); });
            }
            if(this.formTask==="borrar"){
                console.log("submiting Delete form data ")
                const data = new FormData();
                data.append('option',"deleteBill")
                data.append('id',this.billDataToBox.id)
                fetch(this.billEndPoint, {
                method: 'POST',
                body: data
                }).then(response=> {
                        return  response.json()
                    }).then(result=> {
                        console.log("sent!:"+result)
                        this.getBillList()
                        this.editEnabled=false
                        this.billsBox=false
                        this.delConfirm=null
                    }).catch(err=> {
                        console.log(err); });
            }   
        }
    },
    mounted() {
        this.getWallet()
    },
});
</script>
</body>

</html>