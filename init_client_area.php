<?php

echo"arranca okg"; 
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
$monthn = date("n");
$periodo=$mes[3];
$cont=0;															
$sql = "SELECT * FROM `afiliados` WHERE   1 ORDER BY `id` ASC ";
if ($result = $mysqli->query($sql)) {
	while ($row = $result->fetch_assoc()) {		
        $cont++;	
        $ip=$row["ip"];
        $id=$row["id"];
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $part = explode(".", $ip);
            if ($part[2]=="30" || $part[2]=="60"){
                print "\n $cont- $ip : \t\t\t Actualizo la red de Salcedo. id_client_area: 4325 ";
                //$sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`id_client_area`='4325' WHERE `afiliados`.`id`='$id'  ";
                if($result2 = $mysqli->query($sqlUpd)){						
                    echo " Actualizacion con éxito!! \n";	
                }
                else{
                    echo " Error al actualizar \n";	 
                }
            }
            else{
                print "\n $cont- $ip : Actualizo las demas. id_client_area: 4324 ";
                //$sqlUpd="UPDATE `redesagi_facturacion`.`afiliados` SET `afiliados`.`id_client_area`='4324' WHERE `afiliados`.`id`='$id'  ";
                if($result2 = $mysqli->query($sqlUpd)){						
                    echo " Actualizacion con éxito!! \n";	
                }
                else{
                    echo " Error al actualizar \n";	 
                }
            }

            
            
        }
        else{
            print "\n $cont- $ip : Invalid.";
        }
		
		}
    	//$result->free(); ubicar esta linea
	}

 echo "termina en :$cont"; 

 ?>