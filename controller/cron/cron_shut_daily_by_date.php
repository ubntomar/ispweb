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
$file = '/tmp/cron/logs.txt';
$user="aws";
$messageToOnurix="Estimado Usario su factura de Internet esta vencida favor acercarce a la oficina Cra 9#13-11 Guamal Meta y evite SUSPENSION del servicio.";
$smsApiKey=$smsKey;
/////////////////////////////////////////////////////////////////////////////////////////////////////////
$diaDeCorte = $currentDay;
$fechaCompleta = date('Y-m-') . str_pad($diaDeCorte, 2, '0', STR_PAD_LEFT); // Añade ceros si es necesario
$isHoliday = false;
$anio = date('Y', strtotime($fechaCompleta));
// Obtener todos los días festivos según el año
if ($anio == 2024) {
    $holidays = CO_2024::all();
} elseif ($anio == 2025) {
    $holidays = CO_2025::all();
} elseif ($anio == 2026) {
    $holidays = CO_2026::all();
} elseif ($anio == 2027) {
    $holidays = CO_2027::all();
} elseif ($anio == 2028) {
    $holidays = CO_2028::all();
} elseif ($anio == 2029) {
    $holidays = CO_2029::all();
} elseif ($anio == 2030) {
    $holidays = CO_2030::all();
} else {
    // Manejar años fuera del rango
}
// Comprobar si la fecha formada es un día festivo
foreach ($holidays as $holiday) {
    if ($holiday['full_date'] === $fechaCompleta) {
        $isHoliday = true;
        break;
    }
}
if ($isHoliday) {
    echo "\n\nLa fecha $fechaCompleta SI es un día festivo.\n";
    $aplazar = true;
} else {
    echo "\n\nLa fecha $fechaCompleta NO es un día festivo VAMOS A CORTAR USUARIOS CORTE $diaDeCorte.\n\n\n\n";
    $aplazar = false;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////
if(!$aplazar){
    $sqlSelect = "SELECT * FROM `afiliados` WHERE  `eliminar`=0 AND `activo`=1 AND `corte`='$currentDay' ";
    $result = $mysqli->query($sqlSelect);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $telefono = $row["telefono"];
            $corteInDb = $row["corte"];
            $sqlSaldo="SELECT SUM(saldo) AS saldo_total FROM `factura` WHERE `id-afiliado` = $id  AND `cerrado`=0    ";
            $resultSaldo = $mysqli->query($sqlSaldo);
            $rowSaldo = $resultSaldo->fetch_assoc();
            $saldoTotal = $rowSaldo["saldo_total"];
            if($saldoTotal==0){
                $sqlUpdate = "UPDATE `afiliados` SET `shutoff_order` = 'completed' WHERE id = $id";
                if ($mysqli->query($sqlUpdate) !== TRUE) {
                    echo "Error al actualizar el registro con ID: $id - " . $mysqli->error . "<br>";
                }
            }else{
                $sqlSelectSuspender = "SELECT * FROM `afiliados` WHERE  `id`=$id AND `suspender`=0 AND (`corte`!='1' OR `corte`!='15') ";
                $resultSuspender = $mysqli->query($sqlSelectSuspender);
                if ($resultSuspender->num_rows > 0) {
                    if($currentDay<=$corteInDb){
                        $sqlText="`corte`='$currentDay'";
                        print"\nPor corte=$currentDay\n";
                    }else{
                        $sqlText="`shutoff_order`='pending'";
                        print"\nPor shutoff_order= pending\n";
                    }
                    $sqlSelectShutOffOrder = "SELECT * FROM `afiliados` WHERE  `id`=$id AND  $sqlText ";
                    $resultShutOffOrder = $mysqli->query($sqlSelectShutOffOrder);
                    if ($resultShutOffOrder->num_rows > 0) {
                        $sqlUpdate = "UPDATE `afiliados` SET `shutoff_order` = 'completed', `suspender` = 1,`shutoffpending`= 1,`reconectPending`= 0,`reconected-date`= NULL,`suspenderFecha`= '$today'  WHERE id = $id";
                        if ($mysqli->query($sqlUpdate) !== TRUE) {
                            echo "Error al actualizar el registro con ID: $id - " . $mysqli->error . "<br>";
                        }else{
                            $sms= sendSms(["idClient"=>"$id","phone"=>"$telefono"],$messageToOnurix,$smsApiKey)["status"];
                            print "\n sms:$sms";
                        }
                    }
                }
            }
    
        }
    
    } 
}

//////////////////////////////////////////////////////////////////////////////////
function sendSms($data,$message,$apiKey){ 
    //var_dump($data);
    print "\n{$data["phone"]}\n"; 
    // print "\n Messge:$message\n"; 
    // print "\n apiKey:$apiKey\n"; 
    if (validarTelefono($data["phone"])) {
        $curl = curl_init();
        $query = http_build_query(array(
            'key' => $apiKey,
            'client' => '1856',
            'phone' => $data["phone"],
            'sms' => $message,
            'country-code' => 'CO'
            ));
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.onurix.com/api/v1/send-sms",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            /*CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,*/
            CURLOPT_POST  => 1, 
            CURLOPT_POSTFIELDS => $query,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
            ));
            $res = curl_exec($curl);
            $err = curl_error($curl);
            // var_dump($res);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
                $response=["status"=>"fail"];
            } else {
                $arrayDecoded=json_decode($res, true);
                $response=($arrayDecoded['status']==1)? ["status"=>"success"]:["status"=>"fail"];
            } 
        return $response;

    } else {
        echo "El número de teléfono no es válido.";
    }
    
}





function validarTelefono($numero) {
    // Eliminar espacios en blanco del número
    $numeroLimpio = str_replace(' ', '', $numero);
    // Definir el patrón de la expresión regular para verificar que comience con 3 y tenga 10 dígitos en total
    $patron = '/^3\d{9}$/';
    // Usar preg_match para verificar si el número cumple con el patrón
    if (preg_match($patron, $numeroLimpio)) {
        return true; // El número es válido
    } else {
        return false; // El número no es válido
    }
}













?>