:local HOST "8.8.8.8"
:local PINGCOUNT "30"
:local INT "sfp1"
:local DELAY "3s"
:local sub1 ([/system identity get name])
:local sub2 ([/system clock get time])
:local sub3 ([/system clock get date])
:local ADMINMAIL1 "YOUR_EMAIL@hotmail.com"
:if ([/ping $HOST interval=1 count=$PINGCOUNT] = 0) do={
:log error "HOST $HOST is not responding to ping request, reseting $INT interface ..."
/interface disable $INT
:log error "$INT is now disabled, waiting $DELAY ..."
:delay $DELAY
/interface enable $INT
:delay $DELAY
:log error "$INT is now enabled"
:log warning "Sending Email alert to $ADMINMAIL1 for Link reset ..."
/tool e-mail send to=$ADMINMAIL1 subject="$INT got reset @ $sub3 $sub2 $sub1" start-tls=yes
} else {
:log warning "HOST $HOST ping is ok, no need to take any action ...";
}

:global neigh [/ip neighbor print count-only ]
:global iden [/system identity get name]
:global txtt ;
:if ($neigh <= 30) do={
    set txtt "Alerta!\tsolo\thay\t$neigh\tUsuarios\ten\tla\tred:$iden";
    /tool fetch url="https://api.telegram.org/bot896101360:AAFy0Gs_8FET6EZBAEeTJ0qxf-GbLOWQ6Sg/sendMessage\?chat_id=-340818870&text=$txtt" keep-result=no
}







#//////////Cerro Orlando///Dia a Dia//////////////////////////////////////////
:global neigh [/ip neighbor print count-only ]
:global txtt;
:global volt [/system healt get voltage];
:global iden [/system identity get name]
set txtt "Voltaje\tactual:$volt\tUbicacion:$iden\t-.Se\treportan\t$neigh\tclientes\ten\teste\tmomento.";
/tool fetch url="https://api.telegram.org/bot896101360:AAFy0Gs_8FET6EZBAEeTJ0qxf-GbLOWQ6Sg/sendMessage\?chat_id=-340818870&text=$txtt" keep-result=no

curl -X POST "https://api.telegram.org/bot896101360:AAFy0Gs_8FET6EZBAEeTJ0qxf-GbLOWQ6Sg/sendMessage" -d "chat_id=-340818870&text=aaaaa"


#Enable net Leo from Paisa Network if Leo Acacias 181.60.60.57 is Down!
:log info "Monitoring if Leo Network is alive:";
:local HOST "192.168.84.1"
:local HOST2 "8.8.8.8"
:if ([/ping $HOST interval=1 count=5] != 0) do={
    :log info "HOST $HOST ping OK ...";
    :if ([/ping $HOST2 interval=1 count=5] != 0) do={
        :log info "HOST $HOST2 ping OK ...";
        /ip address enable [find address="192.168.15.1/24"]
        /ip address enable [find address="192.168.30.1/24"]
        /ip address enable [find address="192.168.60.1/24"]
        /ip address enable [find address="192.168.200.1/24"]
        /ip address enable [find address="192.168.29.2/30"]
        :global txtt ;
        set txtt "Alerta!:\tHa\tsido\tactivado\tel\trespaldo\ta\tla\tred\tLeo\tAcacias";
        /tool fetch url="https://api.telegram.org/bot896101360:AAFy0Gs_8FET6EZBAEeTJ0qxf-GbLOWQ6Sg/sendMessage\?chat_id=-340818870&text=$txtt" keep-result=no
    } else {
        :log warning "HOST $HOST2 ping FALSE ...No es posible reslpaldar la red 30...";
        /ip address disable [find address="192.168.15.1/24"]
        /ip address disable [find address="192.168.30.1/24"]
        /ip address disable [find address="192.168.60.1/24"]
        /ip address disable [find address="192.168.200.1/24"]
        /ip address disable [find address="192.168.29.2/30"]
    }
} else {
    :log warning "HOST $HOST ping FALSE ...no es posible respladar la red 30";
}



#Disable net Leo from Paisa Network if Leo Acacias 181.60.60.57 is Up!
/ip address disable [find address="192.168.15.1/24"]
/ip address disable [find address="192.168.30.1/24"]
/ip address disable [find address="192.168.60.1/24"]
/ip address disable [find address="192.168.200.1/24"]
/ip address disable [find address="192.168.29.2/30"]
:global txtt ;
set txtt "Atencion!:\tHa\tsido\tremovido\tel\trespaldo\ta\tla\tred\tLeo\tAcacias";
/tool fetch url="https://api.telegram.org/bot896101360:AAFy0Gs_8FET6EZBAEeTJ0qxf-GbLOWQ6Sg/sendMessage\?chat_id=-340818870&text=$txtt" keep-result=no




#/system script run disableRedLeoRbAcacias
#Disable net Leo from Casa Leo Network if Leo Retiro 192.168.29.2 is up!
:log info "Monitoring if Leo Network is alive:";
/ip address disable [find address="192.168.15.1/24"]
/ip address disable [find address="192.168.30.1/24"]
/ip address disable [find address="192.168.60.1/24"]
/ip address disable [find address="192.168.200.1/24"]
:global txtt ;
set txtt "Alerta!:\tHa\tsido\tInactivado\tlas\tIps\tde\tla\tred\tLeo\tAcacias\tmientras\ttrabaja\tla\tlinea\tde\trespaldo\ten\tel\tretiro";
/tool fetch url="https://api.telegram.org/bot896101360:AAFy0Gs_8FET6EZBAEeTJ0qxf-GbLOWQ6Sg/sendMessage\?chat_id=-340818870&text=$txtt" keep-result=no


#/system script run enableRedLeoRbAcacias
#Enable net Leo from Casa Leo Network if Leo Retiro 192.168.29.2 is Down!
:log info "Monitoring if Leo Network is alive:";
:log warning "HOST $HOST ping FALSE ...no es posible respladar la red 30";
/ip address enable [find address="192.168.15.1/24"]
/ip address enable [find address="192.168.30.1/24"]
/ip address enable [find address="192.168.60.1/24"]
/ip address enable [find address="192.168.200.1/24"]
:global txtt ;
set txtt "Atencion!:\tHa\tsido\tactivado\tlas\tips\tde\t\tla\tred\tLeo\tAcacias\tya\tque\tla\tip\tpublica\tya\tda\trespuesta";
/tool fetch url="https://api.telegram.org/bot896101360:AAFy0Gs_8FET6EZBAEeTJ0qxf-GbLOWQ6Sg/sendMessage\?chat_id=-340818870&text=$txtt" keep-result=no







/system script run disableRedLeo





/tool e-mail
set address=smtp.gmail.com from="Router #2" password=Redacted port=587 \
    start-tls=yes user=address@gmail.com



# Policies needed:  ftp, read, policy, sensitive, test
# Policies NOT needed:  password, reboot, write, sniff, romon
:log info "Starting daily backup";
/system backup save name=RB750-2_Daily
/system package print file RB750-2_Version.txt
:delay 00:00:01
/tool e-mail send file=RB750-2_Daily.backup to="jim@Redacted.com" body="Router #2 daily backup file attached." \
   subject="RB750r2-2  $[/system clock get date] at $[/system clock get time]  Backup"
/ export file RB750-2_Daily
:delay 00:00:10
/tool e-mail send file=RB750-2_Daily.rsc,RB750-2_Version.txt to="jim@Redacted.com" body="Router #2 daily script and version files attached." \
   subject="RB750r2-2  $[/system clock get date] at $[/system clock get time]  Script"
:log info "Daily backup script completed"






# Start Variables Definition
:local routerName [/system identity get name];
:local dateNow [/system clock get date];
:local timeNow [/system clock get time];
:local sendTo "ispexperts.backup@gmail.com";
:local subject "\F0\9F\93\A6 BACKUP: [ $routerName ] [ $dateNow ]  ";
:local body "Backup file attached in this Email\nDate: $dateNow and Time $timeNow ";
# End Variables Definition
# Start Main Script
# & Make Backup and Send Email
export file=$routerName
:delay 3s 
:local fileToUpload "$routerName.rsc";
/tool e-mail send to=$sendTo body=$body subject=$subject file=$fileToUpload
# End Main Script
:log info "Daily backup script completed"



