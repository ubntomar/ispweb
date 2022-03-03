<?php 

class PingTime {
    private $ipAddress;
    private $time;

    public function __construct($ipAddress){
        $this->ipAddress=$ipAddress; 
    }

    public function time($c=2){
        $res=null;
        if(filter_var($this->ipAddress, FILTER_VALIDATE_IP))
            if($this->ping($c))  $res= $this->time;
        return $res;
    }

    public function ping($c){
        $response=false;
        exec("ping -c $c " . $this->ipAddress . " | head -n 2 | tail -n 1 | awk '{print $7}'", $ping_time);
        if ($ping_time[0]!=""){
            if(($this->time=explode('=',$ping_time[0])[1])!="")  $response=true;  
            return $response;

        }
    }
    

}

?>  