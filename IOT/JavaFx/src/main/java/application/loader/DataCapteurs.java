package application.loader;

import java.util.List;

public class DataCapteurs {
    private String salle;
    private List<DataValue> co2;
    private List<DataValue> temperature;
    private List<DataValue> humidite;


    public DataCapteurs(String salle, List<DataValue> co2, List<DataValue> temperature, List<DataValue> humidite) {
        this.salle = salle;
        this.co2 = co2;
        this.temperature = temperature;
        this.humidite = humidite;
    }

    public DataCapteurs getDataCapteurs() {
        return this;
    }

    public String getSalle() {
        return this.salle;
    }

    public void setSalle(String salle) {
        this.salle = salle;
    }

    public List<DataValue> getCo2() {
        return this.co2;
    }

    public void setCo2(List<DataValue> co2) {
        this.co2 = co2;
    }

    public List<DataValue> getTemperature() {
        return this.temperature;
    }

    public void setTemperature(List<DataValue> temperature) {
        this.temperature = temperature;
    }

    public List<DataValue> getHumidite() {
        return this.humidite;
    }

    public void setHumidite(List<DataValue> humidite) {
        this.humidite = humidite;
    }

}
