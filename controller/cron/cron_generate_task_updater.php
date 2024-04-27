<?php

if (file_exists("/var/www/ispexperts/login/db.php")) {
    require("/var/www/ispexperts/login/db.php");
} else {
    require("/home/omar/docker-work-area/go/ispweb/login/db.php");
    $server="localhost:3306";
}

if (file_exists("/var/www/ispexperts/Mkt.php")) {
    require("/var/www/ispexperts/Mkt.php");
} else {
    require("/home/omar/docker-work-area/go/ispweb/Mkt.php");
}

if (file_exists('/home/omar/docker-work-area/go/ispweb/vendor/autoload.php')) {
    require_once '/home/omar/docker-work-area/go/ispweb/vendor/autoload.php'; // Incluir el autocargador de Composer
} else {
    require_once '/var/www/ispexperts/vendor/autoload.php'; // Incluir el autocargador alternativo
}
use Rmunate\Calendario\Holidays\Colombia\CO_2024;
use Rmunate\Calendario\Holidays\Colombia\CO_2025;
use Rmunate\Calendario\Holidays\Colombia\CO_2026;
use Rmunate\Calendario\Holidays\Colombia\CO_2027;
use Rmunate\Calendario\Holidays\Colombia\CO_2028;
use Rmunate\Calendario\Holidays\Colombia\CO_2029;
use Rmunate\Calendario\Holidays\Colombia\CO_2030;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    print "Failed to connect to MySQL: " . $mysqli->connect_error;
}
mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");
$currentMonth = date('n');
$currentYear = date('Y'); 
$currentDay = date('j');
$currentHour = date('h:i A'); // h para hora en formato 12 horas, i para minutos, A para AM/PM
$convertdate = date("d-m-Y", strtotime($today));
$user="aws";

////////////////////////////////////////////////
$sqlSelect = "SELECT * FROM `generate_task` WHERE  `year`=$currentYear AND `month`=$currentMonth ";
$result = $mysqli->query($sqlSelect);

// Verificar si la consulta devolvió filas
if ($result->num_rows > 0) {
    echo "Ya se genero la tarea para este mes";
    exit;
} else {

    $sqlSelect = "SELECT * FROM `afiliados` WHERE  `eliminar`=0 AND `activo`=1 ";
    $result = $mysqli->query($sqlSelect);
    $error=false;
    while($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $sqlUpdate = "UPDATE `afiliados` SET `shutoff_order` = 'pending' WHERE id = $id";
        if ($mysqli->query($sqlUpdate) !== TRUE) {
            echo "Error al actualizar el registro con ID: $id - " . $mysqli->error . "<br>";
            $error=true;
        }else{
            echo "Todas las actuallizaciones se han realizado correctamente.";
        }
    }
    if(!$error){
        $sqlinsert="insert into redesagi_facturacion.generate_task (id,year,month,day,hour,status) values (null,$currentYear,$currentMonth,$currentDay,'$currentHour','generated')";
            if(!$result2x = $mysqli->query($sqlinsert)){						
                echo "Error al insertar el registro con ID: $id - " . $mysqli->error . "<br>";
            }else{
                print "\nSuccess\n";}
    }

}
/////////////////////////////////////////////////


?>