package application.loader.capteursSalle;

import java.util.ArrayList;
import java.util.List;

import com.fasterxml.jackson.annotation.JsonCreator;
import com.fasterxml.jackson.annotation.JsonProperty;

public class DataCapteurs {
    private String name;
    private List<DataValue> co2;
    private List<DataValue> temp;
    private List<DataValue> humidity;


    @JsonCreator
    public DataCapteurs(
            @JsonProperty("name") String name,
            @JsonProperty("co2") List<DataValue> co2,
            @JsonProperty("temp") List<DataValue> temp,
            @JsonProperty("humidity") List<DataValue> humidity) {
        this.name = name;
        this.co2 = co2;
        this.temp = temp;
        this.humidity = humidity;
    }

    public List<List<DataValue>> getDataCapteurs() {
        ArrayList<List<DataValue>> dataCapteurs = new ArrayList<List<DataValue>>();
        dataCapteurs.add(this.co2);
        dataCapteurs.add(this.temp);
        dataCapteurs.add(this.humidity);
        return dataCapteurs;
    }

    public String getname() {
        return this.name;
    }


    public List<DataValue> getCo2() {
        return this.co2;
    }


    public List<DataValue> gettemp() {
        return this.temp;
    }


    public List<DataValue> gethumidity() {
        return this.humidity;
    }


    public List<DataValue> getValues(String type) {

        switch (type) {

            case "CO2":

                return this.getCo2();

            case "Température":

                return this.gettemp();

            case "Humidité":

                return this.gethumidity();

            default:

                return new ArrayList<>();

        }
    }

    @Override
    public String toString() {
        // TODO Auto-generated method stub
        return this.getname();
    }

}
