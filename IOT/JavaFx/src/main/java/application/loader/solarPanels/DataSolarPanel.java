package application.loader.solarPanels;

import java.util.List;

import com.fasterxml.jackson.annotation.JsonCreator;
import com.fasterxml.jackson.annotation.JsonProperty;

public class DataSolarPanel {
    private String name;
    private List<DataEnergy> energy;

    @JsonCreator
    public DataSolarPanel(
        @JsonProperty("name") String name, 
        @JsonProperty("energy") List<DataEnergy> energy) {
        this.name = name;
        this.energy = energy;
    }


    public String getName() {
        return this.name;
    }

    public List<DataEnergy> getEnergy() {
        return this.energy;
    }



}
