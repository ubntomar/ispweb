<?php

class Ubiquiti{
    private $connection;
    public $status;
    public function __construct($ip,$user,$password){
        $this->status=false;
        if($this->connection = ssh2_connect($ip, 22)){
            //var_dump($this->connection);
            if (ssh2_auth_password($this->connection, $user, $password)) {
                $this->status=true;
                //  print "Authentication Successful!\n";
            }
        }
    }
    
    public function getUbiquitiSignal(){
        $signal=0;
        $cmd="mca-status | grep signal";
        if($output = ssh2_exec($this->connection, $cmd)) {
            stream_set_blocking($output, true);
            $signal=explode("=",stream_get_contents($output))[1];
        }
        return $signal;
    }
}

// require("../../PingTime.php");
// require("../../Client.php");

// $ip=["192.168.70.74","192.168.17.74","192.168.70.75","192.168.16.231"];

// $UbiquitiCredentials[]=["user"=>"admin","password"=>"ubnt123x"];
// $UbiquitiCredentials[]=["user"=>"ubnt","password"=>"ubnt"];
// $UbiquitiCredentials[]=["user"=>"ubnt","password"=>"ubnt1234"];
// $UbiquitiCredentials[]=["user"=>"ubnt","password"=>"agwist2017"];

// foreach ($ip as $ipValue) {
//     $pingObj=new PingTime($ipValue);
//     if($time=$pingObj->time()){
//         foreach ($UbiquitiCredentials as $row) {
//             $obj=new Ubiquiti($ipValue,$row["user"],$row["password"]);
//             $signal=0;
//             if($obj->status){
//                 $signal=$obj->getUbiquitiSignal();
//                 print "$time {$row["user"]} {$row["password"]} $ipValue: ok SIGNAL: $signal \n";
//                 break;
//             }else{
//                 print "$time {$row["user"]} {$row["password"]} $ipValue: falsoo \n";
//             }
//         }
//     }
// }


?>