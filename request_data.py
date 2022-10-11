# -*- coding: utf-8 -*-
"""
Created on Wed Jul 20 14:03:11 2022

@author: henry
"""
import requests
import json
'''
url = 'http://127.0.0.1:8888/call_python.php'
download = requests.get(url)
text = (download.json())
print(text)


print('---------------------------------------')

url ='http://127.0.0.1:8888/send_python.php'
download = requests.post(url,{"username":"johnny","password": 0})
text = (download.json())
print(text)

print('---------------------------------------')
'''
'''
url ='http://140.136.158.155:5050/send_python.php'
#url = 'http://140.136.158.155:5050/test.php'
dic = {"account":"null","password": 0,"latitude":25.03549666109588,"longitude": 121.42948922852717,"mode":'A'}
#dic = str(dic)
download = requests.post(url,dic)
#text = (download.json())
print(download.text)
'''
#url = 'http://140.136.158.155:5050/test2.php'
url ="http://140.136.158.155:5050/test3.php"
#url = 'http://127.0.0.1:8888/php_mysql.php'
#url = 'http://140.136.158.155:5050/test.php'
#url = 'http://127.0.0.1:8888/json.php'
dic = { "account":"henry", "origin":"南港車站" , "destination":"新店", 'mode':'directions','password':'12345','mode2':'python'}
#dic = str(dic)
#dic = {"mode2":"python","mode":"circle","account":"henry","latitude":25.03549666109588,"longitude": 121.42948922852717,'password':'8764'}
download = requests.post(url,dic)
text = download.text
'''
text = json.loads(download.text)
text = eval(text)
'''

print(text)


