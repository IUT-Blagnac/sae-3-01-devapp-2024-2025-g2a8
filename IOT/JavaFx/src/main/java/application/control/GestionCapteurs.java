package application.control;


import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

import application.loader.DataLoader;
import application.loader.capteursSalle.DataCapteurs;
import application.loader.capteursSalle.DataValue;
import application.view.GestionCapteursViewController;
import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.NumberAxis;
import javafx.scene.chart.XYChart;
import javafx.scene.control.Label;
import javafx.scene.control.Tooltip;
import javafx.scene.layout.BorderPane;
import javafx.scene.layout.GridPane;
import javafx.stage.Modality;
import javafx.stage.Stage;

public class GestionCapteurs {

    private Stage capStage;
    private GestionCapteursViewController capViewController;
    private ObservableList<DataCapteurs> oListCapteurs;


    Thread updateCapteurs;
    private boolean running = true;
    
    
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
    
                this.capStage.setOnCloseRequest(e -> {
                    running = false;
                    System.out.println("Arret du script Update Capteurs");
                    updateCapteurs.interrupt();
                });
    
                this.capViewController = loader.getController();
                this.capViewController.initContext(this.capStage, this);
    
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    
        public void updateCapteurs(ObservableList<DataCapteurs> oListCapteurs) {
            updateCapteurs = new Thread(() -> {      
                while (running) {
                    try {
                        Thread.sleep(5000);
                        
                        if(this.oListCapteurs != null){
                        ObservableList <DataCapteurs> olCapteurs = FXCollections.observableArrayList();
                        this.loadCapteurs(olCapteurs);
                        Platform.runLater(() -> {

                            if (this.oListCapteurs.size() == olCapteurs.size()) {
                                for (int i = 0; i < olCapteurs.size(); i++) {
                                    if (!this.isEqual(this.oListCapteurs.get(i), olCapteurs.get(i), "CO2")) {
                                        this.capViewController.configureData(false);
                                    }
                                }    
                            } else {
                                this.capViewController.configureData(true);
                            }
                        });
                    }
                    

                } catch (InterruptedException e) {
                    e.printStackTrace();
                    Thread.currentThread().interrupt(); // Restore the interrupted status
                }
            }
            
        });
        updateCapteurs.start();
    }

    private boolean isEqual(DataCapteurs capteurs, DataCapteurs olCapteurs, String type) {
        return capteurs.getValues(type).size() == olCapteurs.getValues(type).size();
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


	public LineChart<Number, Number> createLineChart(String yAxisName, String title) {
        
        NumberAxis xAxis = new NumberAxis();
        NumberAxis yAxis = new NumberAxis();
		xAxis.setForceZeroInRange(false);
        xAxis.setLabel("Date");
        yAxis.setLabel(yAxisName);

		xAxis.setTickLabelFormatter(new NumberAxis.DefaultFormatter(xAxis) {
            private final SimpleDateFormat dateFormat = new SimpleDateFormat("dd-MM-yyyy");

            @Override
            public String toString(Number value) {
                return dateFormat.format(new Date(value.longValue()));
            }
        });

        LineChart<Number, Number> lineChart = new LineChart<>(xAxis, yAxis);
        lineChart.setTitle(title);
        return lineChart;
    }

	public void addSeriesToLineChart(DataCapteurs capteurs, String type, String unite, LineChart<Number, Number> lineChartGeneral, GridPane gridPane, int rowIndex) { 

		LineChart<Number, Number> lineChartSeul = this.createLineChart(unite, type+" : "+capteurs.getname());
		this.createSeries(capteurs, type, lineChartSeul, gridPane);
		this.createSeries(capteurs, type, lineChartGeneral, gridPane);
		gridPane.add(lineChartSeul, 1, rowIndex);

    }
     
    private void createSeries(DataCapteurs capteurs, String type, LineChart<Number, Number> lineChart, GridPane gridPane) {
        XYChart.Series<Number, Number> series = new XYChart.Series<>();
        series.setName(type + " : " + capteurs.getname());
        for (DataValue dataValue : capteurs.getValues(type)) {
            series.getData().add(new XYChart.Data<>(dateToMillis(dataValue.getDate()), dataValue.getValue()));
        }
        lineChart.getData().add(series);

		for (XYChart.Data<Number, Number> seriesData : series.getData()) {
			String date = new SimpleDateFormat("dd-MM-yyyy HH:mm:ss").format(new Date(seriesData.getXValue().longValue()));
			seriesData.getNode().setOnMouseClicked(event -> {
				Label label = new Label(type + " : " + seriesData.getYValue() +"\nDate : " + date + "\nSalle : " + capteurs.getname());
				label.setStyle( "-fx-alignment: CENTER; -fx-font-size: 16px;");
				gridPane.getChildren().removeIf(node -> GridPane.getColumnIndex(node) == 2 && GridPane.getRowIndex(node) == 0);
				gridPane.add(label, 2, 0);
			});
			seriesData.getNode().setOnMouseEntered(e -> {
                seriesData.getNode().setStyle("-fx-background-color:#B22222;");
				Tooltip.install(seriesData.getNode(), new Tooltip(type +" : " + seriesData.getYValue() +"\nDate : " + date + "\nSalle :" + capteurs.getname()));

            });
            seriesData.getNode().setOnMouseExited(e -> {
                seriesData.getNode().setStyle("");
            });
		}
    }

	private long dateToMillis(String date) {
        try {
            return new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").parse(date).getTime();
        } catch (Exception e) {
            throw new RuntimeException("Format de date invalide : " + date, e);
        }
    }


}

