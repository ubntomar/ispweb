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
