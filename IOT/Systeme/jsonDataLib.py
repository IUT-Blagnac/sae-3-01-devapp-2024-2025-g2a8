import json
import os
from datetime import datetime
from enumType import ValueTypeCap, ValueTypePanel

def initFile(dataType):
    initStructure = {
        "capteurs":[],
        "solarPanel":[]
    }
    
    with open(dataType.value, 'w') as file:
        file.write(json.dumps(initStructure))
        
def verifyFileExist(dataType):
    if(not os.path.isfile(dataType.value)):
        initFile(dataType)

def getActualData(dataType):
    verifyFileExist(dataType)
    
    with open(dataType.value, 'r') as file:
        dataActual = json.load(file)
        
    return dataActual

def writeData(data, dataType):
    with open(dataType.value, 'w') as file :
        file.write(json.dumps(data, indent=2))
        
    return True

def createValueEntry(value):
    now = datetime.now()
    formatted_date = now.strftime("%Y-%m-%d %H:%M:%S")
    return {
        "date" : str(formatted_date),
        "value" : value
    }
    
def createCapteurEntry(name):
    capteurObject = {
        "name" : name
    }
    
    for type in ValueTypeCap._member_map_:
        capteurObject[ValueTypeCap._member_map_[type].value] = []
    
    return capteurObject

def createSolarEntry(name):
    solarObject = {
        "name" : name
    }
    
    for type in ValueTypePanel._member_map_:
        solarObject[ValueTypePanel._member_map_[type].value] = []
    
    return solarObject
    
def addCapteur(name, dataType):
    actData = getActualData(dataType)
    capSearch = None
    
    for cap in actData["capteurs"]:
        if(cap["name"] == name) :
            capSearch = cap
            
    if(capSearch != None):
        return False
    
    capteur = createCapteurEntry(name)
    
    actData["capteurs"].append(capteur)
    
    if(writeData(actData, dataType)):
        return True
    else :
        return False
    
def addSolarPanel(name, dataType):
    actData = getActualData(dataType)
    solarSearch = None
    
    for panel in actData["solarPanel"]:
        if(panel["name"] == name):
            solarSearch = panel
            
    if(solarSearch != None):
        return False
    
    solarPanel = createSolarEntry(name)
    
    actData["solarPanel"].append(solarPanel)
    
    if(writeData(actData, dataType)):
        return True
    else :
        return False
       
def addInfoToCapteur(entry, capName, valueType, dataType):
    actData = getActualData(dataType)
    capteur = None
    
    for cap in actData["capteurs"]:
        if(cap["name"] == capName) :
            capteur = cap
    
    if(capteur == None):
        addCapteur(capName, dataType)
        return addInfoToCapteur(entry, capName, valueType, dataType)
        
    capteur[valueType.value].append(entry)
    
    if(writeData(actData, dataType)):
        return True
    else :
        return False
    
def addInfoToSolarPanel(entry, panelName, valueType, dataType):
    actData = getActualData(dataType)
    solarPannel = None
    
    for panel in actData["solarPanel"]:
        if(panel["name"] == panelName) :
            solarPannel = panel
    
    if(solarPannel == None):
        addSolarPanel(panelName, dataType)
        return addInfoToSolarPanel(entry, panelName, valueType, dataType)
        
    solarPannel[valueType.value].append(entry)
    
    if(writeData(actData, dataType)):
        return True
    else :
        return False