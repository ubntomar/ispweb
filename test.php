<?php


echo "hola mundo";

$sql1="UPDATE `redesagi_facturacion`.`afiliados` SET `cliente` = '$cliente', `apellido` = '$apellido', `telefono` = '$telefono', `direccion` = '$direccion', `ciudad` = '$ciudad', `departamento` = '$departamento', `mail` = '$email', `ip` = '$ip', `router` = '$router', `macRouter` = '$macRouter', `macAntena` = '$macAntena', `inyectorPoe` = '$inyectorPoe', `apuntamiento` = '$apuntamiento', `accesoRemoto` = '$accesoRemoto', `tipoAntena` = '$tipoAntena', `tipoInstalacion` = '$tipoInstalacion'  WHERE `afiliados`.`id` = '$id_cliente'";
if(!$mysqli->query($sql1)){
    $msj= 'error updating afiliados!';
}
$sql2="UPDATE `redesagi_facturacion`.`ticket` SET `telefonoContacto`='$telefonoContacto',`solucion`='$solucion',`recomendaciones`='$recomendaciones',`status`='$status',`precioSoporte`='$precioSoporte',`precioSoporteDescripcion`='$precioSoporteDescripcion',`tecnico`='$tecnico',`tipoSoporte`='$tipoSoporte',`evidenciaFotografica1`='$evidenciaFotografica1',`evidenciaFotografica2`='$evidenciaFotografica2' WHERE `ticket`.`id`='$idTicket' ";
if(!$mysqli->query($sql2)){
    $msj= 'error updating tickets!';
}
?>
https://makitweb.com/how-to-upload-file-with-vue-js-and-php/