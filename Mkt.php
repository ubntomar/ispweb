<?php
use PEAR2\Net\RouterOS;
require_once(__DIR__.'/vendor/autoload.php'); 

//require_once '/home/ubuntu/vendor/autoload.php'; //ponia problemas si lo ponia desde html! ...
// ok=>   /usr/local/bin/composer require pear2/net_transmitter:1.0.0b1 pear2/cache_shm pear2/net_routeros:dev-develop@dev
class Mkt
{
    private $ip;
    private $user;
    private $pass;
    private $client;
    public  $success=true; 
    public $error;  
    public function __construct($ipRouter, $user, $pass)
    {        
        $this->ip = $ipRouter;
        $this->user = $user;
        $this->pass = $pass;
        $response=true;
        try{
            $this->client = new RouterOS\Client($ipRouter, $user, $pass);  
        } catch(Exception $e){
            // echo(json_encode(array('error' => 'timeout Mkt.php new')));
            //print "error:$e";
            $response=false;
            $this->error=$e;
            $this->success=false;
        }
        return $response;
    }

    
    public function remove_ip($ip,$addresList)
    {   
        
        $printRequest = new RouterOS\Request('/ip/firewall/address-list/print');
        $printRequest->setArgument('.proplist', '.id');
        $printRequest->setQuery(RouterOS\Query::where('address', $ip)->andWhere('list', $addresList));
        //var_dump($printRequest);
        try{
            $id = $this->client->sendSync($printRequest)->getProperty('.id');
        } catch(Exception $e){
            print "\n \t\t\t $e \n";
        }
        if ($id==NULL)
            return 2;
        else{
            
            $setRequest = new RouterOS\Request('/ip/firewall/address-list/remove');
            $setRequest->setArgument('numbers', $id);
            $this->client->sendSync($setRequest);
        }
    return 1;
                
    }
    public function list_all() 
    {         
        $responses = $this->client->sendSync(new RouterOS\Request('/ip/firewall/address-list/print'));         
        //$myArray = array();
        foreach ($responses as $response) {
            try{
            if ($response->getType() === RouterOS\Response::TYPE_DATA) {
                    $myArray[] = array("ip"=>$response->getProperty('address'),"list"=>$response->getProperty('list'),"comment"=>iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $response->getProperty('comment')));
                }
            }
            catch(Exception $e){
                echo "Error  en ip firewall list";
            }
        } 
    return $myArray;
    }
    public function verifyList($list,$ip) 
    {         
        $responses = $this->client->sendSync(new RouterOS\Request('/ip/firewall/address-list/print'));         
        $res=false;
        foreach ($responses as $response) {
            try{
            if ($response->getType() === RouterOS\Response::TYPE_DATA) {
                if($ip==$response->getProperty('address')){
                    if($list==$response->getProperty('list')){
                        // print "\ncliente si apareceen la lista!!\n";
                        $res=true;
                    }
                }
                }
            }
            catch(Exception $e){
                $res=false;
            }
        } 
    return $res;
    }
    public function add_address($ip,$listName,$idUser,$nombre="",$apellido="",$direccion="",$fecha="")
    {   
        $comment="$idUser:".$nombre.":".$apellido.":".$direccion.":".$ip.":".$fecha;  
        $printRequest = new RouterOS\Request('/ip/firewall/address-list/print');
        $printRequest->setArgument('.proplist', '.id');
        $printRequest->setQuery(RouterOS\Query::where('address', $ip)->andWhere('list', $listName));
        $id = $this->client->sendSync($printRequest)->getProperty('.id');
        if ($id==NULL){
            $addRequest = new RouterOS\Request('/ip/firewall/address-list/add'); 
            $addRequest->setArgument('address', $ip);
            $addRequest->setArgument('list', $listName);
            $addRequest->setArgument('comment', $comment); 
            if ($this->client->sendSync($addRequest)->getType() !== RouterOS\Response::TYPE_FINAL) {
                return 2;
            }
            return 1;
        }            
        else{
            return 3;           
        }   
        
    }
    public function neighbor()
    {        
        $responses = $this->client->sendSync(new RouterOS\Request('/ip/neighbor/print'));         
        $myArray = array();
        foreach ($responses as $response) {
            if ($response->getType() === RouterOS\Response::TYPE_DATA) {                
                $myArray[] = array("identity" => $response->getProperty('IDENTITY'));
            }
        }  
    return $myArray;
    }
    public function addScheduler($script='/log info Hello World'){
        $util = new RouterOS\Util(
            $client = new RouterOS\Client($this->ip, $this->user, $this->pass)
        );
        $util->setMenu('/system scheduler')->add(
            array(
                'name' => 'DailyBackup',
                'start-date' => 'Jul/10/1978',
                'start-time' => '08:00:00',
                'interval' => '1d',
                'on-event' => RouterOS\Script::prepare(
                    $script
                )
            )
        );
        return true;   
    }
    public function addEmail(){
        $code="/tool e-mail set address=173.194.213.108 from=ispexperts.backup@gmail.com password=-Agwist2017 port=587 start-tls=yes user=ispexperts.backup@gmail.com";
        $response = $this->client->sendSync(new RouterOS\Request($code));
        return $response;    
    }
    public function addNat($port,$comment,$toAddresses,$check=false){
        $response=2;
        $printRequest = new RouterOS\Request('/ip/firewall/nat/print');
        $printRequest->setQuery(RouterOS\Query::where('to-ports', "$port"));
        $id = $this->client->sendSync($printRequest)->getProperty('.id');
        if ($id==NULL && !$check){
            $addRequest = new RouterOS\Request('/ip/firewall/nat/add'); 
            $addRequest->setArgument('action', 'dst-nat');
            $addRequest->setArgument('chain', 'dstnat');
            $addRequest->setArgument('dst-port', $port); 
            $addRequest->setArgument('protocol', 'tcp'); 
            $addRequest->setArgument('to-addresses', $toAddresses); 
            $addRequest->setArgument('to-ports', $port);
            $addRequest->setArgument('comment', $comment); 
            if ($this->client->sendSync($addRequest)->getType() !== RouterOS\Response::TYPE_FINAL) {
                $response= 2; //fail
            }
            $response= 1;//success
        }            
        if($id!=NULL){ 
            $response= 3;//"dst-nat is already added!"; 
        }   
        return $response;
    }
    public function checkQueue($ipAddresses){
        $printRequest = new RouterOS\Request('/queue/simple/print');
        $printRequest->setQuery(RouterOS\Query::where('target', "$ipAddresses"."/32")); 
        $id = $this->client->sendSync($printRequest)->getProperty('.id');
        if ($id==NULL){
            //Queue dont exist!
        }            
        if($id!=NULL){ 
            $response= 3;//"Queue is already added!"; 
        }   
        return $response;
    }
    public function ipRoute($dstAddresses){
        $printRequest = new RouterOS\Request('/ip/route/print');
        $printRequest->setQuery(RouterOS\Query::where('dst-address', "$dstAddresses")); 
        $id = $this->client->sendSync($printRequest)->getProperty('.id');
        if ($id==NULL){
            $response=NULL;
            //ip route dont exist!
        }            
        if($id!=NULL){ 
            $response= 3;//"ip route is already added!"; 
        }   
        return $response;
    }
    public function checkSignal(){
        $printRequest = new RouterOS\Request('/interface/wireless/registration-table/print');
        $signal = $this->client->sendSync($printRequest)->getProperty('signal-strength');
        $response=0;           
        if($signal!=NULL){ 
            $response= $signal;
        }   
        return $response;
    }
    public function arp(){
        $responses = $this->client->sendSync(new RouterOS\Request('/ip/arp/print'));         
        $myArray = array();
        foreach ($responses as $response) {
            if ($response->getType() === RouterOS\Response::TYPE_DATA) {                
                $myArray[]= $response->getProperty('address');
            }
        }  
        return $myArray;
    }
    
} 
// if($mkobj=new Mkt("192.168.26.1","...","...")){
//     echo "=new Mkt(\"192.168.26.1\",\"agingenieria\",\"agwist2017\")";
//     if($mkobj->success){
//         var_dump($mkobj->arp());      
//     } else{
//         echo "problemas"; 
//     }      
// } 

?>