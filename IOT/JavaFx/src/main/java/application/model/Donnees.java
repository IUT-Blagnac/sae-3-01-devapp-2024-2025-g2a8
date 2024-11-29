package application.model;

public class Donnees {
    private String date;
    private String valeur;
    private String type;
    private String salle;

    public Donnees(String date, String valeur, String type, String salle) {
        this.date = date;
        this.valeur = valeur;
        this.type = type;
        this.salle = salle;
    }

    public String getDate() {
        return date;
    }

    public String getValeur() {
        return valeur;
    }

    public String getType() {
        return type;
    }

    public String getSalle() {
        return salle;
    }
}