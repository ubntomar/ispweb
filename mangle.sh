add action=mark-connection chain=forward comment=\
    "Marcado de Paquetes de Youtube 1.250" dst-address-list=servipetroleos-250 \
    in-interface=wlan1 layer7-protocol=youtube new-connection-mark=\
    youtube_250_dw_conn passthrough=yes
add action=mark-packet chain=forward connection-mark=youtube_250_dw_conn \
    new-packet-mark=youtube_250_dw_pkg passthrough=no
add action=mark-connection chain=forward in-interface=LAN layer7-protocol=\
    youtube new-connection-mark=youtube_250_up_conn passthrough=yes \
    src-address-list=servipetroleos-250
add action=mark-packet chain=forward connection-mark=youtube_250_up_conn \
    new-packet-mark=youtube_250_up_pkg passthrough=no
add action=mark-connection chain=forward comment=\
    "Marcado de paquetes de facebook 1.250" dst-address-list=servipetroleos-250 \
    in-interface=wlan1 layer7-protocol=facebook new-connection-mark=\
    facebook_250_dw_conn passthrough=yes
add action=mark-packet chain=forward connection-mark=facebook_dw_conn \
    new-packet-mark=facebook_250_dw_pkg passthrough=no
add action=mark-connection chain=forward in-interface=LAN layer7-protocol=\
    facebook new-connection-mark=facebook_250_up_conn passthrough=yes \
    src-address-list=servipetroleos-250
add action=mark-packet chain=forward connection-mark=facebook_250_up_conn \
    new-packet-mark=facebook_250_up_pkg passthrough=no
add action=mark-connection chain=forward comment="resto download 1.250" \
    dst-address-list=servipetroleos-250 in-interface=wlan1 new-connection-mark=\
    resto_dw_250_conn passthrough=yes
add action=mark-packet chain=forward connection-mark=resto_dw_250_conn \
    new-packet-mark=resto_dw_250 passthrough=no
