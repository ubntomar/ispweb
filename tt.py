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