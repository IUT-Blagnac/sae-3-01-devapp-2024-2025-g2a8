from enumType import DataType, ValueTypeCap, ValueTypePanel
from jsonDataLib import createValueEntry, addInfoToCapteur, addInfoToSolarPanel

#La veleur a sauvegarder
valueToSave = 50

#Une fois qu'une valeur est prête a etre enregistre il faut d'abord la formaté et creer un entrée valeur
entree = createValueEntry(valueToSave)

#Maintenant tout est prêt pour l'enregistrement qui peut ce faire via 2 fonction
#Une pour enregistrer dans un capeteur 
addInfoToCapteur(entree, "AMPHI 1", ValueTypeCap.TEMPERATURE, DataType.NORMAL)

#Une autre pour enregistrer dans une panneau solaire
addInfoToSolarPanel(entree, "TRUC", ValueTypePanel.ENERGY, DataType.NORMAL)

#Ces deux fonction prenne dans les deux dernier parametre le type de la valeur et si c'est une donnee normal ou au dessus 
#du seuil d'alerte (NORMAL = Enregistre sur le fichier des veleur normal, STRANGE enregistre sur le fichier des valeur 
# au dessus du seuil)
#
#Pour les type de valeur il existe deux enum differente une pour les type d'un capteur et une autre pour les type d'un
#panneau solaire il suffit de faire un ctr+espace pour voir la liste de type disponible

#Cette librairie aussure aussi toute la gestion du fichier il n'y a donc rien a faire d'autre qu'utiliser ces 
# trois fonctions