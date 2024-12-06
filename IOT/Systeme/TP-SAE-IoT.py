#!/usr/bin/env python3
# -*- coding: utf-8 -*-

# Le device
from enumType import DataType, ValueTypeCap, ValueTypePanel
from jsonDataLib import createValueEntry, addInfoToCapteur, addInfoToSolarPanel
from traceback import print_tb
from xmlrpc.client import boolean
import paho.mqtt.client as mqtt
import json
import logging
import configparser
import datetime as dt
import os

print("Configuration :")

config = configparser.ConfigParser()
config_path = os.path.join(os.path.dirname(__file__), 'config.ini')
config.read(config_path)

# Configuration
mqttServer = config.get('configTopic', 'mqttServer')
nomFichierDonnees = config.get('configTopic', 'nomFichierDonnees')
#Récupération des seuils 
seuilTemperature = config.get('configTopic', 'seuilTemperature')
seuilHumidite = config.get('configTopic', 'seuilHumidity')
seuilCO2 = config.get('configTopic', 'seuilCO2')
panneauxSolaireBool = config.get('configTopic', 'panneauxSolaireBool')

topics = list(config['topics'].values())


logging.basicConfig(level=logging.INFO)

# Callback de réception des messages
def get_data(mqttc, obj, msg):
    try:
        jsonMsg = json.loads(msg.payload)
        
        #si on est sur le topic des panneaux solaire et que l'option est activé
        if (msg.topic.split("/")[0] == "solaredge" and panneauxSolaireBool == "True"):

            #On recupere la valeur a sauvegarder
            valueToSave =  jsonMsg["lastDayData"]["energy"]
            entree = createValueEntry(valueToSave)

            #Enregistrement de la valeur dans un fichier json
            addInfoToSolarPanel(entree, "Panneaux Solaire", ValueTypePanel.ENERGY, DataType.NORMAL)
            print("Panneaux Solaire : " + str(valueToSave) + " kWh")

        elif (msg.topic.split("/")[0] == "AM107"):

            #On recupere la valeur a sauvegarder pour la salle
            salle = msg.topic.split("/")[2]
            
            #si la temperature est au dessus du seuil
            if (jsonMsg[0]["temperature"] >= int(seuilTemperature)):

                #On recupere les valeurs a sauvegarder
                valueToSave =  jsonMsg[0]["temperature"]
                temp = createValueEntry(valueToSave)

                #Enregistrement des valeurs dans un fichier json different
                addInfoToCapteur(temp, salle, ValueTypeCap.TEMPERATURE, DataType.STARNGE)
                

                print("Salle de cours : " + salle)
                print("Temperature : " + str(valueToSave[0]) + " °C")
            else :
                #On recupere les valeurs a sauvegarder
                valueToSave =  jsonMsg[0]["temperature"]
                temp = createValueEntry(valueToSave)

                #Enregistrement des valeurs dans un fichier json pour les valeurs normales
                addInfoToCapteur(temp, salle, ValueTypeCap.TEMPERATURE, DataType.NORMAL)

                print("Salle de cours : " + salle)
                print("Temperature : " + str(valueToSave) + " °C")
            if (jsonMsg[0]["humidity"] >= int(seuilHumidite)):
                #On recupere les valeurs a sauvegarder
                valueToSave =  jsonMsg[0]["humidity"]
                hum = createValueEntry(valueToSave)

                #Enregistrement des valeurs dans un fichier json different
                addInfoToCapteur(hum, salle, ValueTypeCap.HUMIDITE, DataType.STARNGE)

                print("Salle de cours : " + salle)
                print("humidité : " + str(valueToSave) + " %")
            else:
                #On recupere les valeurs a sauvegarder
                valueToSave =  jsonMsg[0]["humidity"]
                hum = createValueEntry(valueToSave)

                #Enregistrement des valeurs dans un fichier json pour les valeurs normales
                addInfoToCapteur(hum, salle, ValueTypeCap.HUMIDITE, DataType.NORMAL)

                print("Salle de cours : " + salle)
                print("humidité : " + str(valueToSave) + " %")
            if (jsonMsg[0]["co2"] >= int(seuilCO2)):
                #On recupere les valeurs a sauvegarder
                valueToSave =  jsonMsg[0]["co2"]
                co2 = createValueEntry(valueToSave)

                #Enregistrement des valeurs dans un fichier json different
                addInfoToCapteur(co2, salle, ValueTypeCap.CO2, DataType.STARNGE)

                print("Salle de cours : " + salle)
                print("co2 : " + str(valueToSave) + " ppm")
            else:
                #On recupere les valeurs a sauvegarder
                valueToSave =  jsonMsg[0]["co2"]
                co2 = createValueEntry(valueToSave)

                #Enregistrement des valeurs dans un fichier json pour les valeurs normales
                addInfoToCapteur(co2, salle, ValueTypeCap.CO2, DataType.NORMAL)

                print("Salle de cours : " + salle)
                print("co2 : " + str(valueToSave) + " ppm")
            
            
    except (json.JSONDecodeError, KeyError) as e:
        logging.error("Erreur dans les données reçues : %s", e)

# Connexion et souscription
mqttc = mqtt.Client()
mqttc.connect(mqttServer, port=1883, keepalive=60)
mqttc.on_message = get_data
for item in topics:
    mqttc.subscribe(item, qos=0)

mqttc.loop_forever()

