<?php 

class PingTime {
    private $ipAddress;
    private $time;

    public function __construct($ipAddress){
        $this->ipAddress=$ipAddress;
    }

    public function time(){
        if(filter_var($this->ipAddress, FILTER_VALIDATE_IP))
            if($this->ping())  return $this->time;
            else    return null;    
    }

    public function ping(){
        exec("ping -c 1 " . $this->ipAddress . " | head -n 2 | tail -n 1 | awk '{print $7}'", $ping_time);
        if(($this->time=explode('=',$ping_time[0])[1])!="")  return true;  
    }
    

}

$deviceOne=New PingTime("192.168.21.100");
if($time=$deviceOne->time()) print $time;
else print "error!";


?>  