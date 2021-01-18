#!/bin/sh
# This is /etc/ppp/ip-up file.
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
if /sbin/ip route add 192.168.7.0/24 via 192.168.42.10 dev ppp0 ; then
        echo "ok 192.168.21.0/24 ppp0"
else
        /sbin/ip route add 192.168.7.0/24 via 192.168.42.10 dev ppp1
fi
if /sbin/ip route add 192.168.16.0/24 via 192.168.42.10 dev ppp0 ; then
        echo "ok 192.168.21.0/24 ppp0"
else
        /sbin/ip route add 192.168.16.0/24 via 192.168.42.10 dev ppp1
fi
if /sbin/ip route add 192.168.17.0/24 via 192.168.42.10 dev ppp0 ; then
        echo "ok 192.168.21.0/24 ppp0"
else
        /sbin/ip route add 192.168.17.0/24 via 192.168.42.10 dev ppp1
fi
if /sbin/ip route add 192.168.20.0/24 via 192.168.42.10 dev ppp0 ; then
        echo "ok 192.168.21.0/24 ppp0"
else
        /sbin/ip route add 192.168.20.0/24 via 192.168.42.10 dev ppp1
fi
if /sbin/ip route add 192.168.21.0/24 via 192.168.42.10 dev ppp0 ; then
        echo "ok 192.168.21.0/24 ppp0"
else
        /sbin/ip route add 192.168.21.0/24 via 192.168.42.10 dev ppp1
fi
if /sbin/ip route add 192.168.26.0/24 via 192.168.42.10 dev ppp0 ; then
        echo "ok 192.168.21.0/24 ppp0"
else 
        /sbin/ip route add 192.168.26.0/24 via 192.168.42.10 dev ppp1
fi
if /sbin/ip route add 192.168.40.0/24 via 192.168.42.10 dev ppp0 ; then
        echo "ok 192.168.21.0/24 ppp0"
else
        /sbin/ip route add 192.168.40.0/24 via 192.168.42.10 dev ppp1
fi
if /sbin/ip route add 192.168.50.0/24 via 192.168.42.10 dev ppp0 ; then
        echo "ok 192.168.21.0/24 ppp0"
else
        /sbin/ip route add 192.168.50.0/24 via 192.168.42.10 dev ppp1
fi
if /sbin/ip route add 192.168.85.0/24 via 192.168.42.10 dev ppp0 ; then
        echo "route to alcaravan"
else
        /sbin/ip route add 192.168.85.0/24 via 192.168.42.10 dev ppp1
fi
if /sbin/ip route add 192.168.65.0/24 via 192.168.42.10 dev ppp0 ; then
        echo "ok 192.168.21.0/24 ppp0"
else
        /sbin/ip route add 192.168.65.0/24 via 192.168.42.10 dev ppp1
fi
if /sbin/ip route add 192.168.30.0/24 via 192.168.42.11 dev ppp0 ; then
        echo "ok 192.168.21.0/24 ppp0"
else
        /sbin/ip route add 192.168.30.0/24 via 192.168.42.11 dev ppp1
fi
if /sbin/ip route add 192.168.60.0/24 via 192.168.42.11 dev ppp0 ; then
        echo "ok 192.168.21.0/24 ppp0"
else
        /sbin/ip route add 192.168.60.0/24 via 192.168.42.11 dev ppp1
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