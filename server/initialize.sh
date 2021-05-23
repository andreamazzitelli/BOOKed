#!/bin/bash
echo "Aggiungendo Roma"
curl -X PUT http://admin:admin@couchdb:5984/roma
curl -X PUT http://admin:admin@couchdb:5984/roma/listacitta -d @roma.json
echo "Aggiunta Roma"

#echo "Aggiungendo Milano"
#curl -X PUT http://admin:admin@couchdb:5984/milano
#curl -X PUT http://admin:admin@couchdb:5984/milano/listacitta -d @milano.json
#echo "Aggiunta Milano"

#echo "Aggiungendo Firnze"
#curl -X PUT http://admin:admin@couchdb:5984/firenze
#curl -X PUT http://admin:admin@couchdb:5984/firenze/listacitta -d @firenze.json
#echo "Aggiunta Firenze"
