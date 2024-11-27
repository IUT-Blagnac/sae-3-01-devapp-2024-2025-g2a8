package application.control;

import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.layout.BorderPane;
import javafx.stage.Modality;
import javafx.stage.Stage;
import application.view.ConfigCapteursViewController;
import application.view.GestionCapteursViewController;

public class ConfigCapteurs {
    private Stage configStage;
    private ConfigCapteursViewController configCapteursViewController;
    
    


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

	    
    public void doConfigDialog(){
		this.configCapteursViewController.showDialog();
    }
}
