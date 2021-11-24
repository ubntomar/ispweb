<?php
class Sms{
    private $key;
    public function __construct($key){
        $this->key=$key;
    }
    public function sendSms($phoneList,$message){
        $key=$this->key;
        $query = http_build_query(array(
            'key' => $key,
            'client' => '1856',
            'phone' => $phoneList,
            'sms' => $message,
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
            $res = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                //echo "cURL Error #:" . $err;
                $response=["status"=>"fail"];
            } else {
                echo $res."\n";
                $arrayDecoded=json_decode($res, true);
                $response=($arrayDecoded['status']==1)? ["status"=>"success"]:["status"=>"fail"];
            } 
        return $response;
    }



}

$key="7569901a3b138f406d2c7acc4704838c7047dbb5600511a41029d";
$smsObject=new Sms($key);
$prefix="57";
$message="Gracias por tu pago del servicio de Internet. Somos Ag Ingeneiria Wist-Guamal Meta";
$phoneList=[ $prefix."3147654655",$prefix."3215450397"];
print "sendSms($phoneList,$message)";
print $smsObject->sendSms($phoneList,$message);


?>