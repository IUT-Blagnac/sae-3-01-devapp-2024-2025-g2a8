package application.control;

import javafx.collections.ObservableList;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.chart.CategoryAxis;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.NumberAxis;
import javafx.scene.chart.XYChart;
import javafx.scene.control.Label;
import javafx.scene.control.Tooltip;
import javafx.scene.layout.BorderPane;
import javafx.scene.layout.GridPane;
import javafx.stage.Modality;
import javafx.stage.Stage;

import java.util.List;

import application.loader.DataLoader;
import application.loader.capteursSalle.DataCapteurs;
import application.loader.capteursSalle.DataValue;
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



	public void loadCapteurs(ObservableList<DataCapteurs> olCapteurs){
        olCapteurs.clear();
        DataLoader dataLoader = new DataLoader();
        dataLoader.LoadDatasFromJson("dataNormal.json");
        List<DataCapteurs> capteurs = dataLoader.getDataLoader();
        olCapteurs.addAll(capteurs);
    }


	public LineChart<String, Number> createLineChart(String yAxis, String title) {
        CategoryAxis xAxisC02 = new CategoryAxis();
        NumberAxis yAxisC02 = new NumberAxis();
        xAxisC02.setLabel("Date");
        yAxisC02.setLabel(yAxis);

        LineChart<String, Number> lineChartC02 = new LineChart<>(xAxisC02, yAxisC02);
        lineChartC02.setTitle(title);
        return lineChartC02;
    }

	public void addSeriesToLineChart(DataCapteurs capteurs, String type, String unite, LineChart<String, Number> lineChartGeneral, GridPane gridPane, int rowIndex) { 

		LineChart<String, Number> lineChartSeul = this.createLineChart(unite, type+" : "+capteurs.getname());
		this.createSeries(capteurs, type, lineChartSeul, gridPane);
		this.createSeries(capteurs, type, lineChartGeneral, gridPane);
		gridPane.add(lineChartSeul, 1, rowIndex);

    }
     
    private void createSeries(DataCapteurs capteurs, String type, LineChart<String, Number> lineChart, GridPane gridPane) {
        XYChart.Series<String, Number> series = new XYChart.Series<>();
        series.setName(type + " : " + capteurs.getname());
        for (DataValue dataValue : capteurs.getValues(type)) {
            series.getData().add(new XYChart.Data<>(dataValue.getDate(), dataValue.getValue()));
        }
        lineChart.getData().add(series);

		for (XYChart.Data<String, Number> seriesData : series.getData()) {
			seriesData.getNode().setOnMouseClicked(event -> {
				Label label = new Label(type + " : " + seriesData.getYValue() +"\nDate : " + seriesData.getXValue());
				Tooltip.install(seriesData.getNode(), new Tooltip(type + " : " + seriesData.getYValue() +"\nDate : " + seriesData.getXValue()));
				label.setStyle( "-fx-alignment: CENTER; -fx-font-size: 16px;");
				gridPane.getChildren().removeIf(node -> GridPane.getColumnIndex(node) == 2 && GridPane.getRowIndex(node) == 0);
				gridPane.add(label, 2, 0);
			});
		}
    }



}

