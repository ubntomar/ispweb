<?php
$mail = "Hola te voy a comprar el producto del q me hablaste";
//Titulo
$titulo = "Confirmacion de compra";
//cabecera
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
//dirección del remitente 
$headers .= "From: Geeky Theory < omar.a.hernandez.d@gmail.com >\r\n";
//Enviamos el mensaje a omar.a.hernandez.d@gmail.com 
$bool = mail("omar.a.hernandez.d@gmail.com",$titulo,$mail,$headers);
if($bool){
    echo "Mensaje enviado";
}else{
    echo "Mensaje no enviado";
}
?> 