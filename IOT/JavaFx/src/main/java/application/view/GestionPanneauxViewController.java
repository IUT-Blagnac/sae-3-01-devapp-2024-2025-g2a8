package application.view;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.XYChart;
import javafx.scene.chart.PieChart.Data;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.shape.Line;
import javafx.stage.Stage;

import java.util.List;

import application.control.GestionPanneaux;
import application.loader.DataLoader;
import application.loader.capteursSalle.DataCapteurs;
import application.loader.solarPanels.DataEnergy;
import application.loader.solarPanels.DataSolarPanel;
import application.model.Donnees;
import application.control.GestionPanneaux;

public class GestionPanneauxViewController {
    private Stage containingStage;
    private GestionPanneaux rockPanneaux;

    private ObservableList<DataSolarPanel> oListPanneaux;

    @FXML
    TableView<DataEnergy> tablePanneau;

    @FXML
    TableColumn<DataEnergy, Double> colValeur;

    @FXML
    TableColumn<DataEnergy, String> colDate;

    @FXML
    LineChart<Number, String> lineChart;


    public void initContext(Stage containingStage, GestionPanneaux _rc){
        this.containingStage = containingStage;
        this.rockPanneaux = _rc;
        this.configure();
    }

    public void showDialog(){
        this.containingStage.showAndWait();
    }

    private void configure(){
        this.oListPanneaux = FXCollections.observableArrayList();
        this.loadPanneaux(oListPanneaux);
        this.loadData();

        colDate.setCellValueFactory(new PropertyValueFactory<>("date"));
        colValeur.setCellValueFactory(new PropertyValueFactory<>("value"));

        this.tablePanneau.setColumnResizePolicy(TableView.CONSTRAINED_RESIZE_POLICY);
        
   
        colDate.setStyle( "-fx-alignment: CENTER; -fx-font-size: 16px;");
        colValeur.setStyle( "-fx-alignment: CENTER;-fx-font-size: 16px;");

        this.loadLineChart();

    }

    private void loadData(){
        this.tablePanneau.getItems().clear();
        for (DataSolarPanel dataSolarPanel : oListPanneaux) {
            for (DataEnergy dataEnergy : dataSolarPanel.getEnergy()) {
                DataEnergy dataEnergyVal = new DataEnergy(dataEnergy.getDate(), dataEnergy.getValue());
                this.tablePanneau.getItems().add(dataEnergyVal);
            }
            
        }
    }

    private void loadPanneaux(ObservableList<DataSolarPanel> olCapteurs){
        olCapteurs.clear();
        DataLoader dataLoader = new DataLoader();
        dataLoader.LoadDatasFromJson("dataNormal.json");
        List<DataSolarPanel> capteurs = dataLoader.getDataSolarPanel();  
        olCapteurs.addAll(capteurs);
    }

    public void loadLineChart(){
        XYChart.Series series = new XYChart.Series();
        for (DataSolarPanel dataSolarPanel : oListPanneaux) {
            for (DataEnergy dataEnergy : dataSolarPanel.getEnergy()) {
                series.getData().add(new XYChart.Data(dataEnergy.getDate(), dataEnergy.getValue()));
            }
        }
        lineChart.getData().addAll(series);
    }
}

