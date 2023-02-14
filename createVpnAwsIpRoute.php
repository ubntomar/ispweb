<?php
include("login/db.php");
require 'Mkt.php';
require 'vpnConfig.php';
$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}	
mysqli_set_charset($mysqli,"utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");   
$convertdate= date("d-m-Y" , strtotime($today));
$hourMin = date('H:i');
$val = getopt("p:");
$validInitParams=false;
$fileContent="";
if ($val) {
       // print var_dump($val)."\n";
        if( $idx=$val['p'][1] ){
                $idParam=explode(":",$idx)[1];
                $sql="SELECT `id`,`nombre` FROM `redesagi_facturacion`.`empresa` WHERE `id`=$idParam ";
                if( $result=$mysqli->query($sql) ){
                        $nombreEmpresa=$result->fetch_assoc()['nombre'];
                        system('clear');
                        print "\n\n   \t\t\t\t\t  Bienvenido $nombreEmpresa, No olvides  reiniciar el L2tp server  y esperar q levanten los pppx! :sudo /etc/init.d/xl2tpd stop\n\n";
                        $validInitParams=true;
                        $result->free();
                }
        }else{
                system('clear');
                print "\nError!, example for correct use: php vpnAwsIpRoute.php  -p:\"id-empresa\" -p:\"1\" \n\n";// php vpnAwsIpRoute.php  -p:id-empresa -p:1
        }
}else{
        system('clear');
        print "\n  \n  \t\t\t\t\tError!, example for correct use: php vpnAwsIpRoute.php  -p:\"id-empresa\" -p:\"100\" \n\n
        \n  ";
}
if($validInitParams){
$fileContent.="#!/bin/sh
# This is  file was created at $convertdate. 
# This script is run by the pppd after the link is established.
# It uses run-parts to run scripts in /etc/ppp/ip-up.d, so to add routes,
# set IP address, run the mailq etc. you should create script(s) there.
#
# Be aware that other packages may include /etc/ppp/ip-up.d scripts (named
# after that package), so choose local script names with that in mind.
#
# This script is called with the following arguments:
#    Arg  Name                          Example
#    $1   Interface name                ppp0
#    $2   The tty                       ttyS1
#    $3   The link speed                38400
#    $4   Local IP number               12.34.56.78
#    $5   Peer  IP number               12.34.56.99
#    $6   Optional ``ipparam'' value    foo";
$fileContent.="
# The  environment is cleared before executing this script
# so the path must be reset
PATH=/usr/local/sbin:/usr/sbin:/sbin:/usr/local/bin:/usr/bin:/bin
export PATH

# These variables are for the use of the scripts run by run-parts
";
$fileContent.="
PPP_IFACE=\"$1\"
PPP_TTY=\"$2\"
PPP_SPEED=\"$3\"
PPP_LOCAL=\"$4\"
PPP_REMOTE=\"$5\"
PPP_IPPARAM=\"$6\"
export PPP_IFACE PPP_TTY PPP_SPEED PPP_LOCAL PPP_REMOTE PPP_IPPARAM
";
$fileContent.="
# as an additional convenience, \$PPP_TTYNAME is set to the tty name,
# stripped of /dev/ (if present) for easier matching.
PPP_TTYNAME=`/usr/bin/basename \"$2\"`
export PPP_TTYNAME 
";
//block begin
                
$sql="SELECT `id`,`ip`,`target` FROM `redesagi_facturacion`.`aws-vpn-client` WHERE `id-empresa`=$idParam ";
if($result=$mysqli->query($sql)){
        while($row=$result->fetch_assoc()){
                $awsId=$row['id'];
                $awsIp=$row['ip'];
                $awsTarget=$row['target'];
                $sql="SELECT `ip-segment`,`aws-vpn-interface-name-main`,`aws-vpn-interface-name-secondary`,`aws-vpn-interface-name-tertiary` FROM `redesagi_facturacion`.`items_repeater_subnet_group` WHERE `id-aws-vpn-client`=$awsId ";
                if($res=$mysqli->query($sql)){
                        while($rw=$res->fetch_assoc()){
                                $ipSegment=$rw['ip-segment'];
                                $awsNameMain=$rw['aws-vpn-interface-name-main'];
                                $awsNameSecondary=$rw['aws-vpn-interface-name-secondary'];
                                $awsNameTertiary=$rw['aws-vpn-interface-name-tertiary'];
                                //      item begin
$fileContent.="
if /sbin/ip route add 192.168.$ipSegment.0/24 via $awsIp dev $awsNameMain
then
echo \"ok 192.168.$ipSegment.0/24 $awsNameMain\"
elif /sbin/ip route add 192.168.$ipSegment.0/24 via $awsIp dev $awsNameSecondary
then
echo \"ok 192.168.$ipSegment.0/24 $awsNameSecondary\"
elif /sbin/ip route add 192.168.$ipSegment.0/24 via $awsIp dev $awsNameTertiary
then
echo \"ok 192.168.$ipSegment.0/24 $awsNameTertiary\"
else
echo \"None of the condition met\"
fi
";
                        }
                }
        }
        $result->free();       
}
$fileContent.="
echo \"Voy a agregar la ruta:\"
echo \"Ruta agregada\"
echo \"la ruta del log es: /var/log/ppp-ipupdown.log\"
# If /var/log/ppp-ipupdown.log exists use it for logging.
if [ -e /var/log/ppp-ipupdown.log ]; then
exec > /var/log/ppp-ipupdown.log 2>&1
echo $0 $@
echo
fi

# This script can be used to override the .d files supplied by other packages.
if [ -x /etc/ppp/ip-up.local ]; then
exec /etc/ppp/ip-up.local \"$@\"
fi

run-parts /etc/ppp/ip-up.d \
--arg=\"$1\" --arg=\"$2\" --arg=\"$3\" --arg=\"$4\" --arg=\"$5\" --arg=\"$6\"

# if pon was called with the \"quick\" argument, stop pppd
if [ -e /var/run/ppp-quick ]; then
rm /var/run/ppp-quick
wait
kill \$PPPD_PID
fi
#this script was created at $convertdate
";
}
$partContent=[];
$mainArray=[];
$sql="SELECT DISTINCT(`id-aws-vpn-client`) FROM `static_route_steps` WHERE 1";
if($rta=$mysqli->query($sql)){
        while($row=$rta->fetch_assoc()){
                $idAws=$row["id-aws-vpn-client"];
                $sql="SELECT * FROM `redesagi_facturacion`.`static_route_steps` WHERE `id-aws-vpn-client`=$idAws ORDER BY `step` ASC ";
                if($sqlObj=$mysqli->query($sql)){
                        $row_cnt = $sqlObj->num_rows;
                        while($theRow=$sqlObj->fetch_assoc()){
                                $step=$theRow["step"];
                                $localServerip=$theRow["local-server-ip"];
                                $destiantionAddress=$theRow["dst-ip-address"];
                                $gateway=$theRow["gateway"];
                                if($step!=0){
                                        $partContent[]=["step"=>"$step","content"=>"
                                        add comment=\"By Isp-Experts $today\"  distance=1 dst-address=$destiantionAddress gateway=$gateway
                                        "];
                                }
                        }
                        $sqlObj->free();
                }
                $mainArray[]=["$idAws"=>$partContent];
                $partContent=[];
        }

}

//begin aws l2tp rules
$filename = "ip-up";
$file_handler = fopen($filename, 'w');
if(!$file_handler)
die("The file can't be open for writing<br />");
else
{
        $data = $fileContent;
        fwrite($file_handler, $data);
        fclose($file_handler);
        print "\n\n   \t\t\t\t\t  El archivo ip-up, ya ha sido generado. Ahora debes reiniciar el servidor Vpn L2tp!\n\n";
}
//end aws l2tp rules


//var_dump($mainArray);
$i=0;
$mainFile=[];
$contentToSave="";
$k=0;
foreach($mainArray as $value){
        $k++;
        $fileName=$k."AwsStaticRoute";
        foreach($value as $item){
                foreach($item as $element) {
                        $stepArray[]=$element["step"];
                        $contentArray[]=$element["content"];
                        //print "\n      {$element["step"]}   :::  {$element["content"]} \n";
                }
                $stepGroups= array_count_values($stepArray);
                $start=0;
                $endPart=0;
                foreach($stepGroups as $key=>$part){
                        $end=$part+$endPart;
                        for($i=$start;$i<$end;$i++){
                            //print $contentArray[$i];
                            $contentToSave.=$contentArray[$i];
                        }
                        //print $contentToSave;    
                        //print "$fileName.'Step'.$key.'.src'";
                        //inicio bloque crear archivo
                        $fileToSave=$fileName."Step".$key.".src";
                        $file_handler = fopen($fileToSave, 'w');
                        if(!$file_handler)
                                die("The file can't be open for writing<br />");
                        else
                        {
                                $data = $contentToSave;
                                fwrite($file_handler, $data);
                                fclose($file_handler);
                                print "\n  $fileToSave  : Ahora debes reiniciar el servidor Vpn L2tp!\n\n";
                        }
                        //fin bloque crear archivo
                        $start+=$part;
                        $endPart+=$part;
                        $contentToSave="";
                }

                                
        }
        $stepArray=[];
        $contentArray=[];
        $contentToSave="";

// break;
}
?>