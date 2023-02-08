<?php
$connection = ssh2_connect('192.168.16.231', 22);
$cmd="mca-status | grep signal";
if (ssh2_auth_password($connection, 'ubnt', 'agwist2017')) {
  echo "Authentication Successful!\n";
  if($output = ssh2_exec($connection, $cmd)) {
    stream_set_blocking($output, true);
    echo stream_get_contents($output);
}
} else {
  print('Authentication Failed...');
}
?>

