<?php

Class Ping  extends mysqli
{
    private $ip=0;
    private $idUser;
    private $byte3=0:
    public function __construct(){
    }
    public function update($id,$ip){
        $this->idUser=$id;
        $this->ip=$ip;
        $this->byte3=explode(".",$ip)[2];
        $this->updateip();
        $this->updateGroup();
    }
    private function udateIp(){

    }
    private function udateGroup(){

    }
}

 

?>