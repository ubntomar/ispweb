<?php 
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: login/index.php');
		exit;
		}
else    {
        $user=$_SESSION['username'];
        $role=$_SESSION["role"];
		}
include("login/db.php");
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d"); 
echo "
<table id=\"table_statistics\" class=\"display\" cellspacing=\"0\" width=\"100%\">
    <thead>
        <tr>
            <th>Status</th>
            <th>Nombres</th>
            <th>Id</th>
            <th>fecha</th>
            
        </tr>
    </thead>
    <tbody>";
$sql_sent_sms=  "SELECT *, redesagi_facturacion.afiliados.cliente,redesagi_facturacion.afiliados.apellido FROM redesagi_facturacion.sent_messages INNER JOIN redesagi_facturacion.afiliados
                ON redesagi_facturacion.sent_messages.`id_client`=redesagi_facturacion.afiliados.id ";
$result = mysqli_query($mysqli, $sql_sent_sms) or die('error');
while($db_field = mysqli_fetch_assoc($result)){
    echo"    
            <tr>            
                <td>{$db_field['status']}</td>
                <td>{$db_field['cliente']} {$db_field['apellido']} <br><small><cite>\"{$db_field['personalized_content']}\"</cite></small> </td>
                <td>{$db_field['id_client']}</td>
                <td>{$db_field['fecha']}</td>
            </tr>
        ";

}  


echo"         
    </tbody>
    <tfoot>
        <tr>        
            <th>Status</th>
            <th>Nombres</th>
            <th>Id</th>
            <th>fecha</th>
        </tr>
    </tfoot>
</table>
" ;
?>