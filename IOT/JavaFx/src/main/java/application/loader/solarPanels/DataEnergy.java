package application.loader.solarPanels;

public class DataEnergy {
    private String date;

    private double value;

    /**
     * Constructeur de DataEnergy permettant d'initialiser les données d'énergie
     * @param date : (String) date de la mesure
     * @param value : (double) valeur de l'énergie produite
     */
    public DataEnergy(String date, double value) {
        this.date = date;
        this.value = value;
    }

    /**
     * Constructeur sans parametre de DataEnergy permettant d'initialiser les données d'énergie
     */
    public DataEnergy() {
        this.date = "";
        this.value = 0;
    }
    
    /**
     * Permet d'avoir la date
     * @return String : date
     */
    public String getDate() {
        return date;
    }

    /**
     * Permet d'avoir la valeur
     * @return double : valeur
     */
    public double getValue() {
        return value;
    }


}
