<?php 
	require_once '/home/ubuntu/vendor/autoload.php'; 
	use Twilio\Rest\Client; 
	$sid    = "AC6dffc6d75f3fe13e5ab0cfe1f6180b57"; 
	$token  = "b6bcf5d638adfc032d2ab7f4ed35baf3"; 
	$twilio = new Client($sid, $token); 
	
	if (isset($_POST["telefono"]) && !empty($_POST["telefono"]) &&  is_numeric($_POST["telefono"])) {
	    $telefono = $_POST["telefono"];
		$msj = $_POST["msj"];
		try{		
		$message = $twilio->messages->create("+57".$telefono."", // to 
		                           array( 
		                               "from" => "+18508055304",       
		                               "body" => $msj 
		                           ) 
		                  ); 
		echo "ok";
		} catch (Twilio\Exceptions\RestException $e) {
			$status = 'invalid';
			echo "no";
	        
	    }

	} else { 
		echo "fo"; 
	    exit;
	}

	


?>


