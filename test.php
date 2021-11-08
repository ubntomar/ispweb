<?php
$user="Omar";
$administrador="Omar";
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$usuario=$_SESSION['username'];
$blankDate="0000/00/00";
$debug=false;

//
// $valorPlan= mysqli_real_escape_string($mysqli, $_REQUEST['valorPlan']);
// $name= mysqli_real_escape_string($mysqli, $_REQUEST['name']);
// $lastName= mysqli_real_escape_string($mysqli, $_REQUEST['lastName']);
// $cedula= mysqli_real_escape_string($mysqli, $_REQUEST['cedula']);
// $address= mysqli_real_escape_string($mysqli, $_REQUEST['address']);
// $city= mysqli_real_escape_string($mysqli, $_REQUEST['ciudad']);
// $pieces = explode("-", $city);
// $idClientArea=$pieces[0];
// $ciudad=$pieces[1];
// $departamento= mysqli_real_escape_string($mysqli, $_REQUEST['departamento']);
// $phone= mysqli_real_escape_string($mysqli, $_REQUEST['phone']);
// $email= mysqli_real_escape_string($mysqli, $_REQUEST['email']);
// $corte= mysqli_real_escape_string($mysqli, $_REQUEST['corte']);
// $mesAfacturar= mysqli_real_escape_string($mysqli, $_REQUEST['mesAfacturar']);
// $plan= mysqli_real_escape_string($mysqli, $_REQUEST['plan']);
// $velocidadPlan= mysqli_real_escape_string($mysqli, $_REQUEST['velocidadPlan']);
// $generarFactura= mysqli_real_escape_string($mysqli, $_REQUEST['generarFactura']);//It always will be set to 1
// $ipAddress= mysqli_real_escape_string($mysqli, $_REQUEST['ipAddress']);
// $mergeItems= mysqli_real_escape_string($mysqli, $_REQUEST['mergeItems']);
// $stb=0;
// $recibo= mysqli_real_escape_string($mysqli, $_REQUEST['recibo']);
// $valorAfiliacion= mysqli_real_escape_string($mysqli, $_REQUEST['valorAfiliacion']);
// $standby= mysqli_real_escape_string($mysqli, $_REQUEST['standby']);// (el motor mensual debe restarle 1 unidad) standby value depende de el día del mes en curso
// $standarServiceFlag= mysqli_real_escape_string($mysqli, $_REQUEST['standarServiceFlag']);
// $AfiliacionItemValue= mysqli_real_escape_string($mysqli, $_REQUEST['AfiliacionItemValue']);
// $valorProrrateo= mysqli_real_escape_string($mysqli, $_REQUEST['valorProrrateo']);
// $valorApagar= mysqli_real_escape_string($mysqli, $_REQUEST['valorApagar']);
// $iva= mysqli_real_escape_string($mysqli, $_REQUEST['iva']);
// $valorAdicionalServicio= mysqli_real_escape_string($mysqli, $_REQUEST['valorAdicionalServicio']);
//
$valorPlan= "51000";
$name= "Omar Alberto";
$lastName= "Hernandez Diaz";
$cedula= "17446879";
$address= "Cra 9#13-45";
$idClientArea="9111";
$ciudad="Guamal";
$departamento= "Meta";
$phone= "3147654655";
$email= "omar.a.hernandez.d@gmail.com";
$corte= "1";
$plan= "Residencial";
$velocidadPlan= "5";
$ipAddress= "192.168.20.130";
$mergeItems= 1;
$valorAfiliacion= "250000";
$AfiliacionItemValue= "50000";
$valorProrrateo= "0";
$dayOfMonthSelected=1;
$monthSelected=11;
$iva= 19;
$valorAdicionalServicio= 0;
$valorAdicionalServicioDescripcion= "";
$afiliation_include_first_month=$mergeItems;//0 o 1 if(1)=>true
$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
$monthn = date("n");
actualizar_grupo_red_afiliado($id);  
//***** */
$clientAfiliateDaySelected =$dayOfMonthSelected; 
$dias_de_pago="";
$currentMonth=$monthn;
$nextMonth=$currentMonth==12?1:$currentMonth+1;
$periodo="";
$prorateo_checked=$valorProrrateo=="0"?true:false;
$prorrateo=$valorProrrateo;
$id_cliente=afiliar_cliente($mysql);
$cajero=$usuario;
$hora=$hourMin;
$valorServicioAdicional=$valorAdicionalServicio;
$afiliation_include_first_month=$mergeItems;
$dateld = new DateTime('now');
$dateld->modify('last day of this month');
$lastDayofMonth=$dateld->format('d');
if($AfiliacionItemValue<0){
    $response="error";
    exit;
}
if ($clientAfiliateDaySelected ==1){
    $corte =1;
    $dias_de_pago="1-7";
    if ($monthSelected == $currentMonth){
        $periodo =$currentMonth;
        mesActual();//verificar que el valor de prorrateo esté en 0 en client.php
    }
    if ($monthSelected == $nextMonth){
        $periodo =$nextMonth;
        mesSiguiente();//verificar que el valor de prorrateo esté en 0 en client.php
        updateAfiliado("standby",1); 
    }
    
    
}
if ($clientAfiliateDaySelected >= 2 && $clientAfiliateDaySelected <=10){
    $corte =1;
    $dias_de_pago="1-7";
    if ($monthSelected == $currentMonth){
        $periodo =$currentMonth;
        mesActual();
    }
    if ($monthSelected == $nextMonth){  
        $periodo =$nextMonth;
        mesSiguiente();
        updateAfiliado("standby",1); 
    }
}
if ($clientAfiliateDaySelected>10 && $clientAfiliateDaySelected<15){
    $corte=15;
    $dias_de_pago="15-19";
    if ($monthSelected == $currentMonth){
        $periodo =$currentMonth;   
        mesActualConServicioAdicional($valorServicioAdicional);
        
    }
    if ($monthSelected == $nextMonth){
        $periodo =$nextMonth;
        mesSiguienteConServicioAdicional($valorServicioAdicional);
        updateAfiliado("standby",1);
    }
}
if ($clientAfiliateDaySelected ==15){
    $corte =15;
    $dias_de_pago="15-19";
    if ($monthSelected == $currentMonth){
        $periodo =$currentMonth;
        mesActual();//verificar que el valor de prorrateo esté en 0 en client.php
    }
    if ($monthSelected == $nextMonth){
        $periodo =$nextMonth;
        mesSiguiente();//verificar que el valor de prorrateo esté en 0 en client.php
        updateAfiliado("standby",1); 
    }
} 
if ($clientAfiliateDaySelected >=20 && $clientAfiliateDaySelected<$lastDayofMonth){
    $corte=1;
    $dias_de_pago="20-30";
    if ($monthSelected == $currentMonth){
        $periodo=$nextMonth; //(***Se debe generar para que el cliente sepa que está pagando el mes siguiente);
        if ($prorateo_checked){
            if ($afiliation_include_first_month){
                //Valor de factura de mensualidad 0
                //genera factura estandar
                $valorf=$valorPlan;
                $valorp=$valorPlan;				
                $saldo=0;
                $cerrado=1;
                $notas=" Servicio-1er mes";	
                generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
                $valorr=$valorPlan;
                $valorap=$valorPlan;	
                $fecha=$today;
                $aprobado="";
                $descripcion=$notas;
                //generar transaccion de de servicio estandar .
                generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
                //genera factura de servicio ADICIONAL (p.e 4 días)
                $valorf=$valorServicioAdicional;
                $valorp=$valorServicioAdicional;				
                $saldo=0;
                $cerrado=1;	
                $notas="Prorrateo";
                generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
                $valorr=$valorServicioAdicional;
                $valorap=$valorServicioAdicional;	
                $fecha=$today;
                $aprobado="";
                $descripcion=$notas;
                //se genera transaccion de servicio ADICIONAL(p.e 4 días)
                generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
                $email_mensaje_de_facturacion="a partir de $nextMonth+1";    
            }else{//No genera transaccion
                //genera factura estandar
                $valorf=$valorPlan;				
                $valorp=0;				
                $saldo=$valorPlan;
                $cerrado=0;	
                $notas=" Servicio-1er mes";
                generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
                //genera factura de servicio ADICIONAL (p.e 4 días)
                $valorf=$valorServicioAdicional;				
                $valorp=0;				
                $saldo=$valorServicioAdicional;
                $cerrado=0;
                $notas=" Prorrateo";
                generar_factura($periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
                $email_mensaje_de_facturacion="a partir de $nextMonth";
            }      
        }else{
            $valorf=$valorPlan;
            if ($afiliation_include_first_month){
                //Valor de factura de mensualidad 0
                $valorp=$valorPlan;				
                $saldo=0;
                $cerrado=1;
                $notas=" Servicio-1er mes";	
                generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
                $valorr=$valorPlan;
                $valorap=$valorPlan;	
                $fecha=$today;
                $aprobado="";
                $descripcion=$notas;
                generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
                $email_mensaje_de_facturacion="a partir de $nextMonth+1";    
            }else{//No genera transaccion
                //Valor de factura de mensualidad =>$plan mensual
                $valorp=0;				
                $saldo=$valorPlan;
                $cerrado=0;	
                $notas=" Servicio-1er mes";
                generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
                $email_mensaje_de_facturacion="a partir de $nextMonth";
            } 
        }
        updateAfiliado("standby",1);                                      
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
                $notas=" Servicio-1er mes";	
                generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
                $valorr=$valorPlan;
                $valorap=$valorPlan;	
                $fecha=$today;
                $aprobado="";
                $descripcion=$notas;
                //generar transaccion de de servicio estandar .
                generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
                //genera factura de servicio ADICIONAL (p.e 4 días)
                $valorf=$valorServicioAdicional;
                $valorp=$valorServicioAdicional;				
                $saldo=0;
                $cerrado=1;	
                $notas="Prorrateo";
                generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
                $valorr=$valorServicioAdicional;
                $valorap=$valorServicioAdicional;	
                $fecha=$today;
                $aprobado="";
                $descripcion=$notas;
                //se genera transaccion de servicio ADICIONAL(p.e 4 días)
                generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
                $email_mensaje_de_facturacion="a partir de".$nextMonth+2;    
            }else{//No genera transaccion
                //genera factura estandar
                $valorf=$valorPlan;				
                $valorp=0;				
                $saldo=$valorPlan;
                $cerrado=0;	
                $notas=" Servicio-1er mes";
                generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
                //genera factura de servicio ADICIONAL (p.e 4 días)
                $valorf=$valorServicioAdicional;				
                $valorp=0;				
                $saldo=$valorServicioAdicional;
                $cerrado=0;
                $notas=" Prorrateo";
                generar_factura($periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
                $email_mensaje_de_facturacion="a partir de ".$nextMonth+1;                                        
            }      
        }else{
            $valorf=$valorPlan;
            if ($afiliation_include_first_month){
                //Valor de factura de mensualidad 0
                $valorp=$valorPlan;				
                $saldo=0;
                $cerrado=1;
                $notas=" Servicio-1er mes";	
                generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
                $valorr=$valorPlan;
                $valorap=$valorPlan;	
                $fecha=$today;
                $aprobado="";
                $descripcion=$notas;
                generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
                $email_mensaje_de_facturacion="a partir de".$nextMonth+2;    
            }else{//No genera transaccion
                //Valor de factura de mensualidad =>$plan mensual
                $valorp=0;				
                $saldo=$valorPlan;
                $cerrado=0;	
                $notas=" Servicio-1er mes";
                generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
                $email_mensaje_de_facturacion="a partir de ".$nextMonth+1;                                        
            } 
        }
        updateAfiliado($standby=2);                                       
    }
}
if ($clientAfiliateDaySelected ==$lastDayofMonth){
    $corte =1;
    $dias_de_pago="1-7";
    if ($monthSelected == $currentMonth){
        $periodo =$nextMonth;
        mesActual();//verificar que el valor de prorrateo esté en 0 en client.php
        updateAfiliado("standby",1); 
    }
    if ($monthSelected == $nextMonth){
        $periodo =$nextMonth+1;
        mesSiguiente();//verificar que el valor de prorrateo esté en 0 en client.php
        updateAfiliado("standby",2); 
    }
}
//factura de item value afiliacon saldo 0 siempre 
facturaAfiliacion($AfiliacionItemValue);
transaccionFacturaAfiliacion($AfiliacionItemValue); 

if($serviceIsAlreadyInstalled){
    emailToInstalledUser();
}else{
    emailToUserNoInstalledYet();
    generar_Ticket();
}
function afiliar_cliente($mysql){
    print "hi";
    return 90000;
}    
function mesActual(){
    if ($prorateo_checked){
        $valorf=$prorrateo;
        if ($afiliation_include_first_month){
            //factura de mensualidad paga
            //Valor de factura de mensualidad 0
            $valorp=$prorrateo;				
            $saldo=0;
            $cerrado=1;	
            $notas=" Servicio-1er mes-Prorrateo";
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $valorr=$prorrateo;
            $valorap=$prorrateo;	
            $fecha=$today;
            $aprobado="";
            $descripcion=$notas;
            generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            $email_mensaje_de_facturacion="a partir de $nextMonth";                                        
        }else{//No genera transaccion
            //factura de afiliacon valor completo saldo 0 siempre
            
            
            //Valor de factura de mensualidad $prorrateo
            $valorp=0;				
            $saldo=$prorrateo;
            $cerrado=0;
            $notas=" Servicio-1er mes-Prorrateo";
            generar_factura($periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $email_mensaje_de_facturacion="a partir de $currentMonth";                                        
        }
    }else{//No hay prorrateo
        $valorf=$valorPlan;				
        if ($afiliation_include_first_month){//factura de mensualidad paga
            //Valor de factura de mensualidad 0
            //se genera factura  estandar
            $valorp=$valorPlan;				
            $saldo=0;
            $cerrado=1;
            $notas=" Servicio-1er mes";	
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $valorr=$valorPlan;
            $valorap=$valorPlan;	
            $fecha=$today;
            $aprobado="";
            $descripcion=$notas;
            //se genera transaccion  factura  estandar
            generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            $email_mensaje_de_facturacion="a partir de $nextMonth";                                        
        }else{//No genera transaccion

            //se genera factura  estandar
            $valorp=0;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas=" Servicio-1er mes";
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $email_mensaje_de_facturacion="a partir de $currentMonth";                                        
        }
    }  
}
function mesActualConServicioAdicional($valorServicioAdicional){
    if ($prorateo_checked){
        if ($afiliation_include_first_month){
            
            //se genera factura de servicio estandar .
            $valorf=$valorPlan;
            $valorp=$valorPlan;				
            $saldo=0;
            $cerrado=1;
            $notas=" Servicio-1er mes";	
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $valorr=$valorPlan;
            $valorap=$valorPlan;	
            $fecha=$today;
            $aprobado="";
            $descripcion=$notas;
            //se genera transaccion de de servicio estandar.
            generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            //se genera factura de servicio ADICIONAL(p.e 4 días)
            $valorf=$valorServicioAdicional;
            $valorp=$valorServicioAdicional;				
            $saldo=0;
            $cerrado=1;
            $notas=" Servicio-1er mes";	
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $valorr=$valorServicioAdicional;
            $valorap=$valorServicioAdicional;	
            $fecha=$today;
            $aprobado="";
            $descripcion=$notas;
            //se genera transaccion de servicio ADICIONAL(p.e 4 días)
            generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            
            $email_mensaje_de_facturacion="a partir de $nextMonth";                                        
        }else{//No genera transaccion
            //se genera factura de servicio estandar.
            $valorf=$valorPlan;
            $valorp=0;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas=" Servicio-1er mes";
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            //se genera factura de servicio ADICIONAL(p.e 4 días)
            $valorf=$valorServicioAdicional;
            $valorp=0;				
            $saldo=$valorServicioAdicional;
            $cerrado=0;	
            $notas=" Servicio-1er mes";
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $email_mensaje_de_facturacion="a partir de $currentMonth";                                        
        }
    }else{//No hay prorrateo
        $valorf=$valorPlan;				
        if ($afiliation_include_first_month){//factura de mensualidad paga
            //Valor de factura de mensualidad 0
            //se genera factura de servicio estandar .
            $valorp=$valorPlan;				
            $saldo=0;
            $cerrado=1;
            $notas=" Servicio-1er mes";	
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $valorr=$valorPlan;
            $valorap=$valorPlan;	
            $fecha=$today;
            $aprobado="";
            $descripcion=$notas;
            //se genera transaccion  de servicio estandar.
            generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            $email_mensaje_de_facturacion="a partir de $nextMonth";                                        
        }else{//No genera transaccion
            //se genera factura de servicio estandar.
            $valorp=0;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas=" Servicio-1er mes";
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $email_mensaje_de_facturacion="a partir de $currentMonth";                                        
        }
    }  
}
function mesSiguiente(){
    if ($prorateo_checked){
        $valorf=$prorrateo;
        if ($afiliation_include_first_month){//factura de mensualidad paga
            //Valor de factura de mensualidad 0
            $valorp=$prorrateo;				
            $saldo=0;
            $cerrado=1;
            $notas=" Servicio-1er mes-prorrateo";
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $valorr=$prorrateo;
            $valorap=$prorrateo;	
            $fecha=$today;
            $aprobado="";
            $descripcion=$notas;
            generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            $email_mensaje_de_facturacion="a partir de $nextMonth+1";                                        
        }else{//No genera transaccion
            $valorp=0;				
            $saldo=$prorrateo;
            $cerrado=0;
            $notas=" Servicio-1er mes-Prorrateo";
            generar_factura($periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $email_mensaje_de_facturacion="a partir de $nextMonth";                                        
        }
    }else{//No hay prorrateo
        $valorf=$valorPlan;
        if ($afiliation_include_first_month){//factura de mensualidad paga
            //Valor de factura de mensualidad 0
            //se genera factura  estandar
            $valorp=$valorPlan;				
            $saldo=0;
            $cerrado=1;
            $notas=" Servicio-1er mes";	
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $valorr=$valorPlan;
            $valorap=$valorPlan;	
            $fecha=$today;
            $aprobado="";
            $descripcion=$notas;
            generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            $email_mensaje_de_facturacion="a partir de ".$nextMonth+1;                                        
        }else{//No genera transaccion
            //Valor de factura de mensualidad =>$plan mensual
            $valorp=$valorPlan;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas=" Servicio-1er mes";
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);                                      
            $email_mensaje_de_facturacion="a partir de $nextMonth";                                        
        }
    } 
}
function mesSiguienteConServicioAdicional($valorServicioAdicional){
    if ($prorateo_checked){
        if ($afiliation_include_first_month){
            //se genera factura de servicio estandar .
            $valorf=$valorPlan;
            $valorp=$valorPlan;				
            $saldo=0;
            $cerrado=1;
            $notas=" Servicio-1er mes";	
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $valorr=$valorPlan;
            $valorap=$valorPlan;	
            $fecha=$today;
            $aprobado="";
            $descripcion=$notas;
            //se genera transaccion de de servicio estandar.
            generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            //se genera factura de servicio ADICIONAL(p.e 4 días)
            $valorf=$valorServicioAdicional;
            $valorp=$valorServicioAdicional;				
            $saldo=0;
            $cerrado=1;
            $notas=" Servicio-1er mes";	
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $valorr=$valorServicioAdicional;
            $valorap=$valorServicioAdicional;	
            $fecha=$today;
            $aprobado="";
            $descripcion=$notas;
            //se genera transaccion de servicio ADICIONAL(p.e 4 días)
            generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            
            $email_mensaje_de_facturacion="a partir de $nextMonth";                                        
        }else{//No genera transaccion
            //se genera factura de servicio estandar.
            $valorf=$valorPlan;
            $valorp=0;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas=" Servicio-1er mes";
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            //se genera factura de servicio ADICIONAL(p.e 4 días)
            $valorf=$valorServicioAdicional;
            $valorp=0;				
            $saldo=$valorServicioAdicional;
            $cerrado=0;	
            $notas=" Servicio-1er mes";
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $email_mensaje_de_facturacion="a partir de $currentMonth";                                        
        }
    }else{//No hay prorrateo
        $valorf=$valorPlan;				
        if ($afiliation_include_first_month){//factura de mensualidad paga
            //Valor de factura de mensualidad 0
            //se genera factura de servicio estandar .
            $valorp=$valorPlan;				
            $saldo=0;
            $cerrado=1;
            $notas=" Servicio-1er mes";	
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $valorr=$valorPlan;
            $valorap=$valorPlan;	
            $fecha=$today;
            $aprobado="";
            $descripcion=$notas;
            //se genera transaccion  de servicio estandar.
            generar_transaccion($id_cliente,$cajero,$hora,$valorr,$valorap,$cambio,$fecha,$aprobado,$descripcion);
            $email_mensaje_de_facturacion="a partir de $nextMonth";                                        
        }else{//No genera transaccion
            //se genera factura de servicio estandar.
            $valorp=0;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas=" Servicio-1er mes";
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $email_mensaje_de_facturacion="a partir de $currentMonth";                                        
        }
    }  
}
function emailToInstalledUser(){
    $email='{
        "mensaje de bienvenida": "Bienvenido a AG INGENIERIA WIST, Gracias por elegirnos como tu proveedor de servicio de Internet Banda Ancha",
        "usuario": "Pepito perez Sosa",
        "mensaje de facturacion": "Tu fecha de pago ==l $dias_de_pago de cada mes $$email_mensaje_de_facturacion",
        " Valor de la factura": "$50.000 Plan de Internet 3.5 Megas ",
        "medio de pago": "Comunicarse al 3147654655",
        "oficina principal": "Cll 13#8-47" }';
    
}
function emailToUserNoInstalledYet(){
    $email='{
        "mensaje de bienvenida": "Bienvenido a AG INGENIERIA WIST, Gracias por elegirnos como tu proveedor de servicio de Internet Banda Ancha",
        "usuario": "Pepito perez Sosa",
        "mensaje de facturacion": "Tu fecha de pago ==l $dias_de_pago de cada mes $$email_mensaje_de_facturacion,Ahora el siguiente paso es ponernos en contacto contigo para definir dìa exacto y hora exacta de instalaciòn, te estaremos informando!!",
        "Valor de la factura": "$50.000 Plan de Internet 3.5 Megas ",
        "medio de pago": "Comunicarse al 3147654655",
        "oficina principal": "Cll 13#8-47" 
    }';
}
function  generar_Ticket(){
    print "hellow";
}
function facturaDiasAdicionales($valorAdicionalServicio,$valorAdicionalServicioDescripcion){
    print "hi";
}
function facturaAfiliacion($AfiliacionItemValue){
    print "hi";
}
function transaccionFacturaAfiliacion($AfiliacionItemValue){
    print "hello";
}






/**
*Tipos de factura:          Factura estandar
*                           Factura de prorrateo
*                           Factura de servicio adicional

*Tipos de transaccion       Transaccion de Factura estandar
*                           Transaccion de Factura de prorrateo
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