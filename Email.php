<?php
class Email
{
    private $user;
    private $endpoint;
    public  $success=true;
    public function __construct($endpoint)
    {        
        $this->endpoint=$endPoint;
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
        $message= "Ups, Fail!";
        $endPoint = $this->endpoint;
        $postdata = http_build_query(
            $emailArray
        );
        $context = stream_context_create($this->opts($postdata));
        if($result = json_decode(file_get_contents($endPoint, false, $context))){
            if($result->mailStatus=="success"){
                $message= "Enviado con Exito";
            }
        }
        return $message;
    }
    public function emailToUserNoInstalledYet($emailArray){
        $message= "Ups, Fail!";
        $endPoint = $this->endpoint;
        $postdata = http_build_query(
            $emailArray
        );
        $context = stream_context_create($this->opts($postdata));
        if($result = json_decode(file_get_contents($endPoint, false, $context))){
            if($result->mailStatus=="success"){
                $message= "Enviado con Exito";
            }
        }
        return $message;
    }
} 

// $obj=new Email();

 

?>