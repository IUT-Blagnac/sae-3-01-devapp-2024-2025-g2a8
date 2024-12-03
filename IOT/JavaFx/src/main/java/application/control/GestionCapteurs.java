package application.control;

import javafx.collections.ObservableList;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.chart.CategoryAxis;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.NumberAxis;
import javafx.scene.layout.BorderPane;
import javafx.stage.Modality;
import javafx.stage.Stage;

import java.util.List;

import application.loader.DataLoader;
import application.loader.capteursSalle.DataCapteurs;
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


	public LineChart<String, Number> loadLineChart(String yAxis, String title) {
        CategoryAxis xAxisC02 = new CategoryAxis();
        NumberAxis yAxisC02 = new NumberAxis();
        xAxisC02.setLabel("Date");
        yAxisC02.setLabel(yAxis);

        LineChart<String, Number> lineChartC02 = new LineChart<>(xAxisC02, yAxisC02);
        lineChartC02.setTitle(title);
        return lineChartC02;
    }
	

	public void loadCapteurs(ObservableList<DataCapteurs> olCapteurs){
        olCapteurs.clear();
        DataLoader dataLoader = new DataLoader();
        dataLoader.LoadDatasFromJson("dataNormal.json");
        List<DataCapteurs> capteurs = dataLoader.getDataLoader();
        olCapteurs.addAll(capteurs);
    }

}

