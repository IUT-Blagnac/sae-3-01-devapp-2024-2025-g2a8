package application.loader;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import com.fasterxml.jackson.databind.ObjectMapper;

public class DataLoader {
    private List<DataCapteurs> dataLoader;

    public DataLoader() {
        this.dataLoader = new ArrayList<DataCapteurs>();
    }

    public List<DataCapteurs> getDataLoader() {
        return dataLoader;
    }


    public void LoadDatasFromJson(String jsonfilePath){

        ObjectMapper objectMapper = new ObjectMapper();
        
        try {
            File jsonFile = new File(getClass().getResource("/dataNormal.json").getFile());

            DataPrincipale dataPrincipale = objectMapper.readValue(jsonFile, DataPrincipale.class);

            
            this.dataLoader.addAll(dataPrincipale.getCapteurs());
           
            

            
        } catch (IOException e) {
            e.printStackTrace();
        }
        
    }

    public void affichagerData(){
        for (DataCapteurs dataCapteurs : dataLoader) {
            System.out.println("Salle: " + dataCapteurs.getname());
            
            // Afficher les données de CO2
            for (DataValue dataValue : dataCapteurs.getCo2()) {
                System.out.println("CO2 - Date: " + dataValue.getDate() + ", Value: " + dataValue.getValue());
            }

            // Afficher les données de température
            for (DataValue dataValue : dataCapteurs.gettemp()) {
                System.out.println("Température - Date: " + dataValue.getDate() + ", Value: " + dataValue.getValue());
            }

            // Afficher les données d'humidité
            for (DataValue dataValue : dataCapteurs.gethumidity()) {
                System.out.println("Humidité - Date: " + dataValue.getDate() + ", Value: " + dataValue.getValue());
            }
            System.out.println(); 
    }
    }
    
}
