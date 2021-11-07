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
//**1- registrar un cliente
$hora=$hourMin;
$cajero=$usuario;
$cambio=0;
//
$valorPlan= "51000";
$name= "Omar Alberto";
$lastName= "Hernandez Diaz";
$cedula= "17446879";
$address= "Cra 9#13-45";
$city= mysqli_real_escape_string($mysqli, $_REQUEST['ciudad']);
$pieces = explode("-", $city);
$idClientArea="111";
$ciudad="Guamal";
$departamento= "Meta";
$phone= "3147654655";
$email= "omar.a.hernandez.d@gmail.com";
$corte= "1";
$mesAfacturar= "11";
$plan= "Residencial";
$velocidadPlan= "5";
$generarFactura= "1";//It always will be set to 1
$ipAddress= "192.168.20.130";
$mergeItems= 1;
$valorAfiliacion= "250000";
$standarServiceFlag= 1;
$AfiliacionItemValue= mysqli_real_escape_string($mysqli, $_REQUEST['AfiliacionItemValue']);
$valorProrrateo= mysqli_real_escape_string($mysqli, $_REQUEST['valorProrrateo']);
$valorApagar= mysqli_real_escape_string($mysqli, $_REQUEST['valorApagar']);
$iva= 19;
$valorAdicionalServicio= mysqli_real_escape_string($mysqli, $_REQUEST['valorAdicionalServicio']);
$valorAdicionalServicioDescripcion= mysqli_real_escape_string($mysqli, $_REQUEST['valorAdicionalServicioDescripcion']);
//*
$afiliacion_incluye_primer_mes=$mergeItems;
$prorateo_checked=!$standarServiceFlag;
//*
$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
$monthn = date("n");
//
$id_cliente=afiliar_cliente($mysql);
actualizar_grupo_red_afiliado($id);  





















//agregar fecha de afiliacion
if ($fechaAfiliacion ==1){
    $corte =1;
    $dias_de_pago="1-7";
    if ($fecha_seleccionada == $mes_actual){
        $periodo =$mes_actual;
        mesActual();//verificar que el valor de prorrateo esté en 0 en client.php
    }
    if ($fecha_seleccionada == $mes_siguiente){
        $periodo =$mes_siguiente;
        mesSiguiente();//verificar que el valor de prorrateo esté en 0 en client.php
        updateAfiliado("standby",1); 
    }
}
if ($fechaAfiliacion >= 2 && $fechaAfiliacion <=10){
    $corte =1;
    $dias_de_pago="1-7";
    if ($fecha_seleccionada == $mes_actual){
        $periodo =$mes_actual;
        mesActual();
    }
    if ($fecha_seleccionada == $mes_siguiente){
        $periodo =$mes_siguiente;
        mesSiguiente();
        updateAfiliado("standby",1); 
    }
}
if ($fechaAfiliacion>10 && $fechaAfiliacion<15){
    $corte=15;
    $dias_de_pago="15-19";
    if ($fecha_seleccionada == $mes_actual){
        $periodo =$mes_actual;   
        mesActualConServicioAdicional($valorServicioAdicional);
        
    }
    if ($fecha_seleccionada == $mes_siguiente){
        $periodo =$mes_siguiente;
        mesSiguienteConServicioAdicional($valorServicioAdicional);
        updateAfiliado("standby",1);
    }
}
if ($fechaAfiliacion ==15){
    $corte =15;
    $dias_de_pago="15-19";
    if ($fecha_seleccionada == $mes_actual){
        $periodo =$mes_actual;
        mesActual();//verificar que el valor de prorrateo esté en 0 en client.php
    }
    if ($fecha_seleccionada == $mes_siguiente){
        $periodo =$mes_siguiente;
        mesSiguiente();//verificar que el valor de prorrateo esté en 0 en client.php
        updateAfiliado("standby",1); 
    }
} 
if ($fechaAfiliacion >=20 && $fechaAfiliacion<$lastDayofMonth){
    $corte=1;
    $dias_de_pago="20-30";
    if ($fecha_seleccionada == $mes_actual){
        $periodo=$mes_siguiente; //(***Se debe generar para que el cliente sepa que está pagando el mes siguiente);
        if ($prorateo_checked){
            if ($afiliacion_incluye_primer_mes){
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
                $email_mensaje_de_facturacion="a partir de $mes_siguiente+1";    
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
                $email_mensaje_de_facturacion="a partir de $mes_siguiente";
            }      
        }else{
            $valorf=$valorPlan;
            if ($afiliacion_incluye_primer_mes){
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
                $email_mensaje_de_facturacion="a partir de $mes_siguiente+1";    
            }else{//No genera transaccion
                //Valor de factura de mensualidad =>$plan mensual
                $valorp=0;				
                $saldo=$valorPlan;
                $cerrado=0;	
                $notas=" Servicio-1er mes";
                generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
                $email_mensaje_de_facturacion="a partir de $mes_siguiente";
            } 
        }
        updateAfiliado("standby",1);                                      
    }
    if($fecha_seleccionada == $mes_siguiente){
        $periodo=$mes_siguiente+1; 
        if ($prorateo_checked){
            $valorf=$prorrateo;
            if ($afiliacion_incluye_primer_mes){
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
                $email_mensaje_de_facturacion="a partir de".$mes_siguiente+2;    
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
                $email_mensaje_de_facturacion="a partir de ".$mes_siguiente+1;                                        
            }      
        }else{
            $valorf=$valorPlan;
            if ($afiliacion_incluye_primer_mes){
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
                $email_mensaje_de_facturacion="a partir de".$mes_siguiente+2;    
            }else{//No genera transaccion
                //Valor de factura de mensualidad =>$plan mensual
                $valorp=0;				
                $saldo=$valorPlan;
                $cerrado=0;	
                $notas=" Servicio-1er mes";
                generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
                $email_mensaje_de_facturacion="a partir de ".$mes_siguiente+1;                                        
            } 
        }
        updateAfiliado($standby=2);                                       
    }
}
if ($fechaAfiliacion ==$lastDayofMonth){
    $corte =1;
    $dias_de_pago="1-7";
    if ($fecha_seleccionada == $mes_actual){
        $periodo =$mes_siguiente;
        mesActual();//verificar que el valor de prorrateo esté en 0 en client.php
        updateAfiliado("standby",1); 
    }
    if ($fecha_seleccionada == $mes_siguiente){
        $periodo =$mes_siguiente+1;
        mesSiguiente();//verificar que el valor de prorrateo esté en 0 en client.php
        updateAfiliado("standby",2); 
    }
}
if($servicioYaInstalado){
    emailInstalledUser();
}else{
    emailUserNoInstalledyet();
    generar_Ticket();
}    
function mesActual(){
    if ($prorateo_checked){
        $valorf=$prorrateo;
        if ($afiliacion_incluye_primer_mes){
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
            $email_mensaje_de_facturacion="a partir de $mes_siguiente";                                        
        }else{//No genera transaccion
            //Valor de factura de mensualidad $prorrateo
            $valorp=0;				
            $saldo=$prorrateo;
            $cerrado=0;
            $notas=" Servicio-1er mes-Prorrateo";
            generar_factura($periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $email_mensaje_de_facturacion="a partir de $mes_actual";                                        
        }
    }else{//No hay prorrateo
        $valorf=$valorPlan;				
        if ($afiliacion_incluye_primer_mes){//factura de mensualidad paga
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
            $email_mensaje_de_facturacion="a partir de $mes_siguiente";                                        
        }else{//No genera transaccion
            //se genera factura  estandar
            $valorp=0;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas=" Servicio-1er mes";
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $email_mensaje_de_facturacion="a partir de $mes_actual";                                        
        }
    }  
}
function mesActualConServicioAdicional($valorServicioAdicional){
    if ($prorateo_checked){
        if ($afiliacion_incluye_primer_mes){
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
            
            $email_mensaje_de_facturacion="a partir de $mes_siguiente";                                        
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
            $email_mensaje_de_facturacion="a partir de $mes_actual";                                        
        }
    }else{//No hay prorrateo
        $valorf=$valorPlan;				
        if ($afiliacion_incluye_primer_mes){//factura de mensualidad paga
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
            $email_mensaje_de_facturacion="a partir de $mes_siguiente";                                        
        }else{//No genera transaccion
            //se genera factura de servicio estandar.
            $valorp=0;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas=" Servicio-1er mes";
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $email_mensaje_de_facturacion="a partir de $mes_actual";                                        
        }
    }  
}
function mesSiguiente(){
    if ($prorateo_checked){
        $valorf=$prorrateo;
        if ($afiliacion_incluye_primer_mes){//factura de mensualidad paga
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
            $email_mensaje_de_facturacion="a partir de $mes_siguiente+1";                                        
        }else{//No genera transaccion
            $valorp=0;				
            $saldo=$prorrateo;
            $cerrado=0;
            $notas=" Servicio-1er mes-Prorrateo";
            generar_factura($periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $email_mensaje_de_facturacion="a partir de $mes_siguiente";                                        
        }
    }else{//No hay prorrateo
        $valorf=$valorPlan;
        if ($afiliacion_incluye_primer_mes){//factura de mensualidad paga
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
            $email_mensaje_de_facturacion="a partir de ".$mes_siguiente+1;                                        
        }else{//No genera transaccion
            //Valor de factura de mensualidad =>$plan mensual
            $valorp=$valorPlan;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas=" Servicio-1er mes";
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);                                      
            $email_mensaje_de_facturacion="a partir de $mes_siguiente";                                        
        }
    } 
}
function mesSiguienteConServicioAdicional($valorServicioAdicional){
    if ($prorateo_checked){
        if ($afiliacion_incluye_primer_mes){
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
            
            $email_mensaje_de_facturacion="a partir de $mes_siguiente";                                        
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
            $email_mensaje_de_facturacion="a partir de $mes_actual";                                        
        }
    }else{//No hay prorrateo
        $valorf=$valorPlan;				
        if ($afiliacion_incluye_primer_mes){//factura de mensualidad paga
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
            $email_mensaje_de_facturacion="a partir de $mes_siguiente";                                        
        }else{//No genera transaccion
            //se genera factura de servicio estandar.
            $valorp=0;				
            $saldo=$valorPlan;
            $cerrado=0;	
            $notas=" Servicio-1er mes";
            generar_factura($id_cliente,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado);
            $email_mensaje_de_facturacion="a partir de $mes_actual";                                        
        }
    }  
}
function emailInstalledUser(){
    $email='{
        "mensaje de bienvenida": "Bienvenido a AG INGENIERIA WIST, Gracias por elegirnos como tu proveedor de servicio de Internet Banda Ancha",
        "usuario": "Pepito perez Sosa",
        "mensaje de facturacion": "Tu fecha de pago ==l $dias_de_pago de cada mes $$email_mensaje_de_facturacion",
        " Valor de la factura": "$50.000 Plan de Internet 3.5 Megas ",
        "medio de pago": "Comunicarse al 3147654655",
        "oficina principal": "Cll 13#8-47" }';
    
}
function emailUserNoInstalledyet(){
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




 
























/**
*Tipos de factura:          Factura estandar
*                           Factura de prorrateo
*                           Factura de servicio adicional

*Tipos de transaccion       Transaccion de Factura estandar
*                           Transaccion de Factura de prorrateo
*                           Transaccion de Servicio adicional
 */
if($Fechas ==1){
    if($mes_actual){
        
        if($mes_actual_pago){
            //se genera factura  estandar
            //se genera transaccion  factura  estandar
        }else{
            //se genera factura  estandar
        }
    }
}
if($Fechas >= 2 && $fecha <= 10){
    if($mes_actual){
        if($prorrateo){
            if($mes_actual_pago){
                //se genera factura de prorrteo 
                //se genera transaccion factura de prorrteo 
            }else{
                //se genera factura de prorrteo
            }
        }else{
            if($mes_actual_pago){
                //se genera  factura  estandar 
                //se genera transaccion  factura  estandar 
            }else{
                //se genera  factura  estandar
            } 
        }
    }
}
if($Fechas >10 && $fecha<15 ){
    //corte 15
    if($mes_actual){
        if($prorrateo){
            if($mes_actual_pago){
                //se genera factura de servicio estandar .
                //se genera factura de servicio ADICIONAL(p.e 4 días)
                //se genera transaccion de de servicio estandar.
                //se genera transaccion de servicio ADICIONAL(p.e 4 días)
            }else{
                //se genera factura de servicio estandar.
                //se genera factura de servicio ADICIONAL(p.e 4 días)
            }
        }else{
            if($mes_actual_pago){
                //se genera factura de servicio estandar .
                //se genera transaccion de de servicio estandar.
            }else{
                //se genera factura de servicio estandar.
            }
        }
    }else{

    }
}
if($Fechas ==15){
    if($mes_actual){
        if($mes_actual_pago){
            //se genera factura  estandar
            //se genera transaccion  factura  estandar
        }else{
            //se genera factura  estandar
        }
    }
} 
if($Fecha >15 && $fecha <=19){
    if($mes_actual){
        if($prorrateo){
            if($mes_actual_pago){
                //se genera factura de prorrteo
                //se genera transaccion factura de prorrteo  
            }else{
                //se genera factura de prorrteo
            }
        }else{
            if($mes_actual_pago){
                //se genera factura estandar
                //se genera transaccion factura estandar  
            }else{
                //se genera factura estandar
            }
        }
    }else{

    }
} 

if($Fecha >=20 && $fecha<$lastDayofMonth){
    if($mes_actual){
        if ($prorrateo){
            if($mes_actual_pago){
                //genera factura estandar
                //genera factura de servicio ADICIONAL (p.e 4 días)
                //se genera transaccion de de servicio estandar mes completo.
                //se genera transaccion de servicio ADICIONAL(p.e 4 días)
            }else{
                //genera factura estandar
                //genera factura de servicio ADICIONAL (p.e 4 días)
            }
        }else{
            if($mes_actual_pago){
                //genera factura estandar
                //se genera transaccion de de servicio estandar mes completo.
            }else{
                //genera factura estandar
            }
        }
    }else{
    }
} 




?>