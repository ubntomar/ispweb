<?php
///////////////////////////////////////////////////////////////////
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: login/index.php');
		exit;
		}
else    {
		$user=$_SESSION['username'];
		$administrador=$user;
		$role = $_SESSION["role"];
		$id_empresa = $_SESSION['empresa'];
     $idCajero = $_SESSION['idCajero'];
     $cajero=$_SESSION['username'];
		}
////////////////////////////////////////////////////////////////////


////////DEBUG////////////////////////{ 
// $cajero="AngieP";
// $administrador="AngieP";
// $id_empresa=2;
/////////////////////////////////////}



include("login/db.php");
include("Client.php");
include("VpnUtils.php");
include("Bill.php");
include("Transaction.php");
include("Email.php"); 
include("Ticket.php"); 
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$fecha=$today;
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$valorPlan= mysqli_real_escape_string($mysqli, $_REQUEST['valorPlan']);
$name= mysqli_real_escape_string($mysqli, $_REQUEST['name']);
$lastName= mysqli_real_escape_string($mysqli, $_REQUEST['lastName']);
$cedula= mysqli_real_escape_string($mysqli, $_REQUEST['cedula']);
$address= mysqli_real_escape_string($mysqli, $_REQUEST['address']);
$city= mysqli_real_escape_string($mysqli, $_REQUEST['ciudad']);
$pieces = explode("-", $city);
$idClientArea=$pieces[0];
$ciudad=$pieces[1];
$departamento= mysqli_real_escape_string($mysqli, $_REQUEST['departamento']);
$phone= mysqli_real_escape_string($mysqli, $_REQUEST['phone']);
$email= mysqli_real_escape_string($mysqli, $_REQUEST['email']);
$corte= mysqli_real_escape_string($mysqli, $_REQUEST['corte']);
$plan= mysqli_real_escape_string($mysqli, $_REQUEST['plan']);
$velocidadPlan= mysqli_real_escape_string($mysqli, $_REQUEST['velocidadPlan']);
$ipAddress= mysqli_real_escape_string($mysqli, $_REQUEST['ipAddress']);
$quote= mysqli_real_escape_string($mysqli, $_REQUEST['quote']);
$mergeItems= mysqli_real_escape_string($mysqli, $_REQUEST['mergeItems']);
$valorAfiliacion= mysqli_real_escape_string($mysqli, $_REQUEST['valorAfiliacion']);
$AfiliacionItemValue= mysqli_real_escape_string($mysqli, $_REQUEST['AfiliacionItemValue']);
$valorProrrateo= mysqli_real_escape_string($mysqli, $_REQUEST['valorProrrateo']);
$iva= mysqli_real_escape_string($mysqli, $_REQUEST['iva']);
$valorAdicionalServicio= mysqli_real_escape_string($mysqli, $_REQUEST['valorAdicionalServicio']);
$serviceIsAlreadyInstalled= mysqli_real_escape_string($mysqli, $_REQUEST['serviceIsAlreadyInstalled']);
$dayOfMonthSelected= mysqli_real_escape_string($mysqli, $_REQUEST['dayOfMonthSelected']);
$monthSelected= mysqli_real_escape_string($mysqli, $_REQUEST['monthSelected']);
//********************* ///////////////////////////////////////////////////////////////////////////////*************************************************************/
//--------------------------------------------------------
// $name= "tre bes";
// $lastName= "fer Ddfaz";
// $cedula= "17446879";
// $address= "Cra 9#13-45";
// $idClientArea="2";
// $ciudad="Guamal";
// $departamento= "Meta";
// $phone= "3147654655";
// $email= "ag.ingenieria.wist@gmail.com";
// $plan= "Residencial";  
// $velocidadPlan= "5";
// $ipAddress= "192.168.20.130";
// $serviceIsAlreadyInstalled=false; 
// $dayOfMonthSelected=11;
// $monthSelected=11;
// $corte= "1";
// $valorPlan= 60000;
// $valorProrrateo= "100"; //si > 0 => SI
// $valorAfiliacion= "250000";
// $mergeItems= 1;//Se incluye primer mes de servicio? - 
// $AfiliacionItemValue= "200000";
// $valorAdicionalServicio= "105000"; 
//-------------------------------------------------------- 
$iva= 19;
$cambio=0;
$activo=1;
$standby=0;
$nextPay="0";
$billDeliveryNumber="0";
$source="ispdev";
$fechaPago=$today;
$descuento=0;
$fechaCierre=$fechaPago;
$vencidos=0;
$valorAdicionalServicioDescripcion= "";
$fullName="$name $lastName";
if($AfiliacionItemValue<0){
    $AfiliacionItemValue=0;
}
//DEBUG
$content="";
//CONTINUE DEBUG
$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
$monthn = date("n");
$phoneContact=$phone;
$clientAfiliateDaySelected =$dayOfMonthSelected;  
$dias_de_pago="";
$currentMonth=$monthn;
$content.= " \n Mes actual: {$mes[$currentMonth]} \n ";
$nextMonth=$currentMonth==12?1:$currentMonth+1;
$oneMonthMoreTonextMonth=$nextMonth==12?1:$nextMonth+1;
$periodo=$mes[$monthSelected];

$hora=$hourMin;
try {
    $clientObject=new Client($server, $db_user, $db_pwd, $db_name);
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}

try {
    $dateld = new DateTime('now');
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}

try {
    $billObject=new Bill($server, $db_user, $db_pwd, $db_name);
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}


try {
    $transactionObject=new Transaction($server, $db_user, $db_pwd, $db_name);
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}

////////////////////////////////////////////////////////////////////////////////////{
try {
    $id_client=afiliar_cliente($clientObject,$name, $lastName, $cedula, $address, $ciudad, $departamento, $email, $phone, $valorPlan,$corte, $nextPay,$billDeliveryNumber, $velocidadPlan, $plan, $today, $source, $activo, $ipAddress,$quote, $standby, $AfiliacionItemValue,  $cajero, $idClientArea, $id_empresa);
    $content.= "\n id_client:$id_client"; 
    $idClient=$id_client;
    $vpnObject=new VpnUtils($server, $db_user, $db_pwd, $db_name);
    $vpnObject->updateGroupId($id_client,$ipAddress); 
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
////////////////////////////////////////////////////////////////////////////////////}
////////////////////////////////////////////////////////////////////////////////////{
$id_factura_afiliacion=facturaAfiliacion($billObject,$id_client,"",$notas="Afiliacion de servicio",$valorf=$AfiliacionItemValue,$valorp=$AfiliacionItemValue,$saldo=0,$cerrado=1,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
transaccionFacturaAfiliacion($transactionObject,$id_client,$cajero,$hora,$valorr=$AfiliacionItemValue,$valorap=$AfiliacionItemValue,$cambio=0,$fecha,$aprobado=1,$descripcion="Afiliacion de servicio",$id_factura_afiliacion); 
////////////////////////////////////////////////////////////////////////////////////}


////////////////////////////////////////////////////////////////////////////////////{
$valorf=$valorPlan;
$mergeItems?$valorp=$valorPlan:$valorp=0;				
$mergeItems?$saldo=0:$saldo=$valorPlan;				
$mergeItems?$cerrado=1:$cerrado=0;				
$notas="Servicio-1er mes";	
try {
    $id_factura=generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
$valorr=$valorPlan;
$valorap=$valorPlan;	
$fecha=$today;
$aprobado=1;
$descripcion=$notas;
//Generar transaccion de de servicio estandar .
try {
    generar_transaccion($transactionObject,$billObject,$id_factura,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}

try {
    $emailObject=new Email($endpoint=$endPointNewuser);
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
////////////////////////////////////////////////////////////////////////////////////}


////////////////////////////////////////////////////////////////////////////////////{
$tickeObject=new Ticket($server, $db_user, $db_pwd, $db_name,$idClient,$today,$hourMin,$administrador);
generar_Ticket($tickeObject,$phoneContact);
////////////////////////////////////////////////////////////////////////////////////}




//////////////////////////////////////////////////////////////////////////////////{
if($serviceIsAlreadyInstalled){
    try {
        emailToInstalledNewUser($clientObject,$emailObject,$tokenToInstalledUserNew,$fullName,$periodo,$valorPlan,$id_client,$dias_de_pago,$email);
    } catch (Exception $e) {
        // echo "An error occurred: " . $e->getMessage();
    }
}else{
    try {
        emailToUserNoInstalledYet($clientObject,$emailObject,$tokenToNotInstalledUserNewYet,$fullName,$periodo,$valorPlan,$id_client,$dias_de_pago,$email);
        generar_Ticket($tickeObject,$phoneContact);
    } catch (Exception $e) {
        // echo "An error occurred: " . $e->getMessage();
    }
}
//////////////////////////////////////////////////////////////////////////////////}

//COTINUE DEBUG
// echo "content-$content";
//END DEBUG
if ($idClient) {
    echo "$idClient: Usuario agregado con exito!!";
} else {
    echo "Error al agregar usuario!!";
}





function afiliar_cliente($clientObject,$name, $lastName, $cedula, $address, $ciudad, $departamento, $email, $phone, $valorPlan,$corte, $nextPay,$billDeliveryNumber, $velocidadPlan, $plan, $today, $source="ispdev", $activo, $ipAddress,$quote, $standby, $AfiliacionItemValue,  $cajero, $idClientArea, $id_empresa){
    $id_client=$clientObject->createClient($name, $lastName, $cedula, $address, $ciudad, $departamento, $email, $phone, $valorPlan,$corte, $nextPay,$billDeliveryNumber, $velocidadPlan, $plan, $today, $source="ispdev", $activo="1", $ipAddress,$quote, $standby, $AfiliacionItemValue,  $cajero, $idClientArea, $id_empresa);
    return $id_client; 
}    
function emailToInstalledNewUser($clientObject,$emailObject,$tokenToInstalledUserNew,$fullName,$periodo,$valorPlan,$id_client,$dias_de_pago,$email){
    global $content; $content.= "\n  ToInstalledUserNew,fullName,periodo,valorPlan,id_client,dias_de_pago,email $tokenToInstalledUserNew,$fullName,$periodo,$valorPlan,$id_client,$dias_de_pago,$email ";
    if($email!=""){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailArray=[
            "fullName"=> $fullName,
            "paymentDay"=> $dias_de_pago,
            "periodo"=> $periodo,
            "valorPlan"=> $valorPlan,
            "template"=>$tokenToInstalledUserNew,
            "idClient"=>$id_client,
            "email"=>$email
            ];
            if($emailObject->emailToInstalledNewUser($emailArray)){
                $clientObject->updateClient($id_client,"mail-status","success");
            }else{
                $clientObject->updateClient($id_client,"mail-status","fail");
            }
        }
    }
}
function emailToUserNoInstalledYet($clientObject,$emailObject,$tokenToNotInstalledUserNewYet,$fullName,$periodo,$valorPlan,$id_client,$dias_de_pago,$email){
    if($email!=""){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailArray=[
            "fullName"=> $fullName,
            "paymentDay"=> $dias_de_pago,
            "periodo"=> $periodo,
            "valorPlan"=> $valorPlan,
            "template"=>$tokenToNotInstalledUserNewYet,
            "idClient"=>$id_client,
            "email"=>$email
            ];
            if($emailObject->emailToInstalledNewUser($emailArray)){
                $clientObject->updateClient($id_client,"mail-status","success");
            }else{
                $clientObject->updateClient($id_client,"mail-status","fail");
            }
        }
    }
}
function  generar_Ticket($tickeObject,$telefonoContacto){
    global $content; $content.= "\n generar_Ticket() ,telefonoContacto : $telefonoContacto \n";
    $tickeObject->createTicketNewClient($telefonoContacto,$recomendacion="");
}

function facturaAfiliacion($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos){
    global $content; $content.= "\nfacturaAfiliacion() $valorp";
    return $billObject->createBill($id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
}
function transaccionFacturaAfiliacion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion,$id_factura_afiliacion){
    global $content; $content.= "\n transaccionFacturaAfiliacion transactionObject,id_client,cajero,hora,valorr,valorap,cambio,fecha,aprobado,descripcion ,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion";
    $transactionObject->CreateTransaction($id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado="1",$descripcion,$id_factura_afiliacion);
}
function generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos){
    global $content; $content.= "\n generar_factura() billObject,id_client,periodo,notas,valorf,valorp,saldo,cerrado,fechaPago,iva,descuento,fechaCierre,vencidos * $id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos";
    return $billObject->createBill($id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
    
}
function generar_transaccion($transactionObject,$billObject,$id_factura,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion){
    global $content; $content.= "\n generar_transaccion(,) id_client,cajero,hora,valorr,valorap,cambio,fecha,aprobado,descripcion * $id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion";
    $idTransaccion=$transactionObject->CreateTransaction($id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado="1",$descripcion,$id_factura);
}
function updateAfiliado($clientObject,$id_client,$param,$value){   
    global $content; $content.= "\n updateAfiliado clientObject,id_client,param,value ,$id_client,$param,$value";
    $clientObject->updateClient($id_client,$param,$value);  
}

