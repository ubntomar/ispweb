Welcome to Ubuntu 20.04.3 LTS (GNU/Linux 5.4.0-91-generic x86_64)

 * Documentation:  https://help.ubuntu.com
 * Management:     https://landscape.canonical.com
 * Support:        https://ubuntu.com/advantage
New release '22.04.3 LTS' available.
Run 'do-release-upgrade' to upgrade to it.


Welcome to Alibaba Cloud Elastic Compute Service !

Last login: Fri Apr 26 07:01:07 2024 from 186.82.63.70
root@ali-cloud:~# cat /var/www/ispexperts/ip-up
#!/bin/sh
# This is  file was created at 20-04-2024. 
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
#    $6   Optional ``ipparam'' value    foo
# The  environment is cleared before executing this script
# so the path must be reset
PATH=/usr/local/sbin:/usr/sbin:/sbin:/usr/local/bin:/usr/bin:/bin
export PATH

# These variables are for the use of the scripts run by run-parts

PPP_IFACE="$1"
PPP_TTY="$2"
PPP_SPEED="$3"
PPP_LOCAL="$4"
PPP_REMOTE="$5"
PPP_IPPARAM="$6"
export PPP_IFACE PPP_TTY PPP_SPEED PPP_LOCAL PPP_REMOTE PPP_IPPARAM

# as an additional convenience, $PPP_TTYNAME is set to the tty name,
# stripped of /dev/ (if present) for easier matching.
PPP_TTYNAME=`/usr/bin/basename "$2"`
export PPP_TTYNAME 

if /sbin/ip route add 192.168.7.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.7.0/24 ppp0"
elif /sbin/ip route add 192.168.7.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.7.0/24 ppp1"
elif /sbin/ip route add 192.168.7.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.7.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.16.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.16.0/24 ppp0"
elif /sbin/ip route add 192.168.16.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.16.0/24 ppp1"
elif /sbin/ip route add 192.168.16.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.16.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.17.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.17.0/24 ppp0"
elif /sbin/ip route add 192.168.17.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.17.0/24 ppp1"
elif /sbin/ip route add 192.168.17.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.17.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.20.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.20.0/24 ppp0"
elif /sbin/ip route add 192.168.20.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.20.0/24 ppp1"
elif /sbin/ip route add 192.168.20.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.20.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.21.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.21.0/24 ppp0"
elif /sbin/ip route add 192.168.21.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.21.0/24 ppp1"
elif /sbin/ip route add 192.168.21.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.21.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.25.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.25.0/24 ppp0"
elif /sbin/ip route add 192.168.25.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.25.0/24 ppp1"
elif /sbin/ip route add 192.168.25.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.25.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.26.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.26.0/24 ppp0"
elif /sbin/ip route add 192.168.26.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.26.0/24 ppp1"
elif /sbin/ip route add 192.168.26.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.26.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.40.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.40.0/24 ppp0"
elif /sbin/ip route add 192.168.40.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.40.0/24 ppp1"
elif /sbin/ip route add 192.168.40.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.40.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.50.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.50.0/24 ppp0"
elif /sbin/ip route add 192.168.50.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.50.0/24 ppp1"
elif /sbin/ip route add 192.168.50.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.50.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.30.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.30.0/24 ppp0"
elif /sbin/ip route add 192.168.30.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.30.0/24 ppp1"
elif /sbin/ip route add 192.168.30.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.30.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.60.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.60.0/24 ppp0"
elif /sbin/ip route add 192.168.60.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.60.0/24 ppp1"
elif /sbin/ip route add 192.168.60.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.60.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.79.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.79.0/24 ppp0"
elif /sbin/ip route add 192.168.79.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.79.0/24 ppp1"
elif /sbin/ip route add 192.168.79.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.79.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.73.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.73.0/24 ppp0"
elif /sbin/ip route add 192.168.73.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.73.0/24 ppp1"
elif /sbin/ip route add 192.168.73.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.73.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.101.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.101.0/24 ppp0"
elif /sbin/ip route add 192.168.101.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.101.0/24 ppp1"
elif /sbin/ip route add 192.168.101.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.101.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.71.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.71.0/24 ppp0"
elif /sbin/ip route add 192.168.71.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.71.0/24 ppp1"
elif /sbin/ip route add 192.168.71.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.71.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.66.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.66.0/24 ppp0"
elif /sbin/ip route add 192.168.66.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.66.0/24 ppp1"
elif /sbin/ip route add 192.168.66.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.66.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.78.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.78.0/24 ppp0"
elif /sbin/ip route add 192.168.78.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.78.0/24 ppp1"
elif /sbin/ip route add 192.168.78.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.78.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.6.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.6.0/24 ppp0"
elif /sbin/ip route add 192.168.6.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.6.0/24 ppp1"
elif /sbin/ip route add 192.168.6.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.6.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.103.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.103.0/24 ppp0"
elif /sbin/ip route add 192.168.103.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.103.0/24 ppp1"
elif /sbin/ip route add 192.168.103.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.103.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.104.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.104.0/24 ppp0"
elif /sbin/ip route add 192.168.104.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.104.0/24 ppp1"
elif /sbin/ip route add 192.168.104.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.104.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.105.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.105.0/24 ppp0"
elif /sbin/ip route add 192.168.105.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.105.0/24 ppp1"
elif /sbin/ip route add 192.168.105.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.105.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.107.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.107.0/24 ppp0"
elif /sbin/ip route add 192.168.107.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.107.0/24 ppp1"
elif /sbin/ip route add 192.168.107.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.107.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.108.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.108.0/24 ppp0"
elif /sbin/ip route add 192.168.108.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.108.0/24 ppp1"
elif /sbin/ip route add 192.168.108.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.108.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.200.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.200.0/24 ppp0"
elif /sbin/ip route add 192.168.200.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.200.0/24 ppp1"
elif /sbin/ip route add 192.168.200.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.200.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.111.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.111.0/24 ppp0"
elif /sbin/ip route add 192.168.111.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.111.0/24 ppp1"
elif /sbin/ip route add 192.168.111.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.111.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.112.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.112.0/24 ppp0"
elif /sbin/ip route add 192.168.112.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.112.0/24 ppp1"
elif /sbin/ip route add 192.168.112.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.112.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.62.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.62.0/24 ppp0"
elif /sbin/ip route add 192.168.62.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.62.0/24 ppp1"
elif /sbin/ip route add 192.168.62.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.62.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.109.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.109.0/24 ppp0"
elif /sbin/ip route add 192.168.109.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.109.0/24 ppp1"
elif /sbin/ip route add 192.168.109.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.109.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.113.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.113.0/24 ppp0"
elif /sbin/ip route add 192.168.113.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.113.0/24 ppp1"
elif /sbin/ip route add 192.168.113.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.113.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.114.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.114.0/24 ppp0"
elif /sbin/ip route add 192.168.114.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.114.0/24 ppp1"
elif /sbin/ip route add 192.168.114.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.114.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.115.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.115.0/24 ppp0"
elif /sbin/ip route add 192.168.115.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.115.0/24 ppp1"
elif /sbin/ip route add 192.168.115.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.115.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.120.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.120.0/24 ppp0"
elif /sbin/ip route add 192.168.120.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.120.0/24 ppp1"
elif /sbin/ip route add 192.168.120.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.120.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.123.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.123.0/24 ppp0"
elif /sbin/ip route add 192.168.123.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.123.0/24 ppp1"
elif /sbin/ip route add 192.168.123.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.123.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.65.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.65.0/24 ppp0"
elif /sbin/ip route add 192.168.65.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.65.0/24 ppp1"
elif /sbin/ip route add 192.168.65.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.65.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.124.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.124.0/24 ppp0"
elif /sbin/ip route add 192.168.124.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.124.0/24 ppp1"
elif /sbin/ip route add 192.168.124.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.124.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.125.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.125.0/24 ppp0"
elif /sbin/ip route add 192.168.125.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.125.0/24 ppp1"
elif /sbin/ip route add 192.168.125.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.125.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.126.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.126.0/24 ppp0"
elif /sbin/ip route add 192.168.126.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.126.0/24 ppp1"
elif /sbin/ip route add 192.168.126.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.126.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.127.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.127.0/24 ppp0"
elif /sbin/ip route add 192.168.127.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.127.0/24 ppp1"
elif /sbin/ip route add 192.168.127.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.127.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.128.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.128.0/24 ppp0"
elif /sbin/ip route add 192.168.128.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.128.0/24 ppp1"
elif /sbin/ip route add 192.168.128.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.128.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.129.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.129.0/24 ppp0"
elif /sbin/ip route add 192.168.129.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.129.0/24 ppp1"
elif /sbin/ip route add 192.168.129.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.129.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.130.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.130.0/24 ppp0"
elif /sbin/ip route add 192.168.130.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.130.0/24 ppp1"
elif /sbin/ip route add 192.168.130.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.130.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.131.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.131.0/24 ppp0"
elif /sbin/ip route add 192.168.131.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.131.0/24 ppp1"
elif /sbin/ip route add 192.168.131.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.131.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.132.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.132.0/24 ppp0"
elif /sbin/ip route add 192.168.132.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.132.0/24 ppp1"
elif /sbin/ip route add 192.168.132.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.132.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.133.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.133.0/24 ppp0"
elif /sbin/ip route add 192.168.133.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.133.0/24 ppp1"
elif /sbin/ip route add 192.168.133.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.133.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.134.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.134.0/24 ppp0"
elif /sbin/ip route add 192.168.134.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.134.0/24 ppp1"
elif /sbin/ip route add 192.168.134.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.134.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.87.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.87.0/24 ppp0"
elif /sbin/ip route add 192.168.87.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.87.0/24 ppp1"
elif /sbin/ip route add 192.168.87.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.87.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.61.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.61.0/24 ppp0"
elif /sbin/ip route add 192.168.61.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.61.0/24 ppp1"
elif /sbin/ip route add 192.168.61.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.61.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.135.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.135.0/24 ppp0"
elif /sbin/ip route add 192.168.135.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.135.0/24 ppp1"
elif /sbin/ip route add 192.168.135.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.135.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.136.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.136.0/24 ppp0"
elif /sbin/ip route add 192.168.136.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.136.0/24 ppp1"
elif /sbin/ip route add 192.168.136.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.136.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.138.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.138.0/24 ppp0"
elif /sbin/ip route add 192.168.138.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.138.0/24 ppp1"
elif /sbin/ip route add 192.168.138.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.138.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.139.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.139.0/24 ppp0"
elif /sbin/ip route add 192.168.139.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.139.0/24 ppp1"
elif /sbin/ip route add 192.168.139.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.139.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.140.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.140.0/24 ppp0"
elif /sbin/ip route add 192.168.140.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.140.0/24 ppp1"
elif /sbin/ip route add 192.168.140.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.140.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.141.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.141.0/24 ppp0"
elif /sbin/ip route add 192.168.141.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.141.0/24 ppp1"
elif /sbin/ip route add 192.168.141.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.141.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.142.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.142.0/24 ppp0"
elif /sbin/ip route add 192.168.142.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.142.0/24 ppp1"
elif /sbin/ip route add 192.168.142.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.142.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.143.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.143.0/24 ppp0"
elif /sbin/ip route add 192.168.143.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.143.0/24 ppp1"
elif /sbin/ip route add 192.168.143.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.143.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.144.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.144.0/24 ppp0"
elif /sbin/ip route add 192.168.144.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.144.0/24 ppp1"
elif /sbin/ip route add 192.168.144.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.144.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.145.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.145.0/24 ppp0"
elif /sbin/ip route add 192.168.145.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.145.0/24 ppp1"
elif /sbin/ip route add 192.168.145.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.145.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.146.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.146.0/24 ppp0"
elif /sbin/ip route add 192.168.146.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.146.0/24 ppp1"
elif /sbin/ip route add 192.168.146.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.146.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.147.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.147.0/24 ppp0"
elif /sbin/ip route add 192.168.147.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.147.0/24 ppp1"
elif /sbin/ip route add 192.168.147.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.147.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.27.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.27.0/24 ppp0"
elif /sbin/ip route add 192.168.27.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.27.0/24 ppp1"
elif /sbin/ip route add 192.168.27.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.27.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.148.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.148.0/24 ppp0"
elif /sbin/ip route add 192.168.148.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.148.0/24 ppp1"
elif /sbin/ip route add 192.168.148.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.148.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.149.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.149.0/24 ppp0"
elif /sbin/ip route add 192.168.149.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.149.0/24 ppp1"
elif /sbin/ip route add 192.168.149.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.149.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.150.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.150.0/24 ppp0"
elif /sbin/ip route add 192.168.150.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.150.0/24 ppp1"
elif /sbin/ip route add 192.168.150.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.150.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.151.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.151.0/24 ppp0"
elif /sbin/ip route add 192.168.151.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.151.0/24 ppp1"
elif /sbin/ip route add 192.168.151.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.151.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.122.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.122.0/24 ppp0"
elif /sbin/ip route add 192.168.122.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.122.0/24 ppp1"
elif /sbin/ip route add 192.168.122.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.122.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.84.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.84.0/24 ppp0"
elif /sbin/ip route add 192.168.84.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.84.0/24 ppp1"
elif /sbin/ip route add 192.168.84.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.84.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.152.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.152.0/24 ppp0"
elif /sbin/ip route add 192.168.152.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.152.0/24 ppp1"
elif /sbin/ip route add 192.168.152.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.152.0/24 ppp2"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.32.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.32.0/24 ppp2"
elif /sbin/ip route add 192.168.32.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.32.0/24 ppp1"
elif /sbin/ip route add 192.168.32.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.32.0/24 ppp0"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.86.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.86.0/24 ppp2"
elif /sbin/ip route add 192.168.86.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.86.0/24 ppp1"
elif /sbin/ip route add 192.168.86.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.86.0/24 ppp0"
else
echo "None of the condition met"
fi

if /sbin/ip route add 192.168.137.0/24 via 192.168.42.10 dev ppp0
then
echo "ok 192.168.137.0/24 ppp0"
elif /sbin/ip route add 192.168.137.0/24 via 192.168.42.10 dev ppp1
then
echo "ok 192.168.137.0/24 ppp1"
elif /sbin/ip route add 192.168.137.0/24 via 192.168.42.10 dev ppp2
then
echo "ok 192.168.137.0/24 ppp2"
else
echo "None of the condition met"
fi

echo "Voy a agregar la ruta:"
echo "Ruta agregada"
echo "la ruta del log es: /var/log/ppp-ipupdown.log"
# If /var/log/ppp-ipupdown.log exists use it for logging.
if [ -e /var/log/ppp-ipupdown.log ]; then
exec > /var/log/ppp-ipupdown.log 2>&1
echo $0 $@
echo
fi

# This script can be used to override the .d files supplied by other packages.
if [ -x /etc/ppp/ip-up.local ]; then
exec /etc/ppp/ip-up.local "$@"
fi

run-parts /etc/ppp/ip-up.d \
--arg="$1" --arg="$2" --arg="$3" --arg="$4" --arg="$5" --arg="$6"

# if pon was called with the "quick" argument, stop pppd
if [ -e /var/run/ppp-quick ]; then
rm /var/run/ppp-quick
wait
kill $PPPD_PID
fi
