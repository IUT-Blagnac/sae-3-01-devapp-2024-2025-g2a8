package application.loader;

import java.io.IOException;
import java.io.InputStream;
import java.util.ArrayList;
import java.util.List;

import com.fasterxml.jackson.databind.ObjectMapper;

import application.loader.capteursSalle.DataCapteurs;
import application.loader.capteursSalle.DataValue;
import application.loader.solarPanels.DataEnergy;
import application.loader.solarPanels.DataSolarPanel;

public class DataLoader {
    private List<DataCapteurs> dataLoaderSalles;
    private List<DataSolarPanel> dataLoaderSolarPanels;

    public DataLoader() {
        this.dataLoaderSalles = new ArrayList<DataCapteurs>();
        this.dataLoaderSolarPanels = new ArrayList<DataSolarPanel>();
    }

    public List<DataCapteurs> getDataLoader() {
        return dataLoaderSalles;
    }


    public void LoadDatasFromJson(String jsonfilePath){

        ObjectMapper objectMapper = new ObjectMapper();
        
        try {
            InputStream inputStream = getClass().getResourceAsStream("/pythonResources/" + jsonfilePath);
            if (inputStream == null) {
                throw new IOException("Cannot find resource: " + jsonfilePath);
            }
            
            DataPrincipale dataPrincipale = objectMapper.readValue(inputStream, DataPrincipale.class);

            this.dataLoaderSalles.addAll(dataPrincipale.getCapteurs());
            this.dataLoaderSolarPanels.addAll(dataPrincipale.getsolarPanels());

            
        } catch (IOException e) {
            System.err.println("Error loading JSON data: " + e.getMessage());
            e.printStackTrace();
        }
        
    }

    // public void affichagerData(){
    //     for (DataCapteurs dataCapteurs : dataLoaderSalles) {
    //         System.out.println("Salle: " + dataCapteurs.getname());
            
    //         // Afficher les données de CO2
    //         for (DataValue dataValue : dataCapteurs.getCo2()) {
    //             System.out.println("CO2 - Date: " + dataValue.getDate() + ", Value: " + dataValue.getValue());
    //         }

    //         // Afficher les données de température
    //         for (DataValue dataValue : dataCapteurs.gettemp()) {
    //             System.out.println("Température - Date: " + dataValue.getDate() + ", Value: " + dataValue.getValue());
    //         }

    //         // Afficher les données d'humidité
    //         for (DataValue dataValue : dataCapteurs.gethumidity()) {
    //             System.out.println("Humidité - Date: " + dataValue.getDate() + ", Value: " + dataValue.getValue());
    //         }
    //         System.out.println(); 
    //     }
    // }

    // public void afficherSolarPanel(){
    //     for (DataSolarPanel dataSolarPanel : dataLoaderSolarPanels) {
    //         System.out.println("Panneau solaire: " + dataSolarPanel.getName());
    //         for (DataEnergy dataEnergy : dataSolarPanel.getEnergy()) {
    //             System.out.println("Energie - Date: " + dataEnergy.getDate() + ", Value: " + dataEnergy.getValue());
    //         }
    //         System.out.println();
    //     }
    // }

}

