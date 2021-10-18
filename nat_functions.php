<?php
//fetchUser.php if($_SERVER['REQUEST_METHOD']==='POST') {...
function addToNatRule($serverIp,$vpnUser,$vpnPassword,$port,$comment,$toAddresses){
    if($mkobj=new Mkt($serverIp,$vpnUser,$vpnPassword)){
        return $mkobj->addNat($port,$comment,$toAddresses);        
    }
}

?>


