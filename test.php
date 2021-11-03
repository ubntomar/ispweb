<?php
require("login/db.php");
$postdata = http_build_query(
    array(
        'name' => 'Juan',
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
$result = file_get_contents($endPoint, false, $context);
$object=(json_decode($result));
print $object->message."\n";

?>