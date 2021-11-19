<?php

require("../login/db.php");
require("../components/views/TemplateDark.php");
require("../components/views/Html.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");
$templateObject=new TemplateDark();    
$htmlObject=new Html();
?>

<!DOCTYPE html>
<html lang="es"> 
<?=$htmlObject->head($path="../")?>

<body>
    <?=$templateObject->header($user="omar")?>
    <?=$templateObject->navTop($_SESSION['role'])?>
    
    <main id="app">
        <?=$templateObject->navLeft($_SESSION['role'])?>
        
        <section>
            <div class="section-title">
                <img src="img/support.png" alt="">
                <h1>REGISTRAR PAGOS</h1> 
            </div>
            <div class=box-container>
                
                <div class="box">
                    <div class="title">
                        <h3><i class="icon-wrench"></i> Tickets Abiertos</h3>
                    </div>
                    <div>
                        <div class="box-content">
                        </div>
                    </div>
                </div>
                
                
            </div>
        </section>
    </main>

<?=$htmlObject->jsScript($path="../")?>
</body>

<script>
var app = new Vue({
    el: "#app",
    data: {
        
        img2Error: ""
    },
    methods: {
        
       
        saveNewticket: function() {
            axios.get('saveNewTicket.php', {
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
            axios.get('saveClosingTicket.php', {
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
            axios.post('uploadImage.php', formData, {
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
        
    },
});
</script>
</body>

</html> 