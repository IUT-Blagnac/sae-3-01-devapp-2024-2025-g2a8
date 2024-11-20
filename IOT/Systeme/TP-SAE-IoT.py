#!/usr/bin/env python3
# -*- coding: utf-8 -*-

# Le device

from traceback import print_tb
from xmlrpc.client import boolean
import paho.mqtt.client as mqtt
import json
import logging
import configparser
import datetime as dt

config = configparser.ConfigParser()
config.read('config.ini')

# Configuration
mqttServer = config.get("configTopic", "mqttServer")
nomFichierDonnees = config.get("configTopic", "nomFichierDonnees")
seuilTemperature = config.get("configTopic", "seuilTemperature")
panneauxSolaireBool = config.get("configTopic", "panneauxSolaireBool")

topics = []
for item in config["topics"]:
    topics.append(config["topics"][item])


logging.basicConfig(level=logging.INFO)

# Callback de réception des messages
def get_data(mqttc, obj, msg):
    try:
        jsonMsg = json.loads(msg.payload)
        date = dt.datetime.now()
        
        file = open(nomFichierDonnees+".txt", "a")
        fileSeuil = open(nomFichierDonnees+"Alerte.txt", "a")
        if (msg.topic.split("/")[0] == "solaredge" and panneauxSolaireBool == "True"):
            txt = "Puissance produite sur la dernière journée par les panneaux solaires : " + str(jsonMsg["lastDayData"]["energy"])
            print(txt)
            file.write("[" + str(date) + "] " + txt + "\n")
        elif (msg.topic.split("/")[0] == "AM107"):
            temperature = jsonMsg[0]["temperature"]
            txt = "Temperature : " + str(temperature) + "°C de la salle " + str(jsonMsg[1]["room"])
            print(txt)
            fileTemperature = fileSeuil if temperature >= int(seuilTemperature) else file
            fileTemperature.write("[" + str(date) + "] " + txt + "\n")
    except (json.JSONDecodeError, KeyError) as e:
        logging.error("Erreur dans les données reçues : %s", e)

# Connexion et souscription
mqttc = mqtt.Client()
mqttc.connect(mqttServer, port=1883, keepalive=60)
mqttc.on_message = get_data
for item in topics:
    mqttc.subscribe(item, qos=0)

mqttc.loop_forever()

