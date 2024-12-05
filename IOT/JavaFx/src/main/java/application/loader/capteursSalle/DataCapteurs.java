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


    /**
     * Constructeur de DataCapteurs permettant d'initialiser les données des capteurs d'une salle
     * en utilisant le format JSON pour la désérialisation
     * 
     * @param name      : (String) nom de la salle
     * @param co2       : (List<DataValue>) liste des valeurs de CO2 mesurées
     * @param temp      : (List<DataValue>) liste des valeurs de température mesurées
     * @param humidity  : (List<DataValue>) liste des valeurs d'humidité mesurées
     */
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

   
    /**
     * Méthode permettant de récupérer le nom de la salle
     */
    public String getname() {
        return this.name;
    }


    /**
     * Méthode permettant de récupérer les valeurs de CO2 mesurées
     */
    public List<DataValue> getCo2() {
        return this.co2;
    }


    /**
     * Méthode permettant de récupérer les valeurs de température mesurées
     */
    public List<DataValue> gettemp() {
        return this.temp;
    }


    /**
     * Méthode permettant de récupérer les valeurs d'humidité mesurées
     */
    public List<DataValue> gethumidity() {
        return this.humidity;
    }


    /**
     * Méthode permettant de récupérer les valeurs mesurées en fonction du type de mesure souhaité
     * 
     * @param type : (String) type de mesure
     * @return List<DataValue> : liste des valeurs mesurées
     */
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

    /**
     * @return String : nom de la salle
     */
    @Override
    public String toString() {
        return this.getname();
    }

}
