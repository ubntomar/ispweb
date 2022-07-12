if /sbin/ip route add 192.168.65.0/24 via 192.168.42.10 dev ppp0 ; then
        echo "ok 192.168.21.0/24 ppp0"
else
        /sbin/ip route add 192.168.65.0/24 via 192.168.42.10 dev ppp1
fi
