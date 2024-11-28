package application.control;

import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.layout.BorderPane;
import javafx.stage.Modality;
import javafx.stage.Stage;
import application.view.GestionPanneauxViewController;

public class GestionPanneaux {
    private Stage panneauStage;
    private GestionPanneauxViewController panneauxViewController;
    
    


    public GestionPanneaux(Stage _parentStage) {

		try {
			FXMLLoader loader = new FXMLLoader(
			GestionPanneauxViewController.class.getResource("PanneauAffichage.fxml"));
			BorderPane root = loader.load();

			Scene scene = new Scene(root, root.getPrefWidth() + 50, root.getPrefHeight() + 10);

			this.panneauStage = new Stage();
			this.panneauStage.initModality(Modality.NONE);
			this.panneauStage.initOwner(_parentStage);
			this.panneauStage.setScene(scene);
			this.panneauStage.setTitle("Gestion Panneaux Solaire");
            this.panneauStage.setMaximized(true);
			this.panneauStage.setResizable(true);

			this.panneauxViewController = loader.getController();
			this.panneauxViewController.initContext(this.panneauStage, this);

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	    
    void doPanneauxDialog(){
		this.panneauxViewController.showDialog();;
    }

}
