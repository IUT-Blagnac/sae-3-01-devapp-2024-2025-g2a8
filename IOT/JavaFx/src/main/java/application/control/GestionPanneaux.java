package application.control;

import javafx.collections.ObservableList;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.XYChart;
import javafx.scene.control.TableView;
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


	    public void loadLineChart(LineChart<Number, String> lineChart, ObservableList<DataSolarPanel> oListPanneaux) {
        XYChart.Series series = new XYChart.Series<>();
        for (DataSolarPanel dataSolarPanel : oListPanneaux) {
            for (DataEnergy dataEnergy : dataSolarPanel.getEnergy()) {
                series.getData().add(new XYChart.Data<>(dataEnergy.getDate(), dataEnergy.getValue()));
            }
        }
        lineChart.getData().addAll(series);
    }

	public void loadPanneaux(ObservableList<DataSolarPanel> olCapteurs){
        olCapteurs.clear();
        DataLoader dataLoader = new DataLoader();
        dataLoader.LoadDatasFromJson("dataNormal.json");
        List<DataSolarPanel> capteurs = dataLoader.getDataSolarPanel();  
        olCapteurs.addAll(capteurs);
    }

}
