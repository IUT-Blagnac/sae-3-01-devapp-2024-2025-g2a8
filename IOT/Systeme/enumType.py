from enum import Enum
import configparser
import os

config = configparser.ConfigParser()
config_path = os.path.join(os.path.dirname(__file__), 'config.ini')
config.read(config_path)

# Configuration
nomNormal = config.get('configTopic', 'nomFichierDonnees')
nomStrange = config.get('configTopic', 'nomFichierDonneesStrange')

class DataType(Enum):
    NORMAL = nomNormal + ".json"
    STARNGE = nomStrange + ".json"
    
class ValueTypeCap(Enum):
    CO2 = "co2"
    TEMPERATURE = "temp"
    HUMIDITE = "humidity"
    
class ValueTypePanel(Enum):
    ENERGY = "energy"