#!/usr/bin/python
# -*- coding: utf-8 -*-
from twilio.rest import Client 
account_sid = 'AC6dffc6d75f3fe13e5ab0cfe1f6180b57' 
auth_token = 'b6bcf5d638adfc032d2ab7f4ed35baf3' 
client = Client(account_sid, auth_token) 
 
message = client.messages.create( 
                              from_='+18508055304',  
                              body='Su servicio está vencido',      
                              to='+573147654655' 
                          ) 
 
print(message.sid)




