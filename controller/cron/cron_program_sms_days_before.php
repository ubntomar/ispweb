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



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    print "Failed to connect to MySQL: " . $mysqli->connect_error;
}
mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");
$currentYear = date('Y'); 
$currentMonth = date('m');//01 02 ..... 12
$currentDay = date('j');
$currentHour = date('h:i A'); // h para hora en formato 12 horas, i para minutos, A para AM/PM
$convertdate = date("d-m-Y", strtotime($today));
$user="aws";
$file = '/tmp/cron/logs.txt';
$user="aws";
$messageToOnurix="Estimado Usario su factura de Internet esta vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite SUSPENSION del servicio.";
$smsApiKey=$smsKey;
/////////////////////////////////////////////////////////////////////////////////////////////////////////
$diaDeCorte = $currentDay;
$fechaCompleta = date('Y-m-') . str_pad($diaDeCorte, 2, '0', STR_PAD_LEFT); // Añade ceros si es necesario
$isHoliday = false;
$anio = date('Y', strtotime($fechaCompleta));

/////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////
    $sqlSelect = "SELECT * FROM `afiliados` WHERE  `eliminar`=0 AND `activo`=1 AND `corte`='$currentDay' ";
    $result = $mysqli->query($sqlSelect);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $telefono = $row["telefono"];
            $diaCorte = $row["corte"];
            $sqlSaldo="SELECT SUM(saldo) AS saldo_total FROM `factura` WHERE `id-afiliado` = $id  AND `cerrado`=0    ";
            $resultSaldo = $mysqli->query($sqlSaldo);
            $rowSaldo = $resultSaldo->fetch_assoc();
            $saldoTotal = $rowSaldo["saldo_total"];
            if($saldoTotal>0){
                $fechaCorte = date("Y-m-d", strtotime("$currentYear-$currentMonth-$diaCorte"));
                $fechaEjecucion = date("Y-m-d", strtotime("$fechaCorte -3 days"));// Calcular la fecha de ejecución restando tres días
                if (date("Y-m-d") == $fechaEjecucion) {
                    echo "Ejecutando tarea...";
                    $sms= sendSms(["idClient"=>"$id","phone"=>"$telefono"],$messageToOnurix,$smsApiKey)["status"];
                    print "\n sms:$sms";
                }else{
                    echo "No es la fecha de ejecución";
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