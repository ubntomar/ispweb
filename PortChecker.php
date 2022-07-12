<?php
// $host = 'stackoverflow.com';
$host = '192.168.21.1';
$ports = array(8080);

foreach ($ports as $port)
{
    $connection = @fsockopen($host, $port, $errno, $errstr, 2); 

    if (is_resource($connection))
    {
        echo '<h2>' . $host . ':' . $port . ' ' . '(' . getservbyport($port, 'tcp') . ') is open.</h2>' . "\n";

        fclose($connection);
    }

    else
    {
        echo '<h2>' . $host . ':' . $port . ' is not responding.</h2>' . "\n";
    }
} 

?>