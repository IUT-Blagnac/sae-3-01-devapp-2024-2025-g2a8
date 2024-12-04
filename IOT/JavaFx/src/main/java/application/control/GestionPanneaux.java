package application.control;

import javafx.application.Platform;
import javafx.collections.ObservableList;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.XYChart;
import javafx.scene.control.TableView;
import javafx.scene.control.Tooltip;
import javafx.scene.layout.BorderPane;
import javafx.stage.Modality;
import javafx.stage.Stage;

import java.util.List;

import application.loader.DataLoader;
import application.loader.solarPanels.DataEnergy;
import application.loader.solarPanels.DataSolarPanel;
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

    public void updateData(ObservableList<DataSolarPanel> olCapteurs, TableView<DataEnergy> tablePanneau, LineChart<String, Number> lineChart) {
        Thread updatePanneaux = new Thread(() -> {
            
            DataLoader dataLoader = new DataLoader();
            while (true) {
                try {
                    Thread.sleep(1000);
                    dataLoader.LoadDatasFromJson("dataNormal.json");
                    List<DataSolarPanel> capteurs = dataLoader.getDataSolarPanel();
                    System.out.println(olCapteurs.size() + " " + capteurs.size()); 
                    System.out.println(olCapteurs.get(0).getEnergy());
                    if (olCapteurs.size() != capteurs.size()) {
                        Platform.runLater(() -> {
                            lineChart.getData().clear();
                            olCapteurs.clear();
                            olCapteurs.addAll(dataLoader.getDataSolarPanel());
                            loadData(tablePanneau, olCapteurs);
                            loadLineChart(lineChart, olCapteurs);
                        });
                    }
                } catch (InterruptedException e) {
                    e.printStackTrace();
                    Thread.currentThread().interrupt(); // Restore the interrupted status
                }
            }
        });
        updatePanneaux.start();
    }

	    
    void doPanneauxDialog(){
		this.panneauxViewController.showDialog();;
    }


    public void loadData(TableView<DataEnergy> tablePanneau, ObservableList<DataSolarPanel> oListPanneaux) {
        tablePanneau.getItems().clear();
        for (DataSolarPanel dataSolarPanel : oListPanneaux) {
            for (DataEnergy dataEnergy : dataSolarPanel.getEnergy()) {
                DataEnergy dataEnergyVal = new DataEnergy(dataEnergy.getDate(), dataEnergy.getValue());
                tablePanneau.getItems().add(dataEnergyVal);
            }
            
        }
    }


    public void loadLineChart(LineChart<String, Number> lineChart, ObservableList<DataSolarPanel> oListPanneaux) {
        XYChart.Series<String, Number> series = new XYChart.Series<>();
        for (DataSolarPanel dataSolarPanel : oListPanneaux) {
            for (DataEnergy dataEnergy : dataSolarPanel.getEnergy()) {
                series.getData().add(new XYChart.Data<String, Number>(dataEnergy.getDate(), dataEnergy.getValue()));
            }
        }
        lineChart.getData().addAll(series);

    //     for (XYChart.Data<String, Number> dataSolarPanel : series.getData()) {
    //         dataSolarPanel.getNode().setOnMouseEntered(e -> {
    //             dataSolarPanel.getNode().setStyle("-fx-background-color: orange;");
    //             Tooltip.install(dataSolarPanel.getNode(), new Tooltip("Energie : " + dataSolarPanel.getYValue() +"\nDate : " + dataSolarPanel.getXValue()));

    //         });
    //         dataSolarPanel.getNode().setOnMouseExited(e -> {
    //             dataSolarPanel.getNode().setStyle("");

    //         });
            
    //     }
     }

	public void loadPanneaux(ObservableList<DataSolarPanel> olCapteurs){
        olCapteurs.clear();
        DataLoader dataLoader = new DataLoader();
        dataLoader.LoadDatasFromJson("dataNormal.json");
        List<DataSolarPanel> capteurs = dataLoader.getDataSolarPanel();  
        olCapteurs.addAll(capteurs);
    }

}
