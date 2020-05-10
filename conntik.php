<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Interracción con Mikrotik</title>
</head>
<?php 

?>
<body>
<?php 
///aws ubuntu server:  sbin/ip route add 192.168.xx.0/24 via 192.168.42.10 dev ppp0     paste in /etc/ppp/ip-up

require 'Mkt.php';    
if($mkobj=new Mkt('192.168.21.99','agingenieria','agwist2017')){
    if(false)removeIp($mkobj->remove_ip('192.168.88.100','morosos'));
    if(true)listAllIp($mkobj->list_all());
    if(false)addIP($mkobj->add_address('192.168.88.100','morosos','idUserNumber:xxx'));
}
else print "No fue posible conectar a la Rboard";

function removeIp($remove){
    if($remove==1){
        print "Ip removida con éxito";
    }
    if($remove==2){
        print "Dirección Ip o Lista no existe ";
    }     
}
function listAllIp($responses){
    $json = json_encode($responses, JSON_PRETTY_PRINT);
    print  "\n".$json."\n";    
}
function addIp($response){
    if($response){
        print "Ip agregada con éxito";
    }
    else{
        print "Problemas al ingresar la Ip a la Rboard";
    }
}

?>
</body>
</html>