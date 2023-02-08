<?php
class Email
{
    private $user;
    private $endPoint;
    public  $success=true;
    public function __construct($endPoint)
    {        
        $this->endPoint=$endPoint;
    }
    private function opts($postdata){
        $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
        );
        return $opts;  
    }
    public function emailToInstalledNewUser($emailArray)
    {   
        $message= false;
        $endPoint = $this->endPoint;
        $postdata = http_build_query(
            $emailArray
        );
        $context = stream_context_create($this->opts($postdata));
        $result = json_decode(file_get_contents($endPoint, false, $context));
        if($result->mailStatus=="success"){
            $message= true;
        }
        
        return $message;
    }
    public function emailToUserNoInstalledYet($emailArray){
        $message= false;
        $endPoint = $this->endPoint;
        $postdata = http_build_query(
            $emailArray
        );
        $context = stream_context_create($this->opts($postdata));
        if($result = json_decode(file_get_contents($endPoint, false, $context))){
            if($result->mailStatus=="success"){
                $message= true;
            }
        }
        return $message; 
    }
    public function emailAfterPayment($emailArray){
        $message= "fail";
        $endPoint = $this->endPoint;
        $postdata = http_build_query(
            $emailArray
        );
        $context = stream_context_create($this->opts($postdata));
        if($result = json_decode(file_get_contents($endPoint, false, $context))){
            if($result->mailStatus=="success"){
                $message= true;
            }
        }
        return $message;            
    }
    public function emailValidate($email){
        $response=false;
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response=true;
        }
        return $response;
    }
} 

//  $obj=new Email("http://localhost:3001/newuser");
//     $response=$obj->emailToInstalledNewUser($emailArray=[
//         "fullName"=> "Jorge Hernandez",
//         "paymentDay"=> "1 al 7",
//         "periodo"=> "Febrero",
//         "valorPlan"=> "50000",
//         "template"=>"d-4bdc152f4ac04ddfbacd49948f570213",
//         "idClient"=>"958",
//         "email"=>"gilbertolassosaritahijamia@gmail.com"
//         ]);
//     print "response:".$response;
//     var_dump($response);//


?>