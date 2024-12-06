package application.loader;

import java.io.IOException;
import java.io.InputStream;
import java.util.ArrayList;
import java.util.List;

import com.fasterxml.jackson.databind.ObjectMapper;

import application.loader.capteursSalle.DataCapteurs;
import application.loader.solarPanels.DataSolarPanel;

import java.io.File;
import java.io.FileInputStream;

public class DataLoader {
    private List<DataCapteurs> dataLoaderSalles;
    private List<DataSolarPanel> dataLoaderSolarPanels;

    /**
     * Constructeur de DataLoader permettant d'initialiser les listes de capteurs et de panneaux solaires
     */
    public DataLoader() {
        this.dataLoaderSalles = new ArrayList<DataCapteurs>();
        this.dataLoaderSolarPanels = new ArrayList<DataSolarPanel>();
    }

    /**
     * Retourne la liste de tous les capteurs
     * 
     * @return List<DataCapteurs> : liste des capteurs
     */
    public List<DataCapteurs> getDataLoader() {
        return dataLoaderSalles;
    }

    /**
     * Retourne la liste de tous les panneaux solaires
     * 
     * @return List<DataSolarPanel> : liste des panneaux solaires
     */
    public List<DataSolarPanel> getDataSolarPanel() {
        return dataLoaderSolarPanels;
    }

    /**
     * Permet de charger les données depuis un fichier JSON
     * 
     * @param jsonfilePath : (String) chemin du fichier JSON
     */
    public void LoadDatasFromJson(String jsonfilePath){

        //Initialisation de l'objet ObjectMapper
        ObjectMapper objectMapper = new ObjectMapper();
        //Emplacement du fichier JSON
        try {
            FileInputStream inputStream = new FileInputStream(new File(getClass().getProtectionDomain().getCodeSource().getLocation().toURI().getPath()).getParent() + File.separator + "dataNormal.json");

            //Désérialisation du fichier JSON
            DataPrincipale dataPrincipale = objectMapper.readValue(inputStream, DataPrincipale.class);

                        //Ajout des données dans les listes
            this.dataLoaderSalles.addAll(dataPrincipale.getCapteurs());
            this.dataLoaderSolarPanels.addAll(dataPrincipale.getsolarPanels());
        } catch (Exception e) {
            System.err.println("Error loading JSON data: " + e.getMessage());
            e.printStackTrace();
        }
        
    }



}

