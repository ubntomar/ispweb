<?php
use PEAR2\Net\RouterOS;
require_once 'PEAR2_Net_RouterOS-1.0.0b6/src/PEAR2/Autoload.php';  


class Mkt
{
    private $ip;
    private $user;
    private $pass;
    private $client;
    public function __construct($ipRouter, $user, $pass)
    {        
        $this->ip = $ipRouter;
        $this->user = $user;
        $this->pass = $pass;
        try{
            $this->client = new RouterOS\Client($ipRouter, $user, $pass);  
        } catch(Exception $e){
            print "error:$e";
            return false;
        }
        return true;
    }

    
    public function remove_ip($ip,$addresList)
    {   
        
        $printRequest = new RouterOS\Request('/ip/firewall/address-list/print');
        $printRequest->setArgument('.proplist', '.id');
        $printRequest->setQuery(RouterOS\Query::where('address', $ip)->andWhere('list', $addresList));
        $id = $this->client->sendSync($printRequest)->getProperty('.id');
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
        $myArray = array();
        foreach ($responses as $response) {
            if ($response->getType() === RouterOS\Response::TYPE_DATA) {                
                $myArray[] = array("ip" => $response->getProperty('address'), "list" => $response->getProperty('list'),"comment"=>$response->getProperty('comment'));
            }
        }  
    return $myArray;
    }
    public function add_address($ip,$listName,$idUser,$nombre="")
    {   
        $comment="$idUser:".$nombre;  
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
    
    
}
?> 