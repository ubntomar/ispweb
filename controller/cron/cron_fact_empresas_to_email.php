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
if (file_exists("/var/www/ispexperts/Email.php")) {
    require("/var/www/ispexperts/Email.php");
} else {
    require("/home/omar/docker-work-area/go/ispweb/Email.php");
}


if (file_exists('/home/omar/docker-work-area/go/ispweb/vendor/autoload.php')) {
    require_once '/home/omar/docker-work-area/go/ispweb/vendor/autoload.php'; // Incluir el autocargador de Composer
} else {
    require_once '/var/www/ispexperts/vendor/autoload.php'; // Incluir el autocargador alternativo
}

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



$sqlSelect = "SELECT * FROM `afiliados` WHERE  `eliminar`=0 AND `activo`=1 AND `id-formato-factura`=1 ";
    $result = $mysqli->query($sqlSelect);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $cliente = $row["cliente"]."  ".$row["apellido"];
            $direccion = $row["direccion"];
            $telefono = $row["telefono"];
            $nit = $row["cedula"];
            $ciudad = $row["ciudad"];
            $departamento = $row["departamento"];
            $valorPlan = $row["pago"];
            if($row["mail"] != "" && $row["mail"] != null){
                $mailes = explode(",", $row["mail"]);
                if(count($mailes) != 0){
                    foreach($mailes as $mail){
                        print "\t\t\t mail:$mail\n";
                        sendPdfToEmail($id,$cliente,$mail);
                    }
                }
            }else{
                print "\t\t\t mail: No tiene mail\n";
                
            }
            

        }
    }


function sendPdfToEmail($id,$cliente, $mail){
    $year = date('Y');
    $mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
    $month = $mes[date('n')];
    $filename = "factura_{$month}_{$year}_{$id}_".str_replace(" ", "-", $cliente).".pdf";
    if (file_exists("/var/www/ispexperts/controller/cron/")) {
        $directory = "/var/www/ispexperts/controller/cron/facturas/$year/$month/";
        echo "directory(true):$directory\n";
    } else {
        $directory = "/home/omar/docker-work-area/go/ispweb/controller/cron/facturas/$year/$month/";
        echo "directory(false):$directory\n";
    }
    $pathToAttachment=$directory.$filename;
    $urlContainerized="http://node-mail:3001";
    if (verificarTituloEnRespuesta($urlContainerized)){
        $url=$urlContainerized."/pdfFactura";
    }else{
        $url="http://localhost:3001/pdfFactura";
    }
    if(file_exists($pathToAttachment)){

        $obj=new Email($url);
        echo "\t\t....Enviando correo a $mail\t\t\t";
        $response=$obj->emailToCompany($emailArray=[
            "cliente"=> $cliente,
            "filename"=> $filename,
            "email"=> $mail,
            "pathToAttachment"=> $pathToAttachment,
            ]);
        print "\n Email API response:".$response;
        // var_dump($response);//
    }else{
        print "No existe el archivo: ,por lo tanto no se envia el correo\n";
    }
 
}



function verificarTituloEnRespuesta($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Tiempo de espera de 10 segundos
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json"
    ));
    $response = curl_exec($ch);
    if ($response === false) {
        //echo "Error curl  $url en la solicitud cURL: " . curl_error($ch)."\n";
        curl_close($ch);
        return false; // Devuelve false si hay un error en la solicitud
    }
    $data = json_decode($response, true);
    curl_close($ch);
    return isset($data[0]['title']);
}


    




