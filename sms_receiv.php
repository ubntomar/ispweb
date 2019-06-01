<?php 
use Twilio\Rest\Client; 
// Update the path below to your autoload.php, 
// see https://getcomposer.org/doc/01-basic-usage.md 
require_once 'Twilio/autoload.php'; 
//require_once '/var/www/html/vendor/autoload.php';  
use Twilio\Rest\Client; 
 
$sid    = "AC6dffc6d75f3fe13e5ab0cfe1f6180b57"; 
$token  = "b6bcf5d638adfc032d2ab7f4ed35baf3"; 
$twilio = new Client($sid, $token); 
 
$message = $twilio->messages 
                  ->create("+573147654655", // to 
                           array( 
                               "from" => "+18508055304",       
                               "body" => "Your message" 
                           ) 
                  ); 
 
print($message->sid);
