<?php 

class PingTime {
    private $ipAddress;
    private $time;

    public function __construct($ipAddress){
        $this->ipAddress=$ipAddress; 
    }

    public function time($c=3){
        $res=null;
        if(filter_var($this->ipAddress, FILTER_VALIDATE_IP))
            if($this->ping($c))  $res= $this->time;
        return $res;
    }

    public function ping($c){
        exec("ping -c $c " . $this->ipAddress . " | head -n 2 | tail -n 1 | awk '{print $7}'", $ping_time);
        if(($this->time=explode('=',$ping_time[0])[1])!="")  return true;  
    }
    

}

?>  