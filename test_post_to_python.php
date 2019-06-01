  <?php
  //$telefono=$_POST["telefono"];
  //$msj=$_POST["msj"];
  $ret_code=99;  
  $command = escapeshellcmd("/var/www/html/tt.py '3147654655' 'hola python'   ");
  //$output = shell_exec($command);
  exec($command,$output,$ret_code);
  //print_r($output);
  ////echo $output[0];
  $response=$output[0];
  echo "testing  -".$ret_code."- response";
  ?>
