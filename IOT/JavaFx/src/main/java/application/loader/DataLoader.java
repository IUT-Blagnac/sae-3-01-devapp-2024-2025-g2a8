package application.loader;

import java.io.IOException;
import java.io.InputStream;
import java.util.ArrayList;
import java.util.List;

import com.fasterxml.jackson.databind.ObjectMapper;

import application.loader.capteursSalle.DataCapteurs;
import application.loader.solarPanels.DataSolarPanel;
import java.io.FileInputStream;

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

    public List<DataSolarPanel> getDataSolarPanel() {
        return dataLoaderSolarPanels;
    }



    public void LoadDatasFromJson(String jsonfilePath){

        ObjectMapper objectMapper = new ObjectMapper();
        try {
            FileInputStream inputStream = new FileInputStream("C:\\Users\\Etudiant\\Downloads\\sae-3-01-devapp-2024-2025-g2a8\\IOT\\Systeme\\dataNormal.json");
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



}

