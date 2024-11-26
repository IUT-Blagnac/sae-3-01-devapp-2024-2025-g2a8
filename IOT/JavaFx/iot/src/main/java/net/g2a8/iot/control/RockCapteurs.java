package net.g2a8.iot.control;

import javafx.application.Application;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.layout.BorderPane;
import javafx.stage.Modality;
import javafx.stage.Stage;
import net.g2a8.iot.view.GestionCapteursViewController;
import net.g2a8.iot.view.RockGestMainViewController;

public class RockCapteurs {

    private Stage capStage;
    private RockGestMainViewController capViewController;
    
    


    public RockCapteurs(Stage _parentStage) {

		try {
			FXMLLoader loader = new FXMLLoader(
			GestionCapteursViewController.class.getResource("SallesAffichage.fxml"));
			BorderPane root = loader.load();

			Scene scene = new Scene(root, root.getPrefWidth() + 50, root.getPrefHeight() + 10);

			this.capStage = new Stage();
			this.capStage.initModality(Modality.WINDOW_MODAL);
			this.capStage.initOwner(_parentStage);
			this.capStage.setScene(scene);
			this.capStage.setTitle("Gestion des clients");
			this.capStage.setResizable(false);

			this.capViewController = loader.getController();
			this.capViewController.initContext(this.capStage);

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	    
    void doOpenCapteursDialog(){
		this.capViewController.showDialog();
    }


}

