<?php
require("login/db.php");
$endPoint = 'http://localhost:3001/mail';
$postdata = http_build_query(
    array(
        'name' => 'Matreix Reloada',
        'id' => '1'
    )
);
$opts = array('http' =>
    array(
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);
$context = stream_context_create($opts);
$result = json_decode(file_get_contents($endPoint, false, $context));
if($result->mailStatus=="success"){
    print "\n Email enviado con Exito";
}else{
    print "\n Email Fail";
}
?>