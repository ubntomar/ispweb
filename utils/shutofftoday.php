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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>DevXm - Cortados</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="../bower_components/alertify/css/alertify.min.css" />
    <link rel="stylesheet" href="../bower_components/alertify/css/themes/default.min.css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css">
    <link rel="stylesheet" href="../css/fontello.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/dataTables.checkboxes.css">
    
</head>

<body>
    <?php
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
    
</body>


</html>









