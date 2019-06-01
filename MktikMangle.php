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
echo "<h1>Código para Queue Tree</h1>";
echo "^..+\.(facebook.com|facebook.net|fbcdn.com|fbsbx.com|fbcdn.net|fb.com|tfbnw.net).*$";
echo "<p>";
echo "^..+\.(youtube.com|googlevideo.com|akamaihd.net).*$";
$c=0;
$lanInterfaceName="LAN";//put somethink like ether1 |  lan | ...
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
            
            $grupoBajadaLimitAt="8M";
            $grupoBajadaMaxLimit="8M";
            $grupoSubidaLimitAt="4M";
            $grupoSubidaMaxLimit="4M";
            $grupoDescripcion="Grupo $grupoBajadaLimitAt/$grupoSubidaLimitAt ";
            $ip="192.168.".$Text_comment;

            $youtubeLimitAtBajada="50k";
            $facebookLimitAtBajada="50k";
            $restoLimitAtBajada="300k";
            $youtubeMaxLimitBajada="1M";
            $facebookMaxLimitBajada="1M";
            $restoMaxLimitBajada="6M";

            $youtubeLimitAtSubida="50k";
            $facebookLimitAtSubida="50k";
            $restoLimitAtSubida="100k";
            $youtubeMaxLimitSubida="1M";
            $facebookMaxLimitSubida="1M";
            $restoMaxLimitSubida="2M";
             //S   
            $restodwName="$Text_comment".".resto_$ipPc"."_dw";
            $restoUpName="$Text_comment".".resto_$ipPc"."_up";
            $priorityDownload="priority=5";
            $queuePcq="queue=\"$grupoDescripcion\"";
            if($c==1){
                echo"\n<p>/queue type
                \n<p>add kind=pcq name=\"$grupoDescripcion\" pcq-classifier=dst-address \
                    pcq-dst-address6-mask=64 pcq-rate=0 pcq-src-address6-mask=64";
            }
            echo"\n<p>/queue tree";
            if($c==1){
                echo"\n<p>add comment=\"$grupoDescripcion\" $queuePcq limit-at=$grupoBajadaLimitAt max-limit=$grupoBajadaMaxLimit name=\"$grupoDescripcion Dw\" parent=global";
            }
            
            $text="comment=\"$ip\"";    
            
            echo"
            \n<p>add limit-at=$facebookLimitAtBajada $text max-limit=$facebookMaxLimitBajada name=\"$Text_comment facebook dw\" packet-mark=facebook$markPacketDownloadText parent=\"$grupoDescripcion Dw\"
            \n<p>add limit-at=$youtubeLimitAtBajada  max-limit=$youtubeMaxLimitBajada name=\"$Text_comment youtube dw\" packet-mark=youtube$markPacketDownloadText parent=\"$grupoDescripcion Dw\"
            \n<p>add limit-at=$restoLimitAtBajada $priorityDownload max-limit=$restoMaxLimitBajada name=\"$restodwName\" packet-mark=$markPacketRestoDownloadText parent=\"$grupoDescripcion Dw\" ";

            if($c==1)
                echo"\n<p>add comment=\"$grupoDescripcion\" limit-at=$grupoSubidaLimitAt max-limit=$grupoSubidaMaxLimit name=\"$grupoDescripcion Up\" parent=global";

            echo"
            \n<p>add limit-at=$facebookLimitAtSubida $text max-limit=$facebookMaxLimitSubida name=\"$Text_comment facebook up\" packet-mark=facebook$markPacketUploadText parent=\"$grupoDescripcion Up\"
            \n<p>add limit-at=$youtubeLimitAtSubida  max-limit=$youtubeMaxLimitSubida name=\"$Text_comment youtube up\" packet-mark=youtube$markPacketUploadText parent=\"$grupoDescripcion Up\"
            \n<p>add limit-at=$restoLimitAtSubida max-limit=$restoMaxLimitSubida name=\"$restoUpName\" packet-mark=$markPacketRestoUploadText parent=\"$grupoDescripcion Up\"
            ";

            echo"\n<p>/ip firewall address-list add list=$AddressList address=$ip/32";
        }
echo "<h1>Fin de Código para Queue Tree</h1>";
?>
</body>
</html>