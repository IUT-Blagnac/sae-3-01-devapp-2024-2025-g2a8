package application.loader;

import java.util.List;

import com.fasterxml.jackson.annotation.JsonProperty;

import application.loader.capteursSalle.DataCapteurs;
import application.loader.solarPanels.DataSolarPanel;


public class DataPrincipale {

    private List<DataCapteurs> capteurs;
    @JsonProperty("solarPanel")
    private List<DataSolarPanel> solarPanel;


    public List<DataCapteurs> getCapteurs() {
        return capteurs;
    }

    public List<DataSolarPanel> getsolarPanels() {
        return solarPanel;
    }

}