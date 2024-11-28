package application;

import application.control.RockGestionMain;
import application.loader.DataLoader;

public class RockGest {
    public static void main(String[] args) {
        RockGestionMain.runApp();
        DataLoader dataLoader = new DataLoader();
        dataLoader.LoadDatasFromJson("dataNormal.json");
        dataLoader.affichagerData();
    }
}
