package application.loader.solarPanels;

import java.util.List;

import com.fasterxml.jackson.annotation.JsonCreator;
import com.fasterxml.jackson.annotation.JsonProperty;

public class DataSolarPanel {
    private String name;
    private List<DataEnergy> energy;

    /**
     * Constructeur de DataSolarPanel permettant d'initialiser les données des panneaux solaires
     * en utilisant le format JSON pour la désérialisation
     * 
     * @param name : (String) nom du panneau solaire
     * @param energy : (List<DataEnergy>) liste des valeurs d'énergie produites
     */
    @JsonCreator
    public DataSolarPanel(
        @JsonProperty("name") String name, 
        @JsonProperty("energy") List<DataEnergy> energy) {
        this.name = name;
        this.energy = energy;
    }


    /**
     * Méthode permettant de récupérer le nom du panneau solaire
     */
    public String getName() {
        return this.name;
    }

    /**
     * Méthode permettant de récupérer les valeurs d'énergie produites
     */
    public List<DataEnergy> getEnergy() {
        return this.energy;
    }



}
