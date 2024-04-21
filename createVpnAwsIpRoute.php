<?php
include("login/db.php");
// require 'Mkt.php';
// require 'vpnConfig.php';
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
$timestamp=time();
$validInitParams=false;
$fileContent="";
if ($val) {
       // print var_dump($val)."\n";   //php createVpnAwsIpRoute.php.php  -p:id-empresa -p:1
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
                print "\nError!, example for correct use: php createVpnAwsIpRoute.php.php  -p:\"id-empresa\" -p:\"1\" \n\n";// php createVpnAwsIpRoute.php  -p:id-empresa -p:1
        }
}else{
        system('clear');
        print "\n  \n  \t\t\t\t\tError!, example for correct use: php createVpnAwsIpRoute.php.php  -p:\"id-empresa\" -p:\"1\" \n\n
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
                
$sql="SELECT `id`,`ip`,`target` FROM `redesagi_facturacion`.`aws-vpn-client` WHERE `id-empresa`=$idParam ";//aca se crean las reglas route en el server
if($result=$mysqli->query($sql)){
        while($distinctIdAwsVpnClient=$result->fetch_assoc()){
                $awsId=$distinctIdAwsVpnClient['id'];
                $awsIp=$distinctIdAwsVpnClient['ip'];
                $awsTarget=$distinctIdAwsVpnClient['target'];
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


//code to generate files to copy and paste on different router boards by diferents steps
$partContentAllSteps=[];
$mainArrayVpnIds=[];
$sql="SELECT DISTINCT(`id-aws-vpn-client`) FROM `static_route_steps` WHERE 1";
if($rta=$mysqli->query($sql)){
        while($distinctIdAwsVpnClient=$rta->fetch_assoc()){
                $IdAwsVpnClient=$distinctIdAwsVpnClient["id-aws-vpn-client"];
                $sql="SELECT * FROM `redesagi_facturacion`.`static_route_steps` WHERE `id-aws-vpn-client`=$IdAwsVpnClient ORDER BY `step` ASC ";
                if($sqlObj=$mysqli->query($sql)){
                        // $row_cnt = $sqlObj->num_rows;
                        while($theRowFromstatic_route_steps=$sqlObj->fetch_assoc()){
                                $step=$theRowFromstatic_route_steps["step"];
                                $localServerip=$theRowFromstatic_route_steps["local-server-ip"];
                                $destiantionAddress=$theRowFromstatic_route_steps["dst-ip-address"];
                                $gateway=$theRowFromstatic_route_steps["gateway"];
                                if($step!=0){
                                        $partContentAllSteps[]=["step"=>"$step","content"=>"
                                        add comment=\"By Isp-Experts $today local-$localServerip \"  distance=1 dst-address=$destiantionAddress gateway=$gateway
                                        "];
                                }
                        }
                        $sqlObj->free();
                }
                $mainArrayVpnIds[]=["$IdAwsVpnClient"=>$partContentAllSteps];
                $partContentAllSteps=[];
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
                print "\n\n   \t\t\t\t\t  El archivo ./ip-up ,  ha sido generado. Ahora debes reiniciar el servidor Vpn L2tp: sudo /etc/init.d/xl2tpd stop \n\n";
        }
//end aws l2tp rules


//var_dump($mainArrayVpnIds);
$i=0;
$mainFile=[];
$contentToSave="";
$k=0;
foreach($mainArrayVpnIds as $VpnId){
        $k++;
        $fileName=$k."AwsStaticRoute";
        foreach($VpnId as $item){
                foreach($item as $element) {
                        $stepArray[]=$element["step"];
                        $contentArray[]=$element["content"];
                        //print "\n      {$element["step"]}   :::  {$element["content"]} \n";
                }
                $stepGroups= array_count_values($stepArray);//Counts the occurrences of each distinct value in an array
                $start=0;
                $endPart=0;
                foreach($stepGroups as $key=>$stepQuantityoccurrences){
                        $end=$stepQuantityoccurrences+$endPart;
                        for($i=$start;$i<$end;$i++){
                            //print $contentArray[$i];
                            $contentToSave.=$contentArray[$i];
                        }
                        //print $contentToSave;    
                        print "$fileName.'Step'.$key.'.src'";
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
                                
                                //I want create diffrenet files depending of gateway value `step`='1'
                                if($key==1){

                                        $sqlGw="SELECT DISTINCT(`gateway`),`local-server-ip` FROM `static_route_steps` WHERE `step`=1 ";
                                        if($rtaGw=$mysqli->query($sqlGw)){
                                                while($distinctGw=$rtaGw->fetch_assoc()){
                                                        $gateway=$distinctGw["gateway"];
                                                        $localIP=$distinctGw["local-server-ip"];
                                                        print "\n dentro de DISTINCT gateway step1 Aplicando grep al archivo: $fileToSave con step $key";
                                                        exec("grep $gateway $fileToSave >> STEP_1_IN_RB_$localIP#TO_GW_$gateway#$convertdate.src  ");
                                                        exec("chgrp www-data  STEP_1_IN_RB_$localIP#TO_GW_$gateway#$convertdate.src ");
                                                        print "\n  STEP_1_IN_RB_$localIP#TO_GW_$gateway#$convertdate.src  : Creado!\n\n";
                                                }
                                        }
                                }
                                //End creating files //

                                //I want create diffrenet files depending of gateway value `step`='2'
                                if($key==2){
                                        $sqlLocalGw="SELECT DISTINCT(`local-server-ip`) FROM `static_route_steps` WHERE `step`=2 ";
                                        if($rtaLocalGw=$mysqli->query($sqlLocalGw)){
                                                while($distinctLocalGw=$rtaLocalGw->fetch_assoc()){
                                                        $localGatewayTarget=$distinctLocalGw["local-server-ip"];
                                                        print "\n dentro de DISTINCT gateway step2 Aplicando grep al archivo: $fileToSave con step $key";
                                                        exec("grep local-$localGatewayTarget $fileToSave >> STEP_2_IN_RB_$localGatewayTarget#$convertdate.src  ");
                                                        exec("chgrp www-data STEP_2_IN_RB_$localGatewayTarget#$convertdate.src ");
                                                        print "\n  STEP_2_IN_RB_$localGatewayTarget#$convertdate.src  : Creado!\n\n";
                                                }
                                        }
                                }
                                //End creating files
                        }
                        //fin bloque crear archivo
                        $start+=$stepQuantityoccurrences;
                        $endPart+=$stepQuantityoccurrences;
                        $contentToSave="";
                }

                                
        }
        $stepArray=[];
        $contentArray=[];
        $contentToSave="";

// break;
}
?>