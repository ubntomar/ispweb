<?php
class VpnUtils
{
    

    public function __construct{
        echo "he creado nuevo objeto!";
    }
    public function getAreaCode($ip){
    $byte3=explode(".",$ip)[2];
    if($byte3){
        if( $byte3=='7' || $byte3=='16' || $byte3=='17' || $byte3=='20' || $byte3=='21' || $byte3=='25' || $byte3=='26' || $byte3=='40' || $byte3=='50' ) return array('server'=>'192.168.21.1','areaCode'=>'4324');
        if( $byte3=='9'  ) return array('server'=>'192.168.17.62' ,'areaCode'=>'4330');
        if( $byte3=='10' ) return array('server'=>'192.168.30.2'  ,'areaCode'=>'4332');
        if( $byte3=='11' ) return array('server'=>'192.168.30.2'  ,'areaCode'=>'4335');
        if( $byte3=='18' ) return array('server'=>'192.168.30.144','areaCode'=>'4333');
        if( $byte3=='30' ) return array('server'=>'192.168.30.1'  ,'areaCode'=>'4325');
        if( $byte3=='32' ) return array('server'=>'192.168.32.1'  ,'areaCode'=>'4338');
        if( $byte3=='65' ) return array('server'=>'192.168.30.2'  ,'areaCode'=>'4336');
        if( $byte3=='68' ) return array('server'=>'192.168.26.152','areaCode'=>'4328');
        if( $byte3=='71' ) return array('server'=>'192.168.30.163','areaCode'=>'4334');
        if( $byte3=='73' ) return array('server'=>'192.168.17.29' ,'areaCode'=>'4331');
        if( $byte3=='74' ) return array('server'=>'192.168.30.2'  ,'areaCode'=>'4337');
        if( $byte3=='76' ) return array('server'=>'192.168.26.188','areaCode'=>'4329');
        if( $byte3=='79' ) return array('server'=>'192.168.26.186','areaCode'=>'4327');
        if( $byte3=='85' ) return array('server'=>'192.168.17.13' ,'areaCode'=>'4326');
        if( $byte3=='88' ) return array('server'=>'192.168.17.14' ,'areaCode'=>'4400');
    }
    return '';
    }
}






?>