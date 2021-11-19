<?php>
//////////////////////////////////////////////////////NO BORRAR SIN ANTES HACER BACKUP////////////////////////////////////////////////////////////////////////////////////////

/interface ethernet
set [ find default-name=ether1 ] comment="***Cll 8 # 35 37 Independencia Casa \
    Paisa 100Mbps/10Mbps-Titular : Omar 181.51.57.226/24.***Se\F1ora Nury- 120\
    Mbps/20Mbps Titular: Yesica 181.51.57.188/24" name=ISP1-Ether1


/interface l2tp-server server
set enabled=yes ipsec-secret=-Agwist2017 use-ipsec=yes

/ip address
add address=181.51.57.226/24  interface=ISP1-Ether1 network=181.51.57.0 comment="Ip publica de do\F1a Nury"
add address=181.58.212.170/24 interface=ISP1-Ether1 network=181.58.212.0

/ip firewall mangle
add action=accept chain=prerouting comment=A dst-address=10.10.11.0/24
add action=accept chain=prerouting comment=A dst-address=192.168.42.0/24
add action=accept chain=prerouting comment=B dst-address=181.58.212.0/24 in-interface=BRIDGE-LAN
add action=accept chain=prerouting comment=C dst-address=181.51.57.0/24  in-interface=BRIDGE-LAN
add action=mark-connection chain=prerouting comment=D connection-mark=no-mark dst-address=181.51.57.226  in-interface=ISP1-Ether1 new-connection-mark=ISP1_conn passthrough=yes
add action=mark-connection chain=prerouting comment=E connection-mark=no-mark dst-address=181.58.212.170 in-interface=ISP1-Ether1 new-connection-mark=ISP2_conn passthrough=yes
add action=mark-connection chain=prerouting comment=G connection-mark=no-mark dst-address-type=!local in-interface=BRIDGE-LAN new-connection-mark=ISP1_conn passthrough=yes per-connection-classifier=both-addresses:2/0
add action=mark-connection chain=prerouting comment=H connection-mark=no-mark dst-address-type=!local in-interface=BRIDGE-LAN new-connection-mark=ISP2_conn passthrough=yes per-connection-classifier=both-addresses:2/1
add action=mark-routing chain=prerouting comment=J connection-mark=ISP1_conn in-interface=BRIDGE-LAN new-routing-mark=to_ISP1 passthrough=yes
add action=mark-routing chain=prerouting comment=K connection-mark=ISP2_conn in-interface=BRIDGE-LAN new-routing-mark=to_ISP2 passthrough=yes
add action=mark-routing chain=output comment=M connection-mark=ISP1_conn new-routing-mark=to_ISP1 passthrough=yes
add action=mark-routing chain=output comment=N connection-mark=ISP2_conn new-routing-mark=to_ISP2 passthrough=yes

/ip route
add check-gateway=ping distance=1 gateway=181.51.57.1 routing-mark=to_ISP1
add check-gateway=ping distance=1 gateway=181.58.212.1 routing-mark=to_ISP2
add distance=2 gateway=181.51.57.1

add distance=1 dst-address=192.168.16.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.17.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.20.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.21.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.26.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.40.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.50.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.85.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.88.0/24 gateway=192.168.84.8

/////////////////////////////////////////////////////////////////////////////RED MARIA ACACIAS////////////////////////////////////////////////////////////////////////////////

#181.60.60.121/24
/interface ethernet
set [ find default-name=ether1 ] name=ISP1-Ether1


/ip address
add address=181.59.60.27/24 interface=ISP1-Ether1 network=181.59.60.0

/ip firewall mangle
add action=accept chain=prerouting comment=A dst-address=10.10.11.0/24
add action=accept chain=prerouting comment=A dst-address=192.168.42.0/24
add action=accept chain=prerouting comment=B dst-address=181.60.60.0/24 in-interface=BRIDGE-LAN
add action=accept chain=prerouting comment=C dst-address=181.59.60.0/24  in-interface=BRIDGE-LAN
add action=mark-connection chain=prerouting comment=D connection-mark=no-mark dst-address=181.60.60.121  in-interface=ISP1-Ether1 new-connection-mark=ISP1_conn passthrough=yes
add action=mark-connection chain=prerouting comment=E connection-mark=no-mark dst-address=181.59.60.27   in-interface=ISP1-Ether1 new-connection-mark=ISP2_conn passthrough=yes
add action=mark-connection chain=prerouting comment=G connection-mark=no-mark dst-address-type=!local in-interface=BRIDGE-LAN new-connection-mark=ISP1_conn passthrough=yes per-connection-classifier=both-addresses:2/0
add action=mark-connection chain=prerouting comment=H connection-mark=no-mark dst-address-type=!local in-interface=BRIDGE-LAN new-connection-mark=ISP2_conn passthrough=yes per-connection-classifier=both-addresses:2/1
add action=mark-routing chain=prerouting comment=J connection-mark=ISP1_conn in-interface=BRIDGE-LAN new-routing-mark=to_ISP1 passthrough=yes
add action=mark-routing chain=prerouting comment=K connection-mark=ISP2_conn in-interface=BRIDGE-LAN new-routing-mark=to_ISP2 passthrough=yes
add action=mark-routing chain=output comment=M connection-mark=ISP1_conn new-routing-mark=to_ISP1 passthrough=yes
add action=mark-routing chain=output comment=N connection-mark=ISP2_conn new-routing-mark=to_ISP2 passthrough=yes

/ip route
add check-gateway=ping distance=1 gateway=181.60.60.1 routing-mark=to_ISP1
add check-gateway=ping distance=1 gateway=181.59.60.1 routing-mark=to_ISP2
add distance=2 gateway=181.60.60.1