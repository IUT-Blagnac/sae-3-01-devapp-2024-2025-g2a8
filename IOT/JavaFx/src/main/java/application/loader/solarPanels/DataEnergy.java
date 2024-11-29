package application.loader.solarPanels;

public class DataEnergy {
    private String date;

    private double value;

    public DataEnergy(String date, double value) {
        this.date = date;
        this.value = value;
    }

    public DataEnergy() {
        this.date = "";
        this.value = 0;
    }
    
    public String getDate() {
        return date;
    }

    public double getValue() {
        return value;
    }


}
