<?php
$basic  = new \Nexmo\Client\Credentials\Basic('5be89e2a', 'EvHP3RtiIujcwQEw');
$client = new \Nexmo\Client($basic);

$message = $client->message()->send([
    'to' => '573147654655',
    'from' => 'Nexmo',
    'text' => 'Hello from Nexmo'
]);
?>
