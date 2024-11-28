package application.loader;

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

    public DataCapteurs getDataCapteurs() {
        return this;
    }

    public String getname() {
        return this.name;
    }

    public void setname(String name) {
        this.name = name;
    }

    public List<DataValue> getCo2() {
        return this.co2;
    }

    public void setCo2(List<DataValue> co2) {
        this.co2 = co2;
    }

    public List<DataValue> gettemp() {
        return this.temp;
    }

    public void settemp(List<DataValue> temp) {
        this.temp = temp;
    }

    public List<DataValue> gethumidity() {
        return this.humidity;
    }

    public void sethumidity(List<DataValue> humidity) {
        this.humidity = humidity;
    }

}
