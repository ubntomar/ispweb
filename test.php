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
// ///aws ubuntu server:  sbin/ip route add 192.168.xx.0/24 via 192.168.42.10 dev ppp0     paste in /etc/ppp/ip-up
// require 'Mkt.php';    
// if($mkobj=new Mkt('192.168.21.99','agingenieria','agwist2017')){
//     print "estoy conectado a la rb";
//     if(false)removeIp($mkobj->remove_ip('192.168.88.100','morosos'));
//     if(false)listAllIp($mkobj->list_all());
//     if(false)addIP($mkobj->add_address('192.168.88.100','morosos','idUserNumber:xxx'));
//     if(true)listneighbor($mkobj->neighbor());
// }
// else print "No fue posible conectar a la Rboard";

// function removeIp($remove){
//     if($remove==1){
//         print "Ip removida con éxito";
//     }
//     if($remove==2){
//         print "Dirección Ip o Lista no existe ";
//     }     
// }
// function listAllIp($responses){
//     $json = json_encode($responses, JSON_PRETTY_PRINT);
//     print  "\n".$json."\n";    
// }
// function addIp($response){
//     if($response){
//         print "Ip agregada con éxito";
//     }
//     else{
//         print "Problemas al ingresar la Ip a la Rboard";
//     }
// }
// function listneighbor($responses){
//     print " \nresponse:";
//     $json = json_encode($responses, JSON_PRETTY_PRINT);
//     print  "\n".$json."\n";    
// }

// $date = new DateTime('NOW');
// $date->format('Y/m/d');
// $today=$date->format('Y/m/d');
// $date->modify('-25 day');
// $yesterday=$date->format('Y/m/d');
// print "Today:$today  and Yesterday $yesterday";
$password = 'agwist2017';
$crypted = password_hash($password, PASSWORD_DEFAULT);
print $crypted."\n";


// See the password_hash() example to see where this came from.
$hash = '$2y$07$BCryptRequires22Chrcte/VlQH0piJtjXl.0t1XkA8pw9dMXTpOq';

if (password_verify('agwist2017', $crypted)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}




?>
</body>
</html>