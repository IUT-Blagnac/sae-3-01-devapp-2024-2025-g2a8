package application.loader;

import java.util.List;


public class DataPrincipale {

    private List<DataCapteurs> capteurs;
    private List<DataSolarPanel> dataLoader;


    public List<DataCapteurs> getCapteurs() {
        return capteurs;
    }

    public void setCapteurs(List<DataCapteurs> capteurs) {
        this.capteurs = capteurs;
    }
}