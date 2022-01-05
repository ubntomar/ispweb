<?php
class Sms extends Client { 
    
    public function sendSms($data,$message,$apiKey){ 
        $phoneListString="";
        foreach ($data as $key => $value) {
            $idClient=$value["idClient"];
            $phone=$value["phone"];
            if ($this->validate_phone_number($phone)){
                $phoneListString.="$phone,";
            }else{
                parent::updateClient($idClient,$param="telefono",$value="",$operator="=");
            }
        }
        $phoneListFormated=substr_replace($phoneListString ,"",-1);
        // print "\n telefonos:$phoneListFormated\n"; 
        $curl = curl_init();
        $query = http_build_query(array(
            'key' => $apiKey,
            'client' => '1856',
            'phone' => $phoneListFormated,
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
            // var_dump($res);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
                $response=["status"=>"fail"];
            } else {
                $arrayDecoded=json_decode($res, true);
                $response=($arrayDecoded['status']==1)? ["status"=>"success"]:["status"=>"fail"];
            } 
        return $response;
    }
    private function validate_phone_number($phone){
        $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
        $phone_to_check = str_replace("-", "", $filtered_phone_number);
        if (strlen($phone_to_check)== 10 && $phone_to_check[0]=="3" ) {
            $response= true;
        } else {
            $response= false;
        }
        return $response;
    }

}




?>