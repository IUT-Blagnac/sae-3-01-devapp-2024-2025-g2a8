package application.loader;

import java.util.List;

import com.fasterxml.jackson.annotation.JsonProperty;

import application.loader.capteursSalle.DataCapteurs;
import application.loader.solarPanels.DataSolarPanel;


public class DataPrincipale {

    
    private List<DataCapteurs> capteurs;
    @JsonProperty("solarPanel")
    private List<DataSolarPanel> solarPanel;

    /**
     * 
     */
    /**
     * Retourne la liste de tous les capteurs
     * 
     * @return List<DataCapteurs> : liste des capteurs
     */
    public List<DataCapteurs> getCapteurs() {
        return capteurs;
    }

    /**
     * Retourne la liste de tous les panneaux solaires
     * 
     * @return List<DataSolarPanel> : liste des panneaux solaires
     */
    public List<DataSolarPanel> getsolarPanels() {
        return solarPanel;
    }

}