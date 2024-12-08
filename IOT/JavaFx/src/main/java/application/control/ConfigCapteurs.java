package application.control;

import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.layout.BorderPane;
import javafx.stage.Modality;
import javafx.stage.Stage;
import application.view.ConfigCapteursViewController;
import application.view.GestionCapteursViewController;
import org.ini4j.*;
import java.io.File;

public class ConfigCapteurs {
    private Stage configStage;
    private ConfigCapteursViewController configCapteursViewController;
    
    private String fileEmplacement;

    public ConfigCapteurs(Stage _parentStage) {

		try {
			FXMLLoader loader = new FXMLLoader(
			GestionCapteursViewController.class.getResource("ConfigAffichage.fxml"));
			BorderPane root = loader.load();

			Scene scene = new Scene(root, root.getPrefWidth() + 50, root.getPrefHeight() + 10);

			this.configStage = new Stage();
			this.configStage.initModality(Modality.NONE);
			this.configStage.initOwner(_parentStage);
			this.configStage.setScene(scene);
			this.configStage.setTitle("Gestion des capteurs");
			this.configStage.setResizable(true);

			this.configCapteursViewController = loader.getController();
			this.configCapteursViewController.initContext(this.configStage, this);

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	public void setFileEmplacement(String path) {
		this.fileEmplacement = path;
	}

	public String getFileEmplacement() {
		return this.fileEmplacement;
	}

	    
    public void doConfigDialog(){
		this.configCapteursViewController.showDialog();
    }

	public String read(String section, String option){
		try{
            Wini ini = new Wini(new File(new File(getClass().getProtectionDomain().getCodeSource().getLocation().toURI()).getParent() + File.separator + "config.ini")); 
			return (ini.get(section, option));
        }catch(Exception e){
            System.err.println(e.getMessage());
        }
		return null;
	}

	public void write(String frequence, String seuilTemperature, String seuilHumidite, String seuilCO2, String nomFichierDonnees, String nomFichierDonneesStrange){
		try{
            Wini ini = new Wini(new File(new File(getClass().getProtectionDomain().getCodeSource().getLocation().toURI()).getParent() + File.separator + "config.ini")); 
			if (!frequence.isEmpty()){
				ini.put("configTopic", "frequence", frequence);
			}
			if (!seuilTemperature.isEmpty()){
				ini.put("configTopic", "seuilTemperature", seuilTemperature);
			}
			if (!seuilHumidite.isEmpty()) {
				ini.put("configTopic", "seuilHumidity", seuilHumidite);
			}
			if (!seuilCO2.isEmpty()) {
				ini.put("configTopic", "seuilCo2", seuilCO2);
			}
			if (!nomFichierDonnees.isEmpty()) {
				ini.put("configTopic", "nomFichierDonnees", nomFichierDonnees);
			}
			if (!nomFichierDonneesStrange.isEmpty()) {
				ini.put("configTopic", "nomFichierDonneesStrange", nomFichierDonneesStrange);
			}
			
            ini.store();
        }catch(Exception e){
            System.err.println(e.getMessage());
        }
	}
}
