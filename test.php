
<?php
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');


$sql="SELECT * FROM `redesagi_facturacion`.`afiliados` WHERE `afiliados`.`activo` = 1 AND `afiliados`.`eliminar` = 0 ";
if($response=$mysqli->query($sql)){
    while($row=$response->fetch_assoc()){
        $id=$row["id"];
        $sql="SELECT * FROM `redesagi_facturacion`.`factura` WHERE `factura`.`id-afiliado` = $id  ";
        if ($sqlResponse=$mysqli->query($sql)) {
            $totalperiodoByClient=$sqlResponse->num_rows;
            $cont=0;
            while ($rowResponse=$sqlResponse->fetch_assoc()) {
                $cont++;
                if($cont==$totalperiodoByClient){
                    $periodo=$rowResponse["periodo"];
                    if($periodo=="Enero" || $periodo== "Febrero"){
                        print "->$id::Cliente{{$row["cliente"]}} {{$row["registration-date"]}} Suspendido:{{$row["suspender"]}} Standby:{{$row["standby"]}} \n";
                    }
                }
            } 
        } else {
            print "Error: In Table `Factura`";
        }
       
    }
}else{
    print "Error";
}

?>  