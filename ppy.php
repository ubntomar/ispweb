<?php 

$text='Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta';
$command = escapeshellcmd("/var/www/html/tt.py '3147654655' '$text'   ");
$output = shell_exec($command);
echo $output;


?>
