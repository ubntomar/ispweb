<?php
require("/var/www/html/Client.php");
class Sms extends Client{
    
    public function sendSms($data,$message,$key){ 
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
        $curl = curl_init();
        $query = http_build_query(array(
            'key' => $key,
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
            curl_close($curl);
            if ($err) {
                //echo "cURL Error #:" . $err;
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
// require("Sms.php")
require("../brand/Company.php");
require("../../Client.php");
$companyObj=new Company("localhost", "mikrotik", "Agwist1.", "redesagi_facturacion");
$smsObj=new Sms("localhost", "mikrotik", "Agwist1.", "redesagi_facturacion");
$clientObj=new Client("localhost", "mikrotik", "Agwist1.", "redesagi_facturacion");
$idClient=25;
$prefix="+57";
$key="7569901a3b138f406d2c7acc4704838c7047dbb5600511a41029d";
$endPoint="http://localhost:3001/mail";
$email="omar.a.hernandez.d@gmail.com";
$fullName=$clientObj->getClientItem($idClient,$item="cliente")."  ".$clientObj->getClientItem($idClient,$item="apellido");
$companyName=$companyObj->getCompanyItem($idCompany=1,$item="name");
$message="Gracias por tu pago del servicio de Internet. Somos $companyName (Meta)";
$phoneList=$prefix."3147654655,".$prefix."3215450397"; 
$data[] =["idClient"=>$idClient,"phone"=>"2147654655"];
$data[] =["idClient"=>$idClient,"phone"=>"3215450397"]; 
print $smsObj->sendSms($data,$message,$key)["status"];
$emailObj=new Email($endPoint);
if(($emailObj->emailValidate($email)) && $fullName){
    $response=$emailObj->emailAfterPayment($emailArray)($emailArray=[
        "fullName"=> $fullName,
        "template"=>"d-d451365d82394c369b47f375cd19ed6b",
        "idClient"=>$idClient,
        "email"=>$email
        ]);
    print "response:".$response;  
}
 
?>