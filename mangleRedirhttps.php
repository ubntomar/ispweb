<?php
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
        $urlSource=$_SERVER["PHP_SELF"];
        ///ispdev/MktikMangle.php
        $page=explode("/",$urlSource);
        $_SESSION['urlsource']= $page[2];    
		header('Location: login/index.php');
	exit;
		}
else    {
        $user=$_SESSION['username'];
        
		}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Php Mangle Api</title>
</head>
<body>
<?php
echo "<h1>Código para Mangle Facebook Youtube y Resto</h1>";
echo "^..+\.(facebook.com|facebook.net|fbcdn.com|fbsbx.com|fbcdn.net|fb.com|tfbnw.net).*$";
echo "<p>";
echo "^..+\.(youtube.com|googlevideo.com|akamaihd.net).*$";
$c=0;
$lanInterfaceName="bridge";//put somethink like ether1 |  lan | ...
for($x=235;$x<=235;$x++)
        {   $c+=1;
            $text="";
            $ipPc=$x;            
            $AddressList="servipetroleos-$ipPc"."";
            $Text_comment="1.$ipPc"."";
            $markConnectionDownloadText="_$ipPc"."_dw_conn";
            $markConnectionUploadText="_$ipPc"."_up_conn";
            $markPacketDownloadText="_$ipPc"."_dw_pkg";
            $markPacketUploadText="_$ipPc"."_up_pkg";
            $markConnectionRestoDownloadText="resto_dw_$ipPc"."_conn";
            $markPacketRestoDownloadText="resto_dw_$ipPc"."_pkg";
            $markConnectionRestoUploadText="resto_up_$ipPc"."_conn";
            $markPacketRestoUploadText="resto_up_$ipPc"."_pkg";
            
            echo"\n<p>/ip firewall mangle";
            echo "        
            \n<p>add action=mark-connection chain=forward comment=\
            \"Marcado de Paquetes de Youtube $Text_comment\" dst-address-list=$AddressList \
            in-interface=wlan1 layer7-protocol=youtube log=yes log-prefix=\
            youtube$markConnectionDownloadText new-connection-mark=youtube$markConnectionDownloadText passthrough=yes
            \n<p>add action=mark-packet chain=forward connection-mark=youtube$markConnectionDownloadText \
            new-packet-mark=youtube$markPacketDownloadText passthrough=no
            \n<p>add action=mark-connection chain=forward in-interface=$lanInterfaceName layer7-protocol=\
            youtube new-connection-mark=youtube$markConnectionUploadText passthrough=yes \
            src-address-list=$AddressList
            \n<p>add action=mark-packet chain=forward connection-mark=youtube$markConnectionUploadText \
            new-packet-mark=youtube$markPacketUploadText passthrough=no
            \n<p>add action=mark-connection chain=forward comment=\
            \"Marcado de paquetes de facebook $Text_comment\" dst-address-list=$AddressList \
            in-interface=wlan1 layer7-protocol=facebook log=yes log-prefix=\
            facebook$markConnectionDownloadText new-connection-mark=facebook$markConnectionDownloadText passthrough=\
            yes
            \n<p>add action=mark-packet chain=forward connection-mark=facebook$markConnectionDownloadText \
            new-packet-mark=facebook$markPacketDownloadText passthrough=no
            \n<p>add action=mark-connection chain=forward in-interface=$lanInterfaceName layer7-protocol=\
            facebook new-connection-mark=facebook$markConnectionUploadText passthrough=yes \
            src-address-list=$AddressList
            \n<p>add action=mark-packet chain=forward connection-mark=facebook$markConnectionUploadText \
            new-packet-mark=facebook$markPacketUploadText passthrough=no
            \n<p>add action=mark-connection chain=forward comment=\"resto download $Text_comment\
            \" 
            dst-address-list=$AddressList in-interface=wlan1 new-connection-mark=\
            $markConnectionRestoDownloadText passthrough=yes
            \n<p>add action=mark-packet chain=forward connection-mark=$markConnectionRestoDownloadText \
            new-packet-mark=$markPacketRestoDownloadText passthrough=no
            \n<p>add action=mark-connection chain=forward in-interface=$lanInterfaceName new-connection-mark=\
            $markConnectionRestoUploadText passthrough=yes src-address-list=$AddressList
            \n<p>add action=mark-packet chain=forward connection-mark=$markConnectionRestoUploadText \
            new-packet-mark=$markPacketRestoUploadText passthrough=no  
            ";
            
            // A continuación un plan de 8 megas para 20 usuarios en servipetróleos.  20 Usuarios/8 Megas =2.5 User x Mega ó 0.4M por Usuario Garantízado.
            
        }
echo "
add action=mark-connection chain=prerouting comment=4DSL connection-mark=\
no-mark in-interface=pppoe-out1 new-connection-mark=FromDSL passthrough=no

add action=mark-routing chain=prerouting comment=\"Mark DSL Only Users\" \
new-routing-mark=DSL passthrough=no src-address-list=UseDSL

add action=mark-routing chain=prerouting comment=4DSL connection-mark=FromDSL \
in-interface=ether2-master new-routing-mark=DSL passthrough=no

add action=mark-routing chain=output comment=4DSL connection-mark=FromDSL \
new-routing-mark=DSL passthrough=no

add action=mark-routing chain=output dst-address-list=cloud new-routing-mark=\
DSL passthrough=no";




?>
</body>
</html>