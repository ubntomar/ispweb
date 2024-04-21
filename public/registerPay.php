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
    $idCajero = $_SESSION['idCajero'];
    $convenio=$_SESSION['convenio'];
}
if($_SESSION['role']=='tecnico'){
	header('Location: tick.php');
}
require("../login/db.php");
include("../dateHuman.php");
require("../components/views/TemplateDark.php");
require("../components/views/Html.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");
$yesterday = date("Y-m-d",strtotime("-1 days"));
$templateObject=new TemplateDark();    
$htmlObject=new Html();
?>
<!DOCTYPE html>
<html lang="es">
<?=$htmlObject->head($path="../")?> 

<body>
    <?=$templateObject->header($user=$name)?>
    <?=$templateObject->navTop($_SESSION['role'],$path="")?>

    <main id="app">
        <?=$templateObject->navLeft($_SESSION['role'],$path="")?>
        <!-- section -->

        <section>
            <div class="section-title">
                <h1>--DevXm-- AREA DE PAGOS  <?=strtoupper($name)?></h1>
            </div>
            <div class=box-container>

                <div class="box">
                    <div class="title">
                        <h3> </h3>
                    </div>
                    <div>
                        <div class="box-content">
                            <form style="display: hidden" action="../printable.php" method="POST" id="form">
                                <input type="hidden" id="idt" name="idt" value="0" />
                                <input type="hidden" id="rpp" name="rpp" value="register-pay" />
                            </form>
                            <table id="clientList" class="display compact stripe cell-border" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Nombre Titular</th>
                                        <th>Dirección</th>
                                        <th>Antiguedad en meses</th>
                                        <th>Saldo</th>
                                        <th>Fecha de Ingreso</th>
                                        <th>Corte</th>
                                        <th>Cedula Titular</th>
                                        <th>Telefono</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                        $sql="SELECT * from `jurisdicciones` WHERE `jurisdicciones`.`id` = $jurisdiccion";
                        if($result=$mysqli->query($sql)){
                            $row=$result->fetch_assoc();
                            $grupo=$row["id-grupo-area"];
                            $result->free();
                            $sql=" SELECT * FROM `items_grupo_area` WHERE `items_grupo_area`.`id-grupo` = $grupo";
                            if($result=$mysqli->query($sql)){
                                $num_rows = $result->num_rows;
                                $arrayidArea=" AND (";
                                $cn=0;
                                while($row=$result->fetch_assoc()){
                                    $cn+=1;
                                    $idArea=$row["id-area"];
                                    $arrayidArea.=($cn==$num_rows)? "`id_client_area` = $idArea )  ":"`id_client_area` = $idArea OR";
                                }
                                $result->free();   
                            }
                        }
                        $sql = "SELECT * FROM `afiliados` WHERE  `afiliados`.`id-empresa` = $empresa AND  `afiliados`.`activo`=1 AND `afiliados`.`eliminar`!=1   $arrayidArea  ORDER BY `afiliados`.`id`  ASC  ";
                        if ($result = $mysqli->query($sql)) {
                            while ($row = $result->fetch_assoc()) {
                                $idCliente = $row["id"];
                                $cedula = $row["cedula"];
                                $telefono = $row["telefono"];
                                $registration_date = $row["registration-date"];
                                $corte = $row["corte"];
                                $idGRoup = ($row["id-repeater-subnets-group"]==0)?"G-0":"";
                                $standby = $row["standby"];
                                $reconectedDate=$row["reconected-date"];
                                $ip=$row["ip"];
                                $signal=(($row["signal-strenght"]*-1)>0&&($row["signal-strenght"]*-1)<70)?$row["signal-strenght"]." buena":$row["signal-strenght"]." mala";
                                if($row["signal-strenght"]*1==0)$signal="?"; 
                                $sshLoginType=$signal=="?"?$row["ssh-login-type"]."-":$row["ssh-login-type"];
                                if($row["ssh-login-type"]=="router")$signal=" NO APLICA";
                                $pingDate=$row["pingDate"];
                                $pingResponseTime=$row["ping"];
                                $suspenderStatus= (  ($row["suspender-list-status"]==false) )?"FALSO!":"";
                                $clientWidthConvenio=$row["convenio"];
                                if(($sincedexDays=get_date_diff( $pingDate, $today, 2 ))=="") $timeElapsed="Ultimo ping: Hoy"; else $timeElapsed="hace $sincedexDays";
                                $pingCurrentStatus=(($pingDate==$today||$pingDate==$yesterday)&&$pingResponseTime) ? "<small class=\"bg-success text-white p-1\">Ping OK->$pingDate:$pingResponseTime ms</small>" : "<small class=\"bg-danger text-white p-1\">Ping Error desde  $timeElapsed</small>";   
                                $style_cell = "class=\"font-weight-bold h6\" "; 
                                $style2_cell = "";
                                $ts1 = strtotime($registration_date);
                                $ts2 = strtotime($today);

                                $year1 = date('Y', $ts1);
                                $year2 = date('Y', $ts2);

                                $month1 = date('m', $ts1);
                                $month2 = date('m', $ts2);

                                $registration_day = date('d', $ts1);
                                if ($registration_date != "0000-00-00") {

                                    $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                                } else
                                    $diff = "999";

                                $sqlt = "SELECT * FROM `factura`  WHERE `factura`.`id-afiliado`='$idCliente' AND `factura`.`cerrado`='0'  ORDER BY `factura`.`id-factura` DESC ";
                                $vtotal = 0;
                                $cont = 0;
                                if ($resultt = $mysqli->query($sqlt)) {
                                    while ($rowft = $resultt->fetch_assoc()) {
                                        $cont += 1;
                                        $idFactura = $rowft["id-factura"];
                                        $periodo = $rowft["periodo"];
                                        $saldo = $rowft["saldo"];
                                        $vtotal += $saldo;
                                    }
                                    $resultt->free();
                                }
                                if ($vtotal > 0 && $diff == 0) {
                                    $style_cell = "class=\"text-warning bg-dark font-weight-bold h6\" ";
                                }
                                if ($vtotal > 0 && $diff == 1 && $corte == 1 && $registration_day > 15) {
                                    $style_cell = "class=\"text-info bg-dark font-weight-bold h6\" ";
                                }

                                if ($row["eliminar"] == 1) {
                                    $statusText = "Inactivo";
                                    $style = "border-dark text-secundary "; 
                                } else {
                                    $style = "border-primary text-success ";
                                    $statusText = "<p><small class=\"px-1 border $style rounded \">Activo</small></p>";
                                }
                                if($reconectedDate){
                                    $reconectedBox= $reconectedDate=="1999-01-01"? "<div><small class=\"px-1 border border-primary text-danger rounded \">Reconexión Fallida</small></div>":"<div>Reconectado<small class=\"px-1 border border-primary text-success rounded \">$reconectedDate </small></div>";
                                }else{
                                    $reconectedBox="";
                                }
                                if ($row["suspender"] == 1) {
                                    $today = date("Y-m-d");
                                    $date1 = new DateTime($today);
                                    $date2 = new DateTime($row["suspenderFecha"]);
                                    $days  = $date2->diff($date1)->format('%a'); 
                                    ($days==0)? $d="Hoy":$d="$days dias";
                                    if($row["suspender-list-status-date"]){ 
                                        $date3 = new DateTime($row["suspender-list-status-date"]);
                                        $verifiedDays  = $date3->diff($date1)->format('%a'); 
                                        ($verifiedDays==0)? $dVerified="Hoy":$dVerified="$verifiedDays dias";
                                        $styleV = "border-info text-info ";
                                        $textVerified="<div><small class=\"p-1  $styleV  \">Verificado:$dVerified</small></div>";
                                    }
                                    else $textVerified="";
                                    $style = "border-info text-danger ";
                                    $statusText = 
                                    "<div><small class=\"px-1 border $style rounded \">Cortado:$d $suspenderStatus </small>$textVerified</div>"; 
                                }
                                $textCedula = $cedula;
                                if ($cedula == 0) {
                                    $textCedula = "<input class=\"form-control form-control-sm cedula" . $row["id"] . " px-2\" type=\"text\" value=\"\" >";
                                } else {
                                    $textCedula = "<input class=\"form-control form-control-sm cedula" . $row["id"] . " px-2\" type=\"text\" value=\"$cedula\"  	 >";
                                }

                                $textTelefono = $telefono;
                                if ($telefono == "") {
                                    $textTelefono = "<input class=\"form-control form-control-sm telefono" . $row["id"] . " px-2 \" type=\"text\" value=\"\" >";
                                } else {
                                    $textTelefono = "<input class=\"form-control form-control-sm telefono" . $row["id"] . " px-2 \" type=\"text\" value=\"$telefono\"   >";
                                }

                                $telefono = $row["telefono"]; 
                                echo "<tr class=\"text-center  \">";
                                echo "<td class=\" font-weight-bold\"> {$row["cliente"]}  {$row["apellido"]} $statusText $reconectedBox</td>";
                                echo "<td><small>{$row["direccion"]} {$row["ciudad"]} -{$row['id']}</small></td>";  
                                echo "<td>$diff</td>";
                                if($convenio){ 
                                    if($clientWidthConvenio){
                                        echo "<td><small 	$style_cell  >$$vtotal</small><div><a href=\"#\" class=\"text-primary icon-client \" data-toggle=\"modal\" 	data-target=\"#payModal\" data-id=\"" . $row["id"] . "\"><i class=\"icon-money h3\"></i></a></div><div class=\"p-1 border border-secondary rounded \"><p class=\"mb-0 text-secondary\"><small>Billetera</small></p><p class=\"mb-0 text-secondary\"><small>($".number_format($row["wallet-money"]).")</small></p></div></td>";//
                                    }else{
                                        echo "<td><small>Ojo, este cliente paga a Omar hay que darle mis datos.</small></td>";//
                                    }

                                }else{
                                    if($clientWidthConvenio){
                                        echo "<td><div class=\"p-1 border border-secondary rounded \"><p class=\"mb-0 text-secondary\"><small>Convenio:</small></p><p class=\"mb-0 text-secondary\"><small>Este cliente DEBE pagar por Nequi al número: <strong>322-378-23-24</strong> y reportar el pago al mismo número: 322-378-23-24</small></p></div></td>";//
                                    }else{
                                        echo "<td><small 	$style_cell  >$$vtotal</small><div><a href=\"#\" class=\"text-primary icon-client \" data-toggle=\"modal\" 	data-target=\"#payModal\" data-id=\"" . $row["id"] . "\"><i class=\"icon-money h3\"></i></a></div><div class=\"p-1 border border-secondary rounded \"><p class=\"mb-0 text-secondary\"><small>Billetera</small></p><p class=\"mb-0 text-secondary\"><small>($".number_format($row["wallet-money"]).")</small></p></div></td>";//
                                    }

                                }
                                // echo "<td><small>$registration_date</small><div class=\"border border-info rounded p-1 bg-white\"><p class=\"mb-0\"><small><input type=\"text\" value=\"\" placeholder=\"$ip\" id=\"{$row['id']}\"
                                // class=\"form-control form-control-sm ml-1\"></small></p><button v-on:click=\"ipUpdate({$row['id']})\" class=\"border border-rounded icon-arrows-ccw\"></button> 
                                // <p class=\"mb-0\"><small>$pingCurrentStatus</small></p></div></td>";   
                                echo "<td><small>$registration_date</small><div class=\"border border-info rounded p-1 bg-white\"><p class=\"mb-0\"><small>ip:'$ip'</small></p><p class=\"mb-0\"><small>$pingCurrentStatus -</small></p><p class=\"mb-0\"><small>Antena: $sshLoginType</small></p><p class=\"mb-0\"><small>Señal:$signal</small></p></div></td>"; ///$sshLoginType   
                                echo "<td class=\" align-middle \"><small>C-" . $row["corte"] . "*$standby</small><p><small>$idGRoup</small></p></td>";
                                echo "<td class=\" align-middle \">$textCedula</td>"; 
                                echo "<td class=\" align-middle \">$textTelefono</td>";          
                                echo "</tr>";
                            }
                            $result->free(); 
                        }
                        ?>
                                    <!-- Modal -->
                                    <div class="modal fade" id="payModal" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Detalles de Pago</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="fetched-data"></div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" id="cancelbutton" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancelar</button>
                                                    <button type="button" id="paybutton"
                                                        class="btn btn-primary">Pagar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>


            </div>
        </section>

    </main>


    <?=$htmlObject->jsScript($path="../")?>
</body>

<script>
$("button.continue").html("Next Step...")
$('#clientList').DataTable({
    "iDisplayLength": 15,
    "order": [
        [4, "desc"]
    ],
    "responsive": true,
    "paging": true,
    "searching": true,
    "info": true,

});
$('#payModal').on('show.bs.modal', function(e) {
    console.log("modal opened")
    var rowid = $(e.relatedTarget).data('id');

    var cedula = 0;
    if ($.isNumeric($(".cedula" + rowid).val())) {
        if (($(".cedula" + rowid).val() + "").match(/^\d+$/)) {

            cedula = $(".cedula" + rowid).val();
        } else {
            alertify.error('Cedula invalido, tiene puntos decimales!!');
        }
    } else {
        if ($(".cedula" + rowid).val() == "")
            alertify.warning('Cedula  no sera actualizada!!');
        else
            alertify.error('Cedula valor invalido, no sera actualizada!!');
    }
    var telefono = 0;
    if ($.isNumeric($(".telefono" + rowid).val())) {
        if (($(".telefono" + rowid).val() + "").match(/^\d+$/)) {

            telefono = $(".telefono" + rowid).val();
        } else {
            alertify.error('telefono invalido, tiene puntos decimales!!');
        }
    } else {
        if ($(".telefono" + rowid).val() == "")
            alertify.warning('Telefono  no sera actualizado!!');
        else
            alertify.error('Telefono valor invalido, no sera actualizada!!');
    }
    console.log("../controller/ajax/payModal.php")
    $.ajax({
        type: 'post',
        url: '../controller/ajax/payModal.php',
        data: {
            rowid: rowid,
            cedula: cedula,
            telefono: telefono
        },
        success: function(data) {
            $('.fetched-data').html(data);
            $("#tr-valor-abonar").hide();
            $("#tr-valor-descontar").hide();
            $("#nuevo-saldo").hide();
            $('#payment').hide();
            //start block pasted
            $('#cancelbutton').click(function() {
                $('#payModal').modal('hide');
                alertify.error('Operacion Cancelada').dismissOthers();
            });
            $("#checkbox-abonar").click(function() {
                $("#valor-abonar").val("");
                $('.money-abonar').html("");
                if ($('#checkbox-abonar').is(":checked")) {
                    $("#tr-valor-abonar").show();
                    $("#nuevo-saldo").show();
                    //$("#paybutton").prop("disabled", true);
                    if ($('#checkbox-descontar').is(":checked")) {
                        if ($("#valor-abonar").val().replace(/[^0-9\.]/g, '') != "" &&
                            $("#valor-descontar").val().replace(/[^0-9\.]/g, '') != ""
                        ) {
                            $("#paybutton").prop("disabled", false);
                        } else {
                            $("#paybutton").prop("disabled", true);
                        }
                    } else {
                        $("#valor-nuevo-saldo").html($("#valor-pago").html().replace(
                            /[^0-9\.]/g, '')).simpleMoneyFormat();
                    }

                } else {
                    $("#tr-valor-abonar").hide();
                    $("#nuevo-saldo").hide();
                    $("#paybutton").prop("disabled", false);
                    if ($('#checkbox-descontar').is(":checked")) {
                        $("#nuevo-saldo").show();
                        if ($("#valor-descontar").val() == "" || !this.value) {
                            $("#paybutton").prop("disabled", true);
                        } else {
                            let vp = $("#valor-pago").text();
                            let intvp = vp.replace(/[^0-9]/gi, '');
                            let va = $("#valor-descontar").val().replace(/[^0-9\.]/g,
                                '');
                            let vt = parseInt(intvp) - parseInt(va);
                            $("#valor-nuevo-saldo").html(vt).simpleMoneyFormat();

                        }
                    }
                }
            });
            $("#checkbox-descontar").click(function() {
                $("#valor-descontar").val("");
                $('.money-descontar').html("");
                if ($('#checkbox-descontar').is(":checked")) {
                    $("#tr-valor-descontar").show();
                    $("#nuevo-saldo").show();
                    $("#paybutton").prop("disabled", true);
                    if ($('#checkbox-abonar').is(":checked")) {
                        if ($("#valor-abonar").val().replace(/[^0-9\.]/g, '') != "" &&
                            $("#valor-descontar").val().replace(/[^0-9\.]/g, '') != ""
                        ) {
                            $("#paybutton").prop("disabled", false);
                        } else {
                            $("#paybutton").prop("disabled", true);
                        }
                    } else {
                        $("#valor-nuevo-saldo").html($("#valor-pago").html().replace(
                            /[^0-9\.]/g, '')).simpleMoneyFormat();
                    }

                } else {
                    $("#tr-valor-descontar").hide();
                    $("#nuevo-saldo").hide();
                    $("#paybutton").prop("disabled", false);
                    if ($('#checkbox-abonar').is(":checked")) {
                        $("#nuevo-saldo").show();
                        if ($("#valor-abonar").val() == "" || !this.value) {
                            $("#paybutton").prop("disabled", true);
                        } else {
                            let vp = $("#valor-pago").text();
                            let intvp = vp.replace(/[^0-9]/gi, '');
                            let va = $("#valor-abonar").val().replace(/[^0-9\.]/g, '');
                            let vt = parseInt(intvp) - parseInt(va);
                            $("#valor-nuevo-saldo").html(vt).simpleMoneyFormat();

                        }
                    }
                }
            });
            $("#valor-abonar").keyup(function() {
                let value = this.value.replace(/[^0-9\.]/g, '');
                let abonar = $("#valor-abonar").val();
                $('.money-abonar').text($("#valor-abonar").val());
                $('.money-abonar').simpleMoneyFormat();
                var vp = $("#valor-pago").text();
                var intvp = vp.replace(/[^0-9]/gi, '');
                let descontar = 0;
                let voidFlag = 0;
                if ($('#checkbox-descontar').is(":checked")) {
                    if ($("#valor-descontar").val().replace(/[^0-9]/gi, '') != "") {
                        descontar = parseInt($("#valor-descontar").val());
                    } else {
                        descontar = 0;
                        $("#paybutton").prop("disabled", true);
                        voidFlag = 1;
                    }
                }
                if (((parseInt(value) + parseInt(descontar)) > parseInt(intvp)) ||
                    parseInt(abonar) == 0 || isNaN(this.value) || !this.value) {
                    $("#valor-abonar").val('');
                    $("#valor-abonar").addClass('border border-danger');
                    $("#paybutton").prop("disabled", true);
                    $('.money-abonar').text('');
                    $("#valor-nuevo-saldo").html(intvp).simpleMoneyFormat();
                } else {
                    $("#valor-abonar").removeClass('border border-danger');
                    if (voidFlag != 1)
                        $("#paybutton").prop("disabled", false);
                    let ns = intvp - value - descontar;
                    $("#valor-nuevo-saldo").html(ns).simpleMoneyFormat();

                }
            });
            $("#valor-descontar").keyup(function() {
                let value = this.value.replace(/[^0-9\.]/g, '');
                let descontar = $("#valor-descontar").val();
                $('.money-descontar').text($("#valor-descontar").val());
                $('.money-descontar').simpleMoneyFormat();
                var vp = $("#valor-pago").text();
                var intvp = vp.replace(/[^0-9]/gi, '');
                let abonar = 0;
                let voidFlag = 0;
                if ($('#checkbox-abonar').is(":checked")) {
                    if ($("#valor-abonar").val().replace(/[^0-9]/gi, '') != "") {
                        abonar = parseInt($("#valor-abonar").val());

                    } else {
                        abonar = 0;
                        $("#paybutton").prop("disabled", true);

                        voidFlag = 1;
                    }
                }

                if (((parseInt(value) + parseInt(abonar)) > parseInt(intvp)) ||
                    parseInt(descontar) == 0 || isNaN(this.value) || !this.value) {

                    $("#valor-descontar").val('');
                    $("#valor-descontar").addClass('border border-danger');
                    $("#paybutton").prop("disabled", true);
                    $('.money-descontar').text('');
                    $("#valor-nuevo-saldo").html(intvp).simpleMoneyFormat();
                } else {

                    $("#valor-descontar").removeClass('border border-danger');
                    if (voidFlag != 1)
                        $("#paybutton").prop("disabled", false);
                    let ns = intvp - value - abonar;
                    $("#valor-nuevo-saldo").html(ns).simpleMoneyFormat();

                }
            });
            //end block pasted			
            $('#btn-paym').click(function() {
                $.ajax({
                    type: 'post',
                    url: '../controller/ajax/payModalPayment.php',
                    data: {
                        rowid: rowid,
                        cedula: cedula,
                        telefono: telefono
                    },
                    success: function(data) {
                        $('#payment').html(data);
                        $("#payment-div").toggleClass(
                            "border border-info rounded");
                        if (!$.fn.DataTable.isDataTable(
                                '#table_past_payment')) {
                            var table_last_payment = $(
                                '#table_past_payment').DataTable({
                                "responsive": true,
                                "paging": true,
                                "searching": false,
                                "info": false,
                                "order": [
                                    [0, "desc"]
                                ],
                                "fnRowCallback": function(nRow,
                                    aData, iDisplayIndex,
                                    iDisplayIndexFull) {
                                    if (aData[4] != "0") {
                                        $('td', nRow).css(
                                            'color',
                                            '#d9534f');
                                    }
                                    if ((aData[3] ==
                                            "Ajuste") && (aData[
                                            4] == "0")) {
                                        $('td', nRow).css(
                                            'color',
                                            '#428bca');
                                    }

                                }

                            });

                        }
                        $('#payment').toggle();
                        $("#icon-down-open").toggle();
                    }
                });
            });

        }
    });
});

$(document).ajaxComplete(function() {

    $('.icon-client').click(function() {
        $("#paybutton").show();
    });

    var vp = $("#valor-pago").text();
    console.log("valor-pago:" + vp);
    var intvp = vp.replace(/[^0-9]/gi, '');
    if (intvp == 0) {
        $("#paybutton").hide();
        $("#tr-chkb-abonar").hide();
    }
    $('#paybutton').click(function() {
        let strPrompt1 = "Ingreso de Efectivo";
        let strPrompt2 = "Efectivo:";
        if (!$('#checkbox-abonar').is(":checked") && $('#checkbox-descontar').is(":checked")) {
            strPrompt1 = "Presione enter para continuar";
            strPrompt2 = "";
        }
        alertify.prompt(strPrompt1, strPrompt2, "",
            function(evt, value) {
                var vplanRow = parseInt($("#valor-plan").html().replace(/[^0-9]/gi, ''));
                console.log("valor del plan:" + vplanRow);
                var pass = 0;
                var cambio = 0;
                if ($('#checkbox-descontar').is(":checked")) {
                    pass = 1;
                }
                if ($('#checkbox-abonar').is(":checked")) {
                    let tmp1 = 0;
                    if ($("#valor-abonar").val())
                        tmp1 = parseInt($("#valor-abonar").val());
                    var vaa = tmp1;
                    if (value >= parseInt(vaa)) {
                        pass = 1;
                        cambio = value - parseInt(vaa);
                    } else
                        pass = 0;

                }
                if (!$('#checkbox-abonar').is(":checked") && !$('#checkbox-descontar').is(
                        ":checked")) {
                    var vap = $("#valor-pago").text();
                    var intvap = vap.replace(/[^0-9]/gi, '');
                    if (value >= parseInt(intvap)) {
                        pass = 1;
                        cambio = value - parseInt(intvap);
                    } else
                        pass = 0;

                }
                if (pass == 1) {
                    let idcRow = 0;
                    let vapRow = 0;
                    let vaaRow = 0;
                    let vreRow = 0;
                    let cambioRow = 0;
                    let vadRow = 0;
                    let vaa = 0;
                    let vad = 0;
                    let rec = 0;
                    let valorWallet=$("#valor-wallet").val();
                    let emailInput=$("#emailInput").val();
                    if ($('#checkbox-reconectar').is(":checked")) {
                        rec = 1
                    }
                    if ($('#checkbox-abonar').is(":checked") || $('#checkbox-descontar').is(
                            ":checked")) {
                        var idc = $("#id-client").text();
                        var vap = -1;
                        if ($('#checkbox-abonar').is(":checked")) {
                            vaa = $("#valor-abonar").val();
                        }
                        if ($('#checkbox-descontar').is(":checked")) {
                            vad = $("#valor-descontar").val();
                        }
                        idcRow = idc;
                        vapRow = vap;
                        vaaRow = vaa;
                        vreRow = value;
                        cambioRow = cambio;
                        vadRow = vad;
                    } else {
                        var idc = $("#id-client").text();
                        var vap = $("#valor-pago").text();
                        var intvap = vap.replace(/[^0-9]/gi, '');
                        idcRow = idc;
                        vapRow = intvap;
                        vaaRow = 0;
                        vreRow = value;
                        cambioRow = cambio;
                        vadRow = 0;
                    }
                    console.log("valores q voy a enviar \n idc: " + idcRow + ",vap:" + vapRow +
                        ",vaa:" + vaaRow + ",vre: " + vreRow + ",cam: " + cambioRow +
                        ",vad: " + vadRow + ",vpl: " + vplanRow+ ",reconectar: " + rec);
                    $.ajax({
                        type: 'post',
                        url: '../controller/ajax/payFact.php',
                        data: {
                            idc: idcRow,
                            vap: vapRow,
                            vaa: vaaRow,
                            vre: vreRow,
                            cam: cambioRow,
                            vad: vadRow,
                            vpl: vplanRow,
                            rec: rec,
                            valorWallet:valorWallet,
                            emailInput:emailInput
                        },
                        success: function(data) {
                            console.log("data" + data)
                            var arr = data.split('/');
                            var msj = arr[0];
                            var cod = arr[1];
                            console.log(data);
                            console.log("data:"+data+"-msj:"+msj+"-code:"+cod);
                            alertify.success(msj);
                            $("#idt").val(cod); 
                            $("#form").submit();       
                        }
                    });
                } else
                    alertify.error('Efectivo invalido!');
            },
            function() {
                alertify.error('Error,problema con valor de efectivo!');
            });;
        $('#payModal').modal('hide');
    });
})
</script>
</body>

</html>