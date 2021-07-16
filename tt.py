#!/usr/bin/env python
####---- -*- coding: utf-8 -*- 
import smtplib

gmail_user = 'cliente@ispexperts.com@gmail.com'
gmail_password = 'NFGsgQ4awD'

sent_from = gmail_user
to = ['omar.a.hernandez.d@gmail.com', 'omar_alberto_h@yahoo.es']
subject = 'OMG Super Important Message'
body = 'Hey, whats up?\n\n- You'

email_text = """\
From: %s
To: %s
Subject: %s

%s
""" % (sent_from, ", ".join(to), subject, body)

try:
    server = smtplib.SMTP_SSL('smtp.gmail.com', 465)
    server.ehlo()
    server.login(gmail_user, gmail_password)
    server.sendmail(sent_from, to, email_text)
    server.close()

    print 'Email sent!'
except:
    print 'Something went wrong...'


    # jul/14/2021 21:08:26 by RouterOS 6.48.3
# software id = FUUF-UJEG
#
# model = RB750Gr3
# serial number = D5030D2351DB
/ip firewall mangle
add action=accept chain=prerouting dst-address=10.10.11.0/24
add action=accept chain=prerouting dst-address=181.51.57.0/24 in-interface=LAN

add action=accept chain=prerouting dst-address=192.168.0.0/24 in-interface=LAN        ****************

add action=mark-connection chain=prerouting connection-mark=no-mark in-interface=ISP1 new-connection-mark=ISP1_conn
add action=mark-connection chain=prerouting connection-mark=no-mark in-interface=ISP2 new-connection-mark=ISP2_conn

add action=mark-connection chain=prerouting connection-mark=no-mark in-interface=ISP3 new-connection-mark=ISP3_conn    ***********

add action=mark-connection chain=prerouting connection-mark=no-mark dst-address-type=!local in-interface=LAN \
    new-connection-mark=ISP1_conn per-connection-classifier=both-addresses:2/0
add action=mark-connection chain=prerouting connection-mark=no-mark dst-address-type=!local in-interface=LAN \
    new-connection-mark=ISP2_conn per-connection-classifier=both-addresses:2/1

add action=mark-connection chain=prerouting connection-mark=no-mark dst-address-type=!local in-interface=LAN \
    new-connection-mark=ISP3_conn per-connection-classifier=both-addresses:2/2           ******************************

add action=mark-routing chain=prerouting connection-mark=ISP1_conn in-interface=LAN new-routing-mark=to_ISP1
add action=mark-routing chain=prerouting connection-mark=ISP2_conn in-interface=LAN new-routing-mark=to_ISP2

add action=mark-routing chain=prerouting connection-mark=ISP3_conn in-interface=LAN new-routing-mark=to_ISP3   *********************************

add action=mark-routing chain=output connection-mark=ISP1_conn new-routing-mark=to_ISP1
add action=mark-routing chain=output connection-mark=ISP2_conn new-routing-mark=to_ISP2

add action=mark-routing chain=output connection-mark=ISP3_conn new-routing-mark=to_ISP3           ************************




/ip route
add check-gateway=ping distance=1 gateway=181.51.57.1%ISP1 routing-mark=to_ISP1
add check-gateway=ping distance=1 gateway=181.51.57.1%ISP2 routing-mark=to_ISP2

add check-gateway=ping distance=1 gateway=192.168.0.1 routing-mark=to_ISP3          *******************

add check-gateway=ping distance=1 gateway=181.51.57.1%ISP1
add check-gateway=ping distance=2 gateway=181.51.57.1%ISP2


add check-gateway=ping distance=3 gateway=181.51.57.1%ISP3      **************************

