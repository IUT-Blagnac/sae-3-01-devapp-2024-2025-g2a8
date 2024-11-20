from enum import Enum

class DataType(Enum):
    NORMAL = "dataNormal.json"
    STARNGE = "dataStrange.json"
    
class ValueTypeCap(Enum):
    CO2 = "co2"
    TEMPERATURE = "temp"
    HUMIDITE = "humidity"
    
class ValueTypePanel(Enum):
    ENERGY = "energy"