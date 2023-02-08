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
<table id=\"table_ip_shutoff\" class=\"display\" cellspacing=\"0\" width=\"100%\">
    <thead>
        <tr>
            
            <th>Nombres</th>
            
            <th>fecha</th>
            
        </tr>
    </thead>
    <tbody>";
$sql_sent_sms=  "SELECT *, redesagi_facturacion.afiliados.cliente,redesagi_facturacion.afiliados.apellido FROM redesagi_facturacion.service_shut_off INNER JOIN redesagi_facturacion.afiliados
                ON redesagi_facturacion.service_shut_off.`id_client`=redesagi_facturacion.afiliados.id  order by redesagi_facturacion.service_shut_off.`id_client`  ASC LIMIT 2";
$result = mysqli_query($mysqli, $sql_sent_sms) or die('error');
while($db_field = mysqli_fetch_assoc($result)){
    echo"    
            <tr>            
                
                <td>{$db_field['cliente']} {$db_field['apellido']} <br><small><cite class=\" m-1 p-1 border border-success\">{$db_field['ip']}</cite></small> </td>
               
                <td>{$db_field['fecha']}</td> 
            </tr>
        ";

}  


echo"         
    </tbody>
    <tfoot>
        <tr>        
            
            <th>Nombres</th>
           
            <th>fecha</th>
        </tr>
    </tfoot>
</table>
" ;
?>