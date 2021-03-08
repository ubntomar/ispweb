<?php
$curl = curl_init();
$query = http_build_query(array(
 'key' => '7569901a3b138f406d2c7acc4704838c7047dbb5600511a41029d',
 'client' => '1856',
 'phone' => '573147654655,573215450397',
 'sms' => 'Testingl correct y ademas con prefijo 57',
 'country-code' => 'CO'
));
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.onurix.com/api/v1/send-sms",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  /*CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,*/
  CURLOPT_POST  => 1,
  CURLOPT_POSTFIELDS => $query,
  CURLOPT_HTTPHEADER => array(
    "content-type: application/x-www-form-urlencoded"
  ),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response."\n";
  $arrayDecoded=json_decode($response, true);
    echo var_dump($arrayDecoded);
    echo ($arrayDecoded['status']==1)? "Mensaje enviado!":"Error en envio de mensaje";
} 
?>