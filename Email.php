<?php
 
class Email
{
    private $user;
    public  $success=true;
    public function __construct()
    {        
        
    }
    
    public function sendEmailNewUser($newUserTemplateId,$name,$periodoDePago,$valorAPagar,$id)
    {   
        $endPoint = 'http://localhost:3001/newuser';
        $postdata = http_build_query(
            array(
                'name' => $name,
                'id' => $id,
                'template'=>$newUserTemplateId,
                'periodoDePago'=>$periodoDePago,
                'valorAPagar'=>$valorAPagar,
            )
        );
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context = stream_context_create($opts);
        $result = json_decode(file_get_contents($endPoint, false, $context));
        if($result->mailStatus=="success"){
            print "\n Email enviado con Exito";
        }else{
            print "\n Email Fail";
        }
    }
    
    
    
} 

// if($mkobj=new Mkt("192.168.30.163","agingenieria","agwist2017")){
//     print "Connected\n"; 
//     // var_dump($mkobj->arp());       
//    print($mkobj->checkQueue("192.168.71.16"));      
// }
 

?>