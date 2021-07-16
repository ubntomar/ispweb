

# jul/14/2021 21:08:26 by RouterOS 6.48.3
# software id = FUUF-UJEG
#
# model = RB750Gr3
# serial number = D5030D2351DB
/ip firewall mangle
add action=accept chain=prerouting dst-address=10.10.11.0/24
add action=accept chain=prerouting dst-address=181.51.57.0/24 in-interface=LAN
add action=accept chain=prerouting disabled=yes dst-address=192.168.0.0/24 in-interface=LAN
add action=mark-connection chain=prerouting connection-mark=no-mark in-interface=ISP1 new-connection-mark=ISP1_conn
add action=mark-connection chain=prerouting connection-mark=no-mark in-interface=ISP2 new-connection-mark=ISP2_conn
add action=mark-connection chain=prerouting connection-mark=no-mark dst-address-type=!local in-interface=LAN \
    new-connection-mark=ISP1_conn per-connection-classifier=both-addresses:2/0
add action=mark-connection chain=prerouting connection-mark=no-mark dst-address-type=!local in-interface=LAN \
    new-connection-mark=ISP2_conn per-connection-classifier=both-addresses:2/1
add action=mark-routing chain=prerouting connection-mark=ISP1_conn in-interface=LAN new-routing-mark=to_ISP1
add action=mark-routing chain=prerouting connection-mark=ISP2_conn in-interface=LAN new-routing-mark=to_ISP2
add action=mark-routing chain=output connection-mark=ISP1_conn new-routing-mark=to_ISP1
add action=mark-routing chain=output connection-mark=ISP2_conn new-routing-mark=to_ISP2
