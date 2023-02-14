<?php 
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: ../login/index.php');
		exit;
		}
else    {
        $user=$_SESSION['username'];
        $role=$_SESSION["role"];
		}
include("../login/db.php");  
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d"); 

$sql_sent_sms="SELECT *, afiliados.cliente,afiliados.apellido,afiliados.direccion,afiliados.ciudad,afiliados.telefono,afiliados.pago 
    FROM service_shut_off 
    INNER JOIN afiliados ON (service_shut_off.`id_client`=afiliados.id AND service_shut_off.`fecha`='$today')
    
     ";
$result = mysqli_query($mysqli, $sql_sent_sms) or die('error');
echo "<p>INICIO DE LISTA DE CORTADOS</p>";
while($db_field = mysqli_fetch_assoc($result)){
    $totalCortados+=1;
    echo"    
    <p>            
    <span>{$db_field['cliente']} {$db_field['apellido']} IP:{$db_field['ip']}  {$db_field['direccion']} {$db_field['ciudad']} {$db_field['telefono']} {$db_field['fecha']} </span>
    </p>
    ";
    
}  
echo "<strong>TOTAL CORTADOS HOY: $totalCortados</strong>";
echo "<br>";
echo "<p>FIN DE LISTA DE CORTADOS</p>";

?>