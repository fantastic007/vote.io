import os
import sys
import requests
import time

#python superAdminCLI.py deploy agent
#python superAdminCLI.py deploy channel mychannel

org1_token = ''
org2_token = ''
while True:
    com = raw_input('Command:').split()
    if len(com) == 0:
        break

    elif com[0] == 'test':
        print "asdasd"

    elif com[0] == 'deploy':
        if com[1] == 'agent':
            headers = {'content-type': 'application/x-www-form-urlencoded',}
            data = {'username': 'monipur','orgName': 'Org1'}
            response = requests.post('http://localhost:5000/users', headers=headers, data=data)
            org1_token =  response.json()['token']

            print org1_token

            headers = {'content-type': 'application/x-www-form-urlencoded',}
            data = {'username': 'kazipara','orgName': 'Org1'}
            response = requests.post('http://localhost:5000/users', headers=headers, data=data)
            org2_token =  response.json()['token']

            print org2_token

        if com[1] == 'channel'
            headers = {'authorization': 'Bearer '+org1_token,'content-type': 'application/json',}
            data = '{ "channelName":"'+com[2]+'", "channelConfigPath":"../artifacts/channel/'+com[2]+'.tx"}'
            response = requests.post('http://localhost:5000/channels', headers=headers, data=data)

            time.sleep(5)

            headers = {'authorization': 'Bearer '+org1_token,'content-type': 'application/json',}
            data = '{"peers": ["peer0.org1.example.com","peer1.org1.example.com"]}'
            response = requests.post('http://localhost:5000/channels/'+ com[2]+'/peers', headers=headers, data=data)

            headers = {'authorization': 'Bearer '+org2_token,'content-type': 'application/json',}
            data = '{"peers": ["peer0.org2.example.com","peer1.org2.example.com"]}'
            response = requests.post('http://localhost:5000/channels/'+ com[2]+'/peers', headers=headers, data=data)

            


'''
print sys.argv[0]
lenArg =  len(sys.argv)

if lenArg < 2:
    print "error"

if sys.argv[1] == "deploy":
    os.system('./runApp.sh')

if sys.argv[1] == "create":
    os.system('./createScheme.sh')

if sys.argv[1] == "init":
    os.system('./createScheme.sh')

if sys.argv[1] == "enroll":
    headers = {'content-type': 'application/x-www-form-urlencoded',}

    data = {'username': sys.argv[2],'orgName': 'Org1'}

    response = requests.post('http://localhost:5000/users', headers=headers, data=data)

    print response.json()['token']
'''