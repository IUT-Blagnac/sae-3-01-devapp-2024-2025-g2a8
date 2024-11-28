package application.control;

import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.layout.BorderPane;
import javafx.stage.Modality;
import javafx.stage.Stage;
import application.view.GestionCapteursViewController;

public class GestionCapteurs {

    private Stage capStage;
    private GestionCapteursViewController capViewController;
    
    


    public GestionCapteurs(Stage _parentStage) {

		try {
			FXMLLoader loader = new FXMLLoader(
			GestionCapteursViewController.class.getResource("SallesAffichage.fxml"));
			BorderPane root = loader.load();

			Scene scene = new Scene(root, root.getPrefWidth() + 50, root.getPrefHeight() + 10);

			this.capStage = new Stage();
			this.capStage.initModality(Modality.NONE);
			this.capStage.initOwner(_parentStage);
			this.capStage.setScene(scene);
			this.capStage.setTitle("Gestion des capteurs");
			this.capStage.setMaximized(true);
			this.capStage.setResizable(true);

			this.capViewController = loader.getController();
			this.capViewController.initContext(this.capStage, this);

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	    
    public void doCapteursDialog(){
		this.capViewController.showDialog();
    }


}

