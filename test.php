<?php
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
		$empresa = $_SESSION['empresa'];
		}
// $user="Omar";
// $administrador="Omar";
// $empresa=1;
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
$usuario="Omar";//$_SESSION['username'];

//
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
$mergeItems= mysqli_real_escape_string($mysqli, $_REQUEST['mergeItems']);
// $recibo= mysqli_real_escape_string($mysqli, $_REQUEST['recibo']);
$valorAfiliacion= mysqli_real_escape_string($mysqli, $_REQUEST['valorAfiliacion']);
$AfiliacionItemValue= mysqli_real_escape_string($mysqli, $_REQUEST['AfiliacionItemValue']);
$valorProrrateo= mysqli_real_escape_string($mysqli, $_REQUEST['valorProrrateo']);
$iva= mysqli_real_escape_string($mysqli, $_REQUEST['iva']);
$valorAdicionalServicio= mysqli_real_escape_string($mysqli, $_REQUEST['valorAdicionalServicio']);
$serviceIsAlreadyInstalled= mysqli_real_escape_string($mysqli, $_REQUEST['serviceIsAlreadyInstalled']);
$dayOfMonthSelected= mysqli_real_escape_string($mysqli, $_REQUEST['dayOfMonthSelected']);
$monthSelected= mysqli_real_escape_string($mysqli, $_REQUEST['monthSelected']);
//********************* ///////////////////////////////////////////////////////////////////////////////*************************************************************/
//$name= "Omar Alberto";
// $lastName= "Hernandez Diaz";
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
//////////////////////////////////////////////
// $dayOfMonthSelected=11;
// $monthSelected=11;
// $corte= "1";
// $valorPlan= 60000;
// $valorProrrateo= "100"; //si > 0 => SI
// $valorAfiliacion= "250000";
// $mergeItems= 1;//Se incluye primer mes de servicio? -
// $AfiliacionItemValue= "200000";
// $valorAdicionalServicio= "105000"; 
/////////////////////////////////////////////// 
// $iva= 19;
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
$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
$monthn = date("n");
$phoneContact=$phone;
//***** */
$clientAfiliateDaySelected =$dayOfMonthSelected;  
$dias_de_pago="";
$currentMonth=$monthn;
//print " \n Mes actual: {$mes[$currentMonth]} \n ";
$nextMonth=$currentMonth==12?1:$currentMonth+1;
$oneMonthMoreTonextMonth=$nextMonth==12?1:$nextMonth+1;
$twoMonthMoreTonextMonth=$oneMonthMoreTonextMonth==12?1:$oneMonthMoreTonextMonth+1;
$periodo="";
$prorateo_checked=$valorProrrateo=="0"?false:true;
$prorrateo=$valorProrrateo;
$clientObject=new Client($server, $db_user, $db_pwd, $db_name);
$id_client=afiliar_cliente($clientObject,$name, $lastName, $cedula, $address, $ciudad, $departamento, $email, $phone, $valorPlan,$corte, $nextPay,$billDeliveryNumber, $velocidadPlan, $plan, $today, $source, $activo, $ipAddress, $standby, $AfiliacionItemValue,  $usuario, $idClientArea, $empresa);
//print "\n id_client:$id_client";
$vpnObject=new VpnUtils($server, $db_user, $db_pwd, $db_name);
$vpnObject->updateGroupId($id_client,$ipAddress); 
$cajero=$usuario;
$hora=$hourMin;
$valorServicioAdicional=$valorAdicionalServicio;
$afiliation_include_first_month=$mergeItems;
$dateld = new DateTime('now');
$dateld->modify('last day of this month');
$lastDayofMonth=$dateld->format('d');
$billObject=new Bill($server, $db_user, $db_pwd, $db_name);
$transactionObject=new Transaction($server, $db_user, $db_pwd, $db_name);
$emailObject=new Email($endpoint=$endPointNewuser);
$idClient=$id_client;
if($AfiliacionItemValue<0){
    $response="error";
    exit;
}
$tickeObject=new Ticket($server, $db_user, $db_pwd, $db_name,$idClient,$today,$hourMin,$administrador);

if ($clientAfiliateDaySelected ==1){
    $corte =1;
    $dias_de_pago="1 al 7 de cada mes ";
    if ($monthSelected == $currentMonth){
        $periodo =$mes[$currentMonth];
        mesActual($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,0,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth,$billLastDay=false,$oneMonthMoreTonextMonth);//verificar que el valor deProrrateo esté en 0 en client.php
    }
    if ($monthSelected == $nextMonth){
        $periodo =$mes[$nextMonth];
        mesSiguiente($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,$oneMonthMoreTonextMonth,0,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth,$billLastDay=false);//verificar que el valor deProrrateo esté en 0 en client.php
        updateAfiliado($clientObject,$id_client,"standby",1); 
    }
}
if ($clientAfiliateDaySelected >= 2 && $clientAfiliateDaySelected <=10){
    $corte =1;
    $dias_de_pago="1 al 7 de cada mes";
    if ($monthSelected == $currentMonth){
        $periodo =$mes[$currentMonth];
        mesActual($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,$prorateo_checked,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth,$billLastDay=false,$oneMonthMoreTonextMonth);
    }
    if ($monthSelected == $nextMonth){  
        $periodo =$mes[$nextMonth];
        mesSiguiente($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,$oneMonthMoreTonextMonth,$prorateo_checked,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth,$billLastDay=false);
        updateAfiliado($clientObject,$id_client,"standby",1); 
    }
}
if ($clientAfiliateDaySelected>10 && $clientAfiliateDaySelected<15){
    $corte=15;
    $dias_de_pago="15 al 20 de cada mes ";
    if ($monthSelected == $currentMonth){
        $periodo =$mes[$currentMonth];   
        mesActualConServicioAdicional($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,$valorServicioAdicional,$prorateo_checked,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth);
        
    }
    if ($monthSelected == $nextMonth){
        $periodo =$mes[$nextMonth];
        mesSiguienteConServicioAdicional($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,$valorServicioAdicional,$prorateo_checked,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth,$oneMonthMoreTonextMonth);
        updateAfiliado($clientObject,$id_client,"standby",1);
    }
}
if ($clientAfiliateDaySelected ==15){
    $corte =15;
    $dias_de_pago="15 al 20 de cada mes";
    if ($monthSelected == $currentMonth){ 
        $periodo =$mes[$currentMonth];
        mesActual($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,0,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth,$billLastDay=false,$oneMonthMoreTonextMonth);//verificar que el valor deProrrateo esté en 0 en client.php
    }
    if ($monthSelected == $nextMonth){
        $mes[$nextMonth];
        mesSiguiente($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,$oneMonthMoreTonextMonth,0,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth,$billLastDay=false);//verificar que el valor deProrrateo esté en 0 en client.php
        updateAfiliado($clientObject,$id_client,"standby",1); 
    }
} 
if ($clientAfiliateDaySelected > 15 && $clientAfiliateDaySelected <=19){
    $corte =15;
    $dias_de_pago="15 al 20 de cada mes";
    if ($monthSelected == $currentMonth){
        $periodo =$mes[$currentMonth];
        mesActual($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,$prorateo_checked,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth,$billLastDay=false,$oneMonthMoreTonextMonth);
    }
    if ($monthSelected == $nextMonth){  
        $periodo =$mes[$nextMonth];
        mesSiguiente($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,$oneMonthMoreTonextMonth,$prorateo_checked,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth,$billLastDay=false);
        updateAfiliado($clientObject,$id_client,"standby",1); 
    }
}
if ($clientAfiliateDaySelected >=20 && $clientAfiliateDaySelected<$lastDayofMonth){
    $corte=1;
    $dias_de_pago="1 al 7 de cada mes ";
    $cambio="";
    if ($monthSelected == $currentMonth){
        $periodo=$nextMonth; //(***Se debe generar para que el cliente sepa que está pagando el mes siguiente);//,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos
        if ($prorateo_checked){
            if ($afiliation_include_first_month){
                //Valor de factura de mensualidad 0
                //genera factura estandar
                $valorf=$valorPlan;
                $valorp=$valorPlan;				
                $saldo=0;
                $cerrado=1;
                $notas="Servicio-1er mes";	
                generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
                $valorr=$valorPlan;
                $valorap=$valorPlan;	
                $fecha=$today;
                $aprobado=0;;
                $descripcion=$notas;
                //generar transaccion de de servicio estandar .
                generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
                //genera factura de servicio ADICIONAL (p.e 4 días)
                $valorf=$valorServicioAdicional;
                $valorp=$valorServicioAdicional;				
                $saldo=0;
                $cerrado=1;	
                $notas="Prorrateo";
                generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
                $valorr=$valorServicioAdicional;
                $valorap=$valorServicioAdicional;	
                $fecha=$today;
                $aprobado=0;;
                $descripcion=$notas;
                //se genera transaccion de servicio ADICIONAL(p.e 4 días) 
                generar_transaccion($transactionObject,$billObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
                $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$oneMonthMoreTonextMonth];
                //print "\n $billMessageperiod \n";    
            }else{//No genera transaccion
                //genera factura estandar
                $valorf=$valorPlan;				
                $valorp=0;				
                $saldo=$valorPlan;
                $cerrado=0;	
                $notas="Servicio-1er mes";
                generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
                //genera factura de servicio ADICIONAL (p.e 4 días)
                $valorf=$valorServicioAdicional;				
                $valorp=0;				
                $saldo=$valorServicioAdicional;
                $cerrado=0;
                $notas="Prorrateo";
                generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
                $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$nextMonth];
                //print "\n $billMessageperiod \n";
            }      
        }else{
            $valorf=$valorPlan;
            if ($afiliation_include_first_month){
                //Valor de factura de mensualidad 0
                $valorp=$valorPlan;				
                $saldo=0;
                $cerrado=1;
                $notas="Servicio-1er mes";	
                generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
                $valorr=$valorPlan;
                $valorap=$valorPlan;	
                $fecha=$today;
                $aprobado=0;;
                $descripcion=$notas;
                generar_transaccion($transactionObject,$billObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
                $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$oneMonthMoreTonextMonth]; 
                //print "\n $billMessageperiod \n";   
            }else{//No genera transaccion
                //Valor de factura de mensualidad =>$plan mensual
                $valorp=0;				
                $saldo=$valorPlan;
                $cerrado=0;	
                $notas="Servicio-1er mes";
                generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
                $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$nextMonth];
                //print "\n $billMessageperiod \n";
            } 
        }
        updateAfiliado($clientObject,$id_client,"standby",1);                                      
    }
    if($monthSelected == $nextMonth){
        $periodo=$nextMonth+1; 
        if ($prorateo_checked){
            $valorf=$prorrateo;
            if ($afiliation_include_first_month){
                //Valor de factura de mensualidad 0
                //genera factura estandar
                $valorf=$valorPlan;
                $valorp=$valorPlan;				
                $saldo=0;
                $cerrado=1;
                $notas="Servicio-1er mes";	
                generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
                $valorr=$valorPlan;
                $valorap=$valorPlan;	
                $fecha=$today;
                $aprobado=0;;
                $descripcion=$notas;
                //generar transaccion de de servicio estandar .
                generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
                //genera factura de servicio ADICIONAL (p.e 4 días)
                $valorf=$valorServicioAdicional;
                $valorp=$valorServicioAdicional;				
                $saldo=0;
                $cerrado=1;	
                $notas="Prorrateo";
                generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
                $valorr=$valorServicioAdicional;
                $valorap=$valorServicioAdicional;	
                $fecha=$today;
                $aprobado=0;;
                $descripcion=$notas;
                //se genera transaccion de servicio ADICIONAL(p.e 4 días)
                generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
                $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de :".$mes[$twoMonthMoreTonextMonth]; 
                //print "\n $billMessageperiod \n";   
            }else{//No genera transaccion
                //genera factura estandar
                $valorf=$valorPlan;				
                $valorp=0;				
                $saldo=$valorPlan;
                $cerrado=0;	
                $notas="Servicio-1er mes";
                generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
                //genera factura de servicio ADICIONAL (p.e 4 días)
                $valorf=$valorServicioAdicional;				
                $valorp=0;				
                $saldo=$valorServicioAdicional;
                $cerrado=0;
                $notas="Prorrateo";
                generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
                $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$oneMonthMoreTonextMonth];     
                //print "\n $billMessageperiod \n";                                   
            }      
        }else{
            $valorf=$valorPlan;
            if ($afiliation_include_first_month){
                //Valor de factura de mensualidad 0
                $valorp=$valorPlan;				
                $saldo=0;
                $cerrado=1;
                $notas="Servicio-1er mes";	
                generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
                $valorr=$valorPlan;
                $valorap=$valorPlan;	
                $fecha=$today;
                $aprobado=0;;
                $descripcion=$notas;
                generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
                $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de :".$mes[$twoMonthMoreTonextMonth];
                //print "\n $billMessageperiod \n";    
            }else{//No genera transaccion
                //Valor de factura de mensualidad =>$plan mensual
                $valorp=0;				
                $saldo=$valorPlan;
                $cerrado=0;	
                $notas="Servicio-1er mes";
                generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
                $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$oneMonthMoreTonextMonth]; 
                //print "\n $billMessageperiod \n";                                       
            } 
        }
        updateAfiliado($clientObject,$id_client,"standby",2);                                       
    }
}
if ($clientAfiliateDaySelected ==$lastDayofMonth){
    //print "\n He seleccionado el último día del mes! \n";
    $corte =1;
    $dias_de_pago="1 al 7 de cada mes";
    if ($monthSelected == $currentMonth){
        $periodo =$mes[$nextMonth];
        mesActual($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,0,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth,$billLastDay=true,$oneMonthMoreTonextMonth);//verificar que el valor deProrrateo esté en 0 en client.php ,$billLastDay=false
        updateAfiliado($clientObject,$id_client,"standby",1); 
    }
    if ($monthSelected == $nextMonth){
        $periodo =$mes[$nextMonth]+1;
        mesSiguiente($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,$oneMonthMoreTonextMonth,0,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth,$billLastDay=true);//verificar que el valor deProrrateo esté en 0 en client.php
        updateAfiliado($clientObject,$id_client,"standby",2); 
    }
}
//factura de item value afiliacon saldo 0 siempre //
facturaAfiliacion($billObject,$id_client,$periodo,$notas="Afiliacion de servicio",$valorf=$AfiliacionItemValue,$valorp=$AfiliacionItemValue,$saldo=0,$cerrado=1,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
transaccionFacturaAfiliacion($transactionObject,$id_client,$cajero,$hora,$valorr=$AfiliacionItemValue,$valorap=$AfiliacionItemValue,$cambio=0,$fecha,$aprobado=1,$descripcion="Afiliacion de servicio"); 

if($serviceIsAlreadyInstalled){
    emailToInstalledNewUser($emailObject,$tokenToInstalledUserNew,$fullName,$periodo,$valorPlan,$id_client,$dias_de_pago,$email);
}else{
    emailToUserNoInstalledYet($emailObject,$tokenToNotInstalledUserNewYet,$fullName,$periodo,$valorPlan,$id_client,$dias_de_pago,$email);
    generar_Ticket($tickeObject,$phoneContact);
}
echo "$idClient:Usuario agregado con exito!!";
function afiliar_cliente($clientObject,$name, $lastName, $cedula, $address, $ciudad, $departamento, $email, $phone, $valorPlan,$corte, $nextPay,$billDeliveryNumber, $velocidadPlan, $plan, $today, $source="ispdev", $activo, $ipAddress, $standby, $AfiliacionItemValue,  $usuario, $idClientArea, $empresa){
    $id_client=$clientObject->createClient($name, $lastName, $cedula, $address, $ciudad, $departamento, $email, $phone, $valorPlan,$corte, $nextPay,$billDeliveryNumber, $velocidadPlan, $plan, $today, $source="ispdev", $activo="1", $ipAddress, $standby, $AfiliacionItemValue,  $usuario, $idClientArea, $empresa);
    return $id_client; 
}    
function mesActual($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,$prorateo_checked,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth,$billLastDay=false,$oneMonthMoreTonextMonth){
    if ($prorateo_checked){
        $valorf=$prorrateo;
        if ($afiliation_include_first_month){
            //factura de mensualidad paga
            //Valor de factura de mensualidad 0
            $valorp=$prorrateo;				
            $saldo=0;
            $cerrado=1;	
            $notas="Servicio-1er mes-Prorrateo";
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $valorr=$prorrateo;
            $valorap=$prorrateo;	
            $fecha=$today;
            $aprobado=0;;
            $descripcion=$notas;
            generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$nextMonth];  
            //print "\n $billMessageperiod \n";                                       
        }else{//No genera transaccion
            //Valor de factura de mensualidad $prorrateo
            $valorp=0;				
            $saldo=$prorrateo;
            $cerrado=0;
            $notas="Servicio-1er mes-Prorrateo";
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$currentMonth];
            //print "\n $billMessageperiod \n";                                        
        }
    }else{//No hayProrrateo
        $valorf=$valorPlan;				
        if ($afiliation_include_first_month){//factura de mensualidad paga
            //Valor de factura de mensualidad 0
            //se genera factura  estandar
            $valorp=$valorPlan;				
            $saldo=0;
            $cerrado=1;
            $notas="Servicio-1er mes";	
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $valorr=$valorPlan;
            $valorap=$valorPlan;	
            $fecha=$today;
            $aprobado=0;;
            $descripcion=$notas;
            //se genera transaccion  factura  estandar
            generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            if($billLastDay==false){
                $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de :". $mes[$nextMonth];  
            }else{
                $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de :". $mes[$oneMonthMoreTonextMonth];  
            }
            //print "\n $billMessageperiod \n";                                      
        }else{//No genera transaccion

            //se genera factura  estandar
            $valorp=0;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas="Servicio-1er mes";
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            if($billLastDay==false){
                $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$currentMonth];
            }else{
                $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de :".$mes[$nextMonth];  
            }
            //print "\n $billMessageperiod \n";                                        
        }
    }  
}
function mesActualConServicioAdicional($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,$valorServicioAdicional,$prorateo_checked,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth){
    if ($prorateo_checked){
        if ($afiliation_include_first_month){
            
            //se genera factura de servicio estandar .
            $valorf=$valorPlan;
            $valorp=$valorPlan;				
            $saldo=0;
            $cerrado=1;
            $notas="Servicio-1er mes";	
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $valorr=$valorPlan;
            $valorap=$valorPlan;	
            $fecha=$today;
            $aprobado=0;;
            $descripcion=$notas;
            //se genera transaccion de de servicio estandar.
            generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            //se genera factura de servicio ADICIONAL(p.e 4 días)
            $valorf=$valorServicioAdicional;
            $valorp=$valorServicioAdicional;				
            $saldo=0;
            $cerrado=1;
            $notas="Prorrateo";	
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $valorr=$valorServicioAdicional;
            $valorap=$valorServicioAdicional;	
            $fecha=$today;
            $aprobado=0;;
            $descripcion=$notas;
            //se genera transaccion de servicio ADICIONAL(p.e 4 días)
            generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            
            $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$nextMonth];
            //print "\n $billMessageperiod \n";                                        
        }else{//No genera transaccion
            //se genera factura de servicio estandar.
            $valorf=$valorPlan;
            $valorp=0;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas="Servicio-1er mes";
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            //se genera factura de servicio ADICIONAL(p.e 4 días)
            $valorf=$valorServicioAdicional;
            $valorp=0;				
            $saldo=$valorServicioAdicional;
            $cerrado=0;	
            $notas="Prorrateo";
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$currentMonth];
            //print "\n $billMessageperiod \n";                                        
        }
    }else{//No hayProrrateo
        $valorf=$valorPlan;				
        if ($afiliation_include_first_month){//factura de mensualidad paga
            //Valor de factura de mensualidad 0
            //se genera factura de servicio estandar .
            $valorp=$valorPlan;				
            $saldo=0;
            $cerrado=1;
            $notas="Servicio-1er mes";	
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $valorr=$valorPlan;
            $valorap=$valorPlan;	
            $fecha=$today;
            $aprobado=0;;
            $descripcion=$notas;
            //se genera transaccion  de servicio estandar.
            generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$nextMonth]; 
            //print "\n $billMessageperiod \n";                                       
        }else{//No genera transaccion
            //se genera factura de servicio estandar.
            $valorp=0;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas="Servicio-1er mes";
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$currentMonth];  
            //print "\n $billMessageperiod \n";                                      
        }
    }  
}
function mesSiguiente($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,$oneMonthMoreTonextMonth,$prorateo_checked,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth,$billLastDay=false){
    if ($prorateo_checked){
        $valorf=$prorrateo;
        if ($afiliation_include_first_month){//factura de mensualidad paga
            //Valor de factura de mensualidad 0
            $valorp=$prorrateo;				
            $saldo=0;
            $cerrado=1;
            $notas="Servicio-1er mes-prorrateo";
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $valorr=$prorrateo;
            $valorap=$prorrateo;	
            $fecha=$today;
            $aprobado=0;;
            $descripcion=$notas;
            generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de :".$mes[$oneMonthMoreTonextMonth];
            //print "\n $billMessageperiod \n";                                        
        }else{//No genera transaccion
            $valorp=0;				
            $saldo=$prorrateo;
            $cerrado=0;
            $notas="Servicio-1er mes-Prorrateo";
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$nextMonth];
            //print "\n $billMessageperiod \n";                                        
        }
    }else{//No hayProrrateo
        $valorf=$valorPlan;
        if ($afiliation_include_first_month){//factura de mensualidad paga
            //Valor de factura de mensualidad 0
            //se genera factura  estandar
            $valorp=$valorPlan;				
            $saldo=0;
            $cerrado=1;
            $notas="Servicio-1er mes";	
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $valorr=$valorPlan;
            $valorap=$valorPlan;	
            $fecha=$today;
            $aprobado=0;;
            $descripcion=$notas;
            generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$oneMonthMoreTonextMonth];
            //print "\n $billMessageperiod \n";                                        
        }else{//No genera transaccion
            //Valor de factura de mensualidad =>$plan mensual
            $valorp=$valorPlan;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas="Servicio-1er mes";
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);                                      
            $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$nextMonth];
            //print "\n $billMessageperiod \n";                                        
        }
    } 
}
function mesSiguienteConServicioAdicional($transactionObject,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos,$billObject,$dias_de_pago,$mes,$valorServicioAdicional,$prorateo_checked,$prorrateo,$afiliation_include_first_month,$valorPlan,$id_client,$cajero,$hora,$fecha,$periodo,$today,$cambio=0,$nextMonth,$currentMonth,$oneMonthMoreTonextMonth){
    if ($prorateo_checked){
        if ($afiliation_include_first_month){
            //se genera factura de servicio estandar .
            $valorf=$valorPlan;
            $valorp=$valorPlan;				
            $saldo=0;
            $cerrado=1;
            $notas="Servicio-1er mes";	
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $valorr=$valorPlan;
            $valorap=$valorPlan;	
            $fecha=$today;
            $aprobado=0;;
            $descripcion=$notas;
            //se genera transaccion de de servicio estandar.
            generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            //se genera factura de servicio ADICIONAL(p.e 4 días)
            $valorf=$valorServicioAdicional;
            $valorp=$valorServicioAdicional;				
            $saldo=0;
            $cerrado=1;
            $notas="Prorrateo";	
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $valorr=$valorServicioAdicional;
            $valorap=$valorServicioAdicional;	
            $fecha=$today;
            $aprobado=0;;
            $descripcion=$notas;
            //se genera transaccion de servicio ADICIONAL(p.e 4 días)
            generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            
            $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$oneMonthMoreTonextMonth];
            //print "\n $billMessageperiod \n";                                        
        }else{//No genera transaccion
            //se genera factura de servicio estandar.
            $valorf=$valorPlan;
            $valorp=0;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas="Servicio-1er mes";
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            //se genera factura de servicio ADICIONAL(p.e 4 días)
            $valorf=$valorServicioAdicional;
            $valorp=0;				
            $saldo=$valorServicioAdicional;
            $cerrado=0;	
            $notas="Prorrateo";
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$nextMonth];  
            //print "\n $billMessageperiod \n";                                      
        }
    }else{//No hayProrrateo
        $valorf=$valorPlan;				
        if ($afiliation_include_first_month){//factura de mensualidad paga
            //Valor de factura de mensualidad 0
            //se genera factura de servicio estandar .
            $valorp=$valorPlan;				
            $saldo=0;
            $cerrado=1;
            $notas="Servicio-1er mes";	
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $valorr=$valorPlan;
            $valorap=$valorPlan;	
            $fecha=$today;
            $aprobado=0;;
            $descripcion=$notas;
            //se genera transaccion  de servicio estandar.
            generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de :".$mes[$oneMonthMoreTonextMonth]; 
            //print "\n $billMessageperiod \n";                                         
        }else{//No genera transaccion
            //se genera factura de servicio estandar.
            $valorp=0;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas="Servicio-1er mes";
            generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
            $billMessageperiod="Próximo pago los días :  $dias_de_pago   a partir de : ".$mes[$nextMonth]; 
            //print "\n $billMessageperiod \n";                                        
        }
    }  
}
function emailToInstalledNewUser($emailObject,$tokenToInstalledUserNew,$fullName,$periodo,$valorPlan,$id_client,$dias_de_pago,$email){
    //print "\n  ToInstalledUserNew,fullName,periodo,valorPlan,id_client,dias_de_pago,email $tokenToInstalledUserNew,$fullName,$periodo,$valorPlan,$id_client,$dias_de_pago,$email ";
    if($email!=""){
        $emailArray=[
        "fullName"=> $fullName,
        "paymentDay"=> $dias_de_pago,
        "periodo"=> $periodo,
        "valorPlan"=> $valorPlan,
        "template"=>$tokenToInstalledUserNew,
        "idClient"=>$id_client,
        "email"=>$email
        ];
        $emailObject->emailToInstalledNewUser($emailArray);
    }
    
}
function emailToUserNoInstalledYet($emailObject,$tokenToNotInstalledUserNewYet,$fullName,$periodo,$valorPlan,$id_client,$dias_de_pago,$email){
    if($email!=""){
        $emailArray=[
        "fullName"=> $fullName,
        "paymentDay"=> $dias_de_pago,
        "periodo"=> $periodo,
        "valorPlan"=> $valorPlan,
        "template"=>$tokenToNotInstalledUserNewYet,
        "idClient"=>$id_client,
        "email"=>$email
        ];
        $emailObject->emailToInstalledNewUser($emailArray);
    }
}
function  generar_Ticket($tickeObject,$telefonoContacto){
    //print "\n generar_Ticket() ,telefonoContacto : $telefonoContacto \n";
    $tickeObject->createTicketNewClient($telefonoContacto,$recomendacion="");
}
function facturaDiasAdicionales($valorAdicionalServicio,$valorAdicionalServicioDescripcion){
    //print "\n facturaDiasAdicionales()";
}
function facturaAfiliacion($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos){
    ////print "\nfacturaAfiliacion() $AfiliacionItemValue";
    $billObject->createBill($id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
}
function transaccionFacturaAfiliacion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion){
    $transactionObject->CreateTransaction($id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado="1",$descripcion);
}
function generar_factura($billObject,$id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos){
    $billObject->createBill($id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos);
}
function generar_transaccion($transactionObject,$id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion){
    ////print "\n generar_transaccion($transactionObject,) id_client,cajero,hora,valorr,valorap,cambio,fecha,aprobado,descripcion * $id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion";
    $transactionObject->CreateTransaction($id_client,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado="1",$descripcion);
}
function updateAfiliado($clientObject,$id_client,$param,$value){     
    $clientObject->updateClient($id_client,$param,$value);  
}


//////////      

 


/**
*Tipos de factura:          Factura estandar
*                           Factura deProrrateo
*                           Factura de servicio adicional

*Tipos de transaccion       Transaccion de Factura estandar
*                           Transaccion de Factura deProrrateo
*                           Transaccion de Servicio adicional
*/
// if($Fechas ==1){
//     if($currentMonth){
        
//         if($currentMonth_pago){
//             //se genera factura  estandar
//             //se genera transaccion  factura  estandar
//         }else{
//             //se genera factura  estandar
//         }
//     }
// }
// if($Fechas >= 2 && $fecha <= 10){
//     if($currentMonth){
//         if($prorrateo){
//             if($currentMonth_pago){
//                 //se genera factura de prorrteo 
//                 //se genera transaccion factura de prorrteo 
//             }else{
//                 //se genera factura de prorrteo
//             }
//         }else{
//             if($currentMonth_pago){
//                 //se genera  factura  estandar 
//                 //se genera transaccion  factura  estandar 
//             }else{
//                 //se genera  factura  estandar
//             } 
//         }
//     }
// }
// if($Fechas >10 && $fecha<15 ){
//     //corte 15
//     if($currentMonth){
//         if($prorrateo){
//             if($currentMonth_pago){
//                 //se genera factura de servicio estandar .
//                 //se genera factura de servicio ADICIONAL(p.e 4 días)
//                 //se genera transaccion de de servicio estandar.
//                 //se genera transaccion de servicio ADICIONAL(p.e 4 días)
//             }else{
//                 //se genera factura de servicio estandar.
//                 //se genera factura de servicio ADICIONAL(p.e 4 días)
//             }
//         }else{
//             if($currentMonth_pago){
//                 //se genera factura de servicio estandar .
//                 //se genera transaccion de de servicio estandar.
//             }else{
//                 //se genera factura de servicio estandar.
//             }
//         }
//     }else{

//     }
// }
// if($Fechas ==15){
//     if($currentMonth){
//         if($currentMonth_pago){
//             //se genera factura  estandar
//             //se genera transaccion  factura  estandar
//         }else{
//             //se genera factura  estandar
//         }
//     }
// } 
// if($Fecha >15 && $fecha <=19){
//     if($currentMonth){
//         if($prorrateo){
//             if($currentMonth_pago){
//                 //se genera factura de prorrteo
//                 //se genera transaccion factura de prorrteo  
//             }else{
//                 //se genera factura de prorrteo
//             }
//         }else{
//             if($currentMonth_pago){
//                 //se genera factura estandar
//                 //se genera transaccion factura estandar  
//             }else{
//                 //se genera factura estandar
//             }
//         }
//     }else{

//     }
// } 

// if($Fecha >=20 && $fecha<$lastDayofMonth){
//     if($currentMonth){
//         if ($prorrateo){
//             if($currentMonth_pago){
//                 //genera factura estandar
//                 //genera factura de servicio ADICIONAL (p.e 4 días)
//                 //se genera transaccion de de servicio estandar mes completo.
//                 //se genera transaccion de servicio ADICIONAL(p.e 4 días)
//             }else{
//                 //genera factura estandar
//                 //genera factura de servicio ADICIONAL (p.e 4 días)
//             }
//         }else{
//             if($currentMonth_pago){
//                 //genera factura estandar
//                 //se genera transaccion de de servicio estandar mes completo.
//             }else{
//                 //genera factura estandar
//             }
//         }
//     }else{
//     }
// } 

?> 